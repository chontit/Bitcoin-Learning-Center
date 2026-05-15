<?php
// loan_calculator.php

// --- 1. Simulation Engine (PHP Logic) ---
$loan_amount   = $_POST['loan_amount']   ?? 100000;
$interest_rate = $_POST['interest_rate'] ?? 7.5;
$loan_years    = $_POST['loan_years']    ?? 5;
$dca_amount    = $_POST['dca_amount']    ?? 2000;
$extra_pay     = $_POST['extra_pay']     ?? 0;

$months = $loan_years * 12;
$monthly_rate = ($interest_rate / 100) / 12;

// คำนวณ PMT (ค่างวดมาตรฐานตามสัญญา)
if ($monthly_rate > 0) {
    $pmt = ($loan_amount * $monthly_rate) / (1 - pow(1 + $monthly_rate, -$months));
} else {
    $pmt = $loan_amount / $months;
}

// Function ดึงราคา Bitcoin (ข้อมูลจริง Nov 2021 - Feb 2026)
function getRealBtcPrice($month_idx) {
    $historical_data = [
        2054852, 1673151, // 2021
        1170223, 1288183, 1404324, 1344282, 1041825, 984585, 773204, 795343, 732190, 729261, 597590, 571414, // 2022
        709768, 848123, 936483, 997657, 932769, 1005865, 1078962, 1018988, 924308, 1033060, 1294472, 1513513, // 2023
        1480119, 1681838, 2191616, 2347113, 2416626, 2486140, 2055794, 2046375, 2138876, 2251269, 2363663, 3469250, // 2024
        3513120, 3214900, 2916680, 2762298, 3449836, 3410031, 3553709, 3697388, 3588327, 3675746, 3319080, 2771113, // 2025
        2800188, 2118474 // 2026
    ];

    if (isset($historical_data[$month_idx])) {
        return $historical_data[$month_idx];
    } 
    
    // Future Projection
    $last_known_price = end($historical_data);
    srand($month_idx); 
    $noise = 1 + (rand(-50, 50) / 1000); 
    return $last_known_price * $noise;
}

// Processing Loop
$sim_data = [];
$current_loan = $loan_amount;
$cash = $loan_amount; 
$btc_balance = 0;

// ตัวแปรสำหรับสรุปผลการผ่อนชำระจริง
$total_interest_paid_real = 0; 
$total_payment_real = 0;
$total_extra_paid_real = 0;
$real_payoff_month = $months; 
$is_paid_off = false;

$force_sell_events = 0;
$first_crisis_month = -1; 

