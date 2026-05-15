<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>⚡ Lightning Network Simulator</title>
<link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;600;700&family=Kanit:wght@300;400&family=Orbitron:wght@400;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
:root{
    --bg:#050508;--card:rgba(14,14,26,0.9);
    --gold:#ffd700;--blue:#00f3ff;--green:#0aff00;
    --red:#ff2a2a;--orange:#ff9d00;--purple:#b060ff;
    --dim:#555;--muted:#888;--main:#ddd;
    --mono:'JetBrains Mono',monospace;
}
*{box-sizing:border-box;margin:0;padding:0}
body{
    background:var(--bg);
    background-image:
        radial-gradient(ellipse at 15% 40%,rgba(255,215,0,.05) 0,transparent 40%),
        radial-gradient(ellipse at 85% 15%,rgba(0,243,255,.05) 0,transparent 40%),
        radial-gradient(ellipse at 50% 85%,rgba(176,96,255,.04) 0,transparent 40%);
    color:var(--main);font-family:'Kanit',sans-serif;font-weight:300;
    min-height:100vh;padding-bottom:60px;
}
body::before{content:"";position:fixed;inset:0;
    background:linear-gradient(rgba(255,255,255,.016) 1px,transparent 1px),
               linear-gradient(90deg,rgba(255,255,255,.016) 1px,transparent 1px);
    background-size:48px 48px;z-index:-1;pointer-events:none;}

.hdr{text-align:center;padding:14px 20px 10px}
.hdr h1{font-family:'Orbitron',sans-serif;font-size:1.6rem;letter-spacing:3px;
    background:linear-gradient(90deg,var(--gold),#fff 50%,var(--gold));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;}
.hdr .sub{font-family:'Chakra Petch',sans-serif;color:var(--blue);font-size:.8rem;margin:4px 0 10px;opacity:.8}
.badge{display:inline-flex;align-items:center;gap:5px;padding:4px 13px;border-radius:20px;
    font-family:'Chakra Petch',sans-serif;font-size:.63rem;cursor:pointer;transition:.2s}
.badge.edu{background:rgba(255,215,0,.06);border:1px solid rgba(255,215,0,.2);color:var(--gold);cursor:default}
.badge.tour{background:rgba(0,243,255,.06);border:1px solid rgba(0,243,255,.2);color:var(--blue)}
.badge.tour:hover{background:rgba(0,243,255,.14)}

.prog-wrap{max-width:1500px;margin:0 auto 6px;padding:0 20px;display:flex;align-items:center;gap:10px}
.prog-lbl{font-family:'Orbitron',sans-serif;font-size:.52rem;color:var(--dim);white-space:nowrap;letter-spacing:.5px}
.prog-track{flex:1;height:3px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden}
.prog-fill{height:100%;background:linear-gradient(90deg,var(--gold),var(--green));border-radius:3px;transition:width .6s ease;width:0%}
.prog-step{font-family:var(--mono);font-size:.56rem;color:var(--gold);white-space:nowrap}

.main-grid{max-width:1500px;margin:0 auto;padding:8px 20px 20px;
    display:grid;grid-template-columns:260px 1fr 300px;gap:12px;align-items:start;}
@media(max-width:1100px){.main-grid{grid-template-columns:260px 1fr}}
@media(max-width:760px){.main-grid{grid-template-columns:1fr}}

.panel{background:var(--card);border:1px solid rgba(255,255,255,.07);
    border-radius:10px;padding:14px 16px;backdrop-filter:blur(14px)}
.ptitle{font-family:'Orbitron',sans-serif;font-size:.65rem;color:var(--gold);
    letter-spacing:1.5px;margin-bottom:10px;display:flex;align-items:center;gap:7px;
    border-bottom:1px solid rgba(255,215,0,.08);padding-bottom:7px}

.sbadge{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:20px;
    font-family:'Orbitron',sans-serif;font-size:.54rem;letter-spacing:.5px}
.sbadge.open{background:rgba(10,255,0,.08);border:1px solid rgba(10,255,0,.3);color:var(--green)}
.sbadge.setup{background:rgba(0,243,255,.08);border:1px solid rgba(0,243,255,.3);color:var(--blue)}
.sdot{width:6px;height:6px;border-radius:50%;background:currentColor}
.sbadge.open .sdot{animation:pdot 1.5s infinite}
@keyframes pdot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.7)}}

.hint{background:rgba(0,243,255,.04);border-left:2px solid rgba(0,243,255,.22);
    border-radius:0 6px 6px 0;padding:6px 11px;font-size:.68rem;color:#7aa;
    line-height:1.65;margin-bottom:10px;font-family:'Chakra Petch',sans-serif}
.hint strong{color:var(--blue)}

.cg{margin-bottom:7px}
.cg label{display:block;font-family:'Chakra Petch',sans-serif;font-size:.63rem;color:var(--muted);margin-bottom:3px}
.cg input[type=range]{width:100%;accent-color:var(--gold);cursor:pointer}
.vdisp{text-align:right;font-family:var(--mono);font-size:.72rem;color:var(--gold);margin-top:2px}
.cg input[type=number]{width:100%;background:rgba(0,0,0,.5);border:1px solid rgba(255,215,0,.18);
    border-radius:6px;color:#fff;font-family:var(--mono);font-size:.78rem;padding:5px 10px;
    outline:none;transition:border-color .2s}
.cg input[type=number]:focus{border-color:var(--gold)}
.sub-val{font-family:var(--mono);font-size:.58rem;color:var(--dim);text-align:right;margin-top:1px}

.preset-row{display:flex;gap:4px;flex-wrap:wrap;margin-bottom:10px}
.pbtn{font-family:var(--mono);font-size:.58rem;padding:3px 8px;
    background:rgba(0,0,0,.4);border:1px solid rgba(255,255,255,.1);
    border-radius:4px;color:var(--dim);cursor:pointer;transition:.2s}
.pbtn:hover{border-color:var(--gold);color:var(--gold)}

.btn-row{display:flex;gap:6px;flex-wrap:wrap;margin-top:6px}
.btn{flex:1;padding:8px 6px;background:transparent;border:1px solid var(--gold);
    color:var(--gold);font-family:'Orbitron',sans-serif;font-size:.6rem;
    border-radius:6px;cursor:pointer;transition:background .2s,box-shadow .2s;letter-spacing:.4px;white-space:nowrap}
.btn:hover:not(:disabled){background:rgba(255,215,0,.1);box-shadow:0 0 12px rgba(255,215,0,.1)}
.btn:disabled{opacity:.2;cursor:not-allowed}
.btn.red{border-color:var(--red);color:var(--red)}
.btn.red:hover:not(:disabled){background:rgba(255,42,42,.1)}
.btn.grn{border-color:var(--green);color:var(--green)}
.btn.grn:hover:not(:disabled){background:rgba(10,255,0,.08)}
.btn.blu{border-color:var(--blue);color:var(--blue)}
.btn.blu:hover:not(:disabled){background:rgba(0,243,255,.08)}
.btn.pur{border-color:var(--purple);color:var(--purple)}
.btn.pur:hover:not(:disabled){background:rgba(176,96,255,.1)}

/* ── NETWORK CANVAS ─────────────────────── */
#net-canvas{
    width:100%;border-radius:10px;background:rgba(0,0,0,.5);
    border:1px solid rgba(255,215,0,.08);display:block;
    cursor:default;user-select:none;
}
#net-canvas.dragging{cursor:grabbing}

/* ── NODE POPUP (click on canvas node) ──── */
#node-popup{
    display:none;position:absolute;
    background:rgba(8,8,20,.97);border:1px solid rgba(255,215,0,.3);
    border-radius:10px;padding:14px 16px;min-width:200px;z-index:100;
    box-shadow:0 8px 32px rgba(0,0,0,.6);
}
#node-popup.show{display:block;animation:popfade .15s ease}
@keyframes popfade{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:none}}
#node-popup .np-title{font-family:'Orbitron',sans-serif;font-size:.72rem;margin-bottom:10px;letter-spacing:1px}
#node-popup .np-row{display:flex;align-items:center;justify-content:space-between;
    margin-bottom:8px;font-family:'Chakra Petch',sans-serif;font-size:.65rem;color:var(--muted)}
#node-popup .np-val{font-family:var(--mono);font-size:.7rem;color:var(--main)}
#node-popup input[type=number]{
    width:64px;background:rgba(0,0,0,.5);border:1px solid rgba(255,215,0,.2);
    border-radius:4px;color:var(--gold);font-family:var(--mono);font-size:.68rem;
    padding:3px 6px;outline:none;text-align:right}
#node-popup .np-btn-row{display:flex;gap:6px;margin-top:10px}
#node-popup .np-btn{flex:1;padding:6px 4px;background:transparent;font-family:'Orbitron',sans-serif;
    font-size:.55rem;border-radius:5px;cursor:pointer;transition:.2s;border:1px solid}
#node-popup .np-btn.cycle{border-color:var(--gold);color:var(--gold)}
#node-popup .np-btn.cycle:hover{background:rgba(255,215,0,.12)}
#node-popup .np-btn.del{border-color:var(--red);color:var(--red)}
#node-popup .np-btn.del:hover{background:rgba(255,42,42,.12)}
#node-popup .np-btn.close{border-color:rgba(255,255,255,.15);color:var(--dim)}
#node-popup .np-btn.close:hover{border-color:rgba(255,255,255,.3);color:var(--muted)}
.np-status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:4px}

/* ── ROUTE PATH DISPLAY ─────────────────── */
#route-path{display:flex;align-items:center;justify-content:center;gap:0;
    flex-wrap:wrap;min-height:40px;padding:6px 0;margin-bottom:8px}
.rn{display:flex;flex-direction:column;align-items:center;gap:2px}
.rn-dot{width:10px;height:10px;border-radius:50%;border:2px solid currentColor}
.rn-name{font-family:'Orbitron',sans-serif;font-size:.48rem;letter-spacing:.4px}
.rn-fee{font-family:var(--mono);font-size:.48rem;color:var(--dim)}
.rn-arrow{padding:0 3px;color:rgba(255,215,0,.25);font-size:.75rem;align-self:center;position:relative;top:-6px}
.route-fail{font-family:'Chakra Petch',sans-serif;font-size:.68rem;color:var(--red);
    text-align:center;padding:7px;border:1px solid rgba(255,42,42,.2);border-radius:6px;width:100%}

/* ── CHANNEL BAR + BALANCE ──────────────── */
.ch-state{display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:center;margin-bottom:8px}
.pbox{background:rgba(0,0,0,.35);border-radius:8px;padding:10px 8px;text-align:center;
    border:1px solid rgba(255,255,255,.06);transition:border-color .3s,box-shadow .3s}
.pbox.sender{border-color:var(--gold);box-shadow:0 0 16px rgba(255,215,0,.1)}
.pbox.receiver{border-color:var(--green);box-shadow:0 0 16px rgba(10,255,0,.08)}
.pname{font-family:'Orbitron',sans-serif;font-size:.6rem;color:var(--dim);margin-bottom:2px}
.pbal{font-family:var(--mono);font-size:1rem;font-weight:700;color:var(--gold);transition:color .3s}
.pbal.up{color:var(--green)}.pbal.dn{color:var(--red)}
.punit{font-size:.56rem;color:var(--dim);font-family:'Chakra Petch',sans-serif}
.pusd{font-size:.58rem;color:#3a3a3a;font-family:var(--mono);margin-top:1px}
.ch-arrow{font-size:1.1rem;color:var(--gold);text-align:center;animation:ap2 2s infinite}
@keyframes ap2{0%,100%{opacity:.3}50%{opacity:1}}

.metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:8px}
.mbox{background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.05);
    border-radius:7px;padding:7px 8px;text-align:center}
.mlbl{font-family:'Orbitron',sans-serif;font-size:.46rem;color:var(--dim);letter-spacing:.5px;margin-bottom:2px}
.mval{font-family:var(--mono);font-size:.78rem;font-weight:700;color:#fff}
.mval.gold{color:var(--gold)}.mval.grn{color:var(--green)}.mval.red{color:var(--red)}.mval.blu{color:var(--blue)}.mval.pur{color:var(--purple)}

#htlc-strip{display:block;background:rgba(176,96,255,.05);border:1px solid rgba(176,96,255,.18);
    border-radius:8px;padding:8px 12px;margin-bottom:8px}
.htlc-title{font-family:'Orbitron',sans-serif;font-size:.52rem;color:var(--purple);letter-spacing:1px;margin-bottom:4px}
.hs{display:flex;align-items:center;gap:6px;font-family:var(--mono);font-size:.62rem;
    color:var(--dim);padding:2px 0;transition:color .25s}
.hs.active{color:var(--purple)}.hs.done{color:var(--green)}
.hdot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}

.wt-bar{display:flex;align-items:center;gap:6px;padding:5px 10px;
    background:rgba(176,96,255,.04);border:1px solid rgba(176,96,255,.12);
    border-radius:6px;font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:#555;margin-bottom:8px}
.wt-dot{width:5px;height:5px;border-radius:50%;background:var(--purple);animation:pdot 2s infinite}

/* fee comparison removed */

#tx-log{background:rgba(0,0,0,.5);border:1px solid rgba(255,255,255,.05);
    border-radius:8px;padding:8px 10px;height:200px;overflow-y:auto;font-family:var(--mono);font-size:.63rem}
