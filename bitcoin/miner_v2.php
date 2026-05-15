<?php
// ดึงข้อมูลจริงจาก Bitcoin Network (Logic เดิม ไม่เปลี่ยนแปลง)
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

    <title>Bitcoin Mining Simulator — Learning Chontit</title>

    <style>
        :root { 
            --bg-color: #050505;
            --card-bg: #111111;
            --btc-orange: #f2a900; 
            --btc-glow: rgba(242, 169, 0, 0.4);
            --neon-green: #00ff41;
            --text-main: #e0e0e0;
            --text-muted: #888;
            --border-color: #333;

            /* NEW: Education palette */
            --edu-green-bg:   #0d1f12;
            --edu-green-bdr:  rgba(0,255,65,0.25);
            --edu-orange-bg:  #1a0d00;
            --edu-orange-bdr: rgba(242,169,0,0.3);
            --edu-blue-bg:    #0a1628;
            --edu-blue-bdr:   rgba(13,202,240,0.25);
            --edu-red-bg:     #1a0000;
            --edu-red-bdr:    rgba(255,51,51,0.3);
            --edu-blue-accent:#0dcaf0;
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
            box-shadow: 0 0 40px rgba(0,0,0,0.7), 0 0 10px var(--btc-glow); 
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

        .status-badge { 
            background: rgba(255, 255, 255, 0.05); 
            color: var(--btc-orange); 
            padding: 10px 15px; 
            border-radius: 6px; 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 0.8em; 
            border: 1px solid rgba(242, 169, 0, 0.2);
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
            color: #00ff41;
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

        .btn-home-inline {
            background: rgba(242, 169, 0, 0.1);
            color: var(--btc-orange);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: 1px solid rgba(242, 169, 0, 0.3);
            height: fit-content;
        }
        .btn-home-inline:hover { background: var(--btc-orange); color: #000; box-shadow: 0 0 15px var(--btc-glow); }

        .header-title-group { display: flex; align-items: center; gap: 15px; }

        .hash-box { 
            background: #000; 
            color: #333;
            padding: 25px; 
            border-radius: 8px; 
            word-break: break-all; 
            font-size: 1.1em; 
            text-align: center; 
            border: 1px solid #333; 
            font-family: 'JetBrains Mono', monospace; 
            min-height: 60px; 
            transition: all 0.3s;
            position: relative;
        }
        .hash-box.success {
            color: var(--neon-green);
            border-color: var(--neon-green);
            background: rgba(0, 255, 65, 0.05);
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.2);
            text-shadow: 0 0 5px var(--neon-green);
        }
        
        .raw-container { position: relative; display: none; margin-top: 15px; }
        .raw-label { font-size: 0.75em; font-weight: bold; text-transform: uppercase; color: #555; margin-top: 20px; display: none; letter-spacing: 1px; }
        
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
            line-height: 1.5; 
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
            position: relative;
            overflow: hidden;
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
            padding: 20px; 
            background: rgba(242, 169, 0, 0.05); 
            border-radius: 8px; 
            color: #bbb; 
            border-left: 4px solid var(--btc-orange); 
            font-size: 0.9em; 
            line-height: 1.6; 
        }
        .note-box strong { color: var(--btc-orange); }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.85em;
            color: #555;
            padding-top: 20px;
            border-top: 1px solid #222;
            font-family: 'JetBrains Mono', monospace;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        .footer:hover { opacity: 1; color: #777; }

        /* ================================================================
           NEW: Educational Component Styles
           ================================================================ */

        /* Concept Box — สีเขียว (แนวคิด / คำอธิบายหลัก) */
        .edu-concept {
            background: var(--edu-green-bg);
            border: 1px solid var(--edu-green-bdr);
            border-left: 4px solid var(--neon-green);
            border-radius: 0 8px 8px 0;
            padding: 14px 16px;
            margin-bottom: 16px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.88rem;
            color: #c9d1d9;
            line-height: 1.7;
        }
        .edu-concept .edu-title {
            color: var(--neon-green);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 9px;
            display: flex; align-items: center; gap: 6px;
        }

        /* Analogy Box — สีส้ม Bitcoin (อุปมาอุปไมย) */
        .edu-analogy {
            background: var(--edu-orange-bg);
            border: 1px solid var(--edu-orange-bdr);
            border-left: 4px solid var(--btc-orange);
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 14px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.85rem;
            color: #e0c9a0;
            line-height: 1.65;
        }
        .edu-analogy .edu-title { color: var(--btc-orange); font-weight: 700; font-size: 0.76rem; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 7px; }

        /* Deep Info Box — สีน้ำเงิน (เจาะลึก / ข้อมูลทางเทคนิค) */
        .edu-deep {
            background: var(--edu-blue-bg);
            border: 1px solid var(--edu-blue-bdr);
            border-left: 4px solid var(--edu-blue-accent);
            border-radius: 0 8px 8px 0;
            padding: 13px 16px;
            margin-bottom: 14px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.85rem;
            color: #a0b4c8;
            line-height: 1.65;
        }
        .edu-deep .edu-title { color: var(--edu-blue-accent); font-weight: 700; font-size: 0.76rem; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 7px; }

        /* Warning Box — สีแดง (ข้อควรรู้ / ความเป็นจริง) */
        .edu-warning {
            background: var(--edu-red-bg);
            border: 1px solid var(--edu-red-bdr);
            border-left: 4px solid #ff3333;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 14px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.84rem;
            color: #ffbaba;
            line-height: 1.65;
        }
        .edu-warning .edu-title { color: #ff3333; font-weight: 700; font-size: 0.76rem; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 7px; }

        /* Field-level hint (เล็กกว่า edu-concept) */
        .field-hint {
            font-family: 'Prompt', sans-serif;
            font-size: 0.78rem;
            color: #666;
            margin-top: 6px;
            padding: 7px 11px;
            background: rgba(255,255,255,0.02);
            border-radius: 5px;
            border-left: 3px solid #2a2a2a;
            line-height: 1.6;
        }
        .field-hint strong { color: #999; }
        .field-hint .hl-orange { color: var(--btc-orange); font-weight: 600; }
        .field-hint .hl-green  { color: var(--neon-green); font-weight: 600; }
        .field-hint .hl-blue   { color: var(--edu-blue-accent); font-weight: 600; }
        .field-hint .hl-red    { color: #ff6666; font-weight: 600; }

        /* Section divider with label */
        .section-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0 18px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem; color: #444; letter-spacing: 1px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; border-top: 1px solid #1e1e1e;
        }

        /* Realworld stats strip */
        .realworld-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }
        @media(max-width:500px){ .realworld-strip { grid-template-columns: 1fr; } }
        .rw-card {
            background: #0d0d0d;
            border: 1px solid #1e1e1e;
            border-radius: 7px;
            padding: 10px 12px;
            text-align: center;
        }
        .rw-card .rw-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem; font-weight: 700;
            color: var(--btc-orange);
        }
        .rw-card .rw-lbl {
            font-family: 'Prompt', sans-serif;
            font-size: 0.7rem; color: #555; margin-top: 3px;
        }

        /* Inline code tag */
        .tag {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid #333;
            border-radius: 4px;
            padding: 1px 7px;
            color: #aaa;
        }

        /* Hash anatomy highlight */
        .hash-anatomy {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            line-height: 1.8;
            margin-top: 8px;
        }
        .ha-zero { color: var(--neon-green); font-weight: 700; }
        .ha-rest { color: #555; }

        /* Step flow strip */
        .step-flow {
            display: flex; align-items: center; flex-wrap: wrap;
            gap: 4px; margin: 10px 0 14px;
            font-family: 'Prompt', sans-serif; font-size: 0.8rem;
        }
        .sf-box {
            padding: 5px 11px; border-radius: 20px;
            background: #1a1a1a; border: 1px solid #2a2a2a; color: #aaa;
        }
        .sf-box.active { background: rgba(242,169,0,0.12); border-color: rgba(242,169,0,0.4); color: var(--btc-orange); }
        .sf-arr { color: #333; font-size: 0.8rem; }

        /* Diff level explanation table */
        .diff-table { width: 100%; border-collapse: collapse; font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; margin-top: 8px; }
        .diff-table th { color: #555; border-bottom: 1px solid #1e1e1e; padding: 6px 8px; text-align: left; font-weight: normal; font-family: 'Prompt', sans-serif; }
        .diff-table td { padding: 5px 8px; border-bottom: 1px solid #111; color: #777; }
        .diff-table td:first-child { color: var(--btc-orange); font-weight: 700; }
        .diff-table tr.current-net td { color: #ff6666; }
        .diff-table tr.current-net td:first-child { color: #ff3333; }

        /* Intro hero strip */
        .intro-hero {
            background: linear-gradient(135deg, rgba(242,169,0,0.06), rgba(0,255,65,0.03));
            border: 1px solid rgba(242,169,0,0.15);
            border-radius: 12px;
            padding: 20px 22px;
            margin-bottom: 24px;
        }
        .intro-hero h3 {
            font-family: 'Chakra Petch', sans-serif;
            color: var(--btc-orange);
            font-size: 1rem;
            margin: 0 0 10px;
            letter-spacing: 1px;
        }
        .intro-hero .flow-icons {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8rem; flex-wrap: wrap; margin-top: 12px;
        }
        .flow-icon-box {
            text-align: center; min-width: 64px;
        }
        .flow-icon-box .icon { font-size: 1.3rem; }
        .flow-icon-box .lbl { font-family: 'Prompt', sans-serif; font-size: 0.68rem; color: #666; margin-top: 3px; }
        .flow-arr { color: #2a2a2a; font-size: 1.1rem; }

        /* ── Collapsible edu boxes ── */
        .edu-title, .edu-title-static {
            user-select: none;
            -webkit-user-select: none;
        }
        .edu-title-static {
            /* same visual as edu-title but non-interactive */
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 9px;
            display: flex; align-items: center; gap: 6px;
        }
        .edu-concept .edu-title-static  { color: var(--neon-green); }
        .edu-deep    .edu-title-static  { color: var(--edu-blue-accent); }
        .edu-warning .edu-title-static  { color: #ff3333; }
        .edu-analogy .edu-title-static  { color: var(--btc-orange); }
        .edu-title {
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
        }
        .edu-title::after {
            content: ' ▲';
            font-size: 0.65rem;
            opacity: 0.5;
            margin-left: auto;
            transition: transform 0.25s ease;
            display: inline-block;
        }
        .edu-title.collapsed::after {
            transform: rotate(180deg);
        }
        .edu-body {
            overflow: hidden;
            max-height: 2000px;
            transition: max-height 0.35s ease, opacity 0.3s ease;
            opacity: 1;
        }
        .edu-body.collapsed {
            max-height: 0;
            opacity: 0;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- ════════════════════════════════════════
         HEADER
    ════════════════════════════════════════ -->
    <div class="header">
        <div class="header-title-group">
            <h2 style="margin:0; color:var(--btc-orange);">₿ Mining Simulator</h2>
            <a href="/" class="btn-home-inline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                HOME
            </a>
        </div>
        <div class="status-badge">
            Height: #<?php echo $init['height']; ?> &nbsp;|&nbsp; Network Zeros: <?php echo $init['zeros']; ?>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         NEW: INTRO HERO — Bitcoin Mining คืออะไร?
    ════════════════════════════════════════ -->
    <div class="intro-hero">
        <h3>⚡ Bitcoin Mining คืออะไร?</h3>
        <p style="font-family:'Prompt',sans-serif; font-size:0.88rem; color:#a0b0a0; margin:0; line-height:1.75;">
            การขุด Bitcoin คือการแข่งขันระดับโลกที่นักขุดทั่วโลก <strong style="color:#e0e0e0;">แข่งกันสุ่มตัวเลข (Nonce)</strong> อย่างบ้าคลั่ง เพื่อหาค่าที่ทำให้ <strong style="color:var(--btc-orange);">SHA-256 Hash ขึ้นต้นด้วยเลข 0 ให้ครบตามเป้าหมาย</strong> ใครหาเจอก่อน = ได้รางวัล Block = ได้ Bitcoin ฟรี ระบบนี้เรียกว่า <strong style="color:var(--neon-green);">Proof of Work (PoW)</strong> — พิสูจน์ด้วยงานจริง ไม่ใช่เงินหรืออำนาจ
        </p>
        <div class="flow-icons">
            <div class="flow-icon-box"><div class="icon">📦</div><div class="lbl">รวม Tx ใน Block</div></div>
            <div class="flow-arr">›</div>
            <div class="flow-icon-box"><div class="icon">🔢</div><div class="lbl">ใส่ Nonce สุ่ม</div></div>
            <div class="flow-arr">›</div>
            <div class="flow-icon-box"><div class="icon">#️⃣</div><div class="lbl">SHA-256 Hash</div></div>
            <div class="flow-arr">›</div>
            <div class="flow-icon-box"><div class="icon" style="color:var(--neon-green);">0000…</div><div class="lbl">ตรงเป้า? ✅</div></div>
            <div class="flow-arr">›</div>
            <div class="flow-icon-box"><div class="icon">₿</div><div class="lbl">รับ Block Reward</div></div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         NEW: REAL NETWORK STATS STRIP
    ════════════════════════════════════════ -->
    <div class="realworld-strip">
        <div class="rw-card">
            <div class="rw-val">#<?php echo number_format($init['height']); ?></div>
            <div class="rw-lbl">Block ล่าสุดบน Network</div>
        </div>
        <div class="rw-card">
            <div class="rw-val"><?php echo $init['zeros']; ?> ศูนย์</div>
            <div class="rw-lbl">Target Difficulty ของจริง</div>
        </div>
        <div class="rw-card">
            <div class="rw-val">~700 EH/s</div>
            <div class="rw-lbl">Hash Rate เครือข่ายรวม</div>
        </div>
    </div>

    <!-- ════════════════════════════════════════
         FIELD 1: Previous Hash
    ════════════════════════════════════════ -->
    <div class="field">
        <label>🔗 Previous Block Hash (แฮชของบล็อกก่อนหน้า):</label>
        <input type="text" id="prev_hash" value="<?php echo $init['hash']; ?>" readonly>

        <!-- NEW: Concept -->
        <div class="edu-concept" style="margin-top:10px;">
            <div class="edu-title">📖 แนวคิด — ทำไมต้องมี Previous Hash?</div>
            <p style="margin:0 0 7px;">ทุก Block จะต้องนำ <strong style="color:#e0e0e0;">Hash ของ Block ก่อนหน้า</strong> ใส่เข้าไปในสมการด้วย นี่คือกลไกที่ทำให้ Bitcoin กลายเป็น <strong style="color:var(--neon-green);">"โซ่" (Chain)</strong> — ถ้าใครอยากแก้ไข Block เก่า ก็ต้องคำนวณ Hash ของ Block นั้นใหม่ และ Block ทุก Block ที่อยู่ต่อจากนั้นทั้งหมดด้วย ซึ่งต้องใช้พลังงานมหาศาลจนเป็นไปไม่ได้จริงๆ</p>
            <p style="margin:0; font-size:0.82rem; color:#7a9070;">ค่าที่แสดงนี้คือ Hash ของ Block #<?php echo number_format($init['height']); ?> จาก Bitcoin Network จริง ดึงมาสดๆ ตอนโหลดหน้า</p>
        </div>
        <!-- NEW: Analogy -->
        <div class="edu-analogy">
            <div class="edu-title">🎭 เปรียบเทียบ</div>
            เหมือนการเขียน <strong>บันทึกประจำวัน</strong> ที่ทุกหน้าต้องสรุปเนื้อหาหน้าก่อนหน้าไว้ด้วย — ถ้าใครแอบแก้หน้าที่ 5 จะทำให้หน้าที่ 6–7–8… ไม่ตรงกันทันที เพื่อนทุกคนในเครือข่ายตรวจสอบซึ่งกันและกันได้ทันที
        </div>
    </div>

    <!-- ════════════════════════════════════════
         FIELD 2: Difficulty
    ════════════════════════════════════════ -->
    <div class="field">
        <label>🎯 Difficulty Target (ระดับความยาก — จำนวน 0 นำหน้า Hash):</label>
        <select id="diff_level" onchange="resetAll()">
            <?php for($i=1; $i<=10; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($i==1 ? 'selected' : ''); ?>>
                    Level <?php echo $i; ?> — ต้องขึ้นต้นด้วย <?php echo str_repeat('0', $i); echo ($i < 4 ? ' (ง่ายมาก)' : ($i < 7 ? ' (พอทำได้)' : ' (ยาก)')); ?>
                </option>
            <?php endfor; ?>
            <option value="<?php echo $init['zeros']; ?>">
                🌐 Real Network (<?php echo $init['zeros']; ?> ศูนย์) — เป็นไปไม่ได้ด้วย CPU
            </option>
        </select>

        <!-- NEW: Concept -->
        <div class="edu-concept" style="margin-top:10px;">
            <div class="edu-title">📖 แนวคิด — Difficulty Target ทำงานอย่างไร?</div>
            <p style="margin:0 0 8px;">SHA-256 ให้ผลลัพธ์เป็นตัวเลขฐาน 16 ยาว 64 ตัว (256 bits) <strong style="color:#e0e0e0;">ผลลัพธ์ที่จะ "ผ่าน" ต้องขึ้นต้นด้วยเลข 0 ให้ครบตามระดับที่กำหนด</strong> — ยิ่งต้องการ 0 มาก โอกาสที่จะได้ยิ่งน้อย ยิ่งต้องสุ่ม Nonce มากขึ้น</p>
            <table class="diff-table">
                <thead><tr><th>Level</th><th>Target Prefix</th><th>โอกาสสำเร็จต่อ 1 Hash</th><th>ระยะเวลาโดยประมาณ (CPU)</th></tr></thead>
                <tbody>
                    <tr><td>1</td><td class="ha-zero">0</td><td>1 ใน 16</td><td>≈ ทันที</td></tr>
                    <tr><td>2</td><td><span class="ha-zero">00</span></td><td>1 ใน 256</td><td>≈ วินาที</td></tr>
                    <tr><td>4</td><td><span class="ha-zero">0000</span></td><td>1 ใน 65,536</td><td>≈ นาที</td></tr>
                    <tr><td>6</td><td><span class="ha-zero">000000</span></td><td>1 ใน 16 ล้าน</td><td>≈ ชั่วโมง</td></tr>
                    <tr><td>8</td><td><span class="ha-zero">00000000</span></td><td>1 ใน 4 พันล้าน</td><td>≈ หลายวัน (CPU)</td></tr>
                    <tr class="current-net"><td><?php echo $init['zeros']; ?> 🌐</td><td><span style="color:#ff4444;"><?php echo str_repeat('0',$init['zeros']); ?>…</span></td><td>1 ใน 10<sup><?php echo $init['zeros']; ?></sup></td><td>ต้องใช้ ASIC หลายหมื่นเครื่อง</td></tr>
                </tbody>
            </table>
        </div>
        <!-- NEW: Deep Info -->
        <div class="edu-deep">
            <div class="edu-title">🔬 เจาะลึก — ทำไม Difficulty ถึงปรับตัวเอง?</div>
            Bitcoin Network ออกแบบให้ Block ใหม่เกิดขึ้น <strong style="color:#e0e0e0;">ทุกๆ ~10 นาที</strong> ไม่ว่าจะมีคนขุดมากหรือน้อย — ทุก 2,016 บล็อก (~2 สัปดาห์) ระบบจะวัดว่า Block เกิดเร็วไปหรือช้าไป แล้วปรับ Difficulty โดยอัตโนมัติ: คนขุดเพิ่ม → Difficulty สูงขึ้น, คนขุดลด → Difficulty ลดลง กลไกนี้เรียกว่า <strong style="color:var(--edu-blue-accent);">Difficulty Adjustment</strong> และไม่มีใครควบคุมได้ — Code ตัดสิน
        </div>
        <div class="field-hint" id="diff_hint">
            <span class="hl-orange">แนะนำ:</span> เริ่มที่ Level 1–3 เพื่อดูว่า Simulator ทำงานอย่างไร จากนั้นลอง Level 5–6 เพื่อสัมผัสว่าการรอนั้นนานแค่ไหน
        </div>
    </div>

    <!-- ════════════════════════════════════════
         FIELD 3: Block Data / Transactions
    ════════════════════════════════════════ -->
    <div class="field">
        <label>📦 Block Data (ข้อมูลภายใน Block — ธุรกรรม + Metadata):</label>
        <textarea id="tx_input" rows="7" oninput="resetAll()">Coinbase: 3.125 BTC + 0.1842 Fees
Tx1: Alice -> Bob 0.50 BTC
Tx2: Somchai -> Somsri 1.25 BTC
Tx3: Wallet_A -> Wallet_B 10.00 BTC
Message: "The Times <?php echo date('d/M/Y'); ?> Bitcoin is Freedom"
Timestamp: <?php echo date('Y-m-d H:i'); ?> (UTC)</textarea>

        <!-- NEW: Concept -->
        <div class="edu-concept" style="margin-top:10px;">
            <div class="edu-title">📖 แนวคิด — ข้อมูลใน Block มีอะไรบ้าง?</div>
            <p style="margin:0 0 8px;">ข้อมูลทั้งหมดข้างบนจะถูก <strong style="color:#e0e0e0;">นำไป Hash รวมกันเป็นก้อนเดียว</strong> ร่วมกับ Previous Hash และ Nonce — แม้แต่เปลี่ยนตัวอักษรเดียวในช่องนี้ ค่า Hash ที่ได้จะเปลี่ยนไปทั้งหมดทันที (Avalanche Effect)</p>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; font-size:0.8rem;">
                <div style="background:#0d0d0d; border:1px solid #222; border-radius:6px; padding:9px;">
                    <div style="color:var(--btc-orange); font-size:0.72rem; font-weight:700; margin-bottom:5px;">COINBASE TX</div>
                    <div style="color:#777; line-height:1.6;">รางวัลสำหรับนักขุด (ปัจจุบัน 3.125 BTC) + ค่าธรรมเนียมรวมทุก Tx ใน Block — Transaction แรกสุดของทุก Block</div>
                </div>
                <div style="background:#0d0d0d; border:1px solid #222; border-radius:6px; padding:9px;">
                    <div style="color:var(--neon-green); font-size:0.72rem; font-weight:700; margin-bottom:5px;">TRANSACTIONS (Tx)</div>
                    <div style="color:#777; line-height:1.6;">การโอน Bitcoin ระหว่าง Address ที่ Mempool รอยืนยัน — นักขุดเลือกว่าจะใส่ Tx ไหนลง Block (มักเลือก Tx ที่ค่าธรรมเนียมสูงสุดก่อน)</div>
                </div>
                <div style="background:#0d0d0d; border:1px solid #222; border-radius:6px; padding:9px;">
                    <div style="color:#888; font-size:0.72rem; font-weight:700; margin-bottom:5px;">TIMESTAMP</div>
                    <div style="color:#777; line-height:1.6;">เวลาที่นักขุดสร้าง Block — ต้องอยู่ในช่วงเวลาที่ยอมรับได้ตาม Consensus ของ Network</div>
                </div>
                <div style="background:#0d0d0d; border:1px solid #222; border-radius:6px; padding:9px;">
                    <div style="color:#888; font-size:0.72rem; font-weight:700; margin-bottom:5px;">MERKLE ROOT</div>
                    <div style="color:#777; line-height:1.6;">Hash สรุปรวมของทุก Transaction ใน Block — ทำให้ตรวจสอบว่า Tx ไหนอยู่ใน Block โดยไม่ต้องดาวน์โหลดทั้งหมด</div>
                </div>
            </div>
        </div>
        <!-- NEW: Analogy -->
        <div class="edu-analogy">
            <div class="edu-title">🎭 เปรียบเทียบ</div>
            Block Data = <strong>รายการอาหารบนกระดาษคำสั่ง</strong> ทั้งใบ — ถ้าแก้แม้แต่ปริมาณเดียว เลขรหัสท้ายใบสั่ง (Hash) จะเปลี่ยนทันทีและต้องคำนวณ Nonce ใหม่ทั้งหมด ลอง <strong>แก้ไขตัวเลขหรือข้อความในช่องนี้</strong> แล้วสังเกตว่า Nonce รีเซ็ตกลับ 0!
        </div>
    </div>

    <!-- ════════════════════════════════════════
         FIELD 4: Nonce
    ════════════════════════════════════════ -->
    <div class="field">
        <label>🎲 Nonce (ตัวแปรเดียวที่นักขุดควบคุมได้):</label>
        <input type="number" id="nonce" value="0">

        <!-- NEW: Concept -->
        <div class="edu-concept" style="margin-top:10px;">
            <div class="edu-title">📖 แนวคิด — Nonce คืออะไร และทำไมมันถึงสำคัญ?</div>
            <p style="margin:0 0 8px;"><strong style="color:#e0e0e0;">Nonce</strong> (Number Used Once) คือตัวเลขธรรมดาที่นักขุดสุ่มเปลี่ยนค่าไปเรื่อยๆ — <strong>มันคือตัวแปรเดียวที่นักขุดเปลี่ยนได้</strong> โดยที่ข้อมูลอื่นใน Block คงที่</p>
            <p style="margin:0; font-size:0.82rem; color:#7a9070;">SHA-256 เป็นฟังก์ชันทางเดียว (One-Way Function) ไม่มีวิธีลัด — ทำได้อย่างเดียวคือ ลอง Nonce = 1, 2, 3, 4… ไปเรื่อยๆ จนกว่า Hash จะขึ้นต้นด้วย 0 ครบตามจำนวนที่ต้องการ นักขุดระดับโลกทำแบบนี้ <strong style="color:var(--btc-orange);">หลักแสนล้านครั้งต่อวินาที</strong></p>
        </div>
        <!-- NEW: Warning -->
        <div class="edu-warning">
            <div class="edu-title">⚠️ ความจริงที่ควรรู้ — Nonce มีขีดจำกัด</div>
            Nonce ใน Block Header มีขนาดแค่ <strong>32-bit</strong> = สุ่มได้สูงสุด ~4.3 พันล้านค่า — แต่ความยากของเครือข่ายปัจจุบันต้องการ Hash ที่ดี <strong>มากกว่า 4.3 พันล้านครั้ง</strong> นักขุดจึงต้องเปลี่ยน <strong>Extra Nonce</strong> ใน Coinbase Transaction ด้วย เพื่อขยายพื้นที่การค้นหา
        </div>
    </div>

    <!-- Controls -->
    <div class="controls">
        <button class="btn-mine" onclick="handleManualMine()">⚒️ MINE ทีละครั้ง</button>
        <button id="btn-auto" class="btn-auto" onclick="toggleAuto()">🚀 AUTO MINE</button>
    </div>

    <!-- ════════════════════════════════════════
         RESULT: Hash Output
    ════════════════════════════════════════ -->
    <div class="field" style="margin-top:30px;">

        <div class="section-divider">SHA-256 OUTPUT</div>

        <!-- NEW: Concept before result -->
        <div class="edu-concept" style="margin-bottom:14px;">
            <div class="edu-title">📖 อ่านผลลัพธ์ Hash อย่างไร?</div>
            <p style="margin:0 0 6px;">ผลลัพธ์ SHA-256 คือตัวเลข <strong style="color:#e0e0e0;">ฐาน 16 (Hex) ยาว 64 ตัวอักษร</strong> — สิ่งที่นักขุดตรวจสอบคือ <strong style="color:var(--neon-green);">ตัวอักษรแรก N ตัวต้องเป็น 0 ทั้งหมด</strong></p>
            <div class="hash-anatomy">
                <div><span class="ha-zero">0000000000000000</span><span class="ha-rest">000a2d7c965e18cdb41ea696e379df89d67f4524adfebc2</span></div>
                <div style="margin-top:4px; font-family:'Prompt',sans-serif; font-size:0.76rem;">
                    <span style="color:var(--neon-green);">← ส่วนนี้ต้องเป็น 0 ให้ครบตาม Level</span>
                    <span style="color:#444; margin-left:10px;">← ส่วนนี้สุ่มตามข้อมูล</span>
                </div>
            </div>
        </div>

        <div class="result-header">
            <label style="margin:0;">SHA-256 Hash Result:</label>
            <div class="stats-group">
                <div id="hash_rate" class="stat-tag hash-rate" title="Hash Rate: จำนวน Hash ที่คำนวณได้ต่อวินาที">0 H/s</div>
                <div id="energy_cost" class="stat-tag energy-tag" title="ต้นทุนไฟฟ้าจำลอง (100W × ชั่วโมง × 5 บาท/kWh)">฿ 0.0000</div>
                <div id="timer" class="stat-tag" title="เวลาที่ใช้ขุด">00:00:00</div>
            </div>
        </div>
        <div id="hash_box" class="hash-box">WAITING TO MINE...</div>

        <!-- Stats Explanation (always visible) -->
        <div class="field-hint" style="margin-top:8px;">
            <span class="hl-orange">H/s</span> = Hash ต่อวินาที &nbsp;|&nbsp;
            <span class="hl-red">฿ ค่าไฟ</span> = จำลองบน CPU 100W ราคา 5 บาท/kWh &nbsp;|&nbsp;
            <span class="hl-green">สีเขียว</span> = ขุดสำเร็จ! Hash ขึ้นต้นด้วย 0 ครบตาม Level
        </div>

        <div id="raw_label" class="raw-label">📄 Raw Data ที่ป้อนเข้า SHA-256 (ลองคัดลอกไปพิสูจน์เองได้):</div>
        <div class="raw-container" id="raw_container">
            <div id="raw_data" class="raw-data-box"></div>
            <button class="btn-copy" onclick="copyRawData()">📋 Copy</button>
        </div>

        <!-- NEW: DIY Verify Box — dynamic, injects real raw data on win -->
        <div class="edu-deep" style="margin-top:12px; display:none; position:relative;" id="diy_verify_box">
            <div class="edu-title">🔬 ลองพิสูจน์เองด้วย Python (Don't Trust, Verify)</div>
            <p style="margin:0 0 8px; font-family:'Prompt',sans-serif; font-size:0.83rem;">
                คัดลอกโค้ดด้านล่างนี้ไปรันใน Python หรือ
                <a href="https://www.python.org/shell" target="_blank" style="color:var(--edu-blue-accent);">python.org/shell</a>
                ได้เลย — Raw Data จริงถูกใส่ไว้ให้แล้ว ผลลัพธ์ต้องตรงกับ Hash ด้านบน 100%
            </p>
            <div style="position:relative;">
                <pre id="python_code_box" style="
                    margin:0;
                    background:#050505;
                    border:1px solid #1e3a1e;
                    border-radius:6px;
                    padding:14px 16px;
                    font-family:'JetBrains Mono',monospace;
                    font-size:0.78rem;
                    color:var(--neon-green);
                    white-space:pre-wrap;
                    word-break:break-all;
                    line-height:1.65;
                    padding-right:110px;
                "></pre>
                <button onclick="copyPythonCode(this)" style="
                    position:absolute; top:10px; right:10px;
                    background:#1a3a1a; color:var(--neon-green);
                    border:1px solid rgba(0,255,65,0.35);
                    padding:6px 13px; border-radius:4px;
                    cursor:pointer; font-size:0.75rem; font-weight:700;
                    font-family:'Chakra Petch',sans-serif;
                    letter-spacing:0.5px;
                    transition:0.2s;
                " onmouseover="this.style.background='#2a5a2a'" onmouseout="this.style.background='#1a3a1a'">
                    📋 Copy Python
                </button>
            </div>
        </div>

        <div id="win_msg" class="win-text">🎉 Block Mined Successfully! Nonce พบแล้ว! 🎉</div>
    </div>

    <!-- ════════════════════════════════════════
         HISTORY TABLE
    ════════════════════════════════════════ -->
    <div class="history-box">
        <div class="history-header">📜 MINING LOGS — ประวัติการขุดรอบนี้</div>
        <table class="history-table">
            <thead>
                <tr>
                    <th style="width:15%;">Level</th>
                    <th style="width:20%;">Nonce ที่ถูก</th>
                    <th style="width:35%;">Hash ที่ได้</th>
                    <th style="width:30%;">เวลา / ค่าไฟ</th>
                </tr>
            </thead>
            <tbody id="history_list">
                <tr><td colspan="4" style="text-align:center; color:#444; padding:20px;">ยังไม่มีประวัติ — กด MINE หรือ AUTO MINE เพื่อเริ่มต้น</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Probability Note (เดิม + ปรับให้ดีขึ้น) -->
    <div id="note_box" class="note-box"></div>

    <!-- ════════════════════════════════════════
         NEW: BOTTOM EDUCATION SECTION
    ════════════════════════════════════════ -->
    <div class="section-divider" style="margin-top:32px;">BITCOIN MINING — ความเป็นจริง vs. Simulator</div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
        <div class="edu-deep">
            <div class="edu-title-static">🌐 ของจริงในโลก</div>
            <ul style="margin:0; padding-left:18px; line-height:1.9; font-size:0.82rem;">
                <li>Network Hash Rate ≈ <strong style="color:#e0e0e0;">700 Exahash/s</strong> (7×10²⁰ Hash/วิ)</li>
                <li>ต้องการ 0 นำหน้า <strong style="color:#ff6666;"><?php echo $init['zeros']; ?> ตัว</strong> ในปัจจุบัน</li>
                <li>Block ใหม่ทุก <strong style="color:#e0e0e0;">~10 นาที</strong></li>
                <li>รางวัล <strong style="color:var(--btc-orange);">3.125 BTC</strong> ต่อ Block (Halving ล่าสุด)</li>
                <li>ใช้ ASIC เฉพาะทาง ไม่ใช่ CPU</li>
            </ul>
        </div>
        <div class="edu-concept">
            <div class="edu-title-static">🖥️ Simulator นี้</div>
            <ul style="margin:0; padding-left:18px; line-height:1.9; font-size:0.82rem;">
                <li>ใช้ SHA-256 จริง 100% — Hash ที่เห็นพิสูจน์ได้</li>
                <li>Level 1–10 = เลือก Target ที่จัดการด้วย CPU ได้</li>
                <li>Manual Mine = เห็นทุก Hash ทีละค่า</li>
                <li>Auto Mine = 400 Hash/batch เพื่อความเร็ว</li>
                <li><strong style="color:var(--neon-green);">ลอง Level 4–5</strong> เพื่อสัมผัสความยากที่แท้จริง</li>
            </ul>
        </div>
    </div>

    <div class="edu-warning" style="margin-bottom:0;">
        <div class="edu-title-static">🔍 Proof of Work — ทำไมถึงสำคัญกับ Bitcoin?</div>
        PoW ไม่ใช่แค่ "เกมทายตัวเลข" — มันคือ <strong>กลไกสร้างฉันทามติ (Consensus)</strong> โดยไม่ต้องเชื่อใคร: ใครก็ตามที่อยากโกงต้องมีพลังขุดมากกว่า 50% ของทั้ง Network (<strong>51% Attack</strong>) ซึ่งต้องใช้เงินลงทุนมหาศาลและยังไม่คุ้มด้วยซ้ำ เพราะถ้าโกงสำเร็จ Bitcoin ก็จะหมดความน่าเชื่อถือและเงินที่ลงทุนไปก็ไร้ค่า — นี่คือ <strong style="color:var(--neon-green);">Game Theory ที่ทำให้ Bitcoin ปลอดภัย</strong> โดยไม่ต้องพึ่งรัฐบาลหรือธนาคาร
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
    document.getElementById('diy_verify_box').style.display = 'none';
}

function resetAll() {
    stopAuto();
    resetStatsOnly();
    document.getElementById('nonce').value = 0;
    document.getElementById('hash_box').innerText = "WAITING TO MINE...";
    document.getElementById('hash_box').style.color = "#333";
    
    const d = parseInt(document.getElementById('diff_level').value);
    const totalPossibilities = Math.pow(16, d);
    let note = "";
    if (totalPossibilities < 1000000) {
        note = `<strong>Probability:</strong> โอกาสสำเร็จต่อ 1 Hash คือ <strong>1 ใน ${totalPossibilities.toLocaleString()}</strong> — เทียบเท่าถูก <strong>"เลขท้าย 3 ตัว"</strong> ประมาณ <strong>${(totalPossibilities/1000).toFixed(2)} ครั้ง "ติดต่อกัน"</strong>`;
    } else {
        note = `<strong>Probability:</strong> โอกาสสำเร็จต่อ 1 Hash คือ <strong>1 ใน ${totalPossibilities.toLocaleString()}</strong> — เทียบเท่าถูก <strong>"รางวัลที่ 1"</strong> ประมาณ <strong>${(Math.log10(totalPossibilities)/6).toFixed(2)} ครั้ง "ติดต่อกัน"</strong>`;
    }
    document.getElementById('note_box').innerHTML = note;
}

function runMining(count = 1) {
    const prevHash = document.getElementById('prev_hash').value;
    const data = document.getElementById('tx_input').value;
    const target = "0".repeat(parseInt(document.getElementById('diff_level').value));
    let nonce = parseInt(document.getElementById('nonce').value);

    document.getElementById('raw_label').style.display = 'block';
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
            // Inject raw data into Python code box
            document.getElementById('python_code_box').textContent =
                `import hashlib\n\ndata = """${currentInput}"""\n\nresult = hashlib.sha256(data.encode()).hexdigest()\nprint(result)\n# ผลลัพธ์ที่ถูกต้อง:\n# ${hash}`;
            document.getElementById('diy_verify_box').style.display = 'block';
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

function copyPythonCode(btn) {
    const text = document.getElementById('python_code_box').textContent;
    navigator.clipboard.writeText(text).then(() => {
        btn.innerText = "✅ Copied!";
        btn.style.background = "#0d3a0d";
        btn.style.borderColor = "var(--neon-green)";
        setTimeout(() => {
            btn.innerText = "📋 Copy Python";
            btn.style.background = "#1a3a1a";
            btn.style.borderColor = "rgba(0,255,65,0.35)";
        }, 2000);
    });
}

resetAll();

// ── Collapsible edu boxes ──
// wrap sibling content of every .edu-title in .edu-body, collapsed by default
document.querySelectorAll('.edu-title').forEach(title => {
    // collect all sibling nodes that come AFTER the title inside the same parent
    const siblings = [];
    let node = title.nextSibling;
    while (node) {
        siblings.push(node);
        node = node.nextSibling;
    }
    if (!siblings.length) return;

    // wrap them in a div.edu-body — hidden by default
    const body = document.createElement('div');
    body.className = 'edu-body collapsed';
    const parent = title.parentNode;
    siblings.forEach(s => body.appendChild(s));
    parent.appendChild(body);

    // mark title arrow as collapsed on load
    title.classList.add('collapsed');

    // click to toggle
    title.addEventListener('click', () => {
        const collapsed = body.classList.toggle('collapsed');
        title.classList.toggle('collapsed', collapsed);
    });
});
</script>
</body>
</html>