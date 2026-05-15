<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Long Position Calculator</title>
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2322c55e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='23 6 13.5 15.5 8.5 10.5 1 18'%3E%3C/polyline%3E%3Cpolyline points='17 6 23 6 23 12'%3E%3C/polyline%3E%3C/svg%3E" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Prompt', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #059669; border-radius: 4px; } /* Green Thumb for Long */
        
        /* Input Styling */
        .input-dark {
            background-color: #374151; /* gray-700 */
            border: 1px solid #4b5563; /* gray-600 */
            color: #ffffff;
            font-size: 0.95rem;
            border-radius: 0.5rem;
            padding: 0.625rem;
            width: 100%;
            display: block;
            transition: all 0.2s;
            text-align: right; /* ตัวเลขชิดขวาเพื่อความสวยงามเวลามี comma */
        }
        .input-dark:focus {
            outline: none;
            border-color: #3b82f6; 
            ring: 2px solid #3b82f6;
        }
        
        /* Stepper Button Group */
        .stepper-btn {
            background-color: #4b5563;
            color: white;
            padding: 0 10px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stepper-btn:hover { background-color: #6b7280; }
        .stepper-left { border-radius: 0.5rem 0 0 0.5rem; border-right: 1px solid #374151; }
        .stepper-right { border-radius: 0 0.5rem 0.5rem 0; border-left: 1px solid #374151; }
        
        /* Remove default input styles specifically for text type acting as number */
        input[type=text]::-ms-clear { display: none; }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        
        <header class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-gray-700 pb-4 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-green-500 flex items-center gap-3">
				<span><i class="fa-solid fa-chart-line"></i> Long Position Sizing</span>
			<a href="index.php" class="text-gray-600 hover:text-white transition-colors text-xl" title="กลับหน้าหลัก">
				<i class="fa-solid fa-house"></i>
			</a>
            </h1>
               <p class="text-sm text-gray-400 mt-1">เครื่องมือคำนวณไม้เทรด (ขาขึ้น)</p>
            </div>
            <div class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700 text-center md:text-right">
                <div class="text-xs text-gray-500">USD/THB Rate</div>
                <div class="text-xl font-bold text-green-400" id="display-rate">Loading...</div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                    <h2 class="text-xl font-semibold mb-4 text-white border-l-4 border-blue-500 pl-3">1. Portfolio Setting</h2>
                    
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-300">สกุลเงินหลัก</label>
                        <select id="currency" class="input-dark text-left">
                            <option value="THB" selected>THB (บาท)</option>
                            <option value="USD">USD (ดอลล่าร์)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-300">มูลค่าพอร์ตทั้งหมด</label>
                        <input type="text" id="portfolio-size" value="100,000" class="input-dark text-lg font-bold text-yellow-400 js-format-number">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-300">เงินที่ยอมเสีย (Risk)</label>
                            <div class="flex">
                                <button type="button" class="stepper-btn stepper-left" onclick="adjustValue('risk-amount', -100)">-</button>
                                <input type="text" id="risk-amount" class="input-dark text-center text-red-400 font-bold !rounded-none js-format-number" placeholder="0">
                                <button type="button" class="stepper-btn stepper-right" onclick="adjustValue('risk-amount', 100)">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-300">ความเสี่ยง (%)</label>
                            <input type="number" id="risk-percent" value="1" step="0.1" class="input-dark text-red-400 font-bold text-center">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                    <h2 class="text-xl font-semibold mb-4 text-white border-l-4 border-purple-500 pl-3">2. Trade Setup (Long)</h2>
                    
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-300">สินทรัพย์ (Asset)</label>
                        <select id="asset-select" class="input-dark mb-2 cursor-pointer text-left">
                            <option value="BTC">Bitcoin (BTC)</option>
                            <option value="GOLD">Gold (PaxG Proxy)</option>
                            <option value="OTHER">Other</option>
                        </select>
                        <p class="text-xs text-gray-500 text-right" id="asset-status"></p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between">
                                <label class="text-sm text-gray-300">ราคาเข้า (Long Entry USD)</label>
                                <span class="text-xs text-gray-500" id="entry-thb">~ 0 THB</span>
                            </div>
                            <input type="text" id="entry-price" placeholder="0.00" class="input-dark border-blue-500 js-format-number">
                        </div>

                        <div>
                            <div class="flex justify-between items-center">
                                <label class="text-sm text-red-300">ราคา Stop Loss (ต่ำกว่า)</label>
                                <span class="text-xs font-bold text-red-400 bg-red-900/30 px-2 py-0.5 rounded" id="sl-diff-percent">0.00%</span>
                            </div>
                            <input type="text" id="sl-price" placeholder="0.00" class="input-dark border-red-500/50 mt-1 js-format-number">
                            <div class="text-right text-xs text-gray-500 mt-1" id="sl-thb">~ 0 THB</div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center">
                                <label class="text-sm text-green-300">ราคา Take Profit (สูงกว่า)</label>
                                <span class="text-xs font-bold text-green-400 bg-green-900/30 px-2 py-0.5 rounded" id="tp-diff-percent">0.00%</span>
                            </div>
                            <input type="text" id="tp-price" placeholder="0.00" class="input-dark border-green-500/50 mt-1 js-format-number">
                            <div class="text-right text-xs text-gray-500 mt-1" id="tp-thb">~ 0 THB</div>
                        </div>

                        <div>
                            <label class="text-xs text-gray-400">ค่าธรรมเนียม (%)</label>
                            <div class="flex w-32 mt-1">
                                <button type="button" class="stepper-btn stepper-left" onclick="adjustValue('fee-percent', -0.05)">-</button>
                                <input type="number" id="fee-percent" value="0.1" step="0.05" class="input-dark text-center !rounded-none">
                                <button type="button" class="stepper-btn stepper-right" onclick="adjustValue('fee-percent', 0.05)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                    <h2 class="text-xl font-semibold mb-6 text-white border-l-4 border-green-500 pl-3">Results (Long)</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-gray-800 to-green-900/20 p-6 rounded-lg border border-green-900/50 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <i class="fa-solid fa-money-bill-wave text-6xl text-green-500"></i>
                            </div>
                            <h3 class="text-gray-400 text-sm uppercase tracking-wider">Long Position Size</h3>
                            <div class="text-4xl font-bold text-white mt-2" id="res-position-size">0.00</div>
                            <div class="text-sm text-gray-400 mt-1 font-mono" id="res-units">Units: 0.00000000</div>
                        </div>

                        <div class="bg-gray-750 p-6 rounded-lg border border-gray-600 flex flex-col justify-center">
                             <h3 class="text-gray-400 text-sm uppercase tracking-wider mb-2">Risk Check</h3>
                             <div class="space-y-2 text-sm">
                                <div class="flex justify-between border-b border-gray-700 pb-2">
                                    <span>ระยะห่าง SL (%):</span>
                                    <span class="text-red-400 font-bold" id="res-sl-percent">0.00%</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span>เงินที่เสียจริง (Loss):</span>
                                    <span class="text-red-400 font-bold text-lg" id="res-real-loss">0.00</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>(ค่าธรรมเนียมโดยประมาณ):</span>
                                    <span id="res-fee-cost">0.00</span>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gray-700 p-4 rounded-lg text-center">
                            <div class="text-xs text-gray-400">Risk : Reward</div>
                            <div class="text-2xl font-bold text-yellow-400" id="res-rrr">0 : 0</div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg text-center">
                            <div class="text-xs text-gray-400">% ของพอร์ตที่ใช้</div>
                            <div class="text-2xl font-bold text-blue-400" id="res-port-percent">0%</div>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg text-center">
                            <div class="text-xs text-gray-400">พอร์ตเสียหายรวม</div>
                            <div class="text-2xl font-bold text-red-400" id="res-port-impact">0%</div>
                        </div>
                    </div>

                    <button onclick="saveJournal()" class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg flex justify-center items-center gap-2">
                        <i class="fa-solid fa-save"></i> บันทึก Long เข้า Journal
                    </button>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-white border-l-4 border-yellow-500 pl-3">History</h2>
                        <button onclick="clearHistory()" class="text-xs text-red-400 hover:text-red-300 underline">ล้างประวัติ</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-300 uppercase bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3">Asset</th>
                                    <th class="px-4 py-3">Side</th>
                                    <th class="px-4 py-3">Entry</th>
                                    <th class="px-4 py-3">Size</th>
                                    <th class="px-4 py-3 text-right">RRR</th>
                                </tr>
                            </thead>
                            <tbody id="journal-list"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let usdThbRate = 34.0;
        
        // --- Helper Functions for Formatting ---
        const parseRaw = (val) => {
            if (!val) return 0;
            // ลบ comma ออกก่อนแปลงเป็น float
            return parseFloat(val.toString().replace(/,/g, '')) || 0;
        };
        
        const formatWithCommas = (num) => {
            // แยกทศนิยม
            let parts = num.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        };

        // --- Event Listeners Setup ---
        document.addEventListener('DOMContentLoaded', () => {
            fetchRate();
            fetchAssetPrice('BTC'); // Default
            loadJournal();
            
            // Format Init Values
            document.querySelectorAll('.js-format-number').forEach(el => {
                if(el.value) el.value = formatWithCommas(el.value);
                
                // Focus: Remove commas for editing
                el.addEventListener('focus', function() {
                    let val = this.value.replace(/,/g, '');
                    if(val == 0) val = '';
                    this.value = val;
                });

                // Blur: Add commas back
                el.addEventListener('blur', function() {
                    if(this.value !== '') {
                        this.value = formatWithCommas(this.value);
                    }
                    calculate(); // Recalculate on blur
                });

                // Input: Just Calculate
                el.addEventListener('input', function() {
                    // ถ้าเป็นช่องเสี่ยงเงิน ต้อง sync กับ %
                    if(this.id === 'risk-amount') syncRisk('amount');
                    if(this.id === 'portfolio-size') syncRisk('port');
                    
                    calculate();
                });
            });
            
            // Standard Number Inputs
            document.getElementById('risk-percent').addEventListener('input', () => { syncRisk('percent'); calculate(); });
            document.getElementById('fee-percent').addEventListener('input', calculate);

            // Asset Change Listener
            document.getElementById('asset-select').addEventListener('change', (e) => {
                const asset = e.target.value;
                if(asset === 'OTHER') {
                    document.getElementById('entry-price').value = '';
                    document.getElementById('sl-price').value = '';
                    document.getElementById('tp-price').value = '';
                    document.getElementById('asset-status').innerText = 'กรุณากรอกราคาเอง';
                    calculate();
                } else {
                    fetchAssetPrice(asset);
                }
            });
        });

        // --- Stepper Logic ---
        function adjustValue(id, step) {
            const el = document.getElementById(id);
            let val = parseRaw(el.value);
            val += step;
            if(val < 0) val = 0;
            
            // ปัดเศษทศนิยมกรณีที่เป็น fee (ป้องกัน 0.1000000005)
            if(id === 'fee-percent') val = Math.round(val * 100) / 100;

            if(el.classList.contains('js-format-number')) {
                el.value = formatWithCommas(val);
            } else {
                el.value = val;
            }

            // Trigger sync if needed
            if(id === 'risk-amount') syncRisk('amount');
            
            calculate();
        }

        // --- Sync Logic ---
        function syncRisk(source) {
            const portSize = parseRaw(document.getElementById('portfolio-size').value);
            
            if (source === 'amount' || source === 'port') {
                const amount = parseRaw(document.getElementById('risk-amount').value);
                if(portSize > 0) {
                    document.getElementById('risk-percent').value = ((amount / portSize) * 100).toFixed(2);
                }
            } 
            
            if (source === 'percent') {
                const percent = parseFloat(document.getElementById('risk-percent').value) || 0;
                const res = (portSize * percent) / 100;
                document.getElementById('risk-amount').value = formatWithCommas(res.toFixed(0));
            }
        }

        async function fetchRate() {
            const results = [];

            // Source 1: local server (dynamic host)
            try {
                const r = await fetch(window.location.origin + '/rate.json',
                    { signal: AbortSignal.timeout(3000) });
                if (r.ok) {
                    const d = await r.json();
                    const rate = parseFloat(d?.rate);
                    if (rate && rate > 20 && rate < 80)
                        results.push({ rate, ts: d.unix_timestamp || 0, src: 'local/rate.json' });
                }
            } catch { /* skip */ }

            // Source 2: floatrates.com
            try {
                const r = await fetch('https://www.floatrates.com/daily/usd.json',
                    { signal: AbortSignal.timeout(6000) });
                if (r.ok) {
                    const d = await r.json();
                    const rate = parseFloat(d?.thb?.rate);
                    if (rate && rate > 20 && rate < 80) {
                        const ts = d?.thb?.date ? Math.floor(new Date(d.thb.date).getTime() / 1000) : 0;
                        results.push({ rate, ts, src: 'floatrates.com' });
                    }
                }
            } catch { /* skip */ }

            // Source 3: open.er-api.com
            try {
                const r = await fetch('https://open.er-api.com/v6/latest/USD',
                    { signal: AbortSignal.timeout(6000) });
                const d = await r.json();
                if (d?.rates?.THB) {
                    const ts = d?.time_last_update_unix
                        || (d?.time_last_update_utc ? Math.floor(new Date(d.time_last_update_utc).getTime() / 1000) : 0);
                    results.push({ rate: d.rates.THB, ts, src: 'open.er-api.com' });
                }
            } catch { /* skip */ }

            if (results.length === 0) {
                document.getElementById('display-rate').innerText = 'Rate Error (Use 34.0)';
                return;
            }

            results.sort((a, b) => b.ts - a.ts);
            const best = results[0];
            usdThbRate = best.rate;
            document.getElementById('display-rate').innerText = `1 USD = ${usdThbRate.toFixed(2)} THB · ${best.src}`;
            calculate();
        }

        async function fetchAssetPrice(asset) {
            document.getElementById('asset-status').innerText = 'Fetching...';
            let url = '';
            
            if(asset === 'BTC') url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd';
            if(asset === 'GOLD') url = 'https://api.coingecko.com/api/v3/simple/price?ids=pax-gold&vs_currencies=usd';

            if(url) {
                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    let price = 0;
                    
                    if(asset === 'BTC') price = data.bitcoin.usd;
                    if(asset === 'GOLD') price = data['pax-gold'].usd;
                    
                    if(price > 0) {
                        document.getElementById('entry-price').value = formatWithCommas(price);
                        document.getElementById('asset-status').innerText = `Updated: $${formatWithCommas(price)}`;
                        
                        autoFillSLTP(price);
                        calculate();
                    }
                } catch (e) { 
                    document.getElementById('asset-status').innerText = 'Fetch Error';
                }
            }
        }

        function autoFillSLTP(price) {
            const sl = price * 0.90;
            const tp = price * 1.10;
            document.getElementById('sl-price').value = formatWithCommas(sl.toFixed(2));
            document.getElementById('tp-price').value = formatWithCommas(tp.toFixed(2));
        }

        function calculate() {
            const portSize = parseRaw(document.getElementById('portfolio-size').value);
            const currency = document.getElementById('currency').value;
            const riskAmount = parseRaw(document.getElementById('risk-amount').value);
            
            const entryUSD = parseRaw(document.getElementById('entry-price').value);
            const slUSD = parseRaw(document.getElementById('sl-price').value);
            const tpUSD = parseRaw(document.getElementById('tp-price').value);
            const feePercent = parseFloat(document.getElementById('fee-percent').value) || 0;

            const toTHB = (valUSD) => (valUSD * usdThbRate);

            // Display THB conversions
            document.getElementById('entry-thb').innerText = `~ ${new Intl.NumberFormat().format(toTHB(entryUSD).toFixed(0))} THB`;
            document.getElementById('sl-thb').innerText = `~ ${new Intl.NumberFormat().format(toTHB(slUSD).toFixed(0))} THB`;
            document.getElementById('tp-thb').innerText = `~ ${new Intl.NumberFormat().format(toTHB(tpUSD).toFixed(0))} THB`;

            // SL/TP Percent
            if(entryUSD > 0) {
                if(slUSD > 0) {
                    const slDiff = ((slUSD - entryUSD) / entryUSD) * 100;
                    document.getElementById('sl-diff-percent').innerText = `${slDiff > 0 ? '+' : ''}${slDiff.toFixed(2)}%`;
                    const slEl = document.getElementById('sl-diff-percent');
                    slEl.className = slDiff < 0 ? "text-xs font-bold text-red-400 bg-red-900/30 px-2 py-0.5 rounded" : "text-xs font-bold text-green-400 bg-green-900/30 px-2 py-0.5 rounded";
                } else { document.getElementById('sl-diff-percent').innerText = "0.00%"; }

                if(tpUSD > 0) {
                    const tpDiff = ((tpUSD - entryUSD) / entryUSD) * 100;
                    document.getElementById('tp-diff-percent').innerText = `${tpDiff > 0 ? '+' : ''}${tpDiff.toFixed(2)}%`;
                    const tpEl = document.getElementById('tp-diff-percent');
                    tpEl.className = tpDiff > 0 ? "text-xs font-bold text-green-400 bg-green-900/30 px-2 py-0.5 rounded" : "text-xs font-bold text-red-400 bg-red-900/30 px-2 py-0.5 rounded";
                } else { document.getElementById('tp-diff-percent').innerText = "0.00%"; }
            }

            if (entryUSD === 0 || slUSD === 0 || riskAmount === 0) return;

            // Core Logic
            const priceDistance = Math.abs(entryUSD - slUSD);
            const slPercent = (priceDistance / entryUSD); 

            let riskInUSD = riskAmount;
            if (currency === 'THB') riskInUSD = riskAmount / usdThbRate;

            const units = riskInUSD / priceDistance;
            const positionValueUSD = units * entryUSD;
            
            let positionValueUserCurr = positionValueUSD;
            if (currency === 'THB') positionValueUserCurr = positionValueUSD * usdThbRate;

            const estimatedFee = positionValueUserCurr * (feePercent / 100) * 2;
            const totalRealLoss = riskAmount + estimatedFee;

            let rrr = 0;
            if (tpUSD > 0) {
                const rewardDist = Math.abs(tpUSD - entryUSD);
                rrr = rewardDist / priceDistance;
            }

            // Render Results
            document.getElementById('res-position-size').innerText = new Intl.NumberFormat('th-TH', { style: 'currency', currency: currency }).format(positionValueUserCurr);
            
            // Unit Suffix
            const assetType = document.getElementById('asset-select').value;
            let unitSuffix = 'Units';
            if(assetType === 'GOLD') unitSuffix = 'oz';
            if(assetType === 'BTC') unitSuffix = 'BTC';
            
            document.getElementById('res-units').innerText = `Units: ${units.toFixed(8)} ${unitSuffix}`;

            document.getElementById('res-sl-percent').innerText = (slPercent * 100).toFixed(2) + "%";
            document.getElementById('res-real-loss').innerText = new Intl.NumberFormat().format(totalRealLoss.toFixed(2));
            document.getElementById('res-fee-cost').innerText = `(Fee ~${estimatedFee.toFixed(2)})`;
            document.getElementById('res-rrr').innerText = `1 : ${rrr.toFixed(2)}`;
            document.getElementById('res-rrr').className = rrr >= 2 ? "text-2xl font-bold text-green-400" : "text-2xl font-bold text-yellow-400";

            const portPercent = (positionValueUserCurr / portSize) * 100;
            document.getElementById('res-port-percent').innerText = portPercent.toFixed(1) + "%";
            
            const impactPercent = (totalRealLoss / portSize) * 100;
            document.getElementById('res-port-impact').innerText = "-" + impactPercent.toFixed(2) + "%";
        }

        // --- LocalStorage Journal ---
        function saveJournal() {
            const history = JSON.parse(localStorage.getItem('tradeJournal')) || [];
            const entry = parseRaw(document.getElementById('entry-price').value);
            const sl = parseRaw(document.getElementById('sl-price').value);
            
            const data = {
                date: new Date().toLocaleString(),
                asset: document.getElementById('asset-select').value,
                entry: entry,
                size: document.getElementById('res-position-size').innerText,
                rrr: document.getElementById('res-rrr').innerText,
                type: (entry > sl) ? 'Long' : 'Short'
            };
            history.unshift(data);
            if(history.length > 20) history.pop();
            localStorage.setItem('tradeJournal', JSON.stringify(history));
            loadJournal();
        }

        function loadJournal() {
            const history = JSON.parse(localStorage.getItem('tradeJournal')) || [];
            const tbody = document.getElementById('journal-list');
            tbody.innerHTML = '';
            history.forEach(item => {
                const row = `<tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750">
                    <td class="px-4 py-3 font-medium text-white">${item.asset}</td>
                    <td class="px-4 py-3 ${item.type === 'Long' ? 'text-green-400' : 'text-red-400'}">${item.type}</td>
                    <td class="px-4 py-3">${formatWithCommas(item.entry)}</td>
                    <td class="px-4 py-3">${item.size}</td>
                    <td class="px-4 py-3 text-right">${item.rrr}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
        }
        function clearHistory() {
            if(confirm('ลบประวัติ?')) { localStorage.removeItem('tradeJournal'); loadJournal(); }
        }
    </script>
</body>
</html>