.le{padding:3px 0;border-bottom:1px solid rgba(255,255,255,.03);
    display:flex;gap:6px;align-items:flex-start;animation:fadein .2s ease}
@keyframes fadein{from{opacity:0;transform:translateY(2px)}to{opacity:1;transform:none}}
.lt{color:#2e2e2e;flex-shrink:0}.li{flex-shrink:0;width:13px;text-align:center}
.lm{color:#555;line-height:1.5;word-break:break-all}
.le.ok .lm{color:var(--green)}.le.err .lm{color:var(--red)}
.le.info .lm{color:var(--blue)}.le.warn .lm{color:var(--gold)}
.le.sys .lm{color:var(--purple)}.le.dim .lm{color:#333}
::-webkit-scrollbar{width:3px}
::-webkit-scrollbar-track{background:rgba(0,0,0,.2)}
::-webkit-scrollbar-thumb{background:rgba(255,215,0,.13);border-radius:3px}

.concepts-panel{grid-column:3;align-self:start;display:flex;flex-direction:column;max-height:820px;overflow:hidden}
.ctabs{display:flex;flex-direction:column;gap:3px;margin-bottom:12px}
.ctab{padding:6px 12px;background:transparent;border:1px solid rgba(255,215,0,.1);
    color:var(--dim);font-family:'Orbitron',sans-serif;font-size:.52rem;text-align:left;
    border-radius:5px;cursor:pointer;transition:.2s;letter-spacing:.5px}
.ctab:hover,.ctab.active{border-color:var(--gold);color:var(--gold);background:rgba(255,215,0,.07)}
.ccontent{display:none}.ccontent.active{display:block;animation:fadein .3s ease}
.cbody{display:flex;flex-direction:column;gap:10px}
.ccontent{overflow-y:auto;max-height:400px}
.ccontent p{color:#aaa;line-height:1.72;font-size:.78rem;margin-bottom:5px}
.ccontent strong{color:var(--gold)}.ccontent em{color:var(--blue);font-style:normal}
.callout{background:rgba(255,215,0,.04);border:1px solid rgba(255,215,0,.13);
    border-radius:6px;padding:8px 10px;margin-top:6px}
.callout p{font-size:.72rem;margin-bottom:2px}
.live-block{background:rgba(0,0,0,.5);border:1px solid rgba(255,215,0,.08);
    border-radius:8px;padding:12px 14px;font-family:var(--mono);font-size:.67rem;
    color:var(--dim);line-height:1.8;overflow-x:auto;white-space:pre;min-height:60px;font-size:.62rem}
.live-block .yl{color:var(--gold)}.live-block .gn{color:var(--green)}
.live-block .bl{color:var(--blue)}.live-block .pu{color:var(--purple)}
.live-block .rd{color:var(--red)}.live-block .or{color:var(--orange)}
.live-block .hd{color:#3a3a3a;font-size:.62rem}

#tour-ov{position:fixed;inset:0;background:rgba(0,0,0,.78);z-index:9000;
    display:none;align-items:center;justify-content:center}
#tour-ov.show{display:flex;animation:fadein .3s ease}
#tour-box{background:rgba(8,8,18,.98);border:1px solid rgba(255,215,0,.28);
    border-radius:14px;padding:30px 34px;max-width:520px;width:90%}
#tour-box h2{font-family:'Orbitron',sans-serif;font-size:.95rem;color:var(--gold);margin-bottom:11px;letter-spacing:2px}
.tbody{color:#bbb;line-height:1.9;font-size:.84rem;margin-bottom:12px}
.tbody strong{color:var(--gold)}
.tour-num{font-family:var(--mono);font-size:.62rem;color:var(--dim);margin-bottom:6px}
.tbtn{display:inline-block;margin-top:7px;padding:8px 22px;
    background:rgba(255,215,0,.1);border:1px solid var(--gold);color:var(--gold);
    font-family:'Orbitron',sans-serif;font-size:.65rem;border-radius:7px;
    cursor:pointer;letter-spacing:1px;transition:.2s;margin-right:7px}
.tbtn:hover{background:rgba(255,215,0,.2)}
.tbtn.skip{border-color:rgba(255,255,255,.1);color:#3a3a3a;font-size:.6rem}

.step3-bar{display:flex;align-items:flex-start;flex-wrap:wrap;gap:8px;}
/* ══════════════════════════════════════════════
   RESPONSIVE — TABLET (≤1100px): hide right sidebar
══════════════════════════════════════════════ */
@media(max-width:1100px){
    .main-grid{grid-template-columns:260px 1fr}
    .concepts-panel{display:none}   /* hide LC sidebar on tablet, show below */
}

/* ══════════════════════════════════════════════
   RESPONSIVE — MOBILE (≤760px): single column
══════════════════════════════════════════════ */
@media(max-width:760px){

    /* Layout: single column */
    .main-grid{
        grid-template-columns:1fr;
        padding:6px 10px 20px;
        gap:10px;
    }

    /* Header compact */
    .hdr{padding:10px 12px 8px}
    .hdr h1{font-size:1.1rem;letter-spacing:1px}
    .hdr .sub{font-size:.7rem;margin:3px 0 7px}
    .badge{font-size:.58rem;padding:3px 10px}
    .prog-wrap{padding:0 10px;margin-bottom:4px}

    /* Panel compact */
    .panel{padding:12px 13px;border-radius:9px}
    .ptitle{font-size:.6rem;margin-bottom:8px;padding-bottom:6px}

    /* Step 3 horizontal bar: stack vertically on mobile */
    .step3-bar{flex-wrap:wrap !important;gap:8px !important;}

    /* Sliders full width */
    .cg input[type=range]{width:100%}
    .cg input[type=number]{font-size:.75rem;padding:5px 9px}
    .cg{margin-bottom:7px}

    /* Button rows */
    .btn{font-size:.58rem;padding:7px 5px}
    .btn-row{gap:5px}

    /* Network canvas: taller on mobile for better drag UX */
    #net-canvas{height:260px !important}

    /* Balance boxes */
    .ch-state{gap:5px}
    .pbal{font-size:.9rem}
    .pname{font-size:.56rem}
    .pusd{font-size:.52rem}

    /* Metrics: 2 columns instead of 3 */
    .metrics{grid-template-columns:repeat(2,1fr);gap:5px}
    .mlbl{font-size:.42rem}
    .mval{font-size:.72rem}

    /* TX log shorter on mobile */
    #tx-log{height:160px;font-size:.6rem}

    /* HTLC strip compact */
    .htlc-title{font-size:.5rem}
    .hs{font-size:.6rem;gap:5px;padding:1px 0}

    /* Watchtower bar */
    .wt-bar{font-size:.55rem;padding:4px 8px}

    /* Node list */
    #mini-node-list > div{padding:4px 6px !important;gap:4px !important}

    /* Preset buttons wrap */
    .preset-row{gap:3px}
    .pbtn{font-size:.55rem;padding:3px 7px}

    /* Route path display */
    #route-path{min-height:22px}
    .rn-name{font-size:.44rem}
    .rn-fee{font-size:.44rem}
    .rn-arrow{font-size:.7rem;padding:0 2px}

    /* Node popup: full width on mobile */
    #node-popup{
        position:fixed !important;
        left:10px !important;
        right:10px !important;
        bottom:10px !important;
        top:auto !important;
        min-width:unset;
        width:auto !important;
        z-index:9999;
    }

    /* Learning center: show as normal block at bottom on mobile */
    .concepts-panel{
        display:flex !important;
        grid-column:1;
        max-height:none !important;
        overflow:visible !important;
    }
    .ccontent{max-height:none !important}
    .ctabs{flex-direction:row;flex-wrap:wrap;gap:3px}
    .ctab{font-size:.5rem;padding:4px 8px}
    .live-block{font-size:.58rem;min-height:50px}
    .cbody{flex-direction:column;gap:8px}
    .ccontent p{font-size:.76rem}

    /* Tour overlay */
    #tour-box{padding:22px 20px}
    #tour-box h2{font-size:.85rem}
    .tbody{font-size:.78rem}

    /* Step 3 bar: full stack on mobile */
    .step3-bar{flex-direction:column !important;gap:8px !important}
    .step3-bar > *{width:100% !important;margin-right:0 !important;min-width:unset !important}
    .step3-bar .btn-row-inline{width:100%}
    #send-amt{width:100%}
    #dir-ab,#dir-ba{flex:1;font-size:.58rem !important;padding:6px 5px !important}
    #btn-route,#btn-send,#btn-send5{flex:1;font-size:.58rem !important;padding:8px 5px !important}

    /* Progress bar */
    .prog-step{font-size:.5rem}
    .prog-lbl{font-size:.48rem}

    /* Footer compact */
    footer{padding:16px 12px}
}

/* ══════════════════════════════════════════════
   RESPONSIVE — SMALL MOBILE (≤420px)
══════════════════════════════════════════════ */
@media(max-width:420px){
    .hdr h1{font-size:.95rem}
    #net-canvas{height:220px !important}
    .metrics{grid-template-columns:repeat(3,1fr)}
    .mlbl{font-size:.38rem;letter-spacing:0}
    .mval{font-size:.65rem}
    .badge{font-size:.52rem;padding:3px 8px}
}
</style>
</head>
<body>

<!-- NODE POPUP (positioned via JS) -->
<div id="node-popup">
  <div class="np-title" id="np-title"></div>
  <div class="np-row"><span>สถานะ</span><span class="np-val" id="np-status"></span></div>
  <div class="np-row"><span>Liquidity</span><span class="np-val" id="np-liq"></span></div>
  <div class="np-row">
    <span>Base Fee (sat)</span>
    <input type="number" id="np-base" min="0" max="100" onchange="popupSetFee('base',this.value)">
  </div>
  <div class="np-row">
    <span>Fee Rate (ppm)</span>
    <input type="number" id="np-ppm" min="0" max="1000" onchange="popupSetFee('ppm',this.value)">
  </div>
  <div class="np-row"><span>เชื่อมกับ</span><span class="np-val" id="np-edges"></span></div>
  <div class="np-btn-row">
    <button class="np-btn cycle" onclick="popupCycleStatus()">Toggle Status</button>
    <button class="np-btn del" onclick="popupDelete()">✕ ลบ</button>
    <button class="np-btn close" onclick="closePopup()">ปิด</button>
  </div>
</div>

<!-- TOUR -->
<div id="tour-ov">
  <div id="tour-box">
    <div class="tour-num" id="tour-num"></div>
    <h2 id="tour-title"></h2>
    <div class="tbody" id="tour-body"></div>
    <div>
      <button class="tbtn" onclick="tourNext()">ถัดไป →</button>
      <button class="tbtn skip" onclick="tourClose()">ปิด</button>
    </div>
  </div>
</div>

<div class="hdr">
  <h1>⚡ Lightning Network Simulator</h1>
  <p class="sub">จำลอง Payment Channel + Routing Nodes · Drag, Click, Explore</p>
  <a href="/index.php" class="badge" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.14);color:#888;text-decoration:none;margin-right:4px">🏠 Home</a>
  <span class="badge edu">⚠ Simulation เพื่อการศึกษา</span>
  <span class="badge tour" onclick="tourStart()">📖 บทนำ</span>
</div>

<div class="prog-wrap">
  <span class="prog-lbl">LEARNING PROGRESS</span>
  <div class="prog-track"><div class="prog-fill" id="prog-fill"></div></div>
  <span class="prog-step" id="prog-step">รอเปิด Channel</span>
</div>

<div class="main-grid">

<!-- LEFT COLUMN -->
<div style="display:flex;flex-direction:column;gap:12px">

  <!-- STEP 1 -->
  <div class="panel">
    <div class="ptitle">
      <span>⚙</span> STEP 1 · CHANNEL SETUP
      <div style="margin-left:auto"><span class="sbadge setup" id="ch-status"><span class="sdot"></span>NO CHANNEL</span></div>
    </div>
    <div class="hint">💡 ล็อก Bitcoin ใน <strong>2-of-2 Multisig</strong> ครั้งเดียว ทำ TX ได้ไม่จำกัด</div>
    <div class="preset-row">
      <span style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--dim);align-self:center">Preset:</span>
      <button class="pbtn" onclick="preset(200000,200000)">200k/200k</button>
      <button class="pbtn" onclick="preset(500000,500000)">500k/500k</button>
      <button class="pbtn" onclick="preset(1000000,0)">1M/0</button>
      <button class="pbtn" onclick="preset(1500000,500000)">1.5M/500k</button>
    </div>
    <div class="cg">
      <label>Alice — Funding (sats)</label>
      <input type="number" id="alice-fund" value="500000" min="1000" max="21000000" step="1000" oninput="updFund()">
      <div class="sub-val" id="alice-usd">≈ $0.00</div>
    </div>
    <div class="cg">
      <label>Bob — Funding (sats)</label>
      <input type="number" id="bob-fund" value="500000" min="0" max="21000000" step="1000" oninput="updFund()">
      <div class="sub-val" id="bob-usd">≈ $0.00</div>
    </div>
    <div class="cg">
      <label>On-chain Fee Rate (sat/vByte)</label>
      <input type="range" id="fee-rate" min="1" max="200" value="20" oninput="updFeeRate()">
      <div class="vdisp"><span id="fee-rate-val">20</span> sat/vByte · Funding ≈ <span id="fund-fee-est">5,000</span> sats</div>
    </div>
    <div class="btn-row">
      <button class="btn grn" id="btn-open" onclick="chOpen()">⚡ OPEN CHANNEL</button>
      <button class="btn red" id="btn-close" onclick="chClose()" disabled>✕ CLOSE</button>
    </div>
  </div>

  <!-- STEP 2: ROUTING NODES -->
  <div class="panel">
    <div class="ptitle"><span>🔀</span> STEP 2 · ROUTING NODES</div>
    <div class="hint">
      💡 <strong>คลิก</strong> Node ดู/แก้ · <strong>ลาก</strong> ย้ายตำแหน่ง · Fee สุ่ม 2–20 sat<br>
      ลอง Toggle Node ที่ถูกที่สุด <span style="color:var(--green)">★</span> เป็น Offline — ระบบจะเลือก Route ที่แพงกว่าแทน
    </div>
    <div class="btn-row" style="margin-top:0;margin-bottom:8px">
      <button class="btn pur" onclick="addNode()">+ ADD NODE</button>
      <button class="btn" onclick="resetNodes()" style="border-color:rgba(255,255,255,.15);color:var(--dim)">RESET</button>
      <button class="btn blu" onclick="rerandomizeEdges()" id="btn-rewire" disabled>↺ REWIRE</button>
    </div>
    <!-- Mini node list -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
      <span style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--dim)">NODE · FEE · PPM · LIQ · STATUS</span>
      <span style="font-family:var(--mono);font-size:.54rem">
        <span style="color:var(--green)">★ ถูกสุด</span>
        &nbsp;
        <span style="color:var(--red)">● แพงสุด</span>
      </span>
    </div>
    <div id="mini-node-list" style="display:flex;flex-direction:column;gap:4px"></div>
  </div>


</div><!-- /LEFT -->

<!-- RIGHT COLUMN -->
<div style="display:flex;flex-direction:column;gap:12px">

  <!-- STEP 3: SEND (moved to middle column for direct visual feedback) -->
  <div class="panel" style="padding:12px 16px">
    <div class="step3-bar">

      <!-- Title -->
      <div class="ptitle" style="margin-bottom:0;border-bottom:none;padding-bottom:0;margin-right:16px;white-space:nowrap">
        <span>→</span> STEP 3 · SEND
      </div>

      <!-- Amount slider -->
      <div style="flex:1;min-width:160px;margin-right:12px">
        <div style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--muted);margin-bottom:2px">จำนวน (sats)</div>
        <input type="range" id="send-amt" min="100" max="500000" value="10000" step="100" oninput="updSend()" style="width:100%;accent-color:var(--gold)">
        <div style="text-align:right;font-family:var(--mono);font-size:.7rem;color:var(--gold)">
          <span id="send-amt-val">10,000</span> sats <span style="color:var(--dim);font-size:.62rem" id="send-usd"></span>
        </div>
      </div>

      <!-- Direction -->
      <div style="display:flex;flex-direction:column;gap:3px;margin-right:10px">
        <div style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--muted)">ทิศทาง</div>
        <div style="display:flex;gap:5px">
          <button class="btn" id="dir-ab" onclick="setDir('ab')" style="border-color:var(--gold);color:var(--gold);background:rgba(255,215,0,.08);padding:5px 10px;font-size:.58rem;white-space:nowrap">Alice → Bob</button>
          <button class="btn" id="dir-ba" onclick="setDir('ba')" style="border-color:rgba(255,255,255,.12);color:var(--dim);padding:5px 10px;font-size:.58rem;white-space:nowrap">Bob → Alice</button>
        </div>
      </div>

      <!-- Route path + fee -->
      <div style="display:flex;flex-direction:column;gap:3px;margin-right:10px;min-width:120px">
        <div style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--muted)">Route Path</div>
        <div id="route-path" style="min-height:28px;display:flex;align-items:center">
          <div style="font-family:'Chakra Petch',sans-serif;font-size:.6rem;color:var(--dim)">กด FIND ROUTE</div>
        </div>
        <div style="font-family:var(--mono);font-size:.6rem;color:var(--orange)" id="routing-fee-preview">—</div>
      </div>

      <!-- Action buttons -->
      <div style="display:flex;gap:6px;align-items:center;flex-shrink:0">
        <button class="btn" id="btn-route" onclick="findRoute()" disabled style="padding:8px 12px;white-space:nowrap">🔍 FIND ROUTE</button>
        <button class="btn grn" id="btn-send" onclick="chSend(1)" disabled style="padding:8px 12px;white-space:nowrap">SEND ⚡</button>
        <button class="btn blu" id="btn-send5" onclick="chSend(5)" disabled style="padding:8px 14px">×5</button>
      </div>
    </div>
  </div>

  <div class="panel" style="position:relative">
    <div class="ptitle"><span>◈</span> NETWORK MAP &nbsp;<span style="font-size:.54rem;color:var(--dim);font-family:'Chakra Petch',sans-serif">คลิก Node เพื่อแก้ไข · ลากเพื่อย้ายตำแหน่ง</span></div>
    <canvas id="net-canvas" height="270"></canvas>

    <div class="wt-bar" style="margin-top:8px">
      <span class="wt-dot"></span>WATCHTOWER: <strong style="color:var(--purple)">MONITORING</strong>
      <span style="margin-left:auto;font-size:.54rem;color:#333">ป้องกัน Breach Remedy TX</span>
    </div>

    <div id="htlc-strip">
      <div class="htlc-title" style="display:flex;align-items:center;justify-content:space-between">
        <span>🔒 HTLC PROCESS</span>
        <span id="htlc-status" style="font-size:.52rem;color:var(--dim);font-family:var(--mono);letter-spacing:.5px">IDLE — รอการส่งเงิน</span>
      </div>
      <div id="htlc-steps">
        <div class="hs" id="hs1"><span class="hdot"></span>Bob สร้าง Secret R + Hash(R)</div>
        <div class="hs" id="hs2"><span class="hdot"></span>Alice ล็อก HTLC ที่ Node แรกในเส้นทาง</div>
        <div class="hs" id="hs3"><span class="hdot"></span>แต่ละ Node ส่ง HTLC ต่อไปข้างหน้า</div>
        <div class="hs" id="hs4"><span class="hdot"></span>Bob รับและเปิดเผย Secret R</div>
        <div class="hs" id="hs5"><span class="hdot"></span>R ไหลย้อนกลับ — ทุก Hop ปลดล็อก ⚡</div>
      </div>
    </div>

    <div class="ch-state">
      <div class="pbox" id="abox">
        <div class="pname">ALICE</div>
        <div class="pbal" id="a-bal">0</div>
        <div class="punit">sats</div>
        <div class="pusd" id="a-usd">$0.00</div>
      </div>
      <div class="ch-arrow">⚡</div>
      <div class="pbox" id="bbox">
        <div class="pname">BOB</div>
        <div class="pbal" id="b-bal">0</div>
        <div class="punit">sats</div>
        <div class="pusd" id="b-usd">$0.00</div>
      </div>
    </div>

    <div class="metrics">
      <div class="mbox"><div class="mlbl">CAPACITY</div><div class="mval gold" id="m-cap">—</div></div>
      <div class="mbox"><div class="mlbl">OFF-CHAIN TXs</div><div class="mval grn" id="m-tx">0</div></div>
      <div class="mbox"><div class="mlbl">FEES SAVED</div><div class="mval blu" id="m-saved">0</div></div>
      <div class="mbox"><div class="mlbl">OPEN FEE</div><div class="mval red" id="m-openfee">—</div></div>
      <div class="mbox"><div class="mlbl">NODES</div><div class="mval pur" id="m-nodes">0</div></div>
      <div class="mbox"><div class="mlbl">STATUS</div><div class="mval" id="m-status" style="color:var(--red)">OFFLINE</div></div>
    </div>
  </div>

  <div class="panel">
    <div class="ptitle">
      <span>📋</span> TRANSACTION LOG
      <button class="btn" onclick="clearLog()" style="margin-left:auto;flex:none;padding:2px 8px;font-size:.5rem">CLEAR</button>
    </div>
    <div id="tx-log">
      <div class="le info"><span class="lt">--:--:--</span><span class="li">ℹ</span><span class="lm">Simulator พร้อม — เปิด Channel แล้วเพิ่ม Routing Nodes</span></div>
    </div>
  </div>


