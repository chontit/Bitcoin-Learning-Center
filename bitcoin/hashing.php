<?php
// Filename: hashing_enhanced.php
// Theme: SHA-256 Interactive Simulator — Enhanced Edition
// Author: Chollatis Bitcoiner
// Version: 2.4 — Layout Refactoring & 50% Avalanche Rule Explanation
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SHA-256 Simulator | Hashing Mechanics</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='50' fill='%23f7931a'/><text x='50' y='70' font-size='65' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='bold'>₿</text></svg>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Sarabun:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
:root {
    --bg: #050a0e;
    --bg2: #0c1520;
    --glass: rgba(16, 26, 40, 0.85);
    --glass2: rgba(255,255,255,0.06);
    --cyan: #00f3ff;
    --cyan2: #00c8d4;
    --green: #39ff14;
    --green2: #28cc0f;
    --red: #ff3860;
    --orange: #F7931A;
    --orange2: #ffb347;
    --yellow: #fbbf24;
    --purple: #a78bfa;
    --text: #c9d1d9;
    --text2: #8b949e;
    --text3: #3d4f63;
    --border: rgba(0,243,255,0.2);
    --border2: rgba(0,243,255,0.4);
    --radius: 12px;
    --radius-sm: 6px;
    --shadow: 0 8px 32px rgba(0,0,0,0.7);
    --glow-cyan: 0 0 20px rgba(0,243,255,0.3);
    --glow-green: 0 0 20px rgba(57,255,20,0.3);
    --glow-red: 0 0 20px rgba(255,56,96,0.3);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }

body {
    font-family: 'Sarabun', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    overflow-x: hidden;
    -webkit-tap-highlight-color: transparent;
    background-image:
        radial-gradient(ellipse at 30% 0%, rgba(0,243,255,0.07) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 100%, rgba(57,255,20,0.04) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(247,147,26,0.03) 0%, transparent 60%);
    background-attachment: fixed;
}

/* Scanline overlay */
body::after {
    content: '';
    position: fixed; inset: 0;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 2px,
        rgba(0,243,255,0.012) 2px,
        rgba(0,243,255,0.012) 4px
    );
    pointer-events: none; z-index: 0;
}

.brand { font-family: 'Orbitron', sans-serif; letter-spacing: 1px; }
.mono  { font-family: 'JetBrains Mono', monospace; }

::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: var(--bg2); }
::-webkit-scrollbar-thumb { background: var(--cyan); border-radius: 4px; }