for ($i = 0; $i <= $months; $i++) {
    $price = getRealBtcPrice($i);
    
    if ($i == 0) {
        $sim_data[] = [
            'month' => 0, 'price' => $price, 'loan' => $current_loan, 
            'cash' => $cash, 'btc_val' => 0, 'btc_acc' => 0, 'net' => 0, 'force_sell' => false
        ];
        continue;
    }

    // 1. คำนวณดอกเบี้ยของเดือนนี้
    $interest = ($current_loan > 0) ? $current_loan * $monthly_rate : 0;
    
    // 2. กำหนดงวดที่ "ต้องจ่าย" ตามสัญญา (Target)
    $payment_this_month = 0;
    $principal_deducted = 0;
    $extra_this_month = 0;
    
    if ($current_loan > 0) {
        $target_payment = $pmt + $extra_pay;
        // ถ้าหนี้เหลือน้อยกว่ายอดจ่าย ให้จ่ายเท่าที่เหลือ
        if ($target_payment >= ($current_loan + $interest)) {
            $payment_this_month = $current_loan + $interest;
        } else {
            $payment_this_month = $target_payment;
        }
    }

    // 3. เช็คสถานะการเงิน (Cash Flow Check)
    $expense = $payment_this_month + $dca_amount;
    $is_force_sell = false;
    
    // Logic การตัดยอดเงินและหนี้ (ปรับปรุงใหม่เพื่อรองรับกรณีล้มละลาย)
    if ($cash >= $expense) {
        // กรณี 1: เงินสดพอจ่ายหนี้ + DCA
        $cash -= $expense;
        $btc_balance += ($dca_amount / $price);
        
        // ตัดหนี้ปกติ
        $principal_deducted = max(0, $payment_this_month - $interest);
        $current_loan -= $principal_deducted;
        
        // คำนวณ Extra Pay
        $mandatory = min($payment_this_month, $pmt);
        $extra_this_month = max(0, $payment_this_month - $mandatory);

    } elseif ($cash >= $payment_this_month) {
        // กรณี 2: เงินสดพอจ่ายหนี้ แต่ไม่พอ DCA (หยุด DCA)
        $cash -= $payment_this_month;
        if ($cash > 0) { // เศษเงินเหลือ DCA หมด
            $btc_balance += ($cash / $price);
            $cash = 0;
        }
        
        // ตัดหนี้ปกติ
        $principal_deducted = max(0, $payment_this_month - $interest);
        $current_loan -= $principal_deducted;
        $mandatory = min($payment_this_month, $pmt);
        $extra_this_month = max(0, $payment_this_month - $mandatory);

    } else {
        // กรณี 3: เงินสด "ไม่พอจ่ายหนี้" -> ต้องขาย Bitcoin (Crisis)
        if ($first_crisis_month == -1) $first_crisis_month = $i;
        
        $shortfall = $payment_this_month - $cash;
        $btc_value_available = $btc_balance * $price;

        if ($btc_value_available >= $shortfall) {
            // 3.1 ขาย Bitcoin พอปิดยอดขาดดุล (Recoverable Force Sell)
            $btc_needed = $shortfall / $price;
            $btc_balance -= $btc_needed;
            $cash = 0; // เงินสดหมด
            $is_force_sell = true;
            $force_sell_events++;

            // ตัดหนี้ปกติ (เพราะหาเงินมาจ่ายครบ)
            $principal_deducted = max(0, $payment_this_month - $interest);
            $current_loan -= $principal_deducted;
            $mandatory = min($payment_this_month, $pmt);
            $extra_this_month = max(0, $payment_this_month - $mandatory);

        } else {
            // 3.2 ขาย Bitcoin หมดแล้วก็ยังไม่พอ (Bankruptcy / Insolvency)
            $cash_from_btc = $btc_value_available;
            $btc_balance = 0;
            $is_force_sell = true;
            $force_sell_events++;

            // รวมเงินทั้งหมดที่มี (เงินสดเดิม + เงินขาย BTC)
            $total_money_available = $cash + $cash_from_btc;
            $cash = 0;

            // จ่ายเท่าที่มี (จ่ายดอกเบี้ยก่อน)
            $actual_payment = $total_money_available;
            
            if ($actual_payment >= $interest) {
                // จ่ายดอกครบ ตัดต้นได้บางส่วน
                $principal_deducted = $actual_payment - $interest;
                $current_loan -= $principal_deducted;
            } else {
                // ไม่พอจ่ายดอกเบี้ย -> ดอกเบี้ยค้างจ่ายทบต้น (Loan Grows)
                // เพื่อความง่ายใน Sim นี้ จะไม่เพิ่มหนี้ แต่หนี้จะไม่ลดลงเลย
                $principal_deducted = 0;
                // $current_loan += ($interest - $actual_payment); // Uncomment ถ้าอยากให้หนี้งอก
            }
            
            // ปรับยอด Payment ที่บันทึกให้ตรงกับที่จ่ายจริง
            $payment_this_month = $actual_payment;
            $extra_this_month = 0; // ล้มละลายไม่มีโปะ
        }
    }

    // เช็คว่าหนี้หมดหรือยัง
    if ($current_loan <= 0.1 && !$is_paid_off && $i > 0) { // ใช้ 0.1 แก้ปัญหา floating point
        $current_loan = 0;
        $real_payoff_month = $i;
        $is_paid_off = true;
    }

    // บันทึกสถิติรวม
    $total_interest_paid_real += ($payment_this_month >= $interest ? $interest : $payment_this_month);
    $total_payment_real += $payment_this_month;
    $total_extra_paid_real += $extra_this_month;

    $btc_val = $btc_balance * $price;
    $net_worth = ($cash + $btc_val) - $current_loan;

    $sim_data[] = [
        'month' => $i,
        'price' => $price,
        'loan' => $current_loan,
        'cash' => $cash,
        'btc_val' => $btc_val,
        'btc_acc' => $btc_balance,
        'net' => $net_worth,
        'force_sell' => $is_force_sell
    ];
}