</div><!-- /RIGHT -->

<!-- LEARNING CENTER -->
<div class="panel concepts-panel">
  <div class="ptitle" style="margin-bottom:8px"><span>📚</span> LEARNING CENTER</div>
  <div style="font-family:'Chakra Petch',sans-serif;font-size:.58rem;color:var(--dim);margin-bottom:8px">กล่องดำ = Live data จาก Simulator</div>
  <div class="ctabs">
    <button class="ctab active" onclick="showC('what',this)">⚡ What is LN?</button>
    <button class="ctab" onclick="showC('routing',this)">🔀 Routing</button>
    <button class="ctab" onclick="showC('htlc',this)">🔒 HTLC</button>
    <button class="ctab" onclick="showC('liquidity',this)">💧 Liquidity</button>
    <button class="ctab" onclick="showC('pathfind',this)">🗺 Pathfinding</button>
    <button class="ctab" onclick="showC('fail',this)">❌ Route Failure</button>
    <button class="ctab" onclick="showC('fees',this)">💰 Fee Structure</button>
    <button class="ctab" onclick="showC('tradeoffs',this)">⚖ Trade-offs</button>
  </div>

  <div class="ccontent active" id="c-what"><div class="cbody">
    <div>
      <p><strong>Lightning Network</strong> คือ Layer 2 บน Bitcoin ที่ให้ส่งเงินได้ทันที ค่าธรรมเนียมเกือบ 0</p>
      <p>Alice ไม่จำเป็นต้องมี Channel ตรงกับทุกคน — Bitcoin วิ่งผ่าน Routing Nodes ที่เชื่อมกันในเครือข่ายได้</p>
      <div class="callout"><p>⚡ ลองเพิ่ม Node แล้วกด REWIRE เพื่อสร้าง Topology สุ่ม จากนั้น Find Route ดูเส้นทางที่ระบบเลือก</p></div>
    </div>
    <div><div class="live-block" id="live-what">กด OPEN CHANNEL เพื่อดูข้อมูล Live</div></div>
  </div></div>

  <div class="ccontent" id="c-routing"><div class="cbody">
    <div>
      <p>Bitcoin ไม่ต้องวิ่งตรงจาก Alice → Bob เสมอไป — อาจวิ่งผ่าน Node หลายตัวในเครือข่าย</p>
      <p>เส้นทางถูกเลือกจาก: <strong>① ค่าธรรมเนียมรวมต่ำสุด</strong> + <strong>② ทุก Hop มี Liquidity เพียงพอ</strong> + <strong>③ Node Online</strong></p>
      <p>ใน Simulator เส้นเชื่อมระหว่าง Node เป็นแบบสุ่ม กด <em>REWIRE</em> เพื่อสร้าง Topology ใหม่ทุกครั้ง</p>
    </div>
    <div><div class="live-block" id="live-routing">กด OPEN CHANNEL + เพิ่ม Node เพื่อดู Live</div></div>
  </div></div>

  <div class="ccontent" id="c-htlc"><div class="cbody">
    <div>
      <p><strong>HTLC</strong> ทำให้ Multi-hop Payment เป็น Atomic — สำเร็จหรือล้มเหลวทั้งหมด</p>
      <p>① Bob สร้าง Secret R + Hash(R) → ② Alice ล็อกทุก Hop → ③ Bob เปิดเผย R → ④ R ไหลย้อน ทุก Hop ปลดล็อก</p>
      <p>ถ้า Routing ล้มเหลวที่ Hop ใด — HTLC expire → เงินคืนผู้ส่งอัตโนมัติ</p>
    </div>
    <div><div class="live-block" id="live-htlc">กด SEND (routed) เพื่อดู HTLC Live</div></div>
  </div></div>

  <div class="ccontent" id="c-liquidity"><div class="cbody">
    <div>
      <p><strong>Outbound Liquidity</strong> = เงินฝั่งเรา = ส่งออกได้ · <strong>Inbound</strong> = เงินฝั่งคู่ = รับได้</p>
      <p>ถ้า Node กลางมี Liquidity น้อยกว่า Amount → Route นั้นใช้ไม่ได้ ต้องหา Route อื่น</p>
      <div class="callout"><p>💡 ลอง Toggle Node ที่มี Liquidity สูงเป็น Offline ดูว่า Route เปลี่ยนหรือ Fail</p></div>
    </div>
    <div><div class="live-block" id="live-liquidity">กด OPEN CHANNEL + เพิ่ม Node เพื่อดู Live</div></div>
  </div></div>

  <div class="ccontent" id="c-pathfind"><div class="cbody">
    <div>
      <p>Simulator ใช้ <strong>BFS/Dijkstra</strong> หาเส้นทางสั้นที่สุด (fee ต่ำสุด) บน Graph ที่สุ่มสร้างขึ้น</p>
      <p>พิจารณา: ① fee รวม ② Liquidity ทุก Hop ③ Node Online เท่านั้น</p>
      <p>กด <em>REWIRE</em> เพื่อ Random Topology ใหม่ แล้วกด Find Route ดูว่าเส้นทางเปลี่ยนยังไง</p>
    </div>
    <div><div class="live-block" id="live-pathfind">กด FIND ROUTE เพื่อดู Pathfinding Live</div></div>
  </div></div>

  <div class="ccontent" id="c-fail"><div class="cbody">
    <div>
      <p>Routing ล้มเหลวได้จาก: ① Liquidity ไม่พอ ② Node Offline ③ ไม่มีเส้นทางเชื่อมถึง Bob ④ Congested (Fee ×2)</p>
      <p>เมื่อ HTLC expire → เงินคืนผู้ส่งอัตโนมัติ ไม่มีการสูญเสีย</p>
      <div class="callout"><p>🧪 ลอง Toggle ทุก Node เป็น Offline แล้ว Find Route — จะเห็น "No Route" และใช้ Direct ถ้าทำได้</p></div>
    </div>
    <div><div class="live-block" id="live-fail">Toggle Node เป็น Offline แล้ว FIND ROUTE เพื่อดู Live</div></div>
  </div></div>

  <div class="ccontent" id="c-fees"><div class="cbody">
    <div>
      <p><strong>Base Fee</strong>: คงที่ต่อ Payment · <strong>Fee Rate (ppm)</strong>: สัดส่วนตาม Amount</p>
      <p>Formula: <em>hop_fee = base_fee + ⌈amount × fee_rate / 1,000,000⌉</em></p>
      <p>Alice ต้องส่ง amount + routing_fees ทั้งหมด เพื่อให้ Bob ได้รับ amount ครบ</p>
    </div>
    <div><div class="live-block" id="live-fees">กด FIND ROUTE เพื่อดู Fee Live</div></div>
  </div></div>

  <div class="ccontent" id="c-tradeoffs"><div class="cbody">
    <div>
      <p>✅ <strong>ข้อดี</strong>: เร็ว (&lt;1 วินาที), ถูกมาก, Micropayments, Privacy (Onion Routing)</p>
      <p>⚠ <strong>ข้อจำกัด</strong>: ต้องล็อก Liquidity, Routing อาจล้มเหลว, Node ต้อง Online</p>
      <div class="callout">
        <p>🎯 <strong>Lightning เหมาะ</strong>: ชำระประจำวัน, Streaming, IoT, ข้ามพรมแดนขนาดเล็ก</p>
        <p>🎯 <strong>On-chain เหมาะ</strong>: ธุรกรรมขนาดใหญ่, DeFi, Cold storage</p>
      </div>
    </div>
    <div><div class="live-block" id="live-tradeoffs">กด OPEN CHANNEL เพื่อดูข้อมูล Live</div></div>
  </div></div>

