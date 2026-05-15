<?php
// Filename: utxo_enhanced.php
// Theme: UTXO Interactive Simulator — Enhanced Edition
// Author: Chollatis Bitcoiner
// Version: 2.0 — Full Thai Self-Study with Realistic Transaction Flow
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>UTXO Simulator | Bitcoin Transaction Mechanics</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='50' fill='%23f7931a'/><text x='50' y='70' font-size='65' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='bold'>₿</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Sarabun:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
:root {
    --bg:      #02060a;
    --bg2:     #080f18;
    --bg3:     #0c1520;
    --glass:   rgba(10,18,30,0.9);
    --btc:     #F7931A;
    --btc2:    #ffb347;
    --green:   #39ff14;
    --green2:  #28cc0f;
    --blue:    #00f3ff;
    --blue2:   #00c8d4;
    --purple:  #bc13fe;
    --red:     #ff3860;
    --orange:  #ff8c00;
    --yellow:  #fbbf24;
    --text:    #c9d1d9;
    --text2:   #8b949e;
    --text3:   #3d4f63;
    --border:  rgba(0,243,255,0.18);
    --border-btc: rgba(247,147,26,0.35);
    --radius:  12px;
    --radius-sm: 6px;
    --shadow:  0 8px 32px rgba(0,0,0,0.8);
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }

body {
    font-family:'Sarabun',sans-serif;
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
    overflow-x:hidden;
    -webkit-tap-highlight-color:transparent;
}

/* ─── Animated Grid BG ─── */
.bg-grid {
    position:fixed; inset:0; z-index:0;
    background:
        linear-gradient(rgba(0,243,255,0.04) 1px,transparent 1px),
        linear-gradient(90deg,rgba(0,243,255,0.04) 1px,transparent 1px);
    background-size:48px 48px;
    transform:perspective(600px) rotateX(18deg) scale(2.2) translateY(-10%);
    transform-origin:top center;
    animation:gridScroll 6s linear infinite;
    pointer-events:none;
}
.bg-fade {
    position:fixed; inset:0; z-index:0;
    background:radial-gradient(ellipse at 50% 0%,rgba(2,6,10,0) 0%,#02060a 70%);
    pointer-events:none;
}
.bg-glow-left  { position:fixed; top:20%; left:-10%; width:400px; height:400px; background:radial-gradient(circle,rgba(0,243,255,0.06),transparent 70%); pointer-events:none; z-index:0; }
.bg-glow-right { position:fixed; top:50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle,rgba(247,147,26,0.05),transparent 70%); pointer-events:none; z-index:0; }
@keyframes gridScroll { 0%{background-position:0 0} 100%{background-position:0 48px} }

/* ─── Scrollbar ─── */
::-webkit-scrollbar { width:4px; }
::-webkit-scrollbar-track { background:var(--bg2); }
::-webkit-scrollbar-thumb { background:var(--btc); border-radius:4px; }

.brand { font-family:'Orbitron',sans-serif; letter-spacing:1px; }
.mono  { font-family:'JetBrains Mono',monospace; }

/* ─── Header ─── */
header {
    position:sticky; top:0; z-index:100;
    background:rgba(2,6,10,0.97);
    backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
    border-bottom:1px solid var(--border);
    padding:12px 20px;
    display:flex; align-items:center; justify-content:space-between;
}
.logo-title {
    font-size:clamp(0.8rem,2.5vw,1rem);
    background:linear-gradient(90deg,var(--blue),var(--btc));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.home-btn { color:var(--blue); width:28px; height:28px; opacity:0.7; transition:all 0.3s; display:flex; align-items:center; }
.home-btn svg { width:100%; height:100%; fill:currentColor; }
.home-btn:hover { opacity:1; transform:scale(1.15); filter:drop-shadow(0 0 6px var(--blue)); }

/* ─── Main Layout ─── */
.page-wrap { position:relative; z-index:1; padding:16px; max-width:1280px; margin:0 auto; }

/* ─── Callouts ─── */
.callout {
    border-radius:var(--radius-sm); padding:14px 16px;
    display:flex; gap:12px; align-items:flex-start;
    margin:0 0 12px; font-size:0.9rem; line-height:1.75;
}
.callout-blue   { background:rgba(0,243,255,0.06);  border:1px solid rgba(0,243,255,0.2); }
.callout-btc    { background:rgba(247,147,26,0.07); border:1px solid rgba(247,147,26,0.25); }
.callout-green  { background:rgba(57,255,20,0.05);  border:1px solid rgba(57,255,20,0.18); }
.callout-red    { background:rgba(255,56,96,0.06);  border:1px solid rgba(255,56,96,0.2); }
.callout-purple { background:rgba(188,19,254,0.06); border:1px solid rgba(188,19,254,0.2); }
.callout-icon   { font-size:1.1rem; flex-shrink:0; padding-top:2px; }

/* ─── Intro Cards Row ─── */
.concept-cards {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px; margin-bottom:16px;
}
@media(max-width:700px) {
    .concept-cards { grid-template-columns:repeat(2,1fr); }
}
.concept-card {
    background:var(--bg2);
    border:1px solid var(--border);
    border-radius:var(--radius-sm);
    padding:14px 12px;
    transition:border-color 0.3s;
}
.concept-card:hover { border-color:rgba(0,243,255,0.4); }
.concept-card .cc-icon { font-size:1.4rem; margin-bottom:6px; display:block; }
.concept-card .cc-title { font-weight:700; color:var(--text); font-size:0.88rem; margin-bottom:4px; }
.concept-card .cc-desc  { font-size:0.8rem; color:var(--text2); line-height:1.5; }

/* ─── Main Stage Grid ─── */
.stage {
    display:grid;
    grid-template-columns:1fr 44px 1.1fr 44px 1fr;
    gap:0; align-items:stretch;
    width:100%;
}
@media(max-width:1000px) {
    .stage {
        grid-template-columns:1fr;
        grid-template-rows:auto;
    }
    .flow-col {
        transform:none;
        flex-direction:row;
        justify-content:center;
        align-items:center;
        gap:8px;
        padding:4px 0;
        margin:0 auto;
    }
    .flow-arrow-wrap {
        flex-direction:row;
        align-items:center;
        gap:8px;
    }
    .flow-arrow-sym {
        transform:rotate(90deg);
        display:inline-block;
        font-size:1.4rem;
    }
    .flow-arrow-label {
        font-size:0.65rem;
        letter-spacing:2px;
    }
}

/* ─── Flow Arrow Column ─── */
.flow-col {
    display:flex; flex-direction:column; align-items:center;
    justify-content:center; align-self:stretch; padding-top:0;
}
.flow-arrow-wrap {
    display:flex; flex-direction:column; align-items:center; gap:4px;
}
.flow-arrow-sym {
    font-size:1.8rem; color:var(--blue);
    text-shadow:0 0 12px var(--blue);
    animation:arrowPulse 1.8s ease-in-out infinite;
}
.flow-arrow-label { font-size:0.6rem; color:var(--text3); font-family:'Orbitron',sans-serif; letter-spacing:1px; }
@keyframes arrowPulse { 0%,100%{opacity:0.4;transform:scale(1)} 50%{opacity:1;transform:scale(1.15)} }

/* ─── Panels ─── */
.panel {
    background:var(--glass);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:18px;
    backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
    box-shadow:var(--shadow);
    display:flex; flex-direction:column;
    min-height:520px;
}
.panel-inputs  { border-color:var(--border); }
.panel-core    { border-color:rgba(188,19,254,0.4); box-shadow:0 0 20px rgba(188,19,254,0.08),var(--shadow); }
.panel-outputs { border-color:rgba(57,255,20,0.3); box-shadow:0 0 20px rgba(57,255,20,0.05),var(--shadow); }

.panel-header {
    display:flex; justify-content:space-between; align-items:center;
    padding-bottom:12px; margin-bottom:12px;
    border-bottom:1px solid var(--border);
}
.panel-title { font-family:'Orbitron',sans-serif; font-size:0.78rem; letter-spacing:2px; }
.panel-balance { font-family:'JetBrains Mono',monospace; font-size:1rem; color:var(--green); font-weight:700; }

/* ─── Explain strip ─── */
.panel-explain {
    background:rgba(0,0,0,0.3); border-left:3px solid var(--blue);
    border-radius:0 var(--radius-sm) var(--radius-sm) 0;
    padding:10px 12px; font-size:0.82rem; color:var(--text2);
    line-height:1.6; margin-bottom:12px;
}

/* ─── UTXO List ─── */
.utxo-list { flex-grow:1; display:flex; flex-direction:column; gap:8px; overflow-y:auto; padding-right:4px; min-height:200px; }

/* ─── UTXO Block ─── */
.utxo-block {
    background:rgba(0,243,255,0.04);
    border:1px solid var(--blue);
    border-left:4px solid var(--blue);
    border-radius:var(--radius-sm);
    padding:10px 12px;
    font-family:'JetBrains Mono',monospace;
    color:#fff;
    display:flex; flex-direction:column; gap:6px;
    transition:all 0.4s ease;
    position:relative; overflow:hidden;
}
.utxo-block-row { display:flex; justify-content:space-between; align-items:center; }
.utxo-id   { font-size:0.72rem; color:var(--text3); font-family:'Orbitron',sans-serif; letter-spacing:1px; }
.utxo-txid { font-size:0.62rem; color:var(--text3); word-break:break-all; margin-top:2px; opacity:0.7; }
.utxo-amount-wrap { display:flex; align-items:center; gap:4px; }
.utxo-input {
    background:transparent; border:none;
    border-bottom:1px dashed rgba(255,255,255,0.25);
    color:var(--blue); font-family:'JetBrains Mono',monospace;
    font-size:1rem; width:110px; text-align:right;
    outline:none; transition:all 0.3s; padding:2px 0;
}
.utxo-input:focus { border-bottom-color:var(--green); color:var(--green); }
.utxo-btc-label { font-size:0.75rem; color:var(--text3); }
.utxo-sats { font-size:0.68rem; color:var(--text3); text-align:right; }

/* UTXO States */
.utxo-block.new-entry {
    border-color:var(--green); border-left-color:var(--green);
    background:rgba(57,255,20,0.07);
    box-shadow:0 0 12px rgba(57,255,20,0.12);
    animation:slideIn 0.5s ease-out;
}
.utxo-block.new-entry .utxo-input { color:var(--green); }

.utxo-block.locked {
    border-color:var(--orange); border-left-color:var(--orange);
    background:rgba(255,140,0,0.1);
    box-shadow:0 0 14px rgba(255,140,0,0.18);
    transform:translateX(4px);
}
.lock-badge {
    position:absolute; top:-1px; right:8px;
    background:var(--orange); color:#000;
    font-size:0.6rem; font-weight:700; font-family:'Orbitron',sans-serif;
    padding:1px 6px; border-radius:0 0 4px 4px; letter-spacing:1px;
}

.utxo-block.spent {
    border-color:var(--red); background:rgba(255,56,96,0.12);
    color:var(--red);
    animation:shakeBlock 0.5s ease-out;
}
.utxo-block.spent .utxo-input { color:var(--red); text-decoration:line-through; }
.spent-stamp {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%) rotate(-12deg);
    font-size:1.2rem; font-weight:900; font-family:'Orbitron',sans-serif;
    color:var(--red); border:2px solid var(--red);
    padding:4px 10px; background:rgba(0,0,0,0.85); z-index:10;
    letter-spacing:2px; white-space:nowrap;
    text-shadow:0 0 8px var(--red);
}
.utxo-block.fade-out { opacity:0; transform:translateX(-40px); max-height:0; margin:0; padding:0; border:none; overflow:hidden; }

