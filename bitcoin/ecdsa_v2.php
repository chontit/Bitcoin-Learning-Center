<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>ECDSA Visualizer | Real vs Finite Field</title>
<link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Kanit:wght@300;400;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --neon-btc: #F7931A; --neon-green: #00ff41; --neon-blue: #00f3ff;
  --neon-red: #ff0055; --neon-purple: #bf5fff; --bg-dark: #050505;
  --glass: rgba(255,255,255,0.04);
}
body { font-family:'Kanit',sans-serif; background-color:var(--bg-dark); color:#e0e0e0;
  background-image: radial-gradient(circle at 10% 20%,rgba(247,147,26,.04) 0%,transparent 25%),
                    radial-gradient(circle at 90% 80%,rgba(0,243,255,.04) 0%,transparent 25%);
  overflow-x:hidden; min-height:100vh; display:flex; flex-direction:column; font-weight:300; }
.brand-font { font-family:'Chakra Petch',sans-serif; letter-spacing:.5px; }
.mono-font  { font-family:'Share Tech Mono',monospace; }
::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:#111; }
::-webkit-scrollbar-thumb { background:#333; border-radius:3px; }
::-webkit-scrollbar-thumb:hover { background:var(--neon-btc); }
.neon-box { background:var(--glass); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.08);
  box-shadow:0 0 15px rgba(0,0,0,.5); transition:all .3s ease; }
.neon-box:hover { border-color:rgba(247,147,26,.4); box-shadow:0 0 20px rgba(247,147,26,.12); }
.btn-action { background:linear-gradient(45deg,#F7931A,#ffb347); color:#000; font-weight:700;
  font-family:'Chakra Petch',sans-serif; text-transform:uppercase; letter-spacing:1px; transition:.2s;
  clip-path:polygon(8px 0,100% 0,100% calc(100% - 8px),calc(100% - 8px) 100%,0 100%,0 8px); }
.btn-action:hover { transform:translateY(-2px); box-shadow:0 0 25px rgba(247,147,26,.6); }
.btn-action:active { transform:translateY(1px); }
.btn-secondary { border:1px solid #444; color:#aaa; font-family:'Chakra Petch',sans-serif; transition:.3s; }
.btn-secondary:hover { border-color:#fff; color:#fff; background:rgba(255,255,255,.1); }
.btn-reset { border:1px solid var(--neon-red); color:var(--neon-red); font-family:'Chakra Petch',sans-serif; transition:.3s; }
.btn-reset:hover { background:var(--neon-red); color:#fff; box-shadow:0 0 15px var(--neon-red); }
.btn-green { background:rgba(0,255,65,.1); border:1px solid var(--neon-green); color:var(--neon-green);
  font-family:'Chakra Petch',sans-serif; transition:.3s; }
.btn-green:hover { background:var(--neon-green); color:#000; box-shadow:0 0 20px rgba(0,255,65,.4); }
.btn-blue { background:rgba(0,243,255,.1); border:1px solid var(--neon-blue); color:var(--neon-blue);
  font-family:'Chakra Petch',sans-serif; transition:.3s; }
.btn-blue:hover { background:var(--neon-blue); color:#000; box-shadow:0 0 20px rgba(0,243,255,.4); }
.btn-purple { background:rgba(191,95,255,.1); border:1px solid var(--neon-purple); color:var(--neon-purple);
  font-family:'Chakra Petch',sans-serif; transition:.3s; }
.btn-purple:hover { background:var(--neon-purple); color:#fff; box-shadow:0 0 20px rgba(191,95,255,.4); }
.mode-btn { padding:8px 16px; border:1px solid #333; font-family:'Chakra Petch',sans-serif;
  font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px;
  transition:all .3s; cursor:pointer; }
.mode-btn.active-real { background:linear-gradient(135deg,rgba(247,147,26,.25),rgba(247,147,26,.1));
  border-color:var(--neon-btc); color:var(--neon-btc);
  box-shadow:0 0 15px rgba(247,147,26,.3),inset 0 0 15px rgba(247,147,26,.05); }
.mode-btn.active-finite { background:linear-gradient(135deg,rgba(191,95,255,.25),rgba(191,95,255,.1));
  border-color:var(--neon-purple); color:var(--neon-purple);
  box-shadow:0 0 15px rgba(191,95,255,.3),inset 0 0 15px rgba(191,95,255,.05); }
.mode-btn:not(.active-real):not(.active-finite):hover { border-color:#666; color:#fff; }
#canvas-container { position:relative; width:100%; height:100%; min-height:400px;
  background:radial-gradient(circle at center,#111 0%,#000 100%);
  border-radius:12px; overflow:hidden; border:1px solid #222; cursor:grab; }
#canvas-container:active { cursor:grabbing; }
canvas { display:block; }
.overlay-bg { background:rgba(0,0,0,.96); backdrop-filter:blur(16px); z-index:100; }
.hex-string { word-break:break-all; font-family:'Share Tech Mono',monospace; line-height:1.4; font-size:.75rem; }
.key-display { background:rgba(0,0,0,.6); border-left:2px solid var(--neon-blue); padding:8px; font-size:.8em; color:var(--neon-blue); }
.algo-table th { text-align:left; padding:8px; border-bottom:1px solid #333; color:#888; font-size:.75rem; white-space:nowrap; }
.algo-table td { padding:8px; border-bottom:1px solid #1a1a1a; font-family:'Share Tech Mono',monospace; font-size:.75rem; }
.algo-table tr:hover { background:rgba(255,255,255,.04); }
.ff-point { display:inline-flex; align-items:center; justify-content:center;
  width:28px; height:28px; border-radius:50%; border:1px solid #333;
  cursor:pointer; transition:all .2s; position:relative; font-size:8px; color:transparent; font-weight:700; }
.ff-point:hover { transform:scale(1.35); z-index:5; color:white; }
.ff-point.valid { background:rgba(0,243,255,.3); border-color:var(--neon-blue); box-shadow:0 0 6px rgba(0,243,255,.4); }
.ff-point.p1-dot { background:rgba(247,147,26,.7); border-color:var(--neon-btc); box-shadow:0 0 10px rgba(247,147,26,.7); color:white; }
.ff-point.p2-dot { background:rgba(191,95,255,.7); border-color:var(--neon-purple); box-shadow:0 0 10px rgba(191,95,255,.7); color:white; }
.math-step { animation:fadeIn .4s ease-in; }
@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.mod-step { border-left:2px solid #333; padding:6px 12px; margin:4px 0;
  font-family:'Share Tech Mono',monospace; font-size:.75rem; }
.mod-step.highlight { border-color:var(--neon-btc); background:rgba(247,147,26,.07); }
input[type=range] { -webkit-appearance:none; appearance:none; background:transparent; cursor:pointer; width:100%; }
input[type=range]::-webkit-slider-runnable-track { background:linear-gradient(90deg,var(--neon-purple),var(--neon-blue)); border-radius:99px; height:4px; }
input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; width:16px; height:16px; border-radius:50%;
  background:var(--neon-btc); border:2px solid #000; box-shadow:0 0 8px var(--neon-btc); margin-top:-6px; }
.prime-btn { padding:4px 8px; border-radius:4px; font-size:.7rem; font-family:'Share Tech Mono',monospace;
  border:1px solid #555; color:#aaa; cursor:pointer; transition:all .2s; background:transparent; }
.prime-btn:hover, .prime-btn.active { border-color:var(--neon-purple); color:var(--neon-purple); background:rgba(191,95,255,.1); }
/* ── Guide / Explain Overlay ─── */
.guide-tab{padding:8px 14px;border-bottom:2px solid transparent;font-family:'Chakra Petch',sans-serif;
  font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
  cursor:pointer;color:#666;transition:all .2s;white-space:nowrap;}
.guide-tab.active{color:var(--neon-btc);border-color:var(--neon-btc);}
.guide-tab:hover:not(.active){color:#aaa;border-color:#444;}
.guide-step{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);
  border-radius:10px;padding:14px;margin-bottom:10px;}
.guide-step-num{width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;
  justify-content:center;font-family:'Chakra Petch',sans-serif;font-weight:700;font-size:.8rem;flex-shrink:0;}
.info-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:12px;}
.concept-badge{display:inline-block;padding:2px 8px;border-radius:4px;font-size:.65rem;
  font-family:'Chakra Petch',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.5px;}
.analogy-box{background:rgba(247,147,26,.06);border-left:3px solid var(--neon-btc);
  border-radius:0 8px 8px 0;padding:10px 14px;margin:8px 0;}
.warning-box{background:rgba(255,0,85,.06);border-left:3px solid var(--neon-red);
  border-radius:0 8px 8px 0;padding:10px 14px;margin:8px 0;}
.tip-box{background:rgba(0,255,65,.05);border-left:3px solid var(--neon-green);
  border-radius:0 8px 8px 0;padding:10px 14px;margin:8px 0;}
.formula-box{background:#0a0a0a;border:1px solid #333;border-radius:8px;padding:12px;
  font-family:'Share Tech Mono',monospace;text-align:center;margin:8px 0;}
.color-dot{width:12px;height:12px;border-radius:50%;display:inline-block;flex-shrink:0;}
.btn-yellow{background:rgba(255,220,0,.1);border:1px solid #ffd700;color:#ffd700;
  font-family:'Chakra Petch',sans-serif;transition:.3s;}
.btn-yellow:hover{background:#ffd700;color:#000;box-shadow:0 0 20px rgba(255,215,0,.4);}
@media(max-width:767px){.algo-table th,.algo-table td{font-size:.7rem;}.guide-tab{font-size:.6rem;padding:6px 8px;}}
</style>
</head>
<body class="text-gray-300">

<!-- HEADER -->
<header class="w-full px-4 py-3 border-b border-gray-800 flex justify-between items-center bg-black/80 sticky top-0 z-50 backdrop-blur-md h-16 shrink-0">
  <div class="flex items-center gap-3">
    <i class="fa-brands fa-bitcoin text-3xl text-[#F7931A] animate-pulse"></i>
    <div>
      <h1 class="text-lg md:text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font leading-none uppercase">ECDSA Visualizer</h1>
      <p class="text-[10px] text-gray-500 mono-font tracking-wider mt-0.5">Secp256k1 · Real ↔ Finite Field</p>
    </div>
  </div>
  <div class="flex gap-2">
    <button onclick="showOverlay('guide-overlay')" class="px-3 py-1 rounded border border-[#ffd700]/50 hover:border-[#ffd700] hover:text-[#ffd700] text-[#ffd700]/70 transition text-xs flex items-center gap-1.5 font-bold" style="font-family:'Chakra Petch',sans-serif;">
      <i class="fa-solid fa-book-open-reader"></i><span class="hidden sm:inline">วิธีเล่น</span>
    </button>
    <a href="/" class="px-3 py-1 rounded border border-gray-700 hover:border-[#F7931A] hover:text-[#F7931A] transition text-xs flex items-center gap-2">
      <i class="fa-solid fa-house"></i><span class="hidden md:inline">หน้าหลัก</span>
    </a>
  </div>
</header>

<!-- ══ OVERLAY: GUIDE ════════════════════════════════════════════════════ -->
<div id="guide-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-2 md:p-4" style="z-index:200;">
  <div class="max-w-4xl w-full h-[98vh] md:h-[92vh] border border-[#ffd700]/40 bg-[#060606] rounded-xl shadow-[0_0_60px_rgba(255,215,0,.15)] relative flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/60 shrink-0">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-book-open-reader text-[#ffd700] text-xl"></i>
        <div>
          <h2 class="text-base font-bold text-[#ffd700] brand-font uppercase">วิธีเล่นและเรียนรู้</h2>
          <p class="text-[10px] text-gray-500">ECDSA Visualizer — คู่มือฉบับสมบูรณ์</p>
        </div>
      </div>
      <button onclick="hideOverlay('guide-overlay')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></button>
    </div>
    <div class="flex border-b border-gray-800 bg-black/40 overflow-x-auto shrink-0">
      <button class="guide-tab active" onclick="showGuideTab('tab-what')"><i class="fa-solid fa-circle-question mr-1"></i>ECDSA คืออะไร</button>
      <button class="guide-tab" onclick="showGuideTab('tab-real')"><i class="fa-solid fa-wave-square mr-1"></i>Real Field</button>
      <button class="guide-tab" onclick="showGuideTab('tab-mod')"><i class="fa-solid fa-hashtag mr-1"></i>Mod คืออะไร</button>
      <button class="guide-tab" onclick="showGuideTab('tab-finite')"><i class="fa-solid fa-chart-scatter mr-1"></i>Finite Field</button>
      <button class="guide-tab" onclick="showGuideTab('tab-howto')"><i class="fa-solid fa-gamepad mr-1"></i>วิธีใช้งาน</button>
      <button class="guide-tab" onclick="showGuideTab('tab-bitcoin')"><i class="fa-brands fa-bitcoin mr-1"></i>Bitcoin เชื่อมยังไง</button>
    </div>
    <div class="flex-grow overflow-y-auto p-4 md:p-6">

      <!-- TAB: ECDSA คืออะไร -->
      <div id="tab-what" class="guide-tab-content">
        <h3 class="text-lg font-bold text-[#ffd700] brand-font mb-4">ECDSA คืออะไร และทำไมต้องรู้?</h3>
        <div class="analogy-box mb-4">
          <div class="text-[#F7931A] font-bold text-xs brand-font mb-1">🎯 เปรียบเทียบให้เข้าใจง่าย</div>
          <div class="text-sm text-gray-300 leading-relaxed">นึกถึง <b class="text-white">กุญแจ Bitcoin</b> เหมือน Mission Authorization ใน F-16 — <b class="text-[#F7931A]">Private Key</b> คือ PIN ส่วนตัว <b class="text-[#00f3ff]">Public Key</b> คือหมายเลขเครื่องที่ทุกคนเห็น <b class="text-[#00ff41]">Signature</b> คือการพิสูจน์ว่าคุณคือผู้บังคับการจริง โดยไม่ต้องเปิดเผย PIN</div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
          <div class="info-card text-center"><div class="text-3xl mb-2">🔑</div><div class="text-[#F7931A] font-bold text-xs brand-font mb-1">PRIVATE KEY (d)</div><div class="text-xs text-gray-400 leading-relaxed">ตัวเลขสุ่ม 256 บิต — <b class="text-white">อย่าบอกใคร</b></div></div>
          <div class="info-card text-center"><div class="text-3xl mb-2">📍</div><div class="text-[#00f3ff] font-bold text-xs brand-font mb-1">PUBLIC KEY (P)</div><div class="text-xs text-gray-400 leading-relaxed">พิกัด (x,y) บนเส้นโค้ง — แชร์ได้ปลอดภัย</div></div>
          <div class="info-card text-center"><div class="text-3xl mb-2">✍️</div><div class="text-[#00ff41] font-bold text-xs brand-font mb-1">SIGNATURE</div><div class="text-xs text-gray-400 leading-relaxed">พิสูจน์ความเป็นเจ้าของ โดยไม่เปิด Private Key</div></div>
        </div>
        <div class="formula-box mb-4"><div class="text-gray-500 text-[10px] mb-2">สมการหลัก:</div><span class="text-[#00ff41] text-lg">P</span> <span class="text-gray-400 mx-2">=</span> <span class="text-white text-lg">d</span> <span class="text-gray-400 mx-2">×</span> <span class="text-[#00f3ff] text-lg">G</span> <span class="text-gray-600 ml-2 text-sm">(mod p)</span><div class="text-[10px] text-gray-600 mt-2">Public Key = Private Key × Generator Point ใน Finite Field</div></div>
        <div class="guide-step mb-3">
          <div class="text-[#ffd700] font-bold text-xs brand-font mb-2">❓ ทำไมปลอดภัย?</div>
          <div class="text-xs text-gray-300 leading-relaxed space-y-1">
            <p>• <b class="text-white">ไปข้างหน้าง่าย:</b> รู้ d → คำนวณ P ได้เลยด้วย Double-and-Add (เร็วมาก)</p>
            <p>• <b class="text-white">ย้อนกลับยากเป็นไปไม่ได้:</b> รู้ P และ G → ต้องลอง 2²⁵⁶ ความเป็นไปได้ = ใช้เวลาหลักล้านปี</p>
            <p>• นี่คือ <b class="text-[#ffd700]">Discrete Logarithm Problem</b> — รากฐานความปลอดภัย Bitcoin</p>
          </div>
        </div>
        <div class="tip-box"><div class="text-[#00ff41] font-bold text-xs brand-font mb-1">💡 ต่อไป</div><div class="text-xs text-gray-300">คลิก Tab <b class="text-[#F7931A]">"Real Field"</b> เพื่อดูเส้นโค้ง → แล้วตามด้วย <b class="text-[#bf5fff]">"Mod คืออะไร"</b> → <b class="text-[#bf5fff]">"Finite Field"</b></div></div>
      </div>

      <!-- TAB: Real Field -->
      <div id="tab-real" class="guide-tab-content hidden">
        <h3 class="text-lg font-bold text-[#F7931A] brand-font mb-4">Real Field — เส้นโค้งในโลกเลขจริง</h3>
        <div class="analogy-box mb-4"><div class="text-[#F7931A] font-bold text-xs brand-font mb-1">🛩️ Analogy: VFR Navigation</div><div class="text-sm text-gray-300 leading-relaxed">Real Field เหมือนบิน VFR — เห็น terrain ชัด เส้นโค้งต่อเนื่องเข้าใจง่าย แต่ใช้จริงไม่ได้เพราะ decimal ไม่รู้จบ</div></div>
        <div class="guide-step mb-3">
          <div class="text-white font-bold text-sm mb-2">สมการ: y² = x³ + 7</div>
          <div class="formula-box my-2"><span class="text-[#F7931A]">y²</span> = <span class="text-white">x³</span> + <span class="text-gray-400">7</span><div class="text-[10px] text-gray-600 mt-1">ทุกจุด (x,y) บนโค้งต้องผ่านสมการนี้</div></div>
          <div class="text-xs text-gray-400 leading-relaxed">กราฟมี 2 ส่วน: บนและล่างแกน X (สมมาตรกัน เพราะ y² → มีทั้ง +y และ -y)</div>
        </div>
        <div class="guide-step mb-3">
          <div class="text-white font-bold text-sm mb-2">กฎ Point Addition</div>
          <div class="space-y-2">
            <div class="info-card flex items-start gap-2"><div class="color-dot mt-1" style="background:#ff0055;"></div><div class="text-xs text-gray-300"><b class="text-white">ลากเส้นตรง</b>ผ่าน P1, P2 → ตัดโค้งที่จุดที่ 3</div></div>
            <div class="info-card flex items-start gap-2"><div class="color-dot mt-1" style="background:#00ff41;"></div><div class="text-xs text-gray-300"><b class="text-white">สะท้อนจุดที่ 3</b>ผ่านแกน X (y → -y) → ได้ P1+P2</div></div>
            <div class="info-card flex items-start gap-2"><div class="color-dot mt-1" style="background:#F7931A;"></div><div class="text-xs text-gray-300"><b class="text-white">Point Doubling:</b> P1=P2 → ลากเส้นสัมผัส (Tangent) แทน</div></div>
          </div>
        </div>
        <div class="warning-box"><div class="text-[#ff0055] font-bold text-xs brand-font mb-1">⚠️ ทำไม Real Field ไม่ใช้จริง?</div><div class="text-xs text-gray-300">เลขจริงมี decimal ไม่รู้จบ → floating point error → ไม่แม่นยำ 100% → Bitcoin ต้องใช้ <b class="text-[#bf5fff]">Finite Field</b> แทน</div></div>
      </div>

      <!-- TAB: Mod -->
      <div id="tab-mod" class="guide-tab-content hidden">
        <h3 class="text-lg font-bold text-[#00f3ff] brand-font mb-4">Modular Arithmetic คืออะไร?</h3>
        <div class="analogy-box mb-4"><div class="text-[#F7931A] font-bold text-xs brand-font mb-1">🕐 Analogy: นาฬิกา</div><div class="text-sm text-gray-300 leading-relaxed">mod คือ "นาฬิกาคณิตศาสตร์" — 10 โมง + 5 ชั่วโมง = <b class="text-[#ffd700]">3 โมง</b> ไม่ใช่ 15 โมง เพราะ 15 mod 12 = 3</div></div>
        <div class="guide-step mb-4">
          <div class="text-white font-bold text-sm mb-3">ตัวอย่าง mod</div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div class="info-card text-center"><div class="mono-font text-[#F7931A] text-sm">13 mod 12</div><div class="text-gray-500 text-xs mt-1">= <span class="text-white font-bold">1</span></div><div class="text-[10px] text-gray-600 mt-1">13:00 = 1:00</div></div>
            <div class="info-card text-center"><div class="mono-font text-[#F7931A] text-sm">25 mod 7</div><div class="text-gray-500 text-xs mt-1">= <span class="text-white font-bold">4</span></div><div class="text-[10px] text-gray-600 mt-1">25 = 3×7+4</div></div>
            <div class="info-card text-center"><div class="mono-font text-[#F7931A] text-sm">17 mod 17</div><div class="text-gray-500 text-xs mt-1">= <span class="text-white font-bold">0</span></div><div class="text-[10px] text-gray-600 mt-1">หารลงตัว</div></div>
            <div class="info-card text-center"><div class="mono-font text-[#F7931A] text-sm">3 mod 17</div><div class="text-gray-500 text-xs mt-1">= <span class="text-white font-bold">3</span></div><div class="text-[10px] text-gray-600 mt-1">น้อยกว่า p</div></div>
          </div>
        </div>
        <div class="guide-step mb-4">
          <div class="text-white font-bold text-sm mb-2">Modular Inverse — "หาร" ใน Finite Field</div>
          <div class="text-xs text-gray-400 leading-relaxed mb-3">ใน Finite Field ไม่มีการหารตรงๆ — ใช้การคูณด้วย Inverse แทน<br>ถ้า a × b ≡ 1 (mod p) แล้ว b = a⁻¹</div>
          <div class="info-card mb-2"><div class="text-xs text-gray-300"><b class="text-[#ffd700]">ตัวอย่าง mod 7:</b> 3 × 5 = 15 = 2×7+1 → 15 mod 7 = 1 → ดังนั้น 3⁻¹ = 5</div></div>
          <div class="tip-box"><div class="text-[#00ff41] font-bold text-xs brand-font mb-1">🔑 ทำไมต้องเป็น Prime?</div><div class="text-xs text-gray-300">Prime ทำให้ทุกตัวเลข 1 ถึง p-1 มี Inverse เสมอ (Fermat's Little Theorem: a^(p-2) mod p) ถ้าไม่เป็น prime บางตัวไม่มี inverse → ระบบพัง</div></div>
        </div>
        <div class="guide-step">
          <div class="text-white font-bold text-sm mb-2">Slope (λ) ใน Finite Field</div>
          <div class="formula-box"><span class="text-[#00f3ff]">λ</span> = (<span class="text-white">y₂ − y₁</span>) × (<span class="text-white">x₂ − x₁</span>)<sup>−1</sup> <span class="text-gray-500">mod p</span><div class="text-[10px] text-gray-600 mt-2">ผลลัพธ์ λ, x₃, y₃ จะอยู่ระหว่าง 0 ถึง p-1 เสมอ</div></div>
        </div>
      </div>

      <!-- TAB: Finite Field -->
      <div id="tab-finite" class="guide-tab-content hidden">
        <h3 class="text-lg font-bold text-[#bf5fff] brand-font mb-4">Finite Field — เส้นโค้งในโลก mod p</h3>
        <div class="analogy-box mb-4"><div class="text-[#F7931A] font-bold text-xs brand-font mb-1">🎯 Analogy: IFR Navigation</div><div class="text-sm text-gray-300 leading-relaxed">เหมือนบิน IFR ด้วย Waypoints — ไม่ใช่เส้นต่อเนื่อง แต่กระโดดทีละจุดตาม instrument แต่ละ waypoint แม่นยำ 100% ไม่มี rounding error</div></div>
        <div class="guide-step mb-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="info-card"><div class="text-[#F7931A] font-bold text-xs brand-font mb-2">🌊 Real Field</div><div class="text-xs text-gray-400 space-y-1"><p>• x,y เป็นเลขจริงใดๆ (±∞)</p><p>• เส้นโค้งต่อเนื่อง</p><p>• จุดบนโค้ง: ไม่รู้จบ</p></div></div>
            <div class="info-card" style="border-color:rgba(191,95,255,.3)"><div class="text-[#bf5fff] font-bold text-xs brand-font mb-2">🔢 Finite Field</div><div class="text-xs text-gray-400 space-y-1"><p>• x,y เป็นจำนวนเต็ม 0 ถึง p-1</p><p>• กลายเป็นจุดกระจัดกระจาย</p><p>• จุดบนโค้ง: จำกัด แต่มหาศาล</p></div></div>
          </div>
        </div>
        <div class="guide-step mb-4">
          <div class="text-white font-bold text-sm mb-2">วิธีหาจุดที่อยู่บนโค้ง F_p</div>
          <div class="formula-box mb-3"><span class="text-white">y²</span> ≡ <span class="text-[#F7931A]">x³ + 7</span> <span class="text-gray-500">(mod p)</span><div class="text-[10px] text-gray-600 mt-2">ลองทุก (x,y) จาก 0 ถึง p-1 — คู่ไหนผ่าน = "อยู่บนโค้ง"</div></div>
          <div class="info-card text-xs text-gray-300"><b class="text-[#ffd700]">ตัวอย่าง p=7, x=1:</b> 1³+7=8 → 8 mod 7=1 → ต้องหา y ที่ y²≡1(mod 7) → y=1 (1²=1) และ y=6 (6²=36 mod 7=1) → ได้จุด (1,1) และ (1,6)</div>
        </div>
        <div class="warning-box"><div class="text-[#ff0055] font-bold text-xs brand-font mb-1">🔒 นี่คือความปลอดภัย!</div><div class="text-xs text-gray-300">จุดดูสุ่ม → รู้ G และ P แต่ไม่รู้ว่ากระโดดกี่ครั้ง (d) → Hacker ต้องลองทั้ง 2²⁵⁶ ความเป็นไปได้ → <b class="text-white">เป็นไปไม่ได้ในทางปฏิบัติ</b></div></div>
      </div>

      <!-- TAB: วิธีใช้งาน -->
      <div id="tab-howto" class="guide-tab-content hidden">
        <h3 class="text-lg font-bold text-[#00ff41] brand-font mb-4">วิธีใช้งาน Step-by-Step</h3>
        <div class="mb-2 text-[#F7931A] font-bold text-xs brand-font uppercase tracking-wider">A — Real Field (หน้าหลัก)</div>
        <div class="space-y-2 mb-5 ml-2">
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#F7931A]/20 border border-[#F7931A]/40 text-[#F7931A] shrink-0">1</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">กด "เพิ่ม Key (+1G)"</b> ดูจุดสีเขียวกระโดด สังเกตเส้นประแดง (เส้นตัด) และเส้นเขียว (สะท้อน)</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#F7931A]/20 border border-[#F7931A]/40 text-[#F7931A] shrink-0">2</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">กด "Auto"</b> ให้วิ่งอัตโนมัติ สังเกตว่า d เพิ่มขึ้นเรื่อยๆ แต่ตำแหน่ง P ดูสุ่ม ไม่มีรูปแบบ</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#F7931A]/20 border border-[#F7931A]/40 text-[#F7931A] shrink-0">3</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">กด "G จริง"</b> ดูพิกัด x,y ของ G จริง Bitcoin — 78 หลัก! นี่คือ "จุดเริ่มต้น" ของ Private Key ทุกอันในโลก</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#F7931A]/20 border border-[#F7931A]/40 text-[#F7931A] shrink-0">4</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">กด "Algo Lab"</b> ใส่ 12345 ดูว่าแทน 12345 ops ใช้แค่ ~28 ops! — นี่คือเหตุที่ Bitcoin wallet สร้างได้เร็ว</div></div>
        </div>
        <div class="mb-2 text-[#bf5fff] font-bold text-xs brand-font uppercase tracking-wider">B — Finite Field Lab</div>
        <div class="space-y-2 mb-5 ml-2">
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">1</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">สลับ Mode เป็น 🔢 Finite Field → กด "FF Lab"</b> Canvas เปลี่ยนเป็น Scatter Plot ทันที</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">2</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">ลองเปลี่ยน p</b> เริ่มจาก p=7 (จุดน้อย) ไปถึง p=37 (จุดมาก) สังเกตยิ่ง p ใหญ่ จุดยิ่งดูสุ่ม</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">3</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">คลิกจุดแรก</b> (สีส้ม=P1) → <b class="text-white">คลิกจุดที่สอง</b> (สีม่วง=P2) → กด <b class="text-white">"คำนวณ P1+P2"</b> → ดู R สีแดงบน Plot</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">4</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">ดู step-by-step</b> ด้านซ้าย เห็นสูตร λ mod p ทีละขั้น ลอง verify ด้วยเครื่องคิดเลข</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">5</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">ลอง k×G</b> ใส่ k=1,2,3,4,5 สังเกตว่า k ต่างกันน้อย แต่ผลลัพธ์อยู่คนละจุดมาก (ดูสุ่ม)</div></div>
          <div class="guide-step flex items-start gap-3"><span class="guide-step-num bg-[#bf5fff]/20 border border-[#bf5fff]/40 text-[#bf5fff] shrink-0">6</span><div class="text-xs text-gray-300 leading-relaxed"><b class="text-white">กด "p จริง Bitcoin"</b> ดูตัวเลข 78 หลัก เทียบกับกราฟ Scale เข้าใจทันทีว่าทำไม Brute Force เป็นไปไม่ได้</div></div>
        </div>
        <div class="tip-box"><div class="text-[#00ff41] font-bold text-xs brand-font mb-2">✅ Checklist ความเข้าใจ</div><div class="space-y-1 text-xs text-gray-300"><p>☐ เข้าใจกฎ Point Addition บน Real Field</p><p>☐ เข้าใจว่า mod พับตัวเลขให้อยู่ใน 0 ถึง p-1</p><p>☐ เห็นว่า Finite Field ทำให้จุดดูสุ่ม แม้ใช้สมการเดิม</p><p>☐ เข้าใจว่า d ใหญ่ขึ้น แต่หา d กลับจาก P ไม่ได้</p><p>☐ รู้ว่า p จริงของ Bitcoin ใหญ่แค่ไหน</p></div></div>
      </div>

      <!-- TAB: Bitcoin เชื่อมยังไง -->
      <div id="tab-bitcoin" class="guide-tab-content hidden">
        <h3 class="text-lg font-bold text-[#00f3ff] brand-font mb-4">ECDSA เชื่อมกับ Bitcoin ยังไง?</h3>
        <div class="guide-step mb-4">
          <div class="text-white font-bold text-sm mb-3">Flow สร้าง Bitcoin Address</div>
          <div class="space-y-2">
            <div class="flex items-center gap-3"><div class="w-7 h-7 rounded-full bg-[#F7931A]/20 border border-[#F7931A]/40 flex items-center justify-center text-[#F7931A] font-bold text-xs shrink-0">1</div><div class="flex-grow info-card"><div class="text-[#F7931A] font-bold text-xs">สร้าง Private Key</div><div class="text-xs text-gray-400 mt-0.5">ตัวเลขสุ่ม 256 bit เช่น d = 0xE9873D79...</div></div></div>
            <div class="flex items-center gap-3"><div class="w-7 h-7 rounded-full bg-[#00ff41]/20 border border-[#00ff41]/40 flex items-center justify-center text-[#00ff41] font-bold text-xs shrink-0">2</div><div class="flex-grow info-card"><div class="text-[#00ff41] font-bold text-xs">คำนวณ Public Key</div><div class="text-xs text-gray-400 mt-0.5">P = d × G บน Secp256k1 (Finite Field) → พิกัด (x,y) 256 bit</div></div></div>
            <div class="flex items-center gap-3"><div class="w-7 h-7 rounded-full bg-[#00f3ff]/20 border border-[#00f3ff]/40 flex items-center justify-center text-[#00f3ff] font-bold text-xs shrink-0">3</div><div class="flex-grow info-card"><div class="text-[#00f3ff] font-bold text-xs">Hash → Bitcoin Address</div><div class="text-xs text-gray-400 mt-0.5">SHA256 → RIPEMD160 → Base58Check → 1BvBMSEYstWe...</div></div></div>
          </div>
        </div>
        <div class="guide-step mb-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="info-card" style="border-color:rgba(247,147,26,.3)"><div class="text-[#F7931A] font-bold text-xs brand-font mb-2">✍️ การเซ็น (Sign)</div><div class="text-xs text-gray-400 space-y-1"><p>1. เลือก random k (nonce)</p><p>2. R = k × G → เอาแค่ x</p><p>3. s = k⁻¹ × (hash + d×r) mod n</p><p>4. Signature = (r, s)</p></div></div>
            <div class="info-card" style="border-color:rgba(0,243,255,.3)"><div class="text-[#00f3ff] font-bold text-xs brand-font mb-2">✅ การตรวจสอบ (Verify)</div><div class="text-xs text-gray-400 space-y-1"><p>1. u1 = s⁻¹ × hash mod n</p><p>2. u2 = s⁻¹ × r mod n</p><p>3. R' = u1×G + u2×P</p><p>4. ถ้า R'.x = r → ถูกต้อง</p></div></div>
          </div>
        </div>
        <div class="tip-box"><div class="text-[#00ff41] font-bold text-xs brand-font mb-1">🔑 Magic of ECDSA</div><div class="text-xs text-gray-300">ผู้ตรวจสอบ (ทุก Bitcoin node) ไม่เคยเห็น Private Key — แต่ยืนยันได้ 100% ว่าลายเซ็นมาจากเจ้าของจริง เพราะคณิตศาสตร์ Elliptic Curve รับประกัน</div></div>
      </div>

    </div>
    <div class="p-3 border-t border-gray-800 bg-black/40 shrink-0 flex justify-between items-center">
      <div class="text-[10px] text-gray-600 mono-font">ECDSA Visualizer · Secp256k1 · Educational Only</div>
      <button onclick="hideOverlay('guide-overlay')" class="px-5 py-2 bg-[#ffd700] text-black font-bold rounded hover:bg-[#ffaa00] transition brand-font text-xs uppercase">
        <i class="fa-solid fa-play mr-1"></i> เริ่มเล่นเลย
      </button>
    </div>
  </div>
</div>

<!-- OVERLAY: Real G -->
<div id="real-g-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-6 text-center">
  <div class="max-w-4xl w-full border border-[#00ff41] bg-black/90 p-6 rounded-xl shadow-[0_0_50px_rgba(0,255,65,.2)] relative overflow-y-auto max-h-[95vh]">
    <button onclick="hideOverlay('real-g-overlay')" class="absolute top-4 right-4 text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></button>
    <h2 class="text-2xl font-bold text-[#00ff41] brand-font mb-2 uppercase"><i class="fa-solid fa-fingerprint"></i> จุดกำเนิด G ของจริง (Bitcoin)</h2>
    <p class="text-xs text-gray-400 mb-6">ค่ามาตรฐาน Secp256k1 (ฐาน 10)</p>
    <div class="grid grid-cols-1 gap-4 text-left">
      <div><div class="text-[#F7931A] text-xs font-bold mb-1 brand-font">พิกัด X</div>
        <div class="hex-string text-[#F7931A] border border-[#F7931A]/30 p-3 rounded bg-[#F7931A]/5"><span id="type-x"></span></div></div>
      <div><div class="text-[#00f3ff] text-xs font-bold mb-1 brand-font">พิกัด Y</div>
        <div class="hex-string text-[#00f3ff] border border-[#00f3ff]/30 p-3 rounded bg-[#00f3ff]/5"><span id="type-y"></span></div></div>
    </div>
    <button onclick="hideOverlay('real-g-overlay')" class="mt-5 px-6 py-2 bg-[#00ff41] text-black font-bold rounded hover:bg-[#00cc33] transition brand-font">เข้าใจแล้ว</button>
  </div>
</div>

<!-- OVERLAY: Real p -->
<div id="real-p-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-4 text-center" style="z-index:110;">
  <div class="max-w-3xl w-full border border-[#bf5fff] bg-black/95 p-6 rounded-xl shadow-[0_0_60px_rgba(191,95,255,.25)] relative overflow-y-auto max-h-[95vh]">
    <div class="absolute inset-0 opacity-5" style="background-image:linear-gradient(rgba(191,95,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(191,95,255,.5) 1px,transparent 1px);background-size:24px 24px;"></div>
    <button onclick="hideOverlay('real-p-overlay')" class="absolute top-4 right-4 text-gray-500 hover:text-white z-10"><i class="fa-solid fa-xmark text-2xl"></i></button>
    <div class="relative z-10">
      <div class="flex items-center justify-center gap-3 mb-2">
        <i class="fa-solid fa-hashtag text-3xl text-[#bf5fff]"></i>
        <h2 class="text-xl md:text-2xl font-bold text-[#bf5fff] brand-font uppercase">ค่า Prime (p) จริงของ Bitcoin</h2>
      </div>
      <p class="text-xs text-gray-500 mb-5 mono-font">Secp256k1 · Field Prime · ฐาน 10</p>
      <div class="bg-black/80 border border-[#bf5fff]/40 rounded-xl p-4 mb-4 relative">
        <div class="text-[10px] text-[#bf5fff] font-bold brand-font mb-2 text-left">p =</div>
        <div id="type-p" class="hex-string text-[#bf5fff] text-left leading-relaxed min-h-[3rem]"></div>
        <div class="absolute top-2 right-3 text-[10px] text-gray-600 mono-font" id="p-digit-count"></div>
      </div>
      <div class="bg-black/60 border border-gray-800 rounded-xl p-3 mb-4 text-left">
        <div class="text-[10px] text-gray-500 font-bold brand-font mb-1">รูปแบบ Hexadecimal (ที่ใช้จริงในโค้ด):</div>
        <div class="hex-string text-[#F7931A] text-[11px]">FFFFFFFF FFFFFFFF FFFFFFFF FFFFFFFF FFFFFFFF FFFFFFFF FFFFFFFE FFFFFC2F</div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 text-left">
        <div class="bg-gray-900/60 border border-gray-800 rounded-lg p-3">
          <div class="text-[10px] text-[#00f3ff] font-bold brand-font mb-1"><i class="fa-solid fa-ruler"></i> ขนาด</div>
          <div class="text-white text-sm font-bold mono-font">256 bits</div>
          <div class="text-[10px] text-gray-500 mt-1">= 32 bytes = 78 หลักทศนิยม</div>
        </div>
        <div class="bg-gray-900/60 border border-gray-800 rounded-lg p-3">
          <div class="text-[10px] text-[#00ff41] font-bold brand-font mb-1"><i class="fa-solid fa-infinity"></i> จำนวนจุดบนโค้ง</div>
          <div class="text-white text-sm font-bold mono-font">≈ 2²⁵⁶</div>
          <div class="text-[10px] text-gray-500 mt-1">มากกว่าอะตอมในจักรวาล</div>
        </div>
        <div class="bg-gray-900/60 border border-gray-800 rounded-lg p-3">
          <div class="text-[10px] text-[#F7931A] font-bold brand-font mb-1"><i class="fa-solid fa-lock"></i> ทำไมต้องใหญ่?</div>
          <div class="text-white text-sm font-bold mono-font">Brute-force</div>
          <div class="text-[10px] text-gray-500 mt-1">ใช้เวลานานกว่าอายุจักรวาล</div>
        </div>
      </div>
      <div class="bg-gray-900/40 border border-gray-800 rounded-xl p-4 mb-4 text-left">
        <div class="text-xs text-gray-400 font-bold brand-font mb-3"><i class="fa-solid fa-scale-balanced text-[#F7931A]"></i> เปรียบเทียบสเกล</div>
        <div class="space-y-2 text-[11px]">
          <div class="flex items-center gap-2">
            <div class="shrink-0 w-1 h-4 bg-gray-600 rounded"></div>
            <span class="text-gray-400">p = <b class="text-gray-300">7</b> (ใน Lab)</span>
            <span class="text-gray-600 text-[10px]">→ ~8 จุดบนโค้ง</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="shrink-0 w-3 h-4 bg-[#00f3ff]/30 rounded border border-[#00f3ff]/50"></div>
            <span class="text-gray-400">p = <b class="text-[#00f3ff]">37</b> (ใน Lab สูงสุด)</span>
            <span class="text-gray-600 text-[10px]">→ ~48 จุดบนโค้ง</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="shrink-0 flex-grow h-4 rounded" style="background:linear-gradient(90deg,rgba(191,95,255,.6),rgba(247,147,26,.8));box-shadow:0 0 8px rgba(191,95,255,.5);"></div>
            <span class="shrink-0 text-[#bf5fff] font-bold">p จริง (78 หลัก)</span>
          </div>
          <div class="text-[10px] text-gray-600 mono-font pt-1">ขนาดต่างกัน ~10⁷⁶ เท่า — ไม่มีคอมพิวเตอร์ใดในจักรวาล Brute-force ได้</div>
        </div>
      </div>
      <div class="bg-black/60 border-l-4 border-[#bf5fff] rounded-r-lg p-3 text-left text-[11px] text-gray-400 mb-5">
        <b class="text-[#bf5fff]">สูตรพิเศษ:</b> p = 2²⁵⁶ − 2³² − 977<br>
        <span class="text-[10px] text-gray-600 block mt-1">เลือกรูปแบบนี้เพราะ mod เร็วมาก (Mersenne-like prime) — คอมพิวเตอร์คำนวณได้เร็วกว่า prime ทั่วไป</span>
      </div>
      <button onclick="hideOverlay('real-p-overlay')" class="px-8 py-2.5 bg-[#bf5fff] text-white font-bold rounded-lg hover:bg-[#9940dd] transition brand-font uppercase tracking-wider">
        <i class="fa-solid fa-check mr-2"></i>เข้าใจแล้ว
      </button>
    </div>
  </div>
</div>

<!-- OVERLAY: Double-and-Add Lab -->
<div id="algo-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-2 md:p-4">
  <div class="max-w-5xl w-full h-[95vh] md:h-[90vh] border border-[#00f3ff] bg-[#0a0a0a] rounded-xl shadow-[0_0_50px_rgba(0,243,255,.2)] relative flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 shrink-0">
      <h2 class="text-lg font-bold text-[#00f3ff] brand-font uppercase flex items-center gap-2"><i class="fa-solid fa-bolt"></i> Double-and-Add Lab</h2>
      <button onclick="hideOverlay('algo-overlay')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></button>
    </div>
    <div class="flex-grow flex flex-col md:flex-row overflow-y-auto md:overflow-hidden">
      <div class="w-full md:w-1/3 p-4 md:p-6 bg-black/30 border-b md:border-b-0 md:border-r border-gray-800 flex flex-col gap-4 overflow-y-auto flex-shrink-0">
        <div>
          <label class="block text-xs text-gray-500 mb-2 font-bold brand-font">ป้อน PRIVATE KEY (d)</label>
          <div class="flex gap-2">
            <input type="number" id="algo-input" value="22" min="1" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white mono-font focus:border-[#00f3ff] outline-none">
            <button onclick="runAlgo()" class="px-4 bg-[#00f3ff] text-black font-bold rounded hover:bg-[#00d0dd] brand-font shrink-0">คำนวณ</button>
          </div>
          <p class="text-[10px] text-gray-500 mt-2">ลองใส่เลขเยอะๆ เช่น 1000 หรือ 123456</p>
        </div>
        <div class="bg-gray-900/50 p-3 rounded border border-gray-800">
          <div class="text-xs text-gray-500 mb-1 brand-font">เลขฐานสอง (Binary):</div>
          <div id="algo-binary" class="text-[#F7931A] mono-font text-sm break-all">10110</div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div class="bg-red-900/20 p-2 rounded border-l-2 border-red-500">
            <div class="text-[10px] text-red-400 font-bold">Naive</div>
            <div class="text-xl font-bold text-white mono-font" id="stat-naive">22 <span class="text-[10px] text-gray-500">ops</span></div>
          </div>
          <div class="bg-green-900/20 p-2 rounded border-l-2 border-[#00ff41]">
            <div class="text-[10px] text-[#00ff41] font-bold">Double-Add</div>
            <div class="text-xl font-bold text-white mono-font" id="stat-algo">8 <span class="text-[10px] text-gray-500">ops</span></div>
          </div>
        </div>
      </div>
      <div class="w-full md:w-2/3 flex flex-col bg-[#050505] overflow-hidden flex-grow">
        <div class="p-3 bg-gray-900 border-b border-gray-800 text-xs text-gray-400 font-bold flex justify-between shrink-0 brand-font">
          <span>EXECUTION LOG</span><span class="text-[#00f3ff]">R = จุดปัจจุบัน</span>
        </div>
        <div class="flex-grow overflow-y-auto">
          <div class="overflow-x-auto p-2 md:p-4">
            <table class="w-full min-w-[350px] algo-table text-left border-collapse">
              <thead class="sticky top-0 bg-[#050505] z-10">
                <tr>
                  <th class="p-2 w-10">Step</th><th class="p-2 w-10">Bit</th>
                  <th class="p-2">Action</th><th class="p-2">Result (R)</th>
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

<!-- OVERLAY: Finite Field Lab -->
<div id="ff-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-2 md:p-4">
  <div class="max-w-6xl w-full h-[98vh] md:h-[92vh] border border-[#bf5fff] bg-[#080808] rounded-xl shadow-[0_0_60px_rgba(191,95,255,.25)] relative flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/60 shrink-0">
      <h2 class="text-base md:text-lg font-bold text-[#bf5fff] brand-font uppercase flex items-center gap-2">
        <i class="fa-solid fa-hashtag"></i> Finite Field Lab — y² ≡ x³ + 7 (mod p)
      </h2>
      <div class="flex items-center gap-3">
        <button onclick="showOverlay('ff-explain-overlay')" class="btn-yellow px-3 py-1.5 rounded text-[10px] font-bold brand-font uppercase flex items-center gap-1.5">
          <i class="fa-solid fa-circle-question"></i> อธิบาย
        </button>
        <button onclick="showRealP()" class="btn-purple px-3 py-1.5 rounded text-[10px] font-bold brand-font uppercase flex items-center gap-1.5">
          <i class="fa-solid fa-hashtag"></i> p จริง
        </button>
        <button onclick="hideOverlay('ff-overlay')" class="text-gray-500 hover:text-white ml-1"><i class="fa-solid fa-xmark text-2xl"></i></button>
      </div>
    </div>
    <div class="flex-grow flex flex-col md:flex-row overflow-y-auto md:overflow-hidden">

      <!-- FF LEFT: Controls -->
      <div class="w-full md:w-2/5 p-4 border-b md:border-b-0 md:border-r border-gray-800 flex flex-col gap-4 overflow-y-auto flex-shrink-0 bg-black/30">

        <!-- Prime selector -->
        <div>
          <label class="block text-xs text-gray-400 mb-2 brand-font font-bold">เลือก Prime (p)</label>
          <div id="prime-btns" class="flex flex-wrap gap-2 mb-3"></div>
          <div class="flex items-center gap-3">
            <input type="range" id="prime-slider" min="0" max="8" value="3" step="1" class="flex-grow" oninput="setPrimeByIndex(this.value)">
            <div class="text-[#bf5fff] font-bold mono-font text-xl w-14 text-center shrink-0" id="prime-display">p=17</div>
          </div>
        </div>

        <!-- Point grid -->
        <div>
          <div class="flex justify-between items-center mb-2">
            <span class="text-xs text-gray-400 brand-font font-bold">จุดบนเส้นโค้ง</span>
            <span class="text-[10px] mono-font text-[#bf5fff]" id="ff-point-count">— จุด</span>
          </div>
          <div id="ff-grid" class="flex flex-wrap gap-1.5 p-3 bg-black/60 rounded border border-gray-800 min-h-[60px]"></div>
          <div class="flex flex-wrap gap-3 mt-2 text-[10px] text-gray-500">
            <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#00f3ff]/40 border border-[#00f3ff]"></span>บนโค้ง</span>
            <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#F7931A]/70 border border-[#F7931A]"></span>P1</span>
            <span class="flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-[#bf5fff]/70 border border-[#bf5fff]"></span>P2</span>
          </div>
        </div>

        <!-- Point add -->
        <div class="border border-gray-800 rounded p-3 bg-black/40">
          <div class="text-xs text-gray-400 brand-font font-bold mb-2">คลิกจุดเพื่อเลือก P1 → P2 → บวก</div>
          <div class="grid grid-cols-2 gap-2 mb-2">
            <div>
              <div class="text-[10px] text-[#F7931A] mb-1">P1</div>
              <div id="p1-display" class="mono-font text-xs bg-gray-900 p-2 rounded border border-gray-800 text-gray-400">— ยังไม่เลือก</div>
            </div>
            <div>
              <div class="text-[10px] text-[#bf5fff] mb-1">P2</div>
              <div id="p2-display" class="mono-font text-xs bg-gray-900 p-2 rounded border border-gray-800 text-gray-400">— ยังไม่เลือก</div>
            </div>
          </div>
          <button onclick="computeFFAdd()" class="w-full btn-purple py-2 rounded text-xs brand-font font-bold uppercase flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus-circle"></i> คำนวณ P1 + P2 (mod p)
          </button>
        </div>

        <!-- Result -->
        <div id="ff-result-box" class="hidden border border-[#ff0055]/50 rounded p-3 bg-black/60">
          <div class="text-xs text-[#ff0055] font-bold brand-font mb-2"><i class="fa-solid fa-star"></i> ผลลัพธ์ P1 + P2</div>
          <div id="ff-result-coords" class="mono-font text-[#ff0055] text-sm font-bold mb-3"></div>
          <div class="text-[10px] text-gray-500 font-bold mb-1 brand-font">ขั้นตอนการคำนวณ</div>
          <div id="ff-steps" class="space-y-1"></div>
        </div>

        <!-- Scalar multiply -->
        <div class="border border-gray-800 rounded p-3 bg-black/40">
          <div class="text-xs text-gray-400 brand-font font-bold mb-2">Scalar: k × G (mod p)</div>
          <div class="flex gap-2 mb-2">
            <input type="number" id="ff-scalar" value="3" min="1" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white mono-font focus:border-[#bf5fff] outline-none text-sm">
            <button onclick="computeFFScalar()" class="px-3 bg-[#bf5fff] text-white font-bold rounded hover:bg-[#9940dd] brand-font shrink-0 text-xs">คำนวณ</button>
          </div>
          <div id="ff-scalar-result" class="mono-font text-xs text-gray-400"></div>
        </div>
      </div>

      <!-- FF RIGHT: Scatter Canvas -->
      <div class="w-full md:w-3/5 flex flex-col flex-grow overflow-hidden min-h-[400px] md:min-h-0 shrink-0">
        <div class="p-2 bg-gray-900/60 border-b border-gray-800 text-xs text-gray-400 flex justify-between items-center shrink-0 brand-font font-bold">
          <span><i class="fa-solid fa-chart-scatter"></i> SCATTER PLOT — F<sub>p</sub></span>
          <span class="text-[#bf5fff]" id="ff-canvas-label">p = 17</span>
        </div>
        <div class="flex-grow p-2 relative overflow-hidden">
          <canvas id="ff-canvas" class="w-full h-full rounded" style="background:#000;"></canvas>
        </div>
        <div class="p-2 border-t border-gray-800 bg-black/40 text-[10px] text-gray-500">
          แกน X = ค่า x (0→p-1) · แกน Y = ค่า y (0→p-1) · <span class="text-[#00f3ff]">●</span> บนโค้ง · <span class="text-[#00ff41]">●</span> G · <span class="text-[#F7931A]">●</span> P1 · <span class="text-[#bf5fff]">●</span> P2 · <span class="text-[#ff0055]">●</span> R
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN -->
<main class="flex-grow container mx-auto p-3 md:p-4 gap-4 grid grid-cols-1 lg:grid-cols-12 lg:h-[calc(100vh-64px)] lg:overflow-hidden">

  <!-- LEFT PANEL -->
  <div class="lg:col-span-4 flex flex-col gap-4 lg:h-full overflow-visible lg:overflow-y-auto lg:pr-1">

    <!-- Mode Switch -->
    <div class="neon-box rounded-xl p-3 flex-shrink-0">
      <div class="text-[10px] text-gray-500 brand-font font-bold mb-2 uppercase tracking-widest">Field Mode</div>
      <div class="grid grid-cols-2 gap-2">
        <button id="mode-real-btn" class="mode-btn rounded active-real" onclick="setMode('real')">
          <i class="fa-solid fa-wave-square mr-1"></i>🌊 Real Field
          <div class="text-[9px] opacity-60 mt-0.5 normal-case font-normal">เส้นโค้งต่อเนื่อง</div>
        </button>
        <button id="mode-finite-btn" class="mode-btn rounded" onclick="setMode('finite')">
          <i class="fa-solid fa-hashtag mr-1"></i>🔢 Finite Field
          <div class="text-[9px] opacity-60 mt-0.5 normal-case font-normal">จุดกระโดด mod p</div>
        </button>
      </div>
    </div>

    <!-- Real Field Panel -->
    <div id="real-panel" class="neon-box p-5 rounded-xl flex flex-col gap-3 flex-shrink-0">
      <h2 class="text-sm font-bold text-[#F7931A] brand-font flex justify-between">
        <span><i class="fa-solid fa-key"></i> PRIVATE KEY (d)</span>
        <span class="text-[10px] bg-gray-800 px-2 py-0.5 rounded text-gray-400">REAL FIELD</span>
      </h2>
      <div class="flex flex-col items-center justify-center bg-gradient-to-b from-gray-900 to-black p-4 rounded border border-gray-800 shadow-inner relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-[#F7931A] to-transparent opacity-50"></div>
        <div id="private-key-display" class="text-5xl font-bold mono-font text-white drop-shadow-[0_0_15px_rgba(255,255,255,.2)]">1</div>
        <div class="text-[10px] text-gray-600 mt-1 uppercase tracking-widest">Scalar (d)</div>
        <i class="fa-brands fa-bitcoin absolute -bottom-4 -right-4 text-8xl text-gray-800/20 group-hover:text-[#F7931A]/10 transition duration-500"></i>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <button onclick="stepForward()" class="col-span-2 btn-action py-3 rounded text-sm shadow-lg flex items-center justify-center gap-2">
          <i class="fa-solid fa-plus-circle"></i> เพิ่ม Key (+1)
        </button>
        <button onclick="autoPlay()" id="btn-auto" class="btn-secondary py-2 rounded text-xs uppercase font-bold flex items-center justify-center gap-1">
          <i class="fa-solid fa-play"></i> Auto
        </button>
        <button onclick="resetSim()" class="btn-reset py-2 rounded text-xs uppercase font-bold flex items-center justify-center gap-1">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
        <button onclick="showRealG()" class="btn-green py-2 rounded text-[10px] uppercase font-bold flex items-center justify-center gap-1">
          <i class="fa-solid fa-fingerprint"></i> G จริง
        </button>
        <button onclick="showOverlay('algo-overlay'); runAlgo();" class="btn-blue py-2 rounded text-[10px] uppercase font-bold flex items-center justify-center gap-1">
          <i class="fa-solid fa-bolt"></i> Algo Lab
        </button>
      </div>
    </div>

    <!-- Finite Field Panel -->
    <div id="finite-panel" class="hidden neon-box p-5 rounded-xl flex flex-col gap-3 flex-shrink-0" style="border-color:rgba(191,95,255,.3);">
      <h2 class="text-sm font-bold text-[#bf5fff] brand-font flex justify-between">
        <span><i class="fa-solid fa-hashtag"></i> FINITE FIELD</span>
        <span class="text-[10px] bg-gray-800 px-2 py-0.5 rounded text-gray-400">mod p</span>
      </h2>
      <div class="text-xs text-gray-400 bg-gray-900/60 p-3 rounded border border-gray-800 leading-relaxed">
        ใน <b class="text-[#bf5fff]">Finite Field F<sub>p</sub></b> เส้นโค้งถูก "ตัด" ด้วย mod p<br>
        x, y ต้องอยู่ระหว่าง <b>0 ถึง p-1</b> เท่านั้น<br>
        แทนที่จะเป็นเส้นต่อเนื่อง จะกลายเป็น <b class="text-[#F7931A]">จุดกระโดด</b> ที่ดูเหมือนสุ่ม
      </div>
      <div class="grid grid-cols-2 gap-2">
        <button onclick="showOverlay('ff-overlay'); initFFLab();" class="btn-purple py-3 rounded text-xs font-bold brand-font uppercase flex items-center justify-center gap-1">
          <i class="fa-solid fa-flask"></i> FF Lab
        </button>
        <button onclick="showRealP()" class="btn-purple py-3 rounded text-xs font-bold brand-font uppercase flex items-center justify-center gap-1">
          <i class="fa-solid fa-hashtag"></i> p จริง ∞
        </button>
      </div>
      <div class="text-[10px] text-gray-600 text-center">เลือก prime · scatter plot · บวก mod p · ดู p จริง</div>
    </div>

    <!-- Calc Log -->
    <div class="neon-box p-4 rounded-xl flex flex-col flex-shrink-0">
      <h3 class="text-xs font-bold text-gray-400 mb-2 border-b border-gray-800 pb-2 flex justify-between brand-font">
        <span><i class="fa-solid fa-code"></i> CALCULATION LOG</span>
        <span class="text-[10px] text-gray-600 mono-font">y² = x³ + 7</span>
      </h3>
      <div class="space-y-3 mono-font text-xs">
        <div>
          <div class="text-[10px] text-[#F7931A] mb-1 brand-font">Generator G</div>
          <div class="key-display flex justify-between"><span>x: 1.0000</span><span>y: 2.8284</span></div>
        </div>
        <div>
          <div class="text-[10px] text-[#00ff41] mb-1 flex justify-between">
            <span>Public Key = d × G</span>
            <span id="coord-status" class="text-gray-500 animate-pulse"></span>
          </div>
          <div class="bg-black/40 border border-gray-700 p-2 rounded">
            <div class="flex justify-between mb-1"><span class="text-gray-500">X:</span><span id="pub-x" class="text-white font-bold">1.0000</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Y:</span><span id="pub-y" class="text-white font-bold">2.8284</span></div>
          </div>
        </div>
        <div id="explanation-box" class="bg-gray-900/80 p-3 rounded border-l-2 border-[#00ff41] text-gray-300 leading-relaxed math-step text-[11px]">
          <p class="mb-1 text-[#00ff41] font-bold">สถานะ: เริ่มต้น</p>d=1 → Public Key = <b>G</b>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="neon-box p-4 rounded-xl bg-black/80 border-l-4 border-[#F7931A] relative overflow-hidden flex-shrink-0">
      <h3 class="text-sm font-bold text-white mb-3 brand-font flex items-center gap-2"><i class="fa-solid fa-book-open text-[#F7931A]"></i> สรุป</h3>
      <div class="text-xs text-gray-400 space-y-2 leading-relaxed">
        <p><b class="text-white">Public Key</b> คือพิกัด (x, y) บนเส้นโค้ง Secp256k1</p>
        <div class="bg-gray-900 p-2 rounded border border-gray-800 mono-font text-[10px] text-center">
          <span class="text-[#00ff41]">P</span> = <span class="text-white">d</span> × <span class="text-[#00f3ff]">G</span>
        </div>
        <p class="text-[10px]">Bitcoin ใช้ <b class="text-[#bf5fff]">Finite Field</b> (mod p) เพื่อให้คำนวณได้จริงและ "ย้อนกลับ" ไม่ได้</p>
      </div>
      <i class="fa-solid fa-diagram-project absolute -right-2 top-4 text-5xl text-gray-800/20 -rotate-12"></i>
    </div>
  </div>

  <!-- CANVAS PANEL -->
  <div class="lg:col-span-8 flex flex-col h-96 lg:h-full overflow-hidden relative">
    <div class="neon-box p-1 rounded-xl h-full relative bg-black flex flex-col">
      <div id="real-badge" class="absolute top-4 left-4 z-10 pointer-events-none flex flex-col gap-1">
        <span class="bg-black/70 backdrop-blur text-[#F7931A] px-3 py-1 rounded text-xs font-bold border border-[#F7931A]/40 brand-font uppercase">🌊 Real Field — y² = x³ + 7</span>
        <div id="camera-info" class="text-[10px] text-gray-500 mono-font pl-1">Scale: —</div>
      </div>
      <div id="finite-badge" class="hidden absolute top-4 left-4 z-10 pointer-events-none flex flex-col gap-1">
        <span class="bg-black/70 backdrop-blur text-[#bf5fff] px-3 py-1 rounded text-xs font-bold border border-[#bf5fff]/40 brand-font uppercase">🔢 Finite Field — y² ≡ x³+7 (mod p)</span>
        <span class="bg-black/70 text-[10px] text-gray-400 px-2 py-0.5 rounded mono-font" id="main-ff-label">p = 17</span>
      </div>
      <button onclick="showOverlay('real-explain-overlay')" id="canvas-explain-btn"
        class="absolute top-4 right-16 z-10 bg-black/60 backdrop-blur border border-[#ffd700]/40 text-[#ffd700]/80 hover:text-[#ffd700] hover:border-[#ffd700] px-2 py-1 rounded text-[10px] brand-font font-bold uppercase transition flex items-center gap-1">
        <i class="fa-solid fa-circle-question"></i> อธิบาย
      </button>
      <div class="absolute bottom-4 right-4 z-10 flex gap-2">
        <button onclick="manualZoom(-5)" class="bg-gray-800/80 text-white w-8 h-8 rounded-full hover:bg-gray-700 flex items-center justify-center shadow-lg transition active:scale-90"><i class="fa-solid fa-minus"></i></button>
        <button onclick="manualZoom(5)" class="bg-gray-800/80 text-white w-8 h-8 rounded-full hover:bg-gray-700 flex items-center justify-center shadow-lg transition active:scale-90"><i class="fa-solid fa-plus"></i></button>
        <button onclick="autoFit(true)" class="bg-[#F7931A]/80 text-black w-8 h-8 rounded-full hover:bg-[#ffae42] flex items-center justify-center shadow-lg transition active:scale-90"><i class="fa-solid fa-compress"></i></button>
      </div>
      <div id="canvas-container" class="flex-grow">
        <canvas id="curveCanvas"></canvas>
      </div>
    </div>
  </div>
</main>


<!-- ══ OVERLAY: FF Lab Explanation ════════════════════════════════════════ -->
<div id="ff-explain-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-4" style="z-index:150;">
  <div class="max-w-2xl w-full border border-[#ffd700]/40 bg-[#060606] rounded-xl shadow-[0_0_40px_rgba(255,215,0,.1)] flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/60 shrink-0">
      <h2 class="text-base font-bold text-[#ffd700] brand-font uppercase"><i class="fa-solid fa-circle-question mr-2"></i>อธิบาย: Finite Field Lab</h2>
      <button onclick="hideOverlay('ff-explain-overlay')" class="text-gray-500 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="overflow-y-auto p-5" style="max-height:78vh;">

      <!-- Legend -->
      <div class="guide-step mb-4">
        <div class="text-white font-bold text-sm mb-3"><i class="fa-solid fa-eye text-[#00f3ff] mr-2"></i>Legend — สิ่งที่เห็นใน Scatter Plot</div>
        <div class="space-y-2">
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#00f3ff;box-shadow:0 0 6px #00f3ff;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#00f3ff]">จุดสีฟ้า</b> — จุดที่ผ่านสมการ y² ≡ x³+7 (mod p) ไม่ใช่ทุก (x,y) จะผ่าน มีแค่บางคู่เท่านั้น</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-4 h-4 rounded-full shrink-0 border-2 border-[#00ff41]" style="background:transparent;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#00ff41]">วงสีเขียว (G)</b> — Generator Point จุดเริ่มต้นของทุก Bitcoin Wallet d=1 → P = G</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#F7931A;box-shadow:0 0 6px #F7931A;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#F7931A]">สีส้ม (P1)</b> — จุดที่คลิกเลือกแรก (click 1st)</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#bf5fff;box-shadow:0 0 6px #bf5fff;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#bf5fff]">สีม่วง (P2)</b> — จุดที่คลิกเลือกที่สอง (click 2nd)</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#ff0055;box-shadow:0 0 6px #ff0055;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#ff0055]">สีแดง (R)</b> — ผลลัพธ์ P1+P2 คำนวณด้วย Modular Arithmetic</div>
          </div>
        </div>
      </div>

      <!-- Math -->
      <div class="guide-step mb-4">
        <div class="text-white font-bold text-sm mb-2"><i class="fa-solid fa-calculator text-[#bf5fff] mr-2"></i>การบวกจุดใน Finite Field ทำงานยังไง?</div>
        <div class="text-xs text-gray-400 mb-3">กฎเหมือน Real Field ทุกอย่าง แต่ทุกการคำนวณต้องทำ mod p :</div>
        <div class="formula-box mb-2">
          <div class="text-[10px] text-gray-500 mb-1">Step 1 — หา slope (λ)</div>
          <span class="text-[#00f3ff]">λ</span> = (<span class="text-white">y₂−y₁</span>) × (<span class="text-white">x₂−x₁</span>)<sup>−1</sup> <span class="text-gray-500">mod p</span>
        </div>
        <div class="formula-box mb-2">
          <div class="text-[10px] text-gray-500 mb-1">Step 2 — หา x ผลลัพธ์</div>
          <span class="text-[#F7931A]">x₃</span> = λ² − x₁ − x₂ <span class="text-gray-500">mod p</span>
        </div>
        <div class="formula-box mb-3">
          <div class="text-[10px] text-gray-500 mb-1">Step 3 — หา y ผลลัพธ์</div>
          <span class="text-[#00ff41]">y₃</span> = λ(x₁−x₃) − y₁ <span class="text-gray-500">mod p</span>
        </div>
        <div class="tip-box">
          <div class="text-[#00ff41] font-bold text-xs brand-font mb-1">💡 ทำไมผลลัพธ์ดูสุ่ม?</div>
          <div class="text-xs text-gray-300">mod พับตัวเลขกลับ — ค่า x₃ ที่ควรอยู่ที่ 50 กลายเป็น 50 mod 17 = 16 ทำให้จุดผลลัพธ์อยู่คนละซีก เส้นประแดงใน plot คือ visualization เท่านั้น ไม่ใช่เส้นตรงจริงๆ</div>
        </div>
      </div>

      <!-- How to use -->
      <div class="guide-step">
        <div class="text-white font-bold text-sm mb-2"><i class="fa-solid fa-hand-pointer text-[#ffd700] mr-2"></i>ลำดับการใช้งานที่แนะนำ</div>
        <div class="space-y-1.5 text-xs text-gray-300">
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">①</span><span>เลือก <b class="text-white">p=7</b> ก่อน — จุดน้อย เห็นภาพชัด ทำความเข้าใจก่อน</span></div>
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">②</span><span>คลิกจุดแรก → <b class="text-[#F7931A]">P1 เปลี่ยนเป็นสีส้ม</b> · คลิกจุดที่สอง → <b class="text-[#bf5fff]">P2 เปลี่ยนเป็นสีม่วง</b></span></div>
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">③</span><span>กด <b class="text-white">"คำนวณ P1+P2"</b> → ดู <b class="text-[#ff0055]">R (แดง)</b> บน Scatter Plot และดู formula ด้านซ้าย</span></div>
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">④</span><span>ลอง <b class="text-white">k×G</b> ใส่ k=1,2,3,4,5 — สังเกตว่า k ต่างกันน้อย แต่ผลลัพธ์กระโดดไปคนละจุด</span></div>
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">⑤</span><span>เพิ่ม p ขึ้นเรื่อยๆ จนถึง p=37 → จุดมากขึ้น ดูสุ่มมากขึ้น = <b class="text-white">ปลอดภัยมากขึ้น</b></span></div>
          <div class="flex gap-2 items-start"><span class="text-[#ffd700] font-bold shrink-0 brand-font">⑥</span><span>กด <b class="text-[#bf5fff]">"p จริง"</b> เพื่อดูว่า Bitcoin ใช้ p ใหญ่แค่ไหน (78 หลัก!)</span></div>
        </div>
      </div>

    </div>
    <div class="p-3 border-t border-gray-800 bg-black/40 flex justify-end shrink-0">
      <button onclick="hideOverlay('ff-explain-overlay')" class="px-6 py-2 bg-[#ffd700] text-black font-bold rounded hover:bg-[#ffaa00] transition brand-font text-xs uppercase">
        <i class="fa-solid fa-flask mr-1"></i> กลับไปทดลอง
      </button>
    </div>
  </div>
</div>

<!-- ══ OVERLAY: Real Field Explanation ══════════════════════════════════════ -->
<div id="real-explain-overlay" class="fixed inset-0 hidden overlay-bg flex flex-col items-center justify-center p-4" style="z-index:150;">
  <div class="max-w-2xl w-full border border-[#ffd700]/40 bg-[#060606] rounded-xl shadow-[0_0_40px_rgba(255,215,0,.1)] flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-black/60 shrink-0">
      <h2 class="text-base font-bold text-[#ffd700] brand-font uppercase"><i class="fa-solid fa-circle-question mr-2"></i>อธิบาย: Real Field Canvas</h2>
      <button onclick="hideOverlay('real-explain-overlay')" class="text-gray-500 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="overflow-y-auto p-5" style="max-height:78vh;">

      <!-- Legend -->
      <div class="guide-step mb-4">
        <div class="text-white font-bold text-sm mb-3"><i class="fa-solid fa-eye text-[#F7931A] mr-2"></i>Legend — สิ่งที่เห็นบน Canvas</div>
        <div class="space-y-2">
          <div class="flex items-center gap-3 info-card">
            <div class="w-8 h-1 rounded shrink-0" style="background:#F7931A;"></div>
            <div class="text-xs text-gray-300"><b class="text-[#F7931A]">เส้นโค้งสีส้ม</b> — กราฟของ y²=x³+7 มี 2 ส่วน: บนและล่างแกน X (สมมาตรกัน เพราะ √y² = ±y)</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#00f3ff;box-shadow:0 0 6px #00f3ff;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#00f3ff]">จุดสีฟ้า (G)</b> — Generator Point จุดเริ่มต้น x=1, y≈2.828</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <span class="inline-block w-3 h-3 rounded-full shrink-0" style="background:#00ff41;box-shadow:0 0 6px #00ff41;"></span>
            <div class="text-xs text-gray-300"><b class="text-[#00ff41]">จุดสีเขียว P(dG)</b> — Public Key ปัจจุบัน = d × G เคลื่อนที่ทุกครั้งที่กด "+1G"</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <div class="w-8 border-t-2 border-dashed border-[#ff0055] shrink-0"></div>
            <div class="text-xs text-gray-300"><b class="text-[#ff0055]">เส้นประแดง</b> — เส้นตรงลากผ่าน P(n-1) กับ G ไปตัดเส้นโค้งที่จุดที่ 3</div>
          </div>
          <div class="flex items-center gap-3 info-card">
            <div class="w-8 border-t-2 border-solid border-[#00ff41] shrink-0"></div>
            <div class="text-xs text-gray-300"><b class="text-[#00ff41]">เส้นสีเขียว</b> — สะท้อนจุดตัดผ่านแกน X (กลับ y) → ได้จุดผลลัพธ์ใหม่ = P(n)</div>
          </div>
        </div>
      </div>

      <!-- Rules -->
      <div class="guide-step mb-4">
        <div class="text-white font-bold text-sm mb-3"><i class="fa-solid fa-play text-[#00ff41] mr-2"></i>กฎ Point Addition — ทีละขั้น</div>
        <div class="space-y-2">
          <div class="flex gap-3 items-start">
            <span class="w-6 h-6 rounded-full bg-[#ff0055]/20 border border-[#ff0055]/40 text-[#ff0055] font-bold text-[11px] flex items-center justify-center shrink-0">1</span>
            <div class="text-xs text-gray-300">ลากเส้นตรงผ่านจุด <b class="text-[#F7931A]">P(n-1)</b> กับจุด <b class="text-[#00f3ff]">G</b> → เส้นนี้จะตัดเส้นโค้งที่จุดที่ 3 (อยู่กลับหัว)</div>
          </div>
          <div class="flex gap-3 items-start">
            <span class="w-6 h-6 rounded-full bg-[#00ff41]/20 border border-[#00ff41]/40 text-[#00ff41] font-bold text-[11px] flex items-center justify-center shrink-0">2</span>
            <div class="text-xs text-gray-300">สะท้อนจุดที่ 3 ผ่านแกน X (y → −y) → ได้จุดใหม่ <b class="text-[#00ff41]">P(n)</b> = P(n-1) + G</div>
          </div>
          <div class="flex gap-3 items-start">
            <span class="w-6 h-6 rounded-full bg-[#F7931A]/20 border border-[#F7931A]/40 text-[#F7931A] font-bold text-[11px] flex items-center justify-center shrink-0">★</span>
            <div class="text-xs text-gray-300"><b class="text-[#F7931A]">กรณีพิเศษ d=1:</b> P1 = G → ลาก Tangent (เส้นสัมผัส) ที่ G แทน เรียกว่า Point Doubling → ได้ 2G</div>
          </div>
        </div>
      </div>

      <!-- Controls -->
      <div class="guide-step mb-4">
        <div class="text-white font-bold text-sm mb-2"><i class="fa-solid fa-mouse-pointer text-[#ffd700] mr-2"></i>การควบคุม Canvas</div>
        <div class="grid grid-cols-2 gap-2">
          <div class="info-card text-xs text-gray-300"><b class="text-white">🖱 Drag</b><br>ลาก Canvas เพื่อเลื่อนมุมมอง</div>
          <div class="info-card text-xs text-gray-300"><b class="text-white">＋ / −</b><br>Zoom เข้า / Zoom ออก</div>
          <div class="info-card text-xs text-gray-300"><b class="text-white">⊡ ปุ่มสีส้ม</b><br>Auto-fit ให้เห็นทุกจุดพร้อมกัน</div>
          <div class="info-card text-xs text-gray-300"><b class="text-white">▶ Auto</b><br>วิ่งอัตโนมัติทุก 2 วินาที</div>
        </div>
      </div>

      <div class="warning-box">
        <div class="text-[#ff0055] font-bold text-xs brand-font mb-1">⚠️ Real Field ≠ Bitcoin จริง</div>
        <div class="text-xs text-gray-300">Canvas นี้ใช้เลข floating point เพื่อ <b class="text-white">Visualize เท่านั้น</b> Bitcoin จริงใช้ Finite Field (mod p) ที่มีความแม่นยำสมบูรณ์ ลองดูที่ Mode 🔢 Finite Field</div>
      </div>

    </div>
    <div class="p-3 border-t border-gray-800 bg-black/40 flex justify-end shrink-0">
      <button onclick="hideOverlay('real-explain-overlay')" class="px-6 py-2 bg-[#ffd700] text-black font-bold rounded hover:bg-[#ffaa00] transition brand-font text-xs uppercase">
        <i class="fa-solid fa-check mr-1"></i> เข้าใจแล้ว
      </button>
    </div>
  </div>
</div>

<footer class="w-full text-center p-4 text-xs text-gray-600 border-t border-gray-900 mt-auto bg-[#050505] z-10">
  © 2026 Chollatis Bitcoiner · Don't Trust, Verify. ·
  <button onclick="showOverlay('guide-overlay')" class="text-[#ffd700]/50 hover:text-[#ffd700] transition ml-1">📖 วิธีเล่น</button>
</footer>

<script>
// ── Constants ──────────────────────────────────────────────────────────────
const A = 0, B = 7;
const G_REAL = { x: 1, y: Math.sqrt(8) };
const REAL_G_X = "55,066,263,022,277,343,669,578,718,895,168,534,326,250,603,453,777,594,175,500,187,360,389,116,729,240";
const REAL_G_Y = "32,670,510,020,758,816,978,083,085,130,507,043,184,471,273,380,659,243,275,938,904,335,757,337,482,424";
const REAL_P   = "115,792,089,237,316,195,423,570,985,008,687,907,853,269,984,665,640,564,039,457,584,007,908,834,671,663";
const PRIMES   = [7, 11, 13, 17, 19, 23, 29, 31, 37];

// ── State ──────────────────────────────────────────────────────────────────
let currentMode = 'real';
let privateKey  = 1;
let currentP    = { ...G_REAL };
let view = { scale:60, offsetX:0, offsetY:0, targetScale:60, targetOffX:0, targetOffY:0 };
let anim = { active:false, progress:0, p1:null, p2:null, intersect:null, final:null, speed:0.015 };
let autoPlayTimer = null, twTimer = null;
let ffPrime = 17, ffPoints = [], ffP1 = null, ffP2 = null;

// ── Canvas ─────────────────────────────────────────────────────────────────
const canvas    = document.getElementById('curveCanvas');
const ctx       = canvas.getContext('2d');
const container = document.getElementById('canvas-container');

function resize() {
  canvas.width  = container.clientWidth;
  canvas.height = container.clientHeight;
  view.targetOffX = canvas.width  / 2;
  view.targetOffY = canvas.height / 2;
  if (privateKey === 1) { view.offsetX = view.targetOffX; view.offsetY = view.targetOffY; }
}
window.addEventListener('resize', () => { resize(); if (currentMode==='real') autoFit(true); });
document.addEventListener('DOMContentLoaded', () => {
  buildPrimeButtons();
  resize();
  updateUI();
  requestAnimationFrame(renderLoop);
});

// ── Guide Tabs ─────────────────────────────────────────────────────────────
function showGuideTab(tabId) {
  document.querySelectorAll('.guide-tab-content').forEach(function(el){ el.classList.add('hidden'); });
  document.querySelectorAll('.guide-tab').forEach(function(el){ el.classList.remove('active'); });
  document.getElementById(tabId).classList.remove('hidden');
  if (event && event.currentTarget) event.currentTarget.classList.add('active');
}

// ── Prime Buttons (built in JS, no PHP) ───────────────────────────────────
function buildPrimeButtons() {
  const wrap = document.getElementById('prime-btns');
  wrap.innerHTML = '';
  PRIMES.forEach(function(pr) {
    const btn = document.createElement('button');
    btn.className = 'prime-btn' + (pr === ffPrime ? ' active' : '');
    btn.textContent = 'p=' + pr;
    btn.onclick = function() { setPrime(pr); };
    wrap.appendChild(btn);
  });
}
function refreshPrimeBtns() {
  document.querySelectorAll('.prime-btn').forEach(function(btn) {
    const val = parseInt(btn.textContent.replace('p=',''));
    btn.classList.toggle('active', val === ffPrime);
  });
}

// ── Overlay helpers ────────────────────────────────────────────────────────
function showOverlay(id) { document.getElementById(id).classList.remove('hidden'); }
function hideOverlay(id) {
  document.getElementById(id).classList.add('hidden');
  if (twTimer) { clearTimeout(twTimer); twTimer = null; }
}

// ── Mode Switcher ──────────────────────────────────────────────────────────
function setMode(mode) {
  currentMode = mode;
  var isReal = (mode === 'real');
  document.getElementById('real-panel').classList.toggle('hidden', !isReal);
  document.getElementById('finite-panel').classList.toggle('hidden', isReal);
  document.getElementById('real-badge').classList.toggle('hidden', !isReal);
  document.getElementById('finite-badge').classList.toggle('hidden', isReal);
  document.getElementById('mode-real-btn').className   = 'mode-btn rounded ' + (isReal ? 'active-real' : '');
  document.getElementById('mode-finite-btn').className = 'mode-btn rounded ' + (!isReal ? 'active-finite' : '');
  if (!isReal) { computeFFPoints(); updateFFLabel(); }
}

// ── Typewriter ─────────────────────────────────────────────────────────────
function typeWriter(id, text, speed) {
  if (twTimer) { clearTimeout(twTimer); twTimer = null; }
  var el = document.getElementById(id);
  el.innerText = '';
  var i = 0;
  function tick() {
    if (i < text.length) { el.innerText += text.charAt(i++); twTimer = setTimeout(tick, speed || 6); }
  }
  tick();
}

// ── Show Real G ────────────────────────────────────────────────────────────
function showRealG() {
  if (autoPlayTimer) autoPlay();
  showOverlay('real-g-overlay');
  typeWriter('type-x', REAL_G_X, 5);
  setTimeout(function() { typeWriter('type-y', REAL_G_Y, 5); }, 1600);
}

// ── Show Real p ────────────────────────────────────────────────────────────
function showRealP() {
  showOverlay('real-p-overlay');
  var digits = REAL_P.replace(/,/g,'').length;
  document.getElementById('p-digit-count').innerText = digits + ' หลัก';
  typeWriter('type-p', REAL_P, 8);
}

// ── Double-and-Add Algo ───────────────────────────────────────────────────
function runAlgo() {
  var v = parseInt(document.getElementById('algo-input').value);
  if (isNaN(v) || v < 1) return;
  var bin = v.toString(2);
  document.getElementById('algo-binary').innerText = bin;
  var naive = v;
  var algo  = bin.length + bin.split('1').length - 3;
  document.getElementById('stat-naive').innerHTML = naive.toLocaleString() + ' <span class="text-[10px] text-gray-500">ops</span>';
  document.getElementById('stat-algo').innerHTML  = algo + ' <span class="text-[10px] text-gray-500">ops</span>';
  var tbody = document.getElementById('algo-steps');
  tbody.innerHTML = '';
  var cur = 1;
  addAlgoRow(tbody, 0, '—', 'Start (MSB=1)', 'G');
  for (var i = 1; i < bin.length; i++) {
    var bit = bin[i];
    cur *= 2;
    addAlgoRow(tbody, i, bit, '<span class="text-[#00f3ff]">Double</span> (2×' + (cur/2) + 'G)', cur + 'G');
    if (bit === '1') {
      cur++;
      addAlgoRow(tbody, '', '', '<span class="text-[#00ff41]">Add G</span> (' + (cur-1) + 'G+G)', '<b class="text-[#00ff41]">' + cur + 'G</b>');
    }
  }
}
function addAlgoRow(tbody, step, bit, action, result) {
  var r = document.createElement('tr');
  r.innerHTML = '<td class="p-2 text-gray-500">' + step + '</td>' +
                '<td class="p-2 text-[#F7931A] font-bold">' + bit + '</td>' +
                '<td class="p-2 whitespace-nowrap">' + action + '</td>' +
                '<td class="p-2 text-white whitespace-nowrap">' + result + '</td>';
  tbody.appendChild(r);
}

// ── Real-Field Math ────────────────────────────────────────────────────────
function getCurveY(x) { var v = x*x*x + B; return v >= 0 ? Math.sqrt(v) : NaN; }
function addPointsReal(p1, p2) {
  var lam, eps = 0.00001;
  if (Math.abs(p1.x - p2.x) < eps && Math.abs(p1.y - p2.y) < eps) {
    lam = (3*p1.x*p1.x + A) / (2*p1.y);
  } else if (Math.abs(p1.x - p2.x) < eps) {
    // กรณี P1 = -P2 เส้นตั้งฉาก (Point at Infinity)
    // สำหรับ Visualizer พื้นฐาน ให้คืนค่าจุดที่อยู่ไกลมากๆ เพื่อไม่ให้ Canvas พัง
    return { x: 0, y: 1e9 }; 
  } else {
    lam = (p2.y - p1.y) / (p2.x - p1.x);
  }
  var x3 = lam*lam - p1.x - p2.x;
  var y3 = lam*(p1.x - x3) - p1.y;
  return { x: x3, y: y3 };
}

// ── Camera ─────────────────────────────────────────────────────────────────
function autoFit(instant) {
  var pts = [{x:-2,y:0},{x:2,y:0}, G_REAL, currentP];
  if (anim.active && anim.intersect) { pts.push(anim.intersect); pts.push(anim.final); }
  var minX=Infinity, maxX=-Infinity, minY=Infinity, maxY=-Infinity;
  pts.forEach(function(p) {
    if(p.x<minX) minX=p.x; if(p.x>maxX) maxX=p.x;
    if(p.y<minY) minY=p.y; if(p.y>maxY) maxY=p.y;
  });
  var mabsY = Math.max(Math.abs(minY), Math.abs(maxY), 3);
  minY=-mabsY; maxY=mabsY;
  var padX=Math.max((maxX-minX)*0.5,5), padY=Math.max((maxY-minY)*0.2,2);
  minX-=padX; maxX+=padX; minY-=padY; maxY+=padY;
  var ns = Math.min(canvas.width/(maxX-minX), canvas.height/(maxY-minY));
  if(ns>60) ns=60; if(ns<0.1) ns=0.1;
  view.targetScale = ns;
  view.targetOffX  = (canvas.width/2)  - (((minX+maxX)/2)*ns);
  view.targetOffY  = (canvas.height/2) + (((minY+maxY)/2)*ns);
  if(instant) { view.scale=view.targetScale; view.offsetX=view.targetOffX; view.offsetY=view.targetOffY; }
}
function manualZoom(d) { view.targetScale += d; if(view.targetScale<1) view.targetScale=1; }
function updateCam() {
  var e=0.08;
  view.scale   += (view.targetScale - view.scale)   * e;
  view.offsetX += (view.targetOffX  - view.offsetX) * e;
  view.offsetY += (view.targetOffY  - view.offsetY) * e;
  document.getElementById('camera-info').innerText = 'Scale: ' + view.scale.toFixed(1) + 'x';
}
function toScreen(p) { return { x: view.offsetX + p.x*view.scale, y: view.offsetY - p.y*view.scale }; }

// ── Draw Real Field ────────────────────────────────────────────────────────
function drawGrid() {
  ctx.strokeStyle='#1a1a1a'; ctx.lineWidth=1;
  var o=toScreen({x:0,y:0});
  ctx.beginPath(); ctx.moveTo(o.x,0); ctx.lineTo(o.x,canvas.height);
  ctx.moveTo(0,o.y); ctx.lineTo(canvas.width,o.y); ctx.stroke();
}
function drawCurve() {
  var startX=(-view.offsetX)/view.scale, endX=(canvas.width-view.offsetX)/view.scale;
  var root=-Math.pow(B,1/3);
  var step=Math.min(Math.max(8/view.scale,0.005),0.5);
  ctx.strokeStyle='#F7931A'; ctx.lineWidth=2;
  ctx.beginPath();
  var first=true;
  for (var x=Math.max(startX,root); x<endX; x+=step) {
    var y=getCurveY(x); if(isNaN(y)) continue;
    var s=toScreen({x:x,y:y}); if(first){ctx.moveTo(s.x,s.y);first=false;}else ctx.lineTo(s.x,s.y);
  }
  ctx.stroke();
  ctx.beginPath(); first=true;
  for (var x=Math.max(startX,root); x<endX; x+=step) {
    var y=getCurveY(x); if(isNaN(y)) continue;
    var s=toScreen({x:x,y:-y}); if(first){ctx.moveTo(s.x,s.y);first=false;}else ctx.lineTo(s.x,s.y);
  }
  ctx.stroke();
}
function drawPoint(p, color, label, size) {
  size = size || 6;
  var s=toScreen(p);
  if(s.x<-50||s.x>canvas.width+50||s.y<-50||s.y>canvas.height+50) return;
  ctx.shadowBlur=10; ctx.shadowColor=color; ctx.fillStyle=color;
  ctx.beginPath(); ctx.arc(s.x,s.y,size,0,Math.PI*2); ctx.fill();
  ctx.shadowBlur=0; ctx.fillStyle='white'; ctx.font='12px Share Tech Mono';
  ctx.fillText(label, s.x+10, s.y-10);
}

// ── Render Loop ────────────────────────────────────────────────────────────
function renderLoop() {
  updateCam();
  ctx.fillStyle='#000'; ctx.fillRect(0,0,canvas.width,canvas.height);
  if (currentMode === 'real') {
    drawGrid(); drawCurve();
    drawPoint(G_REAL, '#00f3ff', 'G');
    if (anim.active) renderAnim(); else drawPoint(currentP, '#00ff41', 'P(' + privateKey + 'G)');
  } else {
    drawFFMain();
  }
  requestAnimationFrame(renderLoop);
}
function renderAnim() {
  drawPoint(anim.p1,'#555','');
  var s1=toScreen(anim.p1), sI=toScreen(anim.intersect);
  ctx.strokeStyle='#ff0055'; ctx.lineWidth=2; ctx.setLineDash([5,5]);
  ctx.beginPath(); ctx.moveTo(s1.x,s1.y);
  var ph1=Math.min(anim.progress*1.5,1);
  ctx.lineTo(s1.x+(sI.x-s1.x)*ph1, s1.y+(sI.y-s1.y)*ph1); ctx.stroke(); ctx.setLineDash([]);
  if (anim.progress > 0.6) {
    var sF=toScreen(anim.final), ph2=Math.min((anim.progress-0.6)*2.5,1);
    ctx.strokeStyle='#00ff41'; ctx.lineWidth=2;
    ctx.beginPath(); ctx.moveTo(sI.x,sI.y); ctx.lineTo(sI.x+(sF.x-sI.x)*ph2,sI.y+(sF.y-sI.y)*ph2); ctx.stroke();
    ctx.fillStyle='rgba(255,0,85,.5)'; ctx.beginPath(); ctx.arc(sI.x,sI.y,4,0,Math.PI*2); ctx.fill();
  }
  if (anim.progress > 0.9) drawPoint(anim.final,'#00ff41','P('+privateKey+'G)');
  anim.progress += anim.speed;
  if (anim.progress > 1.3) { anim.active=false; anim.progress=0; }
}

// ── Real Field Controls ────────────────────────────────────────────────────
function stepForward() {
  if (anim.active) return;
  var prev = { x:currentP.x, y:currentP.y };
  var res  = addPointsReal(currentP, G_REAL);
  anim.p1=prev; anim.p2=G_REAL;
  anim.intersect={x:res.x,y:-res.y}; anim.final=res;
  anim.progress=0; anim.active=true;
  currentP=res; privateKey++;
  autoFit(); updateUI();
}
function updateUI() {
  document.getElementById('private-key-display').innerText = privateKey;
  document.getElementById('pub-x').innerText = currentP.x.toFixed(4);
  document.getElementById('pub-y').innerText = currentP.y.toFixed(4);
  var st=document.getElementById('coord-status');
  st.innerText='UPDATED'; setTimeout(function(){st.innerText='';},1000);
  var box=document.getElementById('explanation-box'), html='';
  if (privateKey===1) html='<p class="mb-1 text-[#00ff41] font-bold">เริ่มต้น (Initial)</p>d=1 → Public Key = <b>G</b>';
  else if (privateKey===2) html='<p class="mb-1 text-[#F7931A] font-bold">Point Doubling (G+G)</p>ลากเส้นสัมผัสที่ G → สะท้อนได้ <b>2G</b>';
  else html='<p class="mb-1 text-[#00f3ff] font-bold">Point Addition</p>ลากผ่าน <b>'+(privateKey-1)+'G</b> + G → ได้ <b>'+privateKey+'G</b>';
  box.innerHTML=html;
}
function resetSim() {
  if (autoPlayTimer) { clearInterval(autoPlayTimer); autoPlayTimer=null; }
  anim.active=false; privateKey=1; currentP={x:G_REAL.x,y:G_REAL.y};
  autoFit(true); updateUI();
  var btn=document.getElementById('btn-auto');
  btn.innerHTML='<i class="fa-solid fa-play"></i> Auto';
  btn.classList.remove('bg-red-900','text-white');
}
function autoPlay() {
  var btn=document.getElementById('btn-auto');
  if (autoPlayTimer) {
    clearInterval(autoPlayTimer); autoPlayTimer=null;
    btn.innerHTML='<i class="fa-solid fa-play"></i> Auto';
    btn.classList.remove('bg-red-900','text-white');
  } else {
    btn.innerHTML='<i class="fa-solid fa-pause"></i> Stop';
    btn.classList.add('bg-red-900','text-white');
    stepForward();
    autoPlayTimer = setInterval(function(){ if(!anim.active) stepForward(); }, 2000);
  }
}

// ══════════════════════════════════════════════════════════════
// FINITE FIELD
// ══════════════════════════════════════════════════════════════

function modPow(base, exp, mod) {
  var result=1n, b=BigInt(base)%BigInt(mod), e=BigInt(exp), m=BigInt(mod);
  while(e>0n){
    if(e%2n===1n) result=result*b%m;
    e=e/2n; b=b*b%m;
  }
  return Number(result);
}
function modInverse(a, p) { return modPow(a, p-2, p); }

function computeFFPoints() {
  ffPoints = [];
  for (var x=0; x<ffPrime; x++) {
    var rhs=((x*x*x+B)%ffPrime+ffPrime)%ffPrime;
    for (var y=0; y<ffPrime; y++) {
      if((y*y%ffPrime)===rhs) ffPoints.push({x:x,y:y});
    }
  }
}

function ffAdd(p1, p2, p) {
  if (!p1) return { pt: p2, steps: [] };
  if (!p2) return { pt: p1, steps: [] };
  if (p1.x===p2.x && p1.y!==p2.y) return { pt: null, steps: [{ label:'ผลลัพธ์', formula:'P + (−P) = จุดอนันต์ (O)', calc:'', val:'', highlight:false }] };
  var lam, steps=[], num, den, denI;
if (p1.x===p2.x && p1.y===p2.y) {
    if (p1.y === 0) {
        return { pt: null, steps: [{ label:'ผลลัพธ์', formula:'2P = จุดอนันต์ (O)', calc:'y=0', val:'', highlight:false }] };
    }
    num  = ((3*p1.x*p1.x+A)%p+p)%p;
    den  = (2*p1.y%p+p)%p;
    denI = modInverse(den,p);
    lam  = (num*denI)%p;
    steps.push({ label:'λ', formula:'(3x₁²+a)·(2y₁)⁻¹ mod p',
      calc:'(3·'+p1.x+'²)·(2·'+p1.y+')⁻¹ mod '+p,
      val:'= '+num+' · '+denI+' mod '+p+' = '+lam, highlight:true });
  } else {
    num  = ((p2.y-p1.y)%p+p)%p;
    den  = ((p2.x-p1.x)%p+p)%p;
    denI = modInverse(den,p);
    lam  = (num*denI)%p;
    steps.push({ label:'λ', formula:'(y₂−y₁)·(x₂−x₁)⁻¹ mod p',
      calc:'('+p2.y+'−'+p1.y+')·('+p2.x+'−'+p1.x+')⁻¹ mod '+p,
      val:'= '+num+' · '+denI+' mod '+p+' = '+lam, highlight:true });
  }
  var x3=((lam*lam-p1.x-p2.x)%p+p)%p;
  var y3=((lam*(p1.x-x3)-p1.y)%p+p)%p;
  steps.push({ label:'x₃', formula:'λ²−x₁−x₂ mod p',
    calc:lam+'²−'+p1.x+'−'+p2.x+' mod '+p, val:'= '+x3, highlight:false });
  steps.push({ label:'y₃', formula:'λ(x₁−x₃)−y₁ mod p',
    calc:lam+'·('+p1.x+'−'+x3+')−'+p1.y+' mod '+p, val:'= '+y3, highlight:false });
  return { pt:{x:x3,y:y3}, steps:steps };
}

function ffScalarMul(k, p) {
  var pts=[];
  for(var x=0;x<p;x++){var rhs=((x*x*x+B)%p+p)%p;for(var y=0;y<p;y++){if((y*y%p)===rhs)pts.push({x:x,y:y});}}
  if(pts.length===0) return null;
  var Gp=pts[0], R=null, Q={x:Gp.x,y:Gp.y}, rem=k;
  while(rem>0){
    if(rem%2===1){ var r=ffAdd(R,Q,p); R=r.pt; }
    var q2=ffAdd(Q,Q,p); Q=q2.pt; rem=Math.floor(rem/2);
  }
  return { result:R, G:Gp };
}

function updateFFLabel() {
  var txt='p='+ffPrime+' · '+ffPoints.length+' จุดบนโค้ง';
  document.getElementById('ff-point-count').innerText=txt;
  document.getElementById('ff-canvas-label').innerText='p = '+ffPrime;
  document.getElementById('main-ff-label').innerText='p = '+ffPrime;
}

function setPrime(p) {
  ffPrime=p; ffP1=null; ffP2=null;
  document.getElementById('prime-display').innerText='p='+p;
  document.getElementById('prime-slider').value=PRIMES.indexOf(p);
  document.getElementById('p1-display').innerText='— ยังไม่เลือก';
  document.getElementById('p2-display').innerText='— ยังไม่เลือก';
  document.getElementById('ff-result-box').classList.add('hidden');
  document.getElementById('ff-scalar-result').innerText='';
  computeFFPoints(); updateFFLabel(); refreshPrimeBtns();
  renderFFGrid(); drawFFOverlay();
}
function setPrimeByIndex(idx) { setPrime(PRIMES[parseInt(idx)]); }

function initFFLab() {
  computeFFPoints(); updateFFLabel(); renderFFGrid(); drawFFOverlay();
}

function renderFFGrid() {
  var grid=document.getElementById('ff-grid');
  grid.innerHTML='';
  if(ffPoints.length===0){
    grid.innerHTML='<span class="text-gray-600 text-xs">ไม่มีจุดบนโค้งสำหรับ p นี้</span>'; return;
  }
  ffPoints.forEach(function(pt){
    var dot=document.createElement('div');
    dot.className='ff-point valid';
    dot.title='('+pt.x+', '+pt.y+')';
    dot.textContent=pt.x+','+pt.y;
    if(ffP1&&pt.x===ffP1.x&&pt.y===ffP1.y) dot.classList.add('p1-dot');
    if(ffP2&&pt.x===ffP2.x&&pt.y===ffP2.y) dot.classList.add('p2-dot');
    dot.onclick=(function(p){return function(){selectFFPoint(p);};})(pt);
    grid.appendChild(dot);
  });
}

function selectFFPoint(pt) {
  if (!ffP1 || (ffP1 && ffP2)) {
    ffP1={x:pt.x,y:pt.y}; ffP2=null;
    document.getElementById('p1-display').innerText='('+pt.x+', '+pt.y+')';
    document.getElementById('p2-display').innerText='— ยังไม่เลือก';
    document.getElementById('ff-result-box').classList.add('hidden');
  } else {
    ffP2={x:pt.x,y:pt.y};
    document.getElementById('p2-display').innerText='('+pt.x+', '+pt.y+')';
  }
  renderFFGrid(); drawFFOverlay();
}

function computeFFAdd() {
  if(!ffP1||!ffP2){ alert('กรุณาเลือก P1 และ P2 ก่อน (คลิกจากจุดสีฟ้าด้านบน)'); return; }
  var res=ffAdd(ffP1,ffP2,ffPrime);
  var box=document.getElementById('ff-result-box');
  var coords=document.getElementById('ff-result-coords');
  var stepsDiv=document.getElementById('ff-steps');
  box.classList.remove('hidden');
  if(!res.pt){
    coords.innerText='Point at Infinity (O)';
    stepsDiv.innerHTML='<div class="mod-step text-gray-500">P + (−P) = จุดอนันต์</div>';
  } else {
    coords.innerText='R = ('+res.pt.x+', '+res.pt.y+')';
    stepsDiv.innerHTML=res.steps.map(function(s){
      return '<div class="mod-step'+(s.highlight?' highlight':'')+'">'+
        '<div class="text-[#F7931A] text-[10px]">'+s.label+': <span class="text-gray-400">'+s.formula+'</span></div>'+
        '<div class="text-gray-300">'+s.calc+'</div>'+
        '<div class="text-[#00ff41] font-bold">'+s.val+'</div></div>';
    }).join('');
  }
  renderFFGrid(); drawFFOverlay();
}

function computeFFScalar() {
  var k=parseInt(document.getElementById('ff-scalar').value);
  if(isNaN(k)||k<1) return;
  var res=ffScalarMul(k,ffPrime);
  var el=document.getElementById('ff-scalar-result');
  if(!res||!res.result){ el.innerHTML='<span class="text-red-400">ผลลัพธ์เป็น Point at Infinity</span>'; return; }
  el.innerHTML='<span class="text-[#bf5fff]">'+k+'</span> × G('+res.G.x+','+res.G.y+') = <span class="text-[#F7931A] font-bold">('+res.result.x+', '+res.result.y+')</span> mod '+ffPrime;
}

// ── FF Overlay Canvas ──────────────────────────────────────────────────────
function drawFFOverlay() {
  var c=document.getElementById('ff-canvas');
  var cx=c.getContext('2d');
  c.width=c.offsetWidth||400; c.height=c.offsetHeight||300;
  var W=c.width, H=c.height, p=ffPrime, PAD=36;
  cx.fillStyle='#000'; cx.fillRect(0,0,W,H);
  var cw=(W-PAD*2)/p, ch=(H-PAD*2)/p;
  // grid
  cx.strokeStyle='#1a1a1a'; cx.lineWidth=.5;
  for(var i=0;i<=p;i++){
    cx.beginPath(); cx.moveTo(PAD+i*cw,PAD); cx.lineTo(PAD+i*cw,H-PAD); cx.stroke();
    cx.beginPath(); cx.moveTo(PAD,PAD+i*ch); cx.lineTo(W-PAD,PAD+i*ch); cx.stroke();
  }
  // axis labels
  cx.fillStyle='#444'; cx.font='9px Share Tech Mono';
  var step=Math.max(1,Math.floor(p/8));
  for(var i=0;i<p;i+=step){
    cx.fillText(i, PAD+i*cw-3, H-PAD+12);
    cx.fillText(i, PAD-20, H-PAD-i*ch+3);
  }
  cx.fillStyle='#555'; cx.font='10px Chakra Petch';
  cx.fillText('x →',W-22,H-PAD+12);
  cx.save(); cx.translate(10,H/2); cx.rotate(-Math.PI/2); cx.fillText('y ↑',0,0); cx.restore();
  // result point
  var resData=null;
  if(ffP1&&ffP2){ var r=ffAdd(ffP1,ffP2,ffPrime); if(r&&r.pt) resData=r.pt; }
  // all curve points
  ffPoints.forEach(function(pt){
    var sx=PAD+pt.x*cw, sy=H-PAD-pt.y*ch;
    var isP1=ffP1&&pt.x===ffP1.x&&pt.y===ffP1.y;
    var isP2=ffP2&&pt.x===ffP2.x&&pt.y===ffP2.y;
    var isRes=resData&&pt.x===resData.x&&pt.y===resData.y;
    var color='rgba(0,243,255,.55)', sz=Math.min(cw,ch)*0.38;
    if(isP1){color='#F7931A'; sz*=1.6;}
    if(isP2){color='#bf5fff'; sz*=1.6;}
    if(isRes){color='#ff0055'; sz*=1.8;}
    cx.shadowBlur=isP1||isP2||isRes?14:3;
    cx.shadowColor=color; cx.fillStyle=color;
    cx.beginPath(); cx.arc(sx,sy,sz,0,Math.PI*2); cx.fill(); cx.shadowBlur=0;
    if(isP1){ cx.fillStyle='white'; cx.font='bold 9px Share Tech Mono'; cx.fillText('P1',sx+sz+2,sy-2); }
    if(isP2){ cx.fillStyle='white'; cx.font='bold 9px Share Tech Mono'; cx.fillText('P2',sx+sz+2,sy-2); }
    if(isRes){ cx.fillStyle='white'; cx.font='bold 9px Share Tech Mono'; cx.fillText('R',sx+sz+2,sy-2); }
  });
  // dashed line P1→R, P2→R
  if(resData&&ffP1&&ffP2){
    var rx=PAD+resData.x*cw, ry=H-PAD-resData.y*ch;
    cx.strokeStyle='rgba(255,0,85,.35)'; cx.lineWidth=1; cx.setLineDash([3,3]);
    cx.beginPath(); cx.moveTo(PAD+ffP1.x*cw,H-PAD-ffP1.y*ch); cx.lineTo(rx,ry); cx.stroke();
    cx.beginPath(); cx.moveTo(PAD+ffP2.x*cw,H-PAD-ffP2.y*ch); cx.lineTo(rx,ry); cx.stroke();
    cx.setLineDash([]);
  }
  // Generator ring
  if(ffPoints.length>0){
    var Gp=ffPoints[0], gx=PAD+Gp.x*cw, gy=H-PAD-Gp.y*ch;
    cx.strokeStyle='rgba(0,255,65,.7)'; cx.lineWidth=2;
    cx.beginPath(); cx.arc(gx,gy,Math.min(cw,ch)*0.38+4,0,Math.PI*2); cx.stroke();
    cx.fillStyle='rgba(0,255,65,.9)'; cx.font='bold 9px Share Tech Mono'; cx.fillText('G',gx+8,gy-6);
  }
}

// ── Main Canvas: Finite Field Mode ────────────────────────────────────────
function drawFFMain() {
  var W=canvas.width, H=canvas.height, p=ffPrime, PAD=44;
  ctx.fillStyle='#000'; ctx.fillRect(0,0,W,H);
  var cw=(W-PAD*2)/(p+1), ch=(H-PAD*2)/(p+1);
  ctx.strokeStyle='#111'; ctx.lineWidth=.5;
  for(var i=0;i<=p;i++){
    ctx.beginPath(); ctx.moveTo(PAD+i*cw,PAD); ctx.lineTo(PAD+i*cw,H-PAD); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(PAD,PAD+i*ch); ctx.lineTo(W-PAD,PAD+i*ch); ctx.stroke();
  }
  ctx.fillStyle='#2a2a2a'; ctx.font='10px Share Tech Mono';
  var step2=Math.max(1,Math.floor(p/10));
  for(var i=0;i<p;i+=step2){
    ctx.fillText(i,PAD+i*cw-3,H-PAD+16);
    ctx.fillText(i,PAD-22,H-PAD-i*ch+4);
  }
  ctx.fillStyle='#555'; ctx.font='12px Chakra Petch';
  ctx.fillText('x →',W-35,H-PAD+16);
  ctx.save(); ctx.translate(16,H/2); ctx.rotate(-Math.PI/2); ctx.fillText('y ↑',0,0); ctx.restore();
  var ptSz=Math.min(Math.max(Math.min(cw,ch)*0.4,3),10);
  ffPoints.forEach(function(pt){
    var sx=PAD+pt.x*cw, sy=H-PAD-pt.y*ch;
    ctx.shadowBlur=8; ctx.shadowColor='rgba(0,243,255,.5)';
    ctx.fillStyle='rgba(0,243,255,.75)';
    ctx.beginPath(); ctx.arc(sx,sy,ptSz,0,Math.PI*2); ctx.fill();
    ctx.shadowBlur=0;
  });
  if(ffPoints.length>0){
    var Gp=ffPoints[0], gx=PAD+Gp.x*cw, gy=H-PAD-Gp.y*ch;
    ctx.shadowBlur=15; ctx.shadowColor='rgba(0,255,65,.8)';
    ctx.fillStyle='rgba(0,255,65,.9)';
    ctx.beginPath(); ctx.arc(gx,gy,ptSz+5,0,Math.PI*2); ctx.fill();
    ctx.shadowBlur=0; ctx.fillStyle='white'; ctx.font='bold 13px Share Tech Mono';
    ctx.fillText('G',gx+ptSz+6,gy-ptSz-2);
  }
  ctx.fillStyle='rgba(191,95,255,.85)'; ctx.font='bold 14px Chakra Petch';
  ctx.fillText('y² ≡ x³ + 7 (mod '+p+')', PAD, PAD-8);
  ctx.fillStyle='rgba(255,255,255,.35)'; ctx.font='11px Share Tech Mono';
  ctx.fillText(ffPoints.length+' จุดบนโค้ง', PAD, PAD+16);
}

// ── Touch/Mouse Pan ────────────────────────────────────────────────────────
var drag={active:false,startX:0,startY:0,startOffX:0,startOffY:0};
container.addEventListener('mousedown',function(e){
  drag.active=true; drag.startX=e.clientX; drag.startY=e.clientY;
  drag.startOffX=view.offsetX; drag.startOffY=view.offsetY;
});
window.addEventListener('mouseup',function(){drag.active=false;});
window.addEventListener('mousemove',function(e){
  if(!drag.active) return;
  view.offsetX=drag.startOffX+(e.clientX-drag.startX);
  view.offsetY=drag.startOffY+(e.clientY-drag.startY);
  view.targetOffX=view.offsetX; view.targetOffY=view.offsetY;
});
container.addEventListener('touchstart',function(e){
  if(e.touches.length!==1) return;
  var t=e.touches[0]; drag.active=true;
  drag.startX=t.clientX; drag.startY=t.clientY;
  drag.startOffX=view.offsetX; drag.startOffY=view.offsetY;
},{passive:true});
window.addEventListener('touchend',function(){drag.active=false;});
window.addEventListener('touchmove',function(e){
  if(!drag.active||e.touches.length!==1) return;
  var t=e.touches[0];
  view.offsetX=drag.startOffX+(t.clientX-drag.startX);
  view.offsetY=drag.startOffY+(t.clientY-drag.startY);
  view.targetOffX=view.offsetX; view.targetOffY=view.offsetY;
},{passive:true});
</script>
</body>
</html>
