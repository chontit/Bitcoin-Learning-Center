<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Fiat Alchemist | Money Creation out of Thin Air</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --neon-cash: #00ff41;
            --neon-asset: #F7931A;
            --neon-money: #00f3ff;
            --bg-dark: #050505;
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(0, 243, 255, 0.05) 0%, transparent 50%),
                linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,1) 100%);
            background-size: 100% 100%;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .font-tech { font-family: 'Orbitron', 'Chakra Petch', sans-serif; }
        .font-thai-tech { font-family: 'Chakra Petch', sans-serif; }
        
        .neon-box {
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        .neon-box:hover { border-color: rgba(255, 255, 255, 0.2); }

        .input-cyber {
            background: rgba(0,0,0,0.6);
            border: 1px solid #333;
            color: var(--neon-cash);
            font-family: 'Chakra Petch', monospace;
            transition: 0.3s;
        }
        .input-cyber:focus {
            outline: none;
            border-color: var(--neon-cash);
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.2);
        }

        /* Toggle Switch */
        .mode-select {
            background: #111;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 4px;
            display: flex;
            gap: 4px;
        }
        .mode-option {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            font-size: 10px;
            font-family: 'Chakra Petch', sans-serif;
            font-weight: bold;
            transition: all 0.3s;
            opacity: 0.6;
        }
        .mode-option.active {
            opacity: 1;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .mode-conservative.active {
            background: #00ff41;
            color: black;
            box-shadow: 0 0 10px rgba(0, 255, 65, 0.4);
        }
        .mode-aggressive.active {
            background: #F7931A;
            color: black;
            box-shadow: 0 0 10px rgba(247, 147, 26, 0.4);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .block-enter { animation: slideDown 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }

        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-pulse-soft { animation: pulse-soft 3s infinite; }

        #connection_layer {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 50;
        }
        
        .magic-arrow {
            fill: none;
            stroke: url(#gradientArrow); 
            stroke-width: 3;
            stroke-linecap: round;
            stroke-dasharray: 2000;
            stroke-dashoffset: 2000;
            animation: drawLine 1s ease-out forwards;
            filter: drop-shadow(0 0 5px #00f3ff);
        }
        @keyframes drawLine { to { stroke-dashoffset: 0; } }

        /* Story Box & Buttons */
        #story_box {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 400px;
            background: rgba(10, 10, 12, 0.98);
            border: 1px solid var(--neon-money);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s;
            box-shadow: 0 0 60px rgba(0, 243, 255, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #story_box.active {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            pointer-events: auto;
        }

        .btn-story-next {
            margin-top: 20px;
            background: linear-gradient(90deg, #F7931A, #00f3ff);
            color: black;
            font-weight: bold;
            padding: 10px 30px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Chakra Petch', sans-serif;
        }
        .btn-story-next:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(0, 243, 255, 0.4); }

        /* Two Choice Buttons */
        .btn-choice-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            width: 100%;
        }
        .btn-choice {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            font-family: 'Chakra Petch', sans-serif;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .btn-deposit {
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid #00f3ff;
            color: #00f3ff;
        }
        .btn-deposit:hover { background: rgba(0, 243, 255, 0.3); box-shadow: 0 0 15px rgba(0, 243, 255, 0.3); }
        
        .btn-finish {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #666;
            color: #ccc;
        }
        .btn-finish:hover { background: rgba(255, 255, 255, 0.2); border-color: white; color: white; }

        .stack-col {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 12px;
            padding-bottom: 20px;
            justify-content: flex-start;
        }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
    </style>
</head>
<body class="text-gray-300">

    <svg id="connection_layer">
        <defs>
            <linearGradient id="gradientArrow" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" style="stop-color:#F7931A;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#00f3ff;stop-opacity:1" />
            </linearGradient>
            <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                <polygon points="0 0, 10 3.5, 0 7" fill="#00f3ff" />
            </marker>
        </defs>
    </svg>

    <div id="story_box">
        <div id="story_icon" class="text-5xl mb-4 text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.5)]"></div>
        <h3 id="story_title" class="text-xl font-bold text-white font-thai-tech mb-2">Title</h3>
        <p id="story_desc" class="text-sm text-gray-300 leading-relaxed font-light">Description</p>
        
        <div id="story_controls" class="w-full flex justify-center">
            <button id="btn_story_next" class="btn-story-next">
                ถัดไป <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
        </div>
    </div>

    <div id="bankrun_fail_screen" class="fixed inset-0 z-[200] hidden flex flex-col items-center justify-center text-center p-8 bg-black/95">
        <i class="fa-solid fa-radiation text-7xl text-red-500 mb-6 animate-spin-slow"></i>
        <h1 class="text-4xl md:text-6xl font-bold text-red-500 font-thai-tech mb-4 tracking-widest">SYSTEM FAILURE</h1>
        <p class="text-white text-lg mb-8 max-w-xl font-mono">
            เกิดวิกฤตสภาพคล่อง! (Liquidity Crisis)<br>
            <span class="text-red-400">ความต้องการถอนเงิน > เงินสดที่มีอยู่จริง</span>
        </p>
        <div class="grid grid-cols-2 gap-4 mb-8 w-full max-w-md">
            <div class="bg-red-900/20 border border-red-500/50 p-2 rounded">
                <div class="text-xs text-red-400 font-thai-tech">เงินสดที่มีจริง</div>
                <div id="fail_reserves" class="font-mono text-xl font-bold">0</div>
            </div>
            <div class="bg-red-900/20 border border-red-500/50 p-2 rounded">
                <div class="text-xs text-red-400 font-thai-tech">ยอดเงินฝากทั้งหมด</div>
                <div id="fail_claims" class="font-mono text-xl font-bold">0</div>
            </div>
        </div>
        <button onclick="location.reload()" class="px-8 py-3 bg-white text-black font-bold rounded hover:bg-gray-200 transition uppercase tracking-widest font-thai-tech">
            <i class="fa-solid fa-power-off"></i> เริ่มระบบใหม่ (Reboot)
        </button>
    </div>

    <div id="bankrun_safe_screen" class="fixed inset-0 z-[200] hidden flex flex-col items-center justify-center text-center p-8 bg-black/95">
        <i class="fa-solid fa-shield-halved text-7xl text-[#00ff41] mb-6"></i>
        <h1 class="text-4xl md:text-6xl font-bold text-[#00ff41] font-thai-tech mb-4 tracking-widest">SYSTEM SECURE</h1>
        <p class="text-white text-lg mb-8 max-w-xl font-mono">
            สถานะปกติ: 100% Full Reserve<br>
            <span class="text-gray-400">ธนาคารมีเงินสดเพียงพอคืนให้ผู้ฝากทุกคน</span>
        </p>
        <div class="grid grid-cols-2 gap-4 mb-8 w-full max-w-md">
            <div class="bg-green-900/20 border border-green-500/50 p-2 rounded">
                <div class="text-xs text-green-400 font-thai-tech">เงินสดที่มีจริง</div>
                <div id="safe_reserves" class="font-mono text-xl font-bold">0</div>
            </div>
            <div class="bg-green-900/20 border border-green-500/50 p-2 rounded">
                <div class="text-xs text-green-400 font-thai-tech">ยอดเงินฝากทั้งหมด</div>
                <div id="safe_claims" class="font-mono text-xl font-bold">0</div>
            </div>
        </div>
        <button onclick="document.getElementById('bankrun_safe_screen').classList.add('hidden')" class="px-8 py-3 bg-[#00ff41] text-black font-bold rounded hover:bg-green-400 transition uppercase tracking-widest font-thai-tech">
            <i class="fa-solid fa-check"></i> ตกลง (OK)
        </button>
    </div>

    <header class="p-4 border-b border-gray-800 bg-black/80 backdrop-blur sticky top-0 z-40 flex justify-between items-center h-[80px]">
        <div class="flex items-center gap-3">
            <div class="relative">
                <i class="fa-solid fa-infinity text-3xl md:text-4xl text-[#00f3ff] animate-pulse-soft"></i>
                <div class="absolute -inset-1 bg-[#00f3ff] blur opacity-20 rounded-full"></div>
            </div>
            <div class="flex flex-col">
                <h1 class="text-lg md:text-xl font-bold font-tech text-white tracking-widest leading-none">
                    THE FIAT <span class="text-[#00f3ff] drop-shadow-[0_0_5px_rgba(0,243,255,0.8)]">ALCHEMIST</span>
                </h1>
                <span class="text-[10px] md:text-xs text-gray-400 font-thai-tech mt-1 tracking-wider text-[#F7931A]">Money Creation out of Thin Air</span>
            </div>
        </div>
        <a href="/" class="group flex items-center gap-2 px-4 py-2 border border-gray-700 hover:border-[#F7931A] rounded transition">
            <i class="fa-solid fa-house text-gray-400 group-hover:text-[#F7931A] transition"></i>
            <span class="hidden md:inline text-xs text-gray-400 group-hover:text-white font-thai-tech uppercase tracking-wider">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 relative z-10">
        
        <div class="lg:col-span-4 xl:col-span-3 space-y-4 flex flex-col">
            
            <div class="neon-box p-5 rounded-xl border-t-4 border-[#00ff41]">
                <h2 class="text-white font-bold mb-6 flex items-center gap-2 text-sm uppercase tracking-widest font-thai-tech text-[#00ff41]">
                    <i class="fa-solid fa-sliders"></i> ตั้งค่าระบบ
                </h2>
                
                <div class="mb-5">
                    <label class="block text-xs text-gray-500 mb-2 flex justify-between uppercase font-thai-tech">
                        <span>เงินสดตั้งต้น (Vault Cash)</span>
                        <span id="init_cash_txt" class="text-[#00ff41] font-bold font-mono">100 ฿</span>
                    </label>
                    <input type="range" id="init_cash" min="100" max="1000000" step="100" value="100" class="w-full accent-[#00ff41] h-2 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition">
                    <div class="flex justify-between text-[10px] text-gray-600 mt-1 font-thai-tech">
                        <span>100</span>
                        <span>1,000,000</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs text-gray-500 mb-2 font-thai-tech uppercase">เลือกโหมดการคำนวณ (Simulation Mode)</label>
                    <div class="mode-select">
                        <div class="mode-option mode-conservative active" id="mode_conservative" onclick="setMode('conservative')">
                            🔰 ตามทฤษฎี<br>(Conservative)
                        </div>
                        <div class="mode-option mode-aggressive" id="mode_aggressive" onclick="setMode('aggressive')">
                            🚀 ความเป็นจริง!<br>(Aggressive)
                        </div>
                    </div>
                    <div class="text-[9px] text-gray-500 mt-2 font-thai-tech" id="mode_desc">
                        *เสกเงินตามทฤษฎี (Money Multiplier) ปล่อยกู้จากเงินสดที่มี
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs text-gray-500 mb-2 flex justify-between uppercase font-thai-tech">
                        <span>การดำรงสินทรัพย์สภาพคล่อง (%)</span>
                        <span id="reserve_txt" class="text-[#F7931A] font-bold font-mono">10%</span>
                    </label>
                    <input type="range" id="reserve_slider" min="1" max="100" value="10" class="w-full accent-[#F7931A] h-2 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 transition">
                    <div class="flex justify-between text-[10px] text-gray-600 mt-1 font-thai-tech">
                        <span>1% (เสกยับ)</span>
                        <span>100% (ปลอดภัย)</span>
                    </div>
                </div>

                <button id="btn_next" onclick="startProcess()" class="w-full py-4 bg-gradient-to-r from-[#1a1a1a] to-[#0a0a0a] border border-[#00f3ff]/30 hover:border-[#00f3ff] text-[#00f3ff] font-bold rounded-lg transition shadow-[0_0_15px_rgba(0,243,255,0.1)] hover:shadow-[0_0_25px_rgba(0,243,255,0.3)] group relative overflow-hidden mb-4">
                    <div class="absolute inset-0 bg-[#00f3ff]/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <span class="relative z-10 flex flex-col items-center">
                        <span class="text-lg uppercase tracking-[0.2em] font-tech"><i class="fa-solid fa-play"></i> EXECUTE</span>
                        <span class="text-[10px] text-gray-500 group-hover:text-white mt-1 font-thai-tech">เริ่มกระบวนการเสกเงิน</span>
                    </span>
                </button>

                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-800">
                     <button onclick="triggerBankRun()" class="text-red-500 hover:text-white hover:bg-red-900/50 border border-red-900/30 p-2 rounded text-[10px] font-bold uppercase tracking-wider transition flex flex-col items-center justify-center gap-1 font-thai-tech h-12">
                        <i class="fa-solid fa-bomb text-sm"></i> <span>BANK RUN</span>
                    </button>
                    <button onclick="resetSim()" class="text-gray-400 hover:text-white hover:bg-gray-800 border border-gray-700 p-2 rounded text-[10px] font-bold uppercase tracking-wider transition flex flex-col items-center justify-center gap-1 font-thai-tech h-12">
                        <i class="fa-solid fa-rotate-left text-sm"></i> <span>RESET</span>
                    </button>
                </div>
            </div>

            <div class="neon-box p-5 rounded-xl flex-grow flex flex-col justify-between hidden lg:flex">
                <div>
                    <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                        <span class="text-gray-500 text-xs uppercase tracking-wider font-thai-tech">รอบที่ (Cycle)</span>
                        <span id="step_count" class="text-white font-mono text-lg">0</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-end">
                            <span class="text-gray-500 text-xs font-thai-tech">เงินสดที่มีสำรองจริง</span>
                            <span id="disp_reserves" class="text-[#00ff41] font-mono font-bold text-lg">100</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-gray-500 text-xs font-thai-tech">สินทรัพย์รวม (Assets)</span>
                            <span id="txt_assets_side" class="text-[#F7931A] font-mono font-bold text-xl">100</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-gray-500 text-xs font-thai-tech">ปริมาณเงินทั้งหมด (M2)</span>
                            <span id="txt_money_side" class="text-[#00f3ff] font-mono font-bold text-2xl drop-shadow-[0_0_5px_rgba(0,243,255,0.5)]">100</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-2 bg-black/40 rounded border border-gray-800 text-center">
                         <span class="text-[10px] text-gray-500 uppercase font-thai-tech">ตัวคูณเงิน (Multiplier)</span>
                         <div id="txt_multiplier" class="text-white font-mono font-bold text-xl">1.00x</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 xl:col-span-9 h-[600px] lg:h-auto neon-box rounded-xl overflow-hidden flex flex-col relative bg-[#080808]">
            
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none opacity-5">
                <i class="fa-solid fa-building-columns text-9xl"></i>
            </div>

            <div class="grid grid-cols-2 gap-4 md:gap-24 px-4 md:px-8 py-4 border-b border-gray-800 bg-[#0a0a0a]/95 backdrop-blur z-30 shadow-lg sticky top-0">
                <div class="text-center border-b-2 border-[#F7931A]/30 pb-2 flex flex-col items-center">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-[#F7931A] font-bold uppercase text-xs tracking-[0.2em] font-tech"><i class="fa-solid fa-file-contract"></i> Assets</h3>
                        <span id="header_assets_val" class="bg-[#F7931A] text-black text-xs font-bold font-mono px-2 py-0.5 rounded">100</span>
                    </div>
                    <div class="text-[9px] text-gray-500 font-thai-tech">เงินสด + สัญญาเงินกู้ (ทรัพย์สินของสถาบันการเงิน)</div>
                </div>
                
                <div class="text-center border-b-2 border-[#00f3ff]/30 pb-2 flex flex-col items-center">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-[#00f3ff] font-bold uppercase text-xs tracking-[0.2em] font-tech"><i class="fa-solid fa-coins"></i> Liabilities</h3>
                        <span id="header_liabs_val" class="bg-[#00f3ff] text-black text-xs font-bold font-mono px-2 py-0.5 rounded">100</span>
                    </div>
                    <div class="text-[9px] text-gray-500 font-thai-tech">เงินฝากที่สถาบันการเงินเสกขึ้นมา (หนี้สิน)</div>
                </div>
            </div>

            <div class="flex-grow overflow-y-auto relative custom-scroll px-4 md:px-8 pb-10 scroll-smooth" id="viewport">
                
                <div class="w-full grid grid-cols-2 gap-4 md:gap-24 relative min-h-full pt-6">
                    <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-white/10 to-transparent -ml-px z-0"></div>

                    <div id="col_assets" class="stack-col relative z-10">
                    </div>

                    <div id="col_liabs" class="stack-col relative z-10">
                    </div>

                </div>
            </div>
        </div>

    </main>

    <footer class="mt-auto py-6 border-t border-gray-900 bg-black/60 backdrop-blur-sm text-center">
        <p class="text-[10px] md:text-xs text-gray-600 font-thai-tech tracking-wider">
            &copy; 2026 Chollatis Bitcoiner <span class="mx-2">|</span> <span class="text-[#F7931A] font-bold">Don't Trust, Verify.</span>
        </p>
    </footer>

    <script>
        let initialCash = 100;
        let currentAssets = 100;
        let currentMoney = 100;
        let step = 0;
        let isRunning = false;
        let currentMode = 'conservative'; 
        let lendableCash = 100; 
        
        const elAssetsCol = document.getElementById('col_assets');
        const elLiabsCol = document.getElementById('col_liabs');
        const viewport = document.getElementById('viewport');
        const svgLayer = document.getElementById('connection_layer');
        const storyBox = document.getElementById('story_box');
        const btnNext = document.getElementById('btn_story_next');
        const elReserveSlider = document.getElementById('reserve_slider');
        const elReserveTxt = document.getElementById('reserve_txt');
        const elInitCash = document.getElementById('init_cash');
		const elInitCashTxt = document.getElementById('init_cash_txt');
        
        const elHeaderAssets = document.getElementById('header_assets_val');
        const elHeaderLiabs = document.getElementById('header_liabs_val');

        function init() {
            initialCash = parseFloat(elInitCash.value) || 100;
            currentAssets = initialCash;
            currentMoney = initialCash;
            lendableCash = initialCash; 
            step = 0;
            isRunning = false;

            elAssetsCol.innerHTML = '';
            elLiabsCol.innerHTML = '';
            svgLayer.innerHTML = '<defs><linearGradient id="gradientArrow" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#F7931A;stop-opacity:1" /><stop offset="100%" style="stop-color:#00f3ff;stop-opacity:1" /></linearGradient><marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto"><polygon points="0 0, 10 3.5, 0 7" fill="#00f3ff" /></marker></defs>';

            const cashBlock = document.createElement('div');
            cashBlock.className = "w-full p-3 md:p-4 rounded-lg bg-[#00ff41]/5 border border-[#00ff41]/50 text-[#00ff41] flex flex-col md:flex-row justify-between items-center h-auto md:h-20 shadow-[0_0_15px_rgba(0,255,65,0.1)] shrink-0 gap-2";
            cashBlock.id = "vault_block";
            cashBlock.innerHTML = `
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-[#00ff41]/20 flex items-center justify-center border border-[#00ff41] shrink-0"><i class="fa-solid fa-vault"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-xs md:text-sm font-bold font-tech whitespace-nowrap">Vault Cash</div>
                        <div class="text-[9px] md:text-[10px] opacity-70 font-thai-tech truncate">เงินสดสำรองจริง</div>
                    </div>
                </div>
                <div class="text-lg md:text-xl font-mono font-bold" id="vault_display_val">${formatNum(initialCash)}</div>
            `;
            elAssetsCol.appendChild(cashBlock);

            const equityBlock = document.createElement('div');
            equityBlock.className = "w-full p-3 md:p-4 rounded-lg bg-gray-800/50 border border-gray-600 text-gray-400 flex flex-col md:flex-row justify-between items-center h-auto md:h-20 opacity-50 grayscale shrink-0 gap-2";
            equityBlock.innerHTML = `
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-building-columns"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-xs md:text-sm font-bold font-tech whitespace-nowrap">Equity</div>
                        <div class="text-[9px] md:text-[10px] opacity-70 font-thai-tech truncate">ทุนเจ้าของ</div>
                    </div>
                </div>
                <div class="text-lg md:text-xl font-mono font-bold">${formatNum(initialCash)}</div>
            `;
            elLiabsCol.appendChild(equityBlock);

            updateStats();
        }

        // --- Mode Selector ---
        function setMode(mode) {
            currentMode = mode;
            document.getElementById('mode_conservative').classList.remove('active');
            document.getElementById('mode_aggressive').classList.remove('active');
            document.getElementById('mode_' + mode).classList.add('active');
            
            const desc = document.getElementById('mode_desc');
            if(mode === 'conservative') {
                desc.innerText = "*คำนวณตามตำรา (Money Multiplier) ปล่อยกู้จากยอดเงินฝากที่มี (ปลอดภัยกว่า)";
                desc.className = "text-[9px] text-green-500 mt-2 font-thai-tech";
            } else {
                desc.innerText = "*คำนวณแบบ Basel III (Capital Adequacy) ใช้สินทรัพย์เป็นฐานคำนวณเพดานสูงสุด (เสี่ยงสูง)";
                desc.className = "text-[9px] text-red-500 mt-2 font-thai-tech";
            }
            init();
        }

        elReserveSlider.oninput = function() { 
            elReserveTxt.innerText = this.value + "%"; 
            if(this.value < 5) elReserveTxt.classList.add('text-red-500');
            else elReserveTxt.classList.remove('text-red-500');
        }
		elInitCash.oninput = function() { 
			// อัปเดตตัวเลขโชว์ด้านขวา และ รีเซ็ตระบบทันที
			elInitCashTxt.innerText = parseInt(this.value).toLocaleString() + " ฿";
			resetSim(); 
		}

        const formatNum = n => n.toLocaleString('en-US', {maximumFractionDigits: 0});

        async function startProcess() {
            if(isRunning) return;
            isRunning = true;
            
            const mainBtn = document.getElementById('btn_next');
            const cashInput = document.getElementById('init_cash');
            
            mainBtn.classList.add('opacity-50', 'cursor-not-allowed', 'grayscale');
            mainBtn.innerHTML = '<span class="text-sm tracking-widest font-tech animate-pulse">PROCESSING...</span>';
            cashInput.disabled = true;
            
            step++;
            const reserveRate = parseInt(elReserveSlider.value) / 100;
            const lendingPercent = 100 - parseInt(elReserveSlider.value); // <--- เพิ่มบรรทัดนี้เพื่อคำนวณ % ที่ปล่อยกู
			
            // --- LOGIC SWITCH ---
            let loanAmount = 0;
            
            if (currentMode === 'conservative') {
                // Formula: CurrentAssets (Accumulated) * (1 - Reserve)
                loanAmount = currentAssets * (1 - reserveRate);
            } else {
                const maxAllowed = currentAssets / reserveRate;
                loanAmount = maxAllowed - currentAssets;
            }

            if (loanAmount < 1) loanAmount = 0;

            await showStoryStep(
                'fa-file-signature', 
                'ขั้นตอนที่ 1: ผู้กู้เซ็นสัญญา (Contract)', 
                currentMode === 'conservative' 
                    ? `ผู้ให้กู้แบ่งเงิน ${lendingPercent}% จาก "สินทรัพย์" ที่มี (${formatNum(currentAssets)}) เพื่อปล่อยกู้จำนวน ${formatNum(loanAmount)} บาท`
                    : `ลูกค้าเซ็นสัญญาเงินกู้ ${formatNum(loanAmount)} บาท ธนาคารเก็บสัญญานี้ไว้เป็นสินทรัพย์`
            );

            await showStoryStep(
                'fa-wand-magic-sparkles', 
                'ขั้นตอนที่ 2: รับเงินกู้ (Deposit)', 
                `เงินกู้ ${formatNum(loanAmount)} บาท ถูกโอนเข้าบัญชีผู้กู้! กลายเป็น "เงินฝากใหม่ (Liability)" ในระบบ`
            );
            
            const moneyBlock = createBlock('money', step, loanAmount);
            elLiabsCol.appendChild(moneyBlock);
            scrollToBottom();
            
            if (currentMode === 'aggressive') {
                await showStoryStep(
                    'fa-link', 
                    'ขั้นตอนที่ 3: ค้ำประกัน (Collateral)', 
                    `เงินฝากก้อนนี้มีค่าเพราะมี "สัญญาเงินกู้" หนุนหลัง ธนาคารจึงลงบัญชีสัญญาเงินกู้เป็น "ทรัพย์สิน"`
                );

                const assetBlock = createBlock('asset', step, loanAmount);
                elAssetsCol.appendChild(assetBlock);
                scrollToBottom();
                setTimeout(() => { drawMagicArrow(assetBlock, moneyBlock); }, 300);
            } else {
                // Conservative Mode: Visualize Cash Flow Out (Arrow from Vault)
                const vaultBlock = document.getElementById('vault_block');
                setTimeout(() => { drawMagicArrow(vaultBlock, moneyBlock); }, 300);
            }

            // --- Updated Choice Step ---
            let choiceDesc = "";
            if (currentMode === 'conservative') {
                choiceDesc = `หากผู้กู้ฝากเงินกลับมา สินทรัพย์ของท่านจะเพิ่มเป็น ${formatNum(currentAssets + loanAmount)} บาท ท่านต้องการทำอย่างไร?`;
            } else {
                choiceDesc = `สินทรัพย์ใหม่ (${formatNum(loanAmount)}) ถูกนับเป็นฐานทุนสำรองเรียบร้อยแล้ว (สถาบันฯ นำสัญญากู้มาเป็นสินทรัพย์)`;
            }

            const choice = await showStoryChoice(
                'fa-infinity', 
                'จบรอบ (Cycle Complete)', 
                choiceDesc
            );

            // Handle Choice
            if (choice === 'finish') {
                hideStory();
                if (currentMode === 'aggressive') {
                    currentAssets += loanAmount;
                } else {
                    // Conservative (Finish): Remove the loaned amount from assets
                    currentAssets -= loanAmount; 
                    if(currentAssets < 1) currentAssets = 1;
                    
                    const vaultVal = document.getElementById('vault_display_val');
                    if(vaultVal) vaultVal.innerText = formatNum(currentAssets);
                }
                currentMoney += loanAmount;
            } else if (choice === 'deposit') {
                hideStory();
                
                const reDepositBlock = createBlock('redeposit', step, loanAmount);
                elAssetsCol.appendChild(reDepositBlock);
                scrollToBottom();
                
                setTimeout(() => { drawMagicArrow(moneyBlock, reDepositBlock); }, 500);
                
                if (currentMode === 'aggressive') {
                    currentAssets += loanAmount; // Add loan contract
                    currentAssets += loanAmount; // Add returned deposit asset
                } else {
                    // Conservative (Re-Deposit): Add the returned money to Assets
                    currentAssets += loanAmount; 
                    // Do NOT update Vault Cash visual (since it's a re-deposit block)
                    // But calculation base for next round is now higher
                }
                
                currentMoney += loanAmount;
            }

            updateStats();
            
            setTimeout(() => {
                const arrows = document.querySelectorAll('.magic-arrow');
                arrows.forEach(el => {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 1000);
                });
            }, 3000);
            
            svgLayer.innerHTML = '<defs><linearGradient id="gradientArrow" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#F7931A;stop-opacity:1" /><stop offset="100%" style="stop-color:#00f3ff;stop-opacity:1" /></linearGradient><marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto"><polygon points="0 0, 10 3.5, 0 7" fill="#00f3ff" /></marker></defs>'; 
            
            isRunning = false;
            mainBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'grayscale');
            mainBtn.innerHTML = '<div class="absolute inset-0 bg-[#00f3ff]/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div><span class="relative z-10 flex flex-col items-center"><span class="text-lg uppercase tracking-[0.2em] font-tech"><i class="fa-solid fa-play"></i> EXECUTE</span><span class="text-[10px] text-gray-500 group-hover:text-white mt-1 font-thai-tech">เริ่มกระบวนการเสกเงิน</span></span>';
            cashInput.disabled = false;
        }

        // --- New Choice Function ---
        function showStoryChoice(icon, title, desc) {
            return new Promise(resolve => {
                document.getElementById('story_icon').innerHTML = `<i class="fa-solid ${icon}"></i>`;
                document.getElementById('story_title').innerText = title;
                document.getElementById('story_desc').innerText = desc;
                
                const controls = document.getElementById('story_controls');
                
                // Logic to show/hide "Deposit" button based on mode
                if (currentMode === 'aggressive') {
                     controls.innerHTML = `
                        <div class="btn-choice-container">
                            <button id="btn_finish_cycle" class="btn-choice btn-finish w-full">
                                <i class="fa-solid fa-check text-lg"></i>
                                <span>เสร็จสิ้น (Finish)</span>
                            </button>
                        </div>
                    `;
                } else {
                    controls.innerHTML = `
                        <div class="btn-choice-container">
                            <button id="btn_deposit_back" class="btn-choice btn-deposit">
                                <i class="fa-solid fa-arrow-rotate-left text-lg"></i>
                                <span>ฝากเพิ่ม<br>(Re-Deposit)</span>
                            </button>
                            <button id="btn_finish_cycle" class="btn-choice btn-finish">
                                <i class="fa-solid fa-check text-lg"></i>
                                <span>เสร็จสิ้น<br>(Finish)</span>
                            </button>
                        </div>
                    `;
                }
                
                storyBox.classList.add('active');

                // Handlers
                if(currentMode !== 'aggressive') {
                    document.getElementById('btn_deposit_back').onclick = () => { resolve('deposit'); };
                }
                document.getElementById('btn_finish_cycle').onclick = () => { resolve('finish'); };
            });
        }

        function showStoryStep(icon, title, desc) {
            return new Promise(resolve => {
                document.getElementById('story_icon').innerHTML = `<i class="fa-solid ${icon}"></i>`;
                document.getElementById('story_title').innerText = title;
                document.getElementById('story_desc').innerText = desc;
                
                // Standard Next Button
                document.getElementById('story_controls').innerHTML = `
                    <button id="btn_story_next" class="btn-story-next">
                        ถัดไป <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                `;
                
                storyBox.classList.add('active');
                const btn = document.getElementById('btn_story_next');
                const handler = () => { btn.removeEventListener('click', handler); resolve(); };
                btn.addEventListener('click', handler);
            });
        }

        function hideStory() { storyBox.classList.remove('active'); }

        function createBlock(type, idx, amount) {
            const div = document.createElement('div');
            div.className = "w-full p-3 md:p-4 rounded-lg flex flex-col md:flex-row justify-between items-center h-auto md:h-24 shadow-lg block-enter relative z-10 shrink-0 border-l-4 gap-2";
            
            if (type === 'asset') {
                div.className += " bg-[#F7931A]/10 border-[#F7931A] text-[#F7931A]";
                div.innerHTML = `
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="w-8 h-8 rounded bg-[#F7931A] text-black flex items-center justify-center font-bold text-sm font-mono shrink-0">${idx}</div>
                        <div class="overflow-hidden">
                            <div class="text-xs font-bold uppercase tracking-wider font-tech whitespace-nowrap">Loan Contract</div>
                            <div class="text-[9px] md:text-[10px] opacity-60 font-thai-tech truncate">สัญญาเงินกู้ (Asset)</div>
                        </div>
                    </div>
                    <div class="text-lg md:text-xl font-mono font-bold">+${formatNum(amount)}</div>
                `;
            } else if (type === 'redeposit') {
                // New Re-Deposit Block Style
                div.className += " bg-[#F7931A]/20 border-[#F7931A] text-[#F7931A]";
                div.innerHTML = `
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="w-8 h-8 rounded bg-[#F7931A] text-black flex items-center justify-center font-bold text-sm font-mono shrink-0"><i class="fa-solid fa-arrow-rotate-left"></i></div>
                        <div class="overflow-hidden">
                            <div class="text-xs font-bold uppercase tracking-wider font-tech whitespace-nowrap">Re-Deposit</div>
                            <div class="text-[9px] md:text-[10px] opacity-60 font-thai-tech truncate">เงินฝากกลับ (Asset)</div>
                        </div>
                    </div>
                    <div class="text-lg md:text-xl font-mono font-bold">+${formatNum(amount)}</div>
                `;
            } else {
                div.className += " bg-[#00f3ff]/10 border-[#00f3ff] text-[#00f3ff]";
                div.innerHTML = `
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="w-8 h-8 rounded bg-[#00f3ff] text-black flex items-center justify-center font-bold text-sm font-mono shrink-0">${idx}</div>
                        <div class="overflow-hidden">
                            <div class="text-xs font-bold uppercase tracking-wider font-tech whitespace-nowrap">New Deposit</div>
                            <div class="text-[9px] md:text-[10px] opacity-60 font-thai-tech truncate">เงินฝากเสกใหม่ (Liability)</div>
                        </div>
                    </div>
                    <div class="text-lg md:text-xl font-mono font-bold">+${formatNum(amount)}</div>
                `;
            }
            return div;
        }

        function drawMagicArrow(fromEl, toEl) {
            const r1 = fromEl.getBoundingClientRect();
            const r2 = toEl.getBoundingClientRect();
            const x1 = r1.right; const y1 = r1.top + r1.height / 2;
            const x2 = r2.left; const y2 = r2.top + r2.height / 2;
            
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            
            // Check direction (Right to Left or Left to Right)
            const isReturn = x1 > x2; 
            const cp1x = isReturn ? x1 - 50 : x1 + 50;
            const cp2x = isReturn ? x2 + 50 : x2 - 50;

            const pathString = `M ${x1} ${y1} C ${cp1x} ${y1}, ${cp2x} ${y2}, ${x2} ${y2}`;
            
            path.setAttribute("d", pathString);
            path.setAttribute("class", "magic-arrow");
            path.setAttribute("marker-end", "url(#arrowhead)");
            svgLayer.appendChild(path);
        }

        function scrollToBottom() {
            viewport.scrollTo({ top: viewport.scrollHeight, behavior: 'smooth' });
        }

        function updateStats() {
            // Header Stats
            if(elHeaderAssets) elHeaderAssets.innerText = formatNum(currentAssets);
            if(elHeaderLiabs) elHeaderLiabs.innerText = formatNum(currentMoney);

            // Sidebar Stats
            const elDispReserves = document.getElementById('disp_reserves');
            if(elDispReserves) {
                // In Conservative mode, display remaining lendable cash
                elDispReserves.innerText = formatNum(currentMode === 'conservative' ? lendableCash : initialCash);
            }

            const elTxtAssetsSide = document.getElementById('txt_assets_side');
            if(elTxtAssetsSide) elTxtAssetsSide.innerText = formatNum(currentAssets);

            const elTxtMoneySide = document.getElementById('txt_money_side');
            if(elTxtMoneySide) elTxtMoneySide.innerText = formatNum(currentMoney);
            
            const elStepCount = document.getElementById('step_count');
            if(elStepCount) elStepCount.innerText = step;
            
            const elMult = document.getElementById('txt_multiplier');
            if(elMult) {
                const mult = (currentMoney / initialCash).toFixed(2);
                elMult.innerText = mult + "x";
            }
        }

        function triggerBankRun() {
            if (currentMoney > initialCash) {
                document.getElementById('bankrun_fail_screen').classList.remove('hidden');
                document.getElementById('fail_reserves').innerText = formatNum(currentMode === 'conservative' ? lendableCash : initialCash);
                document.getElementById('fail_claims').innerText = formatNum(currentMoney);
            } else {
                document.getElementById('bankrun_safe_screen').classList.remove('hidden');
                document.getElementById('safe_reserves').innerText = formatNum(initialCash);
                document.getElementById('safe_claims').innerText = formatNum(currentMoney);
            }
        }

        function resetSim() { init(); }

        document.addEventListener('DOMContentLoaded', () => { 
            setMode('conservative'); 
        });

    </script>
</body>
</html>