@keyframes slideIn   { from{transform:translateY(16px);opacity:0} to{transform:translateY(0);opacity:1} }
@keyframes shakeBlock{ 0%{transform:translateX(0)} 25%{transform:translateX(6px)} 75%{transform:translateX(-6px)} 100%{transform:translateX(0)} }

/* ─── Add UTXO Button ─── */
.add-utxo-btn {
    margin-top:10px; background:transparent;
    border:1px dashed var(--text3); color:var(--text3);
    cursor:pointer; padding:8px; width:100%;
    border-radius:var(--radius-sm); font-size:0.82rem;
    transition:all 0.3s; font-family:'Sarabun',sans-serif;
}
.add-utxo-btn:hover { border-color:var(--blue); color:var(--blue); }

/* ─── Core Panel ─── */
.core-section-label {
    font-family:'Orbitron',sans-serif; font-size:0.65rem;
    letter-spacing:2px; color:var(--text3); margin-bottom:6px;
    text-transform:uppercase;
}

.input-field-wrap { margin-bottom:14px; }
.field-label {
    font-size:0.72rem; color:var(--blue);
    font-family:'Orbitron',sans-serif; letter-spacing:1.5px;
    display:block; margin-bottom:6px; text-transform:uppercase;
}
.field-label.orange { color:var(--orange); }
.field-label.green  { color:var(--green); }

.core-input {
    width:100%; background:rgba(0,0,0,0.4);
    border:1px solid rgba(188,19,254,0.4);
    padding:12px 14px; color:#fff;
    font-size:1.1rem; font-family:'JetBrains Mono',monospace;
    text-align:center; border-radius:var(--radius-sm);
    outline:none; transition:all 0.3s;
}
.core-input:focus { box-shadow:0 0 16px rgba(188,19,254,0.3); border-color:rgba(188,19,254,0.8); }
.core-input.orange-field { border-color:rgba(255,140,0,0.4); }
.core-input.orange-field:focus { box-shadow:0 0 16px rgba(255,140,0,0.3); border-color:var(--orange); }

.field-hint { font-size:0.76rem; color:var(--text3); margin-top:4px; line-height:1.5; }

/* ─── Coin selector ─── */
.coin-select-wrap { margin-bottom:14px; }
.coin-options { display:flex; gap:6px; flex-wrap:wrap; margin-top:6px; }
.coin-opt {
    background:rgba(0,0,0,0.4); border:1px solid var(--border);
    color:var(--text2); border-radius:20px; padding:4px 12px;
    font-size:0.78rem; font-family:'JetBrains Mono',monospace;
    cursor:pointer; transition:all 0.2s;
}
.coin-opt:hover,.coin-opt.active {
    background:rgba(247,147,26,0.15); border-color:var(--btc); color:var(--btc);
}

/* ─── Fee Meter ─── */
.fee-meter-track {
    height:6px; background:rgba(255,255,255,0.06);
    border-radius:3px; overflow:hidden; margin-top:6px;
}
.fee-meter-fill {
    height:100%; border-radius:3px;
    transition:width 0.4s ease, background 0.4s;
}

