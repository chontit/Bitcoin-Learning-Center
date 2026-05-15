<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store of Value Assets & Investments | Wealth Insight</title>
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
            --neon-blue: #007bff;
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

        .brand-font { font-family: 'Orbitron', sans-serif; }
        
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
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(247, 147, 26, 0.1);
        }

        .btn-neon {
            background: linear-gradient(45deg, #F7931A, #ffb347);
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-neon:hover {
            box-shadow: 0 0 30px #F7931A;
            transform: scale(1.02);
        }

        /* Asset Buttons */
        .asset-btn {
            border: 1px solid #333;
            background: rgba(0,0,0,0.5);
            transition: 0.2s;
            opacity: 0.6;
        }
        .asset-btn:hover { border-color: #666; opacity: 0.8; }
        .asset-btn.active { 
            background: rgba(247, 147, 26, 0.15); 
            border-color: var(--neon-btc); 
            box-shadow: 0 0 10px rgba(247, 147, 26, 0.2);
            opacity: 1;
        }

        .input-dark {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: #fff;
        }
        .input-dark:focus {
            outline: none;
            border-color: var(--neon-btc);
            box-shadow: 0 0 5px var(--neon-btc);
        }

        /* Range Slider Styling */
        input[type=range] {
            -webkit-appearance: none; 
            background: transparent; 
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px; width: 16px;
            border-radius: 50%;
            background: var(--neon-btc);
            cursor: pointer;
            margin-top: -6px;
            box-shadow: 0 0 10px var(--neon-btc);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px;
            cursor: pointer;
            background: #333;
            border-radius: 2px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-chart-line text-3xl text-[#F7931A] animate-pulse"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font">
                    Assets Investments Simulation
                </h1>
                
                <div class="flex items-center gap-2 text-[10px] uppercase font-bold tracking-wider mt-1 text-gray-400">
                    <span class="relative flex h-2 w-2">
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00ff41]"></span>
                    </span>
                    <span id="header_sync_date">DATA SYNC: กำลังโหลด...</span>
                </div>

            </div>
        </div>
        <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm bg-black/30">
            <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 space-y-4">
            
            <div class="neon-box p-6 rounded-xl">
                <h2 class="text-lg font-bold mb-4 text-[#F7931A] flex items-center gap-2">
                    <i class="fa-solid fa-sliders"></i> ตั้งค่าการลงทุน
                </h2>

                <div class="mb-4">
                    <label class="text-xs text-gray-400 uppercase font-bold mb-2 block">
                        เลือกสินทรัพย์ (เลือกได้มากกว่า 1 เพื่อเปรียบเทียบ)
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="toggleAsset('btc')" id="btn_btc" class="asset-btn active p-2 rounded flex flex-col items-center gap-1">
                            <i class="fa-brands fa-bitcoin text-[#F7931A]"></i> <span class="text-xs font-bold text-white">BTC</span>
                        </button>
                        <button onclick="toggleAsset('gold')" id="btn_gold" class="asset-btn p-2 rounded flex flex-col items-center gap-1">
                            <i class="fa-solid fa-coins text-[#ffd700]"></i> <span class="text-xs font-bold text-white">Gold</span>
                        </button>
                        <button onclick="toggleAsset('spx')" id="btn_spx" class="asset-btn p-2 rounded flex flex-col items-center gap-1">
                            <i class="fa-solid fa-chart-line text-[#007bff]"></i> <span class="text-xs font-bold text-white">S&P500</span>
                        </button>
                        <button onclick="toggleAsset('set')" id="btn_set" class="asset-btn p-2 rounded flex flex-col items-center gap-1">
                            <i class="fa-solid fa-building-columns text-[#ff0055]"></i> <span class="text-xs font-bold text-white">SET</span>
                        </button>
                        <button onclick="toggleAsset('custom')" id="btn_custom" class="asset-btn col-span-2 p-2 rounded flex flex-row items-center justify-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-purple-500"></i> <span class="text-xs font-bold text-white">กำหนดผลตอบแทนเอง</span>
                        </button>
                    </div>
                </div>

                <div id="custom_rate_box" class="hidden mb-4 bg-gray-900/50 p-3 rounded border border-purple-500/30">
                    <label class="flex justify-between text-xs text-purple-400 mb-1 font-bold">
                        <span>Custom Growth (CAGR)</span> <span id="custom_rate_val">5%</span>
                    </label>
                    <input type="range" id="custom_rate" min="1" max="50" value="10" class="w-full accent-purple-500" oninput="document.getElementById('custom_rate_val').innerText=this.value+'%'; runSimulation()">
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">ระยะเวลา</label>
                        <select id="timeframe" class="w-full p-2 rounded input-dark text-sm" onchange="runSimulation()">
                            <option value="1">1 ปีล่าสุด</option>
                            <option value="3">3 ปีล่าสุด</option>
                            <option value="5" selected>5 ปีล่าสุด</option>
                            <option value="10">10 ปีล่าสุด</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">รูปแบบ</label>
                        <select id="strategy" class="w-full p-2 rounded input-dark text-sm" onchange="runSimulation()">
                            <option value="dca">DCA (รายเดือน)</option>
                            <option value="lump">All In (ก้อนเดียวจบ)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm text-white mb-2 font-bold">
                        จำนวนเงินลงทุน (บาท)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">฿</span>
                        </div>
                        <input type="number" id="invest_amount" value="3000" min="500" step="500"
                            class="w-full pl-8 p-3 rounded input-dark text-lg font-mono text-[#F7931A] font-bold"
                            oninput="runSimulation()">
                    </div>
                    <div class="text-[10px] text-gray-500 mt-1 text-right">ใส่จำนวนเงินที่ต้องการลงทุน</div>
                </div>

                <div class="mb-6 border-t border-gray-800 pt-4">
                    <label class="flex justify-between text-xs text-red-400 mb-2 font-bold">
                        <span>อัตราเงินเฟ้อ (Inflation)</span>
                        <span id="inf_val">7.0%</span>
                    </label>
                    <input type="range" id="inflation" min="0" max="15" value="7" step="0.1" class="w-full h-1 bg-gray-700 rounded cursor-pointer accent-red-500" oninput="document.getElementById('inf_val').innerText=this.value+'%'; runSimulation()">
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <button onclick="runSimulation()" class="col-span-2 btn-neon py-2 rounded text-sm shadow-[0_0_15px_rgba(247,147,26,0.5)]">
                        <i class="fa-solid fa-calculator"></i> คำนวณใหม่
                    </button>
                    <button onclick="resetAll()" class="col-span-1 bg-gray-800 hover:bg-gray-700 text-gray-400 py-2 rounded text-xs transition border border-gray-700">
                        <i class="fa-solid fa-rotate-left"></i> รีเซ็ต
                    </button>
                </div>
            </div>

            <div class="neon-box p-4 rounded-xl">
                 <div class="flex justify-between items-center mb-2 pb-2 border-b border-gray-700">
                    <span class="text-xs text-gray-400">เงินต้นสะสม (Nominal)</span>
                    <span class="font-mono font-bold text-white" id="res_invested">0 ฿</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs text-[#ff0055]">อำนาจการซื้อจริงคงเหลือ</span>
                    <span class="font-mono font-bold text-[#ff0055]" id="res_real">0 ฿</span>
                </div>
                
                <div class="text-[10px] text-gray-500 uppercase font-bold mb-2 pt-2 border-t border-gray-700">
                    สรุปมูลค่าสินทรัพย์ (Assets)
                </div>
                <div id="asset_summary_list" class="space-y-2">
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-6">
            
            <div class="neon-box p-4 md:p-6 rounded-xl relative h-[500px] flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h2 class="text-xl font-bold brand-font text-white">WEALTH <span class="text-[#F7931A]">GROWTH</span></h2>
                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                            <i class="fa-regular fa-calendar-days text-[#F7931A]"></i>
                            <span id="date_range_display">...</span>
                        </p>
                    </div>
                    
                    <div class="text-[10px] text-gray-400 text-right">
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-1 bg-gray-500 border-dashed border-gray-400"></div> เงินต้นสะสม (%)</div>
                        <div class="flex items-center justify-end gap-2"><div class="w-3 h-1 bg-[#ff0055]"></div> อำนาจซื้อจริง (%)</div>
                    </div>
                </div>
                <div class="relative w-full h-full flex-grow">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="neon-box p-6 rounded-xl bg-gradient-to-r from-gray-900 to-black border-l-4 border-[#F7931A]">
                <h3 class="text-md font-bold text-white mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-quote-left text-gray-600"></i> บทสรุปความมั่งคั่งที่แท้จริง (Real Wealth Narrative)
                </h3>
                <div id="narrative_text" class="text-sm text-gray-300 leading-7 font-light">
                    กำลังคำนวณ...
                </div>
            </div>

        </div>
    </main>

    <footer class="w-full py-6 border-t border-gray-900 bg-black/80 backdrop-blur text-center mt-8">
        <p class="text-xs text-gray-500 tracking-wider">
            &copy; 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner</span> <span class="mx-2 text-gray-700">|</span> Don't Trust, Verify.
        </p>
    </footer>

    <script>
        // ==========================================
        // 🔧 1. DATA BANK 
        // ==========================================
        const DATA_CONFIG = {
            lastMonth: 1, // ก.พ.
            lastYear: 2026 // ค.ศ.
        };

        const ASSET_PATTERNS = {
            'btc': [
                236, 310, 377, 431, 368, 435, 415, 448, 532, 668, 624, 571, 608, 697, 
                742, 966, 964, 1191, 1070, 1350, 2298, 2465, 2856, 4734, 4326, 6434, 
                9948, 13880, 10149, 10315, 6929, 9242, 7492, 6386, 7725, 7017, 6598, 
                6303, 3971, 3693, 3413, 3791, 4096, 5269, 8547, 10760, 10085, 9594, 
                8298, 9150, 7551, 7168, 9328, 8528, 6421, 8627, 9446, 9133, 11357, 
                11655, 10778, 13816, 19700, 28993, 33141, 45241, 58783, 57775, 37341, 
                35037, 41490, 47156, 43834, 61359, 56974, 46214, 38492, 43179, 45517, 
                37640, 31763, 19925, 23323, 20059, 19425, 20498, 17170, 16528, 23127, 
                23136, 28476, 29235, 27219, 30469, 29231, 25932, 26966, 34661, 37719, 
                42258, 42558, 61161, 71285, 60632, 67477, 62676, 64612, 58967, 63302, 
                70231, 96441, 93381, 102412, 84321, 82538, 94181, 104646, 107179, 115750, 
                108269, 114065, 109554, 90382, 87496, 78635, 66630
            ],
            'gold': [
                1115, 1142, 1065, 1061, 1118, 1238, 1232, 1293, 1215, 1321, 1351, 1309, 1316, 1277,
                1173, 1151, 1210, 1248, 1249, 1268, 1269, 1241, 1269, 1321, 1280, 1271, 1275, 1303, 
                1345, 1318, 1325, 1316, 1298, 1252, 1224, 1201, 1190, 1215, 1222, 1282, 1321, 1313, 
                1292, 1283, 1305, 1409, 1412, 1520, 1472, 1513, 1464, 1517, 1590, 1586, 1575, 1685, 
                1729, 1782, 1975, 1967, 1885, 1878, 1777, 1898, 1847, 1733, 1707, 1769, 1903, 1770, 
                1814, 1813, 1756, 1783, 1774, 1829, 1797, 1909, 1936, 1896, 1837, 1807, 1765, 1711, 
                1661, 1633, 1768, 1823, 1928, 1827, 1969, 1990, 1963, 1919, 1966, 1940, 1848, 1984, 
                2036, 2063, 2039, 2044, 2232, 2285, 2327, 2326, 2448, 2503, 2634, 2744, 2650, 2624, 
                2797, 2857, 3123, 3289, 3289, 3303, 3290, 3447, 3858, 4003, 4219, 4318, 4895, 4980
            ],
            'spx': [
                1920, 2079, 2080, 2044, 1940, 1932, 2060, 2065, 2097, 2099, 2174, 2171, 2168, 2126, 
                2199, 2239, 2279, 2364, 2363, 2384, 2412, 2423, 2470, 2472, 2519, 2575, 2648, 2674, 
                2824, 2714, 2641, 2648, 2705, 2718, 2816, 2902, 2914, 2712, 2760, 2507, 2704, 2784, 
                2834, 2946, 2752, 2942, 2980, 2926, 2977, 3038, 3141, 3231, 3226, 2954, 2585, 2912, 
                3044, 3100, 3271, 3500, 3363, 3270, 3622, 3756, 3714, 3811, 3973, 4181, 4204, 4297, 
                4395, 4523, 4308, 4605, 4567, 4766, 4516, 4374, 4530, 4132, 4132, 3785, 4130, 3955, 
                3586, 3872, 4080, 3839, 4077, 3970, 4109, 4169, 4180, 4450, 4589, 4508, 4288, 4194, 
                4568, 4770, 4846, 5096, 5254, 5036, 5278, 5460, 5522, 5648, 5762, 5705, 6032, 5882, 
                6041, 5955, 5612, 5569, 5912, 6205, 6339, 6460, 6688, 6840, 6849, 6845, 6939, 6881
            ],
			'set': [
                1349, 1395, 1360, 1288, 1301, 1332, 1408, 1405, 1424, 1445, 1524, 1548, 1483, 1496, 
                1510, 1543, 1577, 1560, 1575, 1566, 1562, 1575, 1576, 1616, 1673, 1721, 1697, 1754, 
                1827, 1830, 1776, 1780, 1727, 1596, 1702, 1722, 1756, 1669, 1642, 1564, 1642, 1653, 
                1639, 1674, 1620, 1730, 1712, 1655, 1637, 1601, 1591, 1580, 1514, 1341, 1126, 1302, 
				1343, 1339, 1329, 1311, 1237, 1195, 1408, 1449, 1467, 1497, 1587, 1583, 1594, 1588, 
                1522, 1639, 1606, 1623, 1569, 1658, 1649, 1685, 1695, 1667, 1663, 1568, 1576, 1639, 
                1590, 1609, 1635, 1669, 1671, 1622, 1609, 1529, 1534, 1503, 1556, 1566, 1471, 1382, 
                1380, 1416, 1365, 1371, 1378, 1368, 1346, 1301, 1321, 1359, 1449, 1466, 1428, 1400, 
                1315, 1204, 1158, 1197, 1149, 1090, 1242, 1237, 1274, 1310, 1257, 1260, 1326, 1494
            ]
        };

        // ==========================================
        // 🛠 2. APP LOGIC 
        // ==========================================

        const ASSET_CONFIG = {
            'btc': { color: '#F7931A', name: 'Bitcoin (BTC)' },
            'gold': { color: '#ffd700', name: 'Gold' },
            'spx': { color: '#007bff', name: 'S&P 500' },
            'set': { color: '#ff0055', name: 'SET Index' },
            'custom': { color: '#a855f7', name: 'Custom' }
        };

        let state = {
            assets: ['btc'],
            chart: null
        };

        const THAI_MONTHS = ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];

        function setHeaderDate() {
            const m = THAI_MONTHS[DATA_CONFIG.lastMonth];
            const y = DATA_CONFIG.lastYear;
            document.getElementById('header_sync_date').innerText = `ข้อมูลราคาตั้งแต่ ก.ย.2015 ถึง ${m}${y}`;
        }

        function fmtMoney(n) {
            return new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB', maximumFractionDigits: 0 }).format(n);
        }

        function generateDateLabels(count) {
            const labels = [];
            const endDate = new Date(DATA_CONFIG.lastYear, DATA_CONFIG.lastMonth, 1);
            
            for (let i = 0; i < count; i++) {
                const offset = count - 1 - i;
                const d = new Date(endDate.getFullYear(), endDate.getMonth() - offset, 1);
                const thaiYear = d.getFullYear(); 
                labels.push(`${THAI_MONTHS[d.getMonth()]} ${thaiYear}`);
            }
            return labels;
        }

        function interpolateData(baseArray, targetLength) {
            if (!baseArray || baseArray.length === 0) return Array(targetLength).fill(0);
            
            const result = [];
            const step = Math.max(0, (baseArray.length - 1)) / Math.max(1, (targetLength - 1));
            
            for (let i = 0; i < targetLength; i++) {
                const index = i * step;
                const lower = Math.floor(index);
                const upper = Math.ceil(index);
                const weight = index - lower;
                
                if (upper >= baseArray.length) {
                    result.push(baseArray[baseArray.length - 1]);
                } else {
                    result.push(baseArray[lower] * (1 - weight) + baseArray[upper] * weight);
                }
            }
            return result;
        }

        function generateCustomGrowth(months, annualRatePercent) {
            const monthlyRate = Math.pow(1 + (annualRatePercent / 100), 1/12) - 1;
            let data = [100];
            for(let i=1; i<months; i++) data.push(data[i-1] * (1 + monthlyRate));
            return data;
        }

        function toggleAsset(asset) {
            const idx = state.assets.indexOf(asset);
            if (idx > -1) {
                if(state.assets.length > 1) {
                    state.assets.splice(idx, 1);
                    document.getElementById(`btn_${asset}`).classList.remove('active');
                }
            } else {
                state.assets.push(asset);
                document.getElementById(`btn_${asset}`).classList.add('active');
            }

            const customBox = document.getElementById('custom_rate_box');
            if(state.assets.includes('custom')) customBox.classList.remove('hidden');
            else customBox.classList.add('hidden');
            
            runSimulation();
        }

        function resetAll() {
            document.getElementById('invest_amount').value = 3000;
            document.getElementById('inflation').value = 7;
            document.getElementById('inf_val').innerText = "7.0%";
            document.getElementById('timeframe').value = 5;
            document.getElementById('strategy').value = 'dca';
            
            state.assets = ['btc'];
            document.querySelectorAll('.asset-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn_btc').classList.add('active');
            document.getElementById('custom_rate_box').classList.add('hidden');

            runSimulation();
        }

        function runSimulation() {
            const amount = parseFloat(document.getElementById('invest_amount').value) || 0;
            const years = parseInt(document.getElementById('timeframe').value);
            const strategy = document.getElementById('strategy').value;
            const inflationRate = parseFloat(document.getElementById('inflation').value) / 100;
            const months = years * 12;

            let dataInvested = [];
            let dataReal = [];
            let totalInvested = 0;
            const monthlyInflation = Math.pow(1 + inflationRate, 1/12) - 1;

            for (let i = 0; i < months; i++) {
                if (strategy === 'dca') {
                    totalInvested += amount;
                } else if (strategy === 'lump' && i === 0) {
                    totalInvested = amount;
                }
                dataInvested.push(totalInvested);
                dataReal.push(totalInvested / Math.pow(1 + monthlyInflation, i));
            }

            let assetsResults = [];

            state.assets.forEach(assetKey => {
                let priceData = [];
                if (assetKey === 'custom') {
                    const rate = parseFloat(document.getElementById('custom_rate').value);
                    priceData = generateCustomGrowth(months, rate);
                } else {
                    const rawArr = ASSET_PATTERNS[assetKey];
                    if (rawArr.length >= months) {
                        priceData = rawArr.slice(-months);
                    } else {
                        priceData = interpolateData(rawArr, months);
                    }
                }

                let totalUnits = 0;
                let assetValueHistory = [];
                let assetRealHistory = []; // เพิ่มตัวแปรเก็บอำนาจซื้อรายเดือน

                for (let i = 0; i < months; i++) {
                    const price = priceData[i] || 1; 
                    if (strategy === 'dca') {
                        totalUnits += amount / price;
                    } else if (strategy === 'lump' && i === 0) {
                        totalUnits = amount / price;
                    }
                    
                    const nominalValue = totalUnits * price;
                    const realValue = nominalValue / Math.pow(1 + monthlyInflation, i); // คำนวณอำนาจซื้อ
                    
                    assetValueHistory.push(nominalValue);
                    assetRealHistory.push(realValue); 
                }

                assetsResults.push({
                    key: assetKey,
                    name: ASSET_CONFIG[assetKey].name,
                    color: ASSET_CONFIG[assetKey].color,
                    data: assetValueHistory,
                    realData: assetRealHistory, // เก็บประวัติอำนาจซื้อส่งไปกราฟ
                    finalValue: assetValueHistory[months-1],
                    finalRealValue: assetRealHistory[months-1] // มูลค่าอำนาจซื้อเดือนสุดท้าย
                });
            });

            const pctInvested = dataInvested.map(() => 0); 
            const pctReal = dataReal.map((val, i) => dataInvested[i] === 0 ? 0 : ((val - dataInvested[i]) / dataInvested[i]) * 100);

            let datasets = [
                 { label: 'เงินต้นสะสม', data: pctInvested, borderColor: '#6b7280', borderWidth: 2, borderDash: [5, 5], fill: false, pointRadius: 0, order: 99 },
                 { label: 'อำนาจซื้อของเงินสด', data: pctReal, borderColor: '#ef4444', borderWidth: 2, borderDash: [2, 2], fill: false, pointRadius: 0, order: 98 }
            ];

            assetsResults.forEach(asset => {
                const pctData = asset.data.map((val, i) => dataInvested[i] === 0 ? 0 : ((val - dataInvested[i]) / dataInvested[i]) * 100);
                datasets.push({
                    label: asset.name,
                    data: pctData,
                    borderColor: asset.color,
                    backgroundColor: asset.color + '10',
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 20,
                    tension: 0.2
                });
            });

            const labels = generateDateLabels(months);
            if(labels.length > 0) {
                document.getElementById('date_range_display').innerText = `${labels[0]}  —  ${labels[labels.length-1]}`;
            }

            const lastInvested = dataInvested[months-1];
            const lastReal = dataReal[months-1];

            document.getElementById('res_invested').innerText = fmtMoney(lastInvested);
            document.getElementById('res_real').innerText = fmtMoney(lastReal);

            renderChart(labels, datasets, dataInvested, dataReal, assetsResults);
            updateUIOutputs(lastInvested, lastReal, assetsResults, years);
        }

        function renderChart(labels, datasets, absInvested, absReal, absAssets) {
            const ctx = document.getElementById('mainChart').getContext('2d');
            if (state.chart) state.chart.destroy();

            state.chart = new Chart(ctx, {
                type: 'line',
                data: { labels: labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.95)',
                            titleFont: { family: 'Prompt', size: 14, weight: 'bold' },
                            bodyFont: { family: 'Prompt', size: 12 },
                            padding: 12,
                            borderColor: '#333',
                            borderWidth: 1,
                            itemSort: (a, b) => b.raw - a.raw,
                            callbacks: {
                                title: (items) => `📅 ${items[0].label}`,
                                label: function(ctx) {
                                    const i = ctx.dataIndex;
                                    const valPct = ctx.parsed.y;
                                    const label = ctx.dataset.label;
                                    const dsIndex = ctx.datasetIndex; 
                                    
                                    if (dsIndex === 0) return `⚪ ${label}: ${fmtMoney(absInvested[i])}`;
                                    if (dsIndex === 1) return `🔴 ${label}: ${fmtMoney(absReal[i])} (${valPct > 0 ? '+' : ''}${valPct.toFixed(2)}%)`;
                                    
                                    const assetObj = absAssets.find(a => a.name === label);
                                    if (assetObj) {
                                        const absVal = assetObj.data[i];
                                        const realVal = assetObj.realData[i];
                                        let icon = valPct >= 0 ? '🟢' : '🔻';
                                        
                                        // คืนค่าเป็น Array เพื่อให้แสดงหลายบรรทัดใน Tooltip 1 ไอเทม
                                        return [
                                            `${icon} ${label}: ${fmtMoney(absVal)} (${valPct > 0 ? '+' : ''}${valPct.toFixed(2)}%)`,
                                            `    ↳ อำนาจซื้อจริง: ${fmtMoney(realVal)}`
                                        ];
                                    }
                                    return `${label}: ${fmtMoney(ctx.parsed.y)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#666', maxTicksLimit: 10, maxRotation: 0 }, grid: { display: false } },
                        y: { grid: { color: '#222' }, ticks: { color: '#666', callback: (val) => val + '%' } }
                    }
                }
            });
        }

        function updateUIOutputs(invested, real, assets, years) {
            const fmt = (n) => new Intl.NumberFormat('th-TH').format(Math.round(n));
            const sidebarList = document.getElementById('asset_summary_list');
            let sidebarHTML = '';

            const sortedAssets = [...assets].sort((a,b) => b.finalValue - a.finalValue);

            sortedAssets.forEach(asset => {
                const profit = asset.finalValue - invested;
                const pct = (profit / invested) * 100;
                const colorClass = profit >= 0 ? 'text-[#00ff41]' : 'text-red-400';
                const sign = profit > 0 ? '+' : '';
                const realText = `(อำนาจซื้อ: ${fmt(asset.finalRealValue)})`; // แสดงอำนาจซื้อใน sidebar

                sidebarHTML += `
                    <div class="flex justify-between items-center bg-gray-900/50 p-2 rounded border border-gray-700/50">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold" style="color: ${asset.color}">${asset.name}</span>
                            <span class="text-[10px] text-gray-500">${sign}${pct.toFixed(1)}%</span>
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-sm text-white">${fmt(asset.finalValue)}</div>
                            <div class="text-[10px] ${colorClass}">${sign}${fmt(profit)}</div>
                            <div class="text-[9px] text-gray-500 mt-1">${realText}</div>
                        </div>
                    </div>
                `;
            });
            sidebarList.innerHTML = sidebarHTML;

            const narrativeBox = document.getElementById('narrative_text');
            const lossPct = ((invested - real) / invested) * 100;
            const bestAsset = sortedAssets[0]; 

            let html = `
                <p class="mb-3">
                    หากคุณเลือกที่จะ <b>"ไม่ลงทุน"</b> และเก็บเงินสดจำนวน <span class="text-white font-bold">${fmt(invested)} บาท</span> ไว้เฉยๆ ตลอดระยะเวลา ${years} ปี:
                    <br>ผลกระทบจากเงินเฟ้อทำให้อำนาจการซื้อของคุณลดลงไป <span class="text-red-500 font-bold">-${lossPct.toFixed(1)}%</span> 
                    หรือเปรียบเสมือนเงินของคุณเหลือมูลค่าในการซื้อของจริงเพียง <span class="text-red-500 font-bold border-b border-red-500 border-dashed">${fmt(real)} บาท</span> เท่านั้น (เงินหายไป ${fmt(invested-real)} บาท ในความรู้สึก)
                </p>
                <hr class="border-gray-800 my-3">
                <p>
                    แต่หากคุณเลือกออมใน <b><span style="color:${bestAsset.color}">${bestAsset.name}</span></b> (สินทรัพย์ที่สร้างผลตอบแทนสูงสุดในพอร์ตของคุณ):
                    <br>พอร์ตของคุณจะมีตัวเลขในบัญชี <span class="text-[#00ff41] font-bold text-lg">${fmt(bestAsset.finalValue)} บาท</span>
                    <span class="text-xs text-gray-400">(เทียบเท่าอำนาจซื้อ <span class="text-white font-bold">${fmt(bestAsset.finalRealValue)} บาท</span> ในวันนี้)</span>
            `;

            if(bestAsset.finalRealValue > invested) {
                const realGain = bestAsset.finalRealValue - invested;
                const realPct = (realGain / invested) * 100;
                html += `
                    <br>ซึ่งหมายความว่า นอกจากคุณจะรักษามูลค่าเงินต้นไว้ได้แล้ว ยังสร้างความมั่งคั่ง <b>"ที่แท้จริง"</b> เพิ่มขึ้น <span class="text-[#00ff41] font-bold">+${realPct.toFixed(1)}%</span> 
                    (มีอำนาจซื้อเพิ่มขึ้น ${fmt(realGain)} บาท) เอาชนะเงินเฟ้อได้อย่างสมบูรณ์ ✅
                `;
            } else if (bestAsset.finalValue > invested) {
                const realLoss = invested - bestAsset.finalRealValue;
                const nominalGain = bestAsset.finalValue - invested;
                html += `
                    <br>แม้ตัวเลขพอร์ตจะดูเหมือนกำไร <span class="text-[#00ff41]">+${fmt(nominalGain)} บาท</span> แต่เมื่อเจอกับอัตราเงินเฟ้อที่คุณตั้งไว้ 
                    <b>อำนาจการซื้อที่แท้จริงของคุณกลับลดลง</b> <span class="text-red-400">-${fmt(realLoss)} บาท</span> 
                    (การลงทุนนี้ช่วยชะลอการด้อยค่าของเงิน แต่ยังไม่ชนะเงินเฟ้อ) ⚠️
                `;
            } else {
                const nominalLoss = invested - bestAsset.finalValue;
                html += `
                    <br>ซึ่งนอกจากตัวเลขพอร์ตจะขาดทุนลง <span class="text-red-400">-${fmt(nominalLoss)} บาท</span> แล้ว เมื่อรวมกับเงินเฟ้อ 
                    อำนาจซื้อที่แท้จริงจะยิ่งหดหายไปมากกว่าเดิม (ในกรอบเวลานี้มีความผันผวนสูงมาก) ⚠️
                `;
            }
            html += `</p>`;
            
            narrativeBox.innerHTML = html;
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            setHeaderDate();
            runSimulation();
        });

    </script>
</body>
</html>