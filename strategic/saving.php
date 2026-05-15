<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Saving Simulator | by Chollatis Bitcoiner</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.1.0/dist/chartjs-plugin-annotation.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-red: #ff0055;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(247, 147, 26, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 255, 65, 0.05) 0%, transparent 25%);
            overflow-x: hidden;
        }

        .brand-font { 
            font-family: 'Orbitron', sans-serif; 
        }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: var(--neon-btc); border-radius: 4px; }

        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        .neon-box:hover {
            border-color: var(--neon-btc);
            box-shadow: 0 0 20px rgba(247, 147, 26, 0.2);
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
        .input-dark:focus {
            border-color: var(--neon-green);
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 65, 0.2);
        }

        @keyframes pulse-red {
            0% { text-shadow: 0 0 0 rgba(255,0,85, 0); }
            50% { text-shadow: 0 0 10px rgba(255,0,85, 0.8); }
            100% { text-shadow: 0 0 0 rgba(255,0,85, 0); }
        }
        .animate-pulse-text { animation: pulse-red 2s infinite; }

        .chart-container { position: relative; height: 400px; width: 100%; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-brands fa-bitcoin text-4xl text-[#F7931A] animate-pulse"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font">
                    WEALTH SIMULATOR
                </h1>
                <p class="text-xs text-gray-400">Fiat vs. Hard Assets Analysis</p>
            </div>
        </div>
        <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm">
            <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 space-y-4">
            
            <div class="neon-box p-6 rounded-xl">
                <h2 class="text-lg font-bold mb-4 text-[#F7931A] flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i> ตั้งค่าการออม (Simulation)
                </h2>

                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-1">รูปแบบการออม</label>
                    <select id="strategy" class="w-full p-2 rounded input-dark mb-2">
                        <option value="dca_monthly" selected>DCA ทุกเดือน (Monthly)</option>
                        <option value="dca_weekly">DCA ทุกสัปดาห์ (Weekly)</option>
                        <option value="dca_daily">DCA ทุกวัน (Daily)</option>
                        <option value="lump_sum">All In (ครั้งเดียวจบ)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">เงินออม (บาท)</label>
                        <input type="number" id="amount" value="3000" class="w-full p-2 rounded input-dark text-right font-mono" step="100">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">เพิ่มเงินออม/ปี (%)</label>
                        <div class="flex">
                            <input type="number" id="saving_growth" value="0" class="w-full p-2 rounded-l input-dark text-right font-mono" step="0.1">
                            <div class="bg-gray-800 p-2 rounded-r text-xs flex items-center">%</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-red-400 mb-1 flex justify-between">
                        <span>อัตราเงินเฟ้อ (ต่อปี)</span>
                        <span class="text-xs border border-red-500/30 px-1 rounded">ศัตรูที่มองไม่เห็น</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button onclick="adjustVal('inflation', -0.1)" class="px-2 py-1 bg-gray-800 rounded hover:bg-red-900">-</button>
                        <input type="number" id="inflation" value="7.0" class="w-full p-2 rounded input-dark text-right font-mono text-red-400 font-bold" step="0.1">
                        <button onclick="adjustVal('inflation', 0.1)" class="px-2 py-1 bg-gray-800 rounded hover:bg-red-900">+</button>
                        <span class="text-gray-400">%</span>
                    </div>
                </div>

                <div class="border-t border-gray-700 pt-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="compare_asset" class="accent-[#F7931A] w-4 h-4" onchange="toggleAssetOptions()">
                            <span class="text-sm font-bold text-[#F7931A]">เปรียบเทียบสินทรัพย์</span>
                        </label>
                    </div>

                    <div id="asset_options" class="hidden transition-all duration-300 pl-4 border-l-2 border-[#F7931A]/30">
                        <div class="mb-2">
                            <label class="block text-xs text-gray-400">เลือกสินทรัพย์</label>
                            <select id="asset_type" class="w-full p-1 rounded input-dark text-sm" onchange="updateAssetRate()">
                                <option value="btc">Bitcoin (Hard Money)</option>
                                <option value="gold">Gold (Physical)</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-green-400">เติบโตเฉลี่ยต่อปี (%)</label>
                            <div class="flex items-center gap-1">
                                <input type="number" id="asset_growth" value="60.5" class="w-full p-1 rounded input-dark text-right font-mono text-green-400 text-sm" step="0.1">
                                <span class="text-xs text-gray-500">%</span>
                            </div>
                            <div class="text-[10px] text-gray-500 mt-1">*อ้างอิงค่าเฉลี่ย CAGR (แก้ไขได้)</div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm text-gray-400 mb-1">ระยะเวลา (ปี)</label>
                    <input type="range" id="years" min="1" max="50" value="20" class="w-full accent-[#F7931A] h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer">
                    <div class="text-right text-[#F7931A] font-mono"><span id="years_val">20</span> ปี</div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button onclick="runSimulation(false)" class="col-span-1 btn-neon py-3 rounded text-sm shadow-[0_0_15px_rgba(247,147,26,0.5)]">
                        <i class="fa-solid fa-calculator"></i> คำนวณทันที
                    </button>
                    <button onclick="runSimulation(true)" class="col-span-1 border border-[#00ff41] text-[#00ff41] hover:bg-[#00ff41] hover:text-black py-3 rounded text-sm transition font-bold uppercase tracking-wider flex flex-col items-center justify-center leading-tight">
                        <span><i class="fa-solid fa-play"></i> สาธิต</span>
                        <span class="text-[10px] opacity-70">(1 วินาที/ครั้ง)</span>
                    </button>
                    <button onclick="resetAll()" class="col-span-2 bg-gray-800 hover:bg-gray-700 text-gray-400 py-2 rounded text-xs transition">
                        <i class="fa-solid fa-rotate-left"></i> เริ่มต้นใหม่ (Reset)
                    </button>
                </div>
            </div>

            <div class="neon-box p-4 rounded-xl max-h-64 overflow-y-auto">
                <div class="flex justify-between items-center mb-2 sticky top-0 bg-[#050505] p-2 z-10 border-b border-gray-800">
                    <h3 class="text-sm font-bold text-gray-300"><i class="fa-solid fa-clock-rotate-left"></i> ประวัติการคำนวณ</h3>
                    <button onclick="clearHistory()" class="text-xs text-red-500 hover:underline">ล้างประวัติ</button>
                </div>
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="text-gray-500">
                            <th class="p-1">เวลา</th>
                            <th class="p-1">แบบ</th>
                            <th class="p-1 text-right">ผลลัพธ์</th>
                            <th class="p-1"></th>
                        </tr>
                    </thead>
                    <tbody id="history_list">
                        </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-6">
            
            <div class="neon-box p-4 md:p-6 rounded-xl relative">
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-xl font-bold brand-font text-white">GROWTH vs INFLATION</h2>
                    <div class="text-xs text-right text-gray-400">
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-3 bg-[#F7931A] rounded-full"></div> มูลค่าพอร์ตสินทรัพย์ (Nominal)</div>
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-3 bg-gray-400 rounded-full"></div> เงินออมสะสม (บาท)</div>
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-3 bg-[#ff0055] rounded-full"></div> อำนาจการซื้อจริง</div>
                    </div>
                </div>
                
                <div class="chart-container relative">
                    <canvas id="wealthChart"></canvas>

                    <div id="sim_overlay" class="absolute top-0 left-0 w-full h-full z-20 hidden flex flex-col justify-between p-2 pointer-events-none">
                        
                        <div class="pointer-events-auto self-start mt-1 ml-1 md:mt-2 md:ml-2">
                            <div class="bg-black/60 border border-[#F7931A]/30 backdrop-blur-md rounded-full px-3 py-1.5 flex items-center gap-3 shadow-lg">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                      <span id="status_dot_bg" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span id="status_dot" class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    <span id="status_text" class="text-[#F7931A] text-[10px] md:text-xs font-bold tracking-wider brand-font">LIVE</span>
                                </div>
                                
                                <div class="h-3 w-px bg-gray-600"></div>
                                
                                <div class="text-sm font-mono font-bold text-white leading-none min-w-[60px]" id="sim_year">
                                    Y0 M0
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-auto w-full flex justify-center gap-3 pointer-events-auto mb-1">
                            <button id="btn_toggle_sim" onclick="toggleSimulation()" class="bg-black/60 border border-red-500/50 text-red-400 px-4 py-1 rounded-full hover:bg-red-500 hover:text-white transition text-[10px] backdrop-blur-sm flex items-center gap-2 min-w-[100px] justify-center">
                                <i class="fa-solid fa-pause"></i> <span>หยุด (STOP)</span>
                            </button>
                            
                            <button onclick="finishSimulation()" class="bg-[#F7931A]/80 text-black font-bold px-4 py-1 rounded-full hover:bg-[#ffae42] transition text-[10px] backdrop-blur-sm shadow-sm flex items-center gap-2">
                                <i class="fa-solid fa-forward"></i> <span>ข้าม (SKIP)</span>
                            </button>
                        </div>
                    </div>
                    </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="neon-box p-4 rounded-lg border-l-4 border-gray-400">
                    <div class="text-gray-400 text-xs uppercase mb-1">จำนวนเงินบาทที่เก็บออมได้ (Nominal)</div>
                    <div class="text-2xl font-mono font-bold text-white" id="res_nominal">0 THB</div>
                    <div class="text-xs text-gray-500 mt-1">ตัวเลขในบัญชีธนาคาร</div>
                </div>

                <div class="neon-box p-4 rounded-lg border-l-4 border-[#ff0055]">
                    <div class="text-[#ff0055] text-xs uppercase mb-1">อำนาจการซื้อจริง (Real Value)</div>
                    <div class="text-2xl font-mono font-bold text-[#ff0055]" id="res_real">0 THB</div>
                    <div class="text-xs text-gray-500 mt-1">คำนวณตามอัตราเงินเฟ้อ <span id="res_inflation_txt">7%</span></div>
                </div>

                <div class="neon-box p-4 rounded-lg border-l-4 border-[#F7931A] relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-20"><i class="fa-brands fa-bitcoin text-4xl"></i></div>
                    <div class="text-[#F7931A] text-xs uppercase mb-1">มูลค่าในพอร์ตสินทรัพย์ (Nominal)</div>
                    <div class="text-2xl font-mono font-bold text-[#00ff41]" id="res_asset">-</div>
                    <div class="text-xs text-gray-500 mt-1" id="res_asset_diff">ไม่ได้เลือกเปรียบเทียบ</div>
                </div>
            </div>

            <div class="neon-box p-5 rounded-xl bg-gradient-to-r from-gray-900 to-black">
                <h3 class="text-md font-bold text-white mb-2"><i class="fa-solid fa-lightbulb text-yellow-400"></i> บทวิเคราะห์ (Insight)</h3>
                <div id="analysis_text" class="text-sm text-gray-300 leading-relaxed">
                    กดปุ่ม <b>คำนวณ</b> เพื่อดูผลลัพธ์...
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-6">
        <p>© 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner.</span> <span class="mx-2 text-gray-700">|</span> Don't Trust, Verify.</p>
        <p class="mt-1">ข้อมูลนี้เพื่อการศึกษาเท่านั้น ไม่ใช่คำแนะนำในการลงทุน</p>
    </footer>

    <script>
        // --- Configuration & State ---
        let chartInstance = null;
        let simulationInterval = null;
        let currentSimulationData = null; 
        let currentIndex = 0;             
        let isPaused = false;             
        let historyData = JSON.parse(localStorage.getItem('calc_history')) || [];
        
        // Asset Defaults
        const ASSET_RATES = {
            'btc': 60.5, 
            'gold': 8.2, 
            'custom': 10.0
        };

        // --- Initialization ---
        document.addEventListener('DOMContentLoaded', () => {
            initChart();
            loadHistory();
            updateAssetRate();
            
            // Sync Range Slider
            const range = document.getElementById('years');
            const rangeVal = document.getElementById('years_val');
            range.addEventListener('input', (e) => rangeVal.textContent = e.target.value);
            
            // Load Last Settings
            const lastSettings = JSON.parse(localStorage.getItem('last_settings'));
            if(lastSettings) applySettings(lastSettings);
        });

        // --- UI Helpers ---
        function adjustVal(id, step) {
            const el = document.getElementById(id);
            let val = parseFloat(el.value) + step;
            el.value = val.toFixed(1);
        }

        function toggleAssetOptions() {
            const chk = document.getElementById('compare_asset');
            const box = document.getElementById('asset_options');
            if (chk.checked) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }

        function updateAssetRate() {
            const type = document.getElementById('asset_type').value;
            if (ASSET_RATES[type]) {
                document.getElementById('asset_growth').value = ASSET_RATES[type];
            }
        }

        function resetAll() {
            if(confirm("ต้องการเริ่มใหม่ทั้งหมดหรือไม่?")) {
                document.getElementById('amount').value = 3000;
                document.getElementById('inflation').value = 7.0;
                document.getElementById('years').value = 20;
                document.getElementById('years_val').textContent = 20;
                document.getElementById('strategy').value = 'dca_monthly';
                document.getElementById('saving_growth').value = 0;
                
                // Clear Chart
                chartInstance.data.labels = [];
                chartInstance.data.datasets.forEach((dataset) => { dataset.data = []; });
                chartInstance.update();
                
                // Clear text
                document.getElementById('res_nominal').innerText = "0 THB";
                document.getElementById('res_real').innerText = "0 THB";
                document.getElementById('res_asset').innerText = "-";
                document.getElementById('res_asset_diff').innerText = "ไม่ได้เลือกเปรียบเทียบ";
                document.getElementById('analysis_text').innerText = "รีเซ็ตข้อมูลเรียบร้อย...";
            }
        }

        // --- Chart.js Setup ---
        function initChart() {
            const ctx = document.getElementById('wealthChart').getContext('2d');
            
            let gradientRed = ctx.createLinearGradient(0, 0, 0, 400);
            gradientRed.addColorStop(0, 'rgba(255, 0, 85, 0.5)');
            gradientRed.addColorStop(1, 'rgba(255, 0, 85, 0.0)');

            let gradientGreen = ctx.createLinearGradient(0, 0, 0, 400);
            gradientGreen.addColorStop(0, 'rgba(0, 255, 65, 0.5)');
            gradientGreen.addColorStop(1, 'rgba(0, 255, 65, 0.0)');

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'เงินออมสะสม (บาท)',
                            data: [],
                            borderColor: '#9ca3af', 
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 0
                        },
                        {
                            label: 'อำนาจการซื้อจริง',
                            data: [],
                            borderColor: '#ff0055', 
                            backgroundColor: gradientRed,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        },
                        {
                            label: 'มูลค่าสินทรัพย์',
                            data: [],
                            borderColor: '#F7931A', 
                            backgroundColor: gradientGreen,
                            borderWidth: 3,
                            fill: false, 
                            tension: 0.4,
                            pointRadius: 0,
                            hidden: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: {
                        duration: 0 
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { family: 'Prompt' },
                            bodyFont: { family: 'monospace' },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.parsed.y;
                                    let dataIndex = context.dataIndex;
                                    let datasetIndex = context.datasetIndex;
                                    let fmt = new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB', maximumFractionDigits: 0 }).format;
                                    
                                    let simData = currentSimulationData; 
                                    
                                    if (datasetIndex === 0) {
                                        return `⚪ ${label}: ${fmt(value)}`;
                                    } else if (datasetIndex === 1) {
                                        return `🔴 ${label}: ${fmt(value)}`;
                                    } else if (datasetIndex === 2 && simData) {
                                        // สลับให้ value ดึงจากแกนกราฟ (Nominal) และ realVal ดึงจากเบื้องหลัง
                                        let realVal = simData.dataAsset[dataIndex]; 
                                        return [
                                            `🟠 ${label} (พอร์ต): ${fmt(value)}`,
                                            `   ↳ อำนาจซื้อจริง: ${fmt(realVal)}`
                                        ];
                                    }
                                    return `${label}: ${fmt(value)}`;
                                }
                            }
                        },
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    xMin: null, xMax: null, 
                                    borderColor: 'white',
                                    borderWidth: 2,
                                    borderDash: [10, 5],
                                    label: {
                                        display: true,
                                        content: 'กำแพงเงินเฟ้อ (อำนาจซื้อลดลงหลังจากนี้)',
                                        position: 'start',
                                        backgroundColor: 'rgba(255,0,0,0.7)',
                                        font: { family: 'Prompt', size: 10 }
                                    },
                                    display: false
                                }
                            }
                        }
                    },
                    scales: {
                        x: { 
                            grid: { color: '#333' },
                            ticks: { color: '#666', maxTicksLimit: 10 }
                        },
                        y: { 
                            grid: { color: '#333' },
                            ticks: { color: '#666', callback: function(value) { return value/1000 + 'k'; } }
                        }
                    }
                }
            });
        }

        // --- Core Simulation Logic ---
        function runSimulation(isAnimated, shouldSave = true) {
            const strategy = document.getElementById('strategy').value;
            const amount = parseFloat(document.getElementById('amount').value);
            const inflationRate = parseFloat(document.getElementById('inflation').value) / 100;
            const years = parseInt(document.getElementById('years').value);
            const savingGrowth = parseFloat(document.getElementById('saving_growth').value) / 100;
            const compareAsset = document.getElementById('compare_asset').checked;
            const assetGrowth = parseFloat(document.getElementById('asset_growth').value) / 100;

            let periodsPerYear = 12; 
            let labelPrefix = "M";
            if (strategy === 'dca_weekly') { periodsPerYear = 52; labelPrefix = "W"; }
            if (strategy === 'dca_daily') { periodsPerYear = 365; labelPrefix = "D"; }
            if (strategy === 'lump_sum') { periodsPerYear = 1; labelPrefix = "Y"; } 

            const totalPeriods = years * periodsPerYear;
            
            const periodInflation = Math.pow(1 + inflationRate, 1 / periodsPerYear) - 1;
            const periodAssetGrowth = Math.pow(1 + assetGrowth, 1 / periodsPerYear) - 1;

            let labels = [];
            let dataNominal = []; 
            let dataReal = [];    
            let dataAsset = [];   
            let dataAssetNominal = []; 
            
            let currentNominalCost = 0; 
            let currentAssetBalance = 0; 

            let currentContribution = amount;
            let wallIndex = -1;
            let prevReal = 0;

            for (let i = 0; i <= totalPeriods; i++) {
                let yearNum = Math.floor(i / periodsPerYear);
                let label = "";
                if(strategy === 'lump_sum') label = `Year ${i}`;
                else if(strategy === 'dca_monthly') label = `Y${yearNum} M${i%12 + 1}`;
                else label = `Y${yearNum} (${i})`;

                labels.push(label);

                if (i === 0) {
                    if (strategy === 'lump_sum') {
                        currentNominalCost = amount;
                        currentAssetBalance = amount;
                    }
                } else {
                    if (strategy !== 'lump_sum') {
                        if (i % periodsPerYear === 0 && i > 0) {
                            currentContribution *= (1 + savingGrowth);
                        }
                        
                        currentNominalCost += currentContribution;
                        currentAssetBalance += currentContribution;
                        currentAssetBalance *= (1 + periodAssetGrowth);

                    } else {
                        currentAssetBalance *= (1 + periodAssetGrowth);
                    }
                }

                // หักเงินเฟ้อเฉพาะช่วงเวลาที่ผ่านไป (i = 0 เงินเฟ้อยังไม่ทำงาน)
                let inflationFactor = Math.pow(1 + periodInflation, i);

                dataNominal.push(currentNominalCost);
                
                let realCashValue = currentNominalCost / inflationFactor;
                dataReal.push(realCashValue);
                
                if (compareAsset) {
                    dataAssetNominal.push(currentAssetBalance);
                    let realAssetValue = currentAssetBalance / inflationFactor;
                    dataAsset.push(realAssetValue);
                }

                if (i > 0 && strategy !== 'lump_sum') {
                    if (realCashValue < prevReal && wallIndex === -1) {
                        wallIndex = i;
                    }
                }
                prevReal = realCashValue;
            }

            const resultObj = {
                labels, dataNominal, dataReal, dataAsset, dataAssetNominal, wallIndex,
                finalNominal: currentNominalCost,
                finalReal: dataReal[dataReal.length-1],
                finalAssetReal: dataAsset.length > 0 ? dataAsset[dataAsset.length-1] : 0, 
                finalAssetNominal: dataAssetNominal.length > 0 ? dataAssetNominal[dataAssetNominal.length-1] : 0,
                years, strategy, inflationRate, compareAsset
            };
            
            localStorage.setItem('last_settings', JSON.stringify({
                amount, strategy, inflation: document.getElementById('inflation').value
            }));
            
            if (shouldSave) {
                saveToHistory(resultObj);
            }

            if (isAnimated) {
                initSimulation(resultObj);
            } else {
                renderChartFull(resultObj);
                updateStats(resultObj);
                updateAnalysis(resultObj);
            }
        }

        // --- Rendering & Animation Control ---
        function initSimulation(res) {
            document.getElementById('sim_overlay').classList.remove('hidden');
            currentSimulationData = res;
            currentIndex = 0;
            isPaused = false;
            
            chartInstance.data.labels = [];
            chartInstance.data.datasets.forEach(d => d.data = []);
            chartInstance.update();

            updatePauseButtonState(false);
            resumeSimulation();
        }

        function resumeSimulation() {
            if(!currentSimulationData) return;
            let speed = 1000; 

            clearInterval(simulationInterval);
            simulationInterval = setInterval(() => {
                const res = currentSimulationData;

                if (currentIndex >= res.labels.length) {
                    finishSimulation();
                    return;
                }

                chartInstance.data.labels.push(res.labels[currentIndex]);
                chartInstance.data.datasets[0].data.push(res.dataNominal[currentIndex]);
                chartInstance.data.datasets[1].data.push(res.dataReal[currentIndex]);
                if (res.compareAsset) {
                    chartInstance.data.datasets[2].hidden = false;
                    // เปลี่ยนจาก res.dataAsset เป็น res.dataAssetNominal
                    chartInstance.data.datasets[2].data.push(res.dataAssetNominal[currentIndex]); 
                }

                document.getElementById('sim_year').innerText = res.labels[currentIndex];
                chartInstance.update('none'); 

                currentIndex++;
            }, speed);
        }

        function toggleSimulation() {
            if(isPaused) {
                isPaused = false;
                updatePauseButtonState(false);
                resumeSimulation();
            } else {
                isPaused = true;
                updatePauseButtonState(true);
                clearInterval(simulationInterval);
            }
        }

        function updatePauseButtonState(paused) {
            const btn = document.getElementById('btn_toggle_sim');
            const statusDot = document.getElementById('status_dot');
            const statusBg = document.getElementById('status_dot_bg');
            const statusText = document.getElementById('status_text');
            
            if(paused) {
                btn.innerHTML = '<i class="fa-solid fa-play"></i> <span>เล่นต่อ (RESUME)</span>';
                btn.classList.replace('text-red-400', 'text-green-400');
                btn.classList.replace('border-red-500/50', 'border-green-500/50');
                btn.classList.replace('hover:bg-red-500', 'hover:bg-green-500');
                
                statusDot.classList.replace('bg-red-500', 'bg-yellow-500');
                statusBg.classList.add('hidden'); 
                statusText.innerText = "PAUSED";
                statusText.classList.replace('text-[#F7931A]', 'text-yellow-500');
            } else {
                btn.innerHTML = '<i class="fa-solid fa-pause"></i> <span>หยุด (STOP)</span>';
                btn.classList.replace('text-green-400', 'text-red-400');
                btn.classList.replace('border-green-500/50', 'border-red-500/50');
                btn.classList.replace('hover:bg-green-500', 'hover:bg-red-500');
                
                statusDot.classList.replace('bg-yellow-500', 'bg-red-500');
                statusBg.classList.remove('hidden'); 
                statusText.innerText = "LIVE";
                statusText.classList.replace('text-yellow-500', 'text-[#F7931A]');
            }
        }

        function finishSimulation() {
            clearInterval(simulationInterval);
            document.getElementById('sim_overlay').classList.add('hidden');
            if(currentSimulationData) {
                renderChartFull(currentSimulationData);
                updateStats(currentSimulationData);
                updateAnalysis(currentSimulationData);
            }
        }

        function renderChartFull(res) {
            currentSimulationData = res; 
            
            chartInstance.data.labels = res.labels;
            chartInstance.data.datasets[0].data = res.dataNominal;
            chartInstance.data.datasets[1].data = res.dataReal;
            
            chartInstance.data.datasets[2].hidden = !res.compareAsset;
            if (res.compareAsset) {
                // เปลี่ยนจาก res.dataAsset เป็น res.dataAssetNominal
                chartInstance.data.datasets[2].data = res.dataAssetNominal; 
            }

            const anno = chartInstance.options.plugins.annotation.annotations.line1;
            if (res.wallIndex > 0) {
                anno.display = true;
                anno.xMin = res.wallIndex;
                anno.xMax = res.wallIndex;
            } else {
                anno.display = false;
            }
            chartInstance.update();
        }

        function updateStats(res) {
            const fmt = (n) => new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB', maximumFractionDigits: 0 }).format(n);
            document.getElementById('res_nominal').innerText = fmt(res.finalNominal);
            document.getElementById('res_real').innerText = fmt(res.finalReal);
            document.getElementById('res_inflation_txt').innerText = (res.inflationRate * 100).toFixed(1) + "%";
            
            if (res.compareAsset) {
                document.getElementById('res_asset').innerText = fmt(res.finalAssetNominal);
                
                const realGain = res.finalAssetReal - res.finalNominal;
                const realPercent = (realGain / res.finalNominal) * 100;
                const sign = realGain > 0 ? "+" : "";
                const color = realGain >= -0.1 ? "text-green-400" : "text-red-400"; 
                
                document.getElementById('res_asset_diff').innerHTML = 
                    `อำนาจซื้อจริง (Real): <span class="${color}">${fmt(res.finalAssetReal)}</span><br>
                    <span class="${color}">(${sign}${fmt(realGain)} / ${sign}${realPercent.toFixed(0)}%)</span> เทียบกับเงินต้น`;
            } else {
                document.getElementById('res_asset').innerText = "-";
                document.getElementById('res_asset_diff').innerText = "ไม่ได้เลือกเปรียบเทียบ";
            }
        }

        function updateAnalysis(res) {
            let html = "";
            const lossPercent = ((res.finalNominal - res.finalReal) / res.finalNominal * 100).toFixed(1);
            html += `<p class="mb-2">• อำนาจการใช้จ่ายจริงของ "เงินสด" คุณหายไป <b>${lossPercent}%</b> จากเงินเฟ้อที่ ${ (res.inflationRate*100).toFixed(1) }% ต่อปี</p>`;
            if (res.wallIndex > 0) {
                 html += `<p class="mb-2 text-red-400">• ⚠️ <b>กำแพงเงินเฟ้อ:</b> ณ จุดเวลา ${res.labels[res.wallIndex]} คุณเริ่ม "จนลง" แม้จะเติมเงินออมจำนวนเท่าเดิม เนื่องจาก "ดอกเบี้ยทบต้น" ของเงินเฟ้อ ชนะ จำนวนเงินต้นที่คุณใส่เข้าไปเพิ่ม</p>`;
            }
            if (res.compareAsset) {
                 if(res.finalAssetReal > res.finalNominal * 1.01) {
                    const xTimes = (res.finalAssetReal / res.finalNominal).toFixed(1);
                     html += `<p class="mt-2 text-[#00ff41]">• 🚀 <b>สินทรัพย์ชนะขาด:</b> อำนาจซื้อที่แท้จริงเติบโต <b>${xTimes} เท่า</b> ของเงินต้น</p>`;
                 } else if (Math.abs(res.finalAssetReal - res.finalNominal) < (res.finalNominal * 0.05)) { 
                     html += `<p class="mt-2 text-yellow-400">• 🛡️ <b>Wealth Preservation:</b> สินทรัพย์ของคุณรักษามูลค่าได้เท่าทุน (ชนะเงินเฟ้อพอดี)</p>`;
                 } else {
                     html += `<p class="mt-2 text-red-400">• 📉 <b>ยังไม่ชนะเงินเฟ้อ:</b> สินทรัพย์เติบโตน้อยกว่าเงินเฟ้อ อำนาจซื้อจริงจึงลดลง</p>`;
                 }
            }
            document.getElementById('analysis_text').innerHTML = html;
        }

        // --- History System ---
        function saveToHistory(res) {
            const now = new Date();
            const inputs = {
                amount: document.getElementById('amount').value,
                inflation: document.getElementById('inflation').value,
                years: document.getElementById('years').value,
                strategy: document.getElementById('strategy').value,
                savingGrowth: document.getElementById('saving_growth').value,
                compareAsset: document.getElementById('compare_asset').checked,
                assetType: document.getElementById('asset_type').value,
                assetGrowth: document.getElementById('asset_growth').value
            };

            const item = {
                time: now.toLocaleTimeString('th-TH'),
                date: now.toLocaleDateString('th-TH'),
                strategy: res.strategy,
                nominal: res.finalNominal,
                real: res.finalReal,
                asset: res.compareAsset ? res.finalAssetNominal : null, // เก็บ Nominal ไว้แสดง
                inputs: inputs
            };
            
            historyData.unshift(item);
            if(historyData.length > 10) historyData.pop();
            
            localStorage.setItem('calc_history', JSON.stringify(historyData));
            loadHistory();
        }

        function loadHistory() {
            const list = document.getElementById('history_list');
            list.innerHTML = "";
            const fmt = (n) => new Intl.NumberFormat('en-US', { notation: "compact", compactDisplay: "short" }).format(n);

            historyData.forEach((item, index) => {
                let strategyName = item.strategy.split('_')[1] || item.strategy;
                let assetHtml = item.asset ? `<div class="text-[#00ff41] text-[10px]">+Asset: ${fmt(item.asset)}</div>` : '';
                
                const row = `
                    <tr class="border-b border-gray-800 hover:bg-gray-800/50 cursor-pointer" onclick="loadHistoryItem(${index})">
                        <td class="p-2 text-gray-400">${item.time}</td>
                        <td class="p-2 capitalize text-white">${strategyName}</td>
                        <td class="p-2 text-right">
                            <div class="text-white font-mono">${fmt(item.nominal)}</div>
                            <div class="text-[#ff0055] text-[10px]">Real: ${fmt(item.real)}</div>
                            ${assetHtml}
                        </td>
                        <td class="p-2 text-center text-gray-600"><i class="fa-solid fa-chevron-right"></i></td>
                    </tr>
                `;
                list.innerHTML += row;
            });
        }

        function loadHistoryItem(index) {
            const item = historyData[index];
            if (!item || !item.inputs) return;

            const i = item.inputs;
            document.getElementById('amount').value = i.amount;
            document.getElementById('inflation').value = i.inflation;
            document.getElementById('years').value = i.years;
            document.getElementById('years_val').textContent = i.years;
            document.getElementById('strategy').value = i.strategy;
            document.getElementById('saving_growth').value = i.savingGrowth;
            
            document.getElementById('compare_asset').checked = i.compareAsset;
            toggleAssetOptions(); 
            
            document.getElementById('asset_type').value = i.assetType;
            document.getElementById('asset_growth').value = i.assetGrowth;

            runSimulation(false, false);
        }

        function clearHistory() {
            if(confirm("ล้างประวัติทั้งหมด?")) {
                localStorage.removeItem('calc_history');
                historyData = [];
                loadHistory();
            }
        }
        
        function applySettings(settings) {
           if(settings.amount) document.getElementById('amount').value = settings.amount;
        }

    </script>
</body>
</html>