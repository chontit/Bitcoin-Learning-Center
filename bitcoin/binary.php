<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2²⁵⁶ — The Immeasurable | Bitcoin Security Visualizer</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Orbitron:wght@500;900&family=Chakra+Petch:wght@400;600;700&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/svg+xml">
    <style>
        :root {
            --bg:         #050505;
            --bg2:        #0a0a0a;
            --bg3:        #111;
            --orange:     #F7931A;
            --orange-dim: rgba(247,147,26,0.15);
            --orange-glow:rgba(247,147,26,0.4);
            --green:      #00ff88;
            --red:        #ff4444;
            --blue:       #4a9eff;
            --text:       #e0e0e0;
            --muted:      #666;
            --border:     #222;
            --bit-off:    #1e1e1e;
            --bit-on:     #F7931A;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
            padding: 0 0 60px;
        }

        /* ── Header ── */
        .site-header {
            background: rgba(10,10,10,0.95);
            border-bottom: 1px solid var(--border);
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky; top: 0; z-index: 100;
            backdrop-filter: blur(10px);
        }
        .site-header .logo {
            display: flex; align-items: center; gap: 10px;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem; font-weight: 900;
            color: var(--orange);
            text-shadow: 0 0 12px var(--orange-glow);
        }
        .site-header .logo img { width: 28px; filter: drop-shadow(0 0 8px var(--orange-glow)); }
        .home-btn {
            display: flex; align-items: center; gap: 6px;
            font-family: 'Orbitron', sans-serif; font-size: 0.75rem;
            color: var(--orange); text-decoration: none;
            border: 1px solid rgba(247,147,26,0.3);
            padding: 7px 14px; border-radius: 5px;
            background: rgba(0,0,0,0.5);
            transition: all 0.2s;
        }
        .home-btn:hover { border-color: var(--orange); box-shadow: 0 0 10px var(--orange-glow); }

        /* ── Page layout ── */
        .page { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

        /* ── Hero intro ── */
        .hero {
            text-align: center;
            margin-bottom: 28px;
        }
        .hero h1 {
            font-family: 'Chakra Petch', 'Prompt', sans-serif;
            font-size: clamp(1.6rem, 4vw, 2.8rem);
            color: var(--orange);
            text-shadow: 0 0 20px var(--orange-glow);
            line-height: 1.25;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .hero h1 .latin-part {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .hero .tagline {
            color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;
        }
        /* Concept strip */
        .concept-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 0;
            text-align: left;
        }
        .concept-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 0.78rem;
            line-height: 1.65;
            color: #999;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .concept-card:hover { border-color: rgba(247,147,26,0.4); }
        .concept-card .cc-title {
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 6px;
        }
        .concept-card .cc-body { overflow: hidden; max-height: 0; opacity: 0; transition: max-height 0.3s ease, opacity 0.25s ease; }
        .concept-card.open .cc-body { max-height: 300px; opacity: 1; }
        .concept-card .cc-arrow { float: right; transition: transform 0.25s; font-size: 0.6rem; opacity: 0.5; }
        .concept-card.open .cc-arrow { transform: rotate(180deg); }

        /* ── Main grid ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-top: 20px;
        }
        @media(min-width: 900px) {
            .main-grid { grid-template-columns: 1fr 340px; }
        }

        /* ── Binary display ── */
        .binary-panel {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
        }
        .binary-panel-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px;
        }
        .binary-panel-header h2 {
            font-size: 0.7rem; color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 2px; text-transform: uppercase;
        }
        .bit-meta {
            display: flex; gap: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem; color: var(--muted);
        }
        .bit-meta span { color: var(--orange); font-weight: 700; }

        /* Byte groups — desktop 8 col (ผ่าน 2-col grid wrapper), mobile 4 col */
        .byte-groups, .byte-groups-half {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            margin-bottom: 4px;
        }
        .byte-group {
            background: #0d0d0d;
            border: 1px solid #1a1a1a;
            border-radius: 4px;
            padding: 8px 3px;
        }
        .byte-label { display: none; }
        .byte-bits {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 1px;
        }
        .bit {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(0.55rem, 1.2vw, 0.92rem);
            font-weight: 700;
            color: var(--bit-off);
            text-align: center;
            line-height: 1.9;
            transition: color 0.08s, text-shadow 0.08s;
            border-radius: 2px;
            user-select: none;
        }
        .bit.on {
            color: var(--bit-on);
            text-shadow: 0 0 6px var(--orange-glow);
        }
        .bit.flash {
            color: #fff;
            text-shadow: 0 0 10px #fff;
        }

        /* Byte sub-labels (ใหม่: 1 label row ต่อ 4 bytes) */
        .byte-sub-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.52rem; color: #2a2a2a;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            margin-bottom: 2px;
            text-align: center;
        }
        /* desktop: ซ่อน bl-short, แสดง bl-full */
        .bl-full  { display: block; }
        .bl-short { display: none; }

        /* Desktop: จัด 2 sub-label + 2 byte-row เป็นคู่ 8 col
           ใช้ CSS grid wrapper ที่ binary-panel */
        @media(min-width: 701px) {
            /* pair label rows side-by-side */
            .byte-sub-label {
                grid-template-columns: repeat(4, 1fr);
            }
            /* pair byte-groups-half side-by-side ใน 8 col */
            .byte-groups-half {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Byte pair gap (เว้นวรรคระหว่างคู่บน mobile) */
        .byte-pair-gap { display: none; }
        @media(max-width: 700px) {
            .byte-pair-gap { display: block; height: 12px; }
        }

        /* Mobile: แสดง bl-short ซ่อน bl-full */
        @media(max-width: 700px) {
            .byte-sub-label {
                grid-template-columns: repeat(4, 1fr);
                gap: 3px; font-size: 0.58rem;
                margin-bottom: 3px; margin-top: 6px;
                color: #555;
            }
            .bl-full  { display: none; }
            .bl-short { display: block; }
            .bl-mark  { color: rgba(247,147,26,0.7); font-weight: 700; }
            .bl-msb   { color: #666; }
            .bl-lsb   { color: rgba(0,255,136,0.6); font-weight: 700; }

            /* byte-groups-half = 4 col, 1 row (4 bytes) */
            .byte-groups-half {
                grid-template-columns: repeat(4, 1fr);
                gap: 3px; margin-bottom: 2px;
            }
            .byte-group { padding: 5px 2px; }
        }
        @media(max-width: 400px) {
            .byte-groups-half { gap: 2px; }
            .byte-group { padding: 4px 1px; }
            .byte-sub-label { font-size: 0.52rem; }
        }

        /* MSB/LSB row labels */
        .bit-axis {
            display: flex; justify-content: space-between;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem; color: #2a2a2a;
            margin-top: 4px; padding: 0 2px;
        }

        /* ── Decimal display ── */
        .decimal-panel {
            background: #0d0d0d;
            border-left: 4px solid var(--orange);
            border-radius: 0 6px 6px 0;
            padding: 14px 16px;
            margin: 12px 0;
        }
        .decimal-label-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 6px;
        }
        .decimal-label-txt {
            font-size: 0.68rem; color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .decimal-digits-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem; color: var(--blue);
            background: rgba(74,158,255,0.08);
            border: 1px solid rgba(74,158,255,0.2);
            padding: 1px 7px; border-radius: 10px;
        }
        .decimal-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: clamp(0.9rem, 2vw, 1.3rem);
            color: #fff;
            word-break: break-all;
            line-height: 1.5;
        }

        /* ── Progress Bars (dual) ── */
        .progress-section { margin: 12px 0; }
        .progress-row {
            margin-bottom: 10px;
        }
        .progress-header {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.68rem; margin-bottom: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
        .prog-label { color: var(--muted); }
        .prog-val   { color: var(--orange); font-weight: 700; font-size: 0.72rem; }
        .progress-track {
            width: 100%; height: 7px;
            background: #0d0d0d; border: 1px solid #1a1a1a;
            border-radius: 4px; overflow: hidden; position: relative;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.25s ease;
            min-width: 2px;
        }
        .progress-fill.linear {
            background: linear-gradient(to right, #4a9eff, #00ff88);
            box-shadow: 0 0 6px rgba(74,158,255,0.5);
        }
        .progress-fill.log {
            background: linear-gradient(to right, var(--orange), #ffcc00);
            box-shadow: 0 0 6px var(--orange-glow);
        }
        .progress-note {
            font-size: 0.62rem; color: #2e2e2e;
            margin-top: 3px; font-family: 'JetBrains Mono', monospace;
            line-height: 1.4;
        }
        .progress-note.highlight { color: #555; }

        /* ── Right panel ── */
        .right-panel { display: flex; flex-direction: column; gap: 12px; }

        /* Analogy box */
        .analogy-box {
            background: linear-gradient(180deg,#1a1a1a,#0d0d0d);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            min-height: 140px;
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            transition: border-color 0.3s, background 0.3s;
            box-shadow: inset 0 0 20px #000;
            flex-shrink: 0;
        }
        .analogy-icon { font-size: 2.5rem; margin-bottom: 10px; }
        .analogy-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; color: #fff; line-height: 1.3; }
        .analogy-desc  { color: #aaa; font-size: 0.82rem; line-height: 1.6; }
        .analogy-context { margin-top: 8px; font-size: 0.72rem; color: #555; font-style: italic; }

        /* Stats */
        .stats-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .stat-box {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }
        .stat-val   { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: var(--orange); font-size: 1rem; margin-bottom: 4px; word-break: break-word; }
        .stat-label { font-size: 0.65rem; color: var(--muted); line-height: 1.4; }

        /* Context box */
        .context-box {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 12px 14px;
            font-size: 0.78rem;
            color: #888;
            line-height: 1.7;
        }
        .context-box .ctx-title {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--blue); margin-bottom: 8px;
        }
        .ctx-kv { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 4px; font-size: 0.72rem; }
        .ctx-kv .k { color: #555; }
        .ctx-kv .v { color: #ccc; font-family: 'JetBrains Mono', monospace; text-align: right; }

        /* ── Controls ── */
        .controls-section { margin-top: 16px; }
        .controls-row { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 12px; }

        .btn {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem; font-weight: 700;
            cursor: pointer; border: none;
            border-radius: 6px; padding: 13px 24px;
            text-transform: uppercase; letter-spacing: 1px;
            transition: all 0.2s;
        }
        #btnToggle {
            background: var(--orange); color: #000;
            box-shadow: 0 0 15px var(--orange-glow);
            min-width: 220px;
        }
        #btnToggle:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 0 25px var(--orange-glow); }
        #btnToggle.running { background: var(--red); color: #fff; box-shadow: 0 0 15px rgba(255,68,68,0.4); }
        #btnToggle:disabled { background: #333; color: #666; cursor: not-allowed; box-shadow: none; }

        #btnReset { background: #1a1a1a; color: #888; border: 1px solid #333; }
        #btnReset:hover { background: #222; color: #fff; }

        /* ── Warp Zones ── */
        .warp-section {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        .warp-header {
            font-size: 0.7rem; color: var(--muted);
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
        .warp-subtitle {
            font-size: 0.72rem; color: #3a3a3a;
            margin-bottom: 12px; line-height: 1.5;
        }
        .warp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 6px;
        }
        .warp-btn {
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 5px;
            padding: 9px 10px;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s;
        }
        .warp-btn:hover { border-color: var(--orange); background: #1a0f00; }
        .warp-btn .wb-scale {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem; font-weight: 700;
            color: var(--orange); margin-bottom: 2px;
        }
        .warp-btn .wb-desc {
            font-size: 0.65rem; color: #555; line-height: 1.4;
        }

        /* ── Win screen ── */
        .win-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.92);
            z-index: 200;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .win-overlay.active { display: flex; }
        .win-icon { font-size: 5rem; margin-bottom: 16px; }
        .win-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem; color: var(--orange);
            text-shadow: 0 0 30px var(--orange-glow);
            margin-bottom: 10px;
        }
        .win-desc { color: #aaa; font-size: 0.9rem; max-width: 500px; line-height: 1.7; margin-bottom: 24px; }
        .win-restart {
            background: var(--orange); color: #000;
            font-family: 'Orbitron', sans-serif; font-size: 0.9rem;
            font-weight: 700; border: none; border-radius: 6px;
            padding: 14px 32px; cursor: pointer;
            text-transform: uppercase;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }

        /* ═══════════════════════════════════════
           MOBILE RESPONSIVE — ครอบคลุมทุกส่วน
        ═══════════════════════════════════════ */

        /* ── Global overflow guard ── */
        html { overflow-x: hidden; }
        body { overflow-x: hidden; }

        /* ── Page padding ── */
        @media(max-width: 600px) {
            .page { padding: 12px 12px 40px; }
        }

        /* ── Header ── */
        @media(max-width: 500px) {
            .site-header { padding: 10px 14px; }
            .site-header .logo { font-size: 0.78rem; }
            .site-header .logo img { width: 22px; }
            .home-btn { font-size: 0.65rem; padding: 5px 10px; }
        }

        /* ── Hero h1 ── */
        @media(max-width: 500px) {
            .hero h1 { font-size: clamp(1.3rem, 7vw, 1.8rem); letter-spacing: 0; }
            .hero .tagline { font-size: 0.75rem; }
        }

        /* ── Concept strip ── */
        @media(max-width: 500px) {
            .concept-strip { grid-template-columns: 1fr 1fr; gap: 6px; }
        }
        @media(max-width: 360px) {
            .concept-strip { grid-template-columns: 1fr; }
        }

        /* Desktop: จัด sub-group pairs เป็น 8 col ต่อแถว */
        @media(min-width: 701px) {
            .byte-row-pair {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 6px;
                margin-bottom: 6px;
            }
            .byte-pair-gap { display: none; }
        }
        @media(max-width: 600px) {
            .binary-panel { padding: 10px 8px; }
            .binary-panel-header { flex-direction: column; align-items: flex-start; gap: 4px; }
            .binary-panel-header h2 { font-size: 0.6rem; letter-spacing: 1px; }
            .bit-meta { font-size: 0.6rem; }
        }


        /* Bit font */
        @media(max-width: 700px) {
            .bit { font-size: clamp(0.5rem, 2.8vw, 0.72rem); line-height: 1.5; }
        }
        @media(max-width: 400px) {
            .bit { font-size: clamp(0.45rem, 3vw, 0.62rem); }
        }

        /* Bit axis */
        @media(max-width: 500px) {
            .bit-axis { font-size: 0.5rem; }
        }

        /* Decimal value */
        @media(max-width: 500px) {
            .decimal-value { font-size: clamp(0.7rem, 3.5vw, 1rem); }
            .decimal-panel { padding: 10px 12px; }
        }

        /* Progress bars */
        @media(max-width: 500px) {
            .progress-header { font-size: 0.6rem; }
            .prog-val { font-size: 0.65rem; }
            .progress-note { font-size: 0.58rem; }
        }

        /* Controls */
        @media(max-width: 500px) {
            .controls-section .controls-row { gap: 8px; }
            #btnToggle { min-width: unset; width: 100%; font-size: 0.78rem; padding: 12px 16px; }
            #btnReset  { width: 100%; font-size: 0.78rem; padding: 12px 16px; }
            .controls-row { flex-direction: column; }
        }

        /* Right panel stats */
        @media(max-width: 500px) {
            .stats-row { grid-template-columns: 1fr 1fr; gap: 6px; }
            .stat-val { font-size: 0.85rem; }
            .stat-label { font-size: 0.6rem; }
            .analogy-box { padding: 14px; min-height: 100px; }
            .analogy-icon { font-size: 1.8rem; margin-bottom: 6px; }
            .analogy-title { font-size: 0.9rem; }
            .analogy-desc  { font-size: 0.72rem; }
            .context-box { padding: 10px 12px; font-size: 0.72rem; }
            .ctx-kv { font-size: 0.66rem; }
        }

        /* Warp grid */
        @media(max-width: 600px) {
            .warp-grid { grid-template-columns: repeat(2, 1fr); gap: 5px; }
            .warp-btn { padding: 7px 8px; }
            .warp-btn .wb-scale { font-size: 0.68rem; }
            .warp-btn .wb-desc  { font-size: 0.6rem; }
        }
        @media(max-width: 360px) {
            .warp-grid { grid-template-columns: 1fr; }
        }

        /* Warp section */
        @media(max-width: 600px) {
            .warp-section { padding: 12px 10px; }
            .warp-subtitle { font-size: 0.65rem; }
        }
    </style>
</head>
<body>

    <!-- ══════════════════════════════
         HEADER
    ══════════════════════════════ -->
    <header class="site-header">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" alt="BTC">
            2<sup style="font-size:0.6em;">256</sup> — The Immeasurable
        </div>
        <a href="/" class="home-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            HOME
        </a>
    </header>

    <div class="page">

        <!-- ══════════════════════════════
             HERO + CONCEPT CARDS
        ══════════════════════════════ -->
        <div class="hero">
            <h1><span class="latin-part">2<sup>256</sup></span> — ตัวเลขที่ใหญ่เกินจินตนาการ</h1>
            <p class="tagline">Visualizing the scale of Bitcoin's private key security — นับ 1 ทีละ 1 จนถึงขีดจำกัด 256-bit</p>

            <div class="concept-strip">

                <div class="concept-card" onclick="toggleCard(this)">
                    <div class="cc-title" style="color:#F7931A;">🔑 256-bit คืออะไร? <span class="cc-arrow">▲</span></div>
                    <div class="cc-body" style="margin-top:6px;">
                        Private Key ของ Bitcoin คือตัวเลขสุ่ม <strong style="color:#e0e0e0;">256 บิต</strong> — หมายความว่ามีความเป็นไปได้ทั้งหมด <strong style="color:#F7931A;">2²⁵⁶</strong> ค่า (ประมาณ 10⁷⁷) ซึ่งมากกว่าจำนวนอะตอมในจักรวาลที่สังเกตได้เสียอีก ทำให้การเดา Private Key โดยตรงเป็นไปไม่ได้ในทางปฏิบัติ
                    </div>
                </div>

                <div class="concept-card" onclick="toggleCard(this)">
                    <div class="cc-title" style="color:#4a9eff;">🔢 Binary คืออะไร? <span class="cc-arrow">▲</span></div>
                    <div class="cc-body" style="margin-top:6px;">
                        ตัวเลขฐาน 2 (Binary) ใช้เพียง <strong style="color:#e0e0e0;">0 และ 1</strong> แต่ละหลักเรียกว่า <strong style="color:#4a9eff;">Bit</strong> — 8 บิต = 1 Byte, 32 Byte = 256 บิต<br><br>
                        ตัวอย่าง: เลข 13 ในฐาน 10 = <strong style="color:#F7931A;">1101</strong> ในฐาน 2 (8+4+0+1)
                    </div>
                </div>

                <div class="concept-card" onclick="toggleCard(this)">
                    <div class="cc-title" style="color:#00ff88;">⏱️ ทำไมถึงนับไม่ถึง? <span class="cc-arrow">▲</span></div>
                    <div class="cc-body" style="margin-top:6px;">
                        คอมพิวเตอร์เร็วที่สุดในโลกนับได้ประมาณ <strong style="color:#e0e0e0;">10¹⁸ ครั้ง/วินาที</strong> แต่ 2²⁵⁶ ≈ 10⁷⁷<br><br>
                        ต้องใช้เวลาประมาณ <strong style="color:#00ff88;">10⁵⁹ ปี</strong> — มากกว่าอายุจักรวาล (1.4×10¹⁰ ปี) อยู่ถึง 10⁴⁹ เท่า
                    </div>
                </div>

                <div class="concept-card" onclick="toggleCard(this)">
                    <div class="cc-title" style="color:#a78bfa;">🛡️ ความปลอดภัยของ Bitcoin <span class="cc-arrow">▲</span></div>
                    <div class="cc-body" style="margin-top:6px;">
                        Private Key ของคุณเป็นตัวเลขสุ่มในช่วง 1 ถึง 2²⁵⁶ — โอกาสที่ใครจะสุ่มได้ตรงกับของคุณพอดีเหมือนกับการสุ่มเลือกอะตอม 1 อะตอมจากทุกอะตอมในจักรวาล <strong style="color:#a78bfa;">สองครั้งติดต่อกัน</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- ══════════════════════════════
             MAIN GRID
        ══════════════════════════════ -->
        <div class="main-grid">

            <!-- LEFT: Binary + Decimal -->
            <div>
                <div class="binary-panel">
                    <div class="binary-panel-header">
                        <h2>Binary Representation — 256 Bits (32 Bytes)</h2>
                        <div class="bit-meta">
                            Active bits: <span id="activeBitCount">0</span> / 256
                        </div>
                    </div>

                    <!-- 8 sub-groups × 4 bytes, จัดเป็น 4 คู่
                         Desktop: แต่ละคู่ = grid 2-col → รวมเป็น 8 col ต่อแถว
                         Mobile:  แต่ละ sub-group = label + byte row แยกชัดเจน -->

                    <!-- คู่ที่ 1: Byte 31–24 -->
                    <div class="byte-row-pair">
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 31 <small style="color:#3a3a3a;">(MSB)</small></span>
                                <span class="bl-full">Byte 30</span><span class="bl-full">Byte 29</span><span class="bl-full">Byte 28</span>
                                <span class="bl-short bl-msb">Byte 31</span>
                                <span class="bl-short">Byte 30</span><span class="bl-short">Byte 29</span><span class="bl-short">Byte 28</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR1"></div>
                        </div>
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 27</span><span class="bl-full">Byte 26</span>
                                <span class="bl-full">Byte 25</span>
                                <span class="bl-full">Byte 24 <small style="color:#F7931A;opacity:0.5;">Bit 191</small></span>
                                <span class="bl-short">Byte 27</span><span class="bl-short">Byte 26</span>
                                <span class="bl-short">Byte 25</span><span class="bl-short bl-mark">Byte 24</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR2"></div>
                        </div>
                    </div>

                    <div class="byte-pair-gap"></div>

                    <!-- คู่ที่ 2: Byte 23–16 -->
                    <div class="byte-row-pair">
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 23</span><span class="bl-full">Byte 22</span>
                                <span class="bl-full">Byte 21</span><span class="bl-full">Byte 20</span>
                                <span class="bl-short">Byte 23</span><span class="bl-short">Byte 22</span>
                                <span class="bl-short">Byte 21</span><span class="bl-short">Byte 20</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR3"></div>
                        </div>
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 19</span><span class="bl-full">Byte 18</span>
                                <span class="bl-full">Byte 17</span>
                                <span class="bl-full">Byte 16 <small style="color:#F7931A;opacity:0.5;">Bit 127</small></span>
                                <span class="bl-short">Byte 19</span><span class="bl-short">Byte 18</span>
                                <span class="bl-short">Byte 17</span><span class="bl-short bl-mark">Byte 16</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR4"></div>
                        </div>
                    </div>

                    <div class="byte-pair-gap"></div>

                    <!-- คู่ที่ 3: Byte 15–8 -->
                    <div class="byte-row-pair">
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 15</span><span class="bl-full">Byte 14</span>
                                <span class="bl-full">Byte 13</span><span class="bl-full">Byte 12</span>
                                <span class="bl-short">Byte 15</span><span class="bl-short">Byte 14</span>
                                <span class="bl-short">Byte 13</span><span class="bl-short">Byte 12</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR5"></div>
                        </div>
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 11</span><span class="bl-full">Byte 10</span>
                                <span class="bl-full">Byte 9</span>
                                <span class="bl-full">Byte 8 <small style="color:#F7931A;opacity:0.5;">Bit 63</small></span>
                                <span class="bl-short">Byte 11</span><span class="bl-short">Byte 10</span>
                                <span class="bl-short">Byte 9</span><span class="bl-short bl-mark">Byte 8</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR6"></div>
                        </div>
                    </div>

                    <div class="byte-pair-gap"></div>

                    <!-- คู่ที่ 4: Byte 7–0 -->
                    <div class="byte-row-pair">
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 7</span><span class="bl-full">Byte 6</span>
                                <span class="bl-full">Byte 5</span><span class="bl-full">Byte 4</span>
                                <span class="bl-short">Byte 7</span><span class="bl-short">Byte 6</span>
                                <span class="bl-short">Byte 5</span><span class="bl-short">Byte 4</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR7"></div>
                        </div>
                        <div>
                            <div class="byte-sub-label">
                                <span class="bl-full">Byte 3</span><span class="bl-full">Byte 2</span>
                                <span class="bl-full">Byte 1</span>
                                <span class="bl-full">Byte 0 <small style="color:#00ff88;opacity:0.5;">(LSB)</small></span>
                                <span class="bl-short">Byte 3</span><span class="bl-short">Byte 2</span>
                                <span class="bl-short">Byte 1</span><span class="bl-short bl-lsb">Byte 0</span>
                            </div>
                            <div class="byte-groups byte-groups-half" id="byteGroupsR8"></div>
                        </div>
                    </div>

                    <div class="bit-axis">
                        <span>← Bit 255 (สำคัญที่สุด / MSB)</span>
                        <span>Bit 0 (สำคัญน้อยที่สุด / LSB) →</span>
                    </div>
                </div>

                <!-- Decimal -->
                <div class="decimal-panel" style="margin-top:12px;">
                    <div class="decimal-label-row">
                        <span class="decimal-label-txt">Decimal Value (ค่าตัวเลขฐาน 10)</span>
                        <span class="decimal-digits-badge" id="digitCount">1 หลัก</span>
                    </div>
                    <div class="decimal-value" id="decimalDisplay">0</div>
                </div>

                <!-- Progress: dual bar -->
                <div class="progress-section">

                    <!-- Bar 1: Linear — แสดงความจริง -->
                    <div class="progress-row">
                        <div class="progress-header">
                            <span class="prog-label">📏 Linear — ความคืบหน้าที่แท้จริง</span>
                            <span class="prog-val" id="linearPct">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill linear" id="linearBar" style="width:0%;"></div>
                        </div>
                        <div class="progress-note highlight" id="linearNote">2²⁵⁵ = 50.00% พอดี — เพราะ Linear คือสัดส่วนจริงของค่า</div>
                    </div>

                    <!-- Bar 2: Log Scale — แสดงความ "รู้สึก" -->
                    <div class="progress-row">
                        <div class="progress-header">
                            <span class="prog-label">📐 Log Scale — ตามจำนวน Bit ที่ใช้</span>
                            <span class="prog-val" id="logPct">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill log" id="logBar" style="width:0.5px;"></div>
                        </div>
                        <div class="progress-note" id="logNote">2²⁵⁵ = 99.6% เพราะใช้ Bit ครบ 255 จาก 256 แล้ว — แต่ค่ายังห่างอีกเท่าตัว!</div>
                    </div>

                </div>

                <!-- Controls -->
                <div class="controls-section">
                    <div class="controls-row">
                        <button id="btnToggle" class="btn" onclick="toggleCounting()">START COUNTING</button>
                        <button id="btnReset" class="btn" onclick="reset()">RESET</button>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Analogy + Stats + Context -->
            <div class="right-panel">

                <!-- Analogy -->
                <div class="analogy-box" id="analogyBox">
                    <div class="analogy-icon">🌱</div>
                    <div class="analogy-title">เริ่มต้น</div>
                    <div class="analogy-desc">กด START COUNTING เพื่อเริ่มนับ<br>ดูตัวเลขไต่ขึ้นทีละ 1</div>
                    <div class="analogy-context">กล่องนี้จะเปลี่ยนเมื่อผ่านหลักสำคัญ</div>
                </div>

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-val" id="speedDisplay">0</div>
                        <div class="stat-label">ตัวเลข / วินาที<br>(ความเร็วอุปกรณ์นี้)</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-val" id="timeEstDisplay">∞</div>
                        <div class="stat-label">เวลาที่ต้องใช้<br>เพื่อนับถึง 2²⁵⁶</div>
                    </div>
                </div>

                <!-- Context: What 2^256 means for Bitcoin -->
                <div class="context-box">
                    <div class="ctx-title">🔑 2²⁵⁶ กับความปลอดภัยของ Bitcoin</div>
                    <div class="ctx-kv"><span class="k">จำนวนช่วงทั้งหมด</span><span class="v">~1.16 × 10⁷⁷</span></div>
                    <div class="ctx-kv"><span class="k">อะตอมในจักรวาล</span><span class="v">~10⁸⁰</span></div>
                    <div class="ctx-kv"><span class="k">อะตอมบนโลก</span><span class="v">~10⁵⁰</span></div>
                    <div class="ctx-kv"><span class="k">Bitcoin Address</span><span class="v">~10⁴⁸ ต่อ 1 คน</span></div>
                    <div style="margin-top:8px; font-size:0.68rem; color:#444; line-height:1.6;">
                        Private Key ของคุณถูกสุ่มใน 1 จาก 2²⁵⁶ ตำแหน่ง — ใครไม่มีทางเดาได้ในชีวิตนี้ หรือแม้แต่ตลอดอายุจักรวาล
                    </div>
                </div>

                <!-- Live bit stats -->
                <div class="context-box">
                    <div class="ctx-title">📊 สถานะ Binary ปัจจุบัน</div>
                    <div class="ctx-kv"><span class="k">Bit ที่เป็น 1</span><span class="v" id="ctx-ones">0 bits</span></div>
                    <div class="ctx-kv"><span class="k">Bit ที่เป็น 0</span><span class="v" id="ctx-zeros">256 bits</span></div>
                    <div class="ctx-kv"><span class="k">Bit สูงสุดที่ใช้</span><span class="v" id="ctx-highbit">Bit 0</span></div>
                    <div class="ctx-kv"><span class="k">ค่าใน Hex</span><span class="v" id="ctx-hex" style="font-size:0.62rem; word-break:break-all;">0x0</span></div>
                </div>

            </div>
        </div>

        <!-- ══════════════════════════════
             WARP ZONES
        ══════════════════════════════ -->
        <div class="warp-section">
            <div class="warp-header">⚡ Warp Zones — กระโดดไปยังหลักสำคัญ</div>
            <div class="warp-subtitle">คลิกเพื่อ Warp ตัวนับไปยังตัวเลขนั้นทันที แล้วดูว่า Binary Pattern เปลี่ยนไปอย่างไร และยังเหลือเวลาอีกนานแค่ไหนกว่าจะถึง 2²⁵⁶ — เรียงจากน้อยไปมาก</div>
            <div class="warp-grid">
                <button class="warp-btn" onclick="warpTo('1000000')">
                    <div class="wb-scale">10⁶ — 1 ล้าน</div>
                    <div class="wb-desc">โอกาสถูกลอตเตอรี่รางวัลที่ 1</div>
                </button>
                <button class="warp-btn" onclick="warpTo('7900000000')">
                    <div class="wb-scale">7.9 × 10⁹</div>
                    <div class="wb-desc">ประชากรโลกทั้งหมด</div>
                </button>
                <button class="warp-btn" onclick="warpTo('1000000000000')">
                    <div class="wb-scale">10¹² — 1 ล้านล้าน</div>
                    <div class="wb-desc">จำนวนเซลล์ประสาทในสมองมนุษย์</div>
                </button>
                <button class="warp-btn" onclick="warpTo('1000000000000000000')">
                    <div class="wb-scale">10¹⁸ — 1 Quintillion</div>
                    <div class="wb-desc">เม็ดทรายทั้งหมดบนโลก</div>
                </button>
                <button class="warp-btn" onclick="warpTo('602200000000000000000000')">
                    <div class="wb-scale">6.02 × 10²³</div>
                    <div class="wb-desc">เลขอาโวกาโดร (1 โมล)</div>
                </button>
                <button class="warp-btn" onclick="warpTo('1000000000000000000000000000')">
                    <div class="wb-scale">10²⁷</div>
                    <div class="wb-desc">อะตอมในร่างกายมนุษย์ 1 คน</div>
                </button>
                <button class="warp-btn" onclick="warpTo('340282366920938463463374607431768211456')">
                    <div class="wb-scale">2¹²⁸ — ครึ่งทาง (Bit)</div>
                    <div class="wb-desc">ครึ่งหนึ่งของ Bit แต่ค่ายังห่างไกลมาก</div>
                </button>
                <button class="warp-btn" onclick="warpTo('100000000000000000000000000000000000000000000000000')">
                    <div class="wb-scale">10⁵⁰</div>
                    <div class="wb-desc">อะตอมทั้งหมดบนโลก</div>
                </button>
                <button class="warp-btn" onclick="warpTo('57896044618658097711785492504343953926634992332820282019728792003956564819968')">
                    <div class="wb-scale">2²⁵⁵ — Linear 50% พอดี</div>
                    <div class="wb-desc">ครึ่งหนึ่งของค่าจริง — Bar สีฟ้าจะแสดง 50.000%</div>
                </button>
                <button class="warp-btn" onclick="warpTo('100000000000000000000000000000000000000000000000000000000000000000000000000000')">
                    <div class="wb-scale">10⁷⁷ — ใกล้ 2²⁵⁶</div>
                    <div class="wb-desc">อะตอมในจักรวาลที่สังเกตได้</div>
                </button>
                <button class="warp-btn" onclick="warpToMax()">
                    <div class="wb-scale">2²⁵⁶ − 10,000,000</div>
                    <div class="wb-desc">⚠️ 10 ล้านก้าวสุดท้าย — กด Start แล้วดูตัวเลขวิ่งสู่จุดจบ</div>
                </button>
            </div>
        </div>

    </div><!-- end .page -->

    <footer style="
        text-align: center;
        padding: 20px 16px;
        margin-top: 10px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.78rem;
        color: #333;
        border-top: 1px solid #111;
        line-height: 1.8;
    ">
        <span style="white-space: nowrap;">© 2026 Chollatis Bitcoiner</span>
        <span style="color: #222; margin: 0 6px;">|</span>
        <span style="color: #F7931A; opacity: 0.6; white-space: nowrap;"><em>Don't Trust, Verify.</em></span>
    </footer>

    <!-- Win overlay -->
    <div class="win-overlay" id="winOverlay">
        <div class="win-icon">🏁</div>
        <div class="win-title">THE END OF ENTROPY</div>
        <div class="win-desc">
            สิ้นสุดความเป็นไปได้ของ 256-bit<br>
            คุณได้นับครบทุกค่าแล้ว — <strong>2²⁵⁶ − 1</strong><br><br>
            นี่คือขอบเขตที่ Bitcoin Private Key อาศัยอยู่ ตัวเลขที่ใหญ่กว่าอะตอมในจักรวาล คือเกราะที่แท้จริงของทุก Satoshi ที่คุณเก็บไว้
        </div>
        <button class="win-restart" onclick="reset(); document.getElementById('winOverlay').classList.remove('active')">
            เริ่มต้นใหม่
        </button>
    </div>

    <script>
        // ══════════════════════════════════════════
        //  CONFIG
        // ══════════════════════════════════════════
        const MAX_LIMIT = (2n ** 256n) - 1n;
        const LOG_MAX   = 256 * Math.log10(2); // log10(2^256) ≈ 77.06

        let currentNum   = 0n;
        let isRunning    = false;
        let animationId;
        let startTime    = 0;
        let startNum     = 0n;
        let lastStatTime = 0;
        let currentSpeed = 0;

        // ══════════════════════════════════════════
        //  BUILD BIT ELEMENTS
        //  256 bits = 32 bytes, grouped as 4 rows × 8 bytes
        //  Bit 255 = MSB (top-left), Bit 0 = LSB (bottom-right)
        // ══════════════════════════════════════════
        const bitEls = new Array(256); // bitEls[i] = element for bit i (0=LSB, 255=MSB)

        function buildByteGroup(byteIndex) {
            // byteIndex: 0=LSB byte, 31=MSB byte
            // bits in this byte: bit (byteIndex*8 + 7) down to (byteIndex*8)
            const bg = document.createElement('div');
            bg.className = 'byte-group';
            const lbl = document.createElement('div');
            lbl.className = 'byte-label';
            lbl.textContent = `B${byteIndex}`;
            bg.appendChild(lbl);
            const bitsDiv = document.createElement('div');
            bitsDiv.className = 'byte-bits';
            // bits within byte: MSB of byte first
            for (let b = 7; b >= 0; b--) {
                const bitIndex = byteIndex * 8 + b;
                const span = document.createElement('span');
                span.className = 'bit';
                span.textContent = '0';
                span.id = `b-${bitIndex}`;
                bitEls[bitIndex] = span;
                bitsDiv.appendChild(span);
            }
            bg.appendChild(bitsDiv);
            return bg;
        }

        function buildRow(containerId, byteStart, byteEnd) {
            // byteStart..byteEnd inclusive, displayed MSB-byte first (descending)
            const container = document.getElementById(containerId);
            for (let by = byteEnd; by >= byteStart; by--) {
                container.appendChild(buildByteGroup(by));
            }
        }

        function initBits() {
            // 8 sub-groups × 4 bytes each (MSB → LSB)
            buildRow('byteGroupsR1', 28, 31); // Byte 31–28
            buildRow('byteGroupsR2', 24, 27); // Byte 27–24
            buildRow('byteGroupsR3', 20, 23); // Byte 23–20
            buildRow('byteGroupsR4', 16, 19); // Byte 19–16
            buildRow('byteGroupsR5', 12, 15); // Byte 15–12
            buildRow('byteGroupsR6',  8, 11); // Byte 11–8
            buildRow('byteGroupsR7',  4,  7); // Byte 7–4
            buildRow('byteGroupsR8',  0,  3); // Byte 3–0
        }

        // ══════════════════════════════════════════
        //  CONCEPT CARD TOGGLE
        // ══════════════════════════════════════════
        function toggleCard(el) {
            el.classList.toggle('open');
        }

        // ══════════════════════════════════════════
        //  DISPLAY UPDATE
        // ══════════════════════════════════════════
        let prevBinStr = '';

        function updateDisplay(forceFull = false) {
            const decEl = document.getElementById('decimalDisplay');
            decEl.textContent = currentNum.toLocaleString();

            // Digit count
            const decStr = currentNum.toString();
            document.getElementById('digitCount').textContent = `${decStr.length} หลัก`;

            // Binary string (no padding)
            const binStr = currentNum.toString(2);
            const len = binStr.length; // actual bits used

            // Update only changed bits for performance
            let activeBits = 0;
            for (let i = 0; i < 256; i++) {
                const bitChar = (i < len && binStr[len - 1 - i] === '1') ? '1' : '0';
                const el = bitEls[i];
                if (!el) continue;
                if (forceFull || el.textContent !== bitChar) {
                    const wasOn = el.classList.contains('on');
                    const nowOn = bitChar === '1';
                    el.textContent = bitChar;
                    if (nowOn !== wasOn) {
                        el.className = nowOn ? 'bit on flash' : 'bit';
                        if (nowOn) setTimeout(() => el.classList.remove('flash'), 120);
                    }
                }
                if (el.textContent === '1') activeBits++;
            }

            document.getElementById('activeBitCount').textContent = activeBits;
            document.getElementById('ctx-ones').textContent  = activeBits + ' bits';
            document.getElementById('ctx-zeros').textContent = (256 - activeBits) + ' bits';
            document.getElementById('ctx-highbit').textContent = len > 0 ? `Bit ${len - 1}` : 'Bit 0';

            // Hex
            const hexStr = currentNum.toString(16).toUpperCase();
            document.getElementById('ctx-hex').textContent = '0x' + (hexStr.length > 16 ? hexStr.substring(0,8)+'…'+hexStr.slice(-4) : hexStr);

            // Progress (log scale)
            updateProgress();
        }

        // ── Device detection (ทำครั้งเดียวตอน load) ──
        const IS_MOBILE = window.innerWidth < 768 || /Mobi|Android/i.test(navigator.userAgent);
        // JS Number มี precision ~15-17 significant digits
        const MAX_DECIMAL_PLACES = IS_MOBILE ? null : 20; // null = ใช้ exponential

        function formatLinearPct(linearPct) {
            if (linearPct === 0)   return '0%';
            if (linearPct >= 100)  return '100%';
            if (linearPct >= 50)   return linearPct.toFixed(3) + '%';
            if (linearPct >= 0.01) return linearPct.toFixed(4) + '%';
            // ทุกค่าที่เล็ก → scientific notation เสมอ ให้เห็นความคืบหน้า
            // คำนวณ exponent จาก BigInt เพื่อ precision จริง
            const decDigits = currentNum.toString().length;           // ≈ log10(N)+1
            const exp = Math.max(0, 77 - (decDigits - 1));           // 77 = log10(2^256)
            // significant digits จาก float
            const sigFigs = linearPct.toExponential(3);              // e.g. "1.234e-45"
            return sigFigs + '%';
        }

        function updateProgress() {
            const HALF = 2n ** 255n;

            // ── Linear Bar ──
            let linearPct = 0;
            if (currentNum === 0n) {
                linearPct = 0;
            } else if (currentNum >= MAX_LIMIT) {
                linearPct = 100;
            } else {
                // ใช้ BigInt scale 10^20 แล้วแปลง float — ได้ precision ~15 digits
                const SCALE = 10n ** 20n;
                const ratio = (currentNum * SCALE) / (MAX_LIMIT + 1n);
                linearPct = (Number(ratio) / 1e20) * 100;
            }

            const linearWidth = Math.max(0, Math.min(100, linearPct));
            // visual hint สำหรับค่าที่เล็กมาก
            const barVisualWidth = currentNum > 0n && linearWidth < 0.005
                ? Math.max(0.3, (currentNum.toString(2).length / 256) * 2.5)
                : linearWidth;

            document.getElementById('linearBar').style.width = barVisualWidth + '%';
            document.getElementById('linearPct').textContent = formatLinearPct(linearPct);

            const linearEl = document.getElementById('linearNote');
            if (currentNum === 0n) {
                linearEl.textContent = '2²⁵⁵ = 50% พอดี | 10⁷⁷ ≈ 86% | ตัวเลขทั่วไปน้อยกว่า 10⁻⁵⁰%';
            } else if (currentNum < HALF) {
                const bitsToHalf = 255 - currentNum.toString(2).length;
                linearEl.textContent = bitsToHalf > 0
                    ? `ยังไม่ถึง 50% — ต้องนับอีก ~2^${bitsToHalf} เท่า จึงถึงครึ่งทาง`
                    : `เกือบถึงครึ่งทาง (2²⁵⁵) แล้ว!`;
            } else if (currentNum < MAX_LIMIT) {
                const remaining = (100 - linearPct);
                linearEl.textContent = `ผ่านครึ่งทางแล้ว! เหลืออีก ${remaining < 0.001 ? remaining.toExponential(2) : remaining.toFixed(3)}%`;
            } else {
                linearEl.textContent = 'ครบ 100% — สิ้นสุดความเป็นไปได้ทั้งหมดของ 256-bit';
            }

            // ── Log Bar — smooth ด้วย log10 จริง ไม่ใช้ bit-length ──
            // log10(currentNum) / log10(2^256) × 100
            // log10(2^256) = 256 × log10(2) ≈ 77.0627
            let logPct = 0;
            if (currentNum > 0n) {
                const numStr = currentNum.toString(); // เลขฐาน 10
                // log10(N) ≈ (digits-1) + log10(leading digits)
                const digits   = numStr.length;
                const leadFloat = parseFloat(numStr.substring(0, Math.min(15, digits)));
                const log10N   = (digits - 1) + Math.log10(leadFloat / Math.pow(10, Math.min(14, digits - 1)));
                const LOG_MAX  = 256 * Math.log10(2); // 77.0627...
                logPct = Math.min(100, (log10N / LOG_MAX) * 100);
            }

            document.getElementById('logBar').style.width = (logPct > 0 ? Math.max(0.3, logPct) : 0) + '%';
            document.getElementById('logPct').textContent = logPct.toFixed(2) + '%';

            const bitsUsed = currentNum > 0n ? currentNum.toString(2).length : 0;
            const bitsLeft = 256 - bitsUsed;
            document.getElementById('logNote').textContent = bitsUsed > 0
                ? `log₁₀(ค่าปัจจุบัน) / log₁₀(2²⁵⁶) — ใช้ ${bitsUsed} Bit จาก 256 (ยังขาดอีก ${bitsLeft} Bit = ห่าง 2^${bitsLeft} เท่า!)`
                : 'เริ่มต้นที่ 0 — log(0) ไม่นิยาม';
        }

        function toSup(n) {
            return n.toString().split('').map(c=>('⁰¹²³⁴⁵⁶⁷⁸⁹')[+c]||c).join('');
        }

        // ══════════════════════════════════════════
        //  STATS
        // ══════════════════════════════════════════
        function updateStats(now) {
            const durationSec = (now - startTime) / 1000;
            if (durationSec <= 0) return;

            const diffNum = Number(currentNum - startNum);
            currentSpeed  = Math.floor(diffNum / durationSec);
            document.getElementById('speedDisplay').textContent = currentSpeed.toLocaleString();

            const remaining = MAX_LIMIT - currentNum;
            const speedBI   = BigInt(Math.floor(currentSpeed));
            if (speedBI <= 0n) return;

            const secsLeft = remaining / speedBI;
            if (secsLeft < 31536000n) {
                document.getElementById('timeEstDisplay').textContent = Number(secsLeft).toLocaleString() + ' วินาที';
                document.getElementById('timeEstDisplay').style.color = '#ff4444';
            } else {
                const yearsLeft = secsLeft / (60n * 60n * 24n * 365n);
                document.getElementById('timeEstDisplay').textContent = formatYears(yearsLeft);
                document.getElementById('timeEstDisplay').style.color = '';
            }
        }

        function formatYears(yearsBI) {
            const s   = yearsBI.toString();
            const len = s.length;
            if (len < 7) return parseInt(s).toLocaleString() + ' ปี';
            const exp = len - 1;
            const lead = s.substring(0, Math.min(3, len));
            return `${lead[0]}.${lead.substring(1)}×10${toSup(exp)} ปี`;
        }

        // ══════════════════════════════════════════
        //  ANALOGY
        // ══════════════════════════════════════════
        const analogies = [
            // ── หลักหน่วย–สิบ ──
            { val: 2n,   icon:'👫', title:'2',          desc:'จำนวน Binary Digit ที่เป็นไปได้ใน 1 Bit: 0 หรือ 1', ctx:'รากฐานของระบบ Binary ทั้งหมด' },
            { val: 8n,   icon:'🎱', title:'8',           desc:'8 Bit = 1 Byte — หน่วยพื้นฐานของข้อมูลดิจิทัล', ctx:'1 ตัวอักษร ASCII = 1 Byte' },
            { val: 10n,  icon:'🖐️', title:'10',          desc:'นิ้วมือของคนเรา — ต้นกำเนิดระบบเลขฐาน 10 ที่เราคุ้นเคย', ctx:'ฐาน 10 มาจาก 10 นิ้ว' },
            { val: 26n,  icon:'🔤', title:'26',          desc:'จำนวนตัวอักษรภาษาอังกฤษ A–Z', ctx:'5 Bit ก็เพียงพอเก็บได้ (2⁵=32)' },
            { val: 32n,  icon:'💻', title:'32',          desc:'จำนวนสูงสุดของระบบ 32-bit (2⁵) — เหมือน Integer ใน C', ctx:'2⁵ = 32' },
            { val: 52n,  icon:'🃏', title:'52',          desc:'จำนวนไพ่ในสำรับมาตรฐาน 1 สำรับ', ctx:'ผสมไพ่ 52 ใบ มี 52! แบบ (≈8×10⁶⁷)' },
            { val: 64n,  icon:'♟️', title:'64',          desc:'จำนวนช่องบนกระดานหมากรุก 8×8 ช่อง', ctx:'2⁶ = 64' },
            { val: 88n,  icon:'🎹', title:'88',          desc:'จำนวนคีย์เปียโนมาตรฐาน', ctx:'ครอบคลุม 7.25 ออคเทฟ' },
            { val: 100n, icon:'💯', title:'100',         desc:'1 ร้อย — จำนวนปีใน 1 ศตวรรษ / เซ็นต์ใน 1 ดอลลาร์', ctx:'10²' },
            { val: 128n, icon:'🔑', title:'128',         desc:'2⁷ = 128 — จำนวนตัวอักษรในมาตรฐาน ASCII ดั้งเดิม', ctx:'2⁷ และเป็น 1 Bit ของ AES-128' },
            { val: 180n, icon:'📐', title:'180',         desc:'องศาในมุม 180° — เส้นตรง / ครึ่งวงกลม', ctx:'π เรเดียน' },
            { val: 256n, icon:'🖼️', title:'256',         desc:'2⁸ = 256 — จำนวนสีในระบบ 8-bit (palette เก่า) / ค่า Byte สูงสุดคือ 255', ctx:'2⁸ — 1 Byte มี 256 ค่า' },

            // ── หลักพัน ──
            { val: 1000n,     icon:'📏', title:'1,000',      desc:'1 กิโลเมตร = 1,000 เมตร / จำนวนปีใน 1 สหัสวรรษ', ctx:'10³' },
            { val: 1024n,     icon:'💾', title:'1,024',      desc:'1 Kilobyte (KiB) = 1,024 Byte — มาตรฐานคอมพิวเตอร์', ctx:'2¹⁰ = 1,024' },
            { val: 2048n,     icon:'📖', title:'2,048',      desc:'จำนวนคำใน BIP-39 Wordlist มาตรฐาน — ใช้สร้าง Seed Phrase ของ Bitcoin!', ctx:'2¹¹ = 2,048 คำ' },
            { val: 6000n,     icon:'🌲', title:'6,000',      desc:'จำนวนปีโดยประมาณที่มนุษย์มีอารยธรรมที่บันทึกไว้', ctx:'ตั้งแต่อารยธรรมสุเมเรียน' },
            { val: 7000n,     icon:'🗣️', title:'7,000',      desc:'จำนวนภาษาที่ยังมีคนพูดอยู่บนโลกในปัจจุบัน', ctx:'UNESCO บันทึกไว้' },
            { val: 8192n,     icon:'🔐', title:'8,192',      desc:'ขนาด RSA Key ที่ปลอดภัยสูง (8,192 bit) เทียบกับ Bitcoin ที่ใช้แค่ 256 bit', ctx:'2¹³ = 8,192' },

            // ── หลักหมื่น–แสน ──
            { val: 10000n,    icon:'📅', title:'10,000',     desc:'10,000 วัน ≈ 27.4 ปี — ช่วงชีวิตที่กระตือรือร้นของมนุษย์ส่วนใหญ่', ctx:'10⁴' },
            { val: 21000n,    icon:'₿',  title:'21,000',     desc:'จำนวน BTC สูงสุดที่จะมีในโลก (ล้านหน่วย) = 21 ล้าน Bitcoin', ctx:'Hard Cap ของ Bitcoin' },
            { val: 32768n,    icon:'🎮', title:'32,768',     desc:'ขนาด RAM ของ Nintendo NES (32 KiB) — เกม Super Mario Bros ใช้แค่นี้!', ctx:'2¹⁵ = 32,768' },
            { val: 65536n,    icon:'🖥️', title:'65,536',     desc:'จำนวนพอร์ตเครือข่าย (Network Port) ทั้งหมด (0–65535)', ctx:'2¹⁶ = 65,536' },
            { val: 86400n,    icon:'🌅', title:'86,400',     desc:'จำนวนวินาทีใน 1 วัน (24×60×60)', ctx:'วันละ 86,400 วินาที' },
            { val: 100000n,   icon:'🏟️', title:'100,000',    desc:'ความจุสนามฟุตบอลขนาดใหญ่ที่สุด เช่น Rungrado (เกาหลีเหนือ 114,000 ที่นั่ง)', ctx:'10⁵' },

            // ── หลักล้าน ──
            { val: 1000000n,      icon:'🎫', title:'1 ล้าน',       desc:'โอกาสถูกรางวัลที่ 1 สลากกินแบ่งรัฐบาลไทย (1 ใน 1,000,000)', ctx:'10⁶' },
            { val: 2100000n,      icon:'₿',  title:'2.1 ล้าน',     desc:'จำนวน Satoshi ใน 0.021 BTC / จำนวน Bitcoin Block ที่คาดว่าจะขุดได้ถึงปี 2140', ctx:'1 BTC = 100,000,000 Satoshi' },
            { val: 8000000n,      icon:'🗼', title:'8 ล้าน',        desc:'จำนวนประชากรของกรุงเทพมหานคร (ในเขต)', ctx:'~10.5 ล้านรวม ปริมณฑล' },
            { val: 10000000n,     icon:'🇸🇪', title:'10 ล้าน',      desc:'จำนวนประชากรประเทศสวีเดน / ฮังการี / เบลเยียม / กรีซ', ctx:'10⁷' },
            { val: 14000000n,     icon:'🇰🇭', title:'14 ล้าน',      desc:'จำนวนประชากรประเทศกัมพูชา', ctx:'~14 ล้านคน' },
            { val: 17000000n,     icon:'🇳🇱', title:'17 ล้าน',      desc:'จำนวนประชากรประเทศเนเธอร์แลนด์', ctx:'~17.9 ล้านคน' },
            { val: 22000000n,     icon:'🇦🇺', title:'22 ล้าน',      desc:'จำนวนประชากรประเทศออสเตรเลีย / ซีเรีย', ctx:'~26 ล้านคน' },
            { val: 30000000n,     icon:'🇲🇾', title:'30 ล้าน',      desc:'จำนวนประชากรประเทศมาเลเซีย / เปรู', ctx:'~33 ล้านคน' },
            { val: 33000000n,     icon:'🇸🇦', title:'33 ล้าน',      desc:'จำนวนประชากรซาอุดีอาระเบีย', ctx:'~35 ล้านคน' },
            { val: 45000000n,     icon:'🇦🇷', title:'45 ล้าน',      desc:'จำนวนประชากรประเทศอาร์เจนตินา / แอลจีเรีย / ยูเครน', ctx:'~46 ล้านคน' },
            { val: 54000000n,     icon:'🇰🇷', title:'54 ล้าน',      desc:'จำนวนประชากรประเทศเกาหลีใต้', ctx:'~51 ล้านคน' },
            { val: 60000000n,     icon:'🇮🇹', title:'60 ล้าน',      desc:'จำนวนประชากรประเทศไทย / อิตาลี / แอฟริกาใต้', ctx:'~66–70 ล้านคน' },
            { val: 67000000n,     icon:'🇬🇧', title:'67 ล้าน',      desc:'จำนวนประชากรสหราชอาณาจักร (UK)', ctx:'England+Scotland+Wales+N.Ireland' },
            { val: 84000000n,     icon:'🇩🇪', title:'84 ล้าน',      desc:'จำนวนประชากรประเทศเยอรมนี', ctx:'ใหญ่สุดในสหภาพยุโรป' },
            { val: 100000000n,    icon:'🇧🇩', title:'100 ล้าน',     desc:'จำนวนประชากรประเทศไทย (อย่างเป็นทางการ ~72M) / บังกลาเทศ ~170M / เอธิโอเปีย ~126M', ctx:'10⁸' },
            { val: 126000000n,    icon:'🇯🇵', title:'126 ล้าน',     desc:'จำนวนประชากรประเทศญี่ปุ่น', ctx:'~125 ล้านคน (ลดลงต่อเนื่อง)' },
            { val: 145000000n,    icon:'🇷🇺', title:'145 ล้าน',     desc:'จำนวนประชากรประเทศรัสเซีย', ctx:'ประเทศที่มีพื้นที่ใหญ่ที่สุดในโลก' },
            { val: 170000000n,    icon:'🇧🇩', title:'170 ล้าน',     desc:'จำนวนประชากรประเทศบังกลาเทศ', ctx:'หนาแน่นที่สุดประเทศหนึ่งของโลก' },
            { val: 220000000n,    icon:'🇧🇷', title:'220 ล้าน',     desc:'จำนวนประชากรประเทศบราซิล', ctx:'ใหญ่ที่สุดในอเมริกาใต้' },
            { val: 220000000n,    icon:'🇵🇰', title:'220 ล้าน',     desc:'จำนวนประชากรประเทศปากีสถาน', ctx:'~230 ล้านคน' },
            { val: 270000000n,    icon:'🇺🇸', title:'270 ล้าน',     desc:'จำนวนประชากรสหรัฐอเมริกา', ctx:'~335 ล้านคน ปัจจุบัน' },
            { val: 340000000n,    icon:'🇺🇸', title:'340 ล้าน',     desc:'จำนวนประชากรสหรัฐอเมริกา (ปัจจุบัน)', ctx:'10⁸ × 3.4' },
            { val: 384400000n,    icon:'🌑', title:'384 ล้าน (กม)', desc:'ระยะทางเฉลี่ยจากโลกถึงดวงจันทร์ — 384,400 กิโลเมตร', ctx:'แสงใช้เวลา ~1.3 วินาที' },
            { val: 440000000n,    icon:'🎵', title:'440 ล้าน',     desc:'ความถี่ของโน้ต A4 คือ 440 Hz — มาตรฐานดนตรีสากล', ctx:'440 Hz = LA กลาง' },
            { val: 500000000n,    icon:'🐦', title:'500 ล้าน',     desc:'จำนวนนกทั้งหมดในยุโรป / จำนวนผู้ใช้ Twitter (X) ในช่วงพีค', ctx:'5×10⁸' },
            { val: 750000000n,    icon:'🌐', title:'750 ล้าน',     desc:'จำนวนผู้ใช้อินเทอร์เน็ตในทวีปเอเชียตะวันออก', ctx:'~7.5×10⁸' },

            // ── หลักพันล้าน ──
            { val: 1000000000n,       icon:'⏱️', title:'1 พันล้าน',    desc:'จำนวนวินาทีใน 31.7 ปี / 1 Billion = 1 "สหัสล้าน" ในภาษาไทยทางการ', ctx:'10⁹ = 1 Billion' },
            { val: 1400000000n,       icon:'🇨🇳', title:'1.4 พันล้าน', desc:'จำนวนประชากรประเทศจีน — ประชากรมากที่สุดในโลก (กำลังลดลง)', ctx:'~1.41 พันล้านคน' },
            { val: 1440000000n,       icon:'🇮🇳', title:'1.44 พันล้าน',desc:'จำนวนประชากรประเทศอินเดีย — แซงจีนขึ้นเป็นอันดับ 1 ปี 2023', ctx:'ใหญ่ที่สุดในโลกแล้ว' },
            { val: 2100000000n,       icon:'📱', title:'2.1 พันล้าน', desc:'จำนวนผู้ใช้ WhatsApp ทั่วโลก', ctx:'~2.1 Billion users' },
            { val: 3000000000n,       icon:'🌐', title:'3 พันล้าน',   desc:'จำนวนผู้ใช้ Facebook/Meta ที่ active ต่อเดือน', ctx:'~3 Billion MAU' },
            { val: 4000000000n,       icon:'🌍', title:'4 พันล้าน',   desc:'จำนวนปีที่โลกมีสิ่งมีชีวิต — ชีวิตแรกเกิดขึ้น ~3.8-4 พันล้านปีก่อน', ctx:'~4 Billion ปี' },
            { val: 4543000000n,       icon:'🌏', title:'4.5 พันล้าน', desc:'อายุของโลก (Earth) = 4,543 ล้านปี', ctx:'วัดจาก radioactive dating' },
            { val: 6000000000n,       icon:'🌕', title:'6 พันล้าน',   desc:'ระยะทางจากโลกถึงดาวเสาร์ที่ไกลที่สุด (~6 พันล้าน กม.)', ctx:'1.2 ชั่วโมงแสง' },
            { val: 7900000000n,       icon:'👤', title:'7.9 พันล้าน', desc:'ประชากรโลกทุกคนที่มีชีวิตอยู่ในปัจจุบัน (ปี 2024: ~8.1 พันล้าน)', ctx:'~8.1×10⁹ คน' },
            { val: 8100000000n,       icon:'🌍', title:'8.1 พันล้าน', desc:'ประชากรโลก ณ ปี 2024 — เพิ่มขึ้น ~80 ล้านต่อปี', ctx:'UN World Population' },
            { val: 13800000000n,      icon:'🌌', title:'13.8 พันล้าน',desc:'อายุของจักรวาล — Big Bang เกิดขึ้น 13.8 พันล้านปีก่อน', ctx:'วัดจาก CMB radiation' },

            // ── 10¹⁰ – 10¹² ──
            { val: 10n**10n,              icon:'⭐', title:'10¹⁰',         desc:'10 พันล้าน — จำนวนเซลล์ประสาท (Neuron) ในสมองมนุษย์ และจำนวนปีที่ดวงอาทิตย์จะยังส่องแสงได้', ctx:'10¹⁰' },
            { val: 40000000000n,          icon:'🌡️', title:'40 พันล้าน',   desc:'จำนวนแบคทีเรียในร่างกายมนุษย์ 1 คน — มากกว่าเซลล์มนุษย์เอง!', ctx:'~3.8×10¹³ จริงๆ' },
            { val: 10n**11n,              icon:'✨', title:'100 พันล้าน',  desc:'จำนวนดาวในกาแล็กซีทางช้างเผือก — และจำนวน synapse ในสมองมนุษย์', ctx:'10¹¹ = 100 Billion' },
            { val: 150000000000n,         icon:'☀️', title:'150 พันล้าน', desc:'ระยะทางจากโลกถึงดวงอาทิตย์ — 150 ล้านกิโลเมตร (1 AU)', ctx:'แสงใช้เวลา 8.3 นาที' },
            { val: 10n**12n,              icon:'💵', title:'10¹²',         desc:'1 Trillion ดอลลาร์ — GDP ของหลายประเทศ เช่น เกาหลีใต้ / ออสเตรเลีย', ctx:'10¹² = 1 Trillion' },
            { val: 2100000000000n,        icon:'₿',  title:'2.1 × 10¹²',  desc:'จำนวน Satoshi ทั้งหมดใน Bitcoin 21 ล้าน BTC (21M × 10⁸ Satoshi)', ctx:'21,000,000 × 100,000,000' },

            // ── 10¹³ – 10¹⁶ ──
            { val: 10n**13n,              icon:'🧬', title:'10¹³',         desc:'จำนวนเซลล์มนุษย์ในร่างกาย 1 คน และจำนวนคู่เบส (base pair) ใน DNA มนุษย์ทั้งหมด', ctx:'~37 Trillion cells' },
            { val: 10n**14n,              icon:'🔬', title:'10¹⁴',         desc:'จำนวนไวรัสในร่างกายมนุษย์ (Virome) — มากกว่าเซลล์และแบคทีเรียรวมกัน', ctx:'10¹⁴' },
            { val: 10n**15n,              icon:'🐛', title:'10¹⁵',         desc:'จำนวนแมลงทั้งหมดบนโลกโดยประมาณ / จำนวน Petabyte ของข้อมูลที่สร้างต่อวันทั่วโลก', ctx:'10¹⁵ = 1 Peta' },
            { val: 10n**16n,              icon:'🐜', title:'10¹⁶',         desc:'จำนวนมดทั้งหมดบนโลก (ประมาณการใหม่ปี 2022: 2×10¹⁶ ตัว)', ctx:'20 Quadrillion ตัว' },
            { val: 31536000000000000n,    icon:'⌛', title:'31.5 × 10¹⁵', desc:'จำนวนวินาทีตั้งแต่ Big Bang จนถึงปัจจุบัน (~13.8 พันล้านปี)', ctx:'4.35×10¹⁷ วินาที จริงๆ' },

            // ── 10¹⁷ – 10¹⁸ ──
            { val: 10n**17n,              icon:'🔭', title:'10¹⁷',         desc:'ระยะทางจากโลกถึงดาวฤกษ์ใกล้ที่สุด Proxima Centauri (4.24 ปีแสง) ในหน่วยเมตร', ctx:'~4×10¹⁶ เมตร' },
            { val: 10n**18n,              icon:'🏖️', title:'10¹⁸',         desc:'จำนวนเม็ดทรายทุกเม็ดบนโลก — ทุกชายหาด ทะเลทราย และก้นทะเล', ctx:'1 Quintillion = 10¹⁸' },
            { val: 10n**19n,              icon:'💻', title:'10¹⁹',         desc:'จำนวน Hash ที่ Bitcoin Network คำนวณได้ต่อวัน (ประมาณ) ณ ปี 2024', ctx:'~7×10²⁰ H/s ทั้ง Network' },

            // ── 10²⁰ – 10²³ ──
            { val: 10n**20n,              icon:'⚡', title:'10²⁰',         desc:'จำนวน Hash ที่ Bitcoin Network ทำได้ต่อวินาที (~700 EH/s = 7×10²⁰ H/s)', ctx:'700 Exahash/s' },
            { val: 10n**21n,              icon:'⚛️', title:'10²¹',         desc:'จำนวนอะตอมในเม็ดทราย 1 เม็ด (ซิลิกา SiO₂) / จำนวนดาวในจักรวาลที่สังเกตได้', ctx:'~10²⁴ ดาวในจักรวาล' },
            { val: 10n**22n,              icon:'🌠', title:'10²²',         desc:'จำนวนดาวฤกษ์ทั้งหมดในจักรวาลที่สังเกตได้ (~200 พันล้านกาแล็กซี × 10¹¹ ดาว)', ctx:'~2×10²³ ดาว' },
            { val: 6n*(10n**23n),         icon:'⚗️', title:'6.02×10²³',    desc:'เลขอาโวกาโดร — จำนวนโมเลกุลใน 1 โมลของสาร เช่น น้ำ 18g มีโมเลกุล H₂O 6.02×10²³ โมเลกุล', ctx:'Avogadro Number — 10⁻²³ m คือขนาดอะตอม' },

            // ── 10²⁴ – 10²⁷ ──
            { val: 10n**24n,              icon:'🌊', title:'10²⁴',         desc:'จำนวนหยดน้ำในมหาสมุทรทั้งหมด / 1 Septillion / หน่วยคำนำหน้า SI: Yotta', ctx:'1 Yotta = 10²⁴' },
            { val: 10n**25n,              icon:'🌍', title:'10²⁵',         desc:'มวลของโลกในหน่วยโปรตอน (~6×10²⁴ kg ÷ 1.67×10⁻²⁷ kg/proton)', ctx:'~3.6×10⁵¹ proton' },
            { val: 10n**27n,              icon:'🧍', title:'10²⁷',         desc:'จำนวนอะตอมในร่างกายมนุษย์ 1 คน (~7×10²⁷ อะตอม ส่วนใหญ่เป็น H, O, C)', ctx:'7,000 Yotta-atoms' },
            { val: 10n**28n,              icon:'🌐', title:'10²⁸',         desc:'จำนวนอะตอมในน้ำทั้งหมดในมหาสมุทรโลก (~1.4×10²¹ กก. ÷ น้ำหนักโมเลกุล)', ctx:'~5×10⁴⁶ โมเลกุล H₂O' },
            { val: 10n**30n,              icon:'☀️', title:'10³⁰',         desc:'มวลของดวงอาทิตย์ในหน่วยกิโลกรัม (1.989×10³⁰ kg) — ดวงอาทิตย์หนักกว่าโลก 333,000 เท่า', ctx:'1 Solar Mass = 1.989×10³⁰ kg' },

            // ── 10³¹ – 10⁴⁰ ──
            { val: 10n**32n,              icon:'🧮', title:'10³²',         desc:'ค่า 2²⁵⁶ / 2¹²⁸ — ระยะห่างระหว่างครึ่งทางกับจุดสิ้นสุด ยังใหญ่กว่าจำนวนอะตอมในโลกมาก', ctx:'ยังห่างไกลมาก' },
            { val: 10n**34n,              icon:'🔬', title:'10³⁴',         desc:'จำนวน Planck Time ใน 1 วินาที (1 วินาที / 5.39×10⁻⁴⁴ s) — หน่วยเวลาเล็กที่สุดที่มีความหมาย', ctx:'10³⁴ Planck Time / วินาที' },
            { val: 10n**36n,              icon:'⚡', title:'10³⁶',         desc:'จำนวนวินาทีที่ดวงอาทิตย์จะส่องแสงได้ทั้งหมดตลอดอายุขัย (10¹⁰ ปี × 3.15×10⁷ s/ปี)', ctx:'~3×10¹⁷ วินาที จริงๆ' },
            { val: 10n**38n,              icon:'🌡️', title:'10³⁸',         desc:'จำนวนการเคลื่อนที่ที่เป็นไปได้ในหมากรุก (~10⁴³) และ Go (~10¹⁷⁰) — เพื่อเปรียบเทียบ', ctx:'Shannon Number ~10⁴³ (Chess)' },
            { val: 10n**40n,              icon:'♟️', title:'10⁴⁰',         desc:'จำนวน Planck Volume ในจักรวาลที่สังเกตได้ (4×10¹⁸⁵ Planck Volumes จริงๆ ใหญ่กว่านี้มาก)', ctx:'เข้าสู่ย่านที่ไร้การเปรียบเทียบ' },

            // ── 10⁴¹ – 10⁵⁰ ──
            { val: 10n**42n,              icon:'🎲', title:'10⁴²',         desc:'จำนวนการจัดเรียงไพ่ 52 ใบทั้งหมด (52!) ≈ 8×10⁶⁷ — แต่ละครั้งที่สับไพ่ คุณสร้างลำดับที่อาจไม่เคยเกิดขึ้นมาก่อน', ctx:'52! ≈ 8.07×10⁶⁷' },
            { val: 10n**44n,              icon:'⚛️', title:'10⁴⁴',         desc:'จำนวนอนุภาคในจักรวาลที่ผ่านการประมาณการต่างๆ (Proton, Neutron, Electron รวมกัน)', ctx:'~10⁸⁰ baryon จริงๆ' },
            { val: 10n**47n,              icon:'🌊', title:'10⁴⁷',         desc:'จำนวนโมเลกุล H₂O ในมหาสมุทรทั้งหมดบนโลก — น้ำ 1.4×10²¹ กก. ÷ 3×10⁻²⁶ กก./โมเลกุล', ctx:'~4.6×10⁴⁶ โมเลกุล' },
            { val: 10n**50n,              icon:'🌍', title:'10⁵⁰',         desc:'จำนวนอะตอมทั้งหมดในโลก — ทั้งเปลือกโลก แมนเทิล แก่นโลก รวมกัน', ctx:'~10⁵⁰ อะตอม' },

            // ── 10⁵¹ – 10⁶⁰ ──
            { val: 10n**51n,              icon:'🌙', title:'10⁵¹',         desc:'จำนวนอะตอมในดวงจันทร์ทั้งดวง', ctx:'มวลดวงจันทร์ ~7.3×10²² kg' },
            { val: 10n**54n,              icon:'🪐', title:'10⁵⁴',         desc:'จำนวนอะตอมในดาวพฤหัสบดี — ดาวเคราะห์ที่ใหญ่ที่สุดในระบบสุริยะ', ctx:'~1.9×10²⁷ kg' },
            { val: 10n**57n,              icon:'☀️', title:'10⁵⁷',         desc:'จำนวนอะตอมในดวงอาทิตย์ — ดวงอาทิตย์มีมวล ~2×10³⁰ kg ส่วนใหญ่เป็น Hydrogen', ctx:'~1.2×10⁵⁷ อะตอม H' },
            { val: 10n**60n,              icon:'🌟', title:'10⁶⁰',         desc:'จำนวนอะตอมในดาวฤกษ์ที่ใหญ่ที่สุดที่รู้จัก เช่น UY Scuti (ใหญ่กว่าดวงอาทิตย์ ~1,700 เท่า)', ctx:'~10⁵⁷ × 1,700 ≈ 1.7×10⁶⁰' },

            // ── 10⁶¹ – 10⁷⁰ ──
            { val: 10n**63n,              icon:'🌌', title:'10⁶³',         desc:'จำนวนอะตอมในกาแล็กซีขนาดเล็ก (Dwarf Galaxy) ที่มีดาว ~10⁹ ดวง', ctx:'10⁹ ดาว × 10⁵⁷ อะตอม/ดาว' },
            { val: 10n**67n,              icon:'🌌', title:'10⁶⁷',         desc:'จำนวนอะตอมในกาแล็กซีทางช้างเผือก — ~400,000 ล้านดาว × 10⁵⁷ อะตอม/ดาว', ctx:'4×10¹¹ ดาว × 10⁵⁷ = 4×10⁶⁸' },
            { val: 10n**70n,              icon:'🪐', title:'10⁷⁰',         desc:'จำนวนอะตอมในกาแล็กซีขนาดยักษ์ Andromeda (M31) — ใหญ่กว่าทางช้างเผือก ~2 เท่า', ctx:'~10⁶⁸–10⁶⁹' },

            // ── 10⁷¹ – 10⁷⁷ — ย่าน Bitcoin Private Key ──
            { val: 10n**72n,              icon:'🔑', title:'10⁷²',         desc:'เข้าสู่ย่าน Bitcoin Private Key Space — 2²⁵⁶ ≈ 1.16×10⁷⁷ นี่คือขนาดของความปลอดภัยของกระเป๋า Bitcoin ทุกใบ', ctx:'ใกล้แล้ว!' },
            { val: 10n**75n,              icon:'🛡️', title:'10⁷⁵',         desc:'ขนาดที่ใกล้เคียงกับ 2²⁵⁶ — นักขุดทั้งโลกรวมกันทำ ~7×10²⁰ Hash/วิ ต้องใช้ 10⁴⁹ ปีเพื่อสุ่มครบ', ctx:'ยังห่างอีก 100 เท่า' },

            // 2²⁵⁵ ≈ 5.79×10⁷⁶ — ต้องอยู่ก่อน 10⁷⁷ เพราะค่าน้อยกว่า
            { val: 57896044618658097711785492504343953926634992332820282019728792003956564819968n,
              icon:'🏳️', title:'2²⁵⁵ — 1 Bit ก่อนจบ',
              desc:'ผ่านครึ่งทางสุดท้ายแล้ว Bit 255 (MSB) กลายเป็น 1 — เหลืออีก 2²⁵⁵ ค่า จึงจะครบ 2²⁵⁶',
              ctx:'สังเกตว่า Bit ซ้ายสุดติดสว่างแล้ว' },

            // 10⁷⁷ > 2²⁵⁵ — อยู่หลัง เพื่อ override 2²⁵⁵ ได้
            { val: 10n**77n,              icon:'🌌✨', title:'10⁷⁷ ≈ 2²⁵⁶',  desc:'จำนวนอะตอมในจักรวาลที่แสงเดินทางถึง (Observable Universe) — คือจักรวาลที่เราสังเกตได้จริง ไม่ใช่จักรวาลทั้งหมด และตัวเลขนี้ใกล้เคียงกับ 2²⁵⁶ มากที่สุดที่มนุษย์เปรียบเทียบได้', ctx:'2²⁵⁶ ≈ 1.16×10⁷⁷ — เท่ากับอะตอมที่แสงเดินทางถึงได้' },

            // ── 10⁷⁸ – 10⁸⁰ ──
            { val: 10n**78n,              icon:'🔐', title:'10⁷⁸',         desc:'2²⁵⁶ ≈ 1.16×10⁷⁷ — ตอนนี้คุณผ่านขีดจำกัดของ Bitcoin Private Key Space แล้ว! ตัวเลขนี้ใหญ่กว่าขีดจำกัด 256-bit', ctx:'เกิน 2²⁵⁶ แล้ว!' },
            { val: 10n**80n,              icon:'🪐', title:'10⁸⁰',         desc:'จำนวนอะตอม (Baryon) ทั้งหมดในจักรวาลที่สังเกตได้ — นี่คือขีดจำกัดบนของ "สิ่งที่นับได้" ในจักรวาล', ctx:'Observable Universe Baryons' },

            // ── เส้นชัย (ต้องอยู่ท้ายสุด เพื่อ override ทุก entry ก่อนหน้า) ──
            { val: (2n**256n) - 1n - 10000000n, icon:'🏁', title:'เส้นชัย — 10 ล้านก้าวสุดท้าย',
              desc:'เหลืออีกเพียง 10 ล้านค่าจากจุดสิ้นสุดของ 256-bit — กด Start แล้วดูตัวเลขวิ่งสู่ขีดจำกัดสูงสุดของ Bitcoin Private Key Space',
              ctx:'เกือบสิ้นสุด entropy ของ 256-bit แล้ว!' },
        ];

        function checkAnalogy() {
            let found = null;
            for (const a of analogies) {
                if (currentNum >= a.val) found = a;
            }
            const box = document.getElementById('analogyBox');
            if (found && box.dataset.last !== found.title) {
                box.dataset.last = found.title;
                box.innerHTML = `
                    <div class="analogy-icon">${found.icon}</div>
                    <div class="analogy-title">ผ่านหลัก: ${found.title}</div>
                    <div class="analogy-desc">${found.desc}</div>
                    <div class="analogy-context">${found.ctx}</div>
                `;
                box.style.borderColor = '#F7931A';
                setTimeout(() => { box.style.borderColor = ''; }, 600);
            }
        }

        // ══════════════════════════════════════════
        //  MAIN LOOP
        // ══════════════════════════════════════════
        function loop() {
            if (!isRunning) return;
            const now = performance.now();

            // Run as many increments as possible in 12ms slice
            const sliceEnd = now + 12;
            while (performance.now() < sliceEnd) {
                if (currentNum >= MAX_LIMIT) {
                    currentNum = MAX_LIMIT;
                    finishGame();
                    return;
                }
                currentNum += 1n;
            }

            updateDisplay();
            checkAnalogy();

            if (now - lastStatTime > 800) {
                updateStats(now);
                lastStatTime = now;
            }

            animationId = requestAnimationFrame(loop);
        }

        // ══════════════════════════════════════════
        //  CONTROL FUNCTIONS
        // ══════════════════════════════════════════
        function toggleCounting() {
            if (isRunning) stopCounting(); else startCounting();
        }

        function startCounting() {
            if (currentNum >= MAX_LIMIT) return;
            isRunning  = true;
            startTime  = performance.now();
            startNum   = currentNum;
            lastStatTime = startTime;
            const btn = document.getElementById('btnToggle');
            btn.textContent = 'STOP COUNTING';
            btn.classList.add('running');
            loop();
        }

        function stopCounting() {
            isRunning = false;
            cancelAnimationFrame(animationId);
            const btn = document.getElementById('btnToggle');
            if (currentNum < MAX_LIMIT) {
                btn.textContent = 'CONTINUE COUNTING';
                btn.classList.remove('running');
            }
            saveData();
        }

        function reset() {
            stopCounting();
            currentNum = 0n;
            document.getElementById('speedDisplay').textContent  = '0';
            document.getElementById('timeEstDisplay').textContent = '∞';
            document.getElementById('timeEstDisplay').style.color = '';
            const btn = document.getElementById('btnToggle');
            btn.textContent = 'START COUNTING';
            btn.classList.remove('running');
            btn.disabled = false;
            document.getElementById('analogyBox').dataset.last = '';
            document.getElementById('analogyBox').innerHTML = `
                <div class="analogy-icon">🌱</div>
                <div class="analogy-title">เริ่มต้นใหม่</div>
                <div class="analogy-desc">กด START COUNTING เพื่อเริ่มนับจาก 0</div>
                <div class="analogy-context">กล่องนี้จะเปลี่ยนเมื่อผ่านหลักสำคัญ</div>
            `;
            updateDisplay(true);
            saveData();
        }

        function warpTo(valStr) {
            stopCounting();
            currentNum = BigInt(valStr);
            if (currentNum >= MAX_LIMIT) currentNum = MAX_LIMIT;
            document.getElementById('btnToggle').disabled = false;
            document.getElementById('btnToggle').textContent = 'START COUNTING';
            document.getElementById('btnToggle').classList.remove('running');
            document.getElementById('analogyBox').dataset.last = '';
            updateDisplay(true);
            checkAnalogy();
        }

        function warpToMax() {
            warpTo((MAX_LIMIT - 10000000n).toString());
            document.getElementById('btnToggle').textContent = 'START FINAL COUNTDOWN';
        }

        function finishGame() {
            stopCounting();
            updateDisplay(true);
            document.getElementById('winOverlay').classList.add('active');
            const btn = document.getElementById('btnToggle');
            btn.textContent = 'MAXIMUM REACHED';
            btn.disabled = true;
            saveData();
        }

        function saveData() {
            try { localStorage.setItem('btc_counter_val', currentNum.toString()); } catch(e) {}
        }

        // ══════════════════════════════════════════
        //  INIT
        // ══════════════════════════════════════════
        function init() {
            initBits();
            try {
                const saved = localStorage.getItem('btc_counter_val');
                if (saved) {
                    currentNum = BigInt(saved);
                    if (currentNum >= MAX_LIMIT) { currentNum = MAX_LIMIT; finishGame(); }
                    else { updateDisplay(true); checkAnalogy(); }
                }
            } catch(e) {}
        }

        init();
    </script>
</body>
</html>