</div><!-- /concepts -->
</div><!-- /main-grid -->

<script>
/* ══════════════════════════════════════════════════════════
   STATE
══════════════════════════════════════════════════════════ */
const S = {
    open:false, alice:0, bob:0, capacity:0,
    txCount:0, totalSent:0, feesSaved:0, openFee:0,
    dir:'ab', commitNum:0, btcUsd:95000,
    currentRoute:null, routeValid:false,
};
setInterval(()=>{
    S.btcUsd+=(Math.random()-.497)*180;
    S.btcUsd=Math.max(80000,Math.min(115000,S.btcUsd));
    if(S.open){updBalUSD();updLive();}
},5000);
function susd(sats){ return '$'+(sats/1e8*S.btcUsd).toFixed(2); }
function el(id){ return document.getElementById(id); }
function fmt(n){ return Math.round(Math.abs(n||0)).toLocaleString('en-US'); }

/* ══════════════════════════════════════════════════════════
   NODE DATA
══════════════════════════════════════════════════════════ */
const NODE_NAMES  = ['Zap','Volt','Spark','Flash','Bolt','Storm','Arc','Pulse'];
const NODE_COLORS = ['#b060ff','#00f3ff','#ff9d00','#ff60c0','#00ffcc','#ffd700','#0aff00','#ff2a2a'];

let nodes = [];     /* { id,name,color,baseFee,feeRate,liquidity,status,x,y } */
let edges = [];     /* { a:'alice'|'bob'|nodeId, b:'alice'|'bob'|nodeId } */
let nodeIdSeq = 1;   /* start at 1: id=0 is falsy in JS, breaks drag find() */

/* ── Fixed node "virtual" objects for Alice/Bob ── */
const nc = document.getElementById('net-canvas');
const nctx = nc.getContext('2d');

function alicePos(){ return { x: nc.width*0.10, y: nc.height*0.50 }; }
function bobPos(){   return { x: nc.width*0.90, y: nc.height*0.50 }; }

function getNodeById(id){
    if(id==='alice') return {...alicePos(), id:'alice', name:'ALICE', color:'#ffd700', liquidity:S.alice, status:'online'};
    if(id==='bob')   return {...bobPos(),   id:'bob',   name:'BOB',   color:'#00f3ff', liquidity:S.bob,   status:'online'};
    return nodes.find(n=>n.id===id);
}
function getPos(id){
    if(id==='alice') return alicePos();
    if(id==='bob')   return bobPos();
    const n=nodes.find(n=>n.id===id); return n?{x:n.x,y:n.y}:null;
}

/* ══════════════════════════════════════════════════════════
   TOPOLOGY — RANDOM EDGES
══════════════════════════════════════════════════════════ */
function buildEdges(){
    /* Rules for realistic Lightning topology:
       - Alice always has at least one edge
       - Bob always has at least one edge
       - Each node has 2-3 random edges to others (including alice/bob)
       - Alice–Bob direct channel always exists (main channel)
    */
    edges = [];
    const all = ['alice', ...nodes.map(n=>n.id), 'bob'];
    const edgeSet = new Set();

    const addEdge = (a,b) => {
        if(a===b) return;
        const k = [a,b].sort().join('|');
        if(edgeSet.has(k)) return;
        edgeSet.add(k); edges.push({a,b});
    };

    /* Alice–Bob direct always */
    addEdge('alice','bob');

    if(nodes.length===0) return;

    /* Ensure Alice and Bob each connect to at least one routing node */
    const nids = nodes.map(n=>n.id);
    const shuffled = [...nids].sort(()=>Math.random()-.5);
    addEdge('alice', shuffled[0]);
    addEdge('bob',   shuffled[shuffled.length>1?shuffled.length-1:0]);

    /* Each routing node: connect to 1-2 random others */
    nodes.forEach(n=>{
        const others = all.filter(id=>id!==n.id);
        const numLinks = 1 + Math.floor(Math.random()*2); /* 1 or 2 extra */
        const picked = others.sort(()=>Math.random()-.5).slice(0,numLinks);
        picked.forEach(id=>addEdge(n.id,id));
    });

    /* Ensure every routing node is reachable from alice (BFS check, add edge if isolated) */
    nodes.forEach(n=>{
        const connected = edges.some(e=>e.a===n.id||e.b===n.id);
        if(!connected) addEdge('alice',n.id);
    });
}

function rerandomizeEdges(){
    buildEdges();
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null);
    log('info','↺','Rewired network topology — เส้นทางใหม่สุ่มแล้ว');
    updLive();
}

/* ══════════════════════════════════════════════════════════
   CANVAS — DRAG & CLICK
══════════════════════════════════════════════════════════ */
let drag = null;           /* { nodeId, ox, oy } */
let popupNodeId = null;
let particles = [];        /* payment animation particles */

function resizeNC(){
    nc.width=nc.offsetWidth;
    /* On mobile, set canvas height proportional to width */
    if(window.innerWidth<=760){
        nc.height = Math.max(220, Math.round(nc.offsetWidth * 0.62));
    } else {
        nc.height = 270;
    }
}
window.addEventListener('resize',()=>{ resizeNC(); });
resizeNC();

function hitTest(mx,my){
    /* Returns nodeId (number) or 'alice'|'bob' string if click is on that node, else null */
    const R=14;
    const ap=alicePos(), bp=bobPos();
    if(dist(mx,my,ap.x,ap.y)<R+4) return 'alice';
    if(dist(mx,my,bp.x,bp.y)<R+4) return 'bob';
    for(let i=nodes.length-1;i>=0;i--){
        const n=nodes[i];
        if(dist(mx,my,n.x,n.y)<R) return n.id;
    }
    return null;
}
function dist(x1,y1,x2,y2){ return Math.sqrt((x2-x1)**2+(y2-y1)**2); }
function canvasXY(e){
    const r=nc.getBoundingClientRect();
    const src=e.touches?e.touches[0]:e;
    return { x:src.clientX-r.left, y:src.clientY-r.top };
}

nc.addEventListener('mousedown',e=>{ onDown(canvasXY(e)); });
nc.addEventListener('touchstart',e=>{ e.preventDefault(); onDown(canvasXY(e)); },{passive:false});
nc.addEventListener('mousemove',e=>{ onMove(canvasXY(e)); });
nc.addEventListener('touchmove',e=>{ e.preventDefault(); onMove(canvasXY(e)); },{passive:false});
nc.addEventListener('mouseup',  e=>{ onUp(canvasXY(e)); });
nc.addEventListener('touchend', e=>{ onUp({}); });

let downPos=null; let downTime=0;
function onDown(p){
    downPos=p; downTime=Date.now();
    const hit=hitTest(p.x,p.y);
    if(hit !== null && hit!=='alice' && hit!=='bob'){
        const n=nodes.find(x=>x.id===hit);
        if(n){ drag={id:hit, ox:p.x-n.x, oy:p.y-n.y}; nc.classList.add('dragging'); }
    }
    closePopup();
}
function onMove(p){
    if(!drag) return;
    const n=nodes.find(x=>x.id===drag.id); if(!n) return;
    const margin=20;
    n.x=Math.max(margin,Math.min(nc.width-margin,  p.x-drag.ox));
    n.y=Math.max(margin,Math.min(nc.height-margin, p.y-drag.oy));
}
function onUp(p){
    if(drag){
        nc.classList.remove('dragging');
        drag=null; return;
    }
    /* Click detection: short press, didn't move much */
    if(!downPos) return;
    const dt=Date.now()-downTime;
    if(p.x!==undefined && dist(p.x,p.y,downPos.x,downPos.y)<6 && dt<400){
        const hit=hitTest(downPos.x,downPos.y);
        if(hit !== null && hit!=='alice' && hit!=='bob') openPopup(hit, downPos);
    }
    downPos=null;
}

/* ══════════════════════════════════════════════════════════
   NODE POPUP
══════════════════════════════════════════════════════════ */
function openPopup(nodeId, pos){
    const n=nodes.find(x=>x.id===nodeId); if(!n) return;
    popupNodeId=nodeId;
    el('np-title').textContent=n.name;
    el('np-title').style.color=n.color;
    el('np-status').innerHTML=`<span class="np-status-dot" style="background:${n.status==='offline'?'#ff2a2a':n.status==='congested'?'#ff9d00':n.color}"></span>${n.status.toUpperCase()}`;
    el('np-liq').textContent=fmt(n.liquidity)+' sats';
    el('np-base').value=n.baseFee;
    el('np-ppm').value=n.feeRate;
    const myEdges=edges.filter(e=>e.a===nodeId||e.b===nodeId)
                       .map(e=>e.a===nodeId?e.b:e.a)
                       .map(id=>id==='alice'?'ALICE':id==='bob'?'BOB':(nodes.find(x=>x.id===id)||{name:'?'}).name);
    el('np-edges').textContent=myEdges.join(', ')||'—';

    /* Position popup near click, avoid overflow */
    const popup=el('node-popup');
    popup.classList.add('show');
    const parentRect=nc.parentElement.getBoundingClientRect();
    const ncRect=nc.getBoundingClientRect();
    let px=ncRect.left-parentRect.left + pos.x + 16;
    let py=ncRect.top -parentRect.top  + pos.y - 20;
    popup.style.left=px+'px'; popup.style.top=py+'px';
    /* clamp so it doesn't go off right */
    const pw=popup.offsetWidth||210;
    const parentW=nc.parentElement.offsetWidth;
    if(px+pw>parentW-10) { popup.style.left=(px-pw-32)+'px'; }
}
function closePopup(){ el('node-popup').classList.remove('show'); popupNodeId=null; }
function popupSetFee(type,val){
    if(!popupNodeId) return;
    const n=nodes.find(x=>x.id===popupNodeId); if(!n) return;
    if(type==='base') n.baseFee=Math.max(0,parseInt(val)||0);
    else n.feeRate=Math.max(0,parseInt(val)||0);
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null);
}
function popupCycleStatus(){
    if(!popupNodeId) return;
    const n=nodes.find(x=>x.id===popupNodeId); if(!n) return;
    const cycle={online:'offline',offline:'congested',congested:'online'};
    n.status=cycle[n.status];
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null);
    renderMiniNodeList();
    log('info','🔌',`${n.name}: ${n.status.toUpperCase()}${n.status==='congested'?' — Fee ×2':''}`);
    openPopup(popupNodeId,{x:n.x,y:n.y}); /* refresh popup */
    updLive();
}
function popupDelete(){
    if(!popupNodeId) return;
    removeNode(popupNodeId);
    closePopup();
}

