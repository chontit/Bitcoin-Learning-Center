<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ECDSA Visualizer | Mobile Optimized</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Kanit:wght@300;400;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-blue: #00f3ff;
            --neon-red: #ff0055;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Kanit', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(247, 147, 26, 0.03) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0, 243, 255, 0.03) 0%, transparent 20%);
            /* Allow scrolling on body for mobile */
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-weight: 300;
        }

        .brand-font { font-family: 'Chakra Petch', sans-serif; letter-spacing: 0.5px; }
        .mono-font { font-family: 'Share Tech Mono', monospace; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--neon-btc); }

        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        .neon-box:hover {
            border-color: var(--neon-btc);
            box-shadow: 0 0 20px rgba(247, 147, 26, 0.15);
        }

        .btn-action {
            background: linear-gradient(45deg, #F7931A, #ffb347);
            color: #000;
            font-weight: 700;
            font-family: 'Chakra Petch', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.2s;
            clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);
        }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(247, 147, 26, 0.6); }
        .btn-action:active { transform: translateY(1px); }

        .btn-secondary {
            border: 1px solid #444;
            color: #aaa;
            font-family: 'Chakra Petch', sans-serif;
            transition: 0.3s;
        }
        .btn-secondary:hover { border-color: white; color: white; background: rgba(255,255,255,0.1); }

        .btn-reset {
            border: 1px solid var(--neon-red);
            color: var(--neon-red);
            font-family: 'Chakra Petch', sans-serif;
            transition: 0.3s;
        }
        .btn-reset:hover { background: var(--neon-red); color: white; box-shadow: 0 0 15px var(--neon-red); }

        .btn-real-g {
            background: rgba(0, 255, 65, 0.1);
            border: 1px solid var(--neon-green);
            color: var(--neon-green);
            font-family: 'Chakra Petch', sans-serif;
            transition: 0.3s;
        }
        .btn-real-g:hover { background: var(--neon-green); color: black; box-shadow: 0 0 20px rgba(0, 255, 65, 0.4); }

        .btn-algo {
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid var(--neon-blue);
            color: var(--neon-blue);
            font-family: 'Chakra Petch', sans-serif;
            transition: 0.3s;
        }
        .btn-algo:hover { background: var(--neon-blue); color: black; box-shadow: 0 0 20px rgba(0, 243, 255, 0.4); }

        #canvas-container {
            position: relative;
            width: 100%;
            height: 100%;
            /* Fixed Height for Mobile Canvas to prevent squashing */
            min-height: 400px;
            background: radial-gradient(circle at center, #1a1a1a 0%, #000 100%);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #333;
            cursor: grab;
        }
        #canvas-container:active { cursor: grabbing; }
        canvas { display: block; }

        .overlay-bg {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(15px);
            z-index: 100;
        }
        .hex-string {
            word-break: break-all;
            font-family: 'Share Tech Mono', monospace;
            line-height: 1.4;
            font-size: 0.75rem; 
        }

        .key-display {
            background: rgba(0,0,0,0.6);
            border-left: 2px solid var(--neon-blue);
            padding: 8px;
            font-size: 0.8em;
            color: var(--neon-blue);
        }
        
        .math-step { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .algo-table th { text-align: left; padding: 8px; border-bottom: 1px solid #333; color: #888; font-size: 0.75rem; white-space: nowrap; }
        .algo-table td { padding: 8px; border-bottom: 1px solid #222; font-family: 'Share Tech Mono', monospace; font-size: 0.75rem; }
        .algo-table tr:hover { background: rgba(255,255,255,0.05); }
        
        @media (min-width: 768px) {
            .algo-table th, .algo-table td { font-size: 0.9rem; }
        }
    </style>
</head>
<body class="text-gray-300">

    <header class="w-full px-4 py-3 border-b border-gray-800 flex justify-between items-center bg-black/80 sticky top-0 z-50 backdrop-blur-md h-16 shrink-0">
        <div class="flex items-center gap-3">
            <i class="fa-brands fa-bitcoin text-3xl text-[#F7931A] animate-pulse"></i>
            <div>
                <h1 class="text-lg md:text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font leading-none uppercase">
                    ECDSA Visualizer
                </h1>
                <p class="text-[10px] text-gray-500 mono-font tracking-wider mt-1">Secp256k1 Simulator</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="/" class="px-3 py-1 rounded border border-gray-700 hover:border-[#F7931A] hover:text-[#F7931A] transition text-xs flex items-center gap-2 font-light">
                <i class="fa-solid fa-house"></i> <span class="hidden md:inline">หน้าหลัก (Home)</span>
            </a>
        </div>
    </header>

    <div id="real-g-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-6 text-center transition-opacity duration-300">
        <div class="max-w-4xl w-full border border-[#00ff41] bg-black/90 p-6 rounded-xl shadow-[0_0_50px_rgba(0,255,65,0.2)] relative overflow-hidden">
            <button onclick="hideOverlay('real-g-overlay')" class="absolute top-4 right-4 text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></button>
            <h2 class="text-2xl md:text-3xl font-bold text-[#00ff41] brand-font mb-2 uppercase"><i class="fa-solid fa-fingerprint"></i> จุดกำเนิด G ของจริง (Bitcoin)</h2>
            <p class="text-xs text-gray-400 mb-6 font-light">ค่ามาตรฐาน Secp256k1 (ฐาน 10)</p>
            <div class="grid grid-cols-1 gap-6 text-left">
                <div><div class="text-[#F7931A] text-xs font-bold mb-1 brand-font">พิกัด X</div><div class="hex-string text-[#F7931A] border border-[#F7931A]/30 p-3 rounded bg-[#F7931A]/5"><span id="type-x"></span></div></div>
                <div><div class="text-[#00f3ff] text-xs font-bold mb-1 brand-font">พิกัด Y</div><div class="hex-string text-[#00f3ff] border border-[#00f3ff]/30 p-3 rounded bg-[#00f3ff]/5"><span id="type-y"></span></div></div>
            </div>
            <button onclick="hideOverlay('real-g-overlay')" class="mt-4 px-6 py-2 bg-[#00ff41] text-black font-bold rounded hover:bg-[#00cc33] transition brand-font">เข้าใจแล้ว</button>
        </div>
    </div>

    <div id="algo-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-2 md:p-4 transition-opacity duration-300">
        <div class="max-w-5xl w-full h-[95vh] md:h-[90vh] border border-[#00f3ff] bg-[#0a0a0a] rounded-xl shadow-[0_0_50px_rgba(0,243,255,0.2)] relative flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/50">
                <h2 class="text-lg md:text-xl font-bold text-[#00f3ff] brand-font uppercase flex items-center gap-2">
                    <i class="fa-solid fa-bolt"></i> Double-and-Add <span class="hidden md:inline">Lab</span>
                </h2>
                <button onclick="hideOverlay('algo-overlay')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <div class="flex-grow flex flex-col md:flex-row overflow-hidden">
                <div class="w-full md:w-1/3 p-4 md:p-6 bg-black/30 border-b md:border-b-0 md:border-r border-gray-800 flex flex-col gap-4 overflow-y-auto flex-shrink-0">
                    <div>
                        <label class="block text-xs text-gray-500 mb-2 font-bold">ป้อน PRIVATE KEY (d)</label>
                        <div class="flex gap-2">
                            <input type="number" id="algo-input" value="22" min="1" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white mono-font focus:border-[#00f3ff] outline-none">
                            <button onclick="runAlgo()" class="px-4 bg-[#00f3ff] text-black font-bold rounded hover:bg-[#00d0dd] brand-font">คำนวณ</button>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">ลองใส่เลขเยอะๆ เช่น 1000 หรือ 123456</p>
                    </div>
                    <div class="bg-gray-900/50 p-3 rounded border border-gray-800">
                        <div class="text-xs text-gray-500 mb-1">เลขฐานสอง (Binary):</div>
                        <div id="algo-binary" class="text-[#F7931A] mono-font text-sm md:text-lg break-all">10110</div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-1 gap-2">
                        <div class="bg-red-900/20 p-2 rounded border-l-2 border-red-500">
                            <div class="text-[10px] text-red-400 font-bold">แบบเดิม (Naive)</div>
                            <div class="text-lg md:text-2xl font-bold text-white mono-font" id="stat-naive">22 <span class="text-[10px] text-gray-500">ops</span></div>
                        </div>
                        <div class="bg-green-900/20 p-2 rounded border-l-2 border-[#00ff41]">
                            <div class="text-[10px] text-[#00ff41] font-bold">แบบ Double-and-Add</div>
                            <div class="text-lg md:text-2xl font-bold text-white mono-font" id="stat-algo">8 <span class="text-[10px] text-gray-500">ops</span></div>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-2/3 p-0 flex flex-col bg-[#050505] overflow-hidden flex-grow">
                    <div class="p-3 bg-gray-900 border-b border-gray-800 text-xs text-gray-400 font-bold flex justify-between shrink-0">
                        <span>EXECUTION LOG</span>
                        <span class="text-[#00f3ff]">R = จุดปัจจุบัน</span>
                    </div>
                    <div class="flex-grow overflow-y-auto p-0 md:p-4 custom-scroll">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[350px] md:min-w-0 algo-table text-left border-collapse">
                                <thead class="sticky top-0 bg-[#050505] z-10 shadow-sm">
                                    <tr>
                                        <th class="p-2 w-10">Step</th>
                                        <th class="p-2 w-10">Bit</th>
                                        <th class="p-2">Action</th>
                                        <th class="p-2">Result (R)</th>
                                    </tr>
                                </thead>
                                <tbody id="algo-steps"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-grow container mx-auto p-4 gap-4 grid grid-cols-1 lg:grid-cols-12 lg:h-[calc(100vh-64px)] lg:overflow-hidden h-auto overflow-visible">
        
        <div class="lg:col-span-4 flex flex-col gap-4 h-auto lg:h-full overflow-visible lg:overflow-hidden">
            <div class="lg:overflow-y-auto lg:pr-1 lg:h-full custom-scroll flex flex-col gap-4 lg:pb-4">
                
                <div class="neon-box p-5 rounded-xl bg-[#0a0a0a] flex-shrink-0">
                    <h2 class="text-sm font-bold mb-3 text-[#F7931A] brand-font flex items-center justify-between">
                        <span><i class="fa-solid fa-key"></i> PRIVATE KEY (d)</span>
                        <span class="text-[10px] bg-gray-800 px-2 py-0.5 rounded text-gray-400 font-sans">INPUT</span>
                    </h2>
                    
                    <div class="flex flex-col items-center justify-center bg-gradient-to-b from-gray-900 to-black p-4 rounded border border-gray-800 mb-4 shadow-inner relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#F7931A] to-transparent opacity-50"></div>
                        <div id="private-key-display" class="text-5xl md:text-6xl font-bold mono-font text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] z-10">1</div>
                        <div class="text-[10px] text-gray-600 mt-1 z-10 uppercase tracking-widest font-sans">จำนวนครั้งที่กระโดด (Scalar)</div>
                        <i class="fa-brands fa-bitcoin absolute -bottom-4 -right-4 text-8xl text-gray-800/20 group-hover:text-[#F7931A]/10 transition duration-500"></i>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="stepForward()" id="btn-add" class="col-span-2 btn-action py-3 rounded text-sm shadow-lg flex items-center justify-center gap-2 relative overflow-hidden">
                            <span class="z-10"><i class="fa-solid fa-plus-circle"></i> เพิ่ม Key (+1)</span>
                            <div class="absolute inset-0 bg-white/20 translate-y-full hover:translate-y-0 transition duration-300"></div>
                        </button>
                        <button onclick="autoPlay()" id="btn-auto" class="col-span-1 btn-secondary py-2 rounded text-xs uppercase font-bold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-play"></i> Auto
                        </button>
                        <button onclick="resetSim()" class="col-span-1 btn-reset py-2 rounded text-xs uppercase font-bold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                        
                        <button onclick="showOverlay('real-g-overlay'); showRealG();" class="col-span-1 btn-real-g py-2 rounded text-[10px] uppercase font-bold flex flex-col items-center justify-center leading-tight">
                            <span><i class="fa-solid fa-fingerprint"></i> ดูค่า G จริง</span>
                        </button>
                        <button onclick="showOverlay('algo-overlay'); runAlgo();" class="col-span-1 btn-algo py-2 rounded text-[10px] uppercase font-bold flex flex-col items-center justify-center leading-tight">
                            <span><i class="fa-solid fa-bolt"></i> Lab คำนวณไว</span>
                        </button>
                    </div>
                </div>

                <div class="neon-box p-4 rounded-xl flex flex-col flex-shrink-0">
                    <h3 class="text-xs font-bold text-gray-400 mb-2 border-b border-gray-800 pb-2 flex justify-between brand-font">
                        <span><i class="fa-solid fa-code"></i> CALCULATION LOG</span>
                        <span class="text-[10px] text-gray-600 mono-font">y² = x³ + 7</span>
                    </h3>
                    <div class="space-y-3 mono-font text-xs">
                        <div>
                            <div class="text-[10px] text-[#F7931A] mb-1 font-sans">จุดเริ่มต้น (Generator Point G)</div>
                            <div class="key-display flex justify-between"><span>x: 1.0000</span><span>y: 2.8284</span></div>
                        </div>
                        <div>
                            <div class="text-[10px] text-[#00ff41] mb-1 flex justify-between font-sans">
                                <span>ผลลัพธ์ (Public Key = d × G)</span>
                                <span id="coord-status" class="text-gray-500 animate-pulse"></span>
                            </div>
                            <div class="bg-black/40 border border-gray-700 p-2 rounded">
                                <div class="flex justify-between mb-1"><span class="text-gray-500">X:</span><span id="pub-x" class="text-white font-bold">...</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Y:</span><span id="pub-y" class="text-white font-bold">...</span></div>
                            </div>
                        </div>
                        <div id="explanation-box" class="bg-gray-900/80 p-3 rounded border-l-2 border-[#00ff41] text-gray-300 leading-relaxed math-step font-sans">
                            <p class="mb-1 text-[#00ff41] font-bold">สถานะ: เริ่มต้น (Initial)</p>เริ่มต้นที่ <b>d=1</b> ดังนั้น Public Key คือจุดเดียวกับ <b>G</b>
                        </div>
                    </div>
                </div>

                <div class="neon-box p-5 rounded-xl bg-black/80 border-l-4 border-[#F7931A] relative overflow-hidden flex-shrink-0">
                    <h3 class="text-sm font-bold text-white mb-3 brand-font flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-[#F7931A]"></i> สรุป: Public Key คืออะไร?
                    </h3>
                    <div class="text-xs text-gray-400 space-y-3 font-light leading-relaxed">
                        <p>ในทางคณิตศาสตร์ <b>Public Key</b> ไม่ใช่แค่รหัสสุ่ม แต่คือ <b>"พิกัดทางเรขาคณิต"</b> (x, y) ที่แน่นอนบนเส้นโค้งที่เกิดจากสมการ:</p>
                        <div class="bg-gray-900 p-2 rounded border border-gray-800 font-mono text-[10px] md:text-xs text-center my-2 shadow-inner">
                            <span class="text-[#00ff41]">Public Key (P)</span> = <span class="text-white">Private Key (d)</span> × <span class="text-[#00f3ff]">G</span>
                        </div>
                        <ul class="list-disc list-inside text-[10px] space-y-1 ml-1 bg-gray-900/50 p-2 rounded">
                            <li><b>ค่า X:</b> คือแกนหลักที่ใช้ระบุตัวตน (Compressed Key)</li>
                            <li><b>ค่า Y:</b> ใช้ระบุทิศทางบน/ล่าง (ใช้ระบุว่าเป็นเลขคู่หรือคี่)</li>
                        </ul>
                    </div>
                    <i class="fa-solid fa-diagram-project absolute -right-2 top-4 text-6xl text-gray-800/30 -rotate-12"></i>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col h-96 lg:h-full overflow-hidden relative group">
            <div class="neon-box p-1 rounded-xl h-full relative bg-black flex flex-col">
                <div class="absolute top-4 left-4 z-10 pointer-events-none flex flex-col gap-1">
                    <span class="bg-black/60 backdrop-blur text-[#F7931A] px-3 py-1 rounded text-xs font-bold border border-[#F7931A]/30 brand-font shadow-lg uppercase">Secp256k1 (แบบจำลองเลขจำนวนจริง)</span>
                    <div id="camera-info" class="text-[10px] text-gray-500 font-mono pl-1">Scale: 1.0x | Offset: 0,0</div>
                </div>
                <div class="absolute bottom-4 right-4 z-10 flex gap-2">
                    <button onclick="manualZoom(-5)" class="bg-gray-800/80 text-white w-8 h-8 rounded-full hover:bg-gray-700 flex items-center justify-center shadow-lg transition transform active:scale-90"><i class="fa-solid fa-minus"></i></button>
                    <button onclick="manualZoom(5)" class="bg-gray-800/80 text-white w-8 h-8 rounded-full hover:bg-gray-700 flex items-center justify-center shadow-lg transition transform active:scale-90"><i class="fa-solid fa-plus"></i></button>
                    <button onclick="autoFit(true)" class="bg-[#F7931A]/80 text-black w-8 h-8 rounded-full hover:bg-[#ffae42] flex items-center justify-center shadow-lg transition transform active:scale-90" title="Auto Fit"><i class="fa-solid fa-compress"></i></button>
                </div>
                <div id="canvas-container" class="flex-grow">
                    <canvas id="curveCanvas"></canvas>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full text-center p-4 text-xs text-gray-600 border-t border-gray-900 mt-auto bg-[#050505] z-10 relative">
        © 2026 Chollatis Bitcoiner | Don't Trust, Verify.
    </footer>

    <script>
        const canvas = document.getElementById('curveCanvas');
        const ctx = canvas.getContext('2d');
        const container = document.getElementById('canvas-container');
        const A = 0; const B = 7; const G = { x: 1, y: Math.sqrt(8) }; 
        const REAL_G_X_DEC = "55,066,263,022,277,343,669,578,718,895,168,534,326,250,603,453,777,594,175,500,187,360,389,116,729,240";
        const REAL_G_Y_DEC = "32,670,510,020,758,816,978,083,085,130,507,043,184,471,273,380,659,243,275,938,904,335,757,337,482,424";
        
        let privateKey = 1;
        let currentP = { ...G };
        let view = { scale: 60, offsetX: 0, offsetY: 0, targetScale: 60, targetOffX: 0, targetOffY: 0 };
        let anim = { active: false, progress: 0, p1: null, p2: null, intersect: null, final: null, speed: 0.015 };
        let autoPlayTimer = null; let typeWriterTimer = null;

        function resize() {
            canvas.width = container.clientWidth;
            canvas.height = container.clientHeight;
            view.targetOffX = canvas.width / 2; view.targetOffY = canvas.height / 2;
            if(privateKey === 1) { view.offsetX = view.targetOffX; view.offsetY = view.targetOffY; }
        }
        window.addEventListener('resize', () => { resize(); autoFit(true); });
        document.addEventListener('DOMContentLoaded', () => { resize(); updateUI(); requestAnimationFrame(renderLoop); });

        function showOverlay(id) { document.getElementById(id).classList.remove('hidden'); }
        function hideOverlay(id) { 
            document.getElementById(id).classList.add('hidden'); 
            if(id === 'real-g-overlay' && typeWriterTimer) clearTimeout(typeWriterTimer);
        }

        function showRealG() {
            if(autoPlayTimer) autoPlay(); 
            typeWriterEffect('type-x', REAL_G_X_DEC);
            setTimeout(() => { typeWriterEffect('type-y', REAL_G_Y_DEC); }, 1500); 
        }
        function typeWriterEffect(elementId, text) {
            const el = document.getElementById(elementId); el.innerText = "";
            let i = 0; const speed = 5; 
            function type() { if (i < text.length) { el.innerText += text.charAt(i); i++; typeWriterTimer = setTimeout(type, speed); } }
            type();
        }

        function runAlgo() {
            const input = parseInt(document.getElementById('algo-input').value);
            if(isNaN(input) || input < 1) return;

            const binary = input.toString(2);
            document.getElementById('algo-binary').innerText = binary;
            
            const naiveSteps = input; 
            const algoSteps = binary.length + binary.split('1').length - 2; 
            
            document.getElementById('stat-naive').innerHTML = `${naiveSteps.toLocaleString()} <span class="text-[10px] text-gray-500">ops</span>`;
            document.getElementById('stat-algo').innerHTML = `${algoSteps} <span class="text-[10px] text-gray-500">ops</span>`;

            const tbody = document.getElementById('algo-steps');
            tbody.innerHTML = "";

            let currentVal = 0;
            addAlgoRow(tbody, 0, "-", "Start (MSB=1)", "G");
            currentVal = 1;
            
            for (let i = 1; i < binary.length; i++) {
                const bit = binary[i];
                const stepNum = i;
                currentVal *= 2;
                addAlgoRow(tbody, stepNum, bit, `<span class="text-blue-400">Double</span> (2 × ${currentVal/2}G)`, `${currentVal}G`);
                if (bit === '1') {
                    currentVal += 1;
                    addAlgoRow(tbody, "", "", `<span class="text-green-400">Add G</span> (${currentVal-1}G + G)`, `<b class="text-[#00ff41]">${currentVal}G</b>`);
                }
            }
        }

        function addAlgoRow(tbody, step, bit, action, result) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-gray-500 p-2">${step}</td>
                <td class="text-[#F7931A] font-bold p-2">${bit}</td>
                <td class="whitespace-nowrap p-2">${action}</td>
                <td class="text-white whitespace-nowrap p-2">${result}</td>
            `;
            tbody.appendChild(row);
        }

        function getCurveY(x) { let val = x*x*x + A*x + B; return val >= 0 ? Math.sqrt(val) : NaN; }
        function addPoints(p1, p2) {
            let lambda;
            const epsilon = 0.00001;
            if (Math.abs(p1.x - p2.x) < epsilon && Math.abs(p1.y - p2.y) < epsilon) {
                lambda = (3 * p1.x * p1.x + A) / (2 * p1.y);
            } else {
                lambda = (p2.y - p1.y) / (p2.x - p1.x);
            }
            let x3 = lambda * lambda - p1.x - p2.x;
            let y3 = lambda * (p1.x - x3) - p1.y;
            return { x: x3, y: y3 };
        }

        function autoFit(instant = false) {
            let points = [{x: -2, y: 0}, {x: 2, y: 0}, G, currentP];
            if (anim.active && anim.intersect) { points.push(anim.intersect); points.push(anim.final); }
            
            let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
            points.forEach(p => { 
                if(p.x < minX) minX = p.x; if(p.x > maxX) maxX = p.x; 
                if(p.y < minY) minY = p.y; if(p.y > maxY) maxY = p.y; 
            });

            // FIXED: Force symmetry around Y=0 to keep graph looking correct on mobile
            let maxAbsY = Math.max(Math.abs(minY), Math.abs(maxY));
            // Add minimum headroom
            maxAbsY = Math.max(maxAbsY, 3);
            
            minY = -maxAbsY;
            maxY = maxAbsY;

            let w = maxX - minX, h = maxY - minY;
            // Pad
            let padX = Math.max(w * 0.5, 5);
            let padY = Math.max(h * 0.2, 2);
            
            minX -= padX; maxX += padX; 
            minY -= padY; maxY += padY;

            let scaleX = canvas.width / (maxX - minX);
            let scaleY = canvas.height / (maxY - minY);
            let newScale = Math.min(scaleX, scaleY);
            
            if(newScale > 60) newScale = 60; 
            if(newScale < 0.1) newScale = 0.1;

            let logicCenterX = (minX + maxX) / 2;
            let logicCenterY = (minY + maxY) / 2; // Should be 0 now due to symmetry force
            
            view.targetScale = newScale;
            view.targetOffX = (canvas.width / 2) - (logicCenterX * newScale);
            view.targetOffY = (canvas.height / 2) + (logicCenterY * newScale); 
            
            if(instant) { view.scale = view.targetScale; view.offsetX = view.targetOffX; view.offsetY = view.targetOffY; }
        }

        function manualZoom(delta) { view.targetScale += delta; if(view.targetScale < 1) view.targetScale = 1; }
        function updateCameraPhysics() {
            const ease = 0.08;
            view.scale += (view.targetScale - view.scale) * ease;
            view.offsetX += (view.targetOffX - view.offsetX) * ease;
            view.offsetY += (view.targetOffY - view.offsetY) * ease;
            document.getElementById('camera-info').innerText = `Scale: ${view.scale.toFixed(1)}x | X: ${(view.offsetX).toFixed(0)}`;
        }
        function toScreen(p) { return { x: view.offsetX + p.x * view.scale, y: view.offsetY - p.y * view.scale }; }

        function drawCurve() {
            ctx.beginPath(); ctx.strokeStyle = '#F7931A'; ctx.lineWidth = 2;
            let startLogicX = (-view.offsetX) / view.scale, endLogicX = (canvas.width - view.offsetX) / view.scale;
            let curveRoot = -Math.pow(B, 1/3); if (startLogicX < curveRoot) startLogicX = curveRoot;
            let step = 8 / view.scale; if (step < 0.005) step = 0.005; if (step > 0.5) step = 0.5;
            let isFirst = true;
            for (let x = startLogicX; x < endLogicX; x += step) {
                let y = getCurveY(x);
                if (!isNaN(y)) { let s = toScreen({x, y}); if (isFirst) { ctx.moveTo(s.x, s.y); isFirst = false; } else ctx.lineTo(s.x, s.y); }
            }
            ctx.stroke();
            ctx.beginPath(); isFirst = true;
            for (let x = startLogicX; x < endLogicX; x += step) {
                let y = getCurveY(x);
                if (!isNaN(y)) { let s = toScreen({x, y: -y}); if (isFirst) { ctx.moveTo(s.x, s.y); isFirst = false; } else ctx.lineTo(s.x, s.y); }
            }
            ctx.stroke();
        }
        function drawGrid() {
            ctx.strokeStyle = '#222'; ctx.lineWidth = 1;
            let origin = toScreen({x:0, y:0});
            ctx.beginPath(); ctx.moveTo(origin.x, 0); ctx.lineTo(origin.x, canvas.height); ctx.moveTo(0, origin.y); ctx.lineTo(canvas.width, origin.y); ctx.stroke();
        }
        function drawPoint(p, color, label, size = 6) {
            let s = toScreen(p);
            if(s.x < -50 || s.x > canvas.width+50 || s.y < -50 || s.y > canvas.height+50) return;
            ctx.shadowBlur = 10; ctx.shadowColor = color; ctx.fillStyle = color;
            ctx.beginPath(); ctx.arc(s.x, s.y, size, 0, Math.PI * 2); ctx.fill();
            ctx.shadowBlur = 0; ctx.fillStyle = 'white'; ctx.font = '12px Share Tech Mono'; ctx.fillText(label, s.x + 10, s.y - 10);
        }
        function renderLoop() {
            updateCameraPhysics();
            ctx.fillStyle = '#000'; ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#080808'; ctx.fillRect(0,0, canvas.width, canvas.height);
            drawGrid(); drawCurve(); drawPoint(G, '#00f3ff', 'G');
            if (anim.active) renderAnimation(); else drawPoint(currentP, '#00ff41', `P(${privateKey}G)`);
            requestAnimationFrame(renderLoop);
        }
        function renderAnimation() {
            drawPoint(anim.p1, '#666', '');
            let s1 = toScreen(anim.p1), sInt = toScreen(anim.intersect);
            ctx.strokeStyle = '#ff0055'; ctx.lineWidth = 2; ctx.setLineDash([5, 5]);
            ctx.beginPath(); ctx.moveTo(s1.x, s1.y);
            let phase1 = Math.min(anim.progress * 1.5, 1);
            ctx.lineTo(s1.x + (sInt.x - s1.x) * phase1, s1.y + (sInt.y - s1.y) * phase1);
            ctx.stroke(); ctx.setLineDash([]);
            if (anim.progress > 0.6) {
                let sFinal = toScreen(anim.final);
                let phase2 = Math.min((anim.progress - 0.6) * 2.5, 1);
                ctx.strokeStyle = '#00ff41'; ctx.lineWidth = 2;
                ctx.beginPath(); ctx.moveTo(sInt.x, sInt.y);
                ctx.lineTo(sInt.x + (sFinal.x - sInt.x) * phase2, sInt.y + (sFinal.y - sInt.y) * phase2);
                ctx.stroke();
                ctx.fillStyle = 'rgba(255, 0, 85, 0.5)'; ctx.beginPath(); ctx.arc(sInt.x, sInt.y, 4, 0, Math.PI*2); ctx.fill();
            }
            if (anim.progress > 0.9) drawPoint(anim.final, '#00ff41', `P(${privateKey}G)`);
            anim.progress += anim.speed;
            if (anim.progress > 1.3) { anim.active = false; anim.progress = 0; }
        }
        function stepForward() {
            if (anim.active) return;
            const prevP = { ...currentP };
            const result = addPoints(currentP, G);
            anim.p1 = prevP; anim.p2 = G;
            anim.intersect = { x: result.x, y: -result.y }; anim.final = result;
            anim.progress = 0; anim.active = true;
            currentP = result; privateKey++;
            autoFit(); updateUI();
        }
        function updateUI() {
            document.getElementById('private-key-display').innerText = privateKey;
            document.getElementById('pub-x').innerText = currentP.x.toFixed(4);
            document.getElementById('pub-y').innerText = currentP.y.toFixed(4);
            const status = document.getElementById('coord-status');
            status.innerText = "UPDATED"; setTimeout(() => status.innerText = "", 1000);
            const box = document.getElementById('explanation-box');
            let html = "";
            if (privateKey === 1) html = `<p class="mb-1 text-[#00ff41] font-bold">สถานะ: เริ่มต้น (Initial)</p>เริ่มต้นที่ <b>Private Key = 1</b> ดังนั้น Public Key คือจุดเดียวกับ <b>G</b> (Generator)`;
            else if (privateKey === 2) html = `<p class="mb-1 text-[#F7931A] font-bold">Action: Point Doubling (G+G)</p>เมื่อ <b>P=G</b> เราลากเส้นสัมผัส (Tangent) ที่จุด G ไปชนกราฟแล้วสะท้อนกลับ ได้จุด 2G`;
            else html = `<p class="mb-1 text-[#00f3ff] font-bold">Action: Point Addition (P+G)</p>ลากเส้นตรงผ่านจุด <b>${privateKey-1}G</b> และ <b>G</b> ไปชนกราฟที่จุดตัด แล้วสะท้อนกลับมาเป็น <b>${privateKey}G</b>`;
            box.innerHTML = html;
        }
        function resetSim() {
            if (autoPlayTimer) clearInterval(autoPlayTimer);
            anim.active = false; privateKey = 1; currentP = { ...G };
            autoFit(true); updateUI();
            const btn = document.getElementById('btn-auto');
            btn.innerHTML = '<i class="fa-solid fa-play"></i> Auto'; btn.classList.remove('bg-red-900', 'text-white');
        }
        function autoPlay() {
            const btn = document.getElementById('btn-auto');
            if (autoPlayTimer) { clearInterval(autoPlayTimer); autoPlayTimer = null; btn.innerHTML = '<i class="fa-solid fa-play"></i> Auto'; btn.classList.remove('bg-red-900', 'text-white'); } 
            else { btn.innerHTML = '<i class="fa-solid fa-pause"></i> Stop'; btn.classList.add('bg-red-900', 'text-white'); stepForward(); autoPlayTimer = setInterval(() => { if (!anim.active) stepForward(); }, 2000); }
        }
    </script>
</body>
</html>