$last = end($sim_data);
$roi = ($loan_amount > 0) ? ($last['net'] / $loan_amount) * 100 : 0;
$months_saved = ($is_paid_off) ? ($months - $real_payoff_month) : 0;

// คำนวณสรุปยอดรวม (สำหรับการประมาณการเบื้องต้นในหน้า UI ก่อนคำนวณจริง)
$total_payment_estimate = ($pmt * $months) + ($extra_pay * $months); 
$total_interest_estimate = ($pmt * $months) - $loan_amount;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leverage Simulator | Bitcoin Strategy</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.1.0/dist/chartjs-plugin-annotation.min.js"></script>

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-red: #ff0055;
            --neon-blue: #00e5ff;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(247, 147, 26, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(255, 0, 85, 0.08) 0%, transparent 40%);
            overflow-x: hidden;
        }

        .brand-font { font-family: 'Orbitron', sans-serif; }
        
        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        .neon-box:hover {
            border-color: rgba(247, 147, 26, 0.5);
            box-shadow: 0 0 20px rgba(247, 147, 26, 0.15);
        }

        .btn-neon {
            background: linear-gradient(45deg, #F7931A, #ffb347);
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }
        .btn-neon:hover {
            box-shadow: 0 0 30px #F7931A;
            transform: scale(1.02);
        }

        .input-dark {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: #fff;
            transition: 0.3s;
        }
        
        /* Range Slider Styling */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 18px; width: 18px; border-radius: 50%;
            background: var(--neon-btc); cursor: pointer; margin-top: -7px;
            box-shadow: 0 0 10px var(--neon-btc);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #333; border-radius: 2px;
        }

        .chart-container { position: relative; height: 500px; width: 100%; }
        
        /* Stats Box Coloring Logic */
        .stat-negative { color: #ff0055 !important; }
        .stat-positive { color: #00ff41 !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-brands fa-bitcoin text-5xl text-[#F7931A] animate-pulse drop-shadow-[0_0_10px_rgba(247,147,26,0.6)]"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ff0055] brand-font">
                    LEVERAGE SIMULATOR
                </h1>
                <p class="text-xs text-gray-400">Bitcoin Loan & Cashflow Analysis</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm text-gray-400">
                <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
            </a>
            <a href="#" onclick="window.location.reload()" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#ff0055] hover:text-[#ff0055] transition text-sm text-gray-400">
                <i class="fa-solid fa-rotate"></i> <span class="hidden md:inline">Reset</span>
            </a>
        </div>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 space-y-4">
            <div class="neon-box p-6 rounded-xl h-full">
                <h2 class="text-lg font-bold mb-6 text-[#F7931A] flex items-center gap-2 border-b border-gray-800 pb-2">
                    <i class="fa-solid fa-sliders"></i> กำหนดเงื่อนไขในการกู้ยืม (Contract)
                </h2>
                <form method="POST" id="simForm" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-2 flex justify-between items-end">
                            <span>วงเงินกู้ (บาท)</span>
                            <span class="text-xl font-bold font-mono text-[#F7931A]" id="disp_loan"><?php echo number_format($loan_amount); ?></span>
                        </label>
                        <input type="range" name="loan_amount" id="loan_amount" 
                               min="10000" max="2000000" step="10000" 
                               value="<?php echo $loan_amount; ?>" 
                               class="w-full" 
                               oninput="syncVal('loan_amount', this.value, 'disp_loan')">
                        <div class="flex justify-between text-[10px] text-gray-600 mt-1">
                            <span>10k</span>
                            <span>2M</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">ดอกเบี้ย (%)</label>
                            <input type="number" name="interest_rate" step="0.1" value="<?php echo $interest_rate; ?>" class="w-full p-2 rounded input-dark text-center font-mono text-red-400">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">ระยะเวลา (ปี)</label>
                            <select name="loan_years" class="w-full p-2 rounded input-dark text-center cursor-pointer">
                                <option value="1" <?php echo $loan_years==1?'selected':''; ?>>1 ปี</option>
                                <option value="3" <?php echo $loan_years==3?'selected':''; ?>>3 ปี</option>
                                <option value="5" <?php echo $loan_years==5?'selected':''; ?>>5 ปี</option>
                                <option value="10" <?php echo $loan_years==10?'selected':''; ?>>10 ปี</option>
                                <option value="15" <?php echo $loan_years==15?'selected':''; ?>>15 ปี</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 my-2"></div>

                    <div>
                        <label class="block text-sm text-green-400 mb-1 flex justify-between">
                            <span>กำหนด DCA ต่อเดือน (บาท)</span>
                            <span class="text-xs text-gray-500">เพื่อซื้อ Bitcoin สะสมเพิ่มเติม</span>
                        </label>
                        <input type="number" name="dca_amount" id="dca_amount" value="<?php echo $dca_amount; ?>" class="w-full p-2 rounded input-dark text-right font-mono mb-2 border-green-900/50 focus:border-green-500">
                        <input type="range" min="500" max="50000" step="500" value="<?php echo $dca_amount; ?>" class="w-full accent-green-500" oninput="syncVal('dca_amount', this.value)">
                    </div>

                    <div>
                        <label class="block text-sm text-blue-400 mb-1">โปะเพิ่มต่อเดือนเพื่อตัดเงินต้น (บาท)</label>
                        <input type="number" name="extra_pay" value="<?php echo $extra_pay; ?>" class="w-full p-2 rounded input-dark text-right font-mono border-blue-900/50 focus:border-blue-500">
                    </div>

                    <button type="submit" class="w-full btn-neon py-3 rounded-lg text-sm mt-4 shadow-[0_0_15px_rgba(247,147,26,0.5)]">
                        <i class="fa-solid fa-bolt"></i> คำนวณความเสี่ยง (Let's go)
                    </button>

                    <div class="bg-black/40 p-3 rounded border border-gray-800 space-y-3">
                        <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                            <span class="text-gray-400 text-xs">ค่างวดที่ต้องจ่าย/เดือน:</span>
                            <span class="text-red-400 font-mono text-base font-bold"><?php echo number_format($pmt); ?> ฿</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-1 text-xs border-b border-gray-800 pb-2">
                            <div>
                                <span class="block text-gray-500">ยอดจ่ายจริงรวม</span>
                                <span class="block text-gray-300 font-mono"><?php echo number_format($total_payment_real); ?> ฿</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-gray-500">ดอกเบี้ยจ่ายจริงรวม</span>
                                <span class="block text-gray-300 font-mono"><?php echo number_format($total_interest_paid_real); ?> ฿</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs">
                             <div>
                                <span class="block text-gray-500">เงินโปะเพิ่มรวม</span>
                                <span class="block text-[#00e5ff] font-mono font-bold"><?php echo number_format($total_extra_paid_real); ?> ฿</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-gray-500">ผ่อนหมดเร็วขึ้น</span>
                                <span class="block text-green-400 font-mono font-bold"><?php echo $months_saved; ?> เดือน</span>
                            </div>
                            <div class="col-span-2 text-center bg-gray-900/50 rounded p-1 mt-1 border border-gray-800">
                                <span class="text-gray-500">ระยะเวลาผ่อนจริง: </span>
                                <span class="text-white font-bold"><?php echo $real_payoff_month; ?> เดือน</span>
                                <span class="text-gray-600"> (จาก <?php echo $months; ?>)</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-6">
            
            <div class="neon-box p-4 md:p-6 rounded-xl relative">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <h2 class="text-xl font-bold brand-font text-white">OVERVIEW CHART</h2>
                        <p class="text-xs text-gray-500">Click legend items to toggle visibility</p>
                    </div>
                    <div class="text-xs text-right text-gray-400 space-y-1">
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-3 bg-white rounded-full"></div> ราคา BTC (ขวา)</div>
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-3 bg-[#00ff41] rounded-full"></div> มูลค่าพอร์ต (ซ้าย)</div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <?php if($force_sell_events > 0): ?>
            <div class="p-4 rounded-xl bg-red-900/20 border border-red-500/50 flex items-center gap-4 animate-pulse">
                <div class="text-red-500 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <h3 class="font-bold text-red-400 brand-font">WARNING: LIQUIDITY CRISIS DETECTED</h3>
                    <p class="text-xs text-gray-300">
                        กระแสเงินสดติดลบ <b><?php echo $force_sell_events; ?> ครั้ง</b> 
                        เริ่มตั้งแต่เดือนที่ <b><?php echo $first_crisis_month; ?></b> เป็นต้นไป
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="neon-box p-4 rounded-lg border-l-4 <?php echo $last['net']>=0 ? 'border-[#00ff41]' : 'border-[#ff0055]'; ?>">
                    <div class="text-gray-400 text-xs uppercase mb-1">กำไร/ขาดทุน สุทธิ (Net Worth)</div>
                    <div class="text-2xl font-mono font-bold <?php echo $last['net']>=0 ? 'text-[#00ff41]' : 'text-[#ff0055]'; ?>">
                        <?php echo number_format($last['net']); ?> ฿
                    </div>
                    <div class="text-xs text-gray-500 mt-1">ROI: <?php echo number_format($roi, 2); ?>% (5 ปี)</div>
                </div>
                <div class="neon-box p-4 rounded-lg border-l-4 border-[#F7931A]">
                    <div class="text-[#F7931A] text-xs uppercase mb-1">หนี้คงเหลือ (Debt Balance)</div>
                    <div class="text-2xl font-mono font-bold text-white"><?php echo number_format($last['loan']); ?> ฿</div>
                    <div class="text-xs text-gray-500 mt-1">ดอกเบี้ยจ่ายจริง: <?php echo number_format($total_interest_paid_real); ?> ฿</div>
                </div>
                <div class="neon-box p-4 rounded-lg border-l-4 border-[#00ff41] relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-20"><i class="fa-brands fa-bitcoin text-4xl"></i></div>
                    <div class="text-[#00ff41] text-xs uppercase mb-1">มูลค่าพอร์ต (BTC Value)</div>
                    <div class="text-2xl font-mono font-bold text-white"><?php echo number_format($last['btc_val']); ?> ฿</div>
                    <div class="text-xs text-gray-500 mt-1">จำนวนเหรียญ: <?php echo number_format($btc_balance, 4); ?> BTC</div>
                </div>
            </div>

            <div class="neon-box p-5 rounded-xl bg-gradient-to-r from-gray-900 to-black">
                <h3 class="text-md font-bold text-white mb-2"><i class="fa-solid fa-microchip text-blue-400"></i> AI ANALYSIS</h3>
                <div class="text-sm text-gray-300 leading-relaxed">
                    <?php if($first_crisis_month > -1): ?>
                        <span class="text-[#ff0055]">● <b>CRITICAL POINT:</b></span> จุดวิกฤตสภาพคล่องเกิดขึ้นในเดือนที่ <b><?php echo $first_crisis_month; ?></b> (เส้นแนวตั้งสีแดงในกราฟ) คุณไม่มีเงินสดพอจ่ายหนี้และ Bitcoin ที่สะสมไว้จะถูกทยอยขายเพื่อใช้หนี้ (ยกเว้นว่าคุณสามารถหาเงินอื่นมาใช้หนี้ได้)
                    <?php else: ?>
                        <span class="text-[#00ff41]">● <b>Great Job!</b></span> กระแสเงินสดของคุณเพียงพอตลอดอายุสัญญา ไม่มีการ Force Sell เกิดขึ้น
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-6">
        <p>© 2026 Chollatis Bitcoiner | Don't Trust, Verify.</p>
		<p class="mt-1">ข้อมูลนี้เพื่อการศึกษาเท่านั้น ไม่ใช่คำแนะนำในการลงทุน</p>
    </footer>

    <script>
        function syncVal(inputId, val, displayId = null) {
            const inputEl = document.getElementById(inputId);
            if(inputEl) inputEl.value = val;
            
            if(displayId) {
                const dispEl = document.getElementById(displayId);
                if(dispEl) dispEl.innerText = new Intl.NumberFormat().format(val);
            }
        }

        const ctx = document.getElementById('mainChart').getContext('2d');
        
        let gradGreen = ctx.createLinearGradient(0, 0, 0, 400);
        gradGreen.addColorStop(0, 'rgba(0, 255, 65, 0.4)');
        gradGreen.addColorStop(1, 'rgba(0, 255, 65, 0.0)');
        
        let gradOrange = ctx.createLinearGradient(0, 0, 0, 400);
        gradOrange.addColorStop(0, 'rgba(247, 147, 26, 0.4)');
        gradOrange.addColorStop(1, 'rgba(247, 147, 26, 0.0)');

        const labels = <?php echo json_encode(array_column($sim_data, 'month')); ?>;
        const dataAsset = <?php echo json_encode(array_column($sim_data, 'btc_val')); ?>;
        const dataDebt = <?php echo json_encode(array_column($sim_data, 'loan')); ?>;
        const dataCash = <?php echo json_encode(array_column($sim_data, 'cash')); ?>;
        const dataPrice = <?php echo json_encode(array_column($sim_data, 'price')); ?>;
        const dataBtcAcc = <?php echo json_encode(array_column($sim_data, 'btc_acc')); ?>;
        const firstCrisisMonth = <?php echo $first_crisis_month; ?>; 

        let annotations = {};
        if (firstCrisisMonth > -1) {
            annotations = {
                line1: {
                    type: 'line',
                    xMin: firstCrisisMonth,
                    xMax: firstCrisisMonth,
                    borderColor: 'rgb(255, 0, 85)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    label: {
                        display: true,
                        content: 'LIQUIDITY CRISIS',
                        color: 'white',
                        backgroundColor: 'rgba(255, 0, 85, 0.8)',
                        position: 'start',
                        font: { size: 10 }
                    }
                }
            };
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Asset Value (THB)',
                        data: dataAsset,
                        borderColor: '#00ff41', 
                        backgroundColor: gradGreen,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        yAxisID: 'y',
                        order: 2
                    },
                    {
                        label: 'Bitcoin Price (THB)',
                        data: dataPrice,
                        borderColor: '#ffffff',
                        backgroundColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1.5,
                        borderDash: [2, 2],
                        fill: false,
                        tension: 0.3,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        yAxisID: 'y1',
                        order: 1
                    },
                    {
                        label: 'Debt Balance (THB)',
                        data: dataDebt,
                        borderColor: '#F7931A', 
                        backgroundColor: gradOrange,
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        yAxisID: 'y',
                        order: 3
                    },
                    {
                        label: 'Cash Flow (THB)',
                        data: dataCash,
                        borderColor: '#00e5ff', 
                        borderWidth: 1,
                        tension: 0.2,
                        pointRadius: 0,
                        yAxisID: 'y',
                        hidden: false, 
                        order: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { 
                        display: true,
                        labels: { color: '#999', usePointStyle: true },
                    }, 
                    tooltip: {
                        backgroundColor: 'rgba(5, 5, 5, 0.95)',
                        titleColor: '#F7931A',
                        bodyColor: '#fff',
                        borderColor: '#333',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let val = context.parsed.y;
                                let formattedVal = new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB', maximumFractionDigits: 0 }).format(val);
                                return label + ': ' + formattedVal;
                            },
                            afterBody: function(tooltipItems) {
                                let idx = tooltipItems[0].dataIndex;
                                let btcAmount = dataBtcAcc[idx];
                                return '\n💰 Accumulated: ' + btcAmount.toFixed(6) + ' BTC';
                            }
                        }
                    },
                    annotation: {
                        annotations: annotations
                    }
                },
                scales: {
                    x: { grid: { color: '#222' }, ticks: { color: '#666', maxTicksLimit: 12 } },
                    y: { 
                        type: 'linear', display: true, position: 'left',
                        grid: { color: '#222' }, ticks: { color: '#00ff41' },
                        title: { display: true, text: 'Portfolio / Debt (THB)', color: '#666' }
                    },
                    y1: { 
                        type: 'linear', display: true, position: 'right',
                        grid: { drawOnChartArea: false }, ticks: { color: '#fff' },
                        title: { display: true, text: 'Bitcoin Price (THB)', color: '#999' }
                    }
                }
            }
        });
    </script>
</body>
</html>