/* ─── Header ─── */
header {
    position: sticky; top: 0; z-index: 100;
    background: rgba(5,10,14,0.96);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 14px 20px;
    display: flex; align-items: center; justify-content: space-between;
}
.title-glow {
    font-size: clamp(0.85rem,2.5vw,1.1rem);
    background: linear-gradient(90deg, var(--cyan), var(--green));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

.home-btn {
    color: var(--cyan); width: 32px; height: 32px;
    transition: all 0.3s; opacity: 0.7;
    display: flex; align-items: center; justify-content: center;
}
.home-btn svg { width:100%; height:100%; fill:currentColor; }
.home-btn:hover { transform: scale(1.2); opacity: 1; filter: drop-shadow(0 0 8px var(--cyan)); }

/* ─── Zone Containers ─── */
.zone-container {
    background: rgba(16, 26, 40, 0.3);
    border-radius: var(--radius);
    padding: 32px 24px;
    margin-top: 40px;
    margin-bottom: 40px;
    position: relative;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
@media (max-width: 600px) {
    .zone-container {
        padding: 24px 16px;
        margin-top: 24px;
        margin-bottom: 24px;
    }
}
.zone-cyan {
    border: 1px solid rgba(0,243,255,0.1);
    border-top: 3px solid var(--cyan);
    box-shadow: inset 0 20px 40px -20px rgba(0,243,255,0.05);
}
.zone-orange {
    border: 1px solid rgba(247,147,26,0.1);
    border-top: 3px solid var(--orange);
    box-shadow: inset 0 20px 40px -20px rgba(247,147,26,0.05);
}
.zone-purple {
    border: 1px solid rgba(167,139,250,0.1);
    border-top: 3px solid var(--purple);
    box-shadow: inset 0 20px 40px -20px rgba(167,139,250,0.05);
}

.zone-header {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 24px;
}
.zone-title {
    font-family: 'Orbitron', sans-serif; font-size: 0.8rem;
    letter-spacing: 3px; font-weight: 700; white-space: nowrap;
}
.zone-header .line { flex: 1; height: 1px; }
.zone-cyan .zone-title { color: var(--cyan); }
.zone-cyan .line { background: rgba(0,243,255,0.2); }
.zone-orange .zone-title { color: var(--orange); }
.zone-orange .line { background: rgba(247,147,26,0.2); }
.zone-purple .zone-title { color: var(--purple); }
.zone-purple .line { background: rgba(167,139,250,0.2); }

/* ─── Cards ─── */
.card {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: var(--shadow);
    position: relative;
    transition: border-color 0.3s;
    margin-bottom: 20px;
}
.card:last-child { margin-bottom: 0; }

.card.master {
    border-color: var(--cyan);
    box-shadow: var(--glow-cyan), var(--shadow);
}
.card.master::before, .card.master::after {
    content: ''; position: absolute;
    width: 14px; height: 14px;
    border: 2px solid var(--cyan);
}
.card.master::before { top:-1px; left:-1px; border-bottom:transparent; border-right:transparent; }
.card.master::after  { bottom:-1px; right:-1px; border-top:transparent; border-left:transparent; }

.card.avalanche-card {
    border-color: var(--orange);
    box-shadow: 0 0 20px rgba(247,147,26,0.2), var(--shadow);
}
.card.avalanche-card::before, .card.avalanche-card::after {
    content: ''; position: absolute;
    width: 14px; height: 14px;
    border: 2px solid var(--orange);
}
.card.avalanche-card::before { top:-1px; left:-1px; border-bottom:transparent; border-right:transparent; }
.card.avalanche-card::after  { bottom:-1px; right:-1px; border-top:transparent; border-left:transparent; }

/* ─── Callouts ─── */
.callout {
    border-radius: var(--radius-sm); padding: 14px 16px;
    display: flex; gap: 12px; align-items: flex-start;
    margin-bottom: 16px; font-size: 0.9rem; line-height: 1.7;
}
.callout:last-child { margin-bottom: 0; }
.callout-cyan   { background: rgba(0,243,255,0.06); border: 1px solid rgba(0,243,255,0.2); }
.callout-green  { background: rgba(57,255,20,0.05); border: 1px solid rgba(57,255,20,0.2); }
.callout-orange { background: rgba(247,147,26,0.07); border: 1px solid rgba(247,147,26,0.25); }
.callout-red    { background: rgba(255,56,96,0.06); border: 1px solid rgba(255,56,96,0.2); }
.callout-purple { background: rgba(167,139,250,0.06); border: 1px solid rgba(167,139,250,0.2); }
.callout-icon   { font-size: 1.1rem; flex-shrink: 0; padding-top: 2px; }

/* ─── Label row ─── */
.label-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 10px; flex-wrap: wrap; gap: 8px;
}
.card-label {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.72rem; letter-spacing: 2px;
    text-transform: uppercase; font-weight: 700;
}
.card-sublabel {
    font-size: 0.8rem; color: var(--text2);
    margin-bottom: 12px; line-height: 1.5;
}

/* ─── Badge ─── */
.badge {
    padding: 3px 10px; border-radius: 4px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem; font-weight: 700;
    border: 1px solid; white-space: nowrap;
}
.badge-cyan   { color: var(--cyan);   border-color: rgba(0,243,255,0.4);   background: rgba(0,243,255,0.05); }
.badge-green  { color: var(--green);  border-color: rgba(57,255,20,0.4);   background: rgba(57,255,20,0.05); }
.badge-orange { color: var(--orange); border-color: rgba(247,147,26,0.4);  background: rgba(247,147,26,0.05); }
.badge-red    { color: var(--red);    border-color: rgba(255,56,96,0.4);   background: rgba(255,56,96,0.05); }

/* ─── Input ─── */
.hash-input {
    width: 100%; background: rgba(0,0,0,0.4);
    border: none; border-bottom: 2px solid var(--border);
    color: #fff; padding: 12px 0;
    font-family: 'JetBrains Mono', monospace;
    font-size: clamp(0.9rem, 2.5vw, 1.05rem);
    margin-bottom: 12px; outline: none; transition: all 0.3s;
}
.hash-input:focus { border-bottom-color: var(--cyan); background: linear-gradient(to bottom, transparent, rgba(0,243,255,0.04)); }
.hash-input[readonly] { opacity: 0.6; cursor: default; }
.hash-input.avalanche-input:focus { border-bottom-color: var(--orange); background: linear-gradient(to bottom, transparent, rgba(247,147,26,0.04)); }

/* ─── Hash Display ─── */
.hash-display {
    font-family: 'JetBrains Mono', monospace;
    font-size: clamp(0.7rem, 2vw, 0.9rem);
    word-break: break-all; background: rgba(0,5,10,0.6);
    padding: 14px; border-radius: var(--radius-sm);
    line-height: 1.8; letter-spacing: 0.5px;
    border-left: 3px solid var(--border);
    min-height: 3.5em; transition: border-color 0.3s;
}
.hash-display.master-display { border-left-color: var(--cyan); }
.hash-display.avalanche-display { border-left-color: var(--orange); }

/* char states */
.ch-base  { color: #fff; opacity: 0.75; }
.ch-match { color: var(--green);  text-shadow: 0 0 6px var(--green);  font-weight: 700; }
.ch-diff  { color: var(--red);    opacity: 0.45; }

/* Avalanche Highlights */
.ch-av-match { color: var(--text3); opacity: 0.5; }
.ch-av-diff  { color: var(--orange); text-shadow: 0 0 6px var(--orange); font-weight: 700; }

/* ─── Avalanche Meter ─── */
.av-meter-track {
    height: 8px; background: rgba(255,255,255,0.06);
    border-radius: 4px; overflow: hidden; margin: 8px 0;
    position: relative;
}
.av-meter-fill {
    height: 100%; border-radius: 4px;
    transition: width 0.4s cubic-bezier(0.4,0,0.2,1), background 0.4s;
    position: relative;
}
.av-meter-fill::after {
    content: ''; position: absolute; top: 0; right: 0;
    width: 16px; height: 100%;
    background: rgba(255,255,255,0.5); filter: blur(3px);
    animation: shimmer 1.5s infinite;
}
@keyframes shimmer { 0%,100%{opacity:0.5} 50%{opacity:1} }

/* ─── Bit-flip counter ─── */
.bit-counter {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;
    margin-top: 12px;
}
.bit-stat {
    background: rgba(0,0,0,0.4); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 10px 8px; text-align: center;
}
.bit-stat-val {
    font-family: 'Orbitron', sans-serif;
    font-size: 1.2rem; font-weight: 700; display: block;
}
.bit-stat-label { font-size: 0.65rem; color: var(--text3); margin-top: 2px; display: block; letter-spacing: 1px; }

/* ─── Properties grid ─── */
.prop-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-top: 12px;
}
@media (max-width: 500px) { .prop-grid { grid-template-columns: 1fr; } }

.prop-card {
    background: rgba(0,0,0,0.4); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 12px;
}
.prop-icon { font-size: 1.3rem; margin-bottom: 6px; display: block; }
.prop-title { font-weight: 700; color: var(--text); font-size: 0.88rem; margin-bottom: 4px; }
.prop-desc  { font-size: 0.8rem; color: var(--text2); line-height: 1.5; }

/* ─── Explainer box ─── */
.explainer {
    background: var(--bg2); border-left: 3px solid var(--cyan);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    padding: 12px 16px; font-size: 0.88rem; line-height: 1.7;
    color: var(--text2); margin-bottom: 24px;
}

/* ─── Animations ─── */
@keyframes pulse-cyan {
    0%,100% { box-shadow: var(--glow-cyan), var(--shadow); }
    50%      { box-shadow: 0 0 40px rgba(0,243,255,0.5), var(--shadow); }
}
.card.master { animation: pulse-cyan 4s ease-in-out infinite; }

@keyframes hash-flicker {
    0%   { opacity: 1; }
    50%  { opacity: 0.85; }
    100% { opacity: 1; }
}
.computing { animation: hash-flicker 0.3s ease-in-out 2; }

@keyframes appear {
    from { opacity:0; transform: translateY(10px); }
    to   { opacity:1; transform: translateY(0); }
}
.card { animation: appear 0.4s ease-out both; }

/* ─── Responsive ─── */
@media (max-width: 600px) {
    .card { padding: 15px; }
    .label-row { font-size: 0.85rem; }
    .bit-counter { grid-template-columns: repeat(3,1fr); }
    .bit-stat-val { font-size: 1rem; }
}

/* ─── Footer ─── */
footer {
    text-align: center; padding: 24px; color: var(--text3);
    font-size: 0.75rem; border-top: 1px solid var(--border);
    background: rgba(0,0,0,0.3);
}
</style>
</head>
<body>

<!-- ════ HEADER ════ -->
<header>
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:1.4rem;">🔐</span>
        <div>
            <div class="brand title-glow">SHA-256 SIMULATOR</div>
            <div style="font-size:0.72rem;color:var(--text3);margin-top:1px;">Hashing Mechanics · Avalanche Effect · Educational</div>
        </div>
    </div>
    <a href="/" class="home-btn" title="Back to Home">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
    </a>
</header>

<div style="max-width:860px;margin:0 auto;padding:32px 16px 60px;position:relative;z-index:1;">

<!-- ════ INTRO ════ -->
<div class="callout callout-cyan" style="margin-bottom:24px;">
    <span class="callout-icon" style="color:var(--cyan);">📖</span>
    <div>
        <strong style="color:var(--cyan);">SHA-256 คืออะไร?</strong><br>
        <span style="color:var(--text2);">SHA-256 (Secure Hash Algorithm 256-bit) คือฟังก์ชันที่แปลงข้อมูลทุกชนิด — ไม่ว่าจะสั้นหรือยาวแค่ไหน — ให้กลายเป็น <strong style="color:var(--cyan);">ตัวเลข hex ยาว 64 ตัวอักษร (256 bits)</strong> เสมอ เปรียบเหมือน "ลายนิ้วมือดิจิทัล" ที่ไม่มีวันซ้ำกัน และไม่มีทางย้อนกลับได้ — ใช้ใน Bitcoin ทุกขั้นตอน ตั้งแต่ Wallet Address ไปจนถึง Mining</span>
    </div>
</div>

<!-- ════ PROPERTIES ════ -->
<div class="card" style="margin-bottom:40px;animation-delay:0.05s;">
    <div class="label-row">
        <span class="card-label" style="color:var(--cyan);">⬡ คุณสมบัติ 4 ประการของ SHA-256</span>
    </div>
    <div class="card-sublabel">ทำความเข้าใจว่าทำไม SHA-256 ถึงปลอดภัยและเชื่อถือได้ในระดับสากล</div>
    <div class="prop-grid">
        <div class="prop-card" style="border-color:rgba(0,243,255,0.25);">
            <span class="prop-icon">📏</span>
            <div class="prop-title" style="color:var(--cyan);">Deterministic — คงที่เสมอ</div>
            <div class="prop-desc">Input เดิม → Output เดิมทุกครั้ง ไม่ขึ้นกับเวลาหรือสถานที่ <em>"bitcoin"</em> จะได้ hash เดิมเสมอทุกที่บนโลก</div>
        </div>
        <div class="prop-card" style="border-color:rgba(57,255,20,0.25);">
            <span class="prop-icon">🌊</span>
            <div class="prop-title" style="color:var(--green);">Avalanche Effect — ระเบิดโดมิโน</div>
            <div class="prop-desc">เปลี่ยน input แค่ 1 ตัวอักษร → hash เปลี่ยน ~50% ทันที ทำให้ไม่มีใครหา pattern ได้</div>
        </div>
        <div class="prop-card" style="border-color:rgba(255,56,96,0.25);">
            <span class="prop-icon">🚫</span>
            <div class="prop-title" style="color:var(--red);">One-Way — ทางเดียวเท่านั้น</div>
            <div class="prop-desc">รู้ hash ก็ไม่สามารถหา input ต้นทางได้ เหมือนบดกระดาษทิ้ง — รู้ผงกระดาษ แต่ประกอบกลับไม่ได้</div>
        </div>
        <div class="prop-card" style="border-color:rgba(167,139,250,0.25);">
            <span class="prop-icon">🎯</span>
            <div class="prop-title" style="color:var(--purple);">Collision-Resistant — ไม่ชนกัน</div>
            <div class="prop-desc">แทบเป็นไปไม่ได้ที่ input 2 ตัวจะได้ hash เดียวกัน ความน่าจะเป็น = 1/2<sup>256</sup></div>
        </div>
    </div>
</div>

<!-- ════ SECTION: VARIANT COMPARISON ════ -->
<section class="zone-container zone-cyan">
    <div class="zone-header">
        <span class="zone-title">ZONE 1 · VARIANT COMPARISON</span>
        <div class="line"></div>
    </div>

    <div class="explainer">
        <strong style="color:var(--cyan);">🧪 ทดลอง Deterministic Property:</strong>
        พิมพ์ข้อความใดก็ได้ในช่อง Source ด้านล่าง — ระบบจะสร้าง 3 variant อัตโนมัติ (UPPERCASE, lowercase, snake_case) และแสดง hash ของแต่ละตัวพร้อมกัน<br><br>
        <strong style="color:var(--green);">สีเขียว</strong> = ตำแหน่ง hex ที่ตรงกับ Source &nbsp;|&nbsp; <strong style="color:var(--red);">สีแดง</strong> = ตำแหน่งที่ต่างกัน<br>
        สังเกตว่าการเปลี่ยน case เพียงอย่างเดียวทำให้ hash เปลี่ยนไปเกือบทั้งหมด — นั่นคือ Avalanche Effect ในทางปฏิบัติ
    </div>

    <!-- Master Input -->
    <div class="card master" style="animation-delay:0.1s;">
        <div class="label-row">
            <span class="card-label" style="color:var(--cyan);">▶ SOURCE INPUT</span>
            <span class="badge badge-cyan">MASTER REFERENCE</span>
        </div>
        <div class="card-sublabel">พิมพ์ข้อความที่นี่ — ระบบจะ hash และเปรียบเทียบกับ variant ด้านล่างทันที</div>
        <input type="text" class="hash-input" id="input-raw" placeholder="พิมพ์อะไรก็ได้ เช่น: bitcoin, Hello World..." oninput="syncInputs(this.value)" autofocus>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
            <span style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;">SHA-256 OUTPUT (64 HEX CHARS = 256 BITS)</span>
            <span id="char-count-raw" style="font-size:0.72rem;color:var(--text3);">input: 0 chars</span>
        </div>
        <div class="hash-display master-display" id="hash-raw">
            <span style="opacity:0.4;font-size:0.85rem;">⏳ รอข้อมูล... พิมพ์เพื่อเริ่ม</span>
        </div>
    </div>

    <!-- Uppercase -->
    <div class="card" style="animation-delay:0.15s;">
        <div class="label-row">
            <span class="card-label">▶ VARIANT: UPPERCASE</span>
            <span class="badge badge-cyan" id="percent-upper">— % Match</span>
        </div>
        <div class="card-sublabel" id="explain-upper" style="display:none;">
            เปลี่ยนตัวพิมพ์เล็กเป็นใหญ่ทั้งหมด — แม้ความหมายเหมือนกัน แต่ SHA-256 ถือว่าเป็น input คนละตัวโดยสิ้นเชิง
        </div>
        <input type="text" class="hash-input" id="input-upper" readonly placeholder="จะปรากฏอัตโนมัติ...">
        <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">SHA-256 OUTPUT</div>
        <div class="hash-display" id="hash-upper"><span style="opacity:0.4;">...</span></div>
    </div>

    <!-- Lowercase -->
    <div class="card" style="animation-delay:0.2s;">
        <div class="label-row">
            <span class="card-label">▶ VARIANT: LOWERCASE</span>
            <span class="badge badge-cyan" id="percent-lower">— % Match</span>
        </div>
        <div class="card-sublabel" id="explain-lower" style="display:none;">
            เปลี่ยนทุกตัวอักษรเป็นพิมพ์เล็ก — ลองสังเกตว่า match กี่ % กับ Source เมื่อกด เช่น "Bitcoin" vs "bitcoin"
        </div>
        <input type="text" class="hash-input" id="input-lower" readonly placeholder="จะปรากฏอัตโนมัติ...">
        <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">SHA-256 OUTPUT</div>
        <div class="hash-display" id="hash-lower"><span style="opacity:0.4;">...</span></div>
    </div>

    <!-- Snake case -->
    <div class="card" style="animation-delay:0.25s;">
        <div class="label-row">
            <span class="card-label">▶ VARIANT: SNAKE_CASE</span>
            <span class="badge badge-cyan" id="percent-snake">— % Match</span>
        </div>
        <div class="card-sublabel" id="explain-snake" style="display:none;">
            เปลี่ยน space ทุกตัวเป็น underscore (_) — "hello world" กับ "hello_world" ดูใกล้เคียง แต่ hash แตกต่างกันมหาศาล
        </div>
        <input type="text" class="hash-input" id="input-snake" readonly placeholder="จะปรากฏอัตโนมัติ...">
        <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">SHA-256 OUTPUT</div>
        <div class="hash-display" id="hash-snake"><span style="opacity:0.4;">...</span></div>
    </div>
</section>

<!-- ════ SECTION: AVALANCHE LAB ════ -->
<section class="zone-container zone-orange">
    <div class="zone-header">
        <span class="zone-title">ZONE 2 · AVALANCHE EFFECT LAB</span>
        <div class="line"></div>
    </div>

    <div class="callout callout-orange" style="margin-bottom:24px;">
        <span class="callout-icon" style="color:var(--orange);">🌊</span>
        <div>
            <strong style="color:var(--orange);">Avalanche Effect — ผลกระทบแบบโดมิโน</strong><br>
            <span style="color:var(--text2);">คุณสมบัติสำคัญที่สุดของ cryptographic hash function: เปลี่ยน input แค่ <strong>1 ตัวอักษร</strong> (หรือแม้แต่ 1 bit) → output เปลี่ยนโดยเฉลี่ย <strong style="color:var(--orange);">~50% (ประมาณ 128 ใน 256 bits)</strong> — ทำให้เป็นไปไม่ได้ที่จะ "เดา" หรือ "ค่อยๆ ปรับ" hash ให้ตรงเป้าหมาย นี่คือรากฐานที่ทำให้ Bitcoin Mining ยากมาก</span>
        </div>
    </div>

    <!-- ════ 50% RULE EXPLANATION CARD ════ -->
    <div class="card" style="margin-bottom:24px; background: rgba(247,147,26,0.03); border-color: rgba(247,147,26,0.2);">
        <div class="label-row">
            <span class="card-label" style="color:var(--orange);">🤔 ทำไมต้อง 50% ? (The 50% Rule)</span>
        </div>
        <div style="font-size: 0.88rem; color: var(--text2); line-height: 1.7;">
            หลายคนมักเข้าใจผิดว่ายิ่งเปลี่ยนมาก (เช่น 100%) ยิ่งเดายาก แต่ในทาง Cryptography <strong>50% คือค่าความสุ่มที่สมบูรณ์แบบที่สุด (Maximum Entropy)</strong> เปรียบเสมือนการโยนเหรียญที่ยุติธรรม
            <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 10px;">
                <div style="background: rgba(0,0,0,0.4); padding: 10px 14px; border-radius: 6px; border-left: 3px solid var(--red);">
                    <strong style="color:var(--red);">ถ้าเปลี่ยนน้อยไป (เช่น 10%):</strong> แฮ็กเกอร์สามารถเล่นเกมทายใจ ค่อยๆ ปรับ Input ทีละนิดเพื่อคลำหา Hash เป้าหมายได้
                </div>
                <div style="background: rgba(0,0,0,0.4); padding: 10px 14px; border-radius: 6px; border-left: 3px solid var(--yellow);">
                    <strong style="color:var(--yellow);">ถ้าเปลี่ยนมากไป (เช่น 100%):</strong> จะกลายเป็นกระจกสะท้อน แฮ็กเกอร์แค่สลับบิตตรงข้ามทั้งหมด (Invert) ก็จะได้ค่าใหม่ทันที มี Pattern ให้จับได้อยู่ดี
                </div>
                <div style="background: rgba(0,0,0,0.4); padding: 10px 14px; border-radius: 6px; border-left: 3px solid var(--green);">
                    <strong style="color:var(--green);">ถ้าเปลี่ยน 50% (อุดมคติ):</strong> ทุกครั้งที่เปลี่ยน 1 ตัวอักษร แต่ละบิตจะมีโอกาส 50/50 ที่จะเหมือนเดิมหรือเปลี่ยนไป ทำให้ไม่มีใครสามารถจับ Pattern ใดๆ ได้เลย!
                </div>
            </div>
        </div>
    </div>

    <div class="explainer" style="border-left-color:var(--orange);">
        <strong style="color:var(--orange);">🔬 วิธีทดลอง:</strong><br>
        ① ใน Zone 1 ด้านบน ให้พิมพ์ข้อความ เช่น <code style="color:var(--cyan);">bitcoin</code><br>
        ② ใน Avalanche Lab ด้านล่าง ให้พิมพ์ข้อความที่ต่างกันเล็กน้อย เช่น <code style="color:var(--cyan);">Bitcoin</code> หรือ <code style="color:var(--cyan);">bitcon</code><br>
        ③ ดูว่า % ที่เปลี่ยนไป (bits flipped) ใกล้ 50% มากแค่ไหน — ยิ่งใกล้ 50% ยิ่งดี
    </div>

    <!-- Avalanche Input -->
    <div class="card avalanche-card" style="animation-delay:0.3s;">
        <div class="label-row">
            <span class="card-label" style="color:var(--orange);">🌊 AVALANCHE TEST INPUT</span>
            <span class="badge badge-orange">COMPARE VS SOURCE</span>
        </div>
        <div class="card-sublabel">พิมพ์ข้อความที่คล้ายกับ Source — เช่น เปลี่ยนตัวอักษรเดียว เพิ่มจุด หรือเว้นวรรค</div>
        <input type="text" class="hash-input avalanche-input" id="input-av" placeholder="เช่น: Bitcoin, bitcon, bitcoin1..." oninput="updateAvalanche()">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
            <span style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;">SHA-256 OUTPUT (เทียบกับ SOURCE)</span>
            <span id="char-count-av" style="font-size:0.72rem;color:var(--text3);">input: 0 chars</span>
        </div>
        <div class="hash-display avalanche-display" id="hash-av">
            <span style="opacity:0.4;font-size:0.85rem;">⏳ รอข้อมูล... พิมพ์เพื่อเปรียบเทียบ</span>
        </div>

        <!-- Avalanche Meter -->
        <div id="av-meter-section" style="display:none;margin-top:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;">AVALANCHE INTENSITY</span>
                <span id="av-percent-label" style="font-size:0.85rem;font-family:'Orbitron',sans-serif;font-weight:700;color:var(--orange);">0%</span>
            </div>
            <div class="av-meter-track">
                <div id="av-meter-fill" class="av-meter-fill" style="width:0%;background:var(--red);"></div>
            </div>
            <div id="av-meter-desc" style="font-size:0.78rem;color:var(--text3);text-align:right;margin-top:4px;"></div>

            <div class="bit-counter">
                <div class="bit-stat">
                    <span class="bit-stat-val" id="stat-same" style="color:var(--green);">0</span>
                    <span class="bit-stat-label">HEX MATCH</span>
                </div>
                <div class="bit-stat">
                    <span class="bit-stat-val" id="stat-diff" style="color:var(--red);">0</span>
                    <span class="bit-stat-label">HEX CHANGED</span>
                </div>
                <div class="bit-stat">
                    <span class="bit-stat-val" id="stat-bits" style="color:var(--orange);">0</span>
                    <span class="bit-stat-label">BITS FLIPPED</span>
                </div>
            </div>

            <div id="av-verdict" style="margin-top:12px;padding:10px 14px;border-radius:6px;font-size:0.85rem;"></div>
        </div>
    </div>
</section>

<!-- ════ SECTION: BITCOIN USE CASES ════ -->
<section class="zone-container zone-purple">
    <div class="zone-header">
        <span class="zone-title">ZONE 3 · SHA-256 ใน BITCOIN</span>
        <div class="line"></div>
    </div>

    <div class="card" style="animation-delay:0.35s;">
        <div class="label-row">
            <span class="card-label" style="color:var(--purple);">₿ USE CASES IN BITCOIN PROTOCOL</span>
        </div>
        <div class="card-sublabel" style="margin-bottom:20px;">SHA-256 ไม่ใช่แค่ hash ทั่วไป — มันเป็น engine หลักของ Bitcoin ทั้งระบบ</div>

        <div class="callout callout-orange" style="margin-bottom:16px;">
            <span class="callout-icon" style="color:var(--orange);">⛏️</span>
            <div>
                <strong style="color:var(--orange);">Mining (Proof of Work)</strong><br>
                <span style="color:var(--text2);font-size:0.88rem;">นักขุดต้องหาค่า <strong>Nonce</strong> ที่ทำให้ SHA-256(SHA-256(block header)) ขึ้นต้นด้วย 0 จำนวนมาก — ไม่มีทางทำนายได้ ต้องทดลองจนกว่าจะถูก คือการพิสูจน์ว่าใช้พลังงานจริง</span>
            </div>
        </div>
        <div class="callout callout-cyan" style="margin-bottom:16px;">
            <span class="callout-icon" style="color:var(--cyan);">🔗</span>
            <div>
                <strong style="color:var(--cyan);">Block Chaining</strong><br>
                <span style="color:var(--text2);font-size:0.88rem;">ทุก block เก็บ hash ของ block ก่อนหน้า — ถ้าใครแก้ข้อมูลใน block เก่า hash ของทุก block ถัดไปจะเปลี่ยนหมด เครือข่ายตรวจสอบได้ทันที (Avalanche Effect ใช้งานจริง)</span>
            </div>
        </div>
        <div class="callout callout-green" style="margin-bottom:16px;">
            <span class="callout-icon" style="color:var(--green);">🔑</span>
            <div>
                <strong style="color:var(--green);">Seed → Wallet Address</strong><br>
                <span style="color:var(--text2);font-size:0.88rem;">HASH160 = RIPEMD-160(SHA-256(public key)) → ใช้สร้าง Bitcoin address · Checksum ของ BIP-39 ก็ใช้ SHA-256 เช่นกัน — คุณเพิ่งเห็นมันทำงานใน Seed Phrase Simulator</span>
            </div>
        </div>
        <div class="callout callout-purple" style="margin-bottom:0;">
            <span class="callout-icon" style="color:var(--purple);">🧾</span>
            <div>
                <strong style="color:var(--purple);">Transaction ID (TXID)</strong><br>
                <span style="color:var(--text2);font-size:0.88rem;">ทุก transaction มี TXID = SHA-256(SHA-256(raw transaction data)) — เปลี่ยนแม้แต่ 1 satoshi ใน transaction → TXID เปลี่ยนทันที ไม่มีทาง "แอบแก้" ในภายหลัง</span>
            </div>
        </div>
    </div>
</section>

</div><!-- end container -->

<footer>
    <div style="margin-bottom:4px;">© 2026 Chollatis Bitcoiner · <em>Don't Trust, Verify.</em></div>
    <div style="opacity:0.5;">SHA-256 · Educational Simulator · Not for Production Use</div>
</footer>

<script>
// ─── State ────────────────────────────────────────────────────────────────────
let masterHash = '';

// ─── Boot ─────────────────────────────────────────────────────────────────────
window.onload = function() {
    if (window.innerWidth > 768) {
        document.getElementById('input-raw').focus();
    }
};

// ─── Hex → Binary string ─────────────────────────────────────────────────────
function hexToBin(hex) {
    return hex.split('').map(c => parseInt(c,16).toString(2).padStart(4,'0')).join('');
}

// ─── Count bit differences between two 256-bit binary strings ─────────────────
function countBitDiffs(bin1, bin2) {
    let diff = 0;
    for (let i = 0; i < bin1.length; i++) {
        if (bin1[i] !== bin2[i]) diff++;
    }
    return diff;
}

// ─── Sync all variant inputs and update hashes ────────────────────────────────
function syncInputs(val) {
    const upper = val.toUpperCase();
    const lower = val.toLowerCase();
    const snake = val.replace(/ /g, '_');

    document.getElementById('input-upper').value = upper;
    document.getElementById('input-lower').value = lower;
    document.getElementById('input-snake').value = snake;

    document.getElementById('char-count-raw').innerText = `input: ${val.length} chars`;

    // Show/hide sublabels
    const hasVal = val.length > 0;
    ['upper','lower','snake'].forEach(id => {
        document.getElementById(`explain-${id}`).style.display = hasVal ? 'block' : 'none';
    });

    if (!val) {
        clearAll();
        masterHash = '';
        return;
    }

    masterHash = sha256(val);

    // Animate master hash
    const masterEl = document.getElementById('hash-raw');
    masterEl.classList.add('computing');
    setTimeout(() => masterEl.classList.remove('computing'), 300);
    masterEl.innerHTML = renderColoredHash(masterHash, null, 'base');

    compareAndRender('upper', upper, masterHash);
    compareAndRender('lower', lower, masterHash);
    compareAndRender('snake', snake, masterHash);

    // Update avalanche if it has value
    if (document.getElementById('input-av').value) {
        updateAvalanche();
    }
}

// ─── Render hash with colored characters ──────────────────────────────────────
function renderColoredHash(hash, masterH, mode) {
    if (mode === 'base' || !masterH) {
        return hash.split('').map(c => `<span class="ch-base">${c}</span>`).join('');
    }
    let html = '';
    for (let i = 0; i < 64; i++) {
        if (hash[i] === masterH[i]) {
            html += `<span class="${mode === 'variant' ? 'ch-match' : 'ch-av-match'}">${hash[i]}</span>`;
        } else {
            html += `<span class="${mode === 'variant' ? 'ch-diff' : 'ch-av-diff'}">${hash[i]}</span>`;
        }
    }
    return html;
}

// ─── Compare and render variant card ─────────────────────────────────────────
function compareAndRender(type, text, mHash) {
    const currentHash = sha256(text);
    let matchCount = 0;
    let html = '';
    for (let i = 0; i < 64; i++) {
        if (currentHash[i] === mHash[i]) {
            html += `<span class="ch-match">${currentHash[i]}</span>`;
            matchCount++;
        } else {
            html += `<span class="ch-diff">${currentHash[i]}</span>`;
        }
    }
    document.getElementById(`hash-${type}`).innerHTML = html;

    const pct = (matchCount / 64) * 100;
    const pEl = document.getElementById(`percent-${type}`);
    pEl.innerText = `${pct.toFixed(1)}% Match`;
    if (pct < 20) {
        pEl.className = 'badge badge-red';
    } else if (pct < 40) {
        pEl.className = 'badge badge-orange';
    } else {
        pEl.className = 'badge badge-cyan';
    }
}

// ─── Avalanche Lab ────────────────────────────────────────────────────────────
function updateAvalanche() {
    const avVal = document.getElementById('input-av').value;
    document.getElementById('char-count-av').innerText = `input: ${avVal.length} chars`;

    if (!avVal) {
        document.getElementById('hash-av').innerHTML = '<span style="opacity:0.4;font-size:0.85rem;">⏳ รอข้อมูล...</span>';
        document.getElementById('av-meter-section').style.display = 'none';
        return;
    }

    const avHash = sha256(avVal);
    const avEl = document.getElementById('hash-av');

    if (!masterHash) {
        // No source — just show plain hash
        avEl.innerHTML = renderColoredHash(avHash, null, 'base');
        document.getElementById('av-meter-section').style.display = 'none';
        return;
    }

    // Compare with masterHash
    avEl.classList.add('computing');
    setTimeout(() => avEl.classList.remove('computing'), 300);
    avEl.innerHTML = renderColoredHash(avHash, masterHash, 'avalanche');

    // Count hex matches and bit diffs
    let hexMatch = 0;
    for (let i = 0; i < 64; i++) {
        if (avHash[i] === masterHash[i]) hexMatch++;
    }
    const hexDiff = 64 - hexMatch;

    // Bit-level diff
    const binMaster = hexToBin(masterHash);
    const binAv     = hexToBin(avHash);
    const bitsDiff  = countBitDiffs(binMaster, binAv);
    const bitsPct   = (bitsDiff / 256) * 100;

    // Update meter
    document.getElementById('av-meter-section').style.display = 'block';
    document.getElementById('stat-same').innerText  = hexMatch;
    document.getElementById('stat-diff').innerText  = hexDiff;
    document.getElementById('stat-bits').innerText  = bitsDiff;

    const meterFill  = document.getElementById('av-meter-fill');
    const meterLabel = document.getElementById('av-percent-label');
    const meterDesc  = document.getElementById('av-meter-desc');
    const verdict    = document.getElementById('av-verdict');

    meterFill.style.width = Math.min(bitsPct, 100) + '%';
    meterLabel.innerText  = bitsPct.toFixed(1) + '%';

    // Color by intensity
    if (bitsPct < 20) {
        meterFill.style.background = 'var(--red)';
        meterLabel.style.color     = 'var(--red)';
        meterDesc.innerText        = '⚠️ Avalanche Effect อ่อน — input คล้ายกันมาก';
        verdict.style.background   = 'rgba(255,56,96,0.08)';
        verdict.style.borderLeft   = '3px solid var(--red)';
        verdict.style.color        = 'var(--text2)';
        verdict.innerHTML          = `<strong style="color:var(--red);">ความแตกต่างน้อย (${bitsDiff}/256 bits)</strong> — input ทั้งสองคล้ายกัน hash จึงดู "ใกล้เคียง" กว่าปกติ (แต่ยังต่างกันสุ่มไม่ได้ทำนาย)`;
    } else if (bitsPct < 40) {
        meterFill.style.background = 'linear-gradient(90deg,var(--red),var(--yellow))';
        meterLabel.style.color     = 'var(--yellow)';
        meterDesc.innerText        = '📊 Avalanche Effect ปานกลาง';
        verdict.style.background   = 'rgba(251,191,36,0.08)';
        verdict.style.borderLeft   = '3px solid var(--yellow)';
        verdict.style.color        = 'var(--text2)';
        verdict.innerHTML          = `<strong style="color:var(--yellow);">Avalanche กลางๆ (${bitsDiff}/256 bits = ${bitsPct.toFixed(1)}%)</strong> — SHA-256 กำลังทำงาน แต่ยังไม่ถึงค่าเฉลี่ย ~50%`;
    } else if (bitsPct < 60) {
        meterFill.style.background = 'linear-gradient(90deg,var(--orange),var(--green))';
        meterLabel.style.color     = 'var(--green)';
        meterDesc.innerText        = '✅ Avalanche Effect ดีเยี่ยม — ใกล้ 50% อุดมคติ';
        verdict.style.background   = 'rgba(57,255,20,0.06)';
        verdict.style.borderLeft   = '3px solid var(--green)';
        verdict.style.color        = 'var(--text2)';
        verdict.innerHTML          = `<strong style="color:var(--green);">Avalanche ดีเยี่ยม (${bitsDiff}/256 bits = ${bitsPct.toFixed(1)}%)</strong> — ใกล้ค่า "อุดมคติ" 50% (128/256 bits) แสดงว่า SHA-256 กระจาย output อย่างสม่ำเสมอ`;
    } else {
        meterFill.style.background = 'linear-gradient(90deg,var(--green),var(--cyan))';
        meterLabel.style.color     = 'var(--cyan)';
        meterDesc.innerText        = '🔵 Avalanche Effect สูงมาก — input ต่างกันมาก';
        verdict.style.background   = 'rgba(0,243,255,0.06)';
        verdict.style.borderLeft   = '3px solid var(--cyan)';
        verdict.style.color        = 'var(--text2)';
        verdict.innerHTML          = `<strong style="color:var(--cyan);">Avalanche สูง (${bitsDiff}/256 bits = ${bitsPct.toFixed(1)}%)</strong> — input ทั้งสองต่างกันมาก hash จึง diverge ไปมากกว่า 50%`;
    }
}

// ─── Clear all ────────────────────────────────────────────────────────────────
function clearAll() {
    document.getElementById('hash-raw').innerHTML = '<span style="opacity:0.4;font-size:0.85rem;">⏳ รอข้อมูล... พิมพ์เพื่อเริ่ม</span>';
    ['upper','lower','snake'].forEach(id => {
        document.getElementById(`hash-${id}`).innerHTML     = '<span style="opacity:0.4;">...</span>';
        document.getElementById(`percent-${id}`).innerText  = '— % Match';
        document.getElementById(`percent-${id}`).className  = 'badge badge-cyan';
        document.getElementById(`input-${id}`).value        = '';
    });
    ['upper','lower','snake'].forEach(id => {
        const el = document.getElementById(`explain-${id}`);
        if (el) el.style.display = 'none';
    });
    document.getElementById('char-count-raw').innerText = 'input: 0 chars';
    
    // เคลียร์ค่าของ Avalanche เพิ่มเติมเมื่อข้อมูล Source ว่างเปล่า
    const avInput = document.getElementById('input-av');
    if (avInput) avInput.value = '';
    const avMeter = document.getElementById('av-meter-section');
    if (avMeter) avMeter.style.display = 'none';
    const avHash = document.getElementById('hash-av');
    if (avHash) avHash.innerHTML = '<span style="opacity:0.4;font-size:0.85rem;">⏳ รอข้อมูล... พิมพ์เพื่อเปรียบเทียบ</span>';
    const avCharCount = document.getElementById('char-count-av');
    if (avCharCount) avCharCount.innerText = 'input: 0 chars';
}
</script>
</body>
</html>