/* ══════════════════════════════════════════════════════════
   DRAW
══════════════════════════════════════════════════════════ */
function drawNet(){
    const W=nc.width, H=nc.height;
    nctx.clearRect(0,0,W,H);
    const ap=alicePos(), bp=bobPos();

    /* ── EDGES ── */
    edges.forEach(e=>{
        const pa=getPos(e.a), pb=getPos(e.b); if(!pa||!pb) return;
        const inRoute = S.currentRoute && isEdgeInRoute(e, S.currentRoute);
        const isDirectMain = (e.a==='alice'&&e.b==='bob')||(e.a==='bob'&&e.b==='alice');
        const isDirect = isDirectMain && S.currentRoute && S.currentRoute.length===0;

        let col, lw;
        if(isDirect)   { col='#ffd700'; lw=2.5; }
        else if(inRoute){ const nb=getRouteEdgeColor(e,S.currentRoute); col=nb; lw=2.5; }
        else if(isDirectMain){ col='rgba(255,215,0,0.18)'; lw=1; }
        else {
            const na=getNodeById(e.a), nb=getNodeById(e.b);
            const offline=(na&&na.status==='offline')||(nb&&nb.status==='offline');
            col=offline?'rgba(80,80,80,.2)':'rgba(180,180,180,.15)'; lw=1;
        }
        drawEdge(pa,pb,col,lw,inRoute||isDirect);

        /* fee label on edge midpoint (only for in-route) */
        if(inRoute){
            const mx=(pa.x+pb.x)/2, my=(pa.y+pb.y)/2;
            const na=getNodeById(e.a), nb=getNodeById(e.b);
            const routeNode=[na,nb].find(n=>n&&n.id!=='alice'&&n.id!=='bob'&&nodes.some(x=>x.id===n.id));
            if(routeNode){
                const v=parseInt(el('send-amt').value)||0;
                const f=hopFee(routeNode,v);
                nctx.save();
                nctx.fillStyle='rgba(0,0,0,.7)'; nctx.font='bold 9px JetBrains Mono,monospace'; nctx.textAlign='center';
                nctx.strokeStyle='rgba(0,0,0,.8)'; nctx.lineWidth=3; nctx.strokeText('+'+f+' s',mx,my-4);
                nctx.fillStyle=routeNode.color; nctx.fillText('+'+f+' s',mx,my-4);
                nctx.restore();
            }
        }
    });

    /* ── PARTICLES ── */
    drawParticles();

    /* ── ALICE & BOB ── */
    drawMainNode(ap,'ALICE','#ffd700',S.open?fmt(S.alice)+' sats':'—');
    drawMainNode(bp,'BOB',  '#00f3ff',S.open?fmt(S.bob)+' sats':'—');

    /* ── ROUTING NODES ── */
    nodes.forEach(n=>drawRoutingNode(n));

    /* ── MINI CHANNEL BAR ── */
    if(S.open){
        const bX=W*0.22,bY=H*0.87,bW=W*0.56,bH=10;
        const ratio=Math.max(0,Math.min(1,S.alice/S.capacity));
        nctx.fillStyle='rgba(255,255,255,.04)';
        nctx.beginPath(); nctx.roundRect(bX,bY,bW,bH,4); nctx.fill();
        if(ratio>0.002){
            const g=nctx.createLinearGradient(bX,0,bX+bW*ratio,0);
            g.addColorStop(0,'rgba(255,215,0,.9)'); g.addColorStop(1,'rgba(255,157,0,.6)');
            nctx.fillStyle=g; nctx.beginPath(); nctx.roundRect(bX,bY,bW*ratio,bH,4); nctx.fill();
        }
        if(ratio<0.998){
            const g=nctx.createLinearGradient(bX+bW*ratio,0,bX+bW,0);
            g.addColorStop(0,'rgba(0,180,255,.5)'); g.addColorStop(1,'rgba(10,255,0,.6)');
            nctx.fillStyle=g; nctx.beginPath(); nctx.roundRect(bX+bW*ratio,bY,bW*(1-ratio),bH,[0,4,4,0]); nctx.fill();
        }
        nctx.fillStyle='#333'; nctx.font='8px Chakra Petch,sans-serif'; nctx.textAlign='center';
        nctx.fillText('Alice–Bob  '+fmt(S.capacity)+' sats  CommTX #'+S.commitNum, W/2, bY+bH+11);
    }
}

function isEdgeInRoute(e, route){
    /* Route = array of node objects from alice→...→bob */
    if(!route) return false;
    const path=['alice',...route.map(n=>n.id),'bob'];
    for(let i=0;i<path.length-1;i++){
        if((e.a===path[i]&&e.b===path[i+1])||(e.b===path[i]&&e.a===path[i+1])) return true;
    }
    return false;
}
function getRouteEdgeColor(e,route){
    /* Returns color of the routing node in this edge */
    const path=['alice',...route.map(n=>n.id),'bob'];
    for(let i=0;i<path.length-1;i++){
        if((e.a===path[i]&&e.b===path[i+1])||(e.b===path[i]&&e.a===path[i+1])){
            /* find routing node color */
            const ids=[path[i],path[i+1]];
            const n=ids.map(id=>nodes.find(x=>x.id===id)).find(Boolean);
            return n?n.color:'#ffd700';
        }
    }
    return '#ffd700';
}

function drawEdge(a,b,col,lw=1,glow=false){
    nctx.save();
    if(glow){nctx.shadowBlur=12;nctx.shadowColor=col;}
    nctx.strokeStyle=col; nctx.lineWidth=lw;
    nctx.beginPath(); nctx.moveTo(a.x,a.y); nctx.lineTo(b.x,b.y); nctx.stroke();
    nctx.restore();
}

function drawMainNode(pos,label,color,sub){
    nctx.save(); nctx.shadowBlur=22; nctx.shadowColor=color+'88';
    nctx.fillStyle=color; nctx.beginPath(); nctx.arc(pos.x,pos.y,14,0,Math.PI*2); nctx.fill();
    nctx.restore();
    nctx.fillStyle='#000'; nctx.font='bold 7px Orbitron,sans-serif'; nctx.textAlign='center';
    nctx.fillText(label.slice(0,5),pos.x,pos.y+3);
    nctx.fillStyle=color; nctx.font='bold 9px Orbitron,sans-serif';
    nctx.fillText(label,pos.x,pos.y-19);
    nctx.fillStyle='#555'; nctx.font='7px JetBrains Mono,monospace';
    nctx.fillText(sub,pos.x,pos.y+26);
}

function drawRoutingNode(n){
    const isOff=n.status==='offline', isCon=n.status==='congested';
    const inRoute=S.currentRoute&&S.currentRoute.some(r=>r.id===n.id);
    const r=inRoute?12:9;
    const col=isOff?'#2a2a2a':n.color;

    nctx.save();
    if(inRoute){nctx.shadowBlur=20;nctx.shadowColor=n.color;}
    nctx.fillStyle=col; nctx.beginPath(); nctx.arc(n.x,n.y,r,0,Math.PI*2); nctx.fill();
    nctx.restore();

    /* Pulse ring for online/in-route */
    nctx.save();
    nctx.strokeStyle=isOff?'rgba(255,42,42,.25)':isCon?'rgba(255,157,0,.4)':n.color+(inRoute?'99':'33');
    nctx.lineWidth=isOff?.8:1.5;
    nctx.beginPath(); nctx.arc(n.x,n.y,r+4,0,Math.PI*2); nctx.stroke();
    nctx.restore();

    /* Name */
    nctx.fillStyle=isOff?'#444':n.color; nctx.font='bold 8px Orbitron,sans-serif'; nctx.textAlign='center';
    nctx.fillText(n.name,n.x,n.y-r-7);

    /* Sub label */
    const sub=isOff?'OFFLINE':isCon?'BUSY':fmt(n.liquidity)+'s';
    nctx.fillStyle=isOff?'#ff2a2a':isCon?'#ff9d00':'#3a3a3a'; nctx.font='7px JetBrains Mono,monospace';
    nctx.fillText(sub,n.x,n.y+r+10);
}

/* ── Continuous render loop ── */
(function loop(){ drawNet(); requestAnimationFrame(loop); })();

/* ══════════════════════════════════════════════════════════
   PARTICLES — Bitcoin flying along route
══════════════════════════════════════════════════════════ */
function spawnRouteParticles(pathPositions, color){
    /* Create particles that travel along each edge segment */
    const totalSegs = pathPositions.length - 1;
    if(totalSegs<=0) return;

    /* Spawn a wave of particles staggered along the path */
    const count = 10 + totalSegs*4;
    for(let i=0;i<count;i++){
        const t0=i/count;   /* 0-1 along total path */
        particles.push({
            pathPos: pathPositions,
            t: t0,             /* position along path 0=start 1=end */
            life: 1,
            speed: 0.006 + Math.random()*0.004,
            size:  2.5 + Math.random()*2,
            color: color,
            trail: [],
        });
    }
}

function updateAndDrawParticles(){
    /* Called inside drawNet loop via drawParticles() */
}
function drawParticles(){
    for(let i=particles.length-1;i>=0;i--){
        const p=particles[i];
        p.t+=p.speed;
        if(p.t>=1){ particles.splice(i,1); continue; }
        p.life=Math.min(1,Math.sin(p.t*Math.PI)); /* fade in and out */

        /* Find position along path */
        const segs=p.pathPos.length-1;
        const tSeg=p.t*segs;
        const segIdx=Math.min(Math.floor(tSeg),segs-1);
        const segT=tSeg-segIdx;
        const from=p.pathPos[segIdx], to=p.pathPos[segIdx+1];
        const x=from.x+(to.x-from.x)*segT;
        const y=from.y+(to.y-from.y)*segT;

        /* Trail */
        p.trail.push({x,y});
        if(p.trail.length>8) p.trail.shift();

        /* Draw trail */
        nctx.save();
        nctx.globalAlpha=p.life*0.4;
        for(let j=0;j<p.trail.length-1;j++){
            const alpha=(j/p.trail.length);
            nctx.strokeStyle=p.color;
            nctx.lineWidth=p.size*alpha*0.6;
            nctx.beginPath(); nctx.moveTo(p.trail[j].x,p.trail[j].y);
            nctx.lineTo(p.trail[j+1].x,p.trail[j+1].y); nctx.stroke();
        }
        nctx.restore();

        /* Draw particle */
        nctx.save();
        nctx.globalAlpha=p.life;
        nctx.shadowBlur=10; nctx.shadowColor=p.color;
        nctx.fillStyle=p.color;
        nctx.beginPath(); nctx.arc(x,y,p.size,0,Math.PI*2); nctx.fill();
        nctx.restore();
    }
}

/* ══════════════════════════════════════════════════════════
   NODE MANAGEMENT
══════════════════════════════════════════════════════════ */
function addNode(){
    if(nodes.length>=8){log('warn','⚠','สูงสุด 8 Routing Nodes'); return;}
    const id=nodeIdSeq++;
    const idx=id%NODE_NAMES.length;
    const liq=100000+Math.floor(Math.random()*400000);
    const W=nc.width, H=nc.height;
    const angle=Math.random()*Math.PI*2;
    const r=Math.min(W,H)*0.28;
    /* Random base fee 2–20 sats — สาธิตว่า Node แต่ละตัวเก็บค่าต่างกัน */
    const baseFee = 2 + Math.floor(Math.random()*19);   /* 2–20 sats */
    const feeRate = Math.floor(Math.random()*3);         /* 0–2 ppm   */
    const n={
        id, name:NODE_NAMES[idx], color:NODE_COLORS[idx],
        baseFee, feeRate, liquidity:liq, status:'online',
        x: W/2+r*Math.cos(angle), y: H/2+r*Math.sin(angle)*0.7,
    };
    nodes.push(n);
    buildEdges();
    renderMiniNodeList();
    el('btn-rewire').disabled=false;
    el('m-nodes').textContent=nodes.length;
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null);
    log('sys','🔌','เพิ่ม '+n.name+' · Liquidity '+fmt(liq)+' sats · Fee <span style="color:var(--orange)">'+baseFee+' sat base + '+feeRate+' ppm</span>');
    if(!S.open) log('info','ℹ','เปิด Channel ก่อนแล้วค่อย Find Route');
    updLive();
}

function resetNodes(){
    nodes=[]; nodeIdSeq=1; edges=[]; particles=[];
    buildEdges();
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=!S.open; el('btn-send5').disabled=!S.open;
    el('btn-route').disabled=!S.open; el('btn-rewire').disabled=true;
    el('m-nodes').textContent=0;
    renderMiniNodeList();
    updRoutePath(null);
    log('dim','↺','Reset Routing Nodes — Direct channel เท่านั้น');
    updLive();
}

