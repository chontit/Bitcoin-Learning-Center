<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Bitcoin Mining Simulator</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Kanit:wght@300;400;600&display=swap');

        /* BASE LAYOUT */
        html, body {
            min-height: 100vh; width: 100%; margin: 0; padding: 0;
            font-family: 'Kanit', sans-serif;
            background-color: #0b1121;
            color: #e2e8f0;
            overflow-x: hidden;
        }

        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* ANIMATIONS */
        .block-enter { animation: slideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; opacity: 0; transform: scale(0.9) translateX(20px); }
        @keyframes slideIn { to { opacity: 1; transform: scale(1) translateX(0); } }

        /* CONNECTOR */
        .connector-wrapper { display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .connector-line { width: 40px; height: 2px; background: linear-gradient(to right, #3b82f6 0%, #10b981 100%); margin: 0 8px; position: relative; }
        .connector-line::after { content: ''; position: absolute; right: -4px; top: -4px; border-top: 5px solid transparent; border-bottom: 5px solid transparent; border-left: 6px solid #10b981; }
        @media (max-width: 1023px) {
            .connector-line { width: 2px; height: 30px; background: linear-gradient(to bottom, #3b82f6 0%, #10b981 100%); margin: 0; }
            .connector-line::after { left: -4px; bottom: -4px; top: auto; right: auto; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 6px solid #10b981; border-bottom: none; }
        }

        /* BLOCK CARD */
        .block-card { background: rgba(30, 41, 59, 0.85); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); transition: all 0.2s; position: relative; }
        .block-card:hover { border-color: rgba(74, 222, 128, 0.6); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.6); transform: translateY(-3px); z-index: 20; }
        .block-card.genesis { border-color: rgba(234, 179, 8, 0.4); border-left-color: #eab308; background: rgba(40, 30, 10, 0.85); }

        /* TOOLTIP */
        #global-tooltip { 
            position: fixed; z-index: 9999; visibility: hidden; opacity: 0; 
            background-color: rgba(15, 23, 42, 0.98); border: 1px solid #475569; 
            border-radius: 8px; padding: 12px; 
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.9); 
            pointer-events: none; 
            width: 340px; transform: translate(-50%, -100%); margin-top: -15px;
        }
        #global-tooltip.active { visibility: visible; opacity: 1; }

        /* INFO BALLOON */
        #info-balloon {
            position: fixed;
            z-index: 10000;
            visibility: hidden;
            opacity: 0;
            background-color: rgba(30, 58, 138, 0.95);
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 10px 14px;
            color: #e2e8f0;
            font-size: 12px;
            line-height: 1.5;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            pointer-events: none;
            width: 280px;
            transform: translate(-50%, -10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #info-balloon.show {
            visibility: visible;
            opacity: 1;
            transform: translate(-50%, -20px);
        }

        header { padding-top: max(8px, env(safe-area-inset-top)); }
    </style>
</head>
<body class="bg-[#0b1121]">

    <div id="global-tooltip"></div>

    <!-- WRAPPER FOR SIMULATOR DASHBOARD (1 SCREEN HEIGHT) -->
    <div class="h-screen flex flex-col w-full relative">
        <header class="bg-slate-900/95 flex-none z-50 px-4 py-3 border-b border-slate-800/50 shadow-lg sticky top-0 lg:relative">
            <div class="max-w-[1920px] mx-auto w-full flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0">
                <div class="flex items-center gap-3 w-full sm:w-auto justify-start">
                    <a href="/" onclick="window.location.reload()" class="text-slate-400 hover:text-white transition p-2 rounded-full hover:bg-slate-800"><i class="fas fa-home text-lg"></i></a>
                    <div class="flex items-center gap-2">
                        <div class="bg-yellow-500/10 p-1.5 rounded-full"><i class="fa-brands fa-bitcoin text-yellow-500 text-xl animate-pulse"></i></div>
                        <div>
                            <h1 class="text-base font-bold text-white tracking-wide leading-tight">Bitcoin Mining Simulator</h1>
                            <div class="text-[10px] text-slate-400 flex gap-3 font-mono mt-0.5">
                                <span id="device-cap-display"><i class="fas fa-microchip text-blue-400"></i> Benchmarking...</span>
                                <span class="hidden sm:inline"><i class="fas fa-coins text-yellow-400"></i> 1 BTC = $10</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
                    <button id="btnMute" onclick="sim.toggleSound()" class="w-8 h-8 rounded bg-slate-800 border border-slate-700 hover:bg-slate-700 hover:border-slate-600 text-slate-300 transition flex items-center justify-center">
                        <i class="fas fa-volume-up" id="iconMute"></i>
                    </button>
                    <div class="flex gap-2">
                        <button onclick="sim.start()" id="btnStart" class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded shadow transition flex items-center gap-2"><i class="fas fa-play"></i> <span class="hidden sm:inline">Start</span></button>
                        <button onclick="sim.stop()" id="btnStop" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded shadow transition hidden flex items-center gap-2"><i class="fas fa-pause"></i> <span class="hidden sm:inline">Pause</span></button>
                        <button onclick="window.location.reload()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold rounded transition"><i class="fas fa-rotate-right"></i></button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow flex flex-col w-full h-full relative lg:overflow-hidden overflow-y-auto">
            
            <section class="w-full bg-[#0f172a] border-b border-slate-800 z-40 shadow-xl h-auto lg:h-[60%] lg:overflow-hidden shrink-0">
                
                <div class="max-w-[1920px] mx-auto p-4 flex flex-col lg:flex-row gap-4 h-full">
                    
                    <div class="w-full lg:flex-[25] bg-slate-800/40 rounded-xl p-4 border border-slate-700/50 flex flex-col h-auto lg:h-full lg:min-h-[200px]">
                        <h2 class="text-xs font-bold mb-3 text-blue-400 uppercase tracking-widest shrink-0 flex items-center gap-2">
                            <span><i class="fas fa-globe"></i> Network Status</span>
                            <i class="fas fa-info-circle text-slate-500 cursor-pointer hover:text-blue-400 transition-colors info-trigger" 
                               data-info="ส่วนนี้แสดงสถานะภาพรวมของเครือข่าย เช่น ความสูงของบล็อกปัจจุบัน เวลาเฉลี่ยที่ใช้ต่อบล็อก และความยาก (Target) ในการสุ่มหา Hash"></i>
                        </h2>
                        <div class="grid grid-cols-2 gap-3 flex-grow content-start">
                            <div class="bg-slate-900/80 p-3 rounded-lg border border-slate-700">
                                <div class="text-[10px] text-slate-500 uppercase font-semibold">Height</div>
                                <div class="text-2xl font-mono text-white mt-1" id="stat-blocks">0</div>
                            </div>
                            <div class="bg-slate-900/80 p-3 rounded-lg border border-slate-700">
                                <div class="text-[10px] text-slate-500 uppercase font-semibold">Avg Time (Target 10 sec/blk)</div>
                                <div class="text-2xl font-mono text-green-400 mt-1" id="stat-avg-time">--</div>
                            </div>
                            <div class="bg-slate-900/80 p-3 rounded-lg border border-slate-700 col-span-2">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <div class="text-[10px] text-slate-500 uppercase font-semibold flex items-center gap-1">
                                            Hex Target (Difficulty)
                                            <i class="fas fa-question-circle cursor-pointer hover:text-white info-trigger" 
                                               data-info="เป้าหมายของค่า Hash ที่เหมืองต้องสุ่มให้ได้ค่าน้อยกว่า (ยิ่งมี 0 นำหน้าเยอะ ยิ่งสุ่มเจอยาก)"></i>
                                        </div>
                                        <div class="text-white font-mono text-xs mt-1 break-all" id="stat-target-preview">Loading...</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-slate-500">Reward</div>
                                        <div class="text-yellow-400 font-bold text-sm mt-1">50 BTC/Block</div>
                                    </div>
                                </div>
                                <div class="text-[10px] text-yellow-500 mt-1"><span id="stat-zeros-count">0</span> leading zeros req.</div>
                            </div>
                            
                            <div class="col-span-2 bg-blue-900/20 border border-blue-500/30 p-2 rounded text-[10px] text-blue-300 mt-auto">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold"><i class="fas fa-tachometer-alt"></i> Device Utilization:</span>
                                    <span id="utilization-text" class="font-mono font-bold text-blue-200">0%</span>
                                </div>
                                <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden mb-1">
                                    <div id="utilization-bar" class="bg-blue-500 h-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div class="text-slate-400 opacity-80">Cost: $1.00 per 10,000 Hashes</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:flex-[35] bg-slate-800/40 rounded-xl p-4 border border-slate-700/50 flex flex-col h-[350px] lg:h-full lg:min-h-[300px]">
                        <div class="flex justify-between items-center mb-3 shrink-0">
                            <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                <span><i class="fas fa-microchip"></i> Active Miners</span>
                                <i class="fas fa-info-circle text-slate-500 cursor-pointer hover:text-blue-400 transition-colors info-trigger" 
                                   data-info="จำลองคนงานเหมืองที่แข่งกันสุ่มตัวเลข (Nonce) เครื่องที่มี Hashrate สูงกว่าจะมีโอกาสชนะ (Chance) สูงกว่า แต่ก็ต้องจ่ายค่าไฟที่แพงขึ้นตามไปด้วย"></i>
                            </h2>
                            <div class="flex gap-1">
                                <button onclick="sim.addMiner()" class="w-6 h-6 flex items-center justify-center text-[10px] bg-blue-600 hover:bg-blue-500 text-white rounded shadow cursor-pointer relative z-20"><i class="fas fa-plus"></i></button>
                                <button onclick="sim.removeMiner()" class="w-6 h-6 flex items-center justify-center text-[10px] bg-red-600 hover:bg-red-500 text-white rounded shadow cursor-pointer relative z-20"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-[85px_40px_1fr_1fr_1fr] lg:grid-cols-[100px_50px_1fr_1fr_1fr] gap-1 px-2 border-b border-slate-700 mb-0 shrink-0 sticky top-0 bg-slate-800/95 backdrop-blur z-10 rounded-t h-10">
                            <div class="flex items-center text-[9px] text-slate-500 uppercase font-bold h-full">Miner</div>
                            <div class="flex items-center justify-center text-[9px] text-slate-500 uppercase font-bold h-full">Mined Blks</div>
                            <div class="flex items-center justify-center text-[9px] text-slate-500 uppercase font-bold h-full">Chance</div>
                            <div class="flex items-center justify-center text-[9px] text-slate-500 uppercase font-bold h-full">Electric Cost</div>
                            <div class="flex items-center justify-end text-[9px] text-slate-500 uppercase font-bold h-full">Net Profit</div>
                        </div>
                        
                        <div id="miners-container" class="space-y-1 overflow-y-auto custom-scrollbar flex-grow pr-1 min-h-0 pt-2 pb-2"></div>
                    </div>

                    <div class="w-full lg:flex-[40] bg-slate-800/40 rounded-xl p-4 border border-slate-700/50 flex flex-col h-[250px] lg:h-full lg:min-h-[200px]">
                        <h2 class="text-xs font-bold mb-2 text-slate-500 uppercase shrink-0 flex items-center gap-2">
                            <span>Difficulty Trend</span>
                            <i class="fas fa-info-circle text-slate-500 cursor-pointer hover:text-slate-300 transition-colors info-trigger" 
                               data-info="กราฟแสดงการปรับความยากอัตโนมัติ (Difficulty Adjustment) หากค่าเฉลี่ยขุดบล็อกได้เร็วกว่า 10 วินาที ระบบจะปรับให้การขุดบล็อกต่อไปยากขึ้น (ต้องการเลข 0 ด้านหน้าของ Hash มากขึ้น)"></i>
                        </h2>
                        <div class="w-full flex-grow relative">
                            <canvas id="diffChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <section class="h-[350px] lg:h-[40%] shrink-0 relative bg-[#0b1121] border-t border-slate-800 flex flex-col">
                <div class="bg-slate-900/50 backdrop-blur-sm p-2 border-b border-slate-800 flex justify-between items-center px-5 absolute top-0 left-0 right-0 z-10">
                    <div class="flex items-center gap-2">
                        <div id="status-dot" class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></div>
                        <span id="status-text" class="font-mono text-[10px] text-slate-400 uppercase tracking-widest">READY</span>
                    </div>
                    <div class="text-[10px] text-slate-600 font-mono hidden sm:block">SHA-256 BLOCKCHAIN (VERIFIABLE)</div>
                </div>

                <div id="blockchain-wrapper" class="absolute inset-0 top-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] overflow-y-auto lg:overflow-x-auto lg:overflow-y-hidden custom-scrollbar flex">
                    <div id="blockchain-container" class="p-6 lg:p-0 flex flex-col lg:flex-row items-center lg:items-center min-w-full lg:min-w-min min-h-full h-full lg:pl-10 lg:pr-10 justify-start lg:justify-start">
                        </div>
                </div>
            </section>
        </main>
    </div>

    <!-- EXPLANATION ARTICLE SECTION (SCROLL DOWN TO SEE) -->
    <section class="w-full bg-[#080d1a] border-t-4 border-blue-500/20 py-16 px-4 md:px-8 shadow-[inset_0_20px_30px_-15px_rgba(0,0,0,0.8)] relative z-10">
        <div class="max-w-[1200px] mx-auto text-slate-300">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-slate-800 pb-4">
                <i class="fas fa-graduation-cap text-blue-400"></i> 
                ทำความเข้าใจระบบและกลไกการขุด (How it Works)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <!-- 1. Proof of Work -->
                <div class="bg-slate-800/40 p-6 lg:p-8 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition-colors">
                    <h3 class="text-lg font-bold text-yellow-400 mb-4 flex items-center gap-3"><div class="bg-yellow-500/10 p-2 rounded-lg"><i class="fas fa-hammer"></i></div> 1. การขุด (Mining) และ Proof of Work</h3>
                    <p class="text-sm leading-relaxed text-slate-300 mb-3">
                        ในความจริงแล้ว <strong>"การขุด"</strong> ไม่ใช่การให้คอมพิวเตอร์มาแก้โจทย์คณิตศาสตร์ที่ซับซ้อน แต่คือ <strong>"การสุ่มตัวเลข" (Guessing)</strong> อย่างบ้าคลั่ง 
                    </p>
                    <p class="text-sm leading-relaxed text-slate-300">
                        ระบบจะนำข้อมูลของบล็อกปัจจุบัน มารวมกับตัวเลขสุ่มที่เรียกว่า <strong>Nonce</strong> แล้วนำไปเข้าสมการเข้ารหัส (SHA-256) เป้าหมายคือต้องสุ่มจนกว่าจะได้ผลลัพธ์ (Hash) ที่มีค่าน้อยกว่าเป้าหมาย (Target) ยิ่งเป้าหมายน้อยลง (มีเลข 0 นำหน้าเยอะ) ก็ยิ่งสุ่มเจอได้ยากขึ้น
                    </p>
                </div>

                <!-- 2. Difficulty Adjustment -->
                <div class="bg-slate-800/40 p-6 lg:p-8 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition-colors">
                    <h3 class="text-lg font-bold text-blue-400 mb-4 flex items-center gap-3"><div class="bg-blue-500/10 p-2 rounded-lg"><i class="fas fa-balance-scale"></i></div> 2. การปรับความยาก (Difficulty Adjustment)</h3>
                    <p class="text-sm leading-relaxed text-slate-300 mb-3">
                        ระบบนี้ตั้งเป้าหมายไว้ว่าต้องมีบล็อกใหม่เกิดขึ้นทุกๆ <strong>10 วินาที</strong> (เครือข่าย Bitcoin ของจริงกำหนดไว้ที่ 10 นาที) 
                    </p>
                    <p class="text-sm leading-relaxed text-slate-300">
                        หากมีเครื่องขุดเข้ามาในระบบจำนวนมาก และช่วยกันสุ่มจนหาบล็อกเจอเร็วกว่า 10 วินาที ระบบจะทำการ <strong>"เพิ่มความยาก" (ลดค่า Target ลง)</strong> อัตโนมัติ (สังเกตได้จากกราฟ Difficulty Trend) เพื่อดึงเวลาเฉลี่ยให้กลับมาที่ 10 วินาทีตามเดิม กลไกนี้ทำให้เหรียญถูกผลิตออกมาอย่างคงที่ ไม่ล้นตลาด
                    </p>
                </div>

                <!-- 3. Economics -->
                <div class="bg-slate-800/40 p-6 lg:p-8 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition-colors">
                    <h3 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-3"><div class="bg-green-500/10 p-2 rounded-lg"><i class="fas fa-chart-pie"></i></div> 3. การคำนวณกำไร-ขาดทุน (Economics)</h3>
                    <p class="text-sm leading-relaxed text-slate-300 mb-3">
                        การสุ่มตัวเลขจำนวนมหาศาลต้องใช้พลังงานไฟฟ้า ในระบบจำลองนี้ เครื่องที่มีกำลังขุด (Hashrate) สูง จะมีโอกาสสุ่มเจอ (Chance) มากกว่าเพื่อน แต่ก็ต้องจ่าย <strong>ค่าไฟ (Electric Cost)</strong> ที่แพงขึ้นตามสัดส่วนเช่นกัน
                    </p>
                    <ul class="text-sm leading-relaxed text-slate-300 list-disc list-inside space-y-2 bg-slate-900/50 p-4 rounded-lg">
                        <li><strong>รายรับ:</strong> จะได้ 50 BTC ต่อบล็อก (คูณด้วยราคาเหรียญจำลอง $10 = $500)</li>
                        <li><strong>รายจ่าย:</strong> จะเสีย $1.00 ต่อทุกๆ 10,000 Hashes ที่เครื่องใช้ไป</li>
                        <li><strong>กำไรสุทธิ (Net Profit):</strong> ถ้าระบบมีความยากสูงมาก จนต้องใช้เวลานานในการสุ่ม และเปลืองค่าไฟจนเกินรายรับ ก็จะเกิดการขาดทุนได้ (ตัวเลขรายได้จะกลายเป็นสีแดง)</li>
                    </ul>
                </div>

                <!-- 4. Blockchain structure -->
                <div class="bg-slate-800/40 p-6 lg:p-8 rounded-2xl border border-slate-700/50 hover:border-slate-500 transition-colors">
                    <h3 class="text-lg font-bold text-purple-400 mb-4 flex items-center gap-3"><div class="bg-purple-500/10 p-2 rounded-lg"><i class="fas fa-link"></i></div> 4. สายโซ่แห่งบล็อกเชน (Blockchain)</h3>
                    <p class="text-sm leading-relaxed text-slate-300 mb-3">
                        สังเกตที่หน้าต่างบล็อกด้านล่าง ทุกๆ บล็อกที่ถูกขุดสำเร็จ จะต้องบันทึกค่า <strong>Prev Hash</strong> ซึ่งเป็นรหัส Hash ของบล็อกก่อนหน้าเอาไว้เสมอ 
                    </p>
                    <p class="text-sm leading-relaxed text-slate-300">
                        สิ่งนี้เปรียบเสมือนโซ่ที่นำมาคล้องข้อมูลเข้าด้วยกัน หากมีแฮกเกอร์พยายามแอบเปลี่ยนข้อมูลในบล็อกที่ 1 รหัส Hash ของบล็อกที่ 1 จะเปลี่ยนไปทันที ส่งผลให้บล็อกที่ 2, 3, 4 ที่เชื่อมต่อและอ้างอิงรหัสเดิมอยู่พังทลายลงทั้งหมด นี่คือกลไกอัจฉริยะที่ทำให้ข้อมูลบล็อกเชนมีความน่าเชื่อถือและปลอมแปลงไม่ได้
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="w-full bg-[#050810] border-t border-slate-800 py-6 text-center z-10 relative">
        <div class="text-slate-500 text-xs font-mono flex flex-col md:flex-row items-center justify-center gap-2 md:gap-4">
            <span>&copy; 2026 Chollatis Bitcoiner.</span>
            <span class="hidden md:inline">&middot;</span>
            <span>Don't Trust, Verify.</span>
            <span class="hidden md:inline">&middot;</span>
            <span>Powered by Bitcoin Protocol &amp; PHP <span class="text-yellow-500 ml-1">₿</span></span>
        </div>
    </footer>

    <!-- OVERLAYS -->
    <div id="mining-overlay" class="fixed inset-0 bg-black/90 backdrop-blur-md hidden flex-col items-center justify-center z-[100]">
        <i class="fas fa-check-circle text-6xl text-green-500 mb-6 drop-shadow-[0_0_20px_rgba(34,197,94,0.6)] animate-bounce"></i>
        <div class="text-white text-4xl font-bold tracking-tight">BLOCK FOUND!</div>
        <div class="text-slate-400 text-lg font-mono mt-2 mb-8">Winner: <span id="winner-name" class="font-bold text-yellow-400">--</span></div>
        <div class="w-64 bg-slate-800 rounded-full h-1.5 overflow-hidden"><div class="h-full bg-green-500 animate-[width_1.5s_ease-in-out]"></div></div>
    </div>

    <div id="bench-overlay" class="fixed inset-0 bg-slate-900/95 z-[200] flex flex-col items-center justify-center hidden">
        <i class="fas fa-tachometer-alt text-4xl text-blue-500 mb-4 animate-pulse"></i>
        <div class="text-white font-bold text-xl">Benchmarking Device...</div>
        <div class="text-slate-400 text-sm mt-2">Calculating your maximum hash power</div>
    </div>

    <script>
        // --- CONFIGURATION ---
        const TARGET_BLOCK_TIME = 10; 
        const DIFF_ADJUST_INTERVAL = 3; 
        const MAX_POSSIBLE_VIRTUAL_HASHRATE = 1000000; 
        const PRESETS = [100, 1000, 5000, 10000, 50000, 100000]; 
        
        // ECONOMICS
        const REWARD_BTC = 50;
        const BTC_PRICE_USD = 10; 
        const COST_PER_10000H = 1;

        // SOUND (Retro Coin)
        const COIN_SOUND_URL = 'https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3';
        const SUCCESS_SOUND = new Audio(COIN_SOUND_URL);

        // GENESIS & TARGET
        const MAX_TARGET = BigInt("0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF");
        const INITIAL_DIFFICULTY = 65536; 

        // UPDATED: เปลี่ยนระบบจับเวลาเป็นแบบสะสม (currentBlockMiningTime)
        const state = {
            isRunning: false,
            blocks: [],
            miners: [],
            currentDifficulty: BigInt(INITIAL_DIFFICULTY),
            currentTarget: MAX_TARGET / BigInt(INITIAL_DIFFICULTY),
            currentBlockMiningTime: 0, // สะสมเวลาเฉพาะตอนกำลังขุด
            lastTickTime: 0, // ตัวแปรเก็บ timestamp ชั่วคราวในแต่ละรอบ
            roundStartTime: 0,
            deviceMaxHashRate: 0, 
            isBenchmarked: false,
            globalNonce: 0,
            isMuted: false
        };

        const toSuperscript = (num) => num.toString().split('').map(c => ({'-':'⁻','0':'⁰','1':'¹','2':'²','3':'³','4':'⁴','5':'⁵','6':'⁶','7':'⁷','8':'⁸','9':'⁹'})[c]||c).join('');

        const tooltip = {
            el: document.getElementById('global-tooltip'),
            show: function(el, data) {
                const rect = el.getBoundingClientRect();
                this.el.innerHTML = `
                    <div class="text-[10px] ${data.index === 0 ? 'text-yellow-400' : 'text-blue-300'} font-bold mb-2 border-b border-slate-700 pb-1">
                        ${data.index === 0 ? 'GENESIS BLOCK' : 'BLOCK #' + data.index + ' PROOF'}
                    </div>
                    <div class="grid grid-cols-[60px_1fr] gap-2 text-[10px] text-slate-400 font-mono">
                        <span class="col-span-2 text-slate-500 border-b border-slate-700 pb-1 mb-1">PREV: <span class="text-slate-300 break-all">${data.prevHash.substring(0, 32)}...</span></span>
                        <span>TIME:</span> <span class="text-white font-bold">${data.timeStr}</span>
                        <span>NONCE:</span> <span class="text-white">${data.nonce}</span>
                        <span>TARGET:</span> <span class="text-yellow-600 break-all leading-tight">${data.targetStr.substring(0,20)}...</span>
                        <span class="col-span-2 text-white mt-1 border-t border-slate-700 pt-1 font-bold">HASH RESULT:</span>
                        <div class="col-span-2 text-green-400 break-all font-mono leading-tight text-[10px]">${data.hash}</div>
                    </div>
                `;
                this.el.style.top = rect.top + 'px';
                this.el.style.left = (rect.left + rect.width / 2) + 'px';
                this.el.classList.add('active');
            },
            hide: function() { this.el.classList.remove('active'); }
        };

        class Simulator {
            constructor() {
                this.genesisHash = "000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f"; 
                this.initMiners();
                this.createGenesisBlock(); 
                this.updateGlobalStats();
                this.initChart();
            }

            initMiners() {
                state.miners = [
                    { id: 1, hashrate: 1000, blocksFound: 0, cost: 0, revenue: 0 },
                    { id: 2, hashrate: 10000, blocksFound: 0, cost: 0, revenue: 0 },
                    { id: 3, hashrate: 50000, blocksFound: 0, cost: 0, revenue: 0 }
                ];
                this.renderMiners();
            }

            createGenesisBlock() {
                const genesisBlock = {
                    index: 0,
                    timestamp: Date.now(),
                    minerId: 0,
                    nonce: 2083236893,
                    prevHash: "0000000000000000000000000000000000000000000000000000000000000000",
                    hash: this.genesisHash,
                    rawData: `0:0000000000000000000000000000000000000000000000000000000000000000:0:${Date.now()}:2083236893`,
                    targetStr: state.currentTarget.toString(16).padStart(64, '0'),
                    duration: 0
                };
                state.blocks.push(genesisBlock);
                this.addBlockToUI(genesisBlock);
            }

            async start() {
                if (state.isRunning) return;

                if (!state.isBenchmarked) {
                    await this.benchmarkDevice();
                }

                state.isRunning = true;
                
                // เริ่มจับเวลาเฉพาะตอนที่เริ่ม/กลับมาขุดต่อ
                state.lastTickTime = Date.now();
                
                if (state.roundStartTime === 0) state.roundStartTime = Date.now();
                
                document.getElementById('btnStart').classList.add('hidden');
                document.getElementById('btnStop').classList.remove('hidden');
                this.updateStatus('MINING...', 'green');
                
                this.loop();
            }

            stop() {
                state.isRunning = false;
                document.getElementById('btnStart').classList.remove('hidden');
                document.getElementById('btnStop').classList.add('hidden');
                this.updateStatus('PAUSED', 'yellow');
            }

            toggleSound() {
                state.isMuted = !state.isMuted;
                const icon = document.getElementById('iconMute');
                const btn = document.getElementById('btnMute');
                
                if (state.isMuted) {
                    icon.classList.remove('fa-volume-up');
                    icon.classList.add('fa-volume-mute');
                    btn.classList.add('text-red-400', 'border-red-400/50');
                } else {
                    icon.classList.remove('fa-volume-mute');
                    icon.classList.add('fa-volume-up');
                    btn.classList.remove('text-red-400', 'border-red-400/50');
                    SUCCESS_SOUND.currentTime = 0;
                    SUCCESS_SOUND.volume = 0.5;
                    SUCCESS_SOUND.play().catch(e=>{});
                }
            }

            async benchmarkDevice() {
                const overlay = document.getElementById('bench-overlay');
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                const start = performance.now();
                let hashes = 0;
                const duration = 1000; 
                const encoder = new TextEncoder();
                const dummy = encoder.encode("benchmark");

                while (performance.now() - start < duration) {
                    for(let i=0; i<100; i++) {
                         await crypto.subtle.digest('SHA-256', dummy);
                         hashes++;
                    }
                }

                state.deviceMaxHashRate = hashes; 
                state.isBenchmarked = true;
                
                document.getElementById('device-cap-display').innerHTML = `<i class="fas fa-tachometer-alt text-blue-400"></i> Max Device: ${(hashes/1000).toFixed(1)}k H/s`;
                
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }

            async loop() {
                if (!state.isRunning) return;

                // อัปเดตเวลาสะสม
                const now = Date.now();
                state.currentBlockMiningTime += (now - state.lastTickTime);
                state.lastTickTime = now;

                const tickTime = 100; // ms
                const totalVirtualHashrate = state.miners.reduce((sum, m) => sum + m.hashrate, 0);
                const utilizationRatio = Math.min(1, totalVirtualHashrate / MAX_POSSIBLE_VIRTUAL_HASHRATE);
                
                document.getElementById('utilization-text').innerText = (utilizationRatio * 100).toFixed(2) + '%';
                document.getElementById('utilization-bar').style.width = (utilizationRatio * 100) + '%';

                const maxHashesInTick = (state.deviceMaxHashRate * (tickTime / 1000));
                const hashesToRun = Math.max(1, Math.floor(maxHashesInTick * utilizationRatio));

                const virtualHashesInTick = totalVirtualHashrate * (tickTime / 1000);
                const tickCost = (virtualHashesInTick / 10000) * COST_PER_10000H;
                
                state.miners.forEach(miner => {
                    const minerShare = totalVirtualHashrate > 0 ? miner.hashrate / totalVirtualHashrate : 0;
                    miner.cost += tickCost * minerShare;
                });

                const prevHash = state.blocks[state.blocks.length - 1].hash;
                const targetHex = state.currentTarget.toString(16).padStart(64, '0');
                const encoder = new TextEncoder();
                
                let found = false;
                let foundNonce = 0;
                let foundHash = "";
                let foundInput = "";

                for (let i = 0; i < hashesToRun; i++) {
                    state.globalNonce++;
                    const input = `${state.blocks.length}:${prevHash}:${state.roundStartTime}:${state.globalNonce}`;
                    const msgUint8 = encoder.encode(input);
                    const hashBuffer = await crypto.subtle.digest('SHA-256', msgUint8);
                    const hashArray = Array.from(new Uint8Array(hashBuffer));
                    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');

                    if (hashHex < targetHex) {
                        found = true;
                        foundNonce = state.globalNonce;
                        foundHash = hashHex;
                        foundInput = input;
                        break;
                    }
                }

                if (found) {
                    await this.processBlockFound(foundNonce, foundHash, foundInput, totalVirtualHashrate);
                } else {
                    this.renderMiners(); 
                    setTimeout(() => this.loop(), tickTime);
                }
            }

            async processBlockFound(nonce, hash, rawInput, totalVirtualHashrate) {
                this.stop(); 
                
                if (!state.isMuted) {
                    try {
                        SUCCESS_SOUND.currentTime = 0;
                        SUCCESS_SOUND.volume = 0.5;
                        SUCCESS_SOUND.play().catch(e => console.log(e));
                    } catch(e){}
                }

                let rand = Math.random() * totalVirtualHashrate;
                let winner = state.miners[0];
                for (let m of state.miners) {
                    rand -= m.hashrate;
                    if (rand <= 0) { winner = m; break; }
                }

                const overlay = document.getElementById('mining-overlay');
                document.getElementById('winner-name').innerText = `Miner #${winner.id} (${(winner.hashrate/1000).toFixed(1)}k)`;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                setTimeout(() => {
                    winner.blocksFound++;
                    winner.revenue += (REWARD_BTC * BTC_PRICE_USD);

                    const blockData = {
                        index: state.blocks.length,
                        timestamp: Date.now(),
                        minerId: winner.id,
                        nonce: nonce,
                        prevHash: state.blocks[state.blocks.length-1].hash,
                        hash: hash,
                        roundStart: state.roundStartTime,
                        rawData: rawInput,
                        targetStr: state.currentTarget.toString(16).padStart(64, '0'),
                        // นำเวลาที่สะสมไว้ทั้งหมดมาใช้หารด้วย 1000 เป็นวินาที
                        duration: state.currentBlockMiningTime / 1000 
                    };
                    
                    state.blocks.push(blockData);
                    this.addBlockToUI(blockData);
                    this.checkDifficultyAdjustment();
                    this.updateGlobalStats();
                    
                    // เคลียร์ค่าเวลาและค่าอื่นๆ เพื่อเริ่มนับบล็อกใหม่
                    state.currentBlockMiningTime = 0;
                    state.roundStartTime = Date.now(); 
                    state.globalNonce = 0; 
                    this.renderMiners();
                    
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    this.start();
                }, 1500);
            }

            checkDifficultyAdjustment() {
                const minedBlocks = state.blocks.length - 1; 

                if (minedBlocks > 0 && minedBlocks % DIFF_ADJUST_INTERVAL === 0) {
                    const lastN = state.blocks.slice(-DIFF_ADJUST_INTERVAL);
                    const totalTime = lastN.reduce((acc, b) => acc + b.duration, 0);
                    const avgTime = totalTime / DIFF_ADJUST_INTERVAL;
                    
                    const factor = TARGET_BLOCK_TIME / avgTime;
                    const cappedFactor = Math.max(0.25, Math.min(4, factor)); 
                    
                    const newDiff = Number(state.currentDifficulty) * cappedFactor;
                    state.currentDifficulty = BigInt(Math.floor(newDiff));
                    
                    if(state.currentDifficulty > 0n) {
                        state.currentTarget = MAX_TARGET / state.currentDifficulty;
                    }

                    this.updateChart(`Adj (Blk ${minedBlocks})`, Number(state.currentDifficulty));
                }
            }
            
            updateGlobalStats() {
                document.getElementById('stat-blocks').innerText = state.blocks.length - 1;
                if (state.blocks.length > 1) {
                    const mined = state.blocks.slice(1);
                    const avg = mined.reduce((a,b) => a + b.duration, 0) / mined.length;
                    document.getElementById('stat-avg-time').innerText = avg.toFixed(1) + "s";
                }
                const targetHex = state.currentTarget.toString(16).padStart(64, '0');
                const leadingZeros = targetHex.match(/^0+/)[0].length;
                document.getElementById('stat-zeros-count').innerText = leadingZeros;
                document.getElementById('stat-target-preview').innerText = targetHex.substring(0, 8) + "...";
            }

            renderMiners() {
                const container = document.getElementById('miners-container');
                container.innerHTML = '';
                const totalHashrate = state.miners.reduce((sum, m) => sum + m.hashrate, 0);

                state.miners.forEach((miner) => {
                    const winChance = totalHashrate > 0 ? (miner.hashrate / totalHashrate) * 100 : 0;
                    const netProfit = miner.revenue - miner.cost;
                    const profitClass = netProfit >= 0 ? 'text-green-400' : 'text-red-400';

                    const div = document.createElement('div');
                    div.className = `grid grid-cols-[85px_40px_1fr_1fr_1fr] lg:grid-cols-[100px_50px_1fr_1fr_1fr] gap-1 items-center p-2 rounded bg-slate-900 border ${state.isRunning ? 'border-blue-500/10' : 'border-slate-700/50'} text-xs shrink-0`;
                    div.innerHTML = `
                        <div class="flex flex-col gap-1">
                            <span class="font-bold text-blue-400 bg-blue-500/10 px-1 py-0.5 rounded text-center w-fit">#${miner.id}</span>
                            <select onchange="sim.updateHashrate(${miner.id}, this.value)" class="bg-slate-800 text-white rounded border border-slate-700 py-0.5 px-1 w-full text-[10px] outline-none">
                                ${PRESETS.map(s => `<option value="${s}" ${miner.hashrate === s ? 'selected' : ''}>${s < 1000 ? s + ' Hash/s' : s/1000 + ' KH/s'}</option>`).join('')}
                            </select>
                        </div>
                        <div class="text-center flex flex-col justify-center h-full bg-slate-800/30 rounded border border-slate-700/50">
                            <span class="text-yellow-400 font-bold font-mono text-sm">${miner.blocksFound}</span>
                        </div>
                        <div class="text-center flex flex-col justify-center h-full bg-slate-800/50 rounded p-1">
                            <div class="text-white font-mono font-bold">${winChance.toFixed(2)}%</div>
                            <div class="w-full bg-slate-700 h-1 mt-1 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full" style="width: ${winChance}%"></div>
                            </div>
                        </div>
                        <div class="text-center font-mono text-red-400/80 whitespace-nowrap overflow-hidden text-ellipsis px-1 flex items-center justify-center h-full">
                            -$${miner.cost.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                        </div>
                        <div class="text-right font-mono font-bold ${profitClass} text-sm whitespace-nowrap overflow-hidden text-ellipsis px-1 flex items-center justify-end h-full">
                            $${netProfit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                        </div>
                    `;
                    container.appendChild(div);
                });
            }

            copyBlockData(btn, rawData) {
                navigator.clipboard.writeText(rawData).then(() => {
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check text-green-400"></i>';
                    setTimeout(() => { btn.innerHTML = originalHTML; }, 1000);
                }).catch(err => { alert('Copy failed'); });
            }

            addBlockToUI(block) {
                const container = document.getElementById('blockchain-container');
                const isGenesis = block.index === 0;

                if (!isGenesis) {
                    const connector = document.createElement('div');
                    connector.className = 'connector-wrapper';
                    connector.innerHTML = `<div class="flex flex-col lg:flex-row items-center"><div class="text-[9px] text-slate-500 mb-0.5 lg:mb-0 lg:mr-1 bg-slate-800 px-1.5 rounded border border-slate-700">${block.duration.toFixed(1)}s</div><div class="connector-line"></div></div>`;
                    container.appendChild(connector);
                }

                const div = document.createElement('div');
                div.className = `block-card rounded-xl p-4 w-[240px] shrink-0 mx-2 my-2 border-l-4 cursor-pointer block-enter group ${isGenesis ? 'genesis' : 'border-l-green-500'}`;
                
                const tooltipData = {
                    index: block.index, prevHash: block.prevHash, miner: 'Miner #' + block.minerId,
                    nonce: block.nonce, time: block.roundStart, hash: block.hash, targetStr: block.targetStr, 
                    timeStr: new Date(block.timestamp).toLocaleTimeString() 
                };
                
                div.addEventListener('mouseenter', function() { tooltip.show(this, tooltipData); });
                div.addEventListener('mouseleave', function() { tooltip.hide(); });
                div.onclick = function() { tooltip.show(this, tooltipData); };

                div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <span class="${isGenesis ? 'bg-yellow-600/20 text-yellow-400 border-yellow-500/30' : 'bg-green-600/20 text-green-400 border-green-500/30'} text-[10px] px-2 py-0.5 rounded font-bold tracking-wider border">
                            ${isGenesis ? 'GENESIS' : 'BLOCK #' + block.index}
                        </span>
                        <div class="text-right"><div class="text-[9px] text-slate-500 uppercase font-bold">NONCE</div><div class="text-sm font-mono text-yellow-400 font-bold leading-none">${block.nonce}</div></div>
                    </div>
                    <div class="mt-3 flex justify-between items-center text-[10px] text-slate-400 pb-2 border-b border-white/5 font-mono">
                        <span class="flex items-center gap-2"><i class="fas ${isGenesis ? 'fa-user-secret' : 'fa-server'}"></i> ${isGenesis ? 'Satoshi' : 'Miner #' + block.minerId}</span>
                        <span>${new Date(block.timestamp).toLocaleTimeString()}</span>
                    </div>
                    <div class="bg-black/20 p-2.5 rounded border border-white/5 mt-2 relative">
                        <div class="text-[9px] text-slate-500 uppercase font-bold">Hash</div>
                        <div class="text-[10px] font-mono ${isGenesis ? 'text-yellow-200' : 'text-green-400'} break-all leading-tight">${block.hash.substring(0, 16)}...</div>
                    </div>
                    <div class="mt-2 flex justify-between items-center opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                         <div class="text-[9px] text-slate-600">Raw Data</div>
                         <button onclick="event.stopPropagation(); sim.copyBlockData(this, '${block.rawData}')" class="bg-slate-700 hover:bg-slate-600 text-slate-300 rounded px-2 py-1 shadow border border-slate-600 transition flex items-center gap-1 text-[10px]">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                `;
                container.appendChild(div);
                
                setTimeout(() => {
                    const wrapper = document.getElementById('blockchain-wrapper');
                    if (window.innerWidth >= 1024) wrapper.scrollTo({ left: wrapper.scrollWidth, behavior: 'smooth' });
                }, 100);
            }

            updateStatus(msg, color) {
                const dot = document.getElementById('status-dot');
                const text = document.getElementById('status-text');
                dot.className = `w-2 h-2 rounded-full bg-${color}-500 shadow-[0_0_8px_rgba(${color==='green'?'34,197,94':'234,179,8'},0.5)] ${color === 'green' ? 'animate-pulse' : ''}`;
                text.innerText = msg;
                text.className = `font-mono text-xs text-${color}-400 uppercase tracking-widest`;
            }

            addMiner() { if(state.miners.length >= 10) return alert("Max 10 Miners"); state.miners.push({ id: Math.max(...state.miners.map(m=>m.id))+1, hashrate: 50000, blocksFound: 0, cost: 0, revenue: 0 }); this.renderMiners(); }
            removeMiner() { if(state.miners.length <= 1) return; state.miners.pop(); this.renderMiners(); }
            updateHashrate(id, val) { const m = state.miners.find(m => m.id === id); if(m) m.hashrate = parseInt(val); this.renderMiners(); }

            initChart() {
                const ctx = document.getElementById('diffChart').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Start'],
                        datasets: [{
                            data: [Number(state.currentDifficulty)],
                            borderColor: '#3b82f6', borderWidth: 2, backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.3, pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: { ticks: { color: '#94a3b8', font: { size: 10 }, callback: v => v===0?0:`${(v.toExponential(2).split('e')[0])}e${toSuperscript(v.toExponential(2).split('e')[1].replace('+',''))}` }, grid: { color: '#334155', borderDash: [4, 4] }, beginAtZero: false }
                        }
                    }
                });
            }

            updateChart(label, value) {
                this.chart.data.labels.push(label);
                this.chart.data.datasets[0].data.push(value);
                this.chart.update();
            }
        }

        const sim = new Simulator();

        // --- SYSTEM EXPLAINER BALLOON LOGIC ---
        const infoBalloon = document.createElement('div');
        infoBalloon.id = 'info-balloon';
        document.body.appendChild(infoBalloon);

        let hoverTimer = null;

        function showInfoBalloon(element, text) {
            infoBalloon.innerHTML = `<i class="fas fa-lightbulb text-yellow-400 mr-2"></i>${text}`;
            const rect = element.getBoundingClientRect();
            
            infoBalloon.style.left = (rect.left + rect.width / 2) + 'px';
            infoBalloon.style.top = rect.top + 'px';
            
            infoBalloon.classList.add('show');
        }

        function hideInfoBalloon() {
            clearTimeout(hoverTimer);
            infoBalloon.classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const triggers = document.querySelectorAll('.info-trigger');
            
            triggers.forEach(trigger => {
                const explainerText = trigger.getAttribute('data-info');
                
                trigger.addEventListener('mouseenter', function() {
                    hoverTimer = setTimeout(() => {
                        showInfoBalloon(this, explainerText);
                    }, 1500);
                });

                trigger.addEventListener('mouseleave', hideInfoBalloon);

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    clearTimeout(hoverTimer);
                    showInfoBalloon(this, explainerText);
                });
            });

            document.addEventListener('click', hideInfoBalloon);
        });
    </script>
</body>
</html>