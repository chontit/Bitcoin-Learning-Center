<?php
// ดึงข้อมูลจริงจาก Bitcoin Network
function getInitialData() {
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $latest = @json_decode(file_get_contents("https://blockchain.info/latestblock", false, $ctx), true);
    $hash = @file_get_contents("https://blockchain.info/q/latesthash", false, $ctx);
    
    $zeros = 0;
    if($hash) {
        foreach(str_split($hash) as $c) {
            if($c === '0') $zeros++; else break;
        }
    }
    
    return [
        'height' => $latest['height'] ?? "Unknown",
        'hash' => $hash ?: "00000000000000000000a2d7c965e18cdb41ea696e379df89d67f4524adfebc2",
        'zeros' => $zeros ?: 19
    ];
}
$init = getInitialData();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

    <title>Bitcoin Mining Simulator — Chollatis Bitcoiner</title>

    <style>
        :root { 
            --bg-color: #050505;
            --card-bg: #111111;
            --btc-orange: #f2a900; 
            --btc-glow: rgba(242, 169, 0, 0.4);
            --neon-green: #00ff41;
            --neon-glow: rgba(0, 255, 65, 0.3);
            --text-main: #e0e0e0;
            --text-muted: #888;
            --border-color: #333;
        }

        body { 
            font-family: 'Chakra Petch', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 50% 0%, #1a1a1a 0%, var(--bg-color) 70%);
            color: var(--text-main);
            padding: 20px; 
            margin: 0; 
            min-height: 100vh;
        }

        .container { 
            max-width: 950px; 
            margin: auto; 
            background: var(--card-bg); 
            padding: 30px; 
            border-radius: 16px; 
            border: 1px solid #222;
            box-shadow: 0 0 40px rgba(0,0,0,0.8), 0 0 15px var(--btc-glow); 
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--btc-orange), transparent);
            box-shadow: 0 0 10px var(--btc-orange);
        }

        .header { 
            display: flex; 
            flex-direction: column; 
            border-bottom: 1px solid var(--border-color); 
            margin-bottom: 25px; 
            padding-bottom: 20px; 
        }
        @media (min-width: 600px) { 
            .header { flex-direction: row; justify-content: space-between; align-items: center; } 
        }

        h2 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(242, 169, 0, 0.3);
        }

        .btn-home {
            background: rgba(242, 169, 0, 0.1);
            color: var(--btc-orange);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid rgba(242, 169, 0, 0.3);
            font-family: 'JetBrains Mono', monospace;
        }
        .btn-home:hover {
            background: var(--btc-orange);
            color: #000;
            box-shadow: 0 0 15px var(--btc-glow);
        }

        .header-title-group { display: flex; align-items: center; gap: 15px; }

        .status-badge { 
            background: rgba(255, 255, 255, 0.05); 
            color: var(--btc-orange); 
            padding: 10px 15px; 
            border-radius: 6px; 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 0.8em; 
            border: 1px solid rgba(242, 169, 0, 0.2);
        }

        .realworld-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 25px;
        }
        @media(max-width:500px){ .realworld-strip { grid-template-columns: 1fr; } }
        .rw-card {
            background: #0d0d0d;
            border: 1px solid #1e1e1e;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            transition: all 0.3s;
        }
        .rw-card:hover { border-color: var(--btc-orange); box-shadow: 0 0 10px rgba(242,169,0,0.1); }
        .rw-card .rw-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.1rem; font-weight: 700;
            color: var(--btc-orange);
        }
        .rw-card .rw-lbl {
            font-family: 'Prompt', sans-serif;
            font-size: 0.75rem; color: #666; margin-top: 5px;
        }

        .field { margin-bottom: 20px; }
        
        label { 
            color: var(--btc-orange); 
            margin-bottom: 8px; 
            display: block; 
            font-size: 0.95em; 
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        input, textarea, select { 
            width: 100%; 
            padding: 14px; 
            background: #080808;
            border: 1px solid #333;
            color: var(--neon-green);
            border-radius: 6px; 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 14px; 
            box-sizing: border-box; 
            transition: all 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--btc-orange);
            box-shadow: 0 0 10px rgba(242, 169, 0, 0.2);
        }
        input[readonly] { color: #666; background: #0d0d0d; }

        .field-hint {
            font-family: 'Prompt', sans-serif;
            font-size: 0.8rem;
            color: #777;
            margin-top: 6px;
            padding-left: 8px;
            border-left: 2px solid #333;
        }

        .result-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 10px; flex-wrap: wrap; gap: 10px; }
        .stats-group { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
		
        .stat-tag { 
            font-family: 'JetBrains Mono', monospace; 
            font-weight: bold; 
            font-size: 0.85em; 
            background: #222; 
            padding: 6px 12px; 
            border-radius: 4px; 
            border: 1px solid #333; 
            color: #ccc; 
        }
        .hash-rate { color: #000; background: var(--btc-orange); border-color: var(--btc-orange); box-shadow: 0 0 10px var(--btc-glow); }
        .energy-tag { color: #fff; background: #c0392b; border-color: #c0392b; }

        .hash-box { 
            background: #000; 
            color: #444;
            padding: 25px; 
            border-radius: 8px; 
            word-break: break-all; 
            font-size: 1.1em; 
            text-align: center; 
            border: 1px solid #333; 
            font-family: 'JetBrains Mono', monospace; 
            min-height: 60px; 
            transition: all 0.3s;
        }
        .hash-box.success {
            color: var(--neon-green);
            border-color: var(--neon-green);
            background: rgba(0, 255, 65, 0.05);
            box-shadow: 0 0 20px var(--neon-glow);
            text-shadow: 0 0 5px var(--neon-green);
        }
        
        .raw-container { position: relative; display: none; margin-top: 15px; }
        .raw-data-box { 
            background: #0a0a0a; 
            border: 1px dashed #444; 
            padding: 15px 75px 15px 15px; 
            border-radius: 6px; 
            font-size: 0.8em; 
            color: #888; 
            white-space: pre-wrap; 
            word-break: break-all; 
            font-family: 'JetBrains Mono', monospace; 
        }
        
        .btn-copy { 
            position: absolute; top: 10px; right: 10px; 
            background: #333; color: #fff; border: none; 
            padding: 6px 12px; border-radius: 4px; 
            cursor: pointer; font-size: 11px; font-weight: bold;
            font-family: 'Chakra Petch', sans-serif;
            transition: 0.2s;
        }
        .btn-copy:hover { background: #555; }
        
        .history-box { margin-top: 30px; border: 1px solid #333; border-radius: 8px; overflow: hidden; background: #0f0f0f; }
        .history-header { background: #1a1a1a; color: var(--text-main); padding: 12px 15px; font-weight: bold; font-size: 0.95em; border-bottom: 1px solid #333; }
        
        .history-table { width: 100%; border-collapse: collapse; font-family: 'JetBrains Mono', monospace; font-size: 0.85em; }
        .history-table th { background: #111; padding: 12px; border-bottom: 1px solid #333; text-align: left; color: #888; font-weight: normal; }
        .history-table td { padding: 12px; border-bottom: 1px solid #222; color: #aaa; }

        .controls { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px; }
        
        button { 
            border: none; 
            padding: 18px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1.1em; 
            font-weight: 700; 
            font-family: 'Chakra Petch', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.2s; 
        }
        .btn-mine { 
            background: linear-gradient(135deg, #f2a900 0%, #e67e22 100%);
            color: #000; 
            box-shadow: 0 4px 15px rgba(242, 169, 0, 0.3);
        }
        .btn-mine:active { transform: scale(0.98); }
        .btn-mine:hover { box-shadow: 0 0 20px rgba(242, 169, 0, 0.6); }

        .btn-auto { 
            background: #2c3e50; 
            color: white; 
            border: 1px solid #34495e;
        }
        .btn-auto:hover { background: #34495e; border-color: #5d6d7e; }

        .win-text { 
            color: var(--neon-green); 
            text-align: center; 
            font-size: 1.5em; 
            margin: 20px 0; 
            font-weight: bold; 
            display: none; 
            text-shadow: 0 0 15px var(--neon-green);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0% { opacity:0.8; } 50% { opacity:1; } 100% { opacity:0.8; } }

        .note-box { 
            margin-top: 25px; 
            padding: 15px 20px; 
            background: rgba(242, 169, 0, 0.05); 
            border-radius: 8px; 
            color: #bbb; 
            border-left: 4px solid var(--btc-orange); 
            font-size: 0.9em; 
        }
        .note-box strong { color: var(--btc-orange); }

        /* Information Section Styles */
        .section-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 40px 0 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem; color: var(--btc-orange); letter-spacing: 1px;
            font-weight: bold;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; border-top: 1px solid #333;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } }

        .info-box {
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 8px;
            padding: 20px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.85rem;
            line-height: 1.7;
            color: #aaa;
        }
        .info-box.real { border-left: 4px solid #0dcaf0; }
        .info-box.sim { border-left: 4px solid var(--neon-green); }
        .info-box.warn { border-left: 4px solid #ff3333; margin-bottom: 0; }

        .info-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-box.real .info-title { color: #0dcaf0; }
        .info-box.sim .info-title { color: var(--neon-green); }
        .info-box.warn .info-title { color: #ff3333; }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.85em;
            color: #555;
            padding-top: 20px;
            border-top: 1px solid #222;
            font-family: 'JetBrains Mono', monospace;
        }
        .footer:hover { color: #777; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <div class="header-title-group">
            <h2 style="margin:0; color:var(--btc-orange);">₿ Mining Simulator</h2>
            <a href="/" class="btn-home">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                HOME
            </a>
        </div>
        <div class="status-badge">
            Network Sync: OK
        </div>
    </div>

    <!-- Network Stats Strip -->
    <div class="realworld-strip">
        <div class="rw-card">
            <div class="rw-val">#<?php echo number_format($init['height']); ?></div>
            <div class="rw-lbl">Latest Block</div>
        </div>
        <div class="rw-card">
            <div class="rw-val"><?php echo $init['zeros']; ?> Zeros</div>
            <div class="rw-lbl">Real Difficulty</div>
        </div>
        <div class="rw-card">
            <div class="rw-val">~700 EH/s</div>
            <div class="rw-lbl">Network Hashrate</div>
        </div>
    </div>

    <div class="field">
        <label>🔗 Previous Block Hash:</label>
        <input type="text" id="prev_hash" value="<?php echo $init['hash']; ?>" readonly>
        <div class="field-hint">แฮชของบล็อกก่อนหน้า (ระบบ Chain ที่ป้องกันการปลอมแปลงอดีต)</div>
    </div>

    <div class="field">
        <label>🎯 Difficulty Target:</label>
        <select id="diff_level" onchange="resetAll()">
            <?php for($i=1; $i<=10; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($i==1 ? 'selected' : ''); ?>>Level <?php echo $i; ?> — ต้องขึ้นต้นด้วย 0 จำนวน <?php echo $i; ?> ตัว</option>
            <?php endfor; ?>
            <option value="<?php echo $init['zeros']; ?>">🌐 Real Network (<?php echo $init['zeros']; ?> ศูนย์) — เป็นไปไม่ได้ด้วย CPU</option>
        </select>
        <div class="field-hint">ระดับความยาก — ยิ่งต้องการ 0 นำหน้ามาก ยิ่งต้องใช้พลังประมวลผลสูงขึ้น</div>
    </div>

    <div class="field">
        <label>📦 Block Data (Transactions):</label>
        <textarea id="tx_input" rows="6" oninput="resetAll()">Coinbase: 3.125 BTC + 0.1842 Fees
Tx1: Alice -> Bob 0.50 BTC
Tx2: Somchai -> Somsri 1.25 BTC
Tx3: Wallet_A -> Wallet_B 10.00 BTC
Message: "The Times <?php echo date('d/M/Y'); ?> Bitcoin is Freedom"
Timestamp: <?php echo date('Y-m-d H:i'); ?> (UTC)</textarea>
        <div class="field-hint">ข้อมูลในบล็อก หากเปลี่ยนแปลงแม้แต่ตัวอักษรเดียว Hash ที่ได้จะเปลี่ยนไปทั้งหมด (Avalanche Effect)</div>
    </div>

    <div class="field">
        <label>🎲 Nonce:</label>
        <input type="number" id="nonce" value="0">
        <div class="field-hint">Number Used Once — ตัวแปรเดียวที่นักขุดสุ่มเปลี่ยนค่าไปเรื่อยๆ เพื่อหาแฮชที่เป้าหมายกำหนด</div>
    </div>

    <div class="controls">
        <button class="btn-mine" onclick="handleManualMine()">⚒️ MINE ทีละครั้ง</button>
        <button id="btn-auto" class="btn-auto" onclick="toggleAuto()">🚀 AUTO MINE</button>
    </div>

    <div class="field" style="margin-top:30px;">
        <div class="result-header">
            <label style="margin:0;">SHA-256 Hash Result:</label>
            <div class="stats-group">
                <div id="hash_rate" class="stat-tag hash-rate">0 H/s</div>
                <div id="energy_cost" class="stat-tag energy-tag">฿ 0.0000</div>
                <div id="timer" class="stat-tag">00:00:00</div>
            </div>
        </div>
        
        <div id="hash_box" class="hash-box">WAITING TO MINE...</div>

        <div class="raw-container" id="raw_container">
            <div style="font-size: 0.75em; color: #555; margin-bottom: 5px;">📄 RAW DATA INPUT</div>
            <div id="raw_data" class="raw-data-box"></div>
            <button class="btn-copy" onclick="copyRawData()">📋 Copy</button>
        </div>

        <div id="win_msg" class="win-text">🎉 Block Mined Successfully! Nonce พบแล้ว! 🎉</div>
    </div>

    <div class="history-box">
        <div class="history-header">📜 MINING LOGS</div>
        <table class="history-table">
            <thead>
                <tr>
                    <th style="width:15%;">Level</th>
                    <th style="width:20%;">Nonce</th>
                    <th style="width:35%;">Hash Output</th>
                    <th style="width:30%;">Time / Cost</th>
                </tr>
            </thead>
            <tbody id="history_list">
                <tr><td colspan="4" style="text-align:center; color:#444; padding:20px;">ยังไม่มีประวัติ — กด MINE หรือ AUTO MINE เพื่อเริ่มต้น</td></tr>
            </tbody>
        </table>
    </div>

    <div id="note_box" class="note-box"></div>

    <!-- Information Section -->
    <div class="section-divider">BITCOIN MINING — ความเป็นจริง vs. Simulator</div>

    <div class="info-grid">
        <div class="info-box real">
            <div class="info-title">🌐 ของจริงในโลก</div>
            <ul style="margin:0; padding-left:20px;">
                <li>Network Hash Rate ≈ <strong style="color:#e0e0e0;">700 Exahash/s</strong> (7×10²⁰ Hash/วิ)</li>
                <li>ต้องการ 0 นำหน้า <strong style="color:#ff6666;"><?php echo $init['zeros']; ?> ตัว</strong> ในปัจจุบัน</li>
                <li>Block ใหม่ทุก <strong style="color:#e0e0e0;">~10 นาที</strong></li>
                <li>รางวัล <strong style="color:var(--btc-orange);">3.125 BTC</strong> ต่อ Block</li>
                <li>ใช้ฮาร์ดแวร์ ASIC เฉพาะทางในการขุด ไม่ใช่ CPU</li>
            </ul>
        </div>
        <div class="info-box sim">
            <div class="info-title">🖥️ Simulator นี้</div>
            <ul style="margin:0; padding-left:20px;">
                <li>ใช้สมการ SHA-256 จริง 100% Hash ทุกตัวพิสูจน์ได้</li>
                <li>Level 1–10 เป็นเป้าหมายจำลองเพื่อให้ CPU คอมพิวเตอร์ทำได้</li>
                <li>Manual Mine ขุดทีละค่าเพื่อดูความเปลี่ยนแปลง</li>
                <li>Auto Mine ประมวลผลทีละหลายค่าเพื่อความเร็ว</li>
                <li><strong style="color:var(--neon-green);">ลอง Level 4–5</strong> เพื่อสัมผัสความยากในการค้นหา</li>
            </ul>
        </div>
    </div>

    <div class="info-box warn">
        <div class="info-title">🔍 Proof of Work — ทำไมถึงสำคัญกับ Bitcoin?</div>
        PoW ไม่ใช่แค่ "เกมทายตัวเลข" แต่มันคือ <strong>กลไกสร้างฉันทามติ (Consensus)</strong>: ใครก็ตามที่อยากเปลี่ยนแปลงประวัติของ Bitcoin ต้องมีพลังประมวลผลมากกว่า 50% ของเครือข่ายทั้งหมด (<strong>51% Attack</strong>) ซึ่งต้องใช้ต้นทุนและพลังงานมหาศาลจนเป็นไปแทบไม่ได้ — นี่คือ <strong style="color:var(--neon-green);">Game Theory ที่ทำให้เครือข่ายปลอดภัย</strong> โดยไม่ต้องพึ่งพาตัวกลางใดๆ
    </div>

    <div class="footer">
        © 2026 Chollatis Bitcoiner | Don't Trust, Verify.
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
let isAuto = false;
let hasWon = false; 
let autoInterval, timerInterval;
let startTime = null; 
let elapsedBeforePause = 0; 
let totalHashes = 0;
const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

function playWinSound() {
    try {
        const osc = audioCtx.createOscillator();
        const g = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(500, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(1200, audioCtx.currentTime + 0.1);
        g.gain.setValueAtTime(0.2, audioCtx.currentTime);
        g.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.5);
        osc.connect(g); g.connect(audioCtx.destination);
        osc.start(); osc.stop(audioCtx.currentTime + 0.5);
    } catch(e) {}
}

function updateStatsUI() {
    const now = new Date().getTime();
    const currentDiff = now - startTime;
    const totalDiff = currentDiff + elapsedBeforePause;
    
    const h = Math.floor(totalDiff / 3600000).toString().padStart(2, '0');
    const m = Math.floor((totalDiff % 3600000) / 60000).toString().padStart(2, '0');
    const s = Math.floor((totalDiff % 60000) / 1000).toString().padStart(2, '0');
    document.getElementById('timer').innerText = `${h}:${m}:${s}`;
    
    const hps = Math.round(totalHashes / (currentDiff / 1000));
    document.getElementById('hash_rate').innerText = (isNaN(hps) || hps === Infinity ? 0 : hps).toLocaleString() + " H/s";
    
    const kwh = (100 / 1000) * (totalDiff / 3600000);
    const cost = kwh * 5;
    document.getElementById('energy_cost').innerText = "฿ " + cost.toFixed(4);
}

function addHistory(hash, time, nonce, cost) {
    const list = document.getElementById('history_list');
    if (list.innerText.includes("ยังไม่มีประวัติ") || list.innerText.includes("No logs")) list.innerHTML = "";
    
    const diffVal = document.getElementById('diff_level').value;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td style="font-weight:bold; color:var(--btc-orange);">Lvl ${diffVal}</td>
        <td>${nonce.toLocaleString()}</td>
        <td style="color:var(--neon-green); font-weight:bold;">${hash.substring(0,12)}...</td>
        <td><span class="stat-tag" style="background:#222; border:1px solid #444;">${time}</span> <span class="stat-tag" style="background:rgba(231, 76, 60, 0.2); color:#e74c3c; border:none;">${cost}</span></td>
    `;
    list.prepend(row);
}

function resetStatsOnly() {
    elapsedBeforePause = 0;
    totalHashes = 0;
    hasWon = false;
    document.getElementById('timer').innerText = "00:00:00";
    document.getElementById('energy_cost').innerText = "฿ 0.0000";
    document.getElementById('hash_rate').innerText = "0 H/s";
    document.getElementById('hash_box').classList.remove('success');
    document.getElementById('win_msg').style.display = 'none';
}

function resetAll() {
    stopAuto();
    resetStatsOnly();
    document.getElementById('nonce').value = 0;
    document.getElementById('hash_box').innerText = "WAITING TO MINE...";
    document.getElementById('hash_box').style.color = "#444";
    
    const d = parseInt(document.getElementById('diff_level').value);
    const totalPossibilities = Math.pow(16, d);
    let note = "";
    if (totalPossibilities < 1000000) {
        note = `<strong>Probability:</strong> โอกาสสำเร็จ <strong>1 ใน ${totalPossibilities.toLocaleString()}</strong> เทียบเท่าถูก <strong>"เลขท้าย 3 ตัว"</strong> ประมาณ <strong>${(totalPossibilities/1000).toFixed(2)} ครั้งติดต่อกัน</strong>`;
    } else {
        note = `<strong>Probability:</strong> โอกาสสำเร็จ <strong>1 ใน ${totalPossibilities.toLocaleString()}</strong> เทียบเท่าถูก <strong>"รางวัลที่ 1"</strong> ประมาณ <strong>${(Math.log10(totalPossibilities)/6).toFixed(2)} ครั้งติดต่อกัน</strong>`;
    }
    document.getElementById('note_box').innerHTML = note;
}

function runMining(count = 1) {
    const prevHash = document.getElementById('prev_hash').value;
    const data = document.getElementById('tx_input').value;
    const target = "0".repeat(parseInt(document.getElementById('diff_level').value));
    let nonce = parseInt(document.getElementById('nonce').value);

    document.getElementById('raw_container').style.display = 'block';

    for(let i=0; i<count; i++) {
        nonce++;
        totalHashes++;
        const currentInput = prevHash + "\n" + data + "\n" + nonce;
        const hash = CryptoJS.SHA256(currentInput).toString();
        
        if (hash.startsWith(target)) {
            hasWon = true; 
            updateUI(hash, true);
            document.getElementById('raw_data').innerText = currentInput;
            playWinSound();
            addHistory(hash, document.getElementById('timer').innerText, nonce, document.getElementById('energy_cost').innerText);
            document.getElementById('nonce').value = nonce;
            return true;
        }
        
        if (i === count - 1) {
            updateUI(hash, false);
            if (!isAuto || totalHashes % 100 === 0) document.getElementById('raw_data').innerText = currentInput;
        }
    }
    document.getElementById('nonce').value = nonce;
    return false;
}

function updateUI(hash, isWin) {
    const box = document.getElementById('hash_box');
    const win = document.getElementById('win_msg');
    box.innerText = hash;
    if (isWin) { 
        box.classList.add('success'); 
        box.style.color = ""; 
        win.style.display = 'block'; 
    } else { 
        box.classList.remove('success'); 
        box.style.color = "#444"; 
        win.style.display = 'none'; 
    }
}

function toggleAuto() {
    if (audioCtx.state === 'suspended') audioCtx.resume();
    if (!isAuto) {
        if (hasWon) resetStatsOnly();
        isAuto = true;
        const btn = document.getElementById('btn-auto');
        btn.innerText = "🛑 STOP MINING"; 
        btn.style.background = "#c0392b";
        btn.style.borderColor = "#e74c3c";
        totalHashes = 0;
        startTime = new Date().getTime();
        timerInterval = setInterval(updateStatsUI, 1000);
        autoInterval = setInterval(() => { if (runMining(400)) stopAuto(); }, 10);
    } else {
        const now = new Date().getTime();
        if(startTime) elapsedBeforePause += (now - startTime);
        stopAuto();
    }
}

function stopAuto() {
    isAuto = false;
    clearInterval(autoInterval);
    clearInterval(timerInterval);
    const btn = document.getElementById('btn-auto');
    btn.innerText = "🚀 AUTO MINE";
    btn.style.background = "";
    btn.style.borderColor = "";
}

function handleManualMine() {
    if (audioCtx.state === 'suspended') audioCtx.resume();
    if (isAuto) stopAuto();
    if (hasWon) {
        resetStatsOnly();
        startTime = new Date().getTime(); 
    } else if (startTime === null) {
        startTime = new Date().getTime();
    }
    runMining(1);
    updateStatsUI();
}

function copyRawData() {
    const text = document.getElementById('raw_data').innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector('.btn-copy');
        btn.innerText = "✅ Copied";
        setTimeout(() => btn.innerText = "📋 Copy", 2000);
    });
}

resetAll();
</script>
</body>
</html>