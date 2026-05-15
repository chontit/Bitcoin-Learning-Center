<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Issuance Simulator | by Chollatis Bitcoiner</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            box-shadow: 0 0 20px #F7931A;
            transform: scale(1.02);
        }

        .input-dark {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: #fff;
            transition: 0.3s;
        }

        @keyframes flash-halving {
            0% { box-shadow: 0 0 0px var(--neon-red); background-color: transparent; }
            30% { box-shadow: 0 0 40px var(--neon-red); background-color: rgba(255, 0, 85, 0.15); border-color: var(--neon-red); }
            100% { box-shadow: 0 0 0px var(--neon-red); background-color: transparent; border-color: rgba(255, 255, 255, 0.1); }
        }
        .halving-flash {
            animation: flash-halving 3s ease-out;
        }

        @keyframes bit-shift {
            0% { transform: translateX(-5px); opacity: 0.5; }
            100% { transform: translateX(0); opacity: 1; }
        }
        .animate-shift {
            animation: bit-shift 0.3s ease-out;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col select-none md:select-auto">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-brands fa-bitcoin text-4xl text-[#F7931A] animate-pulse"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font">
                    ISSUANCE SIMULATOR
                </h1>
                <p class="text-xs text-gray-400">Bitcoin Controlled Supply & Halving</p>
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
                    <i class="fa-solid fa-microchip"></i> แผงควบคุมการขุด
                </h2>

                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-1">ความเร็วในการจำลอง (กดค้าง / ออโต้)</label>
                    <select id="sim_speed" class="w-full p-2 rounded input-dark mb-2">
                        <option value="1000">1,000 Block / วินาที</option>
                        <option value="10000" selected>10,000 Block / วินาที</option>
                        <option value="50000">50,000 Block / วินาที</option>
                        <option value="100000">100,000 Block / วินาที</option>
                        <option value="500000">500,000 Block / วินาที (Fast Forward)</option>
                    </select>
                    
                    <label class="flex items-center gap-2 cursor-pointer mt-2">
                        <input type="checkbox" id="chk_stop_halving" class="accent-[#F7931A] w-4 h-4" checked>
                        <span class="text-sm text-gray-300 hover:text-[#F7931A] transition">หยุดชั่วคราวเมื่อถึงจุด Halving</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button id="btn_manual_mine" class="col-span-2 border border-[#F7931A] text-[#F7931A] hover:bg-[#F7931A] hover:text-black py-2 rounded text-sm transition font-bold flex justify-center items-center gap-2 select-none cursor-pointer">
                        <i class="fa-solid fa-hand-pointer"></i> ขุดทีละบล็อก (คลิก / กดค้าง)
                    </button>
                    
                    <button id="btn_auto" onclick="toggleAutoMine()" class="col-span-2 btn-neon py-3 rounded text-sm shadow-[0_0_15px_rgba(247,147,26,0.5)] flex justify-center items-center gap-2">
                        <i class="fa-solid fa-play"></i> เริ่มขุดอัตโนมัติ
                    </button>
                    
                    <button onclick="resetSimulator()" class="col-span-2 bg-gray-800 hover:bg-gray-700 text-gray-400 py-2 rounded text-xs transition">
                        <i class="fa-solid fa-rotate-left"></i> รีเซ็ตใหม่ (Genesis Block)
                    </button>
                </div>

                <div class="border-t border-gray-700 pt-4">
                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                        <span id="halving_label">ความคืบหน้าสู่ Halving ถัดไป</span>
                        <span id="halving_countdown" class="font-bold">210,000 Blocks</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden">
                        <div id="halving_progress" class="bg-[#ff0055] h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="neon-box p-5 rounded-xl bg-gradient-to-r from-gray-900 to-black">
                <h3 class="text-md font-bold text-white mb-2"><i class="fa-solid fa-book-open text-[#00ff41]"></i> สิ่งที่ได้เรียนรู้จากเว็บไซต์นี้</h3>
                <ul class="text-sm text-gray-300 leading-relaxed list-disc pl-4 space-y-2">
                    <li>ระบบบิตคอยน์ถูกออกแบบมาให้สามารถผลิตได้อย่างจำกัด (จำนวนไม่เกิน 21 ล้านเหรียญ) ซึ่งเป็นผลที่เกิดจากกระบวนการ Bitcoin Halving</li>
                    <li>เมื่อเกิดกระบวนการ Halving (ทุก ๆ 210,000 บล็อก) โปรแกรมจะดำเนินการ <b>Right Shift Operator (`>> 1`)</b> นั่นคือ การทำให้เลขฐาน 2 ขยับไปทางขวา 1 ตำแหน่ง</li>
                    <li>การขยับเลขฐาน 2 ไปทางขวา 1 ตำแหน่ง ส่งผลให้มูลค่าเลขฐาน 10 (Satoshi) <b>ลดลงครึ่งหนึ่ง</b></li>
                    <li><span class="text-[#a855f7] font-bold">Stock-to-Flow (S2F):</span> อัตราส่วนระหว่างปริมาณเหรียญทั้งหมดเทียบกับปริมาณที่ผลิตใหม่ต่อปี ยิ่งมีค่าสูง แสดงถึงความหายาก (Scarcity) ที่เพิ่มขึ้น</li>
                    <li>สุดท้ายแล้วเมื่อถึง Halving ครั้งที่ 33 รางวัล Block Subsidy หรือบิตคอยน์ที่ถูกผลิตใหม่จะเหลือ <b>0 Satoshi</b> อย่างถาวร แต่บล็อกยังคงวิ่งต่อไปเพื่อยืนยันธุรกรรมและทำให้ระบบบิตคอยน์ทำงานอย่างต่อเนื่อง</li>
                </ul>
            </div>
        </div>

        <div class="lg:col-span-8 flex flex-col gap-4">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="neon-box p-4 rounded-lg border-l-4 border-[#F7931A]">
                    <div class="text-gray-400 text-xs uppercase mb-1">Block Height</div>
                    <div class="text-2xl font-mono font-bold text-white" id="disp_block">0</div>
                    <div class="text-xs text-gray-500 mt-1">จำนวนบล็อกที่ขุดไปแล้ว</div>
                </div>

                <div class="neon-box p-4 rounded-lg border-l-4 border-[#00ff41]">
                    <div class="text-gray-400 text-xs uppercase mb-1">Total Bitcoin Mined</div>
                    <div class="text-2xl font-mono font-bold text-[#00ff41]" id="disp_btc">0.00000000</div>
                    <div class="text-xs text-gray-500 mt-1" id="disp_sats">0 Sats</div>
                </div>

                <div class="neon-box p-4 rounded-lg border-l-4 border-blue-500">
                    <div class="text-gray-400 text-xs uppercase mb-1">Estimated Date</div>
                    <div class="text-2xl font-mono font-bold text-blue-400" id="disp_date">Jan 2009</div>
                    <div class="text-xs text-gray-500 mt-1">(คำนวณอิงตามประวัติศาสตร์จริง)</div>
                </div>
            </div>

            <div id="subsidy_box" class="neon-box p-6 md:p-8 rounded-xl relative overflow-hidden flex flex-col items-center min-h-[300px]">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                
                <h2 class="text-xl font-bold brand-font text-white z-10 mb-2">BLOCK SUBSIDY (REWARD)</h2>
                <div class="text-[#F7931A] font-bold text-lg z-10 mb-6 bg-black/50 px-4 py-1 border border-[#F7931A]/30 rounded-full">
                    Halving count : <span id="disp_epoch">0</span> <span class="text-gray-400 text-sm ml-2" id="disp_shift_op">(>> 0)</span>
                </div>
                
                <div class="text-center z-10 w-full mb-8">
                    <div class="text-gray-400 text-sm mb-1 uppercase tracking-widest">Satoshi (Base 10)</div>
                    <div class="text-4xl md:text-5xl font-mono font-bold text-white tracking-wider" id="disp_subsidy_dec">
                        5,000,000,000
                    </div>
                </div>

                <div class="text-center z-10 w-full">
                    <div class="text-gray-400 text-sm mb-3 uppercase tracking-widest flex items-center justify-center gap-2">
                        <span>Binary (Base 2)</span>
                        <span id="shift_alert" class="hidden text-[#ff0055] text-xs font-bold px-2 py-0.5 border border-[#ff0055] rounded animate-pulse">
                            RIGHT SHIFT! >> 1
                        </span>
                    </div>
                    
                    <div id="binary_display" class="font-mono text-center flex flex-wrap justify-center gap-1 md:gap-1.5 px-2">
                        </div>
                </div>
            </div>

            <div class="neon-box p-4 rounded-xl w-full h-[280px] md:h-[350px] flex flex-col">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2 flex-none">
                    <div class="flex items-center gap-3">
                        <h3 class="text-sm font-bold text-gray-300"><i class="fa-solid fa-chart-line"></i> Supply Curve vs Block Reward</h3>
                        <div class="text-[11px] bg-[#a855f7]/20 border border-[#a855f7]/40 text-[#a855f7] px-2 py-0.5 rounded shadow-[0_0_8px_rgba(168,85,247,0.3)] flex items-center gap-1">
                            S2F: <span id="disp_s2f" class="font-mono font-bold text-white text-sm">0.00</span>
                        </div>
                    </div>
                    <div class="text-[10px] text-gray-400 flex gap-3">
                        <span class="flex items-center gap-1"><div class="w-3 h-1 bg-[#00ff41]"></div> BTC Mined</span>
                        <span class="flex items-center gap-1"><div class="w-3 h-1 bg-[#ff0055]"></div> Block Reward</span>
                    </div>
                </div>
                <div class="relative w-full flex-grow min-h-0">
                    <canvas id="supplyChart"></canvas>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-6 bg-black/30 backdrop-blur-sm">
        <p>© 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner.</span> <span class="mx-2 text-gray-700">|</span> Don't Trust, Verify.</p>
    </footer>

    <script>
        // --- ค่าคงที่ของ Bitcoin Protocol ---
        const INITIAL_SUBSIDY = 5000000000n; // 50 BTC (Satoshi)
        const HALVING_INTERVAL = 210000;
        const BLOCK_TIME_MS = 10 * 60 * 1000;  // 10 minutes (ใช้พยากรณ์อนาคต)
        const BLOCKS_PER_YEAR = 52560; // จำนวนบล็อกโดยเฉลี่ยใน 1 ปี (6 * 24 * 365)

        // --- ข้อมูลวันที่ Halving ตามประวัติศาสตร์ (Timestamp MS) ---
        const MILESTONE_DATES = [
            new Date("2009-01-03").getTime(), // Block 0 (Genesis)
            new Date("2012-11-27").getTime(), // Block 210,000 (Halving 1)
            new Date("2016-07-09").getTime(), // Block 420,000 (Halving 2)
            new Date("2020-05-11").getTime(), // Block 630,000 (Halving 3)
            new Date("2024-04-20").getTime(), // Block 840,000 (Halving 4)
            new Date("2028-04-17").getTime()  // Block 1,050,000 (Halving 5 - Projected)
        ];

        // --- สถานะการจำลอง ---
        let blockHeight = 0;
        let totalSatoshi = 0n;
        let isAutoMining = false;
        let simInterval = null;
        let hasReachedEnd = false; 
        
        let holdTimeout = null;
        let holdInterval = null;

        let chartInstance = null;
        let lastChartUpdateBlock = -1;
        const CHART_UPDATE_INTERVAL = 20000;

        const fps = 30; // จำนวนเฟรมต่อวินาที

        document.addEventListener('DOMContentLoaded', () => {
            initChart();
            updateUI(true);

            // --- ระบบกดค้าง (Hold to Mine) ---
            const manualBtn = document.getElementById('btn_manual_mine');
            
            manualBtn.addEventListener('mousedown', startManualHold);
            manualBtn.addEventListener('mouseup', stopManualHold);
            manualBtn.addEventListener('mouseleave', stopManualHold);
            
            // สำหรับมือถือ Touch Screen
            manualBtn.addEventListener('touchstart', (e) => { 
                e.preventDefault(); 
                startManualHold(); 
            }, {passive: false});
            manualBtn.addEventListener('touchend', stopManualHold);
        });

        // --- ฟังก์ชันคำนวณวันที่แบบอิงประวัติศาสตร์จริง ---
        function getEstimatedDate(currentBlock) {
            const epoch = Math.floor(currentBlock / HALVING_INTERVAL);
            const remainder = currentBlock % HALVING_INTERVAL;

            // ถ้าอยู่ในยุคที่เรามีหมุดหมายไว้ ให้ใช้การเฉลี่ยเวลาตามจริง
            if (epoch < MILESTONE_DATES.length - 1) {
                const startTime = MILESTONE_DATES[epoch];
                const endTime = MILESTONE_DATES[epoch + 1];
                const msDiff = endTime - startTime;
                const msPerBlockInEpoch = msDiff / HALVING_INTERVAL;
                return new Date(startTime + (remainder * msPerBlockInEpoch));
            } else {
                // อนาคตอันไกลโพ้น กลับไปใช้ 10 นาที/บล็อกเป๊ะๆ
                const lastKnownTime = MILESTONE_DATES[MILESTONE_DATES.length - 1];
                const blocksBeyond = currentBlock - ((MILESTONE_DATES.length - 1) * HALVING_INTERVAL);
                return new Date(lastKnownTime + (blocksBeyond * BLOCK_TIME_MS));
            }
        }

        // --- Chart.js Setup ---
        function initChart() {
            const ctx = document.getElementById('supplyChart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Total Bitcoin (BTC)',
                            data: [],
                            borderColor: '#00ff41',
                            backgroundColor: 'rgba(0, 255, 65, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Block Reward (BTC)',
                            data: [],
                            borderColor: '#ff0055',
                            borderWidth: 2,
                            stepped: true,
                            pointRadius: 0,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 0 },
                    plugins: {
                        legend: { display: false },
						tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { family: 'Prompt' },
                            bodyFont: { family: 'monospace' },
                            callbacks: {
                                label: function(context) {
                                    if(context.datasetIndex === 0) {
                                        return `Total: ${context.parsed.y.toLocaleString(undefined, {maximumFractionDigits: 2})} BTC`;
                                    } else {
                                        // บังคับทศนิยมสูงสุด 8 ตำแหน่ง เพื่อไม่ให้แสดงเป็น e-7
                                        const formattedReward = new Intl.NumberFormat('en-US', { maximumFractionDigits: 8 }).format(context.parsed.y);
                                        return `Reward: ${formattedReward} BTC`;
                                    }
                                },
                                // เพิ่ม afterBody เพื่อนำข้อมูล 2 เส้นมาคำนวณ S2F แล้วโชว์บรรทัดล่างสุด
                                afterBody: function(tooltipItems) {
                                    let totalBtc = 0;
                                    let rewardBtc = 0;
                                    
                                    // ดึงข้อมูล Total และ Reward ของจุดที่เมาส์ชี้อยู่
                                    tooltipItems.forEach(function(item) {
                                        if (item.datasetIndex === 0) totalBtc = item.parsed.y;
                                        if (item.datasetIndex === 1) rewardBtc = item.parsed.y;
                                    });
                                    
                                    const BLOCKS_PER_YEAR = 52560;
                                    const annualFlow = rewardBtc * BLOCKS_PER_YEAR;
                                    
                                    // คำนวณ S2F
                                    if (annualFlow > 0) {
                                        let s2fValue = totalBtc / annualFlow;
                                        return `S2F: ${s2fValue.toLocaleString('en-US', {maximumFractionDigits: 2})}`;
                                    } else if (totalBtc > 0) {
                                        return `S2F: ∞`; // เมื่อเหรียญหมด (ขุดครบ 21 ล้าน)
                                    } else {
                                        return `S2F: 0.00`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: '#333' }, ticks: { color: '#666', maxTicksLimit: 8 } },
                        y: { 
                            type: 'linear', display: true, position: 'left',
                            grid: { color: '#333' }, ticks: { color: '#00ff41', callback: val => (val/1000000) + 'M' },
                            max: 21000000, min: 0
                        },
                        y1: {
                            type: 'linear', display: true, position: 'right',
                            grid: { drawOnChartArea: false }, ticks: { color: '#ff0055' },
                            max: 55, min: 0
                        }
                    }
                }
            });
        }

        function updateChartData(yearStr, currentBtc, currentRewardBtc) {
            chartInstance.data.labels.push(yearStr);
            chartInstance.data.datasets[0].data.push(currentBtc);
            chartInstance.data.datasets[1].data.push(currentRewardBtc);
            chartInstance.update();
        }

        // --- Core Functions ---
        function formatBinary(bigIntVal) {
            return bigIntVal.toString(2).padStart(33, '0');
        }

        function renderBinaryBoxes(binaryString, isHalving) {
            const container = document.getElementById('binary_display');
            let html = '';
            for (let i = 0; i < binaryString.length; i++) {
                const bit = binaryString[i];
                const isOn = bit === '1';
                
                let boxClass = "w-4 h-6 md:w-5 md:h-7 flex items-center justify-center border text-xs font-bold ";
                
                if (isOn) {
                    boxClass += "text-[#F7931A] border-[#F7931A] bg-[#F7931A]/10 drop-shadow-[0_0_5px_rgba(247,147,26,0.8)]";
                } else {
                    boxClass += "text-gray-700 border-gray-800 bg-black/40";
                }

                if (isHalving) boxClass += " animate-shift"; 
                html += `<div class="${boxClass}">${bit}</div>`;
            }
            container.innerHTML = html;
        }

        function triggerHalvingEffect() {
            const box = document.getElementById('subsidy_box');
            const alert = document.getElementById('shift_alert');
            
            box.classList.remove('halving-flash');
            void box.offsetWidth;
            box.classList.add('halving-flash');
            
            alert.classList.remove('hidden');
            setTimeout(() => { alert.classList.add('hidden'); }, 3000);
        }

        function updateUI(forceRenderBinary = false) {
            let currentHalving = Math.floor(blockHeight / HALVING_INTERVAL);
            let currentSubsidy = 0n;
            
            if (currentHalving < 33) {
                currentSubsidy = INITIAL_SUBSIDY >> BigInt(currentHalving);
            }

            const estimatedDate = getEstimatedDate(blockHeight);
            
            document.getElementById('disp_block').innerText = blockHeight.toLocaleString('en-US');
            
            const btcNum = Number(totalSatoshi) / 100000000;
            const btcParts = btcNum.toFixed(8).split('.');
            const formattedBtc = parseInt(btcParts[0]).toLocaleString('en-US') + '.' + btcParts[1];
            
            document.getElementById('disp_btc').innerText = formattedBtc;
            document.getElementById('disp_sats').innerText = Number(totalSatoshi).toLocaleString('en-US') + " Sats";
            
            const dateStr = estimatedDate.toLocaleDateString('en-US', {month: 'short', year: 'numeric'});
            document.getElementById('disp_date').innerText = dateStr;
            
            document.getElementById('disp_epoch').innerText = currentHalving;
            document.getElementById('disp_shift_op').innerText = currentHalving >= 33 ? `(MAX)` : `(>> ${currentHalving})`;
			
			const subsidyBtc = Number(currentSubsidy) / 100000000;
            // บังคับทศนิยมสูงสุด 8 ตำแหน่ง
            const displaySubsidyBtc = new Intl.NumberFormat('en-US', { maximumFractionDigits: 8 }).format(subsidyBtc);
            document.getElementById('disp_subsidy_dec').innerHTML = `${Number(currentSubsidy).toLocaleString('en-US')} <span class="text-2xl md:text-3xl text-[#F7931A]">(${displaySubsidyBtc} BTC)</span>`;
			
            // คำนวณ Stock-to-Flow (S2F)
            let s2fValue = 0;
            const annualFlow = subsidyBtc * BLOCKS_PER_YEAR; 
            
            if (annualFlow > 0) {
                s2fValue = btcNum / annualFlow;
                document.getElementById('disp_s2f').innerText = s2fValue.toLocaleString('en-US', {maximumFractionDigits: 2});
            } else if (btcNum > 0) {
                document.getElementById('disp_s2f').innerHTML = '<i class="fa-solid fa-infinity"></i>';
            } else {
                document.getElementById('disp_s2f').innerText = "0.00";
            }

            // จัดการ UI แถบ Progress เมื่อเหรียญหมด
            if (currentHalving >= 33) {
                document.getElementById('halving_countdown').innerText = `Max Supply Reached (21M)`;
                document.getElementById('halving_countdown').classList.replace('text-gray-400', 'text-[#00ff41]');
                document.getElementById('halving_label').innerText = "สถานะ";
                document.getElementById('halving_progress').style.width = `100%`;
                document.getElementById('halving_progress').classList.replace('bg-[#ff0055]', 'bg-[#00ff41]');
            } else {
                const blocksInCurrentEpoch = blockHeight % HALVING_INTERVAL;
                const progressPercent = (blocksInCurrentEpoch / HALVING_INTERVAL) * 100;
                const blocksLeft = HALVING_INTERVAL - blocksInCurrentEpoch;
                
                document.getElementById('halving_countdown').innerText = `${blocksLeft.toLocaleString()} Blocks Left`;
                document.getElementById('halving_countdown').classList.replace('text-[#00ff41]', 'text-gray-400');
                document.getElementById('halving_label').innerText = "ความคืบหน้าสู่ Halving ถัดไป";
                document.getElementById('halving_progress').style.width = `${progressPercent}%`;
                document.getElementById('halving_progress').classList.replace('bg-[#00ff41]', 'bg-[#ff0055]');
            }

            // อัปเดตกราฟ
            if (blockHeight - lastChartUpdateBlock >= CHART_UPDATE_INTERVAL || blockHeight === 0 || currentHalving >= 33 && lastChartUpdateBlock !== blockHeight) {
                const rewardBtc = Number(currentSubsidy) / 100000000;
                const yearOnly = estimatedDate.getFullYear();
                updateChartData(yearOnly, btcNum, rewardBtc);
                lastChartUpdateBlock = blockHeight;
            }

            // อัปเดตกล่อง Binary
            if (forceRenderBinary || !window.lastHalvingRender || window.lastHalvingRender !== currentHalving) {
                const isHalvingHappened = window.lastHalvingRender !== undefined && window.lastHalvingRender < currentHalving;
                renderBinaryBoxes(formatBinary(currentSubsidy), isHalvingHappened);
                if (isHalvingHappened && currentHalving < 34) triggerHalvingEffect(); 
                
                window.lastHalvingRender = currentHalving;
            }
        }

        function mineBlocks(count) {
            let remaining = count;
            
            while (remaining > 0) {
                let currentHalving = Math.floor(blockHeight / HALVING_INTERVAL);
                
                // หากถึง Halving ที่ 33 ให้บล็อกเดินหน้า แต่ไม่ได้ Subsidy แล้ว
                if (currentHalving >= 33) {
                    blockHeight += remaining;
                    remaining = 0;
                } else {
                    let blocksToNextHalving = HALVING_INTERVAL - (blockHeight % HALVING_INTERVAL);
                    let blocksToMineNow = Math.min(remaining, blocksToNextHalving);
                    
                    let currentSubsidy = INITIAL_SUBSIDY >> BigInt(currentHalving);
                    
                    totalSatoshi += currentSubsidy * BigInt(blocksToMineNow);
                    blockHeight += blocksToMineNow;
                    remaining -= blocksToMineNow;

                    // 1. ทริกเกอร์หยุดออโต้เมื่อสุดทาง (Halving 33)
                    if (Math.floor(blockHeight / HALVING_INTERVAL) === 33 && !hasReachedEnd) {
                        hasReachedEnd = true;
                        if (isAutoMining) toggleAutoMine(); // ปิดปุ่มออโต้
                        stopManualHold(); // ปิดโหมดกดค้าง
                        updateUI(true);
                        return; // ออกจากฟังก์ชันทันที
                    }
                    
                    // 2. ทริกเกอร์หยุดพักชั่วคราวเมื่อเกิด Halving (ตามตั้งค่า Checkbox)
                    if (blockHeight % HALVING_INTERVAL === 0 && blockHeight < 33 * HALVING_INTERVAL) {
                        const stopOnHalving = document.getElementById('chk_stop_halving').checked;
                        if (stopOnHalving && (isAutoMining || holdInterval)) {
                            if (isAutoMining) toggleAutoMine();
                            stopManualHold();
                            updateUI(true);
                            return; // ออกจากลูปเพื่อให้ระบบหยุดพอดีแป๊ะๆ ที่จุดเริ่มต้น Halving ถัดไป
                        }
                    }
                }
            }
            updateUI();
        }

        // --- ระบบกดค้าง (Hold Logic) ---
        function startManualHold() {
            if(isAutoMining) return; 
            mineBlocks(1); 
            
            holdTimeout = setTimeout(() => {
                holdInterval = setInterval(() => {
                    const speedPerSec = parseInt(document.getElementById('sim_speed').value);
                    const blocksPerFrame = Math.ceil(speedPerSec / fps);
                    mineBlocks(blocksPerFrame);
                }, 1000 / fps);
            }, 300);
        }

        function stopManualHold() {
            if (holdTimeout) clearTimeout(holdTimeout);
            if (holdInterval) clearInterval(holdInterval);
        }

        // --- ระบบขุดอัตโนมัติ (Auto Logic) ---
        function toggleAutoMine() {
            const btn = document.getElementById('btn_auto');
            
            if (isAutoMining) {
                clearInterval(simInterval);
                isAutoMining = false;
                btn.innerHTML = '<i class="fa-solid fa-play"></i> ขุดอัตโนมัติต่อไป';
                btn.className = "col-span-2 btn-neon py-3 rounded text-sm shadow-[0_0_15px_rgba(247,147,26,0.5)] flex justify-center items-center gap-2";
            } else {
                isAutoMining = true;
                btn.innerHTML = '<i class="fa-solid fa-stop"></i> หยุดการจำลองชั่วคราว';
                btn.className = "col-span-2 border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-3 rounded text-sm transition font-bold flex justify-center items-center gap-2";
                
                simInterval = setInterval(() => {
                    const speedPerSec = parseInt(document.getElementById('sim_speed').value);
                    const blocksPerFrame = Math.ceil(speedPerSec / fps);
                    mineBlocks(blocksPerFrame);
                }, 1000 / fps);
            }
        }

        function resetSimulator() {
            if (isAutoMining) toggleAutoMine();
            stopManualHold();
            
            const btn = document.getElementById('btn_auto');
            btn.innerHTML = '<i class="fa-solid fa-play"></i> เริ่มขุดอัตโนมัติ';
            btn.className = "col-span-2 btn-neon py-3 rounded text-sm shadow-[0_0_15px_rgba(247,147,26,0.5)] flex justify-center items-center gap-2";
            
            blockHeight = 0;
            totalSatoshi = 0n;
            lastChartUpdateBlock = -1;
            hasReachedEnd = false;
            window.lastHalvingRender = undefined;
            
            chartInstance.data.labels = [];
            chartInstance.data.datasets[0].data = [];
            chartInstance.data.datasets[1].data = [];
            chartInstance.update();

            updateUI(true);
        }

    </script>
</body>
</html>