/* ─── Fee Slider ─── */
.fee-slider-wrap {
    background:rgba(0,0,0,0.4);
    border:1px solid rgba(255,140,0,0.35);
    border-radius:var(--radius-sm);
    padding:14px 16px 12px;
}
.fee-slider-header {
    display:flex; justify-content:space-between; align-items:center;
    margin-bottom:10px;
}
.fee-slider-val {
    font-family:'Orbitron',sans-serif;
    font-size:1.15rem; font-weight:700;
    color:var(--orange);
    transition:color 0.3s;
}
.fee-slider-btc {
    font-family:'JetBrains Mono',monospace;
    font-size:0.72rem; color:var(--text3); margin-top:2px;
}
/* Range input reset */
input[type=range] {
    -webkit-appearance:none; appearance:none;
    width:100%; height:6px;
    border-radius:3px; outline:none; cursor:pointer;
    background:rgba(255,255,255,0.06);
    transition:background 0.3s;
}
input[type=range]::-webkit-slider-thumb {
    -webkit-appearance:none; appearance:none;
    width:20px; height:20px; border-radius:50%;
    background:var(--orange);
    box-shadow:0 0 10px rgba(255,140,0,0.6), 0 0 20px rgba(255,140,0,0.3);
    border:2px solid rgba(255,255,255,0.2);
    cursor:pointer; transition:all 0.2s;
}
input[type=range]::-webkit-slider-thumb:hover {
    transform:scale(1.2);
    box-shadow:0 0 16px rgba(255,140,0,0.9), 0 0 32px rgba(255,140,0,0.4);
}
input[type=range]::-moz-range-thumb {
    width:20px; height:20px; border-radius:50%;
    background:var(--orange);
    box-shadow:0 0 10px rgba(255,140,0,0.6);
    border:2px solid rgba(255,255,255,0.2);
    cursor:pointer;
}
.fee-ticks {
    display:flex; justify-content:space-between;
    margin-top:8px; padding:0 2px;
}
.fee-tick {
    font-size:0.62rem; color:var(--text3);
    font-family:'Orbitron',sans-serif; letter-spacing:0.5px;
    cursor:pointer; transition:color 0.2s; text-align:center;
}
.fee-tick:hover { color:var(--orange); }
.fee-tick.active { color:var(--orange); }
.fee-presets {
    display:flex; gap:6px; margin-top:10px; flex-wrap:wrap;
}
.fee-preset-btn {
    flex:1; background:rgba(0,0,0,0.4);
    border:1px solid var(--border); color:var(--text3);
    border-radius:4px; padding:5px 6px; font-size:0.7rem;
    font-family:'Orbitron',sans-serif; letter-spacing:0.5px;
    cursor:pointer; transition:all 0.25s; text-align:center;
    min-width:50px;
}
.fee-preset-btn:hover, .fee-preset-btn.active {
    border-color:var(--orange); color:var(--orange);
    background:rgba(255,140,0,0.1);
    box-shadow:0 0 8px rgba(255,140,0,0.2);
}

/* ─── Reactor ─── */
.reactor-wrap {
    width:100%; background:rgba(0,0,0,0.5);
    border:1px dashed rgba(188,19,254,0.3);
    border-radius:var(--radius-sm); height:90px;
    display:flex; align-items:center; justify-content:center;
    position:relative; overflow:hidden; margin-bottom:14px;
}
.reactor-core {
    width:50px; height:50px; border-radius:50%;
    background:radial-gradient(circle,var(--blue),transparent);
    box-shadow:0 0 8px var(--blue); opacity:0.12;
    transition:all 0.5s; border:2px solid rgba(255,255,255,0.08);
}
.reactor-core.active {
    opacity:1;
    animation:rPulse 0.2s infinite alternate, rSpin 0.5s infinite linear;
    box-shadow:0 0 40px var(--purple),inset 0 0 20px var(--blue);
    background:radial-gradient(circle,#fff,var(--orange));
    border-color:var(--orange);
}
.reactor-rings {
    position:absolute; inset:0;
    display:flex; align-items:center; justify-content:center;
    pointer-events:none;
}
.ring {
    position:absolute; border-radius:50%;
    border:1px solid rgba(0,243,255,0.15);
    animation:ringExpand 3s ease-out infinite;
    opacity:0;
}
.ring:nth-child(1) { width:60px;  height:60px;  animation-delay:0s; }
.ring:nth-child(2) { width:90px;  height:90px;  animation-delay:1s; }
.ring:nth-child(3) { width:120px; height:120px; animation-delay:2s; }
@keyframes ringExpand { 0%{opacity:0.4;transform:scale(0.5)} 100%{opacity:0;transform:scale(1.2)} }
@keyframes rPulse { from{transform:scale(1)} to{transform:scale(1.12)} }
@keyframes rSpin  { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

.reactor-text {
    position:absolute; font-family:'Orbitron',sans-serif;
    color:#fff; font-size:0.7rem; letter-spacing:3px;
    text-shadow:0 0 6px var(--orange); display:none;
    background:rgba(0,0,0,0.8); padding:6px 16px;
    border:1px solid var(--orange); border-radius:4px;
}

/* ─── TX Summary ─── */
.tx-summary {
    background:rgba(0,0,0,0.4); border:1px solid var(--border);
    border-radius:var(--radius-sm); padding:12px; margin-bottom:12px;
    font-size:0.8rem; display:none;
}
.tx-summary-row { display:flex; justify-content:space-between; margin-bottom:4px; }
.tx-summary-row:last-child { margin-bottom:0; border-top:1px solid var(--border); padding-top:6px; margin-top:6px; }

/* ─── Execute Button ─── */
.exec-btn {
    width:100%; padding:14px;
    background:linear-gradient(90deg,var(--purple),var(--orange));
    border:none; color:#fff;
    font-family:'Orbitron',sans-serif; font-size:0.85rem;
    font-weight:700; cursor:pointer; border-radius:50px;
    transition:all 0.3s; letter-spacing:2px;
    box-shadow:0 0 20px rgba(188,19,254,0.3);
}
.exec-btn:hover:not(:disabled) {
    box-shadow:0 0 40px rgba(188,19,254,0.6);
    transform:translateY(-2px);
}
.exec-btn:active { transform:scale(0.97); }
.exec-btn:disabled { filter:grayscale(1); opacity:0.5; cursor:not-allowed; transform:none; }

.status-msg { height:20px; color:var(--red); font-size:0.85rem; text-align:center; margin-top:8px; }

/* ─── Output Panel ─── */
.output-section-label {
    font-size:0.7rem; color:var(--text3);
    font-family:'Orbitron',sans-serif; letter-spacing:2px;
    margin-bottom:6px; text-transform:uppercase;
}
.output-box {
    background:rgba(0,0,0,0.35); border-radius:var(--radius-sm);
    padding:14px; margin-bottom:12px; min-height:80px;
    display:flex; align-items:center; justify-content:center;
    border:1px dashed var(--text3); flex-direction:column;
    gap:4px; transition:all 0.5s; text-align:center;
}
.output-box.lit { border-style:solid; box-shadow:0 0 16px rgba(255,255,255,0.08); }

.output-explain {
    font-size:0.78rem; color:var(--text2); line-height:1.6;
    margin-bottom:8px;
}

/* ─── TX Log ─── */
.tx-log {
    margin-top:14px; background:rgba(0,0,0,0.4);
    border:1px solid var(--border); border-radius:var(--radius-sm);
    padding:10px; max-height:150px; overflow-y:auto;
}
.tx-log-title { font-family:'Orbitron',sans-serif; font-size:0.65rem; letter-spacing:2px; color:var(--text3); margin-bottom:8px; }
.tx-log-entry {
    font-family:'JetBrains Mono',monospace; font-size:0.7rem;
    color:var(--text3); border-bottom:1px solid rgba(255,255,255,0.04);
    padding:4px 0; display:flex; gap:8px; align-items:flex-start;
}
.tx-log-entry .log-time { color:var(--blue); flex-shrink:0; opacity:0.6; }
.tx-log-entry .log-msg  { line-height:1.4; }
.tx-log-entry.log-success .log-msg { color:var(--green); }
.tx-log-entry.log-warning .log-msg { color:var(--orange); }
.tx-log-entry.log-error   .log-msg { color:var(--red); }

/* ─── Stat Row ─── */
.stat-row {
    display:grid; grid-template-columns:repeat(3,1fr); gap:8px;
    margin-bottom:14px;
}
.stat-box {
    background:rgba(0,0,0,0.4); border:1px solid var(--border);
    border-radius:var(--radius-sm); padding:10px 8px; text-align:center;
}
.stat-val   { font-family:'Orbitron',sans-serif; font-size:1rem; font-weight:700; display:block; }
.stat-label { font-size:0.62rem; color:var(--text3); letter-spacing:1px; margin-top:2px; display:block; }

/* ─── Progress bar ─── */
.progress-track { height:4px; background:rgba(255,255,255,0.06); border-radius:2px; overflow:hidden; margin:8px 0; }
.progress-fill  { height:100%; border-radius:2px; background:var(--btc); transition:width 0.4s ease; box-shadow:0 0 8px var(--btc); }

/* ─── Section divider ─── */
.section-div {
    display:flex; align-items:center; gap:10px;
    margin:16px 0 12px;
}
.section-div .line { flex:1; height:1px; background:var(--border); }
.section-div .text { font-family:'Orbitron',sans-serif; font-size:0.65rem; letter-spacing:3px; color:var(--text3); white-space:nowrap; }

/* ─── Footer ─── */
footer {
    position:relative; z-index:1;
    text-align:center; padding:20px;
    color:var(--text3); font-size:0.75rem;
    border-top:1px solid rgba(255,255,255,0.05);
}

/* ─── Animations ─── */
@keyframes appear { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.panel { animation:appear 0.5s ease-out both; }
.panel:nth-child(1){animation-delay:0.05s}
.panel:nth-child(3){animation-delay:0.15s}
.panel:nth-child(5){animation-delay:0.25s}

@media(max-width:600px) {
    .page-wrap { padding:10px; }
    .panel { padding:14px; min-height:auto; }
    .concept-cards { grid-template-columns:1fr 1fr; } /* mobile 2-col */
    .stat-row { grid-template-columns:repeat(3,1fr); }
}
</style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-fade"></div>
<div class="bg-glow-left"></div>
<div class="bg-glow-right"></div>

<!-- ════ HEADER ════ -->
<header>
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-size:1.5rem;">💎</span>
        <div>
            <div class="brand logo-title">UTXO SIMULATOR</div>
            <div style="font-size:0.68rem;color:var(--text3);margin-top:1px;">Bitcoin Transaction Mechanics · Educational</div>
        </div>
    </div>
    <a href="/" class="home-btn" title="Back to Home">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
    </a>
</header>

<div class="page-wrap">

<!-- ════ SECTION 1 ════ -->
<div class="section-div"><div class="line"></div><div class="text">§ 01 · ความเข้าใจเบื้องต้น</div><div class="line"></div></div>

<!-- ════ INTRO SECTION ════ -->
<div class="callout callout-blue" style="margin-bottom:10px;">
    <span class="callout-icon" style="color:var(--blue);">📖</span>
    <div>
        <strong style="color:var(--blue);">UTXO คืออะไร?</strong><br>
        <span style="color:var(--text2);">UTXO (Unspent Transaction Output) คือ "เศษเงิน Bitcoin" ที่คุณได้รับแต่ยังไม่ได้ใช้ — Bitcoin ไม่มี "ยอดเงินในบัญชี" แบบธนาคาร แต่ใช้ระบบ UTXO แทน เหมือนคุณมีธนบัตรหลายใบในกระเป๋า แต่ละใบมีมูลค่าต่างกัน เวลาจ่ายเงินต้องหยิบธนบัตรมารวมกัน และรับเงินทอนกลับมาเป็นธนบัตรใบใหม่</span>
    </div>
</div>

<div class="concept-cards">
    <div class="concept-card">
        <span class="cc-icon">💵</span>
        <div class="cc-title" style="color:var(--blue);">UTXO = ธนบัตร Bitcoin</div>
        <div class="cc-desc">แต่ละ UTXO คือธนบัตร 1 ใบ มีมูลค่าคงที่ ต้องใช้ทั้งใบเท่านั้น ห้ามแบ่งครึ่ง</div>
    </div>
    <div class="concept-card">
        <span class="cc-icon">🔒</span>
        <div class="cc-title" style="color:var(--orange);">Input = ล็อกธนบัตร</div>
        <div class="cc-desc">เวลาส่ง Bitcoin ระบบจะ "ล็อก" UTXO ที่เลือก — ใช้แล้วหมดทันที ไม่มีการ "หักบัญชี"</div>
    </div>
    <div class="concept-card">
        <span class="cc-icon">📤</span>
        <div class="cc-title" style="color:var(--orange);">Output = ปลายทาง</div>
        <div class="cc-desc">Bitcoin ที่ส่งออกไปกลายเป็น UTXO ใหม่ของผู้รับ พร้อมใช้ในการส่งต่อ</div>
    </div>
    <div class="concept-card">
        <span class="cc-icon">🔄</span>
        <div class="cc-title" style="color:var(--green);">Change = เงินทอน</div>
        <div class="cc-desc">ส่วนที่เหลือจากการใช้ UTXO กลับมาเป็น UTXO ใหม่ของคุณเอง — เหมือนรับเงินทอน</div>
    </div>
    <div class="concept-card">
        <span class="cc-icon">⛏️</span>
        <div class="cc-title" style="color:var(--red);">Fee = ค่า Miner</div>
        <div class="cc-desc">ส่วนต่างที่หายไปคือค่าธรรมเนียมให้นักขุด ไม่มีเงินทอน Fee ก็ไม่มีใครยืนยัน Tx</div>
    </div>
    <div class="concept-card">
        <span class="cc-icon">🔗</span>
        <div class="cc-title" style="color:var(--purple);">UTXO Set</div>
        <div class="cc-desc">รวม UTXO ทั้งหมดที่คุณมี = ยอดรวม Bitcoin ของคุณ Node ทุกตัวเก็บ UTXO Set ของทุกคนบนโลก</div>
    </div>
</div>

<!-- ════ SECTION 2 ════ -->
<div class="section-div"><div class="line"></div><div class="text">§ 02 · ทดลองเพื่อให้เข้าใจ</div><div class="line"></div></div>

<!-- ════ MAIN STAGE ════ -->
<div class="stage">

    <!-- ── LEFT: UTXO INPUTS ── -->
    <div class="panel panel-inputs">
        <div class="panel-header">
            <span class="panel-title" style="color:var(--blue);">📦 UTXO SET (กระเป๋าเงิน)</span>
            <span class="panel-balance" id="total-balance">0.00000000 BTC</span>
        </div>

        <div class="panel-explain">
            นี่คือ UTXO ทั้งหมดที่คุณ "ถืออยู่" — แต่ละก้อนคือ Bitcoin ที่ได้รับจาก transaction ก่อนหน้า<br>
            <span style="color:var(--blue);">🔵 น้ำเงิน</span> = UTXO ปกติ &nbsp;|&nbsp;
            <span style="color:var(--orange);">🔒 ส้ม</span> = กำลังถูกเลือกใช้ &nbsp;|&nbsp;
            <span style="color:var(--green);">🟢 เขียว</span> = UTXO ใหม่ที่เพิ่งได้รับ<br>
            <span style="color:var(--text3);font-size:0.78rem;">💡 แตะตัวเลขเพื่อแก้ไขจำนวน BTC ของแต่ละ UTXO ได้</span>
        </div>

        <!-- Stat row -->
        <div class="stat-row" style="margin-bottom:10px;">
            <div class="stat-box">
                <span class="stat-val" id="stat-count" style="color:var(--blue);">0</span>
                <span class="stat-label">UTXO COUNT</span>
            </div>
            <div class="stat-box">
                <span class="stat-val" id="stat-total-sats" style="color:var(--btc);">0</span>
                <span class="stat-label">TOTAL SATS</span>
            </div>
            <div class="stat-box">
                <span class="stat-val" id="stat-avg" style="color:var(--green);">0</span>
                <span class="stat-label">AVG SATS</span>
            </div>
        </div>

        <div class="utxo-list" id="utxo-container"></div>

        <button class="add-utxo-btn" onclick="addManualUTXO()">
            ＋ เพิ่ม UTXO ใหม่ (จำลองการรับ Bitcoin)
        </button>
    </div>

    <!-- ── ARROW 1 ── -->
    <div class="flow-col">
        <div class="flow-arrow-wrap">
            <div class="flow-arrow-sym">➜</div>
            <div class="flow-arrow-label">INPUT</div>
        </div>
    </div>

    <!-- ── CENTER: TRANSACTION CORE ── -->
    <div class="panel panel-core">
        <div class="panel-header">
            <span class="panel-title" style="color:var(--purple);">⚙️ TRANSACTION CORE</span>
            <span style="font-size:0.65rem;color:var(--text3);font-family:'Orbitron',sans-serif;">MEMPOOL</span>
        </div>

        <div class="panel-explain" style="border-left-color:var(--purple);">
            Transaction คือคำสั่ง "โอน Bitcoin จาก UTXO ของฉัน ไปให้ปลายทาง" — ระบบจะเลือก UTXO อัตโนมัติให้พอดีกับจำนวนที่ส่ง และส่งเงินทอนกลับมาให้คุณเอง
        </div>

        <!-- Amount -->
        <div class="input-field-wrap">
            <label class="field-label">จำนวนที่ส่ง (BTC)</label>
            <input type="number" id="send-amount" class="core-input" placeholder="0.00000000" step="0.01" min="0" oninput="updatePreview()" onchange="updatePreview()">
            <div class="field-hint">กรอกจำนวน BTC ที่ต้องการส่งให้ผู้รับ</div>
        </div>

        <!-- Quick amount buttons -->
        <div class="coin-select-wrap">
            <div class="core-section-label">Quick Fill</div>
            <div class="coin-options">
                <button class="coin-opt" onclick="quickFill(0.1)">0.1</button>
                <button class="coin-opt" onclick="quickFill(0.5)">0.5</button>
                <button class="coin-opt" onclick="quickFill(1.0)">1.0</button>
                <button class="coin-opt" onclick="quickFill(1.5)">1.5</button>
                <button class="coin-opt" onclick="quickFillAll()">MAX</button>
            </div>
        </div>

        <!-- Miner Fee Slider -->
        <div class="input-field-wrap">
            <label class="field-label orange">ค่าธรรมเนียม Miner (Satoshi)</label>
            <div class="fee-slider-wrap">
                <div class="fee-slider-header">
                    <div>
                        <div class="fee-slider-val" id="fee-slider-val">1,000 sat</div>
                        <div class="fee-slider-btc" id="fee-slider-btc">≈ 0.00001000 BTC</div>
                    </div>
                    <div id="fee-speed-badge" style="padding:4px 10px;border-radius:20px;font-size:0.68rem;font-family:'Orbitron',sans-serif;letter-spacing:1px;border:1px solid var(--orange);color:var(--orange);background:rgba(255,140,0,0.1);">ดี</div>
                </div>
                <!-- Hidden input for JS compatibility -->
                <input type="hidden" id="miner-fee" value="1000">
                <!-- Visible slider -->
                <input type="range" id="fee-slider" min="0" max="20000" step="100" value="1000"
                    oninput="onFeeSlide(this.value)" onchange="onFeeSlide(this.value)"
                    style="background:linear-gradient(to right,var(--orange) 5%,rgba(255,255,255,0.06) 5%);">
                <!-- Tick labels -->
                <div class="fee-ticks">
                    <span class="fee-tick" onclick="setFee(0)">0<br>sat</span>
                    <span class="fee-tick" onclick="setFee(500)">500</span>
                    <span class="fee-tick" onclick="setFee(1000)">1K</span>
                    <span class="fee-tick" onclick="setFee(3000)">3K</span>
                    <span class="fee-tick" onclick="setFee(10000)">10K</span>
                    <span class="fee-tick" onclick="setFee(20000)">20K<br>sat</span>
                </div>
                <!-- Preset buttons -->
                <div class="fee-presets">
                    <button class="fee-preset-btn" onclick="setFee(200)">🐢<br>ช้า<br>200</button>
                    <button class="fee-preset-btn active" onclick="setFee(1000)">✅<br>ปกติ<br>1K</button>
                    <button class="fee-preset-btn" onclick="setFee(3000)">⚡<br>เร็ว<br>3K</button>
                    <button class="fee-preset-btn" onclick="setFee(10000)">🚀<br>ด่วน<br>10K</button>
                </div>
                <!-- Progress bar -->
                <div class="fee-meter-track" style="margin-top:10px;">
                    <div id="fee-meter-fill" class="fee-meter-fill" style="width:5%;background:var(--green);"></div>
                </div>
                <div id="fee-label" style="font-size:0.7rem;color:var(--green);text-align:right;margin-top:4px;">✅ ดี — น่าจะได้รับการยืนยันเร็ว</div>
            </div>
            <div class="field-hint" style="margin-top:6px;">1 BTC = 100,000,000 Satoshi · ยิ่ง fee สูง Miner ยิ่งเลือก Transaction นี้ก่อน</div>
        </div>

        <!-- TX Summary Preview -->
        <div class="tx-summary" id="tx-summary">
            <div class="core-section-label">TRANSACTION PREVIEW</div>
            <div class="tx-summary-row">
                <span style="color:var(--text3);">UTXO ที่เลือก:</span>
                <span id="sum-utxo-count" style="color:var(--blue);" class="mono">—</span>
            </div>
            <div class="tx-summary-row">
                <span style="color:var(--text3);">รวม Input:</span>
                <span id="sum-input" style="color:var(--text);" class="mono">—</span>
            </div>
            <div class="tx-summary-row">
                <span style="color:var(--text3);">ส่งให้ผู้รับ:</span>
                <span id="sum-send" style="color:var(--orange);" class="mono">—</span>
            </div>
            <div class="tx-summary-row">
                <span style="color:var(--text3);">เงินทอน (Change):</span>
                <span id="sum-change" style="color:var(--green);" class="mono">—</span>
            </div>
            <div class="tx-summary-row">
                <span style="color:var(--text3);">Fee:</span>
                <span id="sum-fee" style="color:var(--red);" class="mono">—</span>
            </div>
        </div>

        <!-- Reactor -->
        <div class="reactor-wrap">
            <div class="reactor-rings">
                <div class="ring"></div><div class="ring"></div><div class="ring"></div>
            </div>
            <div class="reactor-core" id="reactor"></div>
            <div class="reactor-text" id="reactor-text">BROADCASTING...</div>
        </div>

        <button class="exec-btn" id="exec-btn" onclick="executeTx()">
            ⚡ BROADCAST TRANSACTION
        </button>
        <div class="status-msg" id="status-msg"></div>
    </div>

    <!-- ── ARROW 2 ── -->
    <div class="flow-col">
        <div class="flow-arrow-wrap">
            <div class="flow-arrow-sym">➜</div>
            <div class="flow-arrow-label">OUTPUT</div>
        </div>
    </div>

    <!-- ── RIGHT: OUTPUTS ── -->
    <div class="panel panel-outputs">
        <div class="panel-header">
            <span class="panel-title" style="color:var(--green);">🚀 NETWORK OUTPUTS</span>
            <span style="font-size:0.65rem;color:var(--text3);font-family:'Orbitron',sans-serif;">CONFIRMED</span>
        </div>

        <div class="panel-explain" style="border-left-color:var(--green);">
            เมื่อ Transaction ถูก Miner ยืนยันใน Block — Bitcoin จะถูกส่งไปยัง output ทั้งหมด ผู้รับได้ UTXO ใหม่ คุณได้รับ Change กลับมาเป็น UTXO ใหม่ในกระเป๋าของคุณ
        </div>

        <!-- Recipient Output -->
        <div class="output-explain">
            <span style="color:var(--orange);">📤 ส่งให้ผู้รับ</span> — กลายเป็น UTXO ใหม่ในกระเป๋าของผู้รับทันที
        </div>
        <div class="output-box" id="out-recipient" style="border-color:rgba(255,140,0,0.4);">
            <span style="color:var(--text3);font-size:0.85rem;">⏳ รอ Transaction...</span>
        </div>

        <!-- Change Output -->
        <div class="output-explain">
            <span style="color:var(--green);">🔄 เงินทอน (Change)</span> — กลับมาเป็น UTXO ใหม่ในกระเป๋าของคุณ
        </div>
        <div class="output-box" id="out-change" style="border-color:rgba(57,255,20,0.3);">
            <span style="color:var(--text3);font-size:0.85rem;">⏳ รอ Transaction...</span>
        </div>

        <!-- Fee Paid -->
        <div style="background:rgba(255,56,96,0.06);border:1px solid rgba(255,56,96,0.2);border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:0.78rem;color:var(--text3);">⛏️ Fee จ่ายให้ Miner:</span>
                <span id="fee-paid-display" class="mono" style="color:var(--red);font-size:0.85rem;">0 sat</span>
            </div>
            <div style="font-size:0.72rem;color:var(--text3);margin-top:4px;">Input − Output − Change = Fee (หายไปเลย ไม่ได้รับทอน)</div>
        </div>

        <!-- TX Log -->
        <div class="tx-log" id="tx-log">
            <div class="tx-log-title">TRANSACTION LOG</div>
            <div id="tx-log-entries">
                <div class="tx-log-entry"><span class="log-time">SYSTEM</span><span class="log-msg" style="color:var(--text3);">Simulator ready — สร้าง Transaction แรกได้เลย</span></div>
            </div>
        </div>
    </div>

</div><!-- end stage -->

<!-- ════ EXPLAINER SECTION ════ -->
<!-- ════ SECTION 3 ════ -->
<div class="section-div"><div class="line"></div><div class="text">§ 03 · ทำความเข้าใจเพิ่มเติม</div><div class="line"></div></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
    <div class="callout callout-btc" style="margin:0;">
        <span class="callout-icon" style="color:var(--btc);">🏦</span>
        <div>
            <strong style="color:var(--btc);">ทำไม Bitcoin ถึงใช้ UTXO แทน Account?</strong><br>
            <span style="color:var(--text2);font-size:0.85rem;">ระบบ Account (เช่น Ethereum) เก็บ "ยอดเงิน" ตรงๆ — ง่าย แต่ต้องตรวจสอบประวัติทั้งหมด ระบบ UTXO ของ Bitcoin ทุก node ตรวจสอบได้อิสระ แบ่ง transaction คู่ขนานได้ และป้องกัน double-spend ได้ดีกว่า</span>
        </div>
    </div>
    <div class="callout callout-purple" style="margin:0;">
        <span class="callout-icon" style="color:var(--purple);">⚡</span>
        <div>
            <strong style="color:var(--purple);">UTXO Consolidation คืออะไร?</strong><br>
            <span style="color:var(--text2);font-size:0.85rem;">เมื่อ fee ต่ำ นักพัฒนา Bitcoin มักรวม UTXO หลายก้อนเล็กๆ ให้เป็นก้อนใหญ่ก้อนเดียว เพื่อลด fee ในอนาคต เพราะ fee คิดจากขนาด transaction (bytes) ไม่ใช่จำนวน BTC</span>
        </div>
    </div>
    <div class="callout callout-green" style="margin:0;">
        <span class="callout-icon" style="color:var(--green);">🔍</span>
        <div>
            <strong style="color:var(--green);">ทุกคนเห็น UTXO ของคุณได้?</strong><br>
            <span style="color:var(--text2);font-size:0.85rem;">ใช่ — Bitcoin เป็น public ledger ทุก node บนโลกเก็บ UTXO Set ไว้ เห็น address และยอด แต่ไม่รู้ว่า address นั้นเป็นของใคร จนกว่าคุณจะเปิดเผยตัวเอง (Pseudonymous ไม่ใช่ Anonymous)</span>
        </div>
    </div>
    <div class="callout callout-red" style="margin:0;">
        <span class="callout-icon" style="color:var(--red);">⚠️</span>
        <div>
            <strong style="color:var(--red);">Dust UTXO — ปัญหาที่ต้องระวัง</strong><br>
            <span style="color:var(--text2);font-size:0.85rem;">UTXO ที่มีมูลค่าน้อยมาก (เช่น 546 sats) เรียก "dust" — fee ในการส่งมันอาจแพงกว่าตัวมูลค่าเอง ทำให้ไม่คุ้มที่จะใช้ และ "ติดอยู่" ในกระเป๋าไปเลย</span>
        </div>
    </div>
</div>

</div><!-- end page-wrap -->

<footer>
    <div style="margin-bottom:4px;">© <?php echo date("Y"); ?> Chollatis Bitcoiner · <em>Don't Trust, Verify.</em></div>
    <div style="opacity:0.5;">UTXO Model · Bitcoin Protocol · PHP <?php echo phpversion(); ?></div>
</footer>

<script>
// ─── State ─────────────────────────────────────────────────────────────────
let utxoCounter = 5;
let utxoData = [
    { id:1, amount:0.15000000, isNew:false, txid:'a3f8...1b2c' },
    { id:2, amount:1.25000000, isNew:false, txid:'7e4d...9f0a' },
    { id:3, amount:0.50000000, isNew:false, txid:'c1b7...3d8e' },
    { id:4, amount:0.15000000, isNew:false, txid:'f5a2...6c1d' }
];
let txCount = 0;

renderUTXOs();
updatePreview();

// ─── Fake TXID generator ────────────────────────────────────────────────────
function fakeTxid() {
    const hex = '0123456789abcdef';
    let s = '';
    for(let i=0;i<8;i++) s += hex[Math.floor(Math.random()*16)];
    return s + '...' + Array.from({length:4},()=>hex[Math.floor(Math.random()*16)]).join('');
}

// ─── Format ────────────────────────────────────────────────────────────────
function fmtBTC(n)  { return parseFloat(n.toFixed(8)) + ' BTC'; }
function fmtSats(n) { return Math.round(n * 1e8).toLocaleString() + ' sat'; }
function satsNum(n) { return Math.round(n * 1e8); }

// ─── TX Log ─────────────────────────────────────────────────────────────────
function addLog(msg, type='') {
    const el = document.getElementById('tx-log-entries');
    const now = new Date();
    const ts  = now.toTimeString().substring(0,8);
    const entry = document.createElement('div');
    entry.className = 'tx-log-entry' + (type ? ' log-'+type : '');
    entry.innerHTML = `<span class="log-time">${ts}</span><span class="log-msg">${msg}</span>`;
    el.insertBefore(entry, el.firstChild);
    // Keep only 20 entries
    while(el.children.length > 20) el.removeChild(el.lastChild);
}

// ─── Render UTXOs ──────────────────────────────────────────────────────────
function renderUTXOs() {
    const container = document.getElementById('utxo-container');
    container.innerHTML = '';
    let total = 0;

    utxoData.forEach(utxo => {
        total += utxo.amount;
        const div = document.createElement('div');
        div.className = 'utxo-block' + (utxo.isNew ? ' new-entry' : '');
        div.id = `block-${utxo.id}`;
        const satVal = satsNum(utxo.amount).toLocaleString();
        div.innerHTML = `
            <div class="utxo-block-row">
                <span class="utxo-id">UTXO #${utxo.id}</span>
                <div class="utxo-amount-wrap">
                    <input type="number" class="utxo-input" value="${utxo.amount.toFixed(8)}"
                        onchange="updateAmount(${utxo.id},this.value)"
                        oninput="updateAmount(${utxo.id},this.value)"
                        step="0.01" min="0">
                    <span class="utxo-btc-label">BTC</span>
                </div>
            </div>
            <div class="utxo-txid">txid: ${utxo.txid}</div>
            <div class="utxo-sats">${satVal} satoshi</div>`;
        container.appendChild(div);
    });

    const totalSats = satsNum(total);
    document.getElementById('total-balance').innerText = total.toFixed(8) + ' BTC';
    document.getElementById('stat-count').innerText    = utxoData.length;
    document.getElementById('stat-total-sats').innerText = totalSats.toLocaleString();
    document.getElementById('stat-avg').innerText = utxoData.length
        ? Math.round(totalSats / utxoData.length).toLocaleString() : '0';
}

// ─── Update individual amount ───────────────────────────────────────────────
function updateAmount(id, val) {
    const v = parseFloat(val);
    if(isNaN(v) || v < 0) return;
    const t = utxoData.find(u => u.id === id);
    if(t) { t.amount = v; renderUTXOs(); updatePreview(); }
}

// ─── Add UTXO ───────────────────────────────────────────────────────────────
function addManualUTXO() {
    const amt = parseFloat((Math.random() * 1.5 + 0.05).toFixed(8));
    utxoData.push({ id:utxoCounter++, amount:amt, isNew:true, txid:fakeTxid() });
    renderUTXOs();
    updatePreview();
    addLog(`รับ UTXO ใหม่: +${fmtBTC(amt)}`, 'success');
}

// ─── Quick fill ─────────────────────────────────────────────────────────────
function quickFill(amt) {
    document.getElementById('send-amount').value = amt.toFixed(8);
    document.querySelectorAll('.coin-opt').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    updatePreview();
}
function quickFillAll() {
    const total = utxoData.reduce((a,u) => a + u.amount, 0);
    const fee   = (parseInt(document.getElementById('miner-fee').value) || 0) / 1e8;
    const send  = Math.max(0, total - fee);
    document.getElementById('send-amount').value = send.toFixed(8);
    document.querySelectorAll('.coin-opt').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    updatePreview();
}

// ─── Fee Slider Logic ─────────────────────────────────────────────────────────
const FEE_PRESETS = [
    { max:0,     label:'ไม่มี fee',     badge:'ไม่มี fee',    badgeColor:'var(--text3)', color:'var(--text3)' },
    { max:200,   label:'ช้ามาก',        badge:'⚠️ ช้ามาก',   badgeColor:'var(--red)',    color:'var(--red)' },
    { max:1000,  label:'ปานกลาง',       badge:'🟡 ปานกลาง',  badgeColor:'var(--yellow)', color:'var(--yellow)' },
    { max:3000,  label:'ดี',            badge:'✅ ดี',        badgeColor:'var(--green)',  color:'var(--green)' },
    { max:10000, label:'เร็วมาก',       badge:'⚡ เร็วมาก',   badgeColor:'var(--blue)',   color:'var(--blue)' },
    { max:99999, label:'ด่วน Next Block',badge:'🚀 ด่วน',     badgeColor:'var(--orange)', color:'var(--orange)' },
];

function getFeeLevel(sats) {
    if(sats === 0) return FEE_PRESETS[0];
    if(sats <= 200) return FEE_PRESETS[1];
    if(sats <= 1000) return FEE_PRESETS[2];
    if(sats <= 3000) return FEE_PRESETS[3];
    if(sats <= 10000) return FEE_PRESETS[4];
    return FEE_PRESETS[5];
}

function onFeeSlide(val) {
    const sats = parseInt(val) || 0;
    setFee(sats, true);
}

function setFee(sats, fromSlider=false) {
    sats = Math.max(0, Math.min(20000, sats));
    // Sync hidden input and slider
    document.getElementById('miner-fee').value = sats;
    if(!fromSlider) document.getElementById('fee-slider').value = sats;

    // Update slider track fill gradient
    const pct = (sats / 20000) * 100;
    const level = getFeeLevel(sats);
    document.getElementById('fee-slider').style.background =
        `linear-gradient(to right,${level.color} ${pct}%,rgba(255,255,255,0.06) ${pct}%)`;

    // Update thumb color via CSS variable trick
    document.getElementById('fee-slider').style.setProperty('--thumb-color', level.color);

    // Labels
    document.getElementById('fee-slider-val').innerText = sats.toLocaleString() + ' sat';
    document.getElementById('fee-slider-val').style.color = level.color;
    document.getElementById('fee-slider-btc').innerText = '≈ ' + (sats/1e8).toFixed(8) + ' BTC';
    document.getElementById('fee-speed-badge').innerText = level.badge;
    document.getElementById('fee-speed-badge').style.borderColor = level.badgeColor;
    document.getElementById('fee-speed-badge').style.color = level.badgeColor;
    document.getElementById('fee-speed-badge').style.background = level.badgeColor.replace(')',',0.1)').replace('var(','rgba(').replace('--orange','255,140,0').replace('--green','57,255,20').replace('--blue','0,243,255').replace('--yellow','251,191,36').replace('--red','255,56,96').replace('--text3','61,79,99');

    // Active preset buttons
    document.querySelectorAll('.fee-preset-btn').forEach(b => b.classList.remove('active'));
    const presetMap = {200:0, 1000:1, 3000:2, 10000:3};
    if(presetMap[sats] !== undefined) {
        document.querySelectorAll('.fee-preset-btn')[presetMap[sats]].classList.add('active');
    }

    updateFeeMeter(sats);
    updatePreview();
}

// ─── Update Preview ─────────────────────────────────────────────────────────
function updatePreview() {
    document.querySelectorAll('.utxo-block').forEach(el => {
        el.classList.remove('locked');
        const badge = el.querySelector('.lock-badge');
        if(badge) badge.remove();
    });
    document.getElementById('status-msg').innerText = '';
    document.getElementById('tx-summary').style.display = 'none';

    const amtToSend = parseFloat(document.getElementById('send-amount').value) || 0;
    const feeSats   = parseInt(document.getElementById('miner-fee').value) || 0;
    const feeBTC    = feeSats / 1e8;

    // Fee meter
    updateFeeMeter(feeSats);

    if(amtToSend <= 0) return;

    const totalNeeded = amtToSend + feeBTC;
    let accumulated = 0, possible = false, selectedCount = 0;

    for(let utxo of utxoData) {
        accumulated += utxo.amount;
        selectedCount++;
        const block = document.getElementById(`block-${utxo.id}`);
        if(block && !block.classList.contains('spent')) {
            block.classList.add('locked');
            const badge = document.createElement('div');
            badge.className = 'lock-badge';
            badge.textContent = '🔒 SELECTED';
            block.appendChild(badge);
        }
        if(accumulated >= totalNeeded) { possible = true; break; }
    }

    const change = accumulated - totalNeeded;

    if(!possible) {
        document.getElementById('status-msg').innerText =
            `⚠️ ยอดไม่พอ — ต้องการ ${fmtBTC(totalNeeded)} แต่มีแค่ ${fmtBTC(accumulated)}`;
        return;
    }

    // Show tx summary
    const sumEl = document.getElementById('tx-summary');
    sumEl.style.display = 'block';
    document.getElementById('sum-utxo-count').innerText = selectedCount + ' ก้อน';
    document.getElementById('sum-input').innerText      = fmtBTC(accumulated);
    document.getElementById('sum-send').innerText       = fmtBTC(amtToSend);
    document.getElementById('sum-change').innerText     = change > 1e-8 ? fmtBTC(change) : 'ไม่มี (พอดี)';
    document.getElementById('sum-fee').innerText        = feeSats.toLocaleString() + ' sat';
}

// ─── Fee Meter ──────────────────────────────────────────────────────────────
function updateFeeMeter(sats) {
    const fill  = document.getElementById('fee-meter-fill');
    const label = document.getElementById('fee-label');
    const pct   = Math.min((sats / 20000) * 100, 100);
    fill.style.width = pct + '%';
    if(sats === 0)       { fill.style.background='var(--text3)'; fill.style.width='1%'; label.innerText='ไม่มี fee — Miner จะไม่สนใจ TX นี้'; label.style.color='var(--text3)'; }
    else if(sats <= 200) { fill.style.background='var(--red)';    label.innerText='⚠️ ต่ำมาก — อาจรอนานหลายวันหรือไม่ได้รับการยืนยัน'; label.style.color='var(--red)'; }
    else if(sats < 1000) { fill.style.background='var(--yellow)'; label.innerText='🟡 ปานกลาง — รอประมาณ 1-6 blocks';  label.style.color='var(--yellow)'; }
    else if(sats < 3000) { fill.style.background='var(--green)';  label.innerText='✅ ดี — น่าจะได้รับการยืนยันภายใน 1-2 blocks'; label.style.color='var(--green)'; }
    else if(sats < 10000){ fill.style.background='var(--blue)';   label.innerText='⚡ เร็วมาก — ยืนยันใน Block ถัดไปเกือบแน่นอน'; label.style.color='var(--blue)'; }
    else                 { fill.style.background='var(--orange)'; label.innerText='🚀 ด่วนสูงสุด — Next Block ทุก Miner แย่งยืนยัน TX นี้'; label.style.color='var(--orange)'; }
}

// ─── Execute Transaction ────────────────────────────────────────────────────
function executeTx() {
    const amtInput  = document.getElementById('send-amount');
    const feeInput  = document.getElementById('miner-fee');
    const statusMsg = document.getElementById('status-msg');
    const btn       = document.getElementById('exec-btn');
    const reactor   = document.getElementById('reactor');
    const rText     = document.getElementById('reactor-text');

    const amtToSend = parseFloat(amtInput.value);
    const feeSats   = parseInt(feeInput.value) || 0;
    const feeBTC    = feeSats / 1e8;

    if(isNaN(amtToSend) || amtToSend <= 0) {
        statusMsg.innerText = '⚠️ กรุณากรอกจำนวน BTC ที่ต้องการส่ง';
        return;
    }

    const totalNeeded = amtToSend + feeBTC;
    let selectedUTXOs = [], accumulated = 0;

    for(let u of utxoData) {
        selectedUTXOs.push(u);
        accumulated += u.amount;
        if(accumulated >= totalNeeded) break;
    }

    if(accumulated < totalNeeded) {
        statusMsg.innerText = `⚠️ ยอดไม่พอ — ขาด ${fmtBTC(totalNeeded - accumulated)}`;
        return;
    }

    const changeAmt = accumulated - totalNeeded;
    txCount++;

    // Reset new flags
    utxoData.forEach(u => u.isNew = false);

    // Lock UI
    btn.disabled = true;
    document.getElementById('tx-summary').style.display = 'none';

    // Mark UTXOs as spent
    selectedUTXOs.forEach(u => {
        const block = document.getElementById(`block-${u.id}`);
        if(block) {
            block.classList.remove('locked','new-entry');
            block.querySelectorAll('.lock-badge').forEach(b=>b.remove());
            block.classList.add('spent');
            block.querySelector('input').disabled = true;
            const stamp = document.createElement('div');
            stamp.className = 'spent-stamp';
            stamp.textContent = 'SPENT ❌';
            block.appendChild(stamp);
        }
    });

    addLog(`TX #${txCount} broadcast: ส่ง ${fmtBTC(amtToSend)} + fee ${feeSats} sat`, 'warning');

    // Activate reactor
    reactor.classList.add('active');
    rText.style.display = 'block';

    // Clear outputs
    document.getElementById('out-recipient').innerHTML = '<span style="color:var(--text3);font-size:0.85rem;">⛏️ Miner กำลังยืนยัน...</span>';
    document.getElementById('out-change').innerHTML    = '<span style="color:var(--text3);font-size:0.85rem;">⛏️ Miner กำลังยืนยัน...</span>';
    document.getElementById('fee-paid-display').innerText = feeSats.toLocaleString() + ' sat';

    // Mining animation (3s)
    setTimeout(() => {
        // Remove spent UTXOs from state
        utxoData = utxoData.filter(u => !selectedUTXOs.includes(u));
        // Fade-out animation
        selectedUTXOs.forEach(u => {
            const block = document.getElementById(`block-${u.id}`);
            if(block) block.classList.add('fade-out');
        });

        reactor.classList.remove('active');
        rText.style.display = 'none';

        setTimeout(() => {
            // Recipient output
            const recipientEl = document.getElementById('out-recipient');
            recipientEl.innerHTML = `
                <div style="color:var(--orange);font-weight:700;font-size:1.3rem;">${fmtBTC(amtToSend)}</div>
                <div style="font-size:0.72rem;color:var(--text3);margin-top:4px;">TXID: ${fakeTxid()}</div>
                <div style="font-size:0.72rem;color:var(--text3);">✅ UTXO ใหม่ในกระเป๋าผู้รับ</div>`;
            recipientEl.classList.add('lit');
            recipientEl.style.borderColor = 'var(--orange)';

            // Change output
            const changeEl = document.getElementById('out-change');
            if(changeAmt > 1e-8) {
                const newUTXO = { id:utxoCounter++, amount:changeAmt, isNew:true, txid:fakeTxid() };
                utxoData.push(newUTXO);
                changeEl.innerHTML = `
                    <div style="color:var(--green);font-weight:700;font-size:1.3rem;">${fmtBTC(changeAmt)}</div>
                    <div style="font-size:0.72rem;color:var(--text3);margin-top:4px;">UTXO #${newUTXO.id} — เพิ่มเข้ากระเป๋าของคุณ</div>
                    <div style="font-size:0.72rem;color:var(--text3);">✅ Change กลับมาเป็น UTXO ใหม่</div>`;
                changeEl.style.borderColor = 'var(--green)';
                addLog(`Change: +${fmtBTC(changeAmt)} → UTXO #${newUTXO.id}`, 'success');
            } else {
                changeEl.innerHTML = `<div style="color:var(--green);font-size:0.9rem;">ไม่มีเงินทอน (ใช้ UTXO พอดี)</div>`;
                changeEl.style.borderColor = 'var(--green)';
            }
            changeEl.classList.add('lit');

            addLog(`TX #${txCount} ✅ Confirmed — fee: ${feeSats.toLocaleString()} sat`, 'success');

            renderUTXOs();
            amtInput.value = '';
            document.querySelectorAll('.coin-opt').forEach(b => b.classList.remove('active'));
            btn.disabled = false;
            updatePreview();

            // Dim outputs after 4s
            setTimeout(() => {
                recipientEl.classList.remove('lit');
                changeEl.classList.remove('lit');
                recipientEl.style.borderColor = 'rgba(255,140,0,0.4)';
                changeEl.style.borderColor    = 'rgba(57,255,20,0.3)';
            }, 4000);

        }, 400);
    }, 3000);
}
</script>
</body>
</html>