function removeNode(id){
    nodes=nodes.filter(x=>x.id!==id);
    buildEdges();
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=!S.open; el('btn-send5').disabled=!S.open;
    el('m-nodes').textContent=nodes.length;
    renderMiniNodeList();
    updRoutePath(null);
    log('dim','✕','ลบ Routing Node');
    updLive();
}

function renderMiniNodeList(){
    const list=el('mini-node-list');
    if(!nodes.length){
        list.innerHTML='<div style="font-family:\'Chakra Petch\',sans-serif;font-size:.6rem;color:var(--dim)">ยังไม่มี Node — กด + ADD NODE · คลิก Node บนแผนผังเพื่อแก้ไข</div>';
        return;
    }
    /* Sort by baseFee asc so cheapest nodes appear first */
    const sorted=[...nodes].sort((a,b)=>a.baseFee-b.baseFee);
    const minFee=sorted[0]?.baseFee;
    const maxFee=sorted[sorted.length-1]?.baseFee;
    list.innerHTML=nodes.map(n=>{
        const isOffline=n.status==='offline';
        const isCong=n.status==='congested';
        const isCheapest=n.baseFee===minFee && !isOffline;
        const isExpensive=n.baseFee===maxFee && nodes.length>1;
        const feeColor=isOffline?'#333':isCheapest?'var(--green)':isExpensive?'var(--red)':'var(--orange)';
        const feeLabel=n.baseFee+' sat'+(isCheapest&&nodes.length>1?' ★':'');
        return `
        <div style="display:flex;align-items:center;gap:6px;padding:5px 8px;
            background:rgba(255,255,255,.02);border:1px solid ${isOffline?'rgba(255,42,42,.15)':isCheapest&&nodes.length>1?'rgba(10,255,0,.12)':'rgba(255,255,255,.05)'};
            border-radius:5px;font-family:var(--mono);font-size:.6rem;
            opacity:${isOffline?'.5':'1'}">
          <span style="width:8px;height:8px;border-radius:50%;background:${isOffline?'#333':n.color};flex-shrink:0"></span>
          <span style="color:${isOffline?'var(--dim)':n.color};font-family:'Orbitron',sans-serif;font-size:.54rem;width:44px;flex-shrink:0">${n.name}</span>
          <span style="color:${feeColor};font-weight:700;width:46px;flex-shrink:0">${feeLabel}</span>
          <span style="color:#444;font-size:.54rem;flex:1">${n.feeRate}ppm</span>
          <span style="color:var(--dim);font-size:.54rem">💧${fmt(Math.round(n.liquidity/1000))}k</span>
          <span style="color:${isOffline?'var(--red)':isCong?'var(--orange)':'var(--green)'};font-size:.52rem;width:30px;text-align:center">
            ${isOffline?'OFF':isCong?'BUSY':'ON'}
          </span>
          <button onclick="cycleNodeStatus(${n.id})" style="background:transparent;border:1px solid rgba(255,255,255,.1);
            color:var(--dim);font-size:.48rem;padding:1px 5px;border-radius:3px;cursor:pointer;font-family:var(--mono);white-space:nowrap">Toggle</button>
        </div>`;
    }).join('');
}

function cycleNodeStatus(id){
    const n=nodes.find(x=>x.id===id); if(!n) return;
    const cycle={online:'offline',offline:'congested',congested:'online'};
    n.status=cycle[n.status];
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null); renderMiniNodeList();
    log('info','🔌',`${n.name}: ${n.status.toUpperCase()}${n.status==='congested'?' — Fee ×2':''}`);
    if(popupNodeId===id) openPopup(id,{x:n.x,y:n.y});
    updLive();
}

/* ══════════════════════════════════════════════════════════
   PATHFINDING (BFS over edge graph)
══════════════════════════════════════════════════════════ */
function hopFee(n,amount){
    const rate=n.status==='congested'?n.feeRate*2:n.feeRate;
    return n.baseFee+Math.ceil(amount*rate/1000000);
}

function getNeighbors(nodeId){
    return edges
        .filter(e=>e.a===nodeId||e.b===nodeId)
        .map(e=>e.a===nodeId?e.b:e.a);
}

function bfsRoutes(srcId, dstId, amount, senderBal){
    /* BFS to find all simple paths from src to dst, filter valid, sort by fee */
    const queue = [[srcId]];   /* list of partial paths */
    const found = [];
    const MAX_HOPS = 4;        /* Alice→node→node→...→Bob, max 4 intermediate */

    while(queue.length){
        const path=queue.shift();
        const cur=path[path.length-1];
        if(path.length>MAX_HOPS+2) continue;  /* too long */

        const neighbors=getNeighbors(cur);
        for(const nb of neighbors){
            if(path.includes(nb)) continue;   /* no cycles */
            const newPath=[...path,nb];
            if(nb===dstId){
                found.push(newPath); continue;
            }
            queue.push(newPath);
        }
    }

    /* Evaluate each path */
    const candidates=[];
    found.forEach(path=>{
        /* path = ['alice', ...nodeIds..., 'bob'] */
        const routeNodes=path.slice(1,-1).map(id=>nodes.find(x=>x.id===id)).filter(Boolean);
        /* Check all routing nodes online & have liquidity */
        const valid=routeNodes.every(n=>n.status!=='offline' && n.liquidity>=amount);
        if(!valid) return;
        const totalFee=routeNodes.reduce((s,n)=>s+hopFee(n,amount),0);
        if(amount+totalFee>senderBal) return;
        candidates.push({path, routeNodes, totalFee, hops:routeNodes.length});
    });

    /* Sort all candidates by fee ascending — findRoute handles routed vs direct priority */
    candidates.sort((a,b)=>a.totalFee-b.totalFee || a.hops-b.hops);
    return candidates;
}

function findRoute(){
    if(!S.open){log('err','✗','เปิด Channel ก่อน'); return;}
    const amount=parseInt(el('send-amt').value)||0;
    const dir=S.dir;
    const srcId=dir==='ab'?'alice':'bob';
    const dstId=dir==='ab'?'bob':'alice';
    const senderBal=dir==='ab'?S.alice:S.bob;

    const allCandidates=bfsRoutes(srcId,dstId,amount,senderBal);

    /* ── Separate routed vs direct ── */
    const routedCandidates = allCandidates.filter(c=>c.hops>0);
    const directCandidates = allCandidates.filter(c=>c.hops===0);

    /* ── Log why offline/low-liq nodes are skipped ── */
    nodes.filter(n=>n.status!=='offline').forEach(n=>{
        if(n.liquidity<amount) log('dim','·',n.name+': Liquidity ไม่พอ ('+fmt(n.liquidity)+' < '+fmt(amount)+' sats)');
    });

    let chosen = null;
    let chosenReason = '';

    if(routedCandidates.length > 0){
        /* ── มี routed paths: สุ่มเลือกจาก candidates ที่ valid ──
           Weighted random: ยิ่ง fee ต่ำ ยิ่งมีน้ำหนักมากกว่า แต่ยังมีโอกาสเลือก node แพงกว่าด้วย
           เพื่อสาธิตว่า network ไม่ได้เลือก cheapest เสมอ */
        const maxFee = Math.max(...routedCandidates.map(c=>c.totalFee)) + 1;
        const weights = routedCandidates.map(c => maxFee - c.totalFee + 1); /* inverse-fee weight */
        const totalWeight = weights.reduce((s,w)=>s+w, 0);
        let rand = Math.random() * totalWeight;
        let idx = 0;
        for(let i=0; i<weights.length; i++){
            rand -= weights[i];
            if(rand <= 0){ idx=i; break; }
        }
        chosen = routedCandidates[idx];
        chosenReason = routedCandidates.length > 1
            ? `สุ่มจาก ${routedCandidates.length} เส้นทาง (weighted by fee)`
            : 'เส้นทางเดียวที่ใช้ได้';

    } else if(directCandidates.length > 0){
        /* ── Fallback: Direct channel เพราะ nodes ใช้ไม่ได้ทั้งหมด ── */
        chosen = directCandidates[0];
        const onlineCount = nodes.filter(n=>n.status!=='offline').length;
        if(nodes.length === 0){
            chosenReason = 'ไม่มี Routing Node — ใช้ Direct channel';
        } else if(onlineCount === 0){
            chosenReason = '⚠ ทุก Node Offline — บังคับใช้ Direct channel';
        } else {
            chosenReason = '⚠ Node ที่ Online มี Liquidity ไม่พอ — ใช้ Direct channel';
        }

    } else {
        /* ── ไม่มีทางไหนเลย ── */
        S.currentRoute=null; S.routeValid=false;
        el('btn-send').disabled=true; el('btn-send5').disabled=true;
        updRoutePath(null,'❌ ไม่พบ Route — Node Offline / Liquidity ไม่พอ / ยอดไม่พอ');
        log('err','✗','ROUTE NOT FOUND สำหรับ '+fmt(amount)+' sats — ลอง Toggle Node กลับ Online หรือลดจำนวน');
        updLive(); return;
    }

    S.currentRoute = chosen.routeNodes;
    S.routeValid   = true;
    el('btn-send').disabled=false; el('btn-send5').disabled=false;

    updRoutePath(chosen.routeNodes, null, dir, amount, chosen.totalFee);
    el('routing-fee-preview').textContent = chosen.totalFee===0 ? '0 sats (DIRECT)' : fmt(chosen.totalFee)+' sats';

    const routeStr = chosen.path
        .map(id=>id==='alice'?'Alice':id==='bob'?'Bob':(nodes.find(x=>x.id===id)||{name:id}).name)
        .join(' → ');
    const routeType = chosen.hops===0 ? 'Direct (no routing fee)' : chosen.hops+'-hop · fee '+fmt(chosen.totalFee)+' sats';

    log('ok','🗺','ROUTE: '+routeStr+' ['+routeType+']');
    log('dim','·', chosenReason);

    /* Show all alternatives for comparison */
    if(routedCandidates.length > 1){
        const sorted=[...routedCandidates].sort((a,b)=>a.totalFee-b.totalFee);
        const cheapest=sorted[0];
        const expensive=sorted[sorted.length-1];
        if(chosen.totalFee > cheapest.totalFee){
            const cheapPath=cheapest.path.map(id=>id==='alice'?'Alice':id==='bob'?'Bob':(nodes.find(x=>x.id===id)||{name:id}).name).join('→');
            log('dim','💡','เส้นถูกที่สุด: '+cheapPath+' ('+fmt(cheapest.totalFee)+' sats) · เส้นที่แพงที่สุด: '+fmt(expensive.totalFee)+' sats');
        }
    }

    setProgress(Math.min(45,25+nodes.length*4),'Route พบแล้ว — กด SEND');
    updLive();
}

function updRoutePath(route, failMsg, dir, amount, fee){
    const wrap=el('route-path');
    if(failMsg){ wrap.innerHTML=`<div class="route-fail">${failMsg}</div>`; return; }
    if(!route){ wrap.innerHTML='<div style="font-family:\'Chakra Petch\',sans-serif;font-size:.62rem;color:var(--dim);width:100%;text-align:center">กด FIND ROUTE เพื่อค้นหาเส้นทาง</div>'; return; }

    const sCol=dir==='ab'?'var(--gold)':'var(--blue)';
    const rCol=dir==='ab'?'var(--blue)':'var(--gold)';
    const sLbl=dir==='ab'?'ALICE':'BOB';
    const rLbl=dir==='ab'?'BOB':'ALICE';

    let html=`<div class="rn"><div class="rn-dot" style="color:${sCol}"></div><div class="rn-name" style="color:${sCol}">${sLbl}</div><div class="rn-fee">sender</div></div>`;
    route.forEach(n=>{
        const f=hopFee(n,amount||0);
        html+=`<div class="rn-arrow">─⚡→</div>
               <div class="rn"><div class="rn-dot" style="color:${n.color}"></div>
               <div class="rn-name" style="color:${n.color}">${n.name}</div>
               <div class="rn-fee">+${fmt(f)}s</div></div>`;
    });
    html+=`<div class="rn-arrow">─⚡→</div><div class="rn"><div class="rn-dot" style="color:${rCol}"></div><div class="rn-name" style="color:${rCol}">${rLbl}</div><div class="rn-fee">receiver</div></div>`;
    wrap.innerHTML=html;
}

/* ══════════════════════════════════════════════════════════
   CHANNEL OPERATIONS
══════════════════════════════════════════════════════════ */
function chOpen(){
    const alice=parseInt(el('alice-fund').value)||0;
    const bob=parseInt(el('bob-fund').value)||0;
    const fr=parseInt(el('fee-rate').value)||20;
    if(alice<1000){log('err','✗','Alice ต้องใส่ขั้นต่ำ 1,000 sats'); return;}
    if(alice+bob<2000){log('err','✗','Capacity รวม 2,000 sats ขั้นต่ำ'); return;}

    S.openFee=fr*250; S.capacity=alice+bob; S.alice=alice; S.bob=bob; S.open=true;
    S.txCount=0; S.totalSent=0; S.feesSaved=0; S.commitNum=0;
    S.currentRoute=null; S.routeValid=false;

    setStatus('open','CHANNEL OPEN'); updUI(); setProgress(20,'✓ Channel เปิด — กด FIND ROUTE');
    el('btn-open').disabled=true; el('btn-close').disabled=false;
    el('btn-route').disabled=false; el('btn-send').disabled=false; el('btn-send5').disabled=false;

    log('sys','⚡','[FUNDING TX] 2-of-2 Multisig UTXO บน Blockchain');
    log('ok','✓','Channel เปิดแล้ว · Capacity: '+fmt(S.capacity)+' sats ('+susd(S.capacity)+')');
    log('warn','⛓','Funding fee: '+fmt(S.openFee)+' sats @ '+fr+' sat/vByte');
    log('info','ℹ','Alice: '+fmt(alice)+' | Bob: '+fmt(bob)+' sats');
    log('dim','💡','เพิ่ม Routing Nodes แล้วกด FIND ROUTE หรือ SEND ตรงได้เลย');
    updLive();
}

function chSend(count){
    if(!S.open) return;
    for(let i=0;i<count;i++) setTimeout(()=>_doSend(), i*500);
}

function _doSend(){
    if(!S.open) return;
    const amount=parseInt(el('send-amt').value)||0;
    const dir=S.dir;
    const fr=parseInt(el('fee-rate').value)||20;
    const route=S.currentRoute||[];
    let routeFee=route.reduce((s,n)=>s+hopFee(n,amount),0);
    const senderBal=dir==='ab'?S.alice:S.bob;

    if(amount+routeFee>senderBal){
        log('err','✗','ยอดไม่พอ — กด FIND ROUTE อีกครั้ง'); return;
    }
    /* Revalidate nodes */
    const badNode=route.find(n=>n.status==='offline'||n.liquidity<amount);
    if(badNode){ log('err','✗',badNode.name+' ใช้ไม่ได้ — กด FIND ROUTE ใหม่'); return; }

    /* Apply payment — Alice/Bob balances */
    if(dir==='ab'){ S.alice-=(amount+routeFee); S.bob+=amount; }
    else { S.bob-=(amount+routeFee); S.alice+=amount; }

    /* Node liquidity: each node earns its routing fee.
       It receives (amount + all downstream fees) from the sender side,
       forwards (amount + fees for hops ahead) to next hop.
       Net: node's own fee stays with it → liquidity increases by ownFee. */
    route.forEach((n)=>{
        const ownFee = hopFee(n, amount);
        n.liquidity = Math.max(0, n.liquidity + ownFee);
    });

    S.txCount++; S.commitNum++; S.totalSent+=amount;
    S.feesSaved+=fr*250-routeFee;

    /* ── PARTICLE ANIMATION ── */
    const ap=alicePos(), bp=bobPos();
    const pathIds = dir==='ab'
        ? ['alice',...route.map(n=>n.id),'bob']
        : ['bob',...route.map(n=>n.id),'alice'];
    const pathPositions=pathIds.map(id=>{
        if(id==='alice') return ap;
        if(id==='bob')   return bp;
        const n=nodes.find(x=>x.id===id);
        return n?{x:n.x,y:n.y}:null;
    }).filter(Boolean);

    const particleColor = route.length>0 ? route[0].color : (dir==='ab'?'#ffd700':'#00f3ff');
    spawnRouteParticles(pathPositions, particleColor);

    /* HTLC animation if routed, or reset label if direct */
    if(route.length>0){ htlcAnim(()=>{}); }
    else {
        const ids=['hs1','hs2','hs3','hs4','hs5'];
        ids.forEach(id=>el(id).className='hs');
        const stEl=el('htlc-status');
        if(stEl){ stEl.style.color='var(--dim)'; stEl.textContent='DIRECT — ไม่ใช้ HTLC (0 hop)'; }
    }
    updUI(); flashBox(dir); renderMiniNodeList();

    const snd=dir==='ab'?'Alice':'Bob', rcv=dir==='ab'?'Bob':'Alice';
    const via=route.length===0?'Direct (FREE)':'Via '+route.map(n=>n.name).join('→')+' · Routing fee: '+fmt(routeFee)+' sats';
    log('ok','⚡',snd+' → '+rcv+': '+fmt(amount)+' sats · '+via+' · TX #'+S.txCount);
    if(route.length>0){
        log('dim','🔒','HTLC: preimage revealed · settled atomically');
        route.forEach(n=>{
            const earned=hopFee(n,amount);
            log('dim','💰',n.name+' earned +'+fmt(earned)+' sats fee · Liquidity: '+fmt(n.liquidity)+' sats');
        });
    }
    log('dim','↳','CommTX #'+S.commitNum+': Alice '+fmt(S.alice)+' | Bob '+fmt(S.bob));

    setProgress(Math.min(85,45+S.txCount*5),'TX #'+S.txCount+' สำเร็จ');
    if(S.alice<S.capacity*.08) log('warn','⚠','Alice Outbound ใกล้หมด');
    if(S.bob<S.capacity*.08)   log('warn','⚠','Bob Outbound ใกล้หมด');

    /* Invalidate route */
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null); el('routing-fee-preview').textContent='กด FIND ROUTE อีกครั้ง';
    updLive();
}

function htlcAnim(cb){
    const ids=['hs1','hs2','hs3','hs4','hs5'];
    ids.forEach(id=>el(id).className='hs');
    const stEl = el('htlc-status');
    if(stEl) stEl.style.color='var(--purple)';
    if(stEl) stEl.textContent='ACTIVE — กำลังประมวลผล...';
    let step=0;
    const iv=setInterval(()=>{
        if(step>0) el(ids[step-1]).className='hs done';
        if(step<ids.length){ el(ids[step]).className='hs active'; step++; }
        else{
            clearInterval(iv);
            ids.forEach(id=>el(id).className='hs done');
            if(stEl){ stEl.style.color='var(--green)'; stEl.textContent='✓ SETTLED — Payment สำเร็จ'; }
            cb();
        }
    },360);
}

function chClose(){
    if(!S.open) return;
    const fr=parseInt(el('fee-rate').value)||20;
    /* Closing TX is ~150 vBytes. The party who initiates Close pays the on-chain fee.
       In Lightning, the closer's output is reduced by the closing TX fee.
       We simulate Alice closing (she initiates). */
    const closeFee = fr * 150;   /* ~150 vByte closing TX */
    const closerName = 'Alice';  /* Alice closes in this simulation */

    /* Alice pays the closing fee — deducted from her balance */
    const aliceOnChain = Math.max(0, S.alice - closeFee);
    const bobOnChain   = S.bob;  /* Bob receives full balance, no deduction */

    log('warn','⚠','Alice กด Close Channel — Broadcasting CommTX #'+S.commitNum+'...');
    log('dim','⛓','Cooperative Closing TX · ~150 vByte · fee: '+fmt(closeFee)+' sats @ '+fr+' sat/vByte');
    log('dim','ℹ','ฝ่ายที่ Close Channel เป็นคนจ่าย Closing TX fee (Alice ในกรณีนี้)');

    setTimeout(()=>{
        log('ok','✓','Channel CLOSED — Settlement สมบูรณ์');
        log('info','→','Alice ← '+fmt(aliceOnChain)+' sats on-chain  ('+fmt(S.alice)+' - '+fmt(closeFee)+' closing fee)');
        log('info','→','Bob   ← '+fmt(bobOnChain)+' sats on-chain  (ไม่หัก เพราะ Bob ไม่ได้เป็นคน Close)');
        log('sys','📊','สรุป: '+S.txCount+' off-chain TX · Fees saved ≈ '+fmt(Math.max(0,S.feesSaved))+' sats');
        log('dim','✓','On-chain TX ทั้งหมด 2 ครั้ง: Funding + Closing = '+fmt(S.openFee+closeFee)+' sats รวม');

        /* Update state to reflect actual on-chain amounts */
        S.alice = aliceOnChain;
        S.bob   = bobOnChain;

        S.open=false; setStatus('setup','NO CHANNEL'); updUI(); setProgress(100,'✓ Simulation สมบูรณ์');
        el('btn-open').disabled=false; el('btn-close').disabled=true;
        el('btn-route').disabled=true; el('btn-send').disabled=true; el('btn-send5').disabled=true;
        S.currentRoute=null; updRoutePath(null); updLive();
    },900);
}

/* ══════════════════════════════════════════════════════════
   UI HELPERS
══════════════════════════════════════════════════════════ */
function updUI(){
    el('a-bal').textContent=fmt(S.alice); el('b-bal').textContent=fmt(S.bob);
    el('m-cap').textContent=S.capacity?fmt(S.capacity)+' sats':'—';
    el('m-tx').textContent=S.txCount;
    el('m-saved').textContent=fmt(Math.max(0,S.feesSaved))+' sats';
    el('m-openfee').textContent=S.openFee?fmt(S.openFee)+' sats':'—';
    el('m-nodes').textContent=nodes.length;
    el('m-status').textContent=S.open?'ONLINE':'OFFLINE';
    el('m-status').style.color=S.open?'var(--green)':'var(--red)';
    if(S.open){
        const have=S.dir==='ab'?S.alice:S.bob;
        const sl=el('send-amt'); sl.max=Math.max(1000,have);
        if(parseInt(sl.value)>have) sl.value=Math.floor(have/1000)*1000;
        updBalUSD();
    }
    updSend(); updFeeBar();
}
function updBalUSD(){ el('a-usd').textContent=susd(S.alice); el('b-usd').textContent=susd(S.bob); }
function updSend(){
    const v=parseInt(el('send-amt').value)||0;
    el('send-amt-val').textContent=fmt(v); el('send-usd').textContent=susd(v);
}
function updFund(){
    const a=parseInt(el('alice-fund').value)||0, b=parseInt(el('bob-fund').value)||0;
    el('alice-usd').textContent='≈ '+susd(a); el('bob-usd').textContent='≈ '+susd(b); updFeeRate();
}
function updFeeRate(){
    const fr=parseInt(el('fee-rate').value)||20;
    el('fee-rate-val').textContent=fr; el('fund-fee-est').textContent=fmt(fr*250);
    updFeeBar(); if(S.open) updUI();
}
function updFeeBar(lnFee){ /* fee comparison panel removed */ }
function setDir(d){
    S.dir=d;
    const ab=el('dir-ab'), ba=el('dir-ba');
    ab.style.borderColor=d==='ab'?'var(--gold)':'rgba(255,255,255,.12)';
    ab.style.color=d==='ab'?'var(--gold)':'var(--dim)';
    ab.style.background=d==='ab'?'rgba(255,215,0,.08)':'transparent';
    ba.style.borderColor=d==='ba'?'var(--blue)':'rgba(255,255,255,.12)';
    ba.style.color=d==='ba'?'var(--blue)':'var(--dim)';
    ba.style.background=d==='ba'?'rgba(0,243,255,.08)':'transparent';
    S.currentRoute=null; S.routeValid=false;
    el('btn-send').disabled=true; el('btn-send5').disabled=true;
    updRoutePath(null); if(S.open) updUI();
}
function setStatus(t,txt){ const e=el('ch-status'); e.className='sbadge '+t; e.innerHTML='<span class="sdot"></span>'+txt; }
function flashBox(dir){
    const se=el(dir==='ab'?'abox':'bbox'), re=el(dir==='ab'?'bbox':'abox');
    const sb=el(dir==='ab'?'a-bal':'b-bal'), rb=el(dir==='ab'?'b-bal':'a-bal');
    se.className='pbox sender'; re.className='pbox receiver';
    sb.className='pbal dn'; rb.className='pbal up';
    setTimeout(()=>{ se.className='pbox'; re.className='pbox'; sb.className='pbal'; rb.className='pbal'; },900);
}
function setProgress(p,l){ el('prog-fill').style.width=p+'%'; el('prog-step').textContent=l; }
function preset(a,b){ el('alice-fund').value=a; el('bob-fund').value=b; updFund(); }

/* ══════════════════════════════════════════════════════════
   LOG
══════════════════════════════════════════════════════════ */
function log(type,icon,msg){
    const lg=el('tx-log'), now=new Date().toTimeString().slice(0,8);
    const d=document.createElement('div'); d.className='le '+type;
    d.innerHTML='<span class="lt">'+now+'</span><span class="li">'+icon+'</span><span class="lm">'+msg+'</span>';
    lg.appendChild(d); lg.scrollTop=lg.scrollHeight;
}
function clearLog(){ el('tx-log').innerHTML=''; log('dim','—','Log cleared'); }

/* ══════════════════════════════════════════════════════════
   LIVE PANELS
══════════════════════════════════════════════════════════ */
function updLive(){
    const fr=parseInt(el('fee-rate').value)||20;
    const oc=fr*250, cf=fr*150;
    const v=parseInt(el('send-amt').value)||10000;
    const open=S.open, tx=S.txCount, cap=S.capacity, al=S.alice, bo=S.bob;
    const saved=Math.max(0,S.feesSaved), cn=S.commitNum;
    const route=S.currentRoute||[];
    const routeFee=route.reduce((s,n)=>s+hopFee(n,v),0);
    const onlineN=nodes.filter(n=>n.status==='online').length;

    el('live-what').innerHTML=open
        ?`<span class="hd">── LIVE ──────────────────────────────────────</span>
<span class="yl">Capacity     :</span> <span class="gn">${fmt(cap)} sats</span>  <span class="hd">(${susd(cap)})</span>
<span class="yl">Routing Nodes:</span> <span class="yl">${nodes.length}</span>  <span class="gn">${onlineN} online</span>
<span class="yl">Off-chain TXs:</span> <span class="gn">${tx} ครั้ง</span>  <span class="hd">vs On-chain 2 ครั้ง</span>
<span class="yl">Fees Saved   :</span> <span class="bl">${fmt(saved)} sats</span>  <span class="hd">(${susd(saved)})</span>
<span class="hd">──────────────────────────────────────────</span>
Layer 1 : ~7 TPS · ~10 min · <span class="rd">${fmt(oc)}</span> sats/tx
Layer 2 : >1M TPS · <1 sec · <span class="gn">${routeFee===0?'FREE':fmt(routeFee)+' sats'}</span>/tx
<span class="hd">──────────────────────────────────────────</span>
<span class="hd">ถ้า Close ตอนนี้ (Alice closes):</span>
Alice ← <span class="yl">${fmt(Math.max(0,al-cf))}</span> sats  <span class="hd">(${fmt(al)} - ${fmt(cf)} closing fee)</span>
Bob   ← <span class="yl">${fmt(bo)}</span> sats  <span class="hd">(ไม่หัก)</span>`
        :`<span class="hd">── กด OPEN CHANNEL ─────────────────────────</span>`;

    const routeNotes=nodes.filter(n=>n.status!=='offline').map(n=>{
        const f=hopFee(n,v), ok=n.liquidity>=v;
        return `  ${n.name.padEnd(7)} <span class="${ok?'gn':'rd'}">${ok?'✓':'✗'}</span>  💧${fmt(n.liquidity)}s  fee <span class="or">${f}s</span>`;
    }).join('\n')||'  <span class="hd">ไม่มี Online Node</span>';

    el('live-routing').innerHTML=open
        ?`<span class="hd">── LIVE: Routing Candidates ─────────────────</span>
Amount : <span class="yl">${fmt(v)} sats</span> · Dir: <span class="yl">${S.dir==='ab'?'Alice→Bob':'Bob→Alice'}</span>
Route  : <span class="gn">${route.length===0?'Direct (no nodes)':'Via '+route.map(n=>n.name).join(' → ')}</span>
<span class="hd">──────────────────────────────────────────</span>
${routeNotes}`
        :`<span class="hd">── กด OPEN CHANNEL + เพิ่ม Node ──────────</span>`;

    el('live-htlc').innerHTML=open&&route.length>0
        ?`<span class="hd">── LIVE: HTLC ───────────────────────────────</span>
Route  : Alice → <span class="pu">${route.map(n=>n.name).join(' → ')}</span> → Bob
Amount : <span class="yl">${fmt(v)} sats</span>
Fee    : <span class="or">${fmt(routeFee)} sats</span>
Alice sends : <span class="rd">${fmt(v+routeFee)} sats</span>  Bob gets: <span class="gn">${fmt(v)} sats</span>
<span class="hd">Atomic: สำเร็จทั้งหมดหรือ Refund ทั้งหมด</span>`
        :`<span class="hd">── เลือก Route ผ่าน Node แล้ว SEND ──────────</span>`;

    const aBar=cap>0?'█'.repeat(Math.round(al/cap*20))+'░'.repeat(20-Math.round(al/cap*20)):'░░░░░░░░░░░░░░░░░░░░';
    const liqRows=nodes.map(n=>{
        const pct=Math.round(n.liquidity/Math.max(...nodes.map(x=>x.liquidity),1)*16);
        return `  <span style="color:${n.color}">${n.name.padEnd(7)}</span> [${'█'.repeat(pct)+'░'.repeat(16-pct)}] <span class="hd">${fmt(n.liquidity)}s</span>`;
    }).join('\n')||'  <span class="hd">ไม่มี Node</span>';

    el('live-liquidity').innerHTML=open
        ?`<span class="hd">── LIVE: Liquidity ──────────────────────────</span>
ALICE [<span class="yl">${aBar}</span>] ${cap>0?(al/cap*100).toFixed(1):'—'}%
<span class="hd">──────────────────────────────────────────</span>
${liqRows}`
        :`<span class="hd">── กด OPEN CHANNEL เพื่อดู Live ────────────</span>`;

    /* Pathfinding — show all candidate routes */
    el('live-pathfind').innerHTML=open&&nodes.length
        ?`<span class="hd">── LIVE: BFS Pathfinding Result ─────────────</span>
Amount : <span class="yl">${fmt(v)} sats</span>
Edges  : ${edges.length} connections · Nodes: ${nodes.length}
<span class="hd">──────────────────────────────────────────</span>
Selected: <span class="gn">${route.length===0?'Direct':'Via '+route.map(n=>n.name).join('→')}</span>
Fee     : <span class="or">${fmt(routeFee)} sats</span>  <span class="hd">(vs On-chain ${fmt(oc)} sats)</span>`
        :`<span class="hd">── เพิ่ม Node แล้ว FIND ROUTE เพื่อดู Live ──</span>`;

    const failNodes=nodes.filter(n=>n.status==='offline'||n.liquidity<v);
    el('live-fail').innerHTML=open&&nodes.length
        ?`<span class="hd">── LIVE: Node Health Check ──────────────────</span>
${nodes.map(n=>{
    const reasons=[];
    if(n.status==='offline') reasons.push('OFFLINE');
    if(n.liquidity<v) reasons.push('Liq ไม่พอ');
    if(n.status==='congested') reasons.push('Congested ×2');
    const ok=reasons.length===0;
    return `  <span class="${ok?'gn':'rd'}">${ok?'✓':'✗'} ${n.name}</span>  ${ok?'<span class="gn">พร้อม</span>':'<span class="rd">'+reasons.join(', ')+'</span>'}`;
}).join('\n')}
<span class="hd">──────────────────────────────────────────</span>
${failNodes.length?`<span class="rd">⚠ ${failNodes.length} Node ใช้ไม่ได้</span>`:'<span class="gn">✓ ทุก Node พร้อม</span>'}`
        :`<span class="hd">── Toggle Node เป็น Offline เพื่อดู Live ───</span>`;

    const feeRows=nodes.filter(n=>n.status!=='offline').map(n=>{
        const f=hopFee(n,v);
        return `Via ${n.name.padEnd(7)} : <span class="or">${fmt(f)} sats</span>  <span class="hd">(${n.baseFee}sat+⌈${fmt(v)}×${n.feeRate}/1M⌉${n.status==='congested'?' ×2':''})</span>`;
    }).join('\n')||'<span class="hd">ไม่มี Online Node</span>';

    el('live-fees').innerHTML=open
        ?`<span class="hd">── LIVE: Fee Breakdown ──────────────────────</span>
Amount      : <span class="yl">${fmt(v)} sats</span>
On-chain fee: <span class="rd">${fmt(oc)} sats</span>  (${fr} s/vB × 250 vB)
<span class="hd">──────────────────────────────────────────</span>
Direct      : <span class="gn">FREE</span>
${feeRows}
<span class="hd">──────────────────────────────────────────</span>
Best route  : <span class="gn">${route.length===0?'Direct':route[0].name}</span>  fee <span class="or">${fmt(routeFee)} sats</span>
Saving      : <span class="bl">${fmt(oc-routeFee)} sats</span> (${routeFee===0?'100':((1-routeFee/oc)*100).toFixed(1)}%)`
        :`<span class="hd">── กด OPEN CHANNEL เพื่อดู Live ────────────</span>`;

    const eff=tx>1?((1-2/(tx+2))*100).toFixed(1):'—';
    el('live-tradeoffs').innerHTML=open
        ?`<span class="hd">── LIVE: Economics ──────────────────────────</span>
${tx} payments · ${nodes.length} nodes · ${fmt(cap)} sats
<span class="hd">──────────────────────────────────────────</span>
Open fee       : <span class="rd">${fmt(S.openFee)} sats</span>  <span class="hd">(Alice จ่าย)</span>
Est. close fee : <span class="rd">${fmt(cf)} sats</span>  <span class="hd">(Alice จ่าย ถ้า Alice Close)</span>
Routing saved  : <span class="gn">${fmt(saved)} sats</span>
On-chain equiv : ${tx}×${fmt(oc)} = <span class="rd">${fmt(tx*oc)} sats</span>
<span class="hd">──────────────────────────────────────────</span>
Closing TX: ฝ่ายที่กด Close จ่าย ~${fmt(cf)} sats
<span class="hd">  Alice กด Close → Alice ได้ ${fmt(Math.max(0,al-cf))} sats</span>
<span class="hd">  Bob ไม่ได้ Close → Bob ได้ ${fmt(bo)} sats (เต็ม)</span>
Efficiency : <span class="gn">${eff}%</span>`
        :`<span class="hd">── กด OPEN CHANNEL เพื่อดู Live ────────────</span>`;
}

/* ══════════════════════════════════════════════════════════
   CONCEPT TABS
══════════════════════════════════════════════════════════ */
function showC(id,btn){
    document.querySelectorAll('.ccontent').forEach(e=>e.classList.remove('active'));
    document.querySelectorAll('.ctab').forEach(e=>e.classList.remove('active'));
    el('c-'+id).classList.add('active'); if(btn) btn.classList.add('active');
    updLive();
}

/* ══════════════════════════════════════════════════════════
   TOUR
══════════════════════════════════════════════════════════ */
const TOUR=[
    {title:'⚡ Lightning Network Simulator',body:'จำลอง Lightning Network ครบวงจร — มี Alice–Bob Channel, Routing Nodes แบบ Random Topology, Pathfinding จริง, และ Visual ที่ Bitcoin วิ่งตามเส้นทาง'},
    {title:'⚙ STEP 1 — เปิด Channel',body:'กรอก Funding แล้วกด <strong>OPEN CHANNEL</strong><br>เส้นสีทองตรงกลาง = Direct Channel Alice–Bob'},
    {title:'🔀 STEP 2 — เพิ่ม Routing Nodes',body:'กด <strong>+ ADD NODE</strong> เพื่อเพิ่ม Node กลาง<br><strong>ลาก Node</strong> บนแผนผังเพื่อจัดตำแหน่ง<br><strong>คลิก Node</strong> เพื่อดูรายละเอียด แก้ Fee และ Toggle สถานะ<br>กด <strong>REWIRE</strong> สร้าง Topology ใหม่แบบสุ่ม'},
    {title:'→ STEP 3 — Find Route & Send',body:'กด <strong>FIND ROUTE</strong> ให้ระบบหาเส้นทางดีที่สุดด้วย BFS<br>กด <strong>SEND</strong> แล้วดู <em>Bitcoin วิ่งตามเส้นทางจริง</em> บนแผนผัง<br>ลอง Toggle Node เป็น Offline ดูว่า Route เปลี่ยนยังไง'},
];
let tIdx=0;
function tourStart(){ tIdx=0; _tShow(); el('tour-ov').classList.add('show'); }
function _tShow(){ const t=TOUR[tIdx]; el('tour-num').textContent='STEP '+(tIdx+1)+' / '+TOUR.length; el('tour-title').textContent=t.title; el('tour-body').innerHTML=t.body; }
function tourNext(){ if(++tIdx>=TOUR.length) tourClose(); else _tShow(); }
function tourClose(){ el('tour-ov').classList.remove('show'); }

/* ══════════════════════════════════════════════════════════
   INIT
══════════════════════════════════════════════════════════ */
el('send-amt').addEventListener('input',updSend);
el('fee-rate').addEventListener('input',updFeeRate);
buildEdges();
updFund(); updSend(); updFeeBar(); updLive(); renderMiniNodeList();
</script>
<footer style="
    text-align:center;
    padding:28px 20px 24px;
    border-top:1px solid rgba(255,255,255,.05);
    margin-top:8px;
">
  <div style="
    font-family:'Chakra Petch',sans-serif;
    font-size:.7rem;
    color:#3a3a3a;
    letter-spacing:.5px;
    line-height:2;
  ">
    <span style="color:#555">© 2026 Chollatis Bitcoiner.</span>
    &nbsp;·&nbsp;
    <span style="color:var(--gold);font-weight:600">Don't Trust, Verify.</span>
    &nbsp;·&nbsp;
    <span style="color:#444">Powered by Bitcoin Protocol &amp; PHP</span>
    &nbsp;
    <span style="
      font-family:'Orbitron',sans-serif;
      font-size:.6rem;
      color:#333;
      letter-spacing:1px;
    ">₿</span>
  </div>
  <div style="margin-top:6px;display:flex;align-items:center;justify-content:center;gap:10px">
    <div style="height:1px;width:60px;background:linear-gradient(90deg,transparent,rgba(255,215,0,.2))"></div>
    <span style="font-family:'Orbitron',sans-serif;font-size:.5rem;color:#2a2a2a;letter-spacing:2px">BITCOIN LAYER 2 EDUCATION</span>
    <div style="height:1px;width:60px;background:linear-gradient(90deg,rgba(255,215,0,.2),transparent)"></div>
  </div>
</footer>
</body>
</html>