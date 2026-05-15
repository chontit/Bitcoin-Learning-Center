<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Loan Simulator | Wealth Insight</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💳</text></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --neon-blue: #00f3ff;
            --neon-green: #00ff41;
            --neon-red: #ff0055;
            --neon-yellow: #ffd700;
            --neon-purple: #a855f7;
            --neon-orange: #ff5500;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(0, 243, 255, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 255, 65, 0.05) 0%, transparent 25%);
            overflow-x: hidden;
        }

        .brand-font { font-family: 'Orbitron', sans-serif; }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 4px; }

        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .input-dark {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: #fff;
            transition: 0.3s;
        }
        .input-dark:focus {
            outline: none;
            border-color: var(--neon-blue);
            box-shadow: 0 0 5px var(--neon-blue);
        }

        .toggle-checkbox:checked { right: 0; border-color: var(--neon-green); }
        .toggle-checkbox:checked + .toggle-label { background-color: var(--neon-green); }
        
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 14px; width: 14px; border-radius: 50%;
            cursor: pointer; margin-top: -5px; transition: 0.2s;
        }
        input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.2); }
        input[type=range].slider-blue::-webkit-slider-thumb { background: var(--neon-blue); box-shadow: 0 0 10px var(--neon-blue); }
        input[type=range].slider-green::-webkit-slider-thumb { background: var(--neon-green); box-shadow: 0 0 10px var(--neon-green); }
        input[type=range].slider-purple::-webkit-slider-thumb { background: var(--neon-purple); box-shadow: 0 0 10px var(--neon-purple); }
        input[type=range].slider-orange::-webkit-slider-thumb { background: var(--neon-orange); box-shadow: 0 0 10px var(--neon-orange); }
        input[type=range].slider-white::-webkit-slider-thumb { background: #fff; box-shadow: 0 0 10px #fff; }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #333; border-radius: 2px;
        }

        .table-container { max-height: 400px; overflow-y: auto; }
        .table-container th { position: sticky; top: 0; background-color: #111; z-index: 10; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-3 border-b border-gray-800 flex justify-between items-center bg-black/80 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-house-chimney text-2xl text-[#00f3ff] animate-pulse"></i>
            <div>
                <h1 class="text-lg md:text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#00f3ff] to-[#00ff41] brand-font">
                    Mastering Debt Simulation
                </h1>
                <div class="text-[10px] uppercase font-bold tracking-wider text-gray-400">
                    Interactive Loan Strategies
                </div>
            </div>
        </div>
        
        <a href="/" title="กลับสู่หน้าหลัก Bitcoin Learning Center by Chollatis Bitcoiner" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-700 hover:border-[#00f3ff] text-gray-400 hover:text-[#00f3ff] transition-all text-xs font-bold bg-black/50 hover:bg-[#00f3ff]/10 shadow-sm hover:shadow-[0_0_15px_rgba(0,243,255,0.2)]">
            <i class="fa-solid fa-reply"></i> <span class="hidden md:inline uppercase tracking-wider">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-4 grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <div class="lg:col-span-4 space-y-4">
            <div class="neon-box p-4 rounded-xl border-t-4 border-[#00f3ff]">
                
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2 border-b border-gray-700 pb-1">1. ข้อมูลสินเชื่อพื้นฐาน</h3>
                <div class="mb-4">
                    <label class="block text-xs text-white mb-1">ยอดจัด / วงเงินกู้ (บาท)</label>
                    <input type="text" inputmode="numeric" id="principal" value="1,000,000" class="w-full p-2 rounded input-dark text-base font-mono text-[#00f3ff] font-bold" oninput="formatNumberInput(this); syncSlider('principal'); runSimulation()">
                    <input type="range" id="principal_slider" min="50000" max="10000000" step="50000" value="3000000" class="w-full mt-2 slider-blue" oninput="syncInput('principal'); runSimulation()">
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">ระยะเวลา (ปี)</label>
                        <select id="years" class="w-full p-2 rounded input-dark text-xs" onchange="updatePayoffMax(); runSimulation()">
                            <option value="3">3 ปี</option>
                            <option value="5">5 ปี (รถยนต์)</option>
                            <option value="10" selected>10 ปี</option>
                            <option value="20">20 ปี (บ้าน)</option>
                            <option value="30">30 ปี (บ้าน)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">เทียบ Flat Rate (%)</label>
                        <input type="number" id="flat_rate" value="3.25" step="0.1" class="w-full p-2 rounded input-dark text-xs font-mono text-[#ffd700]" oninput="runSimulation()">
                    </div>
                </div>

                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2 border-b border-gray-700 pb-1 mt-4">2. ดอกเบี้ยลดต้นลดดอก (แบบขั้นบันได)</h3>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">โปรโมชั่น 3 ปีแรก (%)</label>
                        <input type="number" id="promo_rate" value="3.25" step="0.1" class="w-full p-2 rounded input-dark text-xs font-mono" oninput="runSimulation()">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">ปกติ/MRR ปีที่ 4+ (%)</label>
                        <input type="number" id="mrr_rate" value="6.5" step="0.1" class="w-full p-2 rounded input-dark text-xs font-mono" oninput="runSimulation()">
                    </div>
                </div>
                
                <div class="flex justify-between items-center bg-[#ff0055]/10 p-2 rounded border border-[#ff0055]/30 mb-4">
                    <span class="text-[10px] text-gray-300">เฉลี่ยตลอดสัญญา <span id="avg_years_display"></span>:</span>
                    <span class="text-sm font-mono font-bold text-[#ff0055]" id="avg_rate_display">0.00%</span>
                </div>

                <h3 class="text-xs font-bold text-[#00ff41] uppercase mb-2 border-b border-[#00ff41]/30 pb-1 mt-4 flex items-center gap-2">
                    <i class="fa-solid fa-bolt"></i> 3. กลยุทธ์พิชิตหนี้
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center gap-2 text-xs text-white mb-2 cursor-pointer hover:text-[#00ff41] transition w-fit">
                            <input type="checkbox" id="chk_extra_monthly" class="rounded w-4 h-4 accent-[#00ff41] cursor-pointer" onchange="toggleState('extra_monthly'); runSimulation()">
                            <span>โปะเพิ่มทุกเดือน (บาท)</span>
                        </label>
                        <div id="wrap_extra_monthly" class="transition-opacity opacity-50 pointer-events-none pl-6">
                            <input type="text" inputmode="numeric" id="extra_monthly" value="2,000" class="w-full p-2 rounded input-dark text-sm font-mono text-[#00ff41]" oninput="formatNumberInput(this); syncSlider('extra_monthly'); runSimulation()">
                            <input type="range" id="extra_monthly_slider" min="500" max="50000" step="500" value="2000" class="w-full mt-2 slider-green" oninput="syncInput('extra_monthly'); runSimulation()">
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-3">
                        <label class="flex items-center gap-2 text-xs text-white mb-2 cursor-pointer hover:text-[#00ff41] transition w-fit">
                            <input type="checkbox" id="chk_extra_yearly" class="rounded w-4 h-4 accent-[#00ff41] cursor-pointer" onchange="toggleState('extra_yearly'); runSimulation()">
                            <span>โปะเงินก้อนรายปี (บาท)</span>
                        </label>
                        <div id="wrap_extra_yearly" class="grid grid-cols-3 gap-2 pl-6 transition-opacity opacity-50 pointer-events-none">
                            <div class="col-span-2">
                                <input type="text" inputmode="numeric" id="extra_yearly" value="50,000" class="w-full p-2 rounded input-dark text-sm font-mono text-[#00ff41]" oninput="formatNumberInput(this); syncSlider('extra_yearly'); runSimulation()">
                                <input type="range" id="extra_yearly_slider" min="5000" max="500000" step="5000" value="50000" class="w-full mt-2 slider-green" oninput="syncInput('extra_yearly'); runSimulation()">
                            </div>
                            <div class="col-span-1">
                                <select id="bonus_month" class="w-full p-2 rounded input-dark text-xs h-[38px]" onchange="runSimulation()">
								    <option value="1">เดือน 1</option>
                                    <option value="6">เดือน 6</option>
                                    <option value="12" selected>เดือน 12</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-3">
                        <label class="block text-xs text-white mb-1">ตั้งเป้าปิดยอดหนี้ทั้งหมด (เดือนที่)</label>
                        <div class="flex items-center gap-2 mb-1">
                            <input type="number" id="payoff_month" placeholder="เช่น 60" value="0" min="0" class="w-24 p-2 rounded input-dark text-sm font-mono text-white text-center" oninput="syncSlider('payoff_month'); runSimulation()">
                            <span class="text-[10px] text-gray-400">ใส่ 0 = ไม่กำหนด / ลากแถบเพื่อเลือกเดือน</span>
                        </div>
                        <input type="range" id="payoff_month_slider" min="0" max="120" step="1" value="0" class="w-full mt-2 slider-white" oninput="syncInputRaw('payoff_month'); runSimulation()">
                    </div>

                    <div class="flex items-center justify-between mt-3 bg-[#00f3ff]/10 p-2 rounded border border-[#00f3ff]/30">
                        <label class="text-xs font-bold text-[#00f3ff] cursor-pointer" for="refinance_toggle">
                            รีไฟแนนซ์ทุก 3 ปี
                        </label>
                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                            <input type="checkbox" name="toggle" id="refinance_toggle" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer" onchange="runSimulation()"/>
                            <label for="refinance_toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-600 cursor-pointer"></label>
                        </div>
                    </div>
                </div>

                <h3 class="text-xs font-bold text-[#a855f7] uppercase mb-2 border-b border-[#a855f7]/30 pb-1 mt-6 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced"></i> 4. เปรียบเทียบสินเชื่อมหาโหด
                </h3>
                <div class="space-y-2 mt-2 bg-gray-900/50 p-2 rounded border border-gray-700">
                    
                    <div class="flex flex-col">
                        <label class="flex items-center gap-2 text-xs text-white cursor-pointer hover:bg-gray-800 p-1 rounded transition">
                            <input type="checkbox" id="chk_credit_card" class="rounded w-4 h-4 accent-[#a855f7]" onchange="toggleCC()">
                            <span>💳 บัตรเครดิต (ขั้นต่ำ <span id="cc_label_pct" class="text-[#a855f7] font-bold">10%</span>)</span>
                        </label>
                        <div id="wrap_cc_pct" class="pl-7 pr-2 mt-1 hidden mb-2 transition-all">
                            <div class="flex justify-between text-[10px] text-gray-400 mb-1">
                                <span>ปรับเปอร์เซ็นต์ยอดผ่อน</span>
                                <span>(ระบบจะดักขั้นต่ำสุดที่ 500 ฿)</span>
                            </div>
                            <input type="range" id="cc_min_pct" min="1" max="10" step="1" value="10" class="w-full slider-purple" oninput="document.getElementById('cc_label_pct').innerText = this.value + '%'; runSimulation()">
                        </div>
                    </div>

                    <div class="flex flex-col mt-1 border-t border-gray-800 pt-2">
                        <label class="flex items-center gap-2 text-xs text-white cursor-pointer hover:bg-gray-800 p-1 rounded transition">
                            <input type="checkbox" id="chk_informal" class="rounded w-4 h-4 accent-[#ff5500]" onchange="toggleInf()">
                            <span>⚠️ กู้นอกระบบ (ดอกเบี้ย 20% <span class="text-[#ff5500] font-bold underline">ต่อเดือน!</span>)</span>
                        </label>
                        <div id="wrap_inf_pct" class="pl-7 pr-2 mt-1 hidden mb-2 transition-all">
                            <div class="flex justify-between text-[10px] text-gray-400 mb-1">
                                <span>โปะเงินต้น (เปอร์เซ็นต์ของยอดกู้)</span>
                                <span id="inf_label_pct" class="text-[#ff5500] font-bold">0%</span>
                            </div>
                            <input type="range" id="inf_prin_pct" min="0" max="20" step="1" value="0" class="w-full slider-orange" oninput="document.getElementById('inf_label_pct').innerText = this.value + '%'; runSimulation()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-4">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="neon-box p-3 rounded-lg border-t-2 border-[#ffd700] flex flex-col justify-between">
                    <div class="text-[11px] font-bold text-gray-300 mb-1">Flat Rate (คงที่)</div>
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[10px] text-gray-500">ดอกเบี้ยรวม:</span>
                        <span class="text-sm font-mono font-bold text-[#ffd700]" id="res_flat_int">0</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-gray-500">จ่ายสุทธิ:</span>
                        <span class="text-sm font-mono font-bold text-white" id="res_flat_total">0</span>
                    </div>
                </div>

                <div class="neon-box p-3 rounded-lg border-t-2 border-[#ff0055] flex flex-col justify-between">
                    <div class="text-[11px] font-bold text-gray-300 mb-1">Effective (ผ่อนปกติ)</div>
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[10px] text-gray-500">ดอกเบี้ยรวม:</span>
                        <span class="text-sm font-mono font-bold text-[#ff0055]" id="res_eff_int">0</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-gray-500">จ่ายสุทธิ:</span>
                        <span class="text-sm font-mono font-bold text-white" id="res_eff_total">0</span>
                    </div>
                </div>

                <div class="neon-box p-3 rounded-lg border-t-2 border-[#00ff41] bg-[#00ff41]/5 flex flex-col justify-between">
                    <div class="text-[11px] font-bold text-[#00ff41] mb-1" id="strat_title">ใช้กลยุทธ์ (ประหยัดได้)</div>
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[10px] text-gray-500">ดอกเบี้ยรวม:</span>
                        <span class="text-sm font-mono font-bold text-[#00ff41]" id="res_strat_int">0</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-gray-500">จ่ายสุทธิ:</span>
                        <span class="text-sm font-mono font-bold text-white" id="res_strat_total">0</span>
                    </div>
                </div>
            </div>

            <div id="danger_summaries" class="grid grid-cols-1 md:grid-cols-2 gap-3 hidden">
                <div id="box_cc" class="neon-box p-3 rounded-lg border-t-2 border-[#a855f7] flex-col justify-between hidden bg-[#a855f7]/5">
                    <div class="text-[11px] font-bold text-[#a855f7] mb-1" id="box_cc_title">💳 บัตรเครดิต (ขั้นต่ำ 10%)</div>
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[10px] text-gray-400">ดอกเบี้ยรวม:</span>
                        <span class="text-sm font-mono font-bold text-[#a855f7]" id="res_cc_int">0</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-gray-400">ยอดจ่ายสุทธิรวม:</span>
                        <span class="text-sm font-mono font-bold text-white" id="res_cc_total">0</span>
                    </div>
                </div>
                
                <div id="box_inf" class="neon-box p-3 rounded-lg border-t-2 border-[#ff5500] flex-col justify-between hidden bg-[#ff5500]/5">
                    <div class="text-[11px] font-bold text-[#ff5500] mb-1" id="box_inf_title">⚠️ นอกระบบ (จ่ายแค่ดอกเบี้ย)</div>
                    <div class="flex justify-between items-end mt-1">
                        <span class="text-[10px] text-gray-400" id="lbl_inf_paid">ยอดจ่ายทิ้งรวม (ดอกเบี้ยล้วน):</span>
                        <span class="text-sm font-mono font-bold text-[#ff5500]" id="res_inf_paid">0</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-gray-400" id="lbl_inf_bal">ต้นคงเหลือ (ไม่ลดเลย):</span>
                        <span class="text-sm font-mono font-bold text-white" id="res_inf_bal">0</span>
                    </div>
                </div>
            </div>

            <div class="neon-box p-4 rounded-xl relative h-[600px]">
                <canvas id="loanChart"></canvas>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3 neon-box p-4 rounded-xl bg-gradient-to-r from-gray-900 to-black border-l-4 border-[#00ff41]" id="narrative_box">
                    <div id="narrative_text" class="text-xs text-gray-300 leading-6 font-light">
                        กำลังคำนวณผลลัพธ์...
                    </div>
                </div>
                <div class="md:col-span-1 flex flex-col justify-center">
                    <button onclick="toggleTable()" class="w-full py-3 rounded-lg bg-gray-800 hover:bg-gray-700 text-white text-sm font-bold border border-gray-600 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-table-list"></i> ดูตารางผ่อนชำระ
                    </button>
                </div>
            </div>

            <div id="table_section" class="hidden neon-box rounded-xl overflow-hidden mt-2">
                <div class="bg-[#111] p-3 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-[#00f3ff]">Amortization Schedule (ตารางตามกลยุทธ์)</h3>
                    <div class="text-[10px] text-gray-400">คำนวณค่างวดพื้นฐานที่: <span id="base_pmt_display" class="font-mono text-white">0</span> บาท/เดือน</div>
                </div>
                <div class="table-container p-0">
                    <table class="w-full text-right text-[11px] font-mono">
                        <thead>
                            <tr class="text-gray-400 bg-gray-900/80">
                                <th class="p-2 text-center">งวด (ปี)</th>
                                <th class="p-2">ดอกเบี้ย %</th>
                                <th class="p-2">จ่ายจริง</th>
                                <th class="p-2 text-[#ff0055]">ตัดดอกเบี้ย</th>
                                <th class="p-2 text-[#00ff41]">ตัดต้น</th>
                                <th class="p-2">เงินต้นคงเหลือ</th>
                            </tr>
                        </thead>
                        <tbody id="table_body" class="text-gray-300 divide-y divide-gray-800/50">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        let loanChart = null;

        function formatNumberInput(input) {
            let val = input.value.replace(/,/g, '').replace(/\D/g, ''); 
            if(val === '') { input.value = ''; return; }
            input.value = new Intl.NumberFormat('th-TH').format(parseInt(val));
        }

        function getRawValue(id) {
            let val = document.getElementById(id).value.replace(/,/g, '');
            return parseFloat(val) || 0;
        }

        function syncSlider(id) {
            let val = getRawValue(id);
            let slider = document.getElementById(id + '_slider');
            if (slider) {
                if(val > slider.max) slider.value = slider.max;
                else if(val < slider.min) slider.value = slider.min;
                else slider.value = val;
            }
        }

        function syncInput(id) {
            let slider = document.getElementById(id + '_slider');
            let input = document.getElementById(id);
            if (slider && input) {
                input.value = new Intl.NumberFormat('th-TH').format(slider.value);
            }
        }

        function syncInputRaw(id) {
            let slider = document.getElementById(id + '_slider');
            let input = document.getElementById(id);
            if (slider && input) {
                input.value = slider.value;
            }
        }

        function toggleState(id) {
            let chk = document.getElementById('chk_' + id);
            let wrap = document.getElementById('wrap_' + id);
            if(chk && wrap) {
                if(chk.checked) {
                    wrap.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    wrap.classList.add('opacity-50', 'pointer-events-none');
                }
            }
        }

        function toggleCC() {
            const chk = document.getElementById('chk_credit_card');
            const wrap = document.getElementById('wrap_cc_pct');
            if(chk.checked) {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
            }
            runSimulation();
        }

        function toggleInf() {
            const chk = document.getElementById('chk_informal');
            const wrap = document.getElementById('wrap_inf_pct');
            if(chk.checked) {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
            }
            runSimulation();
        }

        function updatePayoffMax() {
            let years = parseInt(document.getElementById('years').value);
            let totalMonths = years * 12;
            let slider = document.getElementById('payoff_month_slider');
            let input = document.getElementById('payoff_month');
            
            slider.max = totalMonths;
            let currentVal = parseInt(input.value) || 0;
            if(currentVal > totalMonths) {
                input.value = totalMonths;
                slider.value = totalMonths;
            }
        }

        function fmtMoney(n) {
            return new Intl.NumberFormat('th-TH', { maximumFractionDigits: 0 }).format(n);
        }
        
        function findExactPMT(P, totalMonths, promoRate, mrrRate) {
            let low = P / totalMonths;
            let maxRate = Math.max(promoRate, mrrRate);
            let rMax = (maxRate / 100) / 12;
            let high = (rMax > 0) ? (P * rMax) / (1 - Math.pow(1 + rMax, -totalMonths)) : low; 
            
            if (promoRate === 0 && mrrRate === 0) return low;

            let pmt = (low + high) / 2;
            
            for (let i = 0; i < 50; i++) {
                let bal = P;
                for (let m = 1; m <= totalMonths; m++) {
                    let currentRate = (m <= 36) ? promoRate : mrrRate;
                    let rMonth = (currentRate / 100) / 12;
                    bal = bal * (1 + rMonth) - pmt;
                }
                
                if (bal > 0) {
                    low = pmt; 
                } else {
                    high = pmt; 
                }
                pmt = (low + high) / 2;
            }
            return pmt;
        }

        function toggleTable() {
            const tbl = document.getElementById('table_section');
            tbl.classList.toggle('hidden');
        }

        function runSimulation() {
            const P = getRawValue('principal');
            if (P <= 0) return;
            
            const years = parseInt(document.getElementById('years').value);
            const totalMonths = years * 12;

            const flatRate = parseFloat(document.getElementById('flat_rate').value) || 0;
            const promoRate = parseFloat(document.getElementById('promo_rate').value) || 0;
            const mrrRate = parseFloat(document.getElementById('mrr_rate').value) || 0;

            // --- คำนวณดอกเบี้ยเฉลี่ยตลอดสัญญา ---
            let avgRate = 0;
            if (years <= 3) {
                avgRate = promoRate;
            } else {
                avgRate = ((promoRate * 3) + (mrrRate * (years - 3))) / years;
            }
            document.getElementById('avg_years_display').innerText = `(${years} ปี)`;
            document.getElementById('avg_rate_display').innerText = avgRate.toFixed(2) + '%';

            const extraMonthly = document.getElementById('chk_extra_monthly').checked ? getRawValue('extra_monthly') : 0;
            const extraYearly = document.getElementById('chk_extra_yearly').checked ? getRawValue('extra_yearly') : 0;
            
            const bonusMonth = parseInt(document.getElementById('bonus_month').value) || 12;
            const payoffMonth = parseInt(document.getElementById('payoff_month').value) || 0;
            
            const isRefinance = document.getElementById('refinance_toggle').checked;
            
            const showCC = document.getElementById('chk_credit_card').checked;
            const ccPctValue = parseInt(document.getElementById('cc_min_pct').value) || 10;
            const ccPct = ccPctValue / 100; 

            const showInf = document.getElementById('chk_informal').checked;
            const infPrinPctValue = parseInt(document.getElementById('inf_prin_pct').value) || 0;
            const infPrinPct = infPrinPctValue / 100;

            const basePMT = findExactPMT(P, totalMonths, promoRate, mrrRate);
            document.getElementById('base_pmt_display').innerText = fmtMoney(basePMT);

            let flatData = [P], flatDetails = [{ payment: 0, interest: 0, principal: 0, balance: P }];
            let effNormalData = [P], effDetails = [{ payment: 0, interest: 0, principal: 0, balance: P }];
            let stratData = [P], stratDetails = [{ payment: 0, interest: 0, principal: 0, balance: P }];
            let ccData = [P], ccDetails = [{ payment: 0, interest: 0, principal: 0, balance: P }];
            let infData = [P], infDetails = [{ payment: 0, interest: 0, principal: 0, balance: P }];

            let effNormalIntTotal = 0, stratIntTotal = 0;
            let balNormal = P, balStrat = P, balCC = P, balInf = P;
            let ccIntTotal = 0, ccTotalPayment = 0;
            let infIntTotal = 0, infTotalPayment = 0;
            
            let stratMonthsElapsed = 0;
            let tableHTML = '';
            let isPayoffTriggered = false;

            const flatTotalInterest = P * (flatRate / 100) * years;
            const flatTotalPayment = P + flatTotalInterest;
            const flatMonthlyPay = flatTotalPayment / totalMonths;

            const maxPlotMonths = totalMonths; 
            
            for (let i = 1; i <= maxPlotMonths; i++) {
                
                // 1. Flat Rate
                if (i <= totalMonths) {
                    let bal = Math.max(0, P - (P / totalMonths) * i);
                    flatData.push(bal);
                    flatDetails.push({ payment: flatMonthlyPay, interest: flatTotalInterest / totalMonths, principal: P / totalMonths, balance: bal });
                } else {
                    flatData.push(0); flatDetails.push({ payment: 0, interest: 0, principal: 0, balance: 0 });
                }

                // 2. Effective Normal
                if (balNormal > 0 && i <= totalMonths) {
                    const currentRate = (i <= 36) ? promoRate : mrrRate;
                    let interestThisMonth = balNormal * ((currentRate / 100) / 12);
                    effNormalIntTotal += interestThisMonth;
                    let principalPaid = basePMT - interestThisMonth;
                    balNormal = Math.max(0, balNormal - principalPaid);
                    
                    effNormalData.push(balNormal);
                    effDetails.push({ payment: basePMT, interest: interestThisMonth, principal: principalPaid, balance: balNormal });
                } else {
                    effNormalData.push(0); effDetails.push({ payment: 0, interest: 0, principal: 0, balance: 0 });
                }

                // 3. กลยุทธ์
                if (balStrat > 0) {
                    stratMonthsElapsed = i;
                    let currentRate = isRefinance ? (( (i - 1) % 36 + 1 <= 36) ? promoRate : mrrRate) : ((i <= 36) ? promoRate : mrrRate);
                    let interestThisMonth = balStrat * ((currentRate / 100) / 12);
                    let paymentThisMonth = basePMT + extraMonthly;
                    
                    if (extraYearly > 0 && (i % 12 === bonusMonth % 12)) paymentThisMonth += extraYearly;
                    if (payoffMonth > 0 && i === payoffMonth) { paymentThisMonth = balStrat + interestThisMonth; isPayoffTriggered = true; }
                    if (paymentThisMonth > balStrat + interestThisMonth) paymentThisMonth = balStrat + interestThisMonth;

                    stratIntTotal += interestThisMonth;
                    let principalPaid = paymentThisMonth - interestThisMonth;
                    balStrat = Math.max(0, balStrat - principalPaid);
                    
                    stratData.push(balStrat);
                    stratDetails.push({ payment: paymentThisMonth, interest: interestThisMonth, principal: principalPaid, balance: balStrat });

                    const rowClass = (i % 12 === 0 || isPayoffTriggered) ? 'bg-gray-800/30' : '';
                    const yearText = (i % 12 === 0) ? `(ปี ${i/12})` : '';
                    const payoffBadge = isPayoffTriggered ? '<span class="text-[9px] bg-red-500 text-white px-1 rounded ml-1">ปิดยอด</span>' : '';
                    tableHTML += `
                        <tr class="${rowClass} hover:bg-gray-800 transition">
                            <td class="p-2 text-center text-gray-400">${i} ${yearText}</td>
                            <td class="p-2">${currentRate}%</td>
                            <td class="p-2 text-white">${fmtMoney(paymentThisMonth)} ${payoffBadge}</td>
                            <td class="p-2 text-[#ff0055] opacity-80">${fmtMoney(interestThisMonth)}</td>
                            <td class="p-2 text-[#00ff41]">${fmtMoney(principalPaid)}</td>
                            <td class="p-2 font-bold">${fmtMoney(balStrat)}</td>
                        </tr>
                    `;
                } else {
                    stratData.push(0); stratDetails.push({ payment: 0, interest: 0, principal: 0, balance: 0 });
                }

                // 4. บัตรเครดิต
                if (showCC) {
                    if (balCC > 0) { 
                        let intCC = balCC * (16 / 100 / 12); 
                        let paymentCC = Math.max((balCC + intCC) * ccPct, 500); 
                        
                        if (paymentCC > balCC + intCC) {
                            paymentCC = balCC + intCC;
                        }

                        let prinCC = paymentCC - intCC;
                        balCC -= prinCC; 
                        
                        if (balCC < 0) { prinCC += balCC; balCC = 0; }
                        
                        ccIntTotal += intCC;
                        ccTotalPayment += paymentCC;

                        ccData.push(balCC);
                        ccDetails.push({ payment: paymentCC, interest: intCC, principal: prinCC, balance: balCC });
                    } else {
                        ccData.push(0); 
                        ccDetails.push({ payment: 0, interest: 0, principal: 0, balance: 0 });
                    }
                }

                // 5. กู้นอกระบบ
                if (showInf) {
                    if (balInf > 0) { 
                        let intInf = balInf * 0.20; 
                        let prinInf = P * infPrinPct; 
                        
                        if (prinInf > balInf) prinInf = balInf; 
                        
                        let paymentInf = intInf + prinInf; 
                        balInf -= prinInf; 
                        
                        infIntTotal += intInf;
                        infTotalPayment += paymentInf;
                        
                        infData.push(balInf);
                        infDetails.push({ payment: paymentInf, interest: intInf, principal: prinInf, balance: balInf });
                    } else {
                        infData.push(0); 
                        infDetails.push({ payment: 0, interest: 0, principal: 0, balance: 0 });
                    }
                }
            }

            document.getElementById('table_body').innerHTML = tableHTML;

            // --- Update UI ---
            document.getElementById('res_flat_int').innerText = fmtMoney(flatTotalInterest);
            document.getElementById('res_flat_total').innerText = fmtMoney(flatTotalPayment);
            
            const effNormalTotalPayment = P + effNormalIntTotal;
            document.getElementById('res_eff_int').innerText = fmtMoney(effNormalIntTotal);
            document.getElementById('res_eff_total').innerText = fmtMoney(effNormalTotalPayment);
            
            const stratTotalPayment = P + stratIntTotal;
            document.getElementById('res_strat_int').innerText = fmtMoney(stratIntTotal);
            document.getElementById('res_strat_total').innerText = fmtMoney(stratTotalPayment);

            const dangerContainer = document.getElementById('danger_summaries');
            dangerContainer.classList.toggle('hidden', !(showCC || showInf));
            
            const boxCC = document.getElementById('box_cc');
            if (showCC) {
                boxCC.classList.remove('hidden'); boxCC.classList.add('flex');
                document.getElementById('box_cc_title').innerText = '💳 บัตรเครดิต (ขั้นต่ำ ' + ccPctValue + '%)';
                document.getElementById('res_cc_int').innerText = fmtMoney(ccIntTotal);
                document.getElementById('res_cc_total').innerText = fmtMoney(ccTotalPayment);
            } else {
                boxCC.classList.add('hidden'); boxCC.classList.remove('flex');
            }

            const boxInf = document.getElementById('box_inf');
            if (showInf) {
                boxInf.classList.remove('hidden'); boxInf.classList.add('flex');
                
                document.getElementById('res_inf_paid').innerText = fmtMoney(infTotalPayment); 
                document.getElementById('res_inf_bal').innerText = fmtMoney(balInf); 
                
                document.getElementById('lbl_inf_bal').innerText = infPrinPctValue === 0 ? "ต้นคงเหลือ (ไม่ลดเลย):" : "ต้นคงเหลือ:";
                document.getElementById('lbl_inf_paid').innerText = infPrinPctValue === 0 ? "ยอดจ่ายทิ้งรวม (ดอกเบี้ยล้วน):" : "ยอดจ่ายสุทธิรวม:";
                document.getElementById('box_inf_title').innerText = infPrinPctValue === 0 ? "⚠️ นอกระบบ (จ่ายแค่ดอกเบี้ย)" : `⚠️ นอกระบบ (โปะต้น ${infPrinPctValue}%)`;

            } else {
                boxInf.classList.add('hidden'); boxInf.classList.remove('flex');
            }

            const savedInt = effNormalIntTotal - stratIntTotal;
            const isUsingStrat = extraMonthly > 0 || extraYearly > 0 || isRefinance || payoffMonth > 0;
            document.getElementById('strat_title').innerText = isUsingStrat ? "ใช้กลยุทธ์ (ประหยัดได้)" : "คุณไม่ได้ใช้กลยุทธ์ใดๆ";
            document.getElementById('strat_title').className = isUsingStrat ? "text-[11px] font-bold text-[#00ff41] mb-1" : "text-[11px] font-bold text-gray-500 mb-1";

            let labels = [];
            let displayMonths = totalMonths; 

            for (let i = 0; i <= displayMonths; i++) {
                if (i % 12 === 0) labels.push(`ปี ${i/12}`);
                else labels.push('');
            }

            updateChart(labels, flatData, effNormalData, stratData, flatDetails, effDetails, stratDetails, 
                        showCC ? ccData : null, showCC ? ccDetails : null,
                        showInf ? infData : null, showInf ? infDetails : null, P, infPrinPctValue);
            
            updateNarrative(P, effNormalIntTotal, savedInt, totalMonths, stratMonthsElapsed, isRefinance, isPayoffTriggered, payoffMonth, showCC, showInf, basePMT, isUsingStrat, years, infTotalPayment, ccPctValue, infPrinPctValue);
        }

        function updateChart(labels, flatData, effData, stratData, flatDetails, effDetails, stratDetails, ccData, ccDetails, infData, infDetails, P, infPrinPctValue) {
            const ctx = document.getElementById('loanChart').getContext('2d');
            if (loanChart) loanChart.destroy();

            let datasets = [
                {
                    label: 'Flat Rate (คงที่)', data: flatData, details: flatDetails,
                    borderColor: '#ffd700', borderWidth: 2, borderDash: [5, 5], pointRadius: 0, tension: 0
                },
                {
                    label: 'Effective (ผ่อนปกติ)', data: effData, details: effDetails,
                    borderColor: '#ff0055', borderWidth: 2, pointRadius: 0, tension: 0.1
                },
                {
                    label: 'My Strategy', data: stratData, details: stratDetails,
                    borderColor: '#00ff41', backgroundColor: 'rgba(0, 255, 65, 0.15)', borderWidth: 3, pointRadius: 0, fill: true, tension: 0.1
                }
            ];

            if (ccData) {
                const ccPctLabel = document.getElementById('cc_min_pct').value;
                datasets.push({
                    label: `บัตรเครดิต (ขั้นต่ำ ${ccPctLabel}%)`, data: ccData, details: ccDetails,
                    borderColor: '#a855f7', borderWidth: 2, borderDash: [2, 2], pointRadius: 0, tension: 0.1
                });
            }
            if (infData) {
                const infLabel = infPrinPctValue === 0 ? 'นอกระบบ (จ่ายแต่ดอกเบี้ย)' : `นอกระบบ (โปะต้น ${infPrinPctValue}%)`;
                datasets.push({
                    label: infLabel, data: infData, details: infDetails,
                    borderColor: '#ff5500', borderWidth: 3, pointRadius: 0, tension: 0.1
                });
            }

            loanChart = new Chart(ctx, {
                type: 'line',
                data: { labels: labels.slice(0, flatData.length), datasets: datasets },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { color: '#ccc', font: { family: 'Prompt', size: 10 } } },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.95)', 
                            titleFont: { family: 'Prompt', size: 13, weight: 'bold' }, 
                            bodyFont: { family: 'Prompt', size: 11 }, 
                            padding: 10, borderColor: '#333', borderWidth: 1,
                            callbacks: {
                                title: (ctx) => `เดือนที่ ${ctx[0].dataIndex}`,
                                label: function(ctx) {
                                    const ds = ctx.dataset; const idx = ctx.dataIndex; const detail = ds.details[idx];
                                    if (!detail || idx === 0) return `${ds.label}: ต้นคงเหลือ ${fmtMoney(ctx.parsed.y)} ฿`;
                                    
                                    let sign = (detail.principal < 0) ? "+" : ""; 
                                    let extraText = detail.principal === 0 && ds.label.includes('นอกระบบ') ? '(ต้นไม่ลดเลย!)' : '';
                                    if(detail.payment === 500 && ds.label.includes('บัตรเครดิต')) extraText = '(ชนเพดานขั้นต่ำ 500 ฿)';

                                    return [
                                        `${ds.label}`,
                                        `  • จ่ายไป: ${fmtMoney(detail.payment)} ฿`,
                                        `  • ดอกเบี้ย: ${fmtMoney(detail.interest)} ฿`,
                                        `  • ตัดต้น: ${sign}${fmtMoney(detail.principal)} ฿ ${extraText}`,
                                        `  • คงเหลือ: ${fmtMoney(detail.balance)} ฿`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: '#222' }, ticks: { color: '#888', maxRotation: 0, autoSkip: false, callback: function(val, index) { return labels[index]; } } },
                        y: { grid: { color: '#222' }, ticks: { color: '#888', callback: (val) => fmtMoney(val) } }
                    }
                }
            });
        }

        function updateNarrative(P, effInt, savedInt, totalM, stratM, isRefinance, isPayoff, payoffM, showCC, showInf, basePMT, isUsingStrat, years, infTotalPaid, ccPctValue, infPrinPctValue) {
            const box = document.getElementById('narrative_text');
            const wrapper = document.getElementById('narrative_box');
            
            if (showInf) {
                wrapper.className = "md:col-span-3 neon-box p-4 rounded-xl bg-gradient-to-r from-gray-900 to-black border-l-4 border-[#ff5500]";
            } else {
                wrapper.className = "md:col-span-3 neon-box p-4 rounded-xl bg-gradient-to-r from-gray-900 to-black border-l-4 border-[#00ff41]";
            }

            const yearsSaved = ((totalM - stratM) / 12).toFixed(1);
            let html = `เงินต้น <b class="text-white">${fmtMoney(P)} บาท</b> หากผ่อนตามบิลปกติ คุณจะเสียดอกเบี้ย <b class="text-[#ff0055]">${fmtMoney(effInt)} บาท</b> `;
            
            if (isUsingStrat && (savedInt > 0 || stratM < totalM)) {
                html += `<hr class="border-gray-800 my-2">`;
                html += `แต่ด้วย <b>"กลยุทธ์ของคุณ"</b> คุณสามารถประหยัดดอกเบี้ยไปได้ถึง <span class="text-[#00ff41] font-bold text-base">${fmtMoney(savedInt)} บาท!</span> `;
                if (stratM < totalM) html += `และผ่อนจบเร็วขึ้น <b>${yearsSaved} ปี</b>`;
            } else if (!isUsingStrat) {
                 html += `<br><span class="text-gray-500 text-[10px]">*คุณยังไม่ได้เปิดใช้งานกลยุทธ์การโปะเพิ่ม หรือรีไฟแนนซ์</span>`;
            }

            if (showCC || showInf) {
                html += `<hr class="border-gray-800 my-3">`;
                html += `<h4 class="text-sm font-bold text-white mb-1"><i class="fa-solid fa-skull-crossbones text-gray-500"></i> โซนอันตราย (The Danger Zone)</h4><ul class="list-disc pl-5 space-y-2">`;
                
                if (showCC) {
                    let firstMonthCC = Math.max((P + (P * (16/100/12))) * (ccPctValue/100), 500);
                    html += `<li><span class="text-[#a855f7]">บัตรเครดิต (จ่ายขั้นต่ำ ${ccPctValue}%):</span> ดอกเบี้ย 16% ต่อปี หากคุณจ่ายแค่ขั้นต่ำ (เดือนแรก ${fmtMoney(firstMonthCC)} บาท และลดลงเรื่อยๆ ตามยอดหนี้) การจ่ายแบบนี้คือ "กับดักการเลี้ยงไข้" เพราะค่างวดจะหดเล็กลงเรื่อยๆ ทำให้ตัดเงินต้นได้น้อยลงในช่วงหลัง หนี้จะลดช้ามากๆ ยิ่งปรับยอดผ่อน % ต่ำ หนี้ก็ยิ่งยาวนาน!</li>`;
                }
                
                if (showInf) {
                    let firstMonthInterest = P * 0.20;
                    if (infPrinPctValue === 0) {
                        html += `<li><span class="text-[#ff5500] font-bold">หนี้นอกระบบ (The Trap):</span> ดอกเบี้ย 20% ต่อเดือน (ตกเดือนละ ${fmtMoney(firstMonthInterest)} บาท) หากคุณกัดฟันหาเงินมา<b>จ่ายแค่ดอกเบี้ย</b>เพื่อเอาตัวรอดไปวันๆ กราฟเงินต้น (เส้นสีส้ม) จะกลายเป็น <u class="text-white">"เส้นตรงแนวนอน"</u> ที่ไม่มีวันลดลงเลย! ผ่านไป ${years} ปี คุณจะจ่ายเงินทิ้งเปล่าๆ ไปถึง <span class="bg-red-500/20 text-red-400 px-1 rounded font-bold">${fmtMoney(infTotalPaid)} บาท</span> โดยที่หนี้ยังเหลือ ${fmtMoney(P)} บาทเท่าเดิมเป๊ะ!</li>`;
                    } else {
                        let monthsToClear = Math.ceil(100 / infPrinPctValue);
                        html += `<li><span class="text-[#ff5500] font-bold">หนี้นอกระบบ (โปะต้น ${infPrinPctValue}%):</span> ดอกเบี้ย 20% ต่อเดือน (เดือนแรกจ่ายดอกเบี้ย ${fmtMoney(firstMonthInterest)} บาท) แม้คุณจะพยายามหนีตายด้วยการโปะเงินต้นเดือนละ ${fmtMoney(P * (infPrinPctValue / 100))} บาท ซึ่งจะทำให้หนี้หมดในเวลา ${monthsToClear} เดือน แต่รวมเบ็ดเสร็จแล้วคุณต้องจ่ายเงินออกไปทั้งหมดถึง <span class="bg-red-500/20 text-red-400 px-1 rounded font-bold">${fmtMoney(infTotalPaid)} บาท</span> ซึ่งสูงกว่าเงินต้นที่กู้มาหลายเท่าตัว!</li>`;
                    }
                }
                html += `</ul>`;
            }

            box.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', () => {
            updatePayoffMax();
            toggleState('extra_monthly');
            toggleState('extra_yearly');
            runSimulation();
        });
    </script>
</body>
</html>