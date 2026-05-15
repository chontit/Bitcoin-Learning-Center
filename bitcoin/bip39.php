<?php
// Filename: bip39.php
// Theme: BIP-39 Simulator — Enhanced Edition
// Author: Chollatis Bitcoiner
// Version: 2.0 — Full Thai Self-Study with Realistic Simulation
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BIP-39 Simulator | Seed Phrase Mechanics</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Sarabun:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
:root {
    --bg: #06090f;
    --bg2: #0c1220;
    --glass: rgba(255,255,255,0.04);
    --glass2: rgba(255,255,255,0.08);
    --btc: #F7931A;
    --btc2: #ffb347;
    --green: #00ff88;
    --green2: #00cc66;
    --blue: #38bdf8;
    --blue2: #0ea5e9;
    --purple: #a78bfa;
    --red: #f87171;
    --yellow: #fbbf24;
    --text: #e2e8f0;
    --text2: #94a3b8;
    --text3: #475569;
    --border: rgba(255,255,255,0.08);
    --border2: rgba(255,255,255,0.15);
    --radius: 16px;
    --radius-sm: 8px;
    --shadow: 0 8px 32px rgba(0,0,0,0.6);
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
        radial-gradient(ellipse at 10% 0%, rgba(247,147,26,0.12) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 100%, rgba(0,255,136,0.07) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(56,189,248,0.04) 0%, transparent 60%);
    background-attachment: fixed;
}

/* ─── Grid Noise Overlay ─── */
body::before {
    content: '';
    position: fixed; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
    pointer-events: none; z-index: 0; opacity: 0.5;
}

/* ─── Typography ─── */
.brand { font-family: 'Orbitron', sans-serif; letter-spacing: 1px; }
.mono  { font-family: 'JetBrains Mono', monospace; }

/* ─── Scrollbar ─── */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--bg2); }
::-webkit-scrollbar-thumb { background: var(--btc); border-radius: 4px; }

/* ─── Cards ─── */
.card-glass {
    background: var(--glass);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: border-color 0.3s, box-shadow 0.3s;
}
.card-glass:hover { border-color: rgba(247,147,26,0.3); }

.card-inner {
    background: rgba(0,0,0,0.4);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px;
}

/* ─── Buttons ─── */
.btn-btc {
    background: linear-gradient(135deg, var(--btc), var(--btc2));
    color: #000; font-weight: 700; border: none;
    font-family: 'Orbitron', sans-serif; font-size: 0.78rem;
    letter-spacing: 1.5px; text-transform: uppercase;
    transition: all 0.3s; border-radius: 50px;
    padding: 14px 28px;
}
.btn-btc:hover:not(:disabled) {
    box-shadow: 0 0 40px rgba(247,147,26,0.6), 0 0 80px rgba(247,147,26,0.2);
    transform: translateY(-2px); color: #000;
}
.btn-btc:disabled { background: #1e293b; color: #475569; cursor: not-allowed; }

.btn-ghost {
    background: var(--glass2); border: 1px solid var(--border2);
    color: var(--text2); transition: all 0.3s; border-radius: 50px;
    padding: 12px 24px; font-size: 0.85rem;
}
.btn-ghost:hover { background: rgba(255,255,255,0.12); color: var(--text); border-color: var(--border2); }

.btn-green {
    background: rgba(0,255,136,0.1); border: 1px solid var(--green2);
    color: var(--green); transition: all 0.3s; border-radius: 50px;
    padding: 12px 24px; font-size: 0.85rem;
}
.btn-green:hover, .btn-check:checked + .btn-green {
    background: var(--green); color: #000; font-weight: 700;
    box-shadow: 0 0 20px rgba(0,255,136,0.4); border-color: var(--green);
}

/* ─── Header ─── */
header {
    position: sticky; top: 0; z-index: 100;
    background: rgba(6,9,15,0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--border);
    padding: 14px 20px;
    display: flex; align-items: center; justify-content: space-between;
}

.btc-logo {
    width: 36px; height: 36px;
    animation: btc-pulse 3s ease-in-out infinite;
}
@keyframes btc-pulse {
    0%,100% { filter: drop-shadow(0 0 4px var(--btc)); transform: scale(1); }
    50% { filter: drop-shadow(0 0 12px var(--btc)); transform: scale(1.08); }
}

/* ─── Progress Bar ─── */
.progress-track {
    background: rgba(255,255,255,0.06);
    height: 4px; border-radius: 2px; overflow: hidden;
}
.progress-fill {
    height: 100%; border-radius: 2px;
    background: linear-gradient(90deg, var(--btc), var(--btc2));
    transition: width 0.6s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 0 10px var(--btc);
}

/* ─── Step Sections ─── */
.step-section { display: none; }
.step-section.active {
    display: block;
    animation: step-in 0.5s cubic-bezier(0.4,0,0.2,1) both;
}
@keyframes step-in {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─── Info Callouts ─── */
.callout {
    border-radius: var(--radius-sm); padding: 16px;
    display: flex; gap: 14px; align-items: flex-start;
    margin: 16px 0; font-size: 0.93rem; line-height: 1.7;
}
.callout-blue  { background: rgba(56,189,248,0.08); border: 1px solid rgba(56,189,248,0.25); }
.callout-green { background: rgba(0,255,136,0.06); border: 1px solid rgba(0,255,136,0.2); }
.callout-btc   { background: rgba(247,147,26,0.08); border: 1px solid rgba(247,147,26,0.25); }
.callout-red   { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.3); }
.callout-purple{ background: rgba(167,139,250,0.08); border: 1px solid rgba(167,139,250,0.25); }
.callout-icon  { font-size: 1.2rem; flex-shrink: 0; padding-top: 2px; }

/* ─── Concept Tags ─── */
.concept-tag {
    display: inline-block; padding: 3px 10px;
    border-radius: 20px; font-size: 0.78rem; font-weight: 600;
    font-family: 'Orbitron', sans-serif;
    background: rgba(247,147,26,0.15); color: var(--btc);
    border: 1px solid rgba(247,147,26,0.3); margin: 2px;
}

/* ─── Bit Visualizer ─── */
.bit-display {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.82rem; line-height: 1.8; word-break: break-all;
    background: rgba(0,0,0,0.5); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 14px;
    letter-spacing: 1px;
}
.bit-chunk {
    display: inline; color: var(--text3);
    transition: all 0.25s ease; border-radius: 3px;
    padding: 1px 2px;
}
.bit-chunk.active {
    color: #000; background: var(--green);
    font-weight: 700; box-shadow: 0 0 12px rgba(0,255,136,0.6);
}
.bit-chunk.processed {
    color: rgba(0,255,136,0.5);
}

/* ─── Word Chips ─── */
.word-chip {
    background: rgba(0,0,0,0.6); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 10px 8px;
    text-align: center; transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    min-height: 72px; display: flex; flex-direction: column;
    justify-content: center; align-items: center;
}
.word-chip.filled {
    border-color: var(--green2);
    background: rgba(0,255,136,0.08);
    box-shadow: 0 0 12px rgba(0,255,136,0.15);
    animation: chip-pop 0.4s cubic-bezier(0.4,0,0.2,1);
}
@keyframes chip-pop {
    0% { transform: scale(0.85); opacity: 0.5; }
    70% { transform: scale(1.05); }
    100% { transform: scale(1); opacity: 1; }
}
.word-num { font-size: 0.65rem; color: var(--text3); margin-bottom: 4px; font-family: 'Orbitron', sans-serif; }
.word-val { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; font-weight: 700; color: var(--text3); }
.word-val.active { color: var(--green); }

/* ─── Hex Highlight ─── */
.hex-hl {
    background: var(--blue); color: #000; padding: 1px 6px;
    border-radius: 4px; font-weight: 700;
    box-shadow: 0 0 10px rgba(56,189,248,0.6);
}

/* ─── Entropy Meter ─── */
.entropy-meter { position: relative; height: 8px; border-radius: 4px; background: #1e293b; overflow: hidden; }
.entropy-fill {
    height: 100%; border-radius: 4px;
    transition: width 0.3s ease, background 0.3s;
    position: relative;
}
.entropy-fill::after {
    content: ''; position: absolute; top: 0; right: 0;
    width: 20px; height: 100%;
    background: rgba(255,255,255,0.4);
    filter: blur(4px); animation: shimmer 1.5s infinite;
}
@keyframes shimmer { 0%,100%{opacity:0.6} 50%{opacity:1} }

/* ─── Dice Animation ─── */
.dice-face {
    width: 48px; height: 48px; border-radius: 10px;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border: 2px solid var(--border2); display: inline-flex;
    align-items: center; justify-content: center;
    font-size: 1.6rem; margin: 4px;
    animation: dice-roll 0.3s ease-out;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
}
@keyframes dice-roll {
    0% { transform: rotateX(90deg) scale(0.5); opacity: 0; }
    100% { transform: rotateX(0deg) scale(1); opacity: 1; }
}

/* ─── Step Result Panel ─── */
.slice-panel {
    background: rgba(0,0,0,0.6); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 20px;
    min-height: 120px; display: flex; align-items: center; justify-content: center;
}

/* ─── SHA Flow Animation ─── */
.sha-flow {
    display: flex; align-items: center; gap: 8px;
    flex-wrap: wrap; justify-content: center; padding: 12px;
}
.sha-block {
    padding: 6px 12px; border-radius: 6px;
    font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;
    animation: block-appear 0.4s ease-out both;
}
@keyframes block-appear {
    from { opacity: 0; transform: scale(0.7); }
    to   { opacity: 1; transform: scale(1); }
}

/* ─── Coin Flip Visual ─── */
.coin {
    width: 60px; height: 60px; border-radius: 50%;
    background: linear-gradient(135deg, var(--btc), var(--btc2));
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.6rem; box-shadow: 0 0 20px rgba(247,147,26,0.4);
    animation: coin-spin 0.5s ease-out;
}
@keyframes coin-spin {
    0% { transform: rotateY(90deg) scale(0.5); }
    100% { transform: rotateY(0deg) scale(1); }
}

/* ─── Quality Badge ─── */
.quality-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
    font-family: 'Orbitron', sans-serif;
}

/* ─── Final Phrase Card ─── */
.final-word-card {
    background: rgba(0,0,0,0.7); border: 1px solid rgba(0,255,136,0.2);
    border-radius: 10px; padding: 10px 16px;
    display: flex; align-items: center; gap: 10px;
    animation: final-appear 0.4s ease-out both;
}
@keyframes final-appear {
    from { opacity: 0; transform: translateX(-10px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ─── Tooltip explain boxes ─── */
.explain-box {
    background: var(--bg2); border-left: 3px solid var(--btc);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    padding: 14px 16px; margin: 12px 0; font-size: 0.9rem; line-height: 1.7;
}

/* ─── Step Header Icon ─── */
.step-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
    flex-shrink: 0;
}

/* ─── Responsive tweaks ─── */
@media (max-width: 576px) {
    .word-chip { min-height: 64px; }
    .btn-btc { font-size: 0.72rem; padding: 12px 20px; }
    .brand { font-size: 0.85rem; }
    .step-icon { width: 40px; height: 40px; font-size: 1.1rem; }
}

/* ─── Scan line effect ─── */
@keyframes scanline {
    0% { transform: translateY(-100%); }
    100% { transform: translateY(100vh); }
}
</style>
</head>
<body>

<!-- ════ HEADER ════ -->
<header>
    <div style="display:flex;align-items:center;gap:12px;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" class="btc-logo" alt="BTC">
        <div>
            <div class="brand" style="font-size:1rem;background:linear-gradient(90deg,var(--btc),var(--btc2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                BIP-39 SIMULATOR
            </div>
            <div style="font-size:0.75rem;color:var(--text3);margin-top:1px;">Seed Phrase Mechanics · Educational</div>
        </div>
    </div>
    <a href="/" class="btn-ghost" style="text-decoration:none;padding:8px 16px;font-size:0.8rem;">
        <i class="fa-solid fa-house me-1"></i> Home
    </a>
</header>

<div class="container py-4" style="max-width:780px;position:relative;z-index:1;">

<!-- ════ SETUP PANEL ════ -->
<div id="setupPanel">
    <div class="card-glass p-4 p-md-5 mb-4 text-center">
        <div style="font-size:3rem;margin-bottom:16px;">🔐</div>
        <h2 class="brand mb-2" style="font-size:1.3rem;color:var(--btc);">SEED PHRASE MECHANICS</h2>
        <p style="color:var(--text2);margin-bottom:8px;font-size:0.95rem;">จำลองกระบวนการสร้าง Seed Phrase ตามมาตรฐาน BIP-39</p>
        <p style="color:var(--text3);font-size:0.82rem;margin-bottom:28px;">ตั้งแต่เริ่มต้นจนจบ — ทุกขั้นตอนมีคำอธิบายภาษาไทย</p>

        <div class="callout callout-blue mb-4 text-start">
            <span class="callout-icon" style="color:var(--blue);">💡</span>
            <div>
                <strong style="color:var(--blue);">BIP-39 คืออะไร?</strong><br>
                <span style="color:var(--text2);font-size:0.9rem;">Bitcoin Improvement Proposal #39 คือมาตรฐานสากลในการแปลง "ตัวเลขสุ่ม" ให้กลายเป็น "คำภาษาอังกฤษ" ที่มนุษย์จำได้ง่าย เช่น <em>"abandon ability able..."</em> — ซึ่งคำเหล่านี้คือกุญแจที่ล็อก/ปลดล็อก Bitcoin wallet ของคุณ</span>
            </div>
        </div>

        <div class="callout callout-btc mb-4 text-start">
            <span class="callout-icon" style="color:var(--btc);">⚙️</span>
            <div>
                <strong style="color:var(--btc);">กระบวนการมี 5 ขั้นตอน</strong><br>
                <span style="color:var(--text2);font-size:0.9rem;">
                    <span class="concept-tag">① Entropy</span> สร้างตัวเลขสุ่ม →
                    <span class="concept-tag">② Checksum</span> ตรวจสอบ SHA-256 →
                    <span class="concept-tag">③ Combine</span> รวมข้อมูล →
                    <span class="concept-tag">④ Slice</span> ตัด 11 bits แบ่งคำ →
                    <span class="concept-tag">⑤ Words</span> ผลลัพธ์ Seed Phrase
                </span>
            </div>
        </div>

        <label class="d-block mb-2" style="color:var(--text3);font-family:'Orbitron',sans-serif;font-size:0.72rem;letter-spacing:2px;">SELECT SECURITY LEVEL</label>
        <select id="wordCount" class="form-select mb-4 mono text-center mx-auto" style="max-width:340px;background:#0c1220;border:1px solid var(--border2);color:var(--text);border-radius:10px;font-size:0.9rem;" onchange="updateSecurityInfo()">
            <option value="12">12 คำ — 128-bit (นิยมใช้งาน ปลอดภัยมากพอ)</option>
            <option value="15">15 คำ — 160-bit (เพื่อเป็นตัวเลือก)</option>
            <option value="18">18 คำ — 192-bit (เพื่อเป็นตัวเลือก)</option>
            <option value="21">21 คำ — 224-bit (เพื่อเป็นตัวเลือก)</option>
            <option value="24">24 คำ — 256-bit (ความปลอดภัยสูงสุด)</option>
        </select>

        <div class="callout callout-green mb-4 text-start" id="securityCallout">
            <span class="callout-icon" style="color:var(--green);">🛡️</span>
            <div style="color:var(--text2);font-size:0.88rem;" id="securityInfoContent">
                <!-- filled by JS on load -->
            </div>
        </div>

        <button onclick="startLesson()" class="btn-btc w-100" style="max-width:320px;">
            <i class="fa-solid fa-power-off me-2"></i> เริ่ม Simulation
        </button>
    </div>
</div>

<!-- ════ LESSON AREA ════ -->
<div id="lessonArea" style="display:none;">

    <!-- Progress Header (sticky) -->
    <div style="position:sticky;top:65px;z-index:50;padding:0 0 16px;">
        <div class="card-glass p-3" style="background:rgba(6,9,15,0.95);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span id="stepTitle" class="brand" style="font-size:0.8rem;color:var(--btc);letter-spacing:2px;">STEP 1: ENTROPY</span>
                <span id="stepCounter" style="font-size:0.75rem;color:var(--text3);font-family:'Orbitron',sans-serif;">1/5</span>
            </div>
            <div class="progress-track"><div id="progressBar" class="progress-fill" style="width:20%;"></div></div>
            <div id="stepSubtitle" style="font-size:0.78rem;color:var(--text3);margin-top:6px;">สร้างตัวเลขสุ่มเพื่อเป็นจุดเริ่มต้น</div>
        </div>
    </div>

    <!-- ──────── STEP 1: ENTROPY ──────── -->
    <div id="step1" class="step-section active">
        <div class="card-glass p-4 p-md-5">
            <!-- Header -->
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div class="step-icon" style="background:rgba(247,147,26,0.15);">🎲</div>
                <div>
                    <h3 class="brand" style="font-size:1.1rem;color:var(--btc);margin-bottom:2px;">STEP 1: ENTROPY</h3>
                    <p style="color:var(--text3);font-size:0.82rem;margin:0;">แหล่งกำเนิดความสุ่ม — รากฐานของความปลอดภัย</p>
                </div>
            </div>

            <!-- Explain -->
            <div class="callout callout-blue">
                <span class="callout-icon" style="color:var(--blue);">📖</span>
                <div>
                    <strong style="color:var(--blue);">Entropy คืออะไร?</strong><br>
                    <span style="color:var(--text2);">
                        Entropy คือ <strong style="color:var(--text);">"การสุ่มหรือความยุ่งเหยิง"</strong> ที่ใช้เป็นจุดเริ่มต้นในการสร้าง private key — ยิ่งตัวเลขมีความยุ่งเหยิงมาก (คาดเดาไม่ได้) ความปลอดภัยของกระเป๋าบิตคอยน์ก็จะยิ่งสูงขึ้น<br><br>
                        🎲 <strong style="color:var(--text);">เปรียบเทียบให้เห็นภาพ:</strong><br>
                        ลองนึกภาพคุณต้องสร้างรหัสล็อกตู้เซฟ<br>
                        &nbsp;&nbsp;• ตัวเลขที่คุณ <em>คิดเอง</em> เช่น "1234" หรือวันเกิด = <strong style="color:var(--red);">Entropy ต่ำ</strong> เนื่องจากคาดเดาได้ง่าย<br>
                        &nbsp;&nbsp;• ตัวเลขที่ได้จาก <em>การทอยลูกเต๋า 100 ครั้ง</em> และบันทึกตามลำดับ = <strong style="color:var(--green);">Entropy สูง</strong> เนื่องจากไม่มีใครทายหรือทอยซ้ำได้<br><br>
                        🧠 <strong style="color:var(--text);">ทำไมมนุษย์สุ่มได้ไม่ดี?</strong><br>
                        สมองมนุษย์มักเลือก pattern ที่ "ดูเหมือนสุ่ม" แต่จริงๆ มีแบบแผน เช่น สลับ 0101 หรือหลีกเลี่ยง 000 ติดกัน — Bitcoin จึงต้องใช้แหล่งสุ่มที่ไม่มี bias<br><br>
                        ⚡ <strong style="color:var(--text);">128-bit Entropy เท่ากับอะไร?</strong><br>
                        เหมือนทอยเหรียญ 128 ครั้ง บันทึกหัว/ก้อย — ความเป็นไปได้ทั้งหมด = 2<sup>128</sup> ≈ 340 ล้านล้านล้านล้าน — มากกว่าจำนวนอะตอมบนโลกถึง 1 พันล้านเท่า
                    </span>
                </div>
            </div>

            <!-- Input Mode -->
            <div style="margin-bottom:16px;">
                <label style="color:var(--text3);font-size:0.72rem;font-family:'Orbitron',sans-serif;letter-spacing:2px;display:block;margin-bottom:10px;">INPUT MODE</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="inputType" id="inBin" value="binary" checked onchange="updateInputMode()">
                    <label class="btn btn-green py-2" for="inBin" style="border-radius:8px 0 0 8px;"><i class="fa-solid fa-barcode me-1"></i> Binary</label>
                    <input type="radio" class="btn-check" name="inputType" id="inHex" value="hex" onchange="updateInputMode()">
                    <label class="btn btn-green py-2" for="inHex"><i class="fa-solid fa-font me-1"></i> Hex</label>
                    <input type="radio" class="btn-check" name="inputType" id="inDice" value="dice" onchange="updateInputMode()">
                    <label class="btn btn-green py-2" for="inDice" style="border-radius:0 8px 8px 0;"><i class="fa-solid fa-dice me-1"></i> Dice</label>
                </div>
            </div>

            <!-- Mode Explanation -->
            <div id="modeExplain" class="explain-box" style="margin-bottom:14px;">
                <strong style="color:var(--btc);">Binary Mode:</strong> ป้อนเลข 0 และ 1 โดยตรง — นี่คือรูปแบบที่คอมพิวเตอร์ "คิด" จริงๆ ทุกบิตคือ "ใช่" (1) หรือ "ไม่ใช่" (0) เป็นการสุ่มที่มีคุณภาพสูงที่สุด
            </div>

            <!-- Dice Visual -->
            <div id="diceVisual" style="display:none;text-align:center;margin-bottom:14px;padding:12px;background:rgba(0,0,0,0.3);border-radius:10px;border:1px solid var(--border);">
                <div style="color:var(--text3);font-size:0.8rem;margin-bottom:8px;">🎲 การแปลงลูกเต๋า → บิต (EFF Method)</div>
                <div id="dicePreview" style="font-size:0.8rem;color:var(--text2);"></div>
                <div style="font-size:0.72rem;color:var(--text3);margin-top:8px;">ลูกเต๋า 1-6 แต่ละลูก ≈ 2.585 bits · ต้องการ ~<span id="diceNeeded">50</span> ลูก</div>
            </div>

            <!-- Input Area -->
            <div style="position:relative;margin-bottom:12px;">
                <textarea id="entropyInput" class="mono" rows="4" placeholder="ป้อนเลขไบนารี่: 0101100110..."
                    style="width:100%;background:rgba(0,0,0,0.6);border:1px solid var(--border2);color:var(--text);border-radius:10px;padding:14px;font-size:0.85rem;resize:none;outline:none;transition:border-color 0.3s;">
                </textarea>
                <button onclick="doRandom()" style="position:absolute;top:10px;right:10px;background:rgba(255,255,255,0.08);border:1px solid var(--border2);color:var(--text2);border-radius:8px;padding:6px 12px;font-size:0.78rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text2)'">
                    <i class="fa-solid fa-rotate"></i> สุ่ม
                </button>
            </div>

            <!-- Bit Counter + Meter -->
            <div style="margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:0.8rem;color:var(--text3);">📊 จำนวน Bits:</span>
                    <span id="bitCountDisplay" class="mono" style="font-size:0.85rem;font-weight:700;color:var(--red);">0 / 128</span>
                </div>
                <div class="entropy-meter">
                    <div id="entropyFill" class="entropy-fill" style="width:0%;background:var(--red);"></div>
                </div>
                <div id="qualityLabel" style="font-size:0.75rem;color:var(--text3);margin-top:6px;text-align:right;"></div>
            </div>

            <div class="callout callout-green" style="margin-bottom:20px;">
                <span class="callout-icon" style="color:var(--green);">✅</span>
                <div style="color:var(--text2);font-size:0.88rem;">
                    กด <strong style="color:var(--green);">สุ่ม</strong> เพื่อให้ browser สร้างตัวเลขสุ่มให้อัตโนมัติ หรือป้อนเองเพื่อทดลอง — ในการใช้งานจริง ควรใช้ hardware wallet เพื่อสร้าง entropy ที่มีคุณภาพ หรือสุ่มด้วยเหรียญ/ลูกเต๋า 
                </div>
            </div>

            <button id="btnStep1Next" onclick="goToStep(2)" class="btn-btc w-100" disabled>
                คำนวณ Checksum <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

    <!-- ──────── STEP 2: CHECKSUM ──────── -->
    <div id="step2" class="step-section">
        <div class="card-glass p-4 p-md-5">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div class="step-icon" style="background:rgba(56,189,248,0.15);">🛡️</div>
                <div>
                    <h3 class="brand" style="font-size:1.1rem;color:var(--blue);margin-bottom:2px;">STEP 2: CHECKSUM</h3>
                    <p style="color:var(--text3);font-size:0.82rem;margin:0;">SHA-256 · ลายเซ็นดิจิทัลเพื่อตรวจจับข้อผิดพลาด</p>
                </div>
            </div>

            <div class="callout callout-blue">
                <span class="callout-icon" style="color:var(--blue);">📖</span>
                <div>
                    <strong style="color:var(--blue);">Checksum คืออะไร?</strong><br>
                    <span style="color:var(--text2);">Checksum คือ "การตรวจสอบ" — เหมือน Parity Check ระหว่างการส่งข้อมูลต่าง ๆ เพื่อให้เกิดความมั่นใจว่าข้อมูลถูกต้องทุกบิต ในส่วนของกระเป๋าบิตคอยน์เราใช้ SHA-256 Hashing Algorithm แล้วนำ bits แรกๆ มาต่อท้าย เพื่อให้ wallet รู้ได้ทันทีว่า seed phrase ที่กรอกเข้ามา "ถูกต้อง" หรือมีคำที่เรา "พิมพ์ผิด"</span>
                </div>
            </div>

            <!-- SHA-256 Flow Visualization -->
            <div style="margin:16px 0;">
                <div style="font-size:0.75rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:10px;">SHA-256 HASHING PROCESS</div>
                <div id="shaFlowViz" style="background:rgba(0,0,0,0.5);border-radius:10px;border:1px solid var(--border);padding:14px;min-height:60px;display:flex;align-items:center;justify-content:center;">
                    <span style="color:var(--text3);font-size:0.85rem;">⏳ รอการคำนวณ...</span>
                </div>
            </div>

            <!-- Entropy Display -->
            <div style="margin-bottom:14px;">
                <div style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:8px;">① RAW ENTROPY (INPUT)</div>
                <div id="dispEntropy" class="bit-display" style="max-height:80px;overflow:auto;color:var(--text2);"></div>
            </div>

            <!-- Arrow -->
            <div style="text-align:center;color:var(--text3);margin:8px 0;font-size:0.85rem;">
                <i class="fa-solid fa-arrow-down"></i> SHA-256 <i class="fa-solid fa-arrow-down"></i>
            </div>

            <!-- Hash Display -->
            <div style="margin-bottom:14px;">
                <div style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:8px;">② HASH RESULT (256-bit / 64 HEX chars)</div>
                <div id="dispHash" class="bit-display" style="color:var(--blue);word-break:break-all;">
                    <span style="color:var(--text3);">⏳ กำลังคำนวณ...</span>
                </div>
            </div>

            <!-- Checksum Extraction -->
            <div id="csExtractArea" style="display:none;">
                <div class="callout callout-btc">
                    <span class="callout-icon" style="color:var(--btc);">🔍</span>
                    <div>
                        <strong style="color:var(--btc);">ทำไมต้องใช้แค่ Byte แรก?</strong><br>
                        <span style="color:var(--text2);font-size:0.88rem;">SHA-256 ให้ผลลัพธ์ 256 bits เสมอ เราต้องการแค่ <strong id="csLen" style="color:var(--btc);">4</strong> bits แรกเป็น checksum — bytes แรกคือตัวแทนที่ดีเพราะ SHA-256 กระจาย randomness อย่างสม่ำเสมอทั่วทั้ง output</span>
                    </div>
                </div>
                <div id="csVizArea" style="background:rgba(0,0,0,0.5);border-radius:10px;border:1px solid var(--border);padding:16px;margin-bottom:16px;"></div>
            </div>

            <!-- Checksum Result -->
            <div id="csResultBox" style="display:none;background:rgba(247,147,26,0.08);border:1px solid rgba(247,147,26,0.3);border-radius:10px;padding:14px;margin-bottom:20px;">
                <div style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:8px;">✅ CHECKSUM BITS</div>
                <div class="brand mono" style="font-size:1.4rem;color:var(--btc);letter-spacing:4px;" id="dispCsBits"></div>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button onclick="goToStep(1)" class="btn-ghost">
                    <i class="fa-solid fa-arrow-left me-1"></i> กลับ
                </button>
                <button id="btnStep2Next" onclick="goToStep(3)" class="btn-btc flex-grow-1" disabled>
                    ต่อท้าย Checksum <i class="fa-solid fa-link ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ──────── STEP 3: COMBINE ──────── -->
    <div id="step3" class="step-section">
        <div class="card-glass p-4 p-md-5">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div class="step-icon" style="background:rgba(167,139,250,0.15);">🔗</div>
                <div>
                    <h3 class="brand" style="font-size:1.1rem;color:var(--purple);margin-bottom:2px;">STEP 3: COMBINE</h3>
                    <p style="color:var(--text3);font-size:0.82rem;margin:0;">รวม Entropy + Checksum เป็นข้อมูลชุดเดียวกัน</p>
                </div>
            </div>

            <div class="callout callout-purple">
                <span class="callout-icon" style="color:var(--purple);">📖</span>
                <div>
                    <strong style="color:var(--purple);">ทำไมต้องรวม?</strong><br>
                    <span style="color:var(--text2);">เราต่อ checksum ไว้ท้าย entropy เพื่อให้ bit stream มีความยาวหารด้วย 11 ลงตัวพอดี เพราะแต่ละคำใน BIP-39 wordlist ถูก map ด้วย 11-bit index (2<sup>11</sup> = 2048 คำ)</span>
                </div>
            </div>

            <!-- Combined Bit Display -->
            <div style="margin-bottom:14px;">
                <div style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:8px;">COMBINED BIT STREAM</div>
                <div id="combinedDisplay" class="bit-display" style="word-break:break-all;color:var(--text);"></div>
            </div>

            <!-- Stats Row -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;">
                <div class="card-inner text-center">
                    <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;margin-bottom:4px;">ENTROPY</div>
                    <div class="mono" style="color:var(--text);font-size:0.9rem;font-weight:700;"><span id="s3Entropy">128</span> bits</div>
                </div>
                <div class="card-inner text-center">
                    <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;margin-bottom:4px;">CHECKSUM</div>
                    <div class="mono" style="color:var(--btc);font-size:0.9rem;font-weight:700;"><span id="s3Cs">4</span> bits</div>
                </div>
                <div class="card-inner text-center">
                    <div style="font-size:0.7rem;color:var(--text3);font-family:'Orbitron',sans-serif;margin-bottom:4px;">TOTAL</div>
                    <div class="mono" style="color:var(--green);font-size:0.9rem;font-weight:700;"><span id="s3Total">132</span> bits</div>
                </div>
            </div>

            <!-- Formula Explanation -->
            <div class="explain-box" style="margin-bottom:20px;">
                <strong style="color:var(--btc);">สูตร BIP-39:</strong><br>
                <span style="color:var(--text2);">(<span id="f1">128</span> bits entropy + <span id="f2">4</span> bits checksum) ÷ 11 = <span id="f3" style="color:var(--green);font-weight:700;">12</span> words</span><br>
                <span style="color:var(--text3);font-size:0.85rem;">ทุก 11 bits จะถูกแปลงเป็น 1 คำ จาก wordlist 2,048 คำ</span>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button onclick="goToStep(2)" class="btn-ghost">
                    <i class="fa-solid fa-arrow-left me-1"></i> กลับ
                </button>
                <button onclick="goToStep(4)" class="btn-btc flex-grow-1">
                    เริ่ม Slicing <i class="fa-solid fa-scissors ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ──────── STEP 4: SLICING ──────── -->
    <div id="step4" class="step-section">
        <div class="card-glass p-4 p-md-4">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div class="step-icon" style="background:rgba(0,255,136,0.1);">✂️</div>
                <div>
                    <h3 class="brand" style="font-size:1.1rem;color:var(--green);margin-bottom:2px;">STEP 4: SLICING</h3>
                    <p style="color:var(--text3);font-size:0.82rem;margin:0;">ตัดแบ่งทีละ 11 bits → แปลงเป็นคำ</p>
                </div>
            </div>

            <div class="callout callout-green" style="margin-bottom:16px;">
                <span class="callout-icon" style="color:var(--green);">📖</span>
                <div>
                    <strong style="color:var(--green);">กระบวนการ Lookup</strong><br>
                    <span style="color:var(--text2);font-size:0.88rem;">แต่ละก้อน 11 bits ถูกแปลงเป็นเลขฐาน 10 (0–2047) แล้วใช้เป็น index ค้นหาคำใน BIP-39 wordlist ที่มี 2,048 คำ</span>
                </div>
            </div>

            <!-- Full Bit Stream -->
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;">FULL BIT STREAM</span>
                    <span id="wordProgress" style="font-size:0.75rem;color:var(--text3);">Word 0 / <span id="wTotal">12</span></span>
                </div>
                <div id="fullBitsDisplay" class="bit-display"></div>
            </div>

            <!-- Slice Stage -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <span class="brand" style="font-size:0.9rem;color:var(--text);">11-BIT → WORD</span>
                <button onclick="sliceNextWord()" id="btnSlice" class="btn-green" style="padding:8px 20px;font-size:0.82rem;">
                    <i class="fa-solid fa-play me-1"></i> Next Word
                </button>
            </div>

            <div class="slice-panel" id="sliceStage" style="margin-bottom:16px;">
                <div style="text-align:center;color:var(--text3);">
                    <div style="font-size:2rem;margin-bottom:8px;">👆</div>
                    <div style="font-size:0.85rem;">กด "Next Word" เพื่อเริ่มประมวลผล</div>
                </div>
            </div>

            <!-- Word Grid -->
            <div id="wordGrid" class="row g-2" style="margin-bottom:16px;"></div>

            <div style="padding-top:12px;border-top:1px solid var(--border);">
                <button onclick="goToStep(3)" class="btn-ghost" style="font-size:0.82rem;padding:8px 16px;">
                    <i class="fa-solid fa-arrow-left me-1"></i> กลับไป Combine
                </button>
            </div>
        </div>
    </div>

    <!-- ──────── STEP 5: FINISH ──────── -->
    <div id="step5" class="step-section">
        <div class="card-glass p-4 p-md-5" style="border-color:rgba(0,255,136,0.3);box-shadow:0 0 40px rgba(0,255,136,0.08);">
            <div style="text-align:center;margin-bottom:24px;">
                <div style="font-size:3.5rem;margin-bottom:12px;animation:btc-pulse 2s infinite;">🎉</div>
                <h2 class="brand" style="font-size:1.3rem;color:var(--green);margin-bottom:4px;">SEED PHRASE COMPLETE</h2>
                <p id="finishSubtext" style="color:var(--text3);font-size:0.85rem;">12 Words Generated</p>
            </div>

            <!-- Final Phrase -->
            <div style="background:rgba(0,0,0,0.6);border:1px solid rgba(0,255,136,0.25);border-radius:12px;padding:20px;margin-bottom:20px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;right:0;font-size:6rem;opacity:0.04;line-height:1;">₿</div>
                <div class="row g-2" id="finalList"></div>
            </div>

            <!-- What's Next -->
            <div class="callout callout-blue" style="margin-bottom:16px;">
                <span class="callout-icon" style="color:var(--blue);">🔄</span>
                <div>
                    <strong style="color:var(--blue);">ขั้นตอนต่อไป (ในระบบจริง)</strong><br>
                    <span style="color:var(--text2);font-size:0.88rem;">Seed phrase → <strong>PBKDF2</strong> (512-bit seed) → <strong>BIP-32</strong> HD Wallet derivation → <strong>BIP-84</strong> path (m/84'/0'/0') → <strong>Native SegWit address</strong> (bc1q...) · ทุก address ที่ wallet คุณสร้างมีต้นกำเนิดจากคำเหล่านี้ &nbsp;<a href="hd-wallet.php" target="_blank" style="color:var(--btc);text-decoration:underline;font-size:0.85rem;">→ เรียนรู้ HD Wallet ต่อ</a></span>
                </div>
            </div>

            <!-- Security Warning -->
            <div class="callout callout-red" style="margin-bottom:20px;">
                <span class="callout-icon" style="color:var(--red);">⚠️</span>
                <div>
                    <strong style="color:var(--red);">SECURITY WARNING</strong><br>
                    <span style="color:var(--text2);font-size:0.88rem;">Seed phrase นี้สร้างบน browser เพื่อ<strong>การศึกษาเท่านั้น</strong> — ห้ามนำไปใช้งานจริงเด็ดขาด ในการใช้งานจริงให้ใช้ hardware wallet (Coldcard, Ledger, Trezor) ที่ generate entropy ใน secure element</span>
                </div>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button onclick="goToStep(4)" class="btn-ghost">
                    <i class="fa-solid fa-arrow-left me-1"></i> กลับ
                </button>
                <button onclick="location.reload()" class="btn-btc flex-grow-1">
                    <i class="fa-solid fa-rotate-left me-2"></i> เริ่มใหม่
                </button>
            </div>
        </div>
    </div>

</div><!-- end lessonArea -->

</div><!-- end container -->

<footer style="text-align:center;padding:24px;color:var(--text3);font-size:0.78rem;border-top:1px solid var(--border);margin-top:20px;background:rgba(0,0,0,0.3);">
    <div style="margin-bottom:4px;">© 2026 Chollatis Bitcoiner · <em>Don't Trust, Verify.</em></div>
    <div style="opacity:0.5;">Educational Purpose Only · Not for Production Use</div>
</footer>

<script>
// ─── State ───────────────────────────────────────────────────────────────────
let targetBits = 128, wordCount = 12;
let finalBin = '', checksumBin = '', fullBin = '', currentWordIndex = 0;
let bip39Words = [];
const wordlistUrl = 'https://raw.githubusercontent.com/bitcoin/bips/master/bip-0039/english.txt';
const fallbackWords = Array.from({length:2048},(_,i)=>`word${i}`);

// ─── Dice face map ────────────────────────────────────────────────────────────
const diceFaces = ['','⚀','⚁','⚂','⚃','⚄','⚅'];

// ─── Security Info (Setup Panel) ─────────────────────────────────────────────
const securityData = {
    12: {
        bits: 128,
        exp: '3.4 × 10<sup>38</sup>',
        expPlain: '340,000,000,000,000,000,000,000,000,000,000,000,000',
        yearsDesc: 'นับล้านล้านล้านปี',
        analogy: 'มากกว่าอายุจักรวาล (1.38 × 10<sup>10</sup> ปี) ถึง <strong style="color:var(--green);">~25 ล้านล้านล้านครั้ง</strong>',
        vs12: null,
        tag: 'มาตรฐาน · ปลอดภัยสูงมาก',
        tagColor: 'var(--green)',
        icon: '🛡️'
    },
    15: {
        bits: 160,
        exp: '1.46 × 10<sup>48</sup>',
        expPlain: '1,461,501,637,330,902,918,203,684,832,716,283,019,655,932,542,976',
        yearsDesc: 'ไม่มีตัวเลขเปรียบเทียบที่มนุษย์เข้าใจได้',
        analogy: 'มากกว่า 12 คำถึง <strong style="color:var(--green);">2<sup>32</sup> ≈ 4.3 พันล้านเท่า</strong>',
        vs12: 32,
        tag: 'ปลอดภัยสูงกว่า 12 คำ',
        tagColor: 'var(--blue)',
        icon: '🔐'
    },
    18: {
        bits: 192,
        exp: '6.27 × 10<sup>57</sup>',
        expPlain: 'เกินขอบเขตที่มนุษย์จะเข้าใจได้ในระดับตัวเลข',
        yearsDesc: 'เกินขีดจำกัดของฟิสิกส์',
        analogy: 'มากกว่า 12 คำถึง <strong style="color:var(--green);">2<sup>64</sup> ≈ 1.8 × 10<sup>19</sup> เท่า</strong>',
        vs12: 64,
        tag: 'ระดับ Overkill สำหรับมนุษย์',
        tagColor: 'var(--purple)',
        icon: '🚀'
    },
    21: {
        bits: 224,
        exp: '2.69 × 10<sup>67</sup>',
        expPlain: 'ตัวเลขระดับจักรวาลวิทยา',
        yearsDesc: 'เกินขีดจำกัดของฟิสิกส์ทุกกฎ',
        analogy: 'มากกว่า 12 คำถึง <strong style="color:var(--green);">2<sup>96</sup> ≈ 7.9 × 10<sup>28</sup> เท่า</strong>',
        vs12: 96,
        tag: 'เกินความจำเป็นทางปฏิบัติ',
        tagColor: 'var(--btc)',
        icon: '⚡'
    },
    24: {
        bits: 256,
        exp: '1.16 × 10<sup>77</sup>',
        expPlain: 'ใกล้เคียงกับจำนวนอะตอมในจักรวาลที่มองเห็นได้',
        yearsDesc: 'เกินขีดจำกัดของจักรวาล',
        analogy: 'มากกว่า 12 คำถึง <strong style="color:var(--green);">2<sup>128</sup> ≈ 3.4 × 10<sup>38</sup> เท่า</strong> — เท่ากับ entropy ทั้งหมดของ 12 คำ ซ้อนกันอีกชั้น',
        vs12: 128,
        tag: 'ความปลอดภัยสูงสุด · Quantum-resistant ระดับหนึ่ง',
        tagColor: 'var(--btc)',
        icon: '👑'
    }
};

function updateSecurityInfo() {
    const wc = parseInt(document.getElementById('wordCount').value);
    const d = securityData[wc];
    const bits = d.bits;

    let vs12html = '';
    if (d.vs12) {
        vs12html = `
            <div style="margin-top:10px;padding:8px 12px;background:rgba(0,0,0,0.3);border-radius:8px;border:1px solid rgba(255,255,255,0.08);">
                <span style="color:var(--text3);font-size:0.78rem;">📊 เทียบกับ 12 คำ (128-bit):</span><br>
                <span style="font-size:0.88rem;">ต้องใช้พลังงานเพิ่มอีก ${d.analogy}</span>
            </div>`;
    }

    document.getElementById('securityInfoContent').innerHTML = `
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
            <strong style="color:var(--green);">ทำไม ${wc} คำถึงปลอดภัย?</strong>
            <span style="padding:2px 8px;border-radius:12px;font-size:0.72rem;font-weight:700;font-family:'Orbitron',sans-serif;background:rgba(0,0,0,0.4);border:1px solid ${d.tagColor};color:${d.tagColor};">${d.tag}</span>
        </div>
        <div style="margin-bottom:8px;">
            <span style="color:var(--text3);font-size:0.8rem;">Entropy:</span>
            <span class="mono" style="color:var(--text);font-weight:700;margin-left:6px;">${bits}-bit</span>
        </div>
        <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:6px;flex-wrap:wrap;">
            <span style="color:var(--text3);font-size:0.8rem;">ความเป็นไปได้:</span>
            <span class="mono" style="color:var(--green);font-size:1rem;font-weight:700;">2<sup>${bits}</sup> = ${d.exp} แบบ</span>
        </div>
        <div style="font-size:0.82rem;color:var(--text2);margin-bottom:6px;">
            ${d.analogy}
        </div>
        <div style="font-size:0.8rem;color:var(--text3);">
            ⏱️ เวลาในการ Brute Force: <strong style="color:var(--text);">${d.yearsDesc}</strong>
        </div>
        ${vs12html}`;
}

// ─── Boot: load wordlist ──────────────────────────────────────────────────────
window.onload = async () => {
    updateSecurityInfo();
    try {
        const resp = await fetch(wordlistUrl);
        if(!resp.ok) throw new Error();
        const text = await resp.text();
        bip39Words = text.split('\n').map(w=>w.trim()).filter(Boolean);
    } catch(e) {
        bip39Words = fallbackWords;
    }
};

// ─── Start Lesson ─────────────────────────────────────────────────────────────
function startLesson() {
    wordCount = parseInt(document.getElementById('wordCount').value);
    targetBits = (wordCount * 11 * 32) / 33;
    document.getElementById('setupPanel').style.display = 'none';
    document.getElementById('lessonArea').style.display = 'block';
    document.getElementById('bitCountDisplay').innerText = `0 / ${targetBits}`;
    document.getElementById('diceNeeded').innerText = Math.ceil(targetBits / 2.585);
    updateProgressBar(1);
}

// ─── Progress Bar ─────────────────────────────────────────────────────────────
const stepSubtitles = ['','สร้างตัวเลขสุ่มเพื่อเป็นจุดเริ่มต้น','คำนวณ SHA-256 hash เพื่อสร้าง checksum','รวม entropy + checksum เป็น bit stream','ตัด 11 bits ต่อคำ แปลงเป็น index → word','Seed phrase สมบูรณ์แล้ว!'];
const stepTitles = ['','ENTROPY','CHECKSUM','COMBINE','SLICING','FINISH'];

function updateProgressBar(step) {
    document.getElementById('progressBar').style.width = `${(step/5)*100}%`;
    document.getElementById('stepCounter').innerText = `${step}/5`;
    document.getElementById('stepTitle').innerText = `STEP ${step}: ${stepTitles[step]}`;
    document.getElementById('stepSubtitle').innerText = stepSubtitles[step];

    document.querySelectorAll('.step-section').forEach(el => {
        el.classList.remove('active');
        el.style.display = 'none';
    });
    setTimeout(() => {
        const el = document.getElementById(`step${step}`);
        el.style.display = 'block';
        requestAnimationFrame(() => el.classList.add('active'));
    }, 80);
    window.scrollTo({top:0, behavior:'smooth'});
}

function goToStep(step) {
    if(step === 2) { calcChecksum(); }
    if(step === 3) { combineData(); }
    if(step === 4) { setupGrid(); }
    updateProgressBar(step);
}

// ─── Step 1: Input Handling ───────────────────────────────────────────────────
const modeExplains = {
    binary: '<strong style="color:var(--btc)">Binary Mode:</strong> ป้อนเลข <strong>0</strong> และ <strong>1</strong> โดยตรง — นี่คือรูปแบบที่คอมพิวเตอร์ \"คิด\" จริงๆ ทุกบิตคือ \"ใช่\" (1) หรือ \"ไม่ใช่\" (0)<br><br><span style=\"color:var(--green);font-weight:700;\">🏆 คุณภาพการสุ่ม: สูงที่สุด</span> — ป้อนบิตต่อบิต 1:1 ไม่มีการแปลง ไม่มีการสูญเสีย entropy ระหว่างทาง ทุกบิตที่พิมพ์คือ entropy โดยตรง',
    hex:    '<strong style="color:var(--btc)">Hex Mode:</strong> ป้อนเลขฐาน 16 (0-9, A-F) — ทุก 1 ตัวอักษร = 4 bits เช่น <em>FF = 11111111</em><br><br><span style=\"color:var(--green);font-weight:700;\">🏆 คุณภาพการสุ่ม: สูงที่สุดเช่นกัน</span> และนิยมใช้เพราะพิมพ์น้อยกว่า Binary ถึง 4 เท่า — 128 bits = แค่ 32 ตัวอักษร ในทางปฏิบัติสามารถใช้ <strong>ลูกเต๋า 16 หน้า (D16)</strong> ทอยหนึ่งครั้งได้ 1 ตัวอักษร Hex พอดี',
    dice:   '<strong style="color:var(--btc)">Dice Mode:</strong> ป้อนตัวเลขจากการทอยลูกเต๋า (1-6) — วิธีนี้ \"สุ่มจริง\" ที่สุดเพราะไม่ผ่านซอฟต์แวร์ใดๆ<br><br><span style=\"color:var(--yellow);font-weight:700;\">⚠️ คุณภาพการสุ่ม: ลดลงเล็กน้อย</span> — ลูกเต๋า 6 หน้าให้ค่า 1-6 ซึ่งไม่ใช่ power of 2 จึงได้เพียง log₂(6) ≈ 2.585 bits ต่อลูก (ไม่ใช่ 3 bits เต็ม) หมายความว่าต้องทอยมากกว่า และค่าบางตัวมีโอกาสออกบ่อยกว่าเล็กน้อย — ยังปลอดภัยมาก แต่ efficiency ต่ำกว่า Hex/Binary'
};

function updateInputMode() {
    const type = document.querySelector('input[name="inputType"]:checked').value;
    const input = document.getElementById('entropyInput');
    input.value = '';
    const placeholders = {
        binary: `ป้อนเลขไบนารี่: 0101100110... (ต้องการ ${targetBits} bits)`,
        hex:    `ป้อน Hex: A4F29C3E... (ต้องการ ${targetBits/4} chars)`,
        dice:   `ทอยลูกเต๋า: 1532461326... (ต้องการ ~${Math.ceil(targetBits/2.585)} ลูก)`
    };
    input.placeholder = placeholders[type];
    document.getElementById('modeExplain').innerHTML = modeExplains[type];
    document.getElementById('diceVisual').style.display = type === 'dice' ? 'block' : 'none';
    checkInput();
}

document.getElementById('entropyInput').addEventListener('input', function() {
    const type = document.querySelector('input[name="inputType"]:checked').value;
    let val = this.value;
    const orig = val;
    if(type === 'binary') val = val.replace(/[^01]/g, '');
    else if(type === 'hex') val = val.replace(/[^0-9a-fA-F]/g, '').toUpperCase();
    else if(type === 'dice') val = val.replace(/[^1-6]/g, '');
    if(val !== orig) this.value = val;

    // Dice visual
    if(type === 'dice') {
        const faces = val.split('').slice(-10).map(d => `<span class="dice-face">${diceFaces[parseInt(d)]}</span>`).join('');
        document.getElementById('dicePreview').innerHTML = faces || '<span style="color:var(--text3)">ทอยลูกเต๋าแล้วพิมพ์ผลลัพธ์</span>';
    }
    checkInput();
});

function checkInput() {
    const val = document.getElementById('entropyInput').value.trim();
    const type = document.querySelector('input[name="inputType"]:checked').value;
    let bits = 0;
    if(type === 'binary') bits = val.length;
    else if(type === 'hex') bits = val.length * 4;
    else if(type === 'dice') bits = Math.floor(val.length * 2.585);

    document.getElementById('bitCountDisplay').innerText = `${bits} / ${targetBits}`;
    const pct = Math.min((bits/targetBits)*100, 100);
    const fill = document.getElementById('entropyFill');
    fill.style.width = pct + '%';

    const btn = document.getElementById('btnStep1Next');
    const counter = document.getElementById('bitCountDisplay');
    const ql = document.getElementById('qualityLabel');

    if(bits >= targetBits) {
        btn.disabled = false;
        counter.style.color = 'var(--green)';
        fill.style.background = 'var(--green)';
        ql.innerHTML = '<span style="color:var(--green);">✅ ครบถ้วน — พร้อมดำเนินการต่อ</span>';
    } else {
        btn.disabled = true;
        counter.style.color = 'var(--red)';
        fill.style.background = pct > 60 ? 'var(--yellow)' : 'var(--red)';
        const rem = targetBits - bits;
        ql.innerHTML = `<span style="color:var(--text3);">ต้องการอีก ${rem} bits</span>`;
    }
}

function doRandom() {
    const type = document.querySelector('input[name="inputType"]:checked').value;
    let res = '';
    if(type === 'binary') {
        const arr = new Uint8Array(Math.ceil(targetBits/8));
        crypto.getRandomValues(arr);
        for(const b of arr) res += b.toString(2).padStart(8,'0');
        res = res.substring(0, targetBits);
    } else if(type === 'hex') {
        const arr = new Uint8Array(Math.ceil(targetBits/8));
        crypto.getRandomValues(arr);
        res = Array.from(arr).map(b=>b.toString(16).padStart(2,'0')).join('').toUpperCase();
        res = res.substring(0, Math.ceil(targetBits/4));
    } else {
        const arr = new Uint8Array(Math.ceil(targetBits / 2.585));
        crypto.getRandomValues(arr);
        res = Array.from(arr).map(b=>(b%6)+1).join('');
        res = res.substring(0, Math.ceil(targetBits/2.585));
    }
    document.getElementById('entropyInput').value = res;
    checkInput();
}

// ─── Step 2: Checksum ─────────────────────────────────────────────────────────
async function calcChecksum() {
    const raw = document.getElementById('entropyInput').value;
    const type = document.querySelector('input[name="inputType"]:checked').value;

    // Animate SHA flow
    const flowEl = document.getElementById('shaFlowViz');
    flowEl.innerHTML = `
        <div class="sha-flow">
            <div class="sha-block" style="background:rgba(247,147,26,0.15);border:1px solid rgba(247,147,26,0.3);color:var(--btc);animation-delay:0s">📥 Entropy (${targetBits} bits)</div>
            <span style="color:var(--text3);animation-delay:0.2s" class="sha-block">→</span>
            <div class="sha-block" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.3);color:var(--blue);animation-delay:0.4s">⚙️ SHA-256</div>
            <span style="color:var(--text3);animation-delay:0.6s" class="sha-block">→</span>
            <div class="sha-block" style="background:rgba(0,255,136,0.08);border:1px solid rgba(0,255,136,0.2);color:var(--green);animation-delay:0.8s">🔑 256-bit Hash</div>
        </div>`;

    // Convert to binary
    if(type === 'binary') {
        finalBin = raw.replace(/[^01]/g,'').substring(0, targetBits);
    } else if(type === 'hex') {
        const clean = raw.replace(/[^0-9a-fA-F]/g,'');
        finalBin = '';
        for(const c of clean) finalBin += parseInt(c,16).toString(2).padStart(4,'0');
        finalBin = finalBin.substring(0, targetBits);
    } else {
        const msgBuffer = new TextEncoder().encode(raw);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        let hex = hashArray.map(b=>b.toString(16).padStart(2,'0')).join('');
        finalBin = '';
        for(const c of hex) finalBin += parseInt(c,16).toString(2).padStart(4,'0');
        finalBin = finalBin.substring(0, targetBits);
    }

    document.getElementById('dispEntropy').innerText = finalBin;
    document.getElementById('dispHash').innerHTML = '<span style="color:var(--text3);">⏳ กำลังคำนวณ SHA-256...</span>';
    document.getElementById('csExtractArea').style.display = 'none';
    document.getElementById('csResultBox').style.display = 'none';
    document.getElementById('btnStep2Next').disabled = true;

    // SHA-256 compute
    await new Promise(r=>setTimeout(r,700));
    const len = finalBin.length / 8;
    const arr = new Uint8Array(len);
    for(let i=0;i<len;i++) arr[i] = parseInt(finalBin.substring(i*8,i*8+8),2);
    const hashBuffer = await crypto.subtle.digest('SHA-256', arr);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b=>b.toString(16).padStart(2,'0')).join('');

    // Display hash with first byte highlighted
    const firstTwo = hashHex.substring(0,2);
    const remaining = hashHex.substring(2);
    document.getElementById('dispHash').innerHTML =
        `<span class="hex-hl">${firstTwo}</span><span style="color:var(--blue);opacity:0.7;">${remaining}</span>`;

    // Checksum extraction
    const csLen = targetBits / 32;
    document.getElementById('csLen').innerText = csLen;

    const firstByteBin = parseInt(firstTwo,16).toString(2).padStart(8,'0');
    const highlightBits = firstByteBin.substring(0, csLen);
    const restBits = firstByteBin.substring(csLen);

    document.getElementById('csExtractArea').style.display = 'block';
    document.getElementById('csVizArea').innerHTML = `
        <div style="margin-bottom:12px;">
            <div style="font-size:0.72rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:8px;">FIRST BYTE BREAKDOWN</div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <div style="background:rgba(56,189,248,0.15);border:1px solid rgba(56,189,248,0.3);border-radius:8px;padding:8px 14px;">
                    <div style="font-size:0.65rem;color:var(--text3);">HEX</div>
                    <div class="mono" style="color:var(--blue);font-size:1.1rem;font-weight:700;">${firstTwo}</div>
                </div>
                <div style="color:var(--text3);">→</div>
                <div style="background:rgba(0,0,0,0.4);border:1px solid var(--border);border-radius:8px;padding:8px 14px;">
                    <div style="font-size:0.65rem;color:var(--text3);">BINARY (8 bits)</div>
                    <div class="mono" style="font-size:1.1rem;font-weight:700;">
                        <span style="color:var(--btc);text-shadow:0 0 10px rgba(247,147,26,0.5);">${highlightBits}</span><span style="color:var(--text3);opacity:0.4;">${restBits}</span>
                    </div>
                </div>
                <div style="color:var(--text3);">→</div>
                <div style="background:rgba(247,147,26,0.1);border:1px solid rgba(247,147,26,0.3);border-radius:8px;padding:8px 14px;">
                    <div style="font-size:0.65rem;color:var(--text3);">CHECKSUM</div>
                    <div class="mono brand" style="color:var(--btc);font-size:1.1rem;font-weight:700;">${highlightBits}</div>
                </div>
            </div>
        </div>
        <div style="font-size:0.8rem;color:var(--text3);">เราใช้แค่ <strong style="color:var(--btc);">${csLen} bits แรก</strong> จากทั้งหมด 8 bits ของ byte แรก</div>`;

    checksumBin = highlightBits;
    document.getElementById('csResultBox').style.display = 'block';
    document.getElementById('dispCsBits').innerText = checksumBin;
    document.getElementById('btnStep2Next').disabled = false;
}

// ─── Step 3: Combine ──────────────────────────────────────────────────────────
function combineData() {
    fullBin = finalBin + checksumBin;
    const csLen = checksumBin.length;
    const entropyLen = finalBin.length;

    // Render combined display with color coding
    const el = document.getElementById('combinedDisplay');
    el.innerHTML = `<span style="color:var(--text);">${finalBin}</span><span style="color:var(--btc);font-weight:700;border:1px solid rgba(247,147,26,0.4);border-radius:3px;padding:1px 3px;">${checksumBin}</span>`;

    document.getElementById('s3Entropy').innerText = entropyLen;
    document.getElementById('s3Cs').innerText = csLen;
    document.getElementById('s3Total').innerText = fullBin.length;
    document.getElementById('f1').innerText = entropyLen;
    document.getElementById('f2').innerText = csLen;
    document.getElementById('f3').innerText = wordCount;
}

// ─── Step 4: Slicing ──────────────────────────────────────────────────────────
function setupGrid() {
    const grid = document.getElementById('wordGrid');
    grid.innerHTML = '';
    for(let i=0;i<wordCount;i++) {
        grid.innerHTML += `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="word-chip" id="wc-${i}">
                    <div class="word-num">#${i+1}</div>
                    <div class="word-val">···</div>
                </div>
            </div>`;
    }

    const stream = document.getElementById('fullBitsDisplay');
    stream.innerHTML = '';
    for(let i=0;i<wordCount;i++) {
        const chunk = fullBin.substring(i*11, i*11+11);
        const sp = document.createElement('span');
        sp.id = `cs-${i}`;
        sp.className = 'bit-chunk';
        sp.textContent = chunk + ' ';
        stream.appendChild(sp);
    }

    currentWordIndex = 0;
    document.getElementById('wordProgress').innerHTML = `Word 0 / <span id="wTotal">${wordCount}</span>`;
    document.getElementById('sliceStage').innerHTML = `
        <div style="text-align:center;color:var(--text3);">
            <div style="font-size:2rem;margin-bottom:8px;">👆</div>
            <div style="font-size:0.85rem;">กด "Next Word" เพื่อเริ่มประมวลผล</div>
        </div>`;

    const btn = document.getElementById('btnSlice');
    btn.innerHTML = '<i class="fa-solid fa-play me-1"></i> Next Word';
    btn.style.background = 'rgba(0,255,136,0.1)';
    btn.style.borderColor = 'var(--green2)';
    btn.style.color = 'var(--green)';
}

function sliceNextWord() {
    if(currentWordIndex >= wordCount) {
        goToStep(5); showFinal(); return;
    }

    const chunk = fullBin.substring(currentWordIndex*11, currentWordIndex*11+11);
    const decimal = parseInt(chunk, 2);
    const word = bip39Words[decimal] || `word${decimal}`;

    // Highlight bit stream
    for(let i=0;i<wordCount;i++) {
        const sp = document.getElementById(`cs-${i}`);
        if(!sp) continue;
        sp.classList.remove('active','processed');
        if(i < currentWordIndex) sp.classList.add('processed');
        else if(i === currentWordIndex) sp.classList.add('active');
    }

    // Show conversion panel
    const csInfo = currentWordIndex >= wordCount - (checksumBin.length / 11)
        ? `<span style="font-size:0.72rem;color:var(--btc);margin-top:4px;display:block;">⚡ รวม checksum bits</span>` : '';

    document.getElementById('sliceStage').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr auto 1fr auto 1fr;align-items:center;gap:8px;width:100%;padding:8px;">
            <div style="text-align:center;">
                <div style="font-size:0.65rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">BINARY (11 bits)</div>
                <div class="mono" style="color:var(--yellow);font-size:0.95rem;font-weight:700;word-break:break-all;">${chunk}</div>
                ${csInfo}
            </div>
            <div style="color:var(--text3);font-size:1.2rem;">→</div>
            <div style="text-align:center;">
                <div style="font-size:0.65rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">INDEX (DEC)</div>
                <div class="mono" style="color:var(--blue);font-size:1.4rem;font-weight:700;">${decimal}</div>
            </div>
            <div style="color:var(--text3);font-size:1.2rem;">→</div>
            <div style="text-align:center;">
                <div style="font-size:0.65rem;color:var(--text3);font-family:'Orbitron',sans-serif;letter-spacing:2px;margin-bottom:6px;">WORD #${currentWordIndex+1}</div>
                <div class="mono" style="color:var(--green);font-size:1.3rem;font-weight:700;text-transform:uppercase;">${word}</div>
            </div>
        </div>`;

    // Fill chip
    const chip = document.getElementById(`wc-${currentWordIndex}`);
    chip.classList.add('filled');
    chip.querySelector('.word-val').innerText = word;
    chip.querySelector('.word-val').classList.add('active');

    document.getElementById('wordProgress').innerHTML = `Word ${currentWordIndex+1} / <span id="wTotal">${wordCount}</span>`;
    currentWordIndex++;

    if(currentWordIndex >= wordCount) {
        const btn = document.getElementById('btnSlice');
        btn.innerHTML = 'Show Result <i class="fa-solid fa-check ms-1"></i>';
        btn.style.background = 'var(--green2)';
        btn.style.color = '#000';
        btn.style.borderColor = 'var(--green2)';
        btn.style.fontWeight = '700';
    }
}

function showFinal() {
    document.getElementById('finishSubtext').innerText = `${wordCount} Words Generated Successfully`;
    const list = document.getElementById('finalList');
    list.innerHTML = '';
    for(let i=0;i<wordCount;i++) {
        const word = document.querySelector(`#wc-${i} .word-val`)?.innerText || '';
        list.innerHTML += `
            <div class="col-6 col-md-3">
                <div class="final-word-card" style="animation-delay:${i*0.05}s;">
                    <span class="mono" style="color:var(--text3);font-size:0.75rem;min-width:22px;">${i+1}.</span>
                    <span class="mono" style="color:var(--text);font-weight:700;">${word}</span>
                </div>
            </div>`;
    }
}
</script>
</body>
</html>