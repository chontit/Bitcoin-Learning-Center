<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Flow Simulator | by Chollatis Bitcoiner</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-blue: #00e5ff;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(247, 147, 26, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 229, 255, 0.05) 0%, transparent 25%);
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
        }

        .step-card {
            transition: all 0.3s ease;
            opacity: 0.4;
            filter: grayscale(100%);
        }

        .step-card.active {
            opacity: 1;
            filter: grayscale(0%);
            border-color: var(--neon-btc);
            box-shadow: 0 0 20px rgba(247, 147, 26, 0.2);
            transform: scale(1.02);
        }

        .step-card.completed {
            opacity: 0.8;
            filter: grayscale(0%);
            border-color: var(--neon-green);
        }

        .btn-neon {
            background: linear-gradient(45deg, #F7931A, #ffb347);
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-neon:hover:not(:disabled) {
            box-shadow: 0 0 20px #F7931A;
            transform: scale(1.02);
        }
        .btn-neon:disabled {
            background: #333;
            color: #666;
            cursor: not-allowed;
            box-shadow: none;
        }

        #networkCanvas {
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            border-radius: 0.5rem;
        }

        .sim-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 8px;
            padding: 4px 10px;
            background: rgba(247, 147, 26, 0.1);
            border: 1px solid rgba(247, 147, 26, 0.3);
            border-radius: 4px;
            color: var(--neon-btc);
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.75rem;
        }
        .sim-link:hover {
            background: rgba(247, 147, 26, 0.2);
            border-color: var(--neon-btc);
        }

        /* Detail Panel Animation */
        .detail-panel {
            display: none;
            animation: fadeInRight 0.4s ease-out forwards;
        }
        .detail-panel.active {
            display: block;
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .hash-text { word-break: break-all; font-family: 'Courier New', Courier, monospace; }
        .data-box { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-network-wired text-4xl text-[#F7931A]"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#00e5ff] brand-font">
                    TX FLOW SIMULATOR
                </h1>
                <p class="text-xs text-gray-400">Step-by-step Bitcoin Transaction Journey</p>
            </div>
        </div>
        <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm">
            <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4 flex flex-col gap-4">
            <div class="neon-box p-6 rounded-xl flex-grow">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-[#00e5ff]"></i> Transaction
                    </h2>
                    <button id="btnNext" onclick="nextStep()" class="btn-neon px-5 py-2 rounded text-xs flex items-center gap-2">
                        ถัดไป <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:ml-[1.15rem] md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-700 before:to-transparent">
                    
                    <div id="step-1" class="step-card active relative flex items-center group is-active border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 group-[.is-active]:bg-[#F7931A] group-[.is-active]:text-black shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#F7931A] text-sm brand-font">1. CREATE</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">รวบรวม UTXO สร้าง Raw TX</p>
                        </div>
                    </div>

                    <div id="step-2" class="step-card relative flex items-center group border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#F7931A] text-sm brand-font">2. SIGN</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">ใช้ Private Key เซ็นดิจิทัล (ECDSA)</p>
                        </div>
                    </div>

                    <div id="step-3" class="step-card relative flex items-center group border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-wifi"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#00e5ff] text-sm brand-font">3. BROADCAST</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">ส่งข้อมูลสู่เครือข่าย (Gossip Protocol)</p>
                        </div>
                    </div>

                    <div id="step-4" class="step-card relative flex items-center group border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#F7931A] text-sm brand-font">4. MEMPOOL WAIT</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">รอ Miner เลือกไปแพ็กใส่ Block</p>
                        </div>
                    </div>

                    <div id="step-5" class="step-card relative flex items-center group border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-hammer"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#F7931A] text-sm brand-font">5. MINE</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">Miner แก้สมการ PoW สำเร็จ</p>
                        </div>
                    </div>

                    <div id="step-6" class="step-card relative flex items-center group border border-gray-700 p-3 rounded-lg bg-black/40 z-10">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-gray-800 text-gray-400 shrink-0 shadow-[0_0_0_4px_#050505] mr-3">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#00ff41] text-sm brand-font">6. CONFIRM</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">ได้รับ Confirmation ลงใน Blockchain</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <button onclick="resetFlow()" class="bg-gray-800 hover:bg-gray-700 text-gray-400 py-2.5 rounded text-xs transition border border-gray-700">
                <i class="fa-solid fa-rotate-left"></i> เริ่มจำลองใหม่
            </button>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-4">
            
            <div class="neon-box rounded-xl border-t-2 border-[#333] bg-gradient-to-b from-gray-900 to-[#050505] min-h-[140px] flex flex-col transition-colors duration-500" id="monitor-box">
                <div class="px-4 py-2 border-b border-gray-800 flex justify-between items-center bg-black/50 rounded-t-xl">
                    <span class="text-xs text-gray-400 uppercase tracking-widest"><i class="fa-solid fa-terminal mr-2"></i>Execution Monitor</span>
                    <span class="text-[10px] text-gray-500" id="monitor-step-text">Step 1/6</span>
                </div>
                
                <div class="p-4 flex-grow relative overflow-hidden flex items-center justify-center">
                    
                    <div id="detail-1" class="detail-panel active w-full">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-[#F7931A] font-bold text-sm">TX Builder (UTXO Selection)</h4>
                            <a href="utxo.php" class="sim-link" target="_blank"><i class="fa-solid fa-link"></i> Simulator</a>
                        </div>
                        <div class="data-box bg-black/50 p-3 rounded border border-gray-800 text-xs w-full">
                            <div class="flex justify-between text-white border-b border-gray-800 pb-1 mb-1"><span>Input (Alice's UTXO):</span> <span class="text-red-400">-1.500 BTC</span></div>
                            <div class="flex justify-between text-white"><span>Output 0 (To Bob):</span> <span class="text-green-400">+1.000 BTC</span></div>
                            <div class="flex justify-between text-white"><span>Output 1 (Change to Alice):</span> <span class="text-blue-400">+0.499 BTC</span></div>
                            <div class="flex justify-between border-t border-gray-800 mt-1 pt-1">
                                <span class="text-gray-500">Miner Fee:</span> <span class="text-[#F7931A] font-bold">0.001 BTC</span>
                            </div>
                        </div>
                    </div>

                    <div id="detail-2" class="detail-panel w-full text-center">
                        <div class="flex justify-between items-center mb-2 absolute top-0 w-full px-4">
                            <h4 class="text-[#F7931A] font-bold text-sm text-left">Digital Signature</h4>
                            <a href="digital_signature.php" class="sim-link" target="_blank"><i class="fa-solid fa-link"></i> Simulator</a>
                        </div>
                        <div class="mt-6">
                            <i class="fa-solid fa-file-signature text-3xl text-gray-600 mb-2 transition-all duration-500" id="sign-icon"></i>
                            <div class="text-xs text-gray-400 mb-2" id="sign-status-text">Applying ECDSA Signature...</div>
                            <div class="data-box bg-black p-2 rounded border border-gray-800 text-[10px] text-gray-500 hash-text" id="signature-output">
                                Waiting for private key...
                            </div>
                        </div>
                    </div>

                    <div id="detail-3" class="detail-panel w-full">
                        <h4 class="text-[#00e5ff] font-bold text-sm mb-2">Node Terminal</h4>
                        <div class="data-box bg-[#0a0a0a] p-3 rounded border border-[#00e5ff]/30 text-[#00e5ff] text-xs h-20 flex flex-col justify-center">
                            <div><span class="opacity-50">user@node:~ $</span> sendrawtransaction 020000000144fa...</div>
                            <div id="term-status" class="animate-pulse text-gray-400 mt-2">> Waiting for network propagation...</div>
                        </div>
                    </div>

                    <div id="detail-4" class="detail-panel w-full">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-[#F7931A] font-bold text-sm">Mempool Status</h4>
                            <span class="text-[10px] bg-green-900/50 text-green-400 border border-green-500/50 px-2 py-0.5 rounded animate-pulse">● LIVE API</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-center w-full" id="mempool-fees">
                            <div class="bg-[#111] p-3 rounded border border-gray-800">
                                <div class="text-[10px] text-gray-400 uppercase">High Priority</div>
                                <div class="text-xl text-white font-bold mt-1"><span id="fee-high">--</span> <span class="text-[9px] text-gray-500 font-normal">sat/vB</span></div>
                            </div>
                            <div class="bg-[#111] p-3 rounded border border-gray-800">
                                <div class="text-[10px] text-gray-400 uppercase">Medium Priority</div>
                                <div class="text-xl text-white font-bold mt-1"><span id="fee-med">--</span> <span class="text-[9px] text-gray-500 font-normal">sat/vB</span></div>
                            </div>
                            <div class="bg-[#111] p-3 rounded border border-gray-800">
                                <div class="text-[10px] text-gray-400 uppercase">Low Priority</div>
                                <div class="text-xl text-white font-bold mt-1"><span id="fee-low">--</span> <span class="text-[9px] text-gray-500 font-normal">sat/vB</span></div>
                            </div>
                        </div>
                    </div>

                    <div id="detail-5" class="detail-panel w-full">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-[#F7931A] font-bold text-sm">Proof of Work (Mining)</h4>
                            <a href="miner.php" class="sim-link" target="_blank"><i class="fa-solid fa-link"></i> Simulator</a>
                        </div>
                        <div class="data-box bg-[#0a0a0a] p-3 rounded border border-gray-800 w-full transition-colors duration-300" id="mining-box">
                            <div class="flex justify-between items-end mb-2">
                                <div class="text-[10px] text-gray-500">Target: <span class="text-white tracking-widest">0000000000000000000...</span></div>
                                <div class="text-xs text-yellow-400 font-bold" id="hash-rate">0 H/s</div>
                            </div>
                            <div class="bg-black p-2 rounded text-[11px] text-red-500 hash-text min-h-[32px] flex items-center" id="mining-hash">
                                Waiting for miner...
                            </div>
                        </div>
                    </div>

                    <div id="detail-6" class="detail-panel w-full">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-[#00ff41] font-bold text-sm">Blockchain Confirmation</h4>
                            <span class="text-[10px] bg-green-900/50 text-green-400 border border-green-500/50 px-2 py-0.5 rounded animate-pulse">● LIVE API</span>
                        </div>
                        <div class="bg-[#00ff41]/5 p-3 rounded border border-[#00ff41]/20 w-full">
                            <div class="flex justify-between border-b border-[#00ff41]/10 pb-2 mb-2">
                                <span class="text-xs text-gray-400">Latest Block Height:</span> 
                                <span class="text-sm text-white font-bold brand-font" id="live-height">fetching...</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block mb-1">Block Hash:</span> 
                                <div class="data-box bg-black/50 p-2 rounded text-[10px] text-[#00ff41] hash-text" id="live-hash">fetching...</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="neon-box p-4 rounded-xl flex-grow flex flex-col min-h-[350px]">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-gray-300"><i class="fa-solid fa-globe"></i> Global P2P Network Simulator</h3>
                    <div id="network-status" class="text-xs px-2 py-1 rounded bg-gray-800 text-gray-400 border border-gray-700">Status: Waiting to Broadcast...</div>
                </div>
                <div class="relative w-full flex-grow border border-gray-800 rounded-lg overflow-hidden bg-[#080808]">
                    <canvas id="networkCanvas"></canvas>
                    
                    <div id="canvas-overlay" class="absolute inset-0 flex items-center justify-center bg-black/80 z-20 transition-opacity duration-300">
                        <div class="text-center">
                            <i class="fa-solid fa-network-wired text-4xl text-gray-600 mb-2"></i>
                            <p class="text-sm text-gray-400">ระบบจำลองเครือข่ายพร้อมทำงาน</p>
                            <p class="text-xs text-gray-500 mt-1">กดปุ่ม ถัดไป ด้านซ้าย เพื่อเริ่มจำลองกระบวนการส่งบิตคอยน์</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="neon-box p-5 rounded-xl bg-gradient-to-r from-gray-900 to-black border-l-4 border-[#F7931A]">
                <h3 class="text-sm font-bold text-white mb-2"><i class="fa-solid fa-book-open text-[#F7931A]"></i> บทสรุปการเรียนรู้</h3>
                <ul class="text-xs text-gray-400 leading-relaxed list-disc pl-4 space-y-1.5">
                    <li><b>Gossip Protocol:</b> ข้อมูลถูกส่งต่อเป็นทอดๆ ระหว่าง Node เหมือนการกระซิบ ทำให้ข้อมูลกระจายทั่วโลกโดยไม่มีศูนย์กลาง</li>
                    <li><b>Mempool & Block:</b> ธุรกรรมของคุณต้องรออยู่ใน Mempool (ห้องพัก) ก่อน จนกว่า Miner จะหยิบไปแก้สมการ (PoW) สำเร็จ จึงจะถูกบรรจุลง Block จริง</li>
                    <li><b>Ecosystem:</b> การโอนบิตคอยน์ผสานการทำงานของ UTXO, การเข้ารหัสลับ (Cryptography), และระบบกระจายศูนย์ (P2P Network) เข้าด้วยกันอย่างสมบูรณ์</li>
                </ul>
            </div>
        </div>

    </main>
    
    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-6 bg-black/30 backdrop-blur-sm">
        <p>© 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner.</span> <span class="mx-2 text-gray-700">|</span> Don't Trust, Verify.</p>
    </footer>

    <script>
        let currentStep = 1;
        const totalSteps = 6;
        let miningInterval;
        let step3Timeout; // ตัวแปรเก็บ timeout ของ Step 3
        
        // --- Canvas Setup ---
        const canvas = document.getElementById('networkCanvas');
        const ctx = canvas.getContext('2d');
        let nodes = [];
        let edges = [];
        let animationId;
        let isTxBroadcasting = false;
        let isBlockBroadcasting = false;

        function resizeCanvas() {
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = canvas.parentElement.clientHeight;
            if(!isTxBroadcasting && !isBlockBroadcasting) initNetwork();
        }
        window.addEventListener('resize', resizeCanvas);

        class Node {
            constructor(x, y, id) {
                this.x = x;
                this.y = y;
                this.id = id;
                this.hasTx = false;      
                this.hasBlock = false;   
                this.isOrigin = false;
                this.isMiner = false;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, (this.isOrigin || this.isMiner) ? 6 : 4, 0, Math.PI * 2);
                
                if (this.isMiner) {
                    ctx.fillStyle = '#F7931A';
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = '#F7931A';
                } else if (this.hasBlock) {
                    ctx.fillStyle = '#F7931A';
                    ctx.shadowBlur = 8;
                    ctx.shadowColor = '#F7931A';
                } else if (this.isOrigin) {
                    ctx.fillStyle = '#00e5ff';
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = '#00e5ff';
                } else if (this.hasTx) {
                    ctx.fillStyle = '#00ff41';
                    ctx.shadowBlur = 8;
                    ctx.shadowColor = '#00ff41';
                } else {
                    ctx.fillStyle = '#444';
                    ctx.shadowBlur = 0;
                }
                
                ctx.fill();
                ctx.shadowBlur = 0; 
            }
        }

        function initNetwork() {
            nodes = [];
            edges = [];
            const numNodes = 70;
            
            for(let i = 0; i < numNodes; i++) {
                nodes.push(new Node(
                    Math.random() * (canvas.width - 40) + 20,
                    Math.random() * (canvas.height - 40) + 20,
                    i
                ));
            }

            for(let i = 0; i < nodes.length; i++) {
                let connections = 0;
                for(let j = i + 1; j < nodes.length; j++) {
                    const dx = nodes[i].x - nodes[j].x;
                    const dy = nodes[i].y - nodes[j].y;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    
                    if(dist < 130 && connections < 4) {
                        edges.push({
                            source: i, target: j, 
                            txActive: false, txProgress: 0,
                            blockActive: false, blockProgress: 0
                        });
                        connections++;
                    }
                }
            }
            drawNetwork();
        }

        function drawNetwork() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            edges.forEach(edge => {
                const s = nodes[edge.source];
                const t = nodes[edge.target];
                
                ctx.beginPath();
                ctx.moveTo(s.x, s.y);
                ctx.lineTo(t.x, t.y);
                
                if (edge.blockActive) {
                    ctx.strokeStyle = `rgba(247, 147, 26, ${edge.blockProgress})`;
                    ctx.lineWidth = 2.5;
                    
                    const px = s.x + (t.x - s.x) * edge.blockProgress;
                    const py = s.y + (t.y - s.y) * edge.blockProgress;
                    ctx.beginPath();
                    ctx.arc(px, py, 3, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    
                } else if (s.hasBlock && t.hasBlock) {
                    ctx.strokeStyle = 'rgba(247, 147, 26, 0.3)';
                    ctx.lineWidth = 1.5;
                } else if (edge.txActive) {
                    ctx.strokeStyle = `rgba(0, 229, 255, ${edge.txProgress})`;
                    ctx.lineWidth = 2;
                    
                    const px = s.x + (t.x - s.x) * edge.txProgress;
                    const py = s.y + (t.y - s.y) * edge.txProgress;
                    ctx.beginPath();
                    ctx.arc(px, py, 2.5, 0, Math.PI * 2);
                    ctx.fillStyle = '#fff';
                    ctx.fill();
                    
                } else if (s.hasTx && t.hasTx) {
                    ctx.strokeStyle = 'rgba(0, 255, 65, 0.2)';
                    ctx.lineWidth = 1;
                } else {
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
                    ctx.lineWidth = 1;
                }
                ctx.stroke();
            });

            nodes.forEach(node => node.draw());
        }

        function updateNetwork() {
            let activeTxEdges = false;
            let activeBlockEdges = false;
            let txInfected = 0;
            let blockInfected = 0;

            edges.forEach(edge => {
                const s = nodes[edge.source];
                const t = nodes[edge.target];

                // Phase 1: TX Propagation
                if (isTxBroadcasting && !isBlockBroadcasting) {
                    if (s.hasTx !== t.hasTx) {
                        if (!edge.txActive && Math.random() < 0.03) {
                            edge.txActive = true;
                            if(!s.hasTx) { edge.source = t.id; edge.target = s.id; }
                        }
                    }
                    if (edge.txActive) {
                        activeTxEdges = true;
                        edge.txProgress += 0.02; 
                        if (edge.txProgress >= 1) {
                            edge.txActive = false; edge.txProgress = 0;
                            nodes[edge.target].hasTx = true;
                        }
                    }
                }

                // Phase 2: Block Propagation
                if (isBlockBroadcasting) {
                    if (s.hasBlock !== t.hasBlock) {
                        if (!edge.blockActive && Math.random() < 0.05) { 
                            edge.blockActive = true;
                            if(!s.hasBlock) { edge.source = t.id; edge.target = s.id; }
                        }
                    }
                    if (edge.blockActive) {
                        activeBlockEdges = true;
                        edge.blockProgress += 0.025;
                        if (edge.blockProgress >= 1) {
                            edge.blockActive = false; edge.blockProgress = 0;
                            nodes[edge.target].hasBlock = true;
                        }
                    }
                }
            });

            nodes.forEach(n => { 
                if(n.hasTx) txInfected++; 
                if(n.hasBlock) blockInfected++; 
            });
            
            if (isBlockBroadcasting) {
                if (blockInfected === nodes.length && !activeBlockEdges) {
                    document.getElementById('network-status').innerText = `Status: Block Synchronized (100%)`;
                    document.getElementById('network-status').className = "text-xs px-2 py-1 rounded bg-[#F7931A]/20 text-[#F7931A] border border-[#F7931A]/50";
                } else {
                    document.getElementById('network-status').innerText = `Status: Block Propagating... (${Math.round((blockInfected/nodes.length)*100)}%)`;
                }
            } else if (isTxBroadcasting) {
                if (txInfected === nodes.length && !activeTxEdges) {
                    document.getElementById('network-status').innerText = `Status: Mempool Synchronized (100%)`;
                    document.getElementById('network-status').className = "text-xs px-2 py-1 rounded bg-[#00ff41]/20 text-[#00ff41] border border-[#00ff41]/50";
                } else {
                    document.getElementById('network-status').innerText = `Status: TX Propagating... (${Math.round((txInfected/nodes.length)*100)}%)`;
                }
            }

            drawNetwork();

            if (isTxBroadcasting || isBlockBroadcasting) {
                animationId = requestAnimationFrame(updateNetwork);
            }
        }

        function startTxBroadcast() {
            document.getElementById('canvas-overlay').style.opacity = '0';
            setTimeout(() => document.getElementById('canvas-overlay').classList.add('hidden'), 300);
            
            isTxBroadcasting = true;
            document.getElementById('network-status').className = "text-xs px-2 py-1 rounded bg-[#00e5ff]/20 text-[#00e5ff] border border-[#00e5ff]/50 animate-pulse";
            
            const originIdx = Math.floor(Math.random() * nodes.length);
            nodes[originIdx].hasTx = true;
            nodes[originIdx].isOrigin = true;
            
            updateNetwork();
        }

        function startBlockBroadcast() {
            isBlockBroadcasting = true;
            document.getElementById('network-status').className = "text-xs px-2 py-1 rounded bg-[#F7931A]/20 text-[#F7931A] border border-[#F7931A]/50 animate-pulse";
            
            let minerIdx = Math.floor(Math.random() * nodes.length);
            nodes.forEach(n => n.isOrigin = false);
            
            nodes[minerIdx].hasBlock = true;
            nodes[minerIdx].isMiner = true;
            
            cancelAnimationFrame(animationId);
            updateNetwork();
        }

        // --- Flow Control ---
        function updateStepUI() {
            // Clear previous actions
            clearInterval(miningInterval);
            clearTimeout(step3Timeout);

            // Update Left Timeline Nav
            for(let i = 1; i <= totalSteps; i++) {
                const el = document.getElementById(`step-${i}`);
                const icon = el.querySelector('div:first-child');
                
                el.classList.remove('active', 'is-active', 'completed');
                icon.classList.remove('group-[.is-active]:bg-[#F7931A]', 'group-[.is-active]:text-black', 'bg-[#00ff41]', 'text-black');
                
                if (i < currentStep) {
                    el.classList.add('completed');
                    icon.classList.add('bg-[#00ff41]', 'text-black');
                    icon.innerHTML = '<i class="fa-solid fa-check"></i>';
                } else if (i === currentStep) {
                    el.classList.add('active', 'is-active');
                    icon.classList.add('group-[.is-active]:bg-[#F7931A]', 'group-[.is-active]:text-black');
                }
            }

            // Update Right Monitor Panels
            document.getElementById('monitor-step-text').innerText = `Step ${currentStep}/6`;
            
            // Change border color of monitor box based on step theme
            const monitorBox = document.getElementById('monitor-box');
            monitorBox.className = "neon-box rounded-xl border-t-2 bg-gradient-to-b from-gray-900 to-[#050505] min-h-[140px] flex flex-col transition-colors duration-500";
            if(currentStep === 3) monitorBox.classList.add('border-[#00e5ff]');
            else if(currentStep === 6) monitorBox.classList.add('border-[#00ff41]');
            else monitorBox.classList.add('border-[#F7931A]');

            for(let i = 1; i <= totalSteps; i++) {
                document.getElementById(`detail-${i}`).classList.remove('active');
            }
            document.getElementById(`detail-${currentStep}`).classList.add('active');

            // Button Logic
            const btn = document.getElementById('btnNext');
            if (currentStep === totalSteps) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-flag-checkered"></i> สำเร็จ!';
            } else {
                btn.disabled = false;
                btn.innerHTML = 'ถัดไป <i class="fa-solid fa-arrow-right"></i>';
            }

            // --- Step Actions (Right Panel) ---
            
            // Step 2: Sign
            if (currentStep === 2) {
                setTimeout(() => {
                    document.getElementById('sign-icon').className = "fa-solid fa-file-shield text-3xl text-[#00ff41] mb-2 drop-shadow-[0_0_5px_#00ff41] transition-all duration-500";
                    document.getElementById('sign-status-text').innerHTML = "<span class='text-[#00ff41]'>Signature Applied Successfully</span>";
                    document.getElementById('signature-output').innerText = "304402204e45e16932b8af514961a1d3a1a25fdf3f4f7732e9d624c6c61548ab5fb8cd410220181522fC... [VALID]";
                    document.getElementById('signature-output').classList.replace('text-gray-500', 'text-[#00ff41]');
                }, 1000);
            }

            // Step 3: Broadcast (Delayed Animation)
            if (currentStep === 3) {
                document.getElementById('term-status').innerText = "> Waiting for network propagation...";
                document.getElementById('term-status').className = "animate-pulse text-gray-400 mt-2";
                
                step3Timeout = setTimeout(() => {
                    document.getElementById('term-status').innerText = "> [OK] Transaction accepted by network!";
                    document.getElementById('term-status').classList.remove('animate-pulse', 'text-gray-400');
                    document.getElementById('term-status').classList.add('text-[#00ff41]');
                    
                    if (!isTxBroadcasting) startTxBroadcast();
                }, 2000);
            }

            // Step 4: Mempool Fetch
            if (currentStep === 4) {
                fetch('https://mempool.space/api/v1/fees/recommended')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('fee-high').innerText = data.fastestFee;
                        document.getElementById('fee-med').innerText = data.halfHourFee;
                        document.getElementById('fee-low').innerText = data.hourFee;
                    })
                    .catch(err => console.log("Mempool API Error"));
            }

            // Step 5: Mine Fake Hash (Delayed Animation)
            if (currentStep === 5) {
                const hashDisplay = document.getElementById('mining-hash');
                const rateDisplay = document.getElementById('hash-rate');
                const boxDisplay = document.getElementById('mining-box');
                let attempts = 0;
                
                miningInterval = setInterval(() => {
                    attempts += 1000000;
                    rateDisplay.innerText = "500 MH/s";
                    
                    const randomHex = [...Array(64)].map(() => Math.floor(Math.random() * 16).toString(16)).join('');
                    hashDisplay.innerText = "0000" + randomHex.substring(4);
                    hashDisplay.className = "bg-[#111] p-2 rounded text-[11px] text-yellow-500 hash-text min-h-[32px] flex items-center";
                    boxDisplay.className = "data-box bg-[#0a0a0a] p-3 rounded border border-gray-800 w-full transition-colors duration-300";

                    if (attempts > 30000000) { 
                        clearInterval(miningInterval);
                        
                        rateDisplay.innerText = "Solved! (Block Found)";
                        rateDisplay.className = "text-xs text-[#00ff41] font-bold";
                        boxDisplay.classList.replace('border-gray-800', 'border-[#00ff41]');
                        
                        hashDisplay.innerText = "0000000000000000000" + randomHex.substring(19);
                        hashDisplay.className = "bg-[#00ff41]/10 p-2 rounded text-[11px] text-[#00ff41] font-bold drop-shadow-[0_0_5px_#00ff41] hash-text min-h-[32px] flex items-center";
                        
                        if (!isBlockBroadcasting) startBlockBroadcast();
                    }
                }, 50);
            }

            // Step 6: Confirm (Fetch Block Height)
            if (currentStep === 6) {
                fetch('https://mempool.space/api/blocks/tip/height')
                    .then(res => res.text())
                    .then(height => { document.getElementById('live-height').innerText = height; })
                    .catch(() => { document.getElementById('live-height').innerText = "Offline"; });
                
                fetch('https://mempool.space/api/blocks/tip/hash')
                    .then(res => res.text())
                    .then(hash => { document.getElementById('live-hash').innerText = hash; })
                    .catch(() => { document.getElementById('live-hash').innerText = "Offline"; });
            }
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepUI();
            }
        }

        function resetFlow() {
            currentStep = 1;
            isTxBroadcasting = false;
            isBlockBroadcasting = false;
            clearInterval(miningInterval);
            clearTimeout(step3Timeout);
            cancelAnimationFrame(animationId);
            
            // Reset UI Overlays
            document.getElementById('canvas-overlay').classList.remove('hidden');
            setTimeout(() => document.getElementById('canvas-overlay').style.opacity = '1', 50);
            document.getElementById('network-status').innerText = "Status: Waiting to Broadcast...";
            document.getElementById('network-status').className = "text-xs px-2 py-1 rounded bg-gray-800 text-gray-400 border border-gray-700";
            
            // Reset Step 2
            document.getElementById('sign-icon').className = "fa-solid fa-file-signature text-3xl text-gray-600 mb-2";
            document.getElementById('sign-status-text').innerText = "Applying ECDSA Signature...";
            document.getElementById('signature-output').innerText = "Waiting for private key...";
            document.getElementById('signature-output').classList.replace('text-[#00ff41]', 'text-gray-500');

            // Reset Step 3
            document.getElementById('term-status').innerText = "> Waiting for network propagation...";
            document.getElementById('term-status').className = "animate-pulse text-gray-400 mt-2";

            // Reset Step 4
            document.getElementById('fee-high').innerText = "--";
            document.getElementById('fee-med').innerText = "--";
            document.getElementById('fee-low').innerText = "--";

            // Reset Step 5
            document.getElementById('hash-rate').innerText = "0 H/s";
            document.getElementById('hash-rate').className = "text-xs text-yellow-400 font-bold";
            document.getElementById('mining-hash').innerText = "Waiting for miner...";
            document.getElementById('mining-hash').className = "bg-black p-2 rounded text-[11px] text-red-500 hash-text min-h-[32px] flex items-center";
            document.getElementById('mining-box').className = "data-box bg-[#0a0a0a] p-3 rounded border border-gray-800 w-full transition-colors duration-300";

            // Reset Step 6
            document.getElementById('live-height').innerText = "fetching...";
            document.getElementById('live-hash').innerText = "fetching...";

            // Reset Icons
            const icons = ["fa-pen-to-square", "fa-key", "fa-wifi", "fa-hourglass-half", "fa-hammer", "fa-check-double"];
            for(let i = 1; i <= totalSteps; i++) {
                const iconContainer = document.querySelector(`#step-${i} div:first-child`);
                iconContainer.innerHTML = `<i class="fa-solid ${icons[i-1]}"></i>`;
            }

            initNetwork();
            updateStepUI();
        }

        // Initialize
        setTimeout(() => {
            resizeCanvas();
            initNetwork();
            updateStepUI();
        }, 100);

    </script>
</body>
</html>