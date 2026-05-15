<?php
// Trading Journal - Self-Learning Edition
// Single-file PHP app - serves HTML/JS only (data stored in localStorage)
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>

<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>⚡ TRADING JOURNAL — Self-Learning Edition</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'><rect width='64' height='64' rx='12' fill='%23f7931a'/><text x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='42' font-family='Arial'>%E2%82%BF</text></svg>">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
:root {
  --bg-deep:     #020408;
  --bg-card:     #060d14;
  --bg-input:    #0a1520;
  --neon-orange: #f7931a;
  --neon-cyan:   #00f5ff;
  --neon-green:  #00ff88;
  --neon-red:    #ff2d55;
  --neon-purple: #bf5fff;
  --text-primary:#e8f4f8;
  --text-muted:  #4a6670;
  --border:      #0f2535;
  --border-glow: #1a3a4a;
  --grid-line:   rgba(0,245,255,0.04);
}

/* ─── LIGHT THEME ────────────────────────── */
[data-theme="light"] {
  --bg-deep:     #f0f4f8;
  --bg-card:     #ffffff;
  --bg-input:    #e8eef5;
  --neon-orange: #d4720a;
  --neon-cyan:   #0088aa;
  --neon-green:  #007744;
  --neon-red:    #cc1133;
  --neon-purple: #8833cc;
  --text-primary:#1a2a35;
  --text-muted:  #607080;
  --border:      #c8d8e4;
  --border-glow: #a0c0d0;
  --grid-line:   rgba(0,136,170,0.06);
}

[data-theme="light"] body::before {
  background-image:
    linear-gradient(var(--grid-line) 1px, transparent 1px),
    linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
}

[data-theme="light"] body::after {
  background: radial-gradient(ellipse, rgba(212,114,10,0.04) 0%, transparent 70%);
}

[data-theme="light"] .balance-card {
  background: linear-gradient(135deg, rgba(136,51,204,0.06), rgba(212,114,10,0.04));
  border-color: rgba(136,51,204,0.25);
}

[data-theme="light"] .balance-card-display {
  text-shadow: none;
}

[data-theme="light"] .header,
[data-theme="light"] .tabs,
[data-theme="light"] .footer {
  background: rgba(240,244,248,0.97);
  border-color: var(--border);
}

[data-theme="light"] .form-select option {
  background: #ffffff;
  color: #1a2a35;
}

[data-theme="light"] .green { text-shadow: none; }
[data-theme="light"] .red   { text-shadow: none; }
[data-theme="light"] .orange{ text-shadow: none; }
[data-theme="light"] .cyan  { text-shadow: none; }
[data-theme="light"] .purple{ text-shadow: none; }

[data-theme="light"] .card::before {
  background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
  opacity: 0.2;
}

[data-theme="light"] .btc-logo {
  box-shadow: 0 0 16px rgba(212,114,10,0.4);
}

[data-theme="light"] .brand-text h1 {
  text-shadow: none;
}

/* ─── THEME TOGGLE ───────────────────────── */
.theme-toggle-btn {
  padding: 9px 16px;
  border: 1px solid var(--border-glow);
  border-radius: 20px;
  background: var(--bg-input);
  color: var(--text-muted);
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.25s;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
}

.theme-toggle-btn:hover {
  border-color: var(--neon-orange);
  color: var(--neon-orange);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: var(--bg-deep);
  color: var(--text-primary);
  font-family: 'Sarabun', sans-serif;
  font-size: 16px;
  min-height: 100vh;
  overflow-x: hidden;
  overflow-y: hidden; /* body itself doesn't scroll */
}

/* Grid Background */
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(var(--grid-line) 1px, transparent 1px),
    linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
  z-index: 0;
}

/* Ambient glow */
body::after {
  content: '';
  position: fixed;
  top: -200px; left: 50%;
  transform: translateX(-50%);
  width: 800px; height: 400px;
  background: radial-gradient(ellipse, rgba(247,147,26,0.06) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}

/* ─── HEADER ─────────────────────────────── */
.header {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 100;
  padding: 16px 32px;
  border-bottom: 1px solid var(--border);
  background: rgba(2,4,8,0.97);
  backdrop-filter: blur(20px);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.header-brand {
  display: flex;
  align-items: center;
  gap: 16px;
}

.btc-logo {
  width: 44px; height: 44px;
  background: linear-gradient(135deg, var(--neon-orange), #e8720c);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
  box-shadow: 0 0 20px rgba(247,147,26,0.5), 0 0 40px rgba(247,147,26,0.2);
  animation: pulse-btc 3s ease-in-out infinite;
  flex-shrink: 0;
}

@keyframes pulse-btc {
  0%,100% { box-shadow: 0 0 20px rgba(247,147,26,0.5), 0 0 40px rgba(247,147,26,0.2); }
  50%      { box-shadow: 0 0 30px rgba(247,147,26,0.8), 0 0 60px rgba(247,147,26,0.3); }
}

.brand-text h1 {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 18px;
  font-weight: 900;
  color: var(--neon-orange);
  letter-spacing: 3px;
  text-shadow: 0 0 20px rgba(247,147,26,0.6);
  line-height: 1;
}

.brand-text p {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 2px;
  margin-top: 3px;
}

.brand-text .subtitle-th {
  font-family: 'Sarabun', sans-serif;
  font-size: 13px;
  color: rgba(74,102,112,0.9);
  letter-spacing: 0.5px;
  margin-top: 2px;
}

.header-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

/* ─── TABS ───────────────────────────────── */
.tabs {
  position: fixed;
  top: var(--header-h);
  left: 0; right: 0;
  z-index: 99;
  display: flex;
  gap: 0;
  padding: 0 32px;
  background: rgba(2,4,8,0.97);
  border-bottom: 1px solid var(--border);
  overflow-x: auto;
  backdrop-filter: blur(20px);
  scrollbar-width: none;
}
.tabs::-webkit-scrollbar { display: none; }

.tab {
  padding: 15px 26px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1.5px;
  color: var(--text-muted);
  cursor: pointer;
  border: none;
  background: none;
  border-bottom: 2px solid transparent;
  white-space: nowrap;
  transition: all 0.3s;
}

.tab:hover { color: var(--text-primary); }

.tab.active {
  color: var(--neon-cyan);
  border-bottom-color: var(--neon-cyan);
  text-shadow: 0 0 12px rgba(0,245,255,0.6);
}

/* CLEAR ALL tab — danger style, never "active" */
.tab.tab-danger {
  color: rgba(255,45,85,0.5);
  margin-left: auto;
}
.tab.tab-danger:hover {
  color: var(--neon-red);
  text-shadow: 0 0 10px rgba(255,45,85,0.5);
}

/* ─── BALANCE CARD (in Dashboard) ───────────── */
.balance-card {
  background: linear-gradient(135deg, rgba(191,95,255,0.08), rgba(247,147,26,0.05));
  border: 1px solid rgba(191,95,255,0.3);
  border-radius: 10px;
  padding: 20px 28px;
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
  margin-bottom: 24px;
  position: relative;
  overflow: hidden;
}

.balance-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--neon-purple), var(--neon-orange), var(--neon-purple));
  opacity: 0.6;
}

.balance-card-icon {
  font-size: 32px;
  flex-shrink: 0;
}

.balance-card-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-purple);
  letter-spacing: 2px;
  margin-bottom: 6px;
}

.balance-card-row {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.balance-card-input {
  background: var(--bg-input);
  border: 1px solid rgba(191,95,255,0.3);
  border-radius: 8px;
  padding: 8px 14px;
  color: var(--text-primary);
  font-family: 'Space Grotesk', sans-serif;
  font-size: 16px;
  font-weight: 700;
  width: 160px;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.balance-card-input:focus {
  outline: none;
  border-color: var(--neon-purple);
  box-shadow: 0 0 0 3px rgba(191,95,255,0.15), 0 0 16px rgba(191,95,255,0.2);
}

.balance-card-select {
  background: var(--bg-input);
  border: 1px solid rgba(191,95,255,0.3);
  border-radius: 8px;
  padding: 8px 12px;
  color: var(--text-primary);
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  cursor: pointer;
  transition: border-color 0.2s;
}

.balance-card-select:focus { outline: none; border-color: var(--neon-purple); }
.balance-card-select option { background: var(--bg-deep); }

.balance-card-display {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 22px;
  font-weight: 900;
  color: var(--neon-purple);
  text-shadow: 0 0 16px rgba(191,95,255,0.5);
}

.balance-card-fx {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 1px;
  margin-top: 6px;
}

.balance-pnl-tag {
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  padding: 5px 12px;
  border-radius: 20px;
  background: rgba(0,255,136,0.08);
  border: 1px solid rgba(0,255,136,0.25);
  color: var(--neon-green);
  white-space: nowrap;
}

.balance-pnl-tag.loss {
  background: rgba(255,45,85,0.08);
  border-color: rgba(255,45,85,0.25);
  color: var(--neon-red);
}

/* ─── MAIN LAYOUT ────────────────────────── */
:root {
  --header-h: 73px;
  --tabs-h:   47px;
  --chrome-h: 120px;
}

.main {
  position: fixed;
  top: var(--chrome-h);
  left: 0; right: 0; bottom: 0;
  overflow-y: auto;
  overflow-x: hidden;
  z-index: 10;
}

.main-inner {
  max-width: 1400px;
  margin: 0 auto;
  padding: 28px 32px;
  min-height: 100%;
  overflow-x: hidden; /* ป้องกัน horizontal scroll จาก content ที่กว้างเกิน */
}

.panel { display: none; }
.panel.active { display: block; }

/* ─── CARDS ──────────────────────────────── */
.card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 24px;
  position: relative;
  overflow: hidden;
}

.card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
  opacity: 0.3;
}

.card-title {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 2.5px;
  color: var(--text-muted);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-title::before {
  content: '';
  width: 3px; height: 14px;
  background: var(--neon-cyan);
  border-radius: 2px;
  box-shadow: 0 0 8px var(--neon-cyan);
}

/* ─── STAT CARDS ─────────────────────────── */
.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
  margin-bottom: 28px;
}

.stat-card {
  background: var(--bg-card);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 20px;
  position: relative;
  overflow: hidden;
  transition: border-color 0.3s;
}

.stat-card:hover { border-color: var(--border-glow); }

.stat-card .accent-line {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 2px;
}

.stat-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 1.5px;
  margin-bottom: 8px;
}

.stat-value {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 24px;
  font-weight: 900;
  line-height: 1;
}

.stat-sub {
  font-size: 12px;
  color: var(--text-muted);
  margin-top: 6px;
  font-family: 'JetBrains Mono', monospace;
}

.green { color: var(--neon-green); text-shadow: 0 0 12px rgba(0,255,136,0.4); }
.red   { color: var(--neon-red);   text-shadow: 0 0 12px rgba(255,45,85,0.4); }
.orange{ color: var(--neon-orange);text-shadow: 0 0 12px rgba(247,147,26,0.4); }
.cyan  { color: var(--neon-cyan);  text-shadow: 0 0 12px rgba(0,245,255,0.4); }
.purple{ color: var(--neon-purple);text-shadow: 0 0 12px rgba(191,95,255,0.4); }

/* ─── FORM ───────────────────────────────── */
.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  min-width: 0;  /* ป้องกัน grid item overflow */
  overflow: hidden;
}

.form-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 1.5px;
}

.form-label .required { color: var(--neon-orange); }

.form-input, .form-select, .form-textarea {
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 11px 14px;
  color: var(--text-primary);
  font-family: 'Sarabun', sans-serif;
  font-size: 15px;
  font-weight: 600;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%;
  max-width: 100%;   /* ป้องกันล้นขอบ */
  min-width: 0;      /* ป้องกัน iOS intrinsic min-width */
  box-sizing: border-box;
  -webkit-appearance: none; /* override iOS default style สำหรับ date input */
  appearance: none;
}

/* คืน appearance สำหรับ select เพราะต้องการ dropdown arrow */
.form-select {
  -webkit-appearance: auto;
  appearance: auto;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--neon-cyan);
  box-shadow: 0 0 0 2px rgba(0,245,255,0.1), 0 0 12px rgba(0,245,255,0.15);
}

.form-select option { background: var(--bg-deep); }

.form-textarea {
  resize: vertical;
  min-height: 80px;
  line-height: 1.5;
}

/* Calculated fields */
.calc-field {
  background: rgba(0,245,255,0.04);
  border-color: rgba(0,245,255,0.2);
  color: var(--neon-cyan);
  font-family: 'JetBrains Mono', monospace;
  cursor: default;
}

/* ─── DIRECTION TOGGLE ───────────────────── */
.direction-toggle {
  display: flex;
  gap: 0;
  border-radius: 6px;
  overflow: hidden;
  border: 1px solid var(--border);
}

.dir-btn {
  flex: 1;
  padding: 11px;
  border: none;
  background: var(--bg-input);
  color: var(--text-muted);
  font-family: 'Space Grotesk', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1.5px;
  cursor: pointer;
  transition: all 0.2s;
}

.dir-btn.long.active  { background: rgba(0,255,136,0.15); color: var(--neon-green); }
.dir-btn.short.active { background: rgba(255,45,85,0.15);  color: var(--neon-red); }

/* ─── OUTCOME TOGGLE ─────────────────────── */
.outcome-toggle {
  display: flex;
  gap: 0;
  border-radius: 6px;
  overflow: hidden;
  border: 1px solid var(--border);
}

.out-btn {
  flex: 1;
  padding: 11px;
  border: none;
  background: var(--bg-input);
  color: var(--text-muted);
  font-family: 'Space Grotesk', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.2s;
}

.out-btn.win.active  { background: rgba(0,255,136,0.15); color: var(--neon-green); }
.out-btn.loss.active { background: rgba(255,45,85,0.15);  color: var(--neon-red); }
.out-btn.be.active   { background: rgba(0,245,255,0.10);  color: var(--neon-cyan); }

/* ─── BUTTONS ────────────────────────────── */
.btn {
  padding: 10px 20px;
  border: 1px solid;
  border-radius: 6px;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1.5px;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background: rgba(247,147,26,0.15);
  border-color: var(--neon-orange);
  color: var(--neon-orange);
}
.btn-primary:hover {
  background: rgba(247,147,26,0.25);
  box-shadow: 0 0 16px rgba(247,147,26,0.3);
}

.btn-cyan {
  background: rgba(0,245,255,0.08);
  border-color: var(--neon-cyan);
  color: var(--neon-cyan);
}
.btn-cyan:hover {
  background: rgba(0,245,255,0.15);
  box-shadow: 0 0 16px rgba(0,245,255,0.2);
}

.btn-green {
  background: rgba(0,255,136,0.08);
  border-color: var(--neon-green);
  color: var(--neon-green);
}
.btn-green:hover {
  background: rgba(0,255,136,0.15);
  box-shadow: 0 0 16px rgba(0,255,136,0.2);
}

.btn-red {
  background: rgba(255,45,85,0.08);
  border-color: var(--neon-red);
  color: var(--neon-red);
}
.btn-red:hover {
  background: rgba(255,45,85,0.15);
  box-shadow: 0 0 16px rgba(255,45,85,0.2);
}

.btn-home {
  background: rgba(0,245,255,0.08);
  border-color: var(--neon-cyan);
  color: var(--neon-cyan);
}
.btn-home:hover {
  background: rgba(0,245,255,0.15);
  box-shadow: 0 0 16px rgba(0,245,255,0.2);
}

.btn-export {
  background: rgba(0,255,136,0.08);
  border-color: var(--neon-green);
  color: var(--neon-green);
}
.btn-export:hover {
  background: rgba(0,255,136,0.15);
  box-shadow: 0 0 16px rgba(0,255,136,0.2);
}

.btn-import {
  background: rgba(191,95,255,0.08);
  border-color: var(--neon-purple);
  color: var(--neon-purple);
}
.btn-import:hover {
  background: rgba(191,95,255,0.15);
  box-shadow: 0 0 16px rgba(191,95,255,0.2);
}

.btn-sm {
  padding: 6px 14px;
  font-size: 10px;
}

/* ─── TABLE ──────────────────────────────── */
.table-wrap { overflow-x: auto; }

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

thead tr {
  border-bottom: 1px solid var(--border);
}

thead th {
  padding: 11px 14px;
  text-align: left;
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  letter-spacing: 1.5px;
  color: var(--text-muted);
  white-space: nowrap;
}

tbody tr {
  border-bottom: 1px solid rgba(15,37,53,0.5);
  transition: background 0.15s;
}

tbody tr:hover { background: rgba(0,245,255,0.03); }

tbody td {
  padding: 11px 14px;
  font-family: 'Sarabun', sans-serif;
  font-size: 14px;
  font-weight: 600;
  white-space: nowrap;
}

.badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 4px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1px;
}

.badge-long  { background: rgba(0,255,136,0.12); color: var(--neon-green); border: 1px solid rgba(0,255,136,0.3); }
.badge-short { background: rgba(255,45,85,0.12);  color: var(--neon-red);   border: 1px solid rgba(255,45,85,0.3); }
.badge-win   { background: rgba(0,255,136,0.10); color: var(--neon-green); }
.badge-loss  { background: rgba(255,45,85,0.10);  color: var(--neon-red); }
.badge-be    { background: rgba(0,245,255,0.08);  color: var(--neon-cyan); }
.badge-open  { background: rgba(247,147,26,0.10); color: var(--neon-orange); border: 1px solid rgba(247,147,26,0.25); }

/* ─── SUMMARY PERIOD TABS ────────────────── */
.period-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.period-btn {
  padding: 7px 18px;
  border: 1px solid var(--border);
  border-radius: 20px;
  background: none;
  color: var(--text-muted);
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.2s;
}

.period-btn.active {
  border-color: var(--neon-orange);
  color: var(--neon-orange);
  background: rgba(247,147,26,0.08);
}

/* ─── EQUITY CURVE ───────────────────────── */
canvas.equity-canvas {
  width: 100%;
  height: 100%;
}

/* ─── FILTER ROW ─────────────────────────── */
.filter-row {
  display: flex;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  align-items: flex-end;
}

.filter-row .form-input,
.filter-row .form-select {
  width: auto;
  min-width: 140px;
}

/* ─── EMPTY STATE ────────────────────────── */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-muted);
}

.empty-state .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.4; }
.empty-state p { font-family: 'JetBrains Mono', monospace; font-size: 12px; letter-spacing: 2px; }

/* ─── TOAST ──────────────────────────────── */
.toast {
  position: fixed;
  bottom: 32px; right: 32px;
  padding: 14px 24px;
  border-radius: 8px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  letter-spacing: 1px;
  z-index: 9999;
  transform: translateY(100px);
  opacity: 0;
  transition: all 0.3s;
  border: 1px solid;
  backdrop-filter: blur(20px);
}

.toast.show { transform: translateY(0); opacity: 1; }
.toast.success { background: rgba(0,255,136,0.12); border-color: var(--neon-green); color: var(--neon-green); }
.toast.error   { background: rgba(255,45,85,0.12);  border-color: var(--neon-red);   color: var(--neon-red); }
.toast.info    { background: rgba(0,245,255,0.10);  border-color: var(--neon-cyan);  color: var(--neon-cyan); }

/* ─── SECTION DIVIDER ────────────────────── */
.section-gap { margin-top: 24px; }

/* ─── PRICE STATUS BADGE ─────────────────── */
.price-status {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 6px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  min-height: 22px;
}

.price-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  border-radius: 4px;
  font-size: 11px;
  letter-spacing: 1px;
  border: 1px solid;
}

.price-badge.live {
  background: rgba(0,255,136,0.08);
  border-color: rgba(0,255,136,0.3);
  color: var(--neon-green);
}

.price-badge.history {
  background: rgba(0,245,255,0.06);
  border-color: rgba(0,245,255,0.2);
  color: var(--neon-cyan);
}

.price-badge.loading {
  background: rgba(247,147,26,0.06);
  border-color: rgba(247,147,26,0.2);
  color: var(--neon-orange);
  animation: blink-badge 1s ease-in-out infinite;
}

.price-badge.error {
  background: rgba(255,45,85,0.06);
  border-color: rgba(255,45,85,0.2);
  color: var(--neon-red);
}

@keyframes blink-badge {
  0%,100% { opacity: 1; }
  50%      { opacity: 0.4; }
}

.dot-live {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--neon-green);
  box-shadow: 0 0 6px var(--neon-green);
  animation: pulse-dot 1.5s ease-in-out infinite;
}

@keyframes pulse-dot {
  0%,100% { transform: scale(1); opacity: 1; }
  50%      { transform: scale(1.4); opacity: 0.6; }
}

/* Balance auto-fill highlight */
.balance-auto {
  border-color: rgba(191,95,255,0.4) !important;
  background: rgba(191,95,255,0.04) !important;
}

/* ─── FOOTER ─────────────────────────────── */
.footer {
  position: relative; z-index: 10;
  border-top: 1px solid var(--border);
  background: transparent;
  padding: 20px 32px;
  margin-top: 48px;
  text-align: center;
}

.footer-secure {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  color: var(--neon-green);
  letter-spacing: 2px;
  margin-bottom: 10px;
  text-shadow: 0 0 8px rgba(0,255,136,0.3);
}

.footer-secure::before {
  content: '🔒';
  font-size: 14px;
}

.footer-copy {
  font-family: 'Sarabun', monospace;
  font-size: 12px;
  color: var(--text-muted);
  letter-spacing: 0.5px;
  line-height: 1.8;
}

.footer-copy .verify {
  color: var(--neon-orange);
  font-style: italic;
}

.footer-copy .powered {
  color: rgba(74,102,112,0.6);
  font-size: 11px;
  margin-top: 4px;
}

/* ─── BALANCE BAR ────────────────────────── */
.balance-bar {
  position: relative; z-index: 10;
  background: rgba(6,13,20,0.98);
  border-bottom: 1px solid var(--border);
  padding: 10px 32px;
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

[data-theme="light"] .balance-bar {
  background: rgba(240,244,248,0.98);
}

.balance-bar-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  color: var(--text-muted);
  letter-spacing: 2px;
  white-space: nowrap;
}

.balance-bar-inputs {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.balance-bar-input {
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 6px 12px;
  color: var(--text-primary);
  font-family: 'Space Grotesk', sans-serif;
  font-size: 13px;
  font-weight: 700;
  width: 140px;
  transition: border-color 0.2s;
}

.balance-bar-input:focus {
  outline: none;
  border-color: var(--neon-purple);
  box-shadow: 0 0 0 2px rgba(191,95,255,0.15);
}

.balance-bar-select {
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 6px 10px;
  color: var(--text-primary);
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  cursor: pointer;
}

.balance-bar-select option { background: var(--bg-deep); }

.balance-bar-display {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 14px;
  font-weight: 900;
  color: var(--neon-purple);
  text-shadow: 0 0 12px rgba(191,95,255,0.4);
  white-space: nowrap;
}

.balance-bar-fx {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-muted);
  letter-spacing: 1px;
}

/* ─── TOOLTIP (BALLOON) SYSTEM ──────────── */
.tj-tooltip-host { position: relative; }

.tj-balloon {
  position: fixed;
  z-index: 99999;
  max-width: 320px;
  background: var(--bg-card);
  border: 1px solid var(--neon-cyan);
  border-radius: 10px;
  padding: 14px 16px;
  pointer-events: none;
  opacity: 0;
  transform: translateY(6px) scale(0.97);
  transition: opacity 0.18s ease, transform 0.18s ease;
  box-shadow: 0 0 24px rgba(0,245,255,0.12), 0 4px 32px rgba(0,0,0,0.5);
}

.tj-balloon.show {
  opacity: 1;
  transform: translateY(0) scale(1);
}

.tj-balloon::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  border-radius: 10px 10px 0 0;
  background: linear-gradient(90deg, var(--neon-cyan), var(--neon-purple));
}

.tj-balloon-title {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1.5px;
  color: var(--neon-cyan);
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.tj-balloon-body {
  font-family: 'Sarabun', sans-serif;
  font-size: 13px;
  color: var(--text-primary);
  line-height: 1.65;
}

.tj-balloon-tip {
  margin-top: 10px;
  padding-top: 10px;
  border-top: 1px solid var(--border);
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-orange);
}

[data-theme="light"] .tj-balloon {
  box-shadow: 0 0 16px rgba(0,136,170,0.1), 0 4px 24px rgba(0,0,0,0.12);
}

/* ─── EDIT MODE BANNER ───────────────────── */
.edit-mode-banner {
  background: linear-gradient(90deg, rgba(247,147,26,0.12), rgba(191,95,255,0.08));
  border: 1px solid rgba(247,147,26,0.4);
  border-radius: 8px;
  padding: 12px 18px;
  margin-bottom: 16px;
  display: none;
  align-items: center;
  gap: 12px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  color: var(--neon-orange);
}

.edit-mode-banner.show { display: flex; }
.edit-mode-banner span { flex: 1; }

/* ─── BALANCE NET DISPLAY ────────────────── */
.balance-net-row {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 6px;
  flex-wrap: wrap;
}

.balance-net-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  color: var(--text-muted);
  letter-spacing: 1px;
}

.balance-net-value {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 18px;
  font-weight: 900;
}
/* ─── FORM SECTIONS ──────────────────────── */
.form-section {
  margin-bottom: 28px;
}

.form-section-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 16px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border);
}

.form-section-num {
  width: 26px; height: 26px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 11px;
  font-weight: 900;
  flex-shrink: 0;
}

.form-section-num.blue   { background: rgba(0,245,255,0.12); color: var(--neon-cyan);   border: 1px solid rgba(0,245,255,0.3); }
.form-section-num.orange { background: rgba(247,147,26,0.12); color: var(--neon-orange); border: 1px solid rgba(247,147,26,0.3); }
.form-section-num.green  { background: rgba(0,255,136,0.12);  color: var(--neon-green);  border: 1px solid rgba(0,255,136,0.3); }

.form-section-title {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  color: var(--text-primary);
}

.form-section-sub {
  font-family: 'Sarabun', sans-serif;
  font-size: 12px;
  color: var(--text-muted);
  margin-left: 4px;
}

/* uniform grid: 4 cols on wide, auto below */
.form-grid-4 {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.form-grid-3 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.form-grid-2 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}

@media (max-width: 1100px) {
  .form-grid-4 { grid-template-columns: repeat(2, 1fr); }
  .form-grid-3 { grid-template-columns: repeat(2, 1fr); }
}

/* tablet & mobile: 1 column เสมอ */
@media (max-width: 1024px) {
  .form-grid-4,
  .form-grid-3,
  .form-grid-2 { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
  .form-grid-4, .form-grid-3, .form-grid-2 { grid-template-columns: 1fr; }
}

/* calc section cards */
.calc-card {
  background: rgba(0,245,255,0.03);
  border: 1px solid rgba(0,245,255,0.12);
  border-radius: 8px;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-height: 80px;
  justify-content: center;
}

.calc-card.orange-tint {
  background: rgba(247,147,26,0.03);
  border-color: rgba(247,147,26,0.15);
}

.calc-card.green-tint {
  background: rgba(0,255,136,0.03);
  border-color: rgba(0,255,136,0.12);
}

.calc-card.red-tint {
  background: rgba(255,45,85,0.03);
  border-color: rgba(255,45,85,0.12);
}

.calc-card-label {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  color: var(--text-muted);
  letter-spacing: 1.5px;
  text-transform: uppercase;
}

.calc-card-value {
  font-family: 'Space Grotesk', sans-serif;
  font-size: 17px;
  font-weight: 900;
  color: var(--neon-cyan);
  line-height: 1.2;
  word-break: break-all;
}

.calc-card-value.orange { color: var(--neon-orange); }
.calc-card-value.green  { color: var(--neon-green);  }
.calc-card-value.red    { color: var(--neon-red);    }

.calc-card-sub {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  color: var(--text-muted);
  line-height: 1.4;
}

/* risk display pill */
.risk-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255,45,85,0.08);
  border: 1px solid rgba(255,45,85,0.2);
  border-radius: 20px;
  padding: 4px 12px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-red);
  margin-top: 6px;
  flex-wrap: wrap;
}

/* ─── EQUITY TOOLTIP ─────────────────────── */
.equity-tooltip {
  position: absolute;
  pointer-events: none;
  background: var(--bg-card);
  border: 1px solid var(--neon-cyan);
  border-radius: 8px;
  padding: 10px 14px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--text-primary);
  display: none;
  z-index: 100;
  min-width: 180px;
  box-shadow: 0 0 16px rgba(0,245,255,0.15);
  white-space: nowrap;
}
.equity-tooltip-date { color: var(--neon-cyan); font-size: 12px; margin-bottom: 4px; }
.equity-tooltip-pnl  { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
.equity-tooltip-detail { font-size: 10px; color: var(--text-muted); }

.equity-wrap {
  position: relative;
  height: 240px;
  margin-top: 16px;
}

/* ─── PRICE SUB-LABEL (THB under price fields) ── */
.price-sub {
  font-family: 'JetBrains Mono', monospace;
  font-size: 10px;
  color: var(--text-muted);
  margin-top: 3px;
  letter-spacing: 0.5px;
  min-height: 14px;
}

/* ─── OPEN POSITION STATUS ───────────────── */
.open-position-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(247,147,26,0.08);
  border: 1px solid rgba(247,147,26,0.3);
  border-radius: 4px;
  padding: 3px 10px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-orange);
  animation: blink-badge 2s ease-in-out infinite;
}

/* ─── FEE INDICATOR ─────────────────────── */
.fee-hint {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-orange);
  margin-top: 4px;
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.calc-field-fee {
  background: rgba(247,147,26,0.04);
  border-color: rgba(247,147,26,0.25) !important;
  color: var(--neon-orange) !important;
  font-family: 'JetBrains Mono', monospace;
  cursor: default;
}

/* ─── SHORT SWAP INDICATOR ───────────────── */
.short-swap-hint {
  font-family: 'JetBrains Mono', monospace;
  font-size: 11px;
  color: var(--neon-red);
  margin-top: 4px;
  display: none;
  align-items: center;
  gap: 5px;
}
.short-swap-hint.show { display: flex; }

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--bg-deep); }
::-webkit-scrollbar-thumb { background: var(--border-glow); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--neon-cyan); }

/* ─── YEAR/MONTH SELECT ──────────────────── */
#yearFilter, #monthFilter {
  background: var(--bg-input);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 7px 14px;
  color: var(--text-primary);
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
}

/* ─── RESPONSIVE ─────────────────────────── */
/* ─── MOBILE: ปลด fixed layout ใน portrait/small screens ─── */
/* Desktop (>1024px): fixed header+tabs, scrollable main         */
/* Mobile/Tablet (≤1024px): normal flow, ทั้งหน้า scroll       */
@media (max-width: 1024px) {
  body {
    overflow-y: auto; /* ทั้งหน้า scroll ได้ */
  }
  .header {
    position: relative; /* ไม่ fixed */
    top: auto; left: auto; right: auto;
  }
  .tabs {
    position: relative;
    top: auto !important; /* override inline style จาก updateLayout() */
    left: auto; right: auto;
  }
  .main {
    position: relative; /* ไม่ fixed */
    top: auto !important;
    left: auto; right: auto; bottom: auto;
    overflow-y: visible;
    overflow-x: hidden;
  }
}

@media (max-width: 768px) {
  .header { padding: 12px 16px; flex-wrap: wrap; }
  .header-actions { gap: 6px; }
  .btn { padding: 8px 14px; font-size: 10px; }
  .main-inner { padding: 16px; }
  .tabs { padding: 0 12px; }
  .tab { padding: 12px 16px; font-size: 10px; letter-spacing: 1px; }
  .stat-grid { grid-template-columns: repeat(2, 1fr); }
  .form-grid { grid-template-columns: 1fr; }
  .balance-card { flex-direction: column; align-items: flex-start; gap: 12px; }
  .balance-card-display { font-size: 18px; }
}

@media (max-width: 480px) {
  .header { padding: 10px 12px; }
  .header-actions { flex-wrap: wrap; }
  .main-inner { padding: 12px; }
  .stat-grid { grid-template-columns: 1fr; }
  .form-grid-4, .form-grid-3, .form-grid-2 { grid-template-columns: 1fr; }
}
</style>

</head>
<body>

<!-- HEADER -->

<header class="header">
  <div class="header-brand">
    <div class="btc-logo" onclick="location.reload()" title="คลิกเพื่อ Refresh หน้าเว็บ" style="cursor:pointer;">₿</div>
    <div class="brand-text">
      <h1>TRADING JOURNAL</h1>
      <p>SELF-LEARNING EDITION · POWERED BY DISCIPLINE</p>
      <div class="subtitle-th">บันทึกการเทรด · วิเคราะห์ · พัฒนาตัวเอง</div>
    </div>
  </div>
  <div class="header-actions">
    <a href="/" class="btn btn-home">🏠 HOME</a>
    <label class="btn btn-import" style="cursor:pointer;">
      ⬇ IMPORT
      <input type="file" accept=".xlsx,.xls" style="display:none;" onchange="importExcel(event)">
    </label>
    <button class="btn btn-export" onclick="exportExcel()">⬆ EXPORT</button>
    <button class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()">🌙 DARK</button>
  </div>
</header>

<!-- TABS -->

<nav class="tabs">
  <button class="tab active" onclick="switchTab('dashboard')">📊 DASHBOARD · ภาพรวม</button>
  <button class="tab" onclick="switchTab('new-trade')">➕ NEW TRADE · บันทึกการเทรด</button>
  <button class="tab" onclick="switchTab('history')">📋 HISTORY · ประวัติ</button>
  <button class="tab" onclick="switchTab('summary')">📈 SUMMARY · สรุปผล</button>
</nav>

<!-- MAIN CONTENT -->

<main class="main">
<div class="main-inner">

  <!-- ═══════════════════════════════════════ -->

  <!-- PANEL: DASHBOARD                        -->

  <!-- ═══════════════════════════════════════ -->

  <div id="panel-dashboard" class="panel active">

<!-- ACCOUNT BALANCE CARD -->
<div class="balance-card" id="balanceCard">
  <div class="balance-card-icon">💰</div>
  <div style="flex:1; min-width:280px;">
    <div class="balance-card-label">⚡ ACCOUNT BALANCE · ยอดพอร์ตเริ่มต้น</div>
    <div class="balance-card-row">
      <input type="number" step="any" class="balance-card-input" id="gb_amount"
             placeholder="10,000" oninput="updateGlobalBalance()">
      <select class="balance-card-select" id="gb_currency" onchange="updateGlobalBalance()">
        <option value="USD">USD ($)</option>
        <option value="THB">THB (฿)</option>
      </select>
      <div class="balance-card-display" id="gb_display">$0.00</div>
    </div>
    <!-- Net balance after P&L -->
    <div class="balance-net-row" id="gbNetRow" style="display:none;">
      <div class="balance-net-label">NET BALANCE · หลังคำนวณ P&L</div>
      <div class="balance-net-value" id="gbNetDisplay">$0.00</div>
    </div>
    <div class="balance-card-fx" id="gb_fx_info">กำลังโหลด FX Rate...</div>
  </div>
  <div id="balance_pnl_summary" style="text-align:right; min-width:180px;"></div>
</div>

<!-- KPI Stats -->
<div class="stat-grid" id="kpiGrid"></div>

<!-- Equity Curve -->
<div class="card" data-tip="dash_equity">
  <div class="card-title">EQUITY CURVE · กราฟสะสมกำไร/ขาดทุน</div>
  <p style="font-size:14px;color:var(--text-muted);margin-bottom:12px;font-family:'Sarabun',sans-serif;">
    📈 เส้นกราฟแสดงผลรวมสะสม P&L ของทุก Trade ตามลำดับเวลา
  </p>
  <div class="equity-wrap" id="equityWrap">
    <canvas id="equityCanvas" class="equity-canvas"
            onmousemove="equityMouseMove(event)"
            onmouseleave="equityMouseLeave()"></canvas>
    <div class="equity-tooltip" id="equityTooltip">
      <div class="equity-tooltip-date" id="ettDate"></div>
      <div class="equity-tooltip-pnl"  id="ettPnl"></div>
      <div class="equity-tooltip-detail" id="ettDetail"></div>
    </div>
  </div>
</div>

<!-- Recent Trades -->
<div class="card section-gap" data-tip="dash_recent">
  <div class="card-title">RECENT TRADES · การเทรดล่าสุด 10 รายการ</div>
  <div class="table-wrap" id="recentTradesTable"></div>
</div>
```

  </div><!-- /dashboard -->

  <!-- ═══════════════════════════════════════ -->

  <!-- PANEL: NEW TRADE                        -->

  <!-- ═══════════════════════════════════════ -->

  <div id="panel-new-trade" class="panel">
    <div class="card">
      <div class="card-title">LOG NEW TRADE · บันทึกการเทรดใหม่</div>

      <!-- EDIT MODE BANNER -->
      <div class="edit-mode-banner" id="editModeBanner">
        <span>✏ กำลังแก้ไข Trade · แก้ไขข้อมูลแล้วกด UPDATE</span>
        <button class="btn btn-cyan btn-sm" onclick="cancelEdit()">✕ ยกเลิก</button>
      </div>

      <p style="font-size:14px;color:var(--text-muted);margin-bottom:20px;font-family:'Sarabun',sans-serif;">
        ⚡ กรอกข้อมูลที่มี <span style="color:var(--neon-orange);">*</span> ให้ครบ · ช่องสีฟ้าคำนวณอัตโนมัติ ไม่ต้องกรอกเอง
      </p>

```
  <!-- ═══════════════════════ SECTION 1: ข้อมูลการเทรด ═══════════════════════ -->
  <div class="form-section">
    <div class="form-section-header">
      <div class="form-section-num blue">1</div>
      <div>
        <span class="form-section-title">TRADE INFO · ข้อมูลการเทรด</span>
        <span class="form-section-sub">— กรอกข้อมูลที่มี <span style="color:var(--neon-orange);">*</span> ให้ครบ</span>
      </div>
    </div>

    <!-- Row 1: Date, Instrument, Direction, Timeframe -->
    <div class="form-grid-4" style="margin-bottom:16px;">

      <div class="form-group" data-tip="date">
        <label class="form-label">DATE · วันที่เทรด <span class="required">*</span></label>
        <input type="date" class="form-input" id="f_date">
        <div class="price-sub" id="sub_date_display" style="color:var(--neon-cyan);font-size:11px;"></div>
      </div>

      <div class="form-group" data-tip="instrument">
        <label class="form-label">INSTRUMENT · สินทรัพย์ <span class="required">*</span></label>
        <select class="form-select" id="f_instrument" onchange="onInstrumentChange()">
          <option value="">— เลือกสินทรัพย์ —</option>
          <optgroup label="CRYPTO · คริปโต">
            <option>BTC/USDT</option>
            <option>ETH/USDT</option>
            <option>SOL/USDT</option>
            <option>XRP/USDT</option>
            <option>BNB/USDT</option>
            <option>DOGE/USDT</option>
            <option>Other Crypto</option>
          </optgroup>
          <optgroup label="FOREX · ฟอเร็กซ์">
            <option>EUR/USD</option>
            <option>GBP/USD</option>
            <option>USD/JPY</option>
            <option>AUD/USD</option>
            <option>USD/CAD</option>
            <option>Other Forex</option>
          </optgroup>
          <optgroup label="COMMODITY · สินค้าโภคภัณฑ์">
            <option>XAUUSD (Gold)</option>
            <option>Other Commodity</option>
          </optgroup>
        </select>
        <div class="price-status" id="priceStatus"></div>
      </div>

      <div class="form-group">
        <label class="form-label">DIRECTION · ทิศทาง <span class="required">*</span></label>
        <div class="direction-toggle" style="height:44px;">
          <button class="dir-btn long active" onclick="setDir('LONG')">▲ LONG · ซื้อ</button>
          <button class="dir-btn short" onclick="setDir('SHORT')">▼ SHORT · ขาย</button>
        </div>
        <input type="hidden" id="f_direction" value="LONG">
      </div>

      <div class="form-group" data-tip="timeframe">
        <label class="form-label">TIMEFRAME · กรอบเวลา</label>
        <select class="form-select" id="f_timeframe">
          <option value="15m">15m · 15 นาที</option>
          <option value="1H">1H · 1 ชั่วโมง</option>
          <option value="4H">4H · 4 ชั่วโมง</option>
          <option value="1D" selected>1D · รายวัน</option>
          <option value="1W">1W · รายสัปดาห์</option>
          <option value="1M">1M · รายเดือน</option>
          <option value="1Y">1Y · รายปี</option>
        </select>
      </div>
    </div>

    <!-- Row 2: Entry, SL, TP, Exit -->
    <div class="form-grid-4" style="margin-bottom:16px;">

      <div class="form-group" data-tip="entry">
        <label class="form-label">ENTRY PRICE · ราคาที่เข้า <span class="required">*</span></label>
        <input type="number" step="any" class="form-input" id="f_entry" placeholder="0.00" oninput="calcAll()">
        <div class="price-sub" id="sub_entry"></div>
      </div>

      <div class="form-group" data-tip="sl">
        <label class="form-label">STOP LOSS · จุดตัดขาดทุน <span class="required">*</span></label>
        <input type="number" step="any" class="form-input" id="f_sl" placeholder="0.00" oninput="calcAll()">
        <div class="short-swap-hint" id="slShortHint">▼ SHORT: SL > Entry</div>
        <div class="price-sub" id="sub_sl"></div>
      </div>

      <div class="form-group" data-tip="tp">
        <label class="form-label">TAKE PROFIT · เป้าหมายกำไร</label>
        <input type="number" step="any" class="form-input" id="f_tp" placeholder="0.00" oninput="calcAll()">
        <div class="short-swap-hint" id="tpShortHint">▼ SHORT: TP &lt; Entry</div>
        <div class="price-sub" id="sub_tp"></div>
      </div>

      <div class="form-group" data-tip="exit">
        <label class="form-label">EXIT PRICE · ราคาที่ออก
          <span style="font-size:11px;color:rgba(74,102,112,0.7);"> (เว้นว่างได้ถ้ายังไม่ปิด)</span>
        </label>
        <input type="number" step="any" class="form-input" id="f_exit" placeholder="0.00 · ไม่บังคับ" oninput="calcAll()">
        <div class="price-sub" id="sub_exit"></div>
      </div>
    </div>

    <!-- Row 3: Balance, Risk%, Fee -->
    <div class="form-grid-3">

      <div class="form-group" data-tip="balance">
        <label class="form-label">ACCOUNT BALANCE ($) <span class="required">*</span>
          <span style="font-size:11px;color:rgba(191,95,255,0.7);"> [อัตโนมัติ]</span>
        </label>
        <input type="number" step="any" class="form-input" id="f_balance" placeholder="10000" oninput="calcAll()">
        <div id="balanceHint" style="font-family:'JetBrains Mono',monospace;font-size:11px;color:rgba(191,95,255,0.6);margin-top:4px;"></div>
        <div class="price-sub" id="sub_balance"></div>
      </div>

      <div class="form-group" data-tip="risk_pct">
        <label class="form-label">RISK PER TRADE (%) <span class="required">*</span>
          <span style="font-size:11px;color:rgba(74,102,112,0.7);"> (แนะนำ 1–2%)</span>
        </label>
        <input type="number" step="0.1" class="form-input" id="f_risk_pct" value="1" min="0.1" max="10" oninput="calcAll()">
        <div class="risk-pill" id="riskPill" style="display:none;">
          <span id="riskPillText">— กรอก Balance ก่อน —</span>
        </div>
      </div>

      <div class="form-group" data-tip="fee_pct">
        <label class="form-label">FEE PER SIDE (%)
          <span style="font-size:11px;color:rgba(74,102,112,0.7);"> (Binance Taker 0.1%)</span>
        </label>
        <input type="number" step="0.01" class="form-input" id="f_fee_pct" value="0.1" min="0" max="5" oninput="calcAll()" placeholder="0.1">
        <div class="fee-hint" id="feeHint" style="display:none;">
          <span id="feeTotalPct"></span>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════ SECTION 2: คำนวณอัตโนมัติ ═══════════════════════ -->
  <div class="form-section">
    <div class="form-section-header">
      <div class="form-section-num orange">2</div>
      <div>
        <span class="form-section-title">AUTO CALC · คำนวณอัตโนมัติ</span>
        <span class="form-section-sub">— ช่องสีฟ้าคำนวณให้อัตโนมัติ ไม่ต้องกรอก</span>
      </div>
    </div>

    <div class="form-grid-4">

      <!-- Position Size -->
      <div class="calc-card" data-tip="position_size">
        <div class="calc-card-label">📐 POSITION SIZE · ขนาด Position</div>
        <div class="calc-card-value" id="c_size_val">—</div>
        <div class="calc-card-sub" id="c_size_usd">กรอก Entry · SL · Balance ก่อน</div>
      </div>

      <!-- Risk:Reward -->
      <div class="calc-card" data-tip="rr">
        <div class="calc-card-label">⚖ RISK : REWARD</div>
        <div class="calc-card-value" id="c_rr_val">—</div>
        <div class="calc-card-sub" id="c_rr_sub">ต้องมี TP เพื่อคำนวณ</div>
      </div>

      <!-- P&L Net -->
      <div class="calc-card" data-tip="pnl">
        <div class="calc-card-label">💵 P&L NET · หักค่าธรรมเนียม</div>
        <div class="calc-card-value" id="c_pnl_val">—</div>
        <div class="calc-card-sub" id="c_pnl_sub">กรอก Exit Price ก่อน</div>
      </div>

      <!-- R-Multiple -->
      <div class="calc-card" data-tip="rmul">
        <div class="calc-card-label">✖ R-MULTIPLE</div>
        <div class="calc-card-value" id="c_rmul_val">—</div>
        <div class="calc-card-sub" id="c_rmul_sub">เช่น +2R = กำไร 2× ของ Risk</div>
      </div>
    </div>

    <!-- hidden inputs for form compat -->
    <input type="hidden" id="c_size">
    <input type="hidden" id="c_rr">
    <input type="hidden" id="c_pnl">
    <input type="hidden" id="c_rmul">
  </div>

  <!-- ═══════════════════════ SECTION 3: ผลการเทรด ═══════════════════════ -->
  <div class="form-section">
    <div class="form-section-header">
      <div class="form-section-num green">3</div>
      <div>
        <span class="form-section-title">TRADE RESULT · ผลการเทรด &amp; บันทึก</span>
        <span class="form-section-sub">— บันทึกผล Setup และบทเรียน</span>
      </div>
    </div>

    <!-- Row: Outcome + Setup -->
    <div class="form-grid-2" style="margin-bottom:16px;">

      <div class="form-group">
        <label class="form-label">OUTCOME · ผลการเทรด <span class="required">*</span></label>
        <div class="outcome-toggle">
          <button class="out-btn win active" onclick="setOutcome('WIN', true)">✓ WIN · ชนะ</button>
          <button class="out-btn loss" onclick="setOutcome('LOSS', true)">✗ LOSS · แพ้</button>
          <button class="out-btn be" onclick="setOutcome('BE', true)">= B/E · เสมอ</button>
        </div>
        <input type="hidden" id="f_outcome" value="WIN">
      </div>

      <div class="form-group" data-tip="setup">
        <label class="form-label">SETUP TAG · รูปแบบการเทรด</label>
        <select class="form-select" id="f_setup">
          <option value="">— ไม่ระบุ —</option>
          <option>Breakout · ราคาทะลุแนวต้าน</option>
          <option>Pullback · ราคาดึงกลับแล้วต่อ</option>
          <option>Reversal · ราคากลับทิศ</option>
          <option>Support/Resistance · แนวรับ/ต้าน</option>
          <option>Trend Follow · เทรดตาม Trend</option>
          <option>RSI Divergence · ความเบี่ยงเบน RSI</option>
          <option>MACD Cross · สัญญาณ MACD</option>
          <option>Price Action · อ่านจากแท่งเทียน</option>
          <option>Other · อื่นๆ</option>
        </select>
      </div>
    </div>

    <!-- Row: Emotion + Followed -->
    <div class="form-grid-2" style="margin-bottom:16px;">

      <div class="form-group" data-tip="emotion">
        <label class="form-label">EMOTION STATE · สภาวะจิตใจขณะเทรด</label>
        <select class="form-select" id="f_emotion">
          <option value="">— ไม่ระบุ —</option>
          <option>Calm / Focused · สงบ มีสมาธิ</option>
          <option>FOMO · กลัวตกรถ</option>
          <option>Revenge Trade · แก้แค้นตลาด</option>
          <option>Overconfident · มั่นใจมากเกินไป</option>
          <option>Fearful · กลัว ลังเล</option>
          <option>Disciplined · มีวินัย ทำตามระบบ</option>
        </select>
      </div>

      <div class="form-group" data-tip="followed">
        <label class="form-label">FOLLOWED SYSTEM? · ทำตามระบบไหม?</label>
        <select class="form-select" id="f_followed">
          <option value="YES">✓ YES · ทำตามกฎ 100%</option>
          <option value="NO">✗ NO · เบี่ยงเบนจากระบบ</option>
          <option value="PARTIAL">~ PARTIAL · ทำตามบางส่วน</option>
        </select>
      </div>
    </div>

    <!-- Reason + Lesson full width -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
      <div class="form-group" data-tip="reason">
        <label class="form-label">ENTRY REASON · เหตุผลในการเข้า Trade</label>
        <textarea class="form-textarea form-input" id="f_reason" style="min-height:90px;" placeholder="เช่น: ราคาทดสอบ Support 2 ครั้ง + RSI Divergence + Volume เพิ่มขึ้น → เข้า Long ที่ $103,500"></textarea>
      </div>
      <div class="form-group" data-tip="lesson">
        <label class="form-label">LESSON LEARNED · บทเรียนที่ได้รับ</label>
        <textarea class="form-textarea form-input" id="f_lesson" style="min-height:90px;" placeholder="เช่น: ควรรอ Confirmation Candle ปิดก่อน แล้วค่อยเข้า ไม่ควร Anticipate ล่วงหน้า"></textarea>
      </div>
    </div>

    <!-- Buttons -->
    <div style="display:flex; gap:12px; flex-wrap:wrap; padding-top:8px; border-top:1px solid var(--border);">
      <button class="btn btn-primary" onclick="saveTrade()">⚡ SAVE TRADE · บันทึก</button>
      <button class="btn btn-cyan" onclick="resetForm()">↺ RESET · ล้างฟอร์ม</button>
    </div>
  </div>

</div><!-- /card inner -->
```

  </div><!-- /new-trade -->

  <!-- ═══════════════════════════════════════ -->

  <!-- PANEL: HISTORY                          -->

  <!-- ═══════════════════════════════════════ -->

  <div id="panel-history" class="panel">
    <div class="card">
      <div class="card-title">TRADE HISTORY · ประวัติการเทรดทั้งหมด</div>
      <p style="font-size:14px;color:var(--text-muted);margin-bottom:16px;font-family:'Sarabun',sans-serif;">
        🔍 กรองข้อมูลด้วย Filter ด้านล่าง · คลิก EDIT เพื่อแก้ไข · คลิก DEL เพื่อลบ
      </p>

```
  <!-- Filters -->
  <div class="filter-row">
    <select class="form-select" id="filterInstrument" onchange="renderHistory()" style="min-width:160px;">
      <option value="">สินทรัพย์ทั้งหมด</option>
    </select>
    <select class="form-select" id="filterOutcome" onchange="renderHistory()" style="min-width:140px;">
      <option value="">ผลทั้งหมด</option>
      <option value="WIN">✓ WIN · ชนะ</option>
      <option value="LOSS">✗ LOSS · แพ้</option>
      <option value="BE">= B/E · เสมอ</option>
    </select>
    <select class="form-select" id="filterSetup" onchange="renderHistory()" style="min-width:160px;">
      <option value="">Setup ทั้งหมด</option>
    </select>
    <input type="text" class="form-input" id="filterSearch" placeholder="🔍 ค้นหา..." oninput="renderHistory()" style="min-width:180px;">
  </div>

  <div class="table-wrap" id="historyTable"></div>
</div>
```

  </div><!-- /history -->

  <!-- ═══════════════════════════════════════ -->

  <!-- PANEL: SUMMARY                          -->

  <!-- ═══════════════════════════════════════ -->

  <div id="panel-summary" class="panel">

```
<div class="period-tabs">
  <button class="period-btn active" onclick="setSummaryPeriod('all', this)">ALL TIME · ทั้งหมด</button>
  <button class="period-btn" onclick="setSummaryPeriod('year', this)">BY YEAR · รายปี</button>
  <button class="period-btn" onclick="setSummaryPeriod('month', this)">BY MONTH · รายเดือน</button>
</div>

<!-- Year/Month selectors -->
<div id="periodSelectors" style="display:none; margin-bottom:20px; gap:12px; flex-wrap:wrap; align-items:center;">
  <select id="yearFilter" onchange="renderSummary()"></select>
  <select id="monthFilter" onchange="renderSummary()" style="display:none;"></select>
</div>

<!-- Summary Stats -->
<div id="summaryContent"></div>

<!-- By Instrument -->
<div class="card section-gap" data-tip="sum_by_instrument">
  <div class="card-title">PERFORMANCE BY INSTRUMENT · ผลลัพธ์แยกตามสินทรัพย์</div>
  <div class="table-wrap" id="byInstrumentTable"></div>
</div>

<!-- By Setup -->
<div class="card section-gap" data-tip="sum_by_setup">
  <div class="card-title">PERFORMANCE BY SETUP · ผลลัพธ์แยกตามรูปแบบการเทรด</div>
  <div class="table-wrap" id="bySetupTable"></div>
</div>

<!-- Emotion Stats -->
<div class="card section-gap" data-tip="sum_by_emotion">
  <div class="card-title">EMOTION ANALYSIS · วิเคราะห์ผลตามสภาวะจิตใจ</div>
  <p style="font-size:14px;color:var(--text-muted);margin-bottom:16px;font-family:'Sarabun',sans-serif;">
    💡 ดูว่าเวลาเทรดด้วยอารมณ์แบบไหน ผลลัพธ์เป็นอย่างไร — ช่วยให้รู้จักตัวเองมากขึ้น
  </p>
  <div class="table-wrap" id="emotionTable"></div>
</div>

<!-- Danger Zone -->
<div class="card section-gap" style="border-color:rgba(255,45,85,0.2);background:rgba(255,45,85,0.03);">
  <div class="card-title" style="color:var(--neon-red);">⚠ DANGER ZONE · ระวัง</div>
  <p style="font-family:'Sarabun',sans-serif;font-size:14px;color:var(--text-muted);margin-bottom:16px;">
    ลบข้อมูล Trade ทั้งหมดออกจาก Journal อย่างถาวร — ไม่สามารถกู้คืนได้
  </p>
  <button class="btn" onclick="confirmClearAll()"
    style="background:rgba(255,45,85,0.1);border:1px solid rgba(255,45,85,0.4);color:var(--neon-red);padding:12px 24px;font-family:'Space Grotesk',sans-serif;font-size:12px;letter-spacing:1.5px;font-weight:700;cursor:pointer;border-radius:6px;transition:all 0.2s;"
    onmouseover="this.style.background='rgba(255,45,85,0.2)'"
    onmouseout="this.style.background='rgba(255,45,85,0.1)'">
    🗑 CLEAR ALL DATA · ลบข้อมูลทั้งหมด
  </button>
</div>
```

  </div><!-- /summary -->

</div><!-- /main-inner -->

<!-- FOOTER -->

<footer class="footer">
  <div class="footer-secure">SECURE &amp; PRIVATE — ข้อมูลทั้งหมดเก็บใน Local Storage บนเครื่องของคุณเท่านั้น</div>
  <div class="footer-copy">
    <div>© 2026 Chollatis Bitcoiner. | <span class="verify">Don't Trust, Verify.</span></div>
    <div class="powered">Powered by Claude AI &amp; PHP 8.2.30 · Self-Learning Trading Journal</div>
  </div>
</footer>

</main>

<!-- TOOLTIP BALLOON -->
<div class="tj-balloon" id="tjBalloon">
  <div class="tj-balloon-title" id="tjBalloonTitle"></div>
  <div class="tj-balloon-body" id="tjBalloonBody"></div>
  <div class="tj-balloon-tip" id="tjBalloonTip"></div>
</div>

<!-- TOAST -->

<div class="toast" id="toast"></div>

<script>
// ═══════════════════════════════════════════
// THEME SYSTEM
// ═══════════════════════════════════════════
(function initTheme() {
  const saved = localStorage.getItem('tj_theme');
  // Default = dark เสมอ ถ้าไม่เคยบันทึก preference ไว้
  const theme = saved || 'dark';
  applyTheme(theme);
})();

// ─── DYNAMIC LAYOUT MEASUREMENT ──────────────
// วัด height จริงของ header+tabs แล้ว set CSS variables
// รองรับ mobile ที่ header อาจ wrap หลายบรรทัด
function updateLayout() {
  // บน mobile/tablet (≤1024px) header ไม่ fixed → ไม่ต้องคำนวณ offset
  if (window.innerWidth <= 1024) {
    // Reset inline styles ที่อาจค้างจาก desktop mode
    const tabsEl = document.querySelector('.tabs');
    const mainEl = document.querySelector('.main');
    if (tabsEl) tabsEl.style.top = '';
    if (mainEl) mainEl.style.top = '';
    return;
  }
  const header = document.querySelector('.header');
  const tabs   = document.querySelector('.tabs');
  if (!header || !tabs) return;
  const hH     = header.getBoundingClientRect().height;
  const tH     = tabs.getBoundingClientRect().height;
  const chrome = Math.ceil(hH + tH);
  document.documentElement.style.setProperty('--header-h', hH + 'px');
  document.documentElement.style.setProperty('--tabs-h',   tH + 'px');
  document.documentElement.style.setProperty('--chrome-h', chrome + 'px');
  tabs.style.top = Math.ceil(hH) + 'px';
  const mainEl = document.querySelector('.main');
  if (mainEl) mainEl.style.top = chrome + 'px';
}

window.addEventListener('resize', updateLayout);

// เรียก updateLayout อีกครั้งหลัง font load เสร็จ (font อาจเปลี่ยน header height)
if (document.fonts) {
  document.fonts.ready.then(updateLayout);
} else {
  window.addEventListener('load', updateLayout);
}

function applyTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('tj_theme', theme);
  const btn = document.getElementById('themeToggleBtn');
  if (btn) btn.textContent = theme === 'dark' ? '☀ LIGHT' : '🌙 DARK';
}

function toggleTheme() {
  const current = document.documentElement.getAttribute('data-theme') || 'dark';
  applyTheme(current === 'dark' ? 'light' : 'dark');
}

// ═══════════════════════════════════════════
// GLOBAL BALANCE BAR
// ═══════════════════════════════════════════
let _gbUsdThb    = null; // cached FX rate USD→THB
let _fxUpdatedAt = null; // วันเวลาอัพเดทจาก source
let _fxSource    = null; // ชื่อ source ที่ถูกเลือก

// ── Poll ทุก 5 นาที (floatrates.com อัพเดทบ่อยๆ) ─────────────
function startFxPolling() {
  setInterval(async () => {
    await fetchUsdThbRate();
    updateGlobalBalance();
    if (document.getElementById('panel-dashboard').classList.contains('active')) {
      renderDashboard();
    }
  }, 300000);
}

// แปลง date string → "21 Apr 2026 · 12:00 UTC"
function parseFxDate(raw) {
  try {
    const dt  = new Date(raw);
    const day = dt.getUTCDate();
    const mon = dt.toLocaleString('en-US', { month: 'short', timeZone: 'UTC' });
    const yr  = dt.getUTCFullYear();
    const hh  = String(dt.getUTCHours()).padStart(2, '0');
    const mm  = String(dt.getUTCMinutes()).padStart(2, '0');
    return `${day} ${mon} ${yr} · ${hh}:${mm} UTC`;
  } catch { return raw; }
}

// ดึงข้อมูลจาก 3 แหล่งพร้อมกัน แล้วเลือก source ที่ unix_timestamp ใหม่ที่สุด
async function fetchUsdThbRate() {
  const results = [];

  // ── Source 1: localhost/rate.json (local server) ─────────────
  try {
    const r = await fetch(window.location.origin + '/rate.json',
      { signal: AbortSignal.timeout(3000) });
    if (r.ok) {
      const d = await r.json();
      const rate = parseFloat(d?.rate);
      if (rate && rate > 20 && rate < 80) {
        results.push({
          rate,
          ts:    d.unix_timestamp || 0,
          label: d.updated_at || '',
          src:   'localhost/rate.json'
        });
      }
    }
  } catch { /* skip */ }

  // ── Source 2: floatrates.com ─────────────────────────────────
  try {
    const r = await fetch('https://www.floatrates.com/daily/usd.json',
      { signal: AbortSignal.timeout(6000) });
    if (r.ok) {
      const d = await r.json();
      const rate = parseFloat(d?.thb?.rate);
      if (rate && rate > 20 && rate < 80) {
        const ts = d?.thb?.date
          ? Math.floor(new Date(d.thb.date).getTime() / 1000)
          : 0;
        results.push({
          rate,
          ts,
          label: d?.thb?.date ? parseFxDate(d.thb.date) : '',
          src:   'floatrates.com'
        });
      }
    }
  } catch { /* skip */ }

  // ── Source 3: open.er-api.com ────────────────────────────────
  try {
    const r = await fetch('https://open.er-api.com/v6/latest/USD',
      { signal: AbortSignal.timeout(6000) });
    const d = await r.json();
    if (d?.rates?.THB) {
      const ts = d?.time_last_update_unix
        || (d?.time_last_update_utc
            ? Math.floor(new Date(d.time_last_update_utc).getTime() / 1000)
            : 0);
      results.push({
        rate:  d.rates.THB,
        ts,
        label: d?.time_last_update_utc ? parseFxDate(d.time_last_update_utc) : '',
        src:   'open.er-api.com'
      });
    }
  } catch { /* skip */ }

  if (results.length === 0) {
    _gbUsdThb = null; _fxUpdatedAt = null; _fxSource = null;
    return;
  }

  // เลือก source ที่ timestamp ใหม่ที่สุด
  results.sort((a, b) => b.ts - a.ts);
  const best   = results[0];
  _gbUsdThb    = best.rate;
  _fxUpdatedAt = best.label;
  _fxSource    = best.src;
}

async function initGlobalBalance() {
  // Load saved — ถ้าเป็นครั้งแรก ตั้งค่า default = 100,000 THB
  const saved = localStorage.getItem('tj_global_balance');
  if (saved) {
    try {
      const obj = JSON.parse(saved);
      document.getElementById('gb_amount').value   = obj.amount || '';
      document.getElementById('gb_currency').value = obj.currency || 'THB';
    } catch(e) {}
  } else {
    document.getElementById('gb_amount').value   = '100000';
    document.getElementById('gb_currency').value = 'THB';
  }

  // Render immediately (ไม่มี THB ก่อน)
  updateGlobalBalance();

  // First load: REST API fetch → render ทันที
  await fetchUsdThbRate();
  updateGlobalBalance();
  if (document.getElementById('panel-dashboard').classList.contains('active')) {
    renderDashboard();
  }

  // เริ่ม polling อัพเดท FX ทุก 5 นาที
  startFxPolling();
}

function updateGlobalBalance() {
  const amount   = parseFloat(document.getElementById('gb_amount').value) || 0;
  const currency = document.getElementById('gb_currency').value;

  let usd = 0, thb = 0;
  const rate = _gbUsdThb;

  if (currency === 'USD') {
    usd = amount;
    thb = rate ? amount * rate : null;
  } else {
    thb = amount;
    usd = rate ? amount / rate : null;
  }

  const displayEl = document.getElementById('gb_display');
  const fxEl      = document.getElementById('gb_fx_info');

  // แสดงเฉพาะหน่วยตรงข้าม (ไม่แสดงหน่วยที่กรอกซ้ำ)
  if (currency === 'USD') {
    // กรอก USD → แสดงเฉพาะ THB
    displayEl.innerHTML = thb
      ? `<span style="color:var(--neon-purple);">= ฿${thb.toLocaleString('th-TH',{minimumFractionDigits:0,maximumFractionDigits:0})}</span>`
      : `<span style="color:var(--text-muted);">กำลังโหลด FX...</span>`;
  } else {
    // กรอก THB → แสดงเฉพาะ USD
    displayEl.innerHTML = usd
      ? `<span style="color:var(--neon-purple);">= $${usd.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2})}</span>`
      : `<span style="color:var(--text-muted);">กำลังโหลด FX...</span>`;
  }

  if (fxEl) {
    fxEl.textContent = rate
      ? `1 USD ≈ ฿${rate.toFixed(2)} · ${_fxSource || 'FX'} · ${_fxUpdatedAt || 'อัพเดทล่าสุด'}`
      : 'ไม่สามารถโหลด FX Rate ได้ (ใช้ตัวเลขโดยตรง)';
  }

  // Save & sync to trade form balance field (always USD)
  localStorage.setItem('tj_global_balance', JSON.stringify({ amount, currency }));
  if (usd > 0) {
    const balField = document.getElementById('f_balance');
    if (balField && !balField.dataset.userEdited) {
      balField.value = usd.toFixed(2);
      balField.classList.add('balance-auto');
    }
  }

  // Net balance = initial USD + all P&L
  const trades   = loadTrades();
  const totalPnl = trades.reduce((s, t) => s + (t.pnlUSD || 0), 0);
  const netUsd   = usd + totalPnl;
  const netRow   = document.getElementById('gbNetRow');
  const netDisp  = document.getElementById('gbNetDisplay');

  if (usd > 0 && trades.length > 0) {
    if (netRow) netRow.style.display = 'flex';
    const netColor  = netUsd >= usd ? 'var(--neon-green)' : 'var(--neon-red)';
    const pnlSign   = totalPnl >= 0 ? '+' : '';
    const netThb    = rate ? netUsd * rate : null;
    const pnlThb    = rate ? totalPnl * rate : null;

    // NET BALANCE: หน่วยหลักตามที่เลือกในช่อง, ต่อท้ายด้วยหน่วยตรงข้าม
    if (currency === 'THB') {
      // หน่วยหลัก = THB, ต่อท้าย USD
      const netThbDisplay = netThb
        ? `฿${netThb.toLocaleString('th-TH',{minimumFractionDigits:0,maximumFractionDigits:0})}`
        : '฿—';
      const pnlThbDisplay = pnlThb
        ? `${pnlSign}฿${Math.abs(pnlThb).toLocaleString('th-TH',{maximumFractionDigits:0})}`
        : '';
      const usdSecondary = `= $${netUsd.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2})}`;
      if (netDisp) netDisp.innerHTML =
        `<span style="color:${netColor};">${netThbDisplay}</span>`
        + ` <span style="font-size:13px;color:var(--text-muted);">${usdSecondary}</span>`
        + ` <span style="font-size:12px;color:var(--text-muted);">(${pnlThbDisplay ? pnlThbDisplay + ' / ' : ''}${pnlSign}$${totalPnl.toFixed(2)} P&L)</span>`;
    } else {
      // หน่วยหลัก = USD, ต่อท้าย THB
      const thbSecondary = netThb
        ? `= ฿${netThb.toLocaleString('th-TH',{minimumFractionDigits:0,maximumFractionDigits:0})}`
        : '';
      const pnlThbStr = pnlThb
        ? ` / ${pnlSign}฿${Math.abs(pnlThb).toLocaleString('th-TH',{maximumFractionDigits:0})}`
        : '';
      if (netDisp) netDisp.innerHTML =
        `<span style="color:${netColor};">$${netUsd.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2})}</span>`
        + (thbSecondary ? ` <span style="font-size:13px;color:var(--text-muted);">${thbSecondary}</span>` : '')
        + ` <span style="font-size:12px;color:var(--text-muted);">(${pnlSign}$${totalPnl.toFixed(2)}${pnlThbStr} P&L)</span>`;
    }

    // Sync net balance to form field (always USD)
    const balField = document.getElementById('f_balance');
    if (balField && !balField.dataset.userEdited) {
      balField.value = netUsd.toFixed(2);
    }
  } else {
    if (netRow) netRow.style.display = 'none';
  }

  // Update P&L summary tag in balance card
  _renderBalancePnlSummary(usd);
}

function _renderBalancePnlSummary(currentUsdBalance) {
  const el = document.getElementById('balance_pnl_summary');
  if (!el) return;
  const trades = loadTrades();
  if (!trades.length) { el.innerHTML = ''; return; }
  const totalPnl = trades.reduce((s, t) => s + (t.pnlUSD || 0), 0);
  const totalR   = trades.reduce((s, t) => s + (t.rMul   || 0), 0);
  const wins     = trades.filter(t => t.outcome === 'WIN').length;
  const winRate  = trades.length ? (wins / trades.length * 100).toFixed(1) : '0.0';
  const sign     = totalPnl >= 0 ? '+' : '';
  const cls      = totalPnl >= 0 ? '' : ' loss';
  const pnlThb   = _gbUsdThb ? totalPnl * _gbUsdThb : null;
  const thbStr   = pnlThb
    ? ` <span style="font-size:10px;opacity:0.8;">${sign}฿${Math.abs(pnlThb).toLocaleString('th-TH',{maximumFractionDigits:0})}</span>`
    : '';
  el.innerHTML = `
    <div class="balance-pnl-tag${cls}">${sign}$${Math.abs(totalPnl).toFixed(2)}${thbStr} · ${sign}${totalR.toFixed(2)}R</div>
    <div style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text-muted);margin-top:6px;">
      ${trades.length} trades · WIN ${winRate}%
    </div>`;
}

// ═══════════════════════════════════════════
// BINANCE SYMBOL MAP
// ═══════════════════════════════════════════
const BINANCE_SYMBOL_MAP = {
  'BTC/USDT':      'BTCUSDT',
  'ETH/USDT':      'ETHUSDT',
  'SOL/USDT':      'SOLUSDT',
  'XRP/USDT':      'XRPUSDT',
  'BNB/USDT':      'BNBUSDT',
  'DOGE/USDT':     'DOGEUSDT',
  'Other Crypto':  null,
};

const FOREX_MAP = {
  'EUR/USD':      { type:'forex', base:'EUR', quote:'USD' },
  'GBP/USD':      { type:'forex', base:'GBP', quote:'USD' },
  'USD/JPY':      { type:'forex', base:'USD', quote:'JPY' },
  'AUD/USD':      { type:'forex', base:'AUD', quote:'USD' },
  'USD/CAD':      { type:'forex', base:'USD', quote:'CAD' },
  'Other Forex':  null,
};

// Commodity: Gold ดึงจาก Binance (PAXGUSDT), Other Commodity กรอกเอง
const COMMODITY_MAP = {
  'XAUUSD (Gold)':    { type:'binance', symbol:'PAXGUSDT', label:'Gold (PAXG/USDT)' },
  'Other Commodity':  null,
};

// ═══════════════════════════════════════════
// INSTRUMENT CHANGE — MASTER HANDLER
// ═══════════════════════════════════════════
async function onInstrumentChange() {
  const instrument = document.getElementById('f_instrument').value;

  setStatusBadge('', '');
  if (!instrument) return;

  // Auto-fill Balance from global bar
  autoFillBalance();

  if (BINANCE_SYMBOL_MAP.hasOwnProperty(instrument)) {
    if (instrument === 'Other Crypto') {
      // ไม่ดึงราคาอัตโนมัติ — กรอก Entry Price เองโดยตรง
      setStatusBadge('history', '✏ กรอก Entry Price เอง (Other Crypto)');
      fillFromJournalHistory(instrument, null);
    } else {
      await fetchBinancePrice(instrument, BINANCE_SYMBOL_MAP[instrument]);
    }
  } else if (FOREX_MAP.hasOwnProperty(instrument)) {
    if (!FOREX_MAP[instrument]) {
      setStatusBadge('history', '✏ กรอก Entry Price เอง (Other Forex)');
      fillFromJournalHistory(instrument, null);
    } else {
      await fetchForexPrice(instrument, FOREX_MAP[instrument]);
    }
  } else if (COMMODITY_MAP.hasOwnProperty(instrument)) {
    if (!COMMODITY_MAP[instrument]) {
      setStatusBadge('history', '✏ กรอก Entry Price เอง (Other Commodity)');
      fillFromJournalHistory(instrument, null);
    } else {
      const cfg = COMMODITY_MAP[instrument];
      if (cfg.type === 'binance') {
        await fetchBinancePrice(instrument, cfg.symbol);
      } else {
        await fetchForexPrice(instrument, cfg);
      }
    }
  } else {
    fillFromJournalHistory(instrument, null);
  }
}

// ── ดึงราคา Binance Real-time ─────────────────
async function fetchBinancePrice(instrument, symbol) {
  setStatusBadge('loading', '⟳ กำลังดึงราคา Binance...');
  try {
    const res = await fetch(
      `https://api.binance.com/api/v3/ticker/price?symbol=${symbol}`,
      { signal: AbortSignal.timeout(5000) }
    );
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();
    const price = parseFloat(data.price);
    if (!price || isNaN(price)) throw new Error('Invalid price');
    document.getElementById('f_entry').value = price.toFixed(isCrypto(price) ? 2 : 5);
    fillFromJournalHistory(instrument, price);
    setStatusBadge('live',
      `<span class="dot-live"></span> LIVE ${symbol} = $${fmtPrice(price)}`
    );
  } catch(err) {
    setStatusBadge('error', `⚠ Binance ไม่ตอบสนอง (${err.message}) — กรอกเอง`);
    fillFromJournalHistory(instrument, null);
  }
}

// ── ดึงราคา Forex/Commodity Real-time ────────
// ใช้ open.er-api.com (ฟรี ไม่ต้อง key รองรับ XAG ด้วย)
async function fetchForexPrice(instrument, cfg) {
  setStatusBadge('loading', '⟳ กำลังดึงราคา Forex...');
  const { base, quote } = cfg;
  try {
    // Primary: open.er-api.com
    const url = `https://open.er-api.com/v6/latest/${base}`;
    const res = await fetch(url, { signal: AbortSignal.timeout(6000) });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const d = await res.json();
    if (d.result !== 'success') throw new Error('API error');
    const rate = parseFloat(d.rates?.[quote]);
    if (!rate || isNaN(rate)) throw new Error(`No rate for ${quote}`);
    const price = rate;
    document.getElementById('f_entry').value = price.toFixed(isCrypto(price) ? 2 : 5);
    fillFromJournalHistory(instrument, price);
    setStatusBadge('live',
      `<span class="dot-live"></span> LIVE ${base}/${quote} = ${fmtPrice(price)}`
    );
  } catch(err) {
    // Fallback: Frankfurter (ไม่มี XAG แต่ใช้ได้สำหรับ major forex)
    try {
      const url2 = `https://api.frankfurter.app/latest?from=${base}&to=${quote}`;
      const res2 = await fetch(url2, { signal: AbortSignal.timeout(5000) });
      if (!res2.ok) throw new Error('Frankfurter failed');
      const d2 = await res2.json();
      const rate2 = parseFloat(d2.rates?.[quote]);
      if (!rate2 || isNaN(rate2)) throw new Error('No rate');
      document.getElementById('f_entry').value = rate2.toFixed(isCrypto(rate2) ? 2 : 5);
      fillFromJournalHistory(instrument, rate2);
      setStatusBadge('live',
        `<span class="dot-live"></span> ${base}/${quote} = ${fmtPrice(rate2)} (fallback)`
      );
    } catch {
      setStatusBadge('error', `⚠ ดึงราคาไม่ได้ — กรอกเอง`);
      fillFromJournalHistory(instrument, null);
    }
  }
}

// ── Fill SL/TP: ใช้ราคา Live เสมอ → SL/TP จาก History หรือ ±5% ─
function fillFromJournalHistory(instrument, currentPrice) {
  const trades = loadTrades();

  // หา Trade ล่าสุดของ instrument นี้
  const prev = [...trades]
    .filter(t => t.instrument === instrument)
    .sort((a, b) => b.date.localeCompare(a.date))[0];

  if (currentPrice) {
    // ── มีราคา Live: set Entry = live price เสมอ ──
    document.getElementById('f_entry').value = currentPrice.toFixed(isCrypto(currentPrice) ? 2 : 5);

    if (prev && prev.sl && prev.tp) {
      // SL/TP จากไม้ล่าสุด — ปรับ % จาก entry เดิม มาเทียบกับ entry ใหม่
      const prevEntry = prev.entry || currentPrice;
      const slPct = (prev.sl - prevEntry) / prevEntry;
      const tpPct = (prev.tp - prevEntry) / prevEntry;
      const newSL  = currentPrice * (1 + slPct);
      const newTP  = currentPrice * (1 + tpPct);
      document.getElementById('f_sl').value = newSL.toFixed(isCrypto(currentPrice) ? 2 : 5);
      document.getElementById('f_tp').value = newTP.toFixed(isCrypto(currentPrice) ? 2 : 5);
      const curr = document.getElementById('priceStatus').innerHTML;
      document.getElementById('priceStatus').innerHTML =
        curr + `<span style="color:var(--neon-cyan);font-size:12px;margin-left:8px;">· SL/TP ปรับตามไม้ล่าสุด (${prev.date})</span>`;
    } else {
      // ไม่มีประวัติ: SL=-5%, TP=+10%
      document.getElementById('f_sl').value = (currentPrice * 0.95).toFixed(isCrypto(currentPrice) ? 2 : 5);
      document.getElementById('f_tp').value = (currentPrice * 1.10).toFixed(isCrypto(currentPrice) ? 2 : 5);
      const curr = document.getElementById('priceStatus').innerHTML;
      document.getElementById('priceStatus').innerHTML =
        curr + `<span style="color:rgba(247,147,26,0.8);font-size:12px;margin-left:8px;">· SL −5% / TP +10% (ปรับได้)</span>`;
    }

  } else {
    // ── ไม่มีราคา Live (API fail) → fallback journal ──
    if (prev && prev.entry) {
      document.getElementById('f_entry').value = prev.entry;
      document.getElementById('f_sl').value    = prev.sl || '';
      document.getElementById('f_tp').value    = prev.tp || '';
      setStatusBadge('history', `📋 API ไม่ตอบ — ใช้ราคาจาก Journal (${prev.date})`);
    } else {
      setStatusBadge('error', '⚠ ดึงราคาไม่ได้ · ไม่มีประวัติ — กรอกเอง');
    }
  }

  calcAll();
}

// ── Auto-fill Balance จากพอร์ตล่าสุด ─────────
function autoFillBalance() {
  const balField = document.getElementById('f_balance');
  if (balField.dataset.userEdited) return;

  // Priority 1: Global Balance Bar
  const gbAmount   = parseFloat(document.getElementById('gb_amount').value) || 0;
  const gbCurrency = document.getElementById('gb_currency').value;
  let usdBalance = 0;

  if (gbAmount > 0) {
    if (gbCurrency === 'USD') {
      usdBalance = gbAmount;
    } else if (_gbUsdThb) {
      usdBalance = gbAmount / _gbUsdThb;
    }
  }

  if (usdBalance > 0) {
    balField.value = usdBalance.toFixed(2);
    balField.classList.add('balance-auto');
    const hint = document.getElementById('balanceHint');
    if (hint) hint.innerHTML = `♻ ใช้จาก Balance Bar: $${fmtPrice(usdBalance)}`;
    balField.addEventListener('input', function() {
      this.classList.remove('balance-auto');
      this.dataset.userEdited = '1';
      const h = document.getElementById('balanceHint');
      if (h) h.innerHTML = '';
    }, { once: true });
    calcAll();
    return;
  }

  // Priority 2: Last trade
  const trades = [...loadTrades()].sort((a,b) => a.date.localeCompare(b.date));
  if (!trades.length) return;
  const last = trades[trades.length - 1];
  const currentBalance = (last.balance || 0) + (last.pnlUSD || 0);
  if (currentBalance > 0) {
    balField.value = currentBalance.toFixed(2);
    balField.classList.add('balance-auto');
    const hint = document.getElementById('balanceHint');
    if (hint) hint.innerHTML = `♻ คำนวณจาก Trade ล่าสุด: $${fmtPrice(currentBalance)}`;
    balField.addEventListener('input', function() {
      this.classList.remove('balance-auto');
      this.dataset.userEdited = '1';
      const h = document.getElementById('balanceHint');
      if (h) h.innerHTML = '';
    }, { once: true });
  }
  calcAll();
}

// ── Helpers ───────────────────────────────────
function setStatusBadge(type, html) {
  const el = document.getElementById('priceStatus');
  if (!el) return;
  if (!type) { el.innerHTML = ''; return; }
  el.innerHTML = `<span class="price-badge ${type}">${html}</span>`;
}

function isCrypto(price) {
  // ราคา > 10 ถือว่าเป็น Crypto/Gold → ใช้ทศนิยม 2 ตำแหน่ง
  // ราคา < 10 เป็น Forex → ใช้ทศนิยม 5 ตำแหน่ง
  return price > 10;
}

function fmtPrice(n) {
  if (!n) return '0';
  if (n >= 1000) return n.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
  if (n >= 1)    return n.toFixed(2);
  return n.toFixed(5);
}

// ═══════════════════════════════════════════
// DATA LAYER — localStorage
// ═══════════════════════════════════════════
const STORAGE_KEY = 'selflearning_trading_journal_v1';

function loadTrades() {
  try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
  catch { return []; }
}

function saveTrades(trades) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(trades));
}

// ═══════════════════════════════════════════
// TAB NAVIGATION
// ═══════════════════════════════════════════
function switchTab(name) {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.getElementById('panel-' + name).classList.add('active');
  event.target.classList.add('active');

  // Scroll to top — desktop: scroll .main, mobile: scroll window
  if (window.innerWidth > 1024) {
    const mainEl = document.querySelector('.main');
    if (mainEl) mainEl.scrollTop = 0;
  } else {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  if (name === 'dashboard') renderDashboard();
  if (name === 'history')   renderHistory();
  if (name === 'summary')   renderSummary();
}

// ═══════════════════════════════════════════
// FORM — DIRECTION / OUTCOME TOGGLES
// ═══════════════════════════════════════════
function setDir(dir) {
  const prevDir = document.getElementById('f_direction').value;
  document.getElementById('f_direction').value = dir;
  document.querySelectorAll('.dir-btn').forEach(b => b.classList.remove('active'));
  document.querySelector('.dir-btn.' + dir.toLowerCase()).classList.add('active');

  // Show/hide SHORT indicators
  const slHint = document.getElementById('slShortHint');
  const tpHint = document.getElementById('tpShortHint');
  if (slHint) slHint.classList.toggle('show', dir === 'SHORT');
  if (tpHint) tpHint.classList.toggle('show', dir === 'SHORT');

  // Auto-swap SL ↔ TP when switching direction (if both have values)
  const slEl = document.getElementById('f_sl');
  const tpEl = document.getElementById('f_tp');
  const slVal = parseFloat(slEl.value);
  const tpVal = parseFloat(tpEl.value);
  const entry = parseFloat(document.getElementById('f_entry').value);

  if (slVal && tpVal && entry && prevDir !== dir) {
    // Switching direction: swap SL and TP values so they stay on correct side
    slEl.value = tpVal.toString();
    tpEl.value = slVal.toString();
  } else if (dir === 'SHORT' && slVal && tpVal && entry) {
    // Validate SHORT: SL must be above Entry, TP must be below Entry
    const slAbove = slVal > entry;
    const tpBelow = tpVal < entry;
    if (!slAbove || !tpBelow) {
      // Swap to fix
      slEl.value = tpVal.toString();
      tpEl.value = slVal.toString();
    }
  }

  calcAll();
}

function setOutcome(outcome, fromUser = false) {
  const map = { WIN:'win', LOSS:'loss', BE:'be' };
  const cls = map[outcome] || 'win';
  document.getElementById('f_outcome').value = map[outcome] ? outcome : 'WIN';
  document.querySelectorAll('.out-btn').forEach(b => b.classList.remove('active'));
  const btn = document.querySelector('.out-btn.' + cls);
  if (btn) btn.classList.add('active');
  // ถ้า user กดเอง → set flag ป้องกัน auto-override
  if (fromUser) {
    document.getElementById('f_outcome').dataset.userOverride = '1';
  }
}

// ═══════════════════════════════════════════
// FORM — AUTO CALCULATIONS
// ═══════════════════════════════════════════
function calcAll() {
  const entry   = parseFloat(document.getElementById('f_entry').value);
  const sl      = parseFloat(document.getElementById('f_sl').value);
  const tp      = parseFloat(document.getElementById('f_tp').value);
  const exit    = parseFloat(document.getElementById('f_exit').value);
  const balance = parseFloat(document.getElementById('f_balance').value);
  const riskPct = parseFloat(document.getElementById('f_risk_pct').value) || 1;
  const feePct  = parseFloat(document.getElementById('f_fee_pct').value) || 0;
  const dir     = document.getElementById('f_direction').value;

  // ── Risk USD + Risk Pill (USD & THB) ──────────
  const riskUSD   = balance ? (balance * riskPct / 100) : null;
  const riskPillEl = document.getElementById('riskPill');
  const riskPillTx = document.getElementById('riskPillText');
  if (riskUSD && riskPillEl && riskPillTx) {
    const riskThb = _gbUsdThb ? (riskUSD * _gbUsdThb) : null;
    const thbStr  = riskThb ? ` = ฿${riskThb.toLocaleString('th-TH',{maximumFractionDigits:0})}` : '';
    riskPillTx.textContent = `⚠ MAX LOSS IF SL HIT: $${riskUSD.toFixed(2)}${thbStr}`;
    riskPillEl.style.display = 'inline-flex';
  } else if (riskPillEl) {
    riskPillEl.style.display = 'none';
  }

  // ── THB sub-labels under price fields ─────────
  function setThbSub(id, usdVal) {
    const el = document.getElementById(id);
    if (!el) return;
    if (usdVal && _gbUsdThb) {
      const thb = usdVal * _gbUsdThb;
      el.textContent = `≈ ฿${thb.toLocaleString('th-TH',{minimumFractionDigits:0,maximumFractionDigits:0})}`;
    } else {
      el.textContent = '';
    }
  }
  setThbSub('sub_entry',   entry   || null);
  setThbSub('sub_sl',      sl      || null);
  setThbSub('sub_tp',      tp      || null);
  setThbSub('sub_exit',    exit    || null);
  setThbSub('sub_balance', balance || null);

  // ── SL distance ────────────────────────────────
  const slDist = (entry && sl) ? Math.abs(entry - sl) : null;

  // ── Position size (6dp + USD value) ───────────
  let posSize = null;
  if (riskUSD && slDist && slDist > 0) {
    posSize = riskUSD / slDist;
    const posUSD = posSize * entry;
    const posThb = _gbUsdThb ? posUSD * _gbUsdThb : null;
    const thbPart = posThb ? ` / ฿${posThb.toLocaleString('th-TH',{maximumFractionDigits:0})}` : '';
    const posSub = `มูลค่า: $${posUSD.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2})}${thbPart}`;
    const sizeValEl = document.getElementById('c_size_val');
    const sizeSubEl = document.getElementById('c_size_usd');
    if (sizeValEl) { sizeValEl.textContent = posSize.toFixed(6) + ' units'; sizeValEl.className = 'calc-card-value'; }
    if (sizeSubEl) sizeSubEl.textContent = posSub;
    const hidSize = document.getElementById('c_size');
    if (hidSize) hidSize.value = posSize.toFixed(6) + ' units';
  } else {
    const sizeValEl = document.getElementById('c_size_val');
    const sizeSubEl = document.getElementById('c_size_usd');
    if (sizeValEl) { sizeValEl.textContent = '—'; sizeValEl.className = 'calc-card-value'; }
    if (sizeSubEl) sizeSubEl.textContent = 'กรอก Entry · SL · Balance ก่อน';
    const hidSize = document.getElementById('c_size');
    if (hidSize) hidSize.value = '—';
  }

  // ── Fee hint (round-trip) ──────────────────────
  const feeHintEl     = document.getElementById('feeHint');
  const feeTotalPctEl = document.getElementById('feeTotalPct');
  const feeNetHintEl  = document.getElementById('feeNetHint');
  const feeNetLabelEl = document.getElementById('feeNetLabel');

  if (feePct > 0 && entry && posSize) {
    const feeOpen    = posSize * entry * (feePct / 100);
    const feeEst     = feeOpen * 2;
    const feeThb     = _gbUsdThb ? feeEst * _gbUsdThb : null;
    const feeThbStr  = feeThb ? ` / ฿${feeThb.toLocaleString('th-TH',{maximumFractionDigits:0})}` : '';
    if (feeTotalPctEl) feeTotalPctEl.textContent =
      `Round-trip ${(feePct*2).toFixed(2)}% · ≈ $${feeEst.toFixed(2)}${feeThbStr} ต่อ Position`;
    if (feeHintEl) feeHintEl.style.display = 'flex';
  } else {
    if (feeHintEl) feeHintEl.style.display = 'none';
  }

  // ── R:R ───────────────────────────────────────
  const rrValEl = document.getElementById('c_rr_val');
  const rrSubEl = document.getElementById('c_rr_sub');
  const hidRr   = document.getElementById('c_rr');
  if (tp && entry && sl && slDist > 0) {
    const tpDist = Math.abs(tp - entry);
    const rr = tpDist / slDist;
    const rrTxt = '1 : ' + rr.toFixed(2);
    if (rrValEl) { rrValEl.textContent = rrTxt; rrValEl.className = 'calc-card-value' + (rr >= 2 ? ' green' : rr >= 1 ? '' : ' red'); }
    if (rrSubEl) rrSubEl.textContent = rr >= 2 ? '✓ R:R ดี — กำไร ≥ 2× ความเสี่ยง' : rr >= 1 ? 'R:R พอใช้ได้' : '⚠ R:R ต่ำเกินไป';
    if (hidRr) hidRr.value = rrTxt;
  } else {
    if (rrValEl) { rrValEl.textContent = '—'; rrValEl.className = 'calc-card-value'; }
    if (rrSubEl) rrSubEl.textContent = 'ต้องมี TP เพื่อคำนวณ';
    if (hidRr) hidRr.value = '—';
  }

  // ── P&L & R-Multiple ──────────────────────────
  const pnlValEl  = document.getElementById('c_pnl_val');
  const pnlSubEl  = document.getElementById('c_pnl_sub');
  const rmulValEl = document.getElementById('c_rmul_val');
  const rmulSubEl = document.getElementById('c_rmul_sub');
  const hidPnl    = document.getElementById('c_pnl');
  const hidRmul   = document.getElementById('c_rmul');

  if (exit && entry && sl && slDist > 0) {
    const pnlPts  = (dir === 'LONG') ? (exit - entry) : (entry - exit);
    const rMul    = pnlPts / slDist;
    const grossPnl = riskUSD ? (rMul * riskUSD) : null;

    // R-Multiple card
    const rmulTxt = (rMul >= 0 ? '+' : '') + rMul.toFixed(2) + 'R';
    if (rmulValEl) { rmulValEl.textContent = rmulTxt; rmulValEl.className = 'calc-card-value' + (rMul >= 0 ? ' green' : ' red'); }
    if (rmulSubEl) rmulSubEl.textContent = rMul >= 2 ? '🏆 ยอดเยี่ยม — กำไรมากกว่า 2× Risk' : rMul >= 0 ? '✓ เป็นบวก' : '✗ ขาดทุน';
    if (hidRmul) hidRmul.value = rmulTxt;

    if (grossPnl !== null) {
      const feeOpen  = posSize ? posSize * entry * (feePct / 100) : 0;
      const feeClose = posSize ? posSize * exit  * (feePct / 100) : 0;
      const totalFee = feeOpen + feeClose;
      const netPnl   = grossPnl - totalFee;
      const signNet  = netPnl >= 0 ? '+' : '';
      const signGross= grossPnl >= 0 ? '+' : '';
      const pnlTxt   = signNet + '$' + netPnl.toFixed(2);

      if (pnlValEl) { pnlValEl.textContent = pnlTxt; pnlValEl.className = 'calc-card-value' + (netPnl >= 0 ? ' green' : ' red'); }

      // Sub: show gross vs net if fee > 0, plus THB
      const netThbVal = _gbUsdThb ? netPnl * _gbUsdThb : null;
      const netThbStr = netThbVal ? `  / ${netPnl>=0?'+':''}฿${Math.abs(netThbVal).toLocaleString('th-TH',{maximumFractionDigits:0})}` : '';
      if (feePct > 0 && totalFee > 0) {
        if (pnlSubEl) pnlSubEl.textContent = `Gross ${signGross}$${grossPnl.toFixed(2)} − Fee $${totalFee.toFixed(2)}${netThbStr}`;
        if (feeNetHintEl) feeNetHintEl.style.display = 'none';
      } else {
        if (pnlSubEl) pnlSubEl.textContent = `P&L รวม${netThbStr ? ' · ' + netThbStr.trim() : ''}`;
      }
      if (hidPnl) hidPnl.value = pnlTxt;

      // ── Auto-set OUTCOME ตาม P&L NET (ถ้า user ไม่ได้ override) ──
      if (!document.getElementById('f_outcome').dataset.userOverride) {
        if (netPnl > 0)      setOutcome('WIN');
        else if (netPnl < 0) setOutcome('LOSS');
        else                 setOutcome('BE');
      }
    } else {
      if (pnlValEl) { pnlValEl.textContent = (rMul >= 0?'+':'')+rMul.toFixed(2)+'R'; pnlValEl.className = 'calc-card-value'; }
      if (pnlSubEl) pnlSubEl.textContent = 'กรอก Balance เพื่อดู USD';
      if (hidPnl) hidPnl.value = '—';
    }
  } else {
    if (pnlValEl)  { pnlValEl.textContent  = '—'; pnlValEl.className  = 'calc-card-value'; }
    if (pnlSubEl)  pnlSubEl.textContent  = 'กรอก Exit Price ก่อน';
    if (rmulValEl) { rmulValEl.textContent = '—'; rmulValEl.className = 'calc-card-value'; }
    if (rmulSubEl) rmulSubEl.textContent = 'เช่น +2R = กำไร 2× ของ Risk';
    if (hidPnl)  hidPnl.value  = '—';
    if (hidRmul) hidRmul.value = '—';
    if (feeNetHintEl) feeNetHintEl.style.display = 'none';
  }
} // end calcAll

// ═══════════════════════════════════════════
// SAVE TRADE
// ═══════════════════════════════════════════
function saveTrade(editIndex = null) {
  // Exit is now optional (open position allowed)
  const required = ['f_date','f_instrument','f_entry','f_sl','f_balance'];
  for (let id of required) {
    if (!document.getElementById(id).value) {
      showToast('⚠ กรุณากรอกข้อมูลที่จำเป็น (ช่องที่มี *)', 'error');
      return;
    }
  }

  const entry   = parseFloat(document.getElementById('f_entry').value);
  const sl      = parseFloat(document.getElementById('f_sl').value);
  const tp      = parseFloat(document.getElementById('f_tp').value) || null;
  const exitVal = document.getElementById('f_exit').value;
  const exit    = exitVal ? parseFloat(exitVal) : 0;
  const isOpen  = !exitVal || exit === 0;
  const balance = parseFloat(document.getElementById('f_balance').value);
  const riskPct = parseFloat(document.getElementById('f_risk_pct').value) || 1;
  const feePct  = parseFloat(document.getElementById('f_fee_pct').value) || 0;
  const dir     = document.getElementById('f_direction').value;
  const slDist  = Math.abs(entry - sl);
  const riskUSD = balance * riskPct / 100;

  let pnlPts   = isOpen ? 0 : (dir === 'LONG') ? (exit - entry) : (entry - exit);
  let rMul     = isOpen ? 0 : (slDist > 0 ? (pnlPts / slDist) : 0);
  let grossPnl = isOpen ? 0 : rMul * riskUSD;
  let rrRatio  = (tp && slDist > 0) ? (Math.abs(tp - entry) / slDist) : null;

  // Fee calculation (only when position is closed)
  const posSize  = slDist > 0 ? riskUSD / slDist : 0;
  const feeOpen  = isOpen ? 0 : posSize * entry * (feePct / 100);
  const feeClose = isOpen ? 0 : posSize * exit  * (feePct / 100);
  const totalFee = feeOpen + feeClose;
  const netPnl   = grossPnl - totalFee;

  const trade = {
    id: editIndex !== null ? loadTrades()[editIndex].id : Date.now(),
    date:       document.getElementById('f_date').value,
    instrument: document.getElementById('f_instrument').value,
    direction:  dir,
    timeframe:  document.getElementById('f_timeframe').value,
    entry, sl, tp,
    exit:    isOpen ? 0 : exit,
    isOpen:  isOpen,
    balance, riskPct,
    feePct:  +feePct.toFixed(3),
    feeUSD:  +totalFee.toFixed(2),
    riskUSD: +riskUSD.toFixed(2),
    grossPnl:+grossPnl.toFixed(2),
    pnlUSD:  +netPnl.toFixed(2),
    rMul:    +rMul.toFixed(2),
    rrRatio: rrRatio ? +rrRatio.toFixed(2) : null,
    outcome:  isOpen ? 'OPEN' : document.getElementById('f_outcome').value,
    setup:    document.getElementById('f_setup').value,
    reason:   document.getElementById('f_reason').value,
    lesson:   document.getElementById('f_lesson').value,
    emotion:  document.getElementById('f_emotion').value,
    followed: document.getElementById('f_followed').value,
    createdAt: new Date().toISOString()
  };

  const trades = loadTrades();
  if (editIndex !== null) {
    trades[editIndex] = trade;
    showToast(isOpen ? '⏳ บันทึก Open Position แล้ว — ค่อยมา Edit ผลลัพธ์ภายหลัง' : '✓ อัปเดต Trade เรียบร้อย', 'success');
  } else {
    trades.push(trade);
    showToast(isOpen ? '⏳ บันทึก Open Position แล้ว — อย่าลืมมา Edit ผลลัพธ์!' : '⚡ บันทึก Trade ลง Journal แล้ว', 'success');
  }
  saveTrades(trades);
  // Reset edit mode
  _editingIdx = null;
  document.getElementById('editModeBanner').classList.remove('show');
  const saveBtn = document.querySelector('#panel-new-trade .btn-primary');
  if (saveBtn) { saveBtn.innerHTML = '⚡ SAVE TRADE · บันทึก'; saveBtn.onclick = () => saveTrade(); }
  // Refresh balance card net display
  updateGlobalBalance();
  resetForm();
}

function resetForm() {
  // Thai timezone date
  const now = new Date();
  const thDate = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Bangkok' }));
  const yyyy = thDate.getFullYear();
  const mm   = String(thDate.getMonth() + 1).padStart(2, '0');
  const dd   = String(thDate.getDate()).padStart(2, '0');
  const today = `${yyyy}-${mm}-${dd}`;

  document.getElementById('f_date').value       = today;
  updateDateDisplay(today);
  document.getElementById('f_instrument').value = '';
  document.getElementById('f_timeframe').value  = '1D';
  document.getElementById('f_entry').value      = '';
  document.getElementById('f_sl').value         = '';
  document.getElementById('f_tp').value         = '';
  document.getElementById('f_exit').value       = '';
  document.getElementById('f_balance').value    = '';
  delete document.getElementById('f_balance').dataset.userEdited;
  document.getElementById('f_risk_pct').value   = '1';
  // Keep fee % as-is (user preference per broker/exchange)
  document.getElementById('f_reason').value     = '';
  document.getElementById('f_lesson').value     = '';
  document.getElementById('f_emotion').value    = '';
  document.getElementById('f_followed').value   = 'YES';
  document.getElementById('c_size').value       = '';
  document.getElementById('c_rr').value         = '';
  document.getElementById('c_pnl').value        = '';
  document.getElementById('c_rmul').value       = '';
  // Reset fee hints
  const feeNetHint = document.getElementById('feeNetHint');
  if (feeNetHint) feeNetHint.style.display = 'none';
  const feeHint = document.getElementById('feeHint');
  if (feeHint) feeHint.style.display = 'none';
  // Reset SHORT indicators
  const slHint = document.getElementById('slShortHint');
  const tpHint = document.getElementById('tpShortHint');
  if (slHint) slHint.classList.remove('show');
  if (tpHint) tpHint.classList.remove('show');
  // Clear outcome user-override flag so auto-detect works on next trade
  delete document.getElementById('f_outcome').dataset.userOverride;
  // Clear smart-fill UI
  setStatusBadge('', '');
  const hint = document.getElementById('balanceHint');
  if (hint) hint.innerHTML = '';
  document.getElementById('f_balance').classList.remove('balance-auto');
  setDir('LONG');
  setOutcome('WIN');
}

// ═══════════════════════════════════════════
// DASHBOARD
// ═══════════════════════════════════════════
function renderDashboard() {
  const trades = loadTrades();
  const wins   = trades.filter(t => t.outcome === 'WIN');
  const losses = trades.filter(t => t.outcome === 'LOSS');
  const totalPnL  = trades.reduce((s,t) => s + (t.pnlUSD||0), 0);
  const winRate   = trades.length ? (wins.length / trades.length * 100) : 0;
  const avgWin    = wins.length ? wins.reduce((s,t)=>s+(t.pnlUSD||0),0)/wins.length : 0;
  const avgLoss   = losses.length ? Math.abs(losses.reduce((s,t)=>s+(t.pnlUSD||0),0)/losses.length) : 0;
  const pf        = avgLoss > 0 ? (avgWin * wins.length) / (avgLoss * losses.length) : (wins.length > 0 ? Infinity : 0);
  const avgRMul   = trades.length ? trades.reduce((s,t)=>s+(t.rMul||0),0)/trades.length : 0;
  const totalR    = trades.reduce((s,t)=>s+(t.rMul||0),0);

  const kpis = [
    { label: 'TOTAL P&L · กำไร/ขาดทุนรวม', value: fmtPnl(totalPnL), cls: totalPnL>=0?'green':'red', sub: `${trades.length} trades ทั้งหมด`, accent:'var(--neon-orange)', tip:'kpi_pnl' },
    { label: 'WIN RATE · อัตราชนะ', value: winRate.toFixed(1)+'%', cls: winRate>=50?'green':'red', sub: `${wins.length} ชนะ / ${losses.length} แพ้`, accent:'var(--neon-green)', tip:'kpi_winrate' },
    { label: 'PROFIT FACTOR · ประสิทธิภาพระบบ', value: isFinite(pf)?pf.toFixed(2):'∞', cls: pf>=1?'green':'red', sub: 'Avg Win / Avg Loss', accent:'var(--neon-cyan)', tip:'kpi_pf' },
    { label: 'TOTAL R · R รวม', value: (totalR>=0?'+':'')+totalR.toFixed(2)+'R', cls: totalR>=0?'green':'red', sub: `เฉลี่ย ${avgRMul>=0?'+':''}${avgRMul.toFixed(2)}R/trade`, accent:'var(--neon-purple)', tip:'kpi_r' },
    { label: 'AVG WIN · กำไรเฉลี่ย', value: fmtPnl(avgWin),  cls:'green', sub:`จาก ${wins.length} trade ที่ชนะ`, accent:'var(--neon-green)', tip:'kpi_avgwin' },
    { label: 'AVG LOSS · ขาดทุนเฉลี่ย', value: fmtPnl(-avgLoss), cls:'red', sub:`จาก ${losses.length} trade ที่แพ้`, accent:'var(--neon-red)', tip:'kpi_avgloss' },
  ];

  document.getElementById('kpiGrid').innerHTML = kpis.map(k => `
    <div class="stat-card" data-tip="${k.tip}">
      <div class="stat-label">${k.label}</div>
      <div class="stat-value ${k.cls}">${k.value}</div>
      <div class="stat-sub">${k.sub}</div>
      <div class="accent-line" style="background:${k.accent};opacity:0.5;"></div>
    </div>
  `).join('');

  drawEquityCurve(trades);

  // Recent 10
  const recent = [...trades].sort((a,b)=>b.date.localeCompare(a.date)).slice(0,10);
  document.getElementById('recentTradesTable').innerHTML = renderTradeTable(recent, false);

  // Update balance P&L summary tag
  const gbAmount   = parseFloat(document.getElementById('gb_amount')?.value) || 0;
  const gbCurrency = document.getElementById('gb_currency')?.value || 'USD';
  let usdBal = gbCurrency === 'USD' ? gbAmount : (_gbUsdThb ? gbAmount / _gbUsdThb : 0);
  _renderBalancePnlSummary(usdBal);
}

// ═══════════════════════════════════════════
// EQUITY CURVE CANVAS
// ═══════════════════════════════════════════
// Store equity chart points globally for hover
let _equityPoints = [];
let _equityTrades = [];
let _equityPad    = {};

function drawEquityCurve(trades) {
  const canvas = document.getElementById('equityCanvas');
  const wrap   = canvas.parentElement;
  canvas.width  = wrap.offsetWidth || 800;
  canvas.height = wrap.offsetHeight || 240;
  const ctx = canvas.getContext('2d');
  const W = canvas.width, H = canvas.height;
  ctx.clearRect(0,0,W,H);

  if (trades.length === 0) {
    ctx.fillStyle = 'rgba(74,102,112,0.5)';
    ctx.font = '12px "JetBrains Mono", monospace';
    ctx.textAlign = 'center';
    ctx.fillText('NO DATA', W/2, H/2);
    return;
  }

  const sorted = [...trades].sort((a,b)=>a.date.localeCompare(b.date));
  let cumPnL = 0;
  const points = [0, ...sorted.map(t => { cumPnL += (t.pnlUSD||0); return cumPnL; })];

  // Store for hover
  _equityTrades = sorted;

  const minV = Math.min(...points);
  const maxV = Math.max(...points);
  const range = maxV - minV || 1;

  const pad = { t:20, b:30, l:70, r:20 };
  _equityPad = pad;
  const cW = W - pad.l - pad.r;
  const cH = H - pad.t - pad.b;

  const px = (i) => pad.l + (i / (points.length-1)) * cW;
  const py = (v) => pad.t + cH - ((v - minV) / range) * cH;

  // Store pixel coords for hover detection
  _equityPoints = points.map((v,i) => ({ x: px(i), y: py(v), cumPnL: v, trade: sorted[i-1] || null }));

  // Grid lines
  ctx.strokeStyle = 'rgba(0,245,255,0.05)';
  ctx.lineWidth = 1;
  for (let i=0; i<=4; i++) {
    const y = pad.t + (i/4)*cH;
    ctx.beginPath(); ctx.moveTo(pad.l,y); ctx.lineTo(W-pad.r,y); ctx.stroke();
    const val = maxV - (i/4)*range;
    ctx.fillStyle = 'rgba(74,102,112,0.7)';
    ctx.font = '12px "JetBrains Mono", monospace';
    ctx.textAlign = 'right';
    ctx.fillText((val>=0?'+':'')+val.toFixed(0), pad.l-6, y+4);
  }

  // Zero line
  if (minV < 0 && maxV > 0) {
    const zy = py(0);
    ctx.strokeStyle = 'rgba(74,102,112,0.4)';
    ctx.setLineDash([4,4]);
    ctx.beginPath(); ctx.moveTo(pad.l,zy); ctx.lineTo(W-pad.r,zy); ctx.stroke();
    ctx.setLineDash([]);
  }

  // Gradient fill
  const grad = ctx.createLinearGradient(0, pad.t, 0, pad.t+cH);
  grad.addColorStop(0, cumPnL >= 0 ? 'rgba(0,255,136,0.3)' : 'rgba(255,45,85,0.3)');
  grad.addColorStop(1, 'rgba(0,0,0,0)');

  ctx.beginPath();
  ctx.moveTo(px(0), py(0));
  points.forEach((v,i) => ctx.lineTo(px(i), py(v)));
  ctx.lineTo(px(points.length-1), pad.t+cH);
  ctx.lineTo(px(0), pad.t+cH);
  ctx.closePath();
  ctx.fillStyle = grad;
  ctx.fill();

  // Line
  const lineColor = cumPnL >= 0 ? '#00ff88' : '#ff2d55';
  ctx.beginPath();
  ctx.strokeStyle = lineColor;
  ctx.lineWidth = 2;
  ctx.shadowColor = lineColor;
  ctx.shadowBlur = 8;
  points.forEach((v,i) => { i===0 ? ctx.moveTo(px(i),py(v)) : ctx.lineTo(px(i),py(v)); });
  ctx.stroke();
  ctx.shadowBlur = 0;

  // Current value dot
  const lastX = px(points.length-1);
  const lastY = py(points[points.length-1]);
  ctx.beginPath();
  ctx.arc(lastX, lastY, 5, 0, Math.PI*2);
  ctx.fillStyle = lineColor;
  ctx.shadowColor = lineColor;
  ctx.shadowBlur = 12;
  ctx.fill();
  ctx.shadowBlur = 0;
}

// ═══════════════════════════════════════════
// EQUITY CURVE — HOVER TOOLTIP
// ═══════════════════════════════════════════
function equityMouseMove(e) {
  if (!_equityPoints.length) return;
  const canvas  = document.getElementById('equityCanvas');
  const tooltip = document.getElementById('equityTooltip');
  const rect    = canvas.getBoundingClientRect();
  const scaleX  = canvas.width / rect.width;
  const mouseX  = (e.clientX - rect.left) * scaleX;

  // Find closest point
  let closest = null, minDist = Infinity;
  for (const pt of _equityPoints) {
    const d = Math.abs(pt.x - mouseX);
    if (d < minDist) { minDist = d; closest = pt; }
  }
  if (!closest || minDist > (canvas.width / _equityPoints.length) * 1.5) {
    tooltip.style.display = 'none'; return;
  }

  const t = closest.trade;
  const pnl = closest.cumPnL;
  const sign = pnl >= 0 ? '+' : '';
  const color = pnl >= 0 ? 'var(--neon-green)' : 'var(--neon-red)';
  const pnlThb = _gbUsdThb ? pnl * _gbUsdThb : null;
  const thbStr = pnlThb ? ` / ${sign}฿${Math.abs(pnlThb).toLocaleString('th-TH',{maximumFractionDigits:0})}` : '';

  document.getElementById('ettDate').textContent = t ? `📅 ${t.date}  ${t.instrument||''}` : '📅 เริ่มต้น';
  document.getElementById('ettPnl').innerHTML =
    `<span style="color:${color};">${sign}$${pnl.toFixed(2)}${thbStr}</span>`;

  let detail = '';
  if (t) {
    const tSign = (t.pnlUSD||0) >= 0 ? '+' : '';
    const outcome = t.outcome === 'WIN' ? '✓ WIN' : t.outcome === 'LOSS' ? '✗ LOSS' : '= B/E';
    const isOpen = !t.exit || t.exit === 0;
    detail = `${outcome}  ${isOpen ? '⏳ รอปิด' : tSign+'$'+Math.abs(t.pnlUSD||0).toFixed(2)+' trade นี้'}  ${(t.rMul||0)>=0?'+':''}${(t.rMul||0).toFixed(2)}R`;
  } else {
    detail = 'ยอดพอร์ตเริ่มต้น';
  }
  document.getElementById('ettDetail').textContent = detail;

  // Position tooltip
  const tipW = 200, tipH = 80;
  const canvasPixelX = (closest.x / canvas.width) * rect.width;
  let tipLeft = e.clientX - rect.left + 14;
  if (tipLeft + tipW > rect.width) tipLeft = e.clientX - rect.left - tipW - 14;
  let tipTop  = e.clientY - rect.top  - 40;
  if (tipTop < 0) tipTop = 4;
  tooltip.style.left    = tipLeft + 'px';
  tooltip.style.top     = tipTop  + 'px';
  tooltip.style.display = 'block';
}

function equityMouseLeave() {
  const tooltip = document.getElementById('equityTooltip');
  if (tooltip) tooltip.style.display = 'none';
}

// ═══════════════════════════════════════════
// HISTORY
// ═══════════════════════════════════════════
function renderHistory() {
  const trades = loadTrades();

  // Build filter options
  const instruments = [...new Set(trades.map(t=>t.instrument).filter(Boolean))];
  const setups = [...new Set(trades.map(t=>t.setup).filter(Boolean))];

  const iSel = document.getElementById('filterInstrument');
  const sSel = document.getElementById('filterSetup');
  const curI = iSel.value, curS = sSel.value;

  iSel.innerHTML = '<option value="">All Instruments</option>' + instruments.map(i=>`<option value="${i}" ${i===curI?'selected':''}>${i}</option>`).join('');
  sSel.innerHTML = '<option value="">All Setups</option>' + setups.map(s=>`<option value="${s}" ${s===curS?'selected':''}>${s}</option>`).join('');

  let filtered = [...trades].sort((a,b)=>b.date.localeCompare(a.date));

  const fI = document.getElementById('filterInstrument').value;
  const fO = document.getElementById('filterOutcome').value;
  const fS = document.getElementById('filterSetup').value;
  const fQ = document.getElementById('filterSearch').value.toLowerCase();

  if (fI) filtered = filtered.filter(t=>t.instrument===fI);
  if (fO) filtered = filtered.filter(t=>t.outcome===fO);
  if (fS) filtered = filtered.filter(t=>t.setup===fS);
  if (fQ) filtered = filtered.filter(t=>
    (t.instrument||'').toLowerCase().includes(fQ) ||
    (t.reason||'').toLowerCase().includes(fQ) ||
    (t.lesson||'').toLowerCase().includes(fQ) ||
    (t.setup||'').toLowerCase().includes(fQ)
  );

  document.getElementById('historyTable').innerHTML = filtered.length
    ? renderTradeTable(filtered, true)
    : emptyState('No trades match your filters');
}

function renderTradeTable(trades, showActions) {
  if (!trades.length) return emptyState('No trades yet — log your first trade!');
  return `
  <table>
    <thead><tr>
      <th>DATE</th><th>INSTRUMENT</th><th>DIR</th><th>ENTRY</th><th>EXIT</th>
      <th>P&L</th><th>R-MUL</th><th>R:R</th><th>OUTCOME</th><th>SETUP</th>
      ${showActions?'<th>SYS</th><th>ACTIONS</th>':''}
    </tr></thead>
    <tbody>
    ${trades.map((t,i) => {
      const allTrades = loadTrades();
      const realIdx   = allTrades.findIndex(x=>x.id===t.id);
      const isOpen    = !t.exit || parseFloat(t.exit) === 0;
      const outcomeClass = isOpen ? '' : `badge-${(t.outcome||'').toLowerCase()}`;
      const outcomeLabel = isOpen
        ? '<span class="open-position-badge">⏳ รอปิด Position</span>'
        : `<span class="badge badge-${(t.outcome||'be').toLowerCase()}">${t.outcome||'—'}</span>`;
      const pnlLabel = isOpen
        ? '<span style="color:var(--neon-orange);font-size:11px;">⏳ ยังไม่ปิด</span>'
        : fmtPnl(t.pnlUSD||0);
      const rmulLabel = isOpen ? '—' : `${(t.rMul>=0?'+':'')+((t.rMul||0).toFixed(2))}R`;
      return `
      <tr style="${isOpen ? 'background:rgba(247,147,26,0.03);' : ''}">
        <td style="font-family:'JetBrains Mono',monospace;font-size:13px;">${t.date}</td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:13px;">${t.instrument||'—'}</td>
        <td><span class="badge badge-${(t.direction||'long').toLowerCase()}">${t.direction||'—'}</span></td>
        <td>${fmt(t.entry)}</td>
        <td>${isOpen ? '<span style="color:var(--neon-orange);">⏳</span>' : fmt(t.exit)}</td>
        <td class="${isOpen ? '' : (t.pnlUSD||0)>=0?'green':'red'}" style="font-family:'JetBrains Mono',monospace;">${pnlLabel}</td>
        <td class="${isOpen ? '' : (t.rMul||0)>=0?'green':'red'}" style="font-family:'JetBrains Mono',monospace;">${rmulLabel}</td>
        <td style="font-family:'JetBrains Mono',monospace;">${t.rrRatio ? '1:'+t.rrRatio : '—'}</td>
        <td>${outcomeLabel}</td>
        <td style="font-size:13px;">${t.setup||'—'}</td>
        ${showActions?`
        <td><span class="badge" style="${t.followed==='YES'?'color:var(--neon-green)':t.followed==='NO'?'color:var(--neon-red)':'color:var(--neon-cyan)'}">${t.followed||'—'}</span></td>
        <td>
          <button class="btn btn-cyan btn-sm" onclick="editTrade(${realIdx})">EDIT</button>
          <button class="btn btn-red btn-sm" onclick="deleteTrade(${realIdx})" style="margin-left:4px;">DEL</button>
        </td>`:''
        }
      </tr>`;
    }).join('')}
    </tbody>
  </table>`;
}

function deleteTrade(idx) {
  if (!confirm('ลบ Trade นี้ออกจาก Journal?')) return;
  const trades = loadTrades();
  trades.splice(idx, 1);
  saveTrades(trades);
  renderHistory();
  showToast('ลบ Trade แล้ว', 'info');
}

let _editingIdx = null; // track current edit index

function editTrade(idx) {
  const t = loadTrades()[idx];
  if (!t) return;

  _editingIdx = idx;

  document.getElementById('f_date').value       = t.date;
  document.getElementById('f_instrument').value = t.instrument;
  document.getElementById('f_timeframe').value  = t.timeframe || '1D';
  document.getElementById('f_entry').value      = t.entry;
  document.getElementById('f_sl').value         = t.sl;
  document.getElementById('f_tp').value         = t.tp || '';
  // Open positions: show empty exit so user can fill in
  document.getElementById('f_exit').value       = (t.isOpen || !t.exit || t.exit === 0) ? '' : t.exit;
  document.getElementById('f_balance').value    = t.balance;
  delete document.getElementById('f_balance').dataset.userEdited;
  document.getElementById('f_risk_pct').value   = t.riskPct;
  if (t.feePct !== undefined) document.getElementById('f_fee_pct').value = t.feePct;
  document.getElementById('f_reason').value     = t.reason || '';
  document.getElementById('f_lesson').value     = t.lesson || '';
  document.getElementById('f_emotion').value    = t.emotion || '';
  document.getElementById('f_followed').value   = t.followed || 'YES';

  setDir(t.direction);
  // Handle OPEN outcome — map to WIN as default (user can change before UPDATE)
  const outcome = (t.outcome === 'OPEN' || !t.outcome) ? 'WIN' : t.outcome;
  // Clear override flag — let auto-detect run, user can override again if needed
  delete document.getElementById('f_outcome').dataset.userOverride;
  setOutcome(outcome);
  calcAll();

  // Show edit-mode banner
  const banner = document.getElementById('editModeBanner');
  const bannerText = banner.querySelector('span');
  if (t.isOpen || t.outcome === 'OPEN') {
    bannerText.innerHTML = '⏳ แก้ไข Open Position · กรอก <strong>EXIT PRICE</strong> และ <strong>OUTCOME</strong> เพื่อปิด Position';
  } else {
    bannerText.textContent = '✏ กำลังแก้ไข Trade · แก้ไขข้อมูลแล้วกด UPDATE';
  }
  banner.classList.add('show');

  // Swap SAVE button → UPDATE
  const saveBtn = document.querySelector('#panel-new-trade .btn-primary');
  saveBtn.innerHTML = '💾 UPDATE TRADE · อัปเดต';
  saveBtn.onclick = () => saveTrade(idx);

  // Switch to new-trade tab
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.getElementById('panel-new-trade').classList.add('active');
  document.querySelector('.tab:nth-child(2)').classList.add('active');
  if (window.innerWidth > 1024) {
    const mainEl = document.querySelector('.main');
    if (mainEl) mainEl.scrollTop = 0;
  } else {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  showToast('โหลด Trade มาแก้ไขแล้ว — กด UPDATE เพื่อบันทึก', 'info');
}

function cancelEdit() {
  _editingIdx = null;
  document.getElementById('editModeBanner').classList.remove('show');
  const saveBtn = document.querySelector('#panel-new-trade .btn-primary');
  saveBtn.innerHTML = '⚡ SAVE TRADE · บันทึก';
  saveBtn.onclick = () => saveTrade();
  resetForm();
  showToast('ยกเลิกการแก้ไขแล้ว', 'info');
}

// ═══════════════════════════════════════════
// SUMMARY
// ═══════════════════════════════════════════
let summaryPeriod = 'all';

function setSummaryPeriod(period, btn) {
  summaryPeriod = period;
  document.querySelectorAll('.period-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');

  const sel = document.getElementById('periodSelectors');
  const yr  = document.getElementById('yearFilter');
  const mo  = document.getElementById('monthFilter');

  if (period === 'all') {
    sel.style.display = 'none';
  } else {
    sel.style.display = 'flex';
    const years = [...new Set(loadTrades().map(t=>t.date.slice(0,4)))].sort().reverse();
    yr.innerHTML = years.map(y=>`<option>${y}</option>`).join('');
    mo.style.display = period==='month'?'block':'none';
    mo.innerHTML = [...Array(12)].map((_,i)=>{
      const m = String(i+1).padStart(2,'0');
      const nm = new Date(2000,i,1).toLocaleString('en',{month:'short'});
      return `<option value="${m}">${nm}</option>`;
    }).join('');
    const curMo = String(new Date().getMonth()+1).padStart(2,'0');
    mo.value = curMo;
  }
  renderSummary();
}

function renderSummary() {
  let trades = loadTrades();

  if (summaryPeriod === 'year') {
    const yr = document.getElementById('yearFilter').value;
    trades = trades.filter(t=>t.date.startsWith(yr));
  } else if (summaryPeriod === 'month') {
    const yr = document.getElementById('yearFilter').value;
    const mo = document.getElementById('monthFilter').value;
    trades = trades.filter(t=>t.date.startsWith(yr+'-'+mo));
  }

  const wins   = trades.filter(t=>t.outcome==='WIN');
  const losses = trades.filter(t=>t.outcome==='LOSS');
  const totalPnL   = trades.reduce((s,t)=>s+(t.pnlUSD||0),0);
  const winRate    = trades.length ? wins.length/trades.length*100 : 0;
  const avgWin     = wins.length ? wins.reduce((s,t)=>s+(t.pnlUSD||0),0)/wins.length : 0;
  const avgLoss    = losses.length ? Math.abs(losses.reduce((s,t)=>s+(t.pnlUSD||0),0)/losses.length) : 0;
  const pf         = avgLoss > 0 ? (avgWin*wins.length)/(avgLoss*losses.length) : 0;
  const totalR     = trades.reduce((s,t)=>s+(t.rMul||0),0);
  const avgRMul    = trades.length ? totalR/trades.length : 0;
  const maxWin     = wins.length ? Math.max(...wins.map(t=>t.pnlUSD||0)) : 0;
  const maxLoss    = losses.length ? Math.min(...losses.map(t=>t.pnlUSD||0)) : 0;
  const followedYes = trades.filter(t=>t.followed==='YES').length;
  const followedPct = trades.length ? followedYes/trades.length*100 : 0;

  // Max drawdown
  let cumPnL=0, peak=0, maxDD=0;
  [...trades].sort((a,b)=>a.date.localeCompare(b.date)).forEach(t=>{
    cumPnL += (t.pnlUSD||0);
    if (cumPnL > peak) peak = cumPnL;
    const dd = peak - cumPnL;
    if (dd > maxDD) maxDD = dd;
  });

  const stats = [
    {label:'TOTAL TRADES · เทรดทั้งหมด',value:trades.length,cls:'cyan',accent:'var(--neon-cyan)',tip:'sum_trades'},
    {label:'WIN RATE · อัตราชนะ',value:winRate.toFixed(1)+'%',cls:winRate>=50?'green':'red',accent:winRate>=50?'var(--neon-green)':'var(--neon-red)',tip:'kpi_winrate'},
    {label:'TOTAL P&L · กำไร/ขาดทุนรวม',value:fmtPnl(totalPnL),cls:totalPnL>=0?'green':'red',accent:totalPnL>=0?'var(--neon-green)':'var(--neon-red)',tip:'kpi_pnl'},
    {label:'TOTAL R · R รวม',value:(totalR>=0?'+':'')+totalR.toFixed(2)+'R',cls:totalR>=0?'green':'red',accent:'var(--neon-orange)',tip:'kpi_r'},
    {label:'AVG R/TRADE · R เฉลี่ยต่อ Trade',value:(avgRMul>=0?'+':'')+avgRMul.toFixed(2)+'R',cls:avgRMul>=0?'green':'red',accent:'var(--neon-orange)',tip:'sum_avgr'},
    {label:'PROFIT FACTOR · ประสิทธิภาพ',value:pf?pf.toFixed(2):'—',cls:pf>=1?'green':'red',accent:'var(--neon-purple)',tip:'kpi_pf'},
    {label:'MAX WIN · กำไรสูงสุด',value:fmtPnl(maxWin),cls:'green',accent:'var(--neon-green)',tip:'sum_maxwin'},
    {label:'MAX LOSS · ขาดทุนสูงสุด',value:fmtPnl(maxLoss),cls:'red',accent:'var(--neon-red)',tip:'sum_maxloss'},
    {label:'MAX DRAWDOWN · ขาดทุนสะสมสูงสุด',value:fmtPnl(-maxDD),cls:'red',accent:'var(--neon-red)',tip:'sum_drawdown'},
    {label:'SYSTEM DISCIPLINE · วินัยระบบ',value:followedPct.toFixed(0)+'%',cls:followedPct>=80?'green':'red',accent:followedPct>=80?'var(--neon-green)':'var(--neon-red)',tip:'sum_discipline'},
  ];

  document.getElementById('summaryContent').innerHTML = `
    <div class="stat-grid">${stats.map(k=>`
      <div class="stat-card" data-tip="${k.tip}">
        <div class="stat-label">${k.label}</div>
        <div class="stat-value ${k.cls}">${k.value}</div>
        <div class="accent-line" style="background:${k.accent};opacity:0.5;"></div>
      </div>`).join('')}
    </div>`;

  // By Instrument
  const byInst = {};
  trades.forEach(t => {
    if (!t.instrument) return;
    if (!byInst[t.instrument]) byInst[t.instrument] = {trades:0,wins:0,pnl:0,r:0};
    byInst[t.instrument].trades++;
    if (t.outcome==='WIN') byInst[t.instrument].wins++;
    byInst[t.instrument].pnl += (t.pnlUSD||0);
    byInst[t.instrument].r   += (t.rMul||0);
  });

  document.getElementById('byInstrumentTable').innerHTML = Object.keys(byInst).length ? `
    <table><thead><tr>
      <th>INSTRUMENT · สินทรัพย์</th>
      <th>TRADES · จำนวน</th>
      <th>WIN RATE · อัตราชนะ</th>
      <th>TOTAL P&L · กำไร/ขาดทุน</th>
      <th>TOTAL R</th>
    </tr></thead>
    <tbody>${Object.entries(byInst).map(([k,v])=>`
      <tr>
        <td style="font-family:'JetBrains Mono',monospace;">${k}</td>
        <td>${v.trades}</td>
        <td class="${v.wins/v.trades>=0.5?'green':'red'}">${(v.wins/v.trades*100).toFixed(1)}%</td>
        <td class="${v.pnl>=0?'green':'red'}">${fmtPnl(v.pnl)}</td>
        <td class="${v.r>=0?'green':'red'}">${(v.r>=0?'+':'')+v.r.toFixed(2)}R</td>
      </tr>`).join('')}
    </tbody></table>` : emptyState('No data');

  // By Setup
  const bySetup = {};
  trades.filter(t=>t.setup).forEach(t => {
    if (!bySetup[t.setup]) bySetup[t.setup] = {trades:0,wins:0,pnl:0};
    bySetup[t.setup].trades++;
    if (t.outcome==='WIN') bySetup[t.setup].wins++;
    bySetup[t.setup].pnl += (t.pnlUSD||0);
  });

  document.getElementById('bySetupTable').innerHTML = Object.keys(bySetup).length ? `
    <table><thead><tr>
      <th>SETUP · รูปแบบ</th>
      <th>TRADES · จำนวน</th>
      <th>WIN RATE · อัตราชนะ</th>
      <th>TOTAL P&L · กำไร/ขาดทุน</th>
    </tr></thead>
    <tbody>${Object.entries(bySetup).sort((a,b)=>b[1].pnl-a[1].pnl).map(([k,v])=>`
      <tr>
        <td>${k}</td>
        <td>${v.trades}</td>
        <td class="${v.wins/v.trades>=0.5?'green':'red'}">${(v.wins/v.trades*100).toFixed(1)}%</td>
        <td class="${v.pnl>=0?'green':'red'}">${fmtPnl(v.pnl)}</td>
      </tr>`).join('')}
    </tbody></table>` : emptyState('No setup data');

  // Emotion
  const byEmo = {};
  trades.filter(t=>t.emotion).forEach(t => {
    if (!byEmo[t.emotion]) byEmo[t.emotion] = {trades:0,wins:0,pnl:0};
    byEmo[t.emotion].trades++;
    if (t.outcome==='WIN') byEmo[t.emotion].wins++;
    byEmo[t.emotion].pnl += (t.pnlUSD||0);
  });

  document.getElementById('emotionTable').innerHTML = Object.keys(byEmo).length ? `
    <table><thead><tr>
      <th>EMOTION STATE · สภาวะจิตใจ</th>
      <th>TRADES · จำนวน</th>
      <th>WIN RATE · อัตราชนะ</th>
      <th>P&L IMPACT · ผลกระทบ</th>
    </tr></thead>
    <tbody>${Object.entries(byEmo).map(([k,v])=>`
      <tr>
        <td>${k}</td>
        <td>${v.trades}</td>
        <td class="${v.wins/v.trades>=0.5?'green':'red'}">${(v.wins/v.trades*100).toFixed(1)}%</td>
        <td class="${v.pnl>=0?'green':'red'}">${fmtPnl(v.pnl)}</td>
      </tr>`).join('')}
    </tbody></table>` : emptyState('No emotion data');
}

// ═══════════════════════════════════════════
// EXPORT / IMPORT
// ═══════════════════════════════════════════
function exportExcel() {
  const trades = loadTrades();
  if (!trades.length) { showToast('ยังไม่มีข้อมูล Trade ที่จะ Export', 'error'); return; }

  const headers = ['Date','Instrument','Direction','Timeframe','Entry','SL','TP','Exit',
    'Balance','Risk%','RiskUSD','PnL_USD','R_Multiple','RR_Ratio','Outcome','Setup',
    'Reason','Lesson','Emotion','Followed_System','Created'];

  const rows = trades.map(t => [
    t.date, t.instrument, t.direction, t.timeframe,
    t.entry, t.sl, t.tp||'', t.exit,
    t.balance, t.riskPct, t.riskUSD, t.pnlUSD, t.rMul, t.rrRatio||'',
    t.outcome, t.setup||'', t.reason||'', t.lesson||'',
    t.emotion||'', t.followed||'', t.createdAt
  ]);

  const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
  ws['!cols'] = headers.map((_,i) => ({ wch: [12,16,10,10,10,10,10,10,12,8,10,10,10,8,10,16,40,40,20,10,20][i] }));

  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Trades');

  // Summary sheet
  const wins = trades.filter(t=>t.outcome==='WIN');
  const losses = trades.filter(t=>t.outcome==='LOSS');
  const totalPnL = trades.reduce((s,t)=>s+(t.pnlUSD||0),0);
  const sumData = [
    ['TRADING JOURNAL SUMMARY — SELF-LEARNING EDITION'],[''],
    ['Total Trades · จำนวนทั้งหมด', trades.length],
    ['Win Rate · อัตราชนะ', (wins.length/trades.length*100).toFixed(1)+'%'],
    ['Total P&L (USD) · กำไร/ขาดทุนรวม', totalPnL.toFixed(2)],
    ['Total R · R รวม', trades.reduce((s,t)=>s+(t.rMul||0),0).toFixed(2)],
    ['Wins · จำนวนชนะ', wins.length],
    ['Losses · จำนวนแพ้', losses.length],
    ['Export Date · วันที่ Export', new Date().toISOString()],
    [''],
    ['© 2026 Chollatis Bitcoiner — Don\'t Trust, Verify.']
  ];
  const wsSummary = XLSX.utils.aoa_to_sheet(sumData);
  XLSX.utils.book_append_sheet(wb, wsSummary, 'Summary');

  XLSX.writeFile(wb, `TradingJournal_${new Date().toISOString().slice(0,10)}.xlsx`);
  showToast('✓ Export สำเร็จ — บันทึกเป็น Excel แล้ว', 'success');
}

function importExcel(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function(e) {
    try {
      const wb    = XLSX.read(e.target.result, {type:'binary'});
      const ws    = wb.Sheets[wb.SheetNames[0]];
      const data  = XLSX.utils.sheet_to_json(ws, {header:1});

      if (data.length < 2) { showToast('ไฟล์ดูเหมือนว่างเปล่า', 'error'); return; }

      const headers = data[0].map(h=>String(h).trim());
      const trades  = loadTrades();
      let count = 0;

      data.slice(1).forEach(row => {
        if (!row[0]) return;
        const g = (name) => {
          const idx = headers.indexOf(name);
          return idx >= 0 ? row[idx] : undefined;
        };
        const t = {
          id: Date.now() + Math.random(),
          date:       g('Date')||'',
          instrument: g('Instrument')||'',
          direction:  g('Direction')||'LONG',
          timeframe:  g('Timeframe')||'Daily',
          entry:      parseFloat(g('Entry'))||0,
          sl:         parseFloat(g('SL'))||0,
          tp:         parseFloat(g('TP'))||null,
          exit:       parseFloat(g('Exit'))||0,
          balance:    parseFloat(g('Balance'))||0,
          riskPct:    parseFloat(g('Risk%'))||1,
          riskUSD:    parseFloat(g('RiskUSD'))||0,
          pnlUSD:     parseFloat(g('PnL_USD'))||0,
          rMul:       parseFloat(g('R_Multiple'))||0,
          rrRatio:    parseFloat(g('RR_Ratio'))||null,
          outcome:    g('Outcome')||'WIN',
          setup:      g('Setup')||'',
          reason:     g('Reason')||'',
          lesson:     g('Lesson')||'',
          emotion:    g('Emotion')||'',
          followed:   g('Followed_System')||'YES',
          createdAt:  g('Created')||new Date().toISOString()
        };
        trades.push(t);
        count++;
      });

      saveTrades(trades);
      showToast(`✓ Import สำเร็จ — ${count} trades · กำลังโหลดใหม่...`, 'success');
      // Reload page after short delay so all panels re-render cleanly
      setTimeout(() => location.reload(), 1200);
    } catch(err) {
      showToast('Import ล้มเหลว: ' + err.message, 'error');
    }
  };
  reader.readAsBinaryString(file);
  event.target.value = '';
}

// ═══════════════════════════════════════════
// UTILITIES
// ═══════════════════════════════════════════

// แสดงวันที่รูปแบบ CE (AD) ใต้ช่อง date เพื่อแก้ปัญหา iOS แสดง BE
function updateDateDisplay(val) {
  const el = document.getElementById('sub_date_display');
  if (!el || !val) return;
  try {
    const [y, m, d] = val.split('-').map(Number);
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    el.textContent = `📅 ${d} ${months[m-1]} ${y} (CE)`;
  } catch(e) { el.textContent = ''; }
}

function fmt$(n)  { return (n>=0?'+$':'-$') + Math.abs(n||0).toFixed(2); }
function fmt(n)   { return n ? parseFloat(n).toLocaleString() : '—'; }

// P&L with THB conversion (e.g. "+$122.00 / +฿4,331")
function fmtPnl(usd) {
  const usdStr = fmt$(usd);
  if (!_gbUsdThb || !usd) return usdStr;
  const thb = usd * _gbUsdThb;
  const sign = thb >= 0 ? '+' : '';
  const thbStr = sign + '฿' + Math.abs(thb).toLocaleString('th-TH', {maximumFractionDigits: 0});
  return `${usdStr} <span style="font-size:0.78em;opacity:0.75;">${thbStr}</span>`;
}

function emptyState(msg) {
  const msgs = {
    'No trades yet — log your first trade!': 'ยังไม่มีข้อมูล — ไปบันทึก Trade แรกได้เลย!',
    'No trades match your filters': 'ไม่พบ Trade ที่ตรงกับ Filter ที่เลือก',
    'No data': 'ยังไม่มีข้อมูล',
    'No setup data': 'ยังไม่มีข้อมูล Setup',
    'No emotion data': 'ยังไม่มีข้อมูล Emotion',
    'NO DATA': 'ยังไม่มีข้อมูล',
  };
  const thaiMsg = msgs[msg] || msg;
  return `<div class="empty-state"><div class="icon">📭</div><p>${msg.toUpperCase()}</p><p style="font-size:13px;margin-top:8px;font-family:'Sarabun',sans-serif;">${thaiMsg}</p></div>`;
}

function showToast(msg, type='info') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast ' + type;
  setTimeout(()=>t.classList.add('show'),10);
  setTimeout(()=>t.classList.remove('show'),3000);
}

function confirmClearAll() {
  if (confirm('⚠ การดำเนินการนี้จะลบข้อมูล Trade ทั้งหมดออกจาก Storage\n\nคุณแน่ใจหรือไม่?')) {
    if (confirm('ยืนยันอีกครั้ง: ลบข้อมูลทั้งหมดถาวร?')) {
      localStorage.removeItem(STORAGE_KEY);
      showToast('ลบข้อมูลทั้งหมดแล้ว · กำลังโหลดใหม่...', 'info');
      setTimeout(() => location.reload(), 800);
    }
  }
}

// ═══════════════════════════════════════════
// TOOLTIP BALLOON SYSTEM
// ═══════════════════════════════════════════
const TIPS = {
  // ─── FORM FIELDS ─────────────────────────────
  date: {
    icon: '📅',
    title: 'DATE · วันที่เทรด',
    body: 'บันทึกวันที่ที่คุณเปิด Position จริง ไม่ใช่วันที่วิเคราะห์หรือวันที่วางแผน การระบุวันที่ที่แม่นยำช่วยให้ระบบสามารถคำนวณ Win Rate รายเดือน/ปีได้ถูกต้อง',
    tip: '⚡ ใช้วันที่เปิด Position จริง — ระบบใช้ Timezone ประเทศไทย (UTC+7)'
  },
  instrument: {
    icon: '₿',
    title: 'INSTRUMENT · สินทรัพย์',
    body: 'เลือกสินทรัพย์ที่คุณเทรด ระบบจะดึงราคาปัจจุบัน (Real-time) จาก Binance API สำหรับ Crypto และ open.er-api.com สำหรับ Forex/Gold โดยอัตโนมัติ',
    tip: '⚡ Crypto: ดึงราคาจาก Binance · Forex/Gold: ดึงจาก open.er-api.com · อัพเดทเมื่อเลือก'
  },
  direction: {
    icon: '🎯',
    title: 'DIRECTION · ทิศทาง',
    body: 'LONG (ซื้อ) = คาดว่าราคาจะขึ้น คุณซื้อก่อนแล้วขายทีหลัง\nSHORT (ขาย) = คาดว่าราคาจะลง คุณขาย (Sell) ก่อนแล้วซื้อคืนทีหลัง\nการระบุทิศทางที่ถูกต้องส่งผลต่อการคำนวณ P&L ทั้งหมด',
    tip: '⚡ ผิดทิศทาง → P&L คำนวณผิดทั้ง Trade'
  },
  timeframe: {
    icon: '⏱',
    title: 'TIMEFRAME · กรอบเวลากราฟ',
    body: 'Timeframe ที่ใช้ตัดสินใจเข้า Trade หลัก:\n• 15m–1H: Day Trading (เปิด-ปิดภายในวัน)\n• 4H: Swing สั้น (2–5 วัน)\n• 1D: Swing กลาง (1–4 สัปดาห์) — แนะนำสำหรับผู้เริ่มต้น\n• 1W–1M: Position Trading (นับเดือน)',
    tip: '⚡ ระยะเวลา TF ใหญ่ขึ้น → Signal แม่นยำขึ้น แต่รอนานขึ้น'
  },
  entry: {
    icon: '🚀',
    title: 'ENTRY PRICE · ราคาที่เข้า',
    body: 'ราคาที่คุณซื้อ/ขายสินทรัพย์จริง ระบบจะดึงราคา Real-time มาใส่ให้อัตโนมัติเมื่อเลือก Instrument แต่คุณสามารถแก้ไขได้ตามราคาที่ได้รับจริง (เผื่อ Slippage)',
    tip: '⚡ ใส่ราคาที่ได้จริง ไม่ใช่ราคาที่ตั้งใจ — Slippage ส่งผลต่อ P&L'
  },
  sl: {
    icon: '🛡',
    title: 'STOP LOSS · จุดตัดขาดทุน',
    body: 'SL คือราคาที่คุณจะยอมปิด Position และรับผลขาดทุน เพื่อป้องกันพอร์ตจากความเสียหายหนัก\n\nกฎทอง: ห้ามเทรดโดยไม่มี SL ทุกกรณี ระยะห่างระหว่าง Entry–SL คือตัวกำหนด Position Size และ Risk ต่อ Trade',
    tip: '⚡ Risk 1% ต่อ Trade = ไม่เกิน 20 Trade ขาดทุนติดต่อกันก็ยัง Survive'
  },
  tp: {
    icon: '🏆',
    title: 'TAKE PROFIT · เป้าหมายกำไร',
    body: 'TP คือราคาเป้าหมายที่คุณตั้งใจปิดทำกำไร การมี TP ช่วยให้ระบบคำนวณ R:R (Risk:Reward Ratio) ได้ เพื่อประเมินว่า Trade นี้คุ้มค่าหรือไม่\n\nR:R ที่ดี ควรมากกว่า 1:2 ขึ้นไป',
    tip: '⚡ R:R = (Entry–TP) ÷ (Entry–SL) — เทรดที่ดีควรได้ R:R ≥ 1:2'
  },
  exit: {
    icon: '🏁',
    title: 'EXIT PRICE · ราคาที่ออก',
    body: 'ราคาจริงที่คุณปิด Position ไม่ว่าจะถูก SL, ถึง TP, หรือปิดก่อนกำหนด ความแตกต่างระหว่าง Exit กับ Entry คือพื้นฐานของการคำนวณ P&L ทั้งหมด',
    tip: '⚡ Exit ≠ TP เสมอไป — บางครั้ง Manual close ก่อน TP'
  },
  balance: {
    icon: '💰',
    title: 'ACCOUNT BALANCE · ยอดพอร์ต',
    body: 'ยอดเงินในพอร์ต ณ ขณะเปิด Trade (ก่อนเทรดนี้) ใช้สำหรับคำนวณ Risk เป็น $ จาก % ที่กำหนด ระบบจะดึงค่าจาก Balance Bar ด้านบนอัตโนมัติ\n\nตัวอย่าง: พอร์ต $10,000 × Risk 1% = เสี่ยงได้ $100 ต่อ Trade',
    tip: '⚡ ใช้ยอดก่อนเปิด Trade — ไม่ใช่หลังเทรด'
  },
  risk_pct: {
    icon: '⚖',
    title: 'RISK PER TRADE · ความเสี่ยงต่อครั้ง',
    body: 'เปอร์เซ็นต์ของพอร์ตที่ยินดีเสียในแต่ละ Trade หากโดน SL\n\nแนวทาง Professional:\n• 0.5–1%: Conservative (ผู้เริ่มต้น)\n• 1–2%: Moderate (ปกติ)\n• 2–5%: Aggressive (มีประสบการณ์สูง)\n\nไม่ควรเกิน 2% ต่อ Trade เด็ดขาด',
    tip: '⚡ Kelly Criterion: Risk ที่เหมาะสม = Win Rate − (Loss Rate ÷ R:R)'
  },
  fee_pct: {
    icon: '💸',
    title: 'FEE · ค่าธรรมเนียมการเทรด',
    body: 'ค่าธรรมเนียมที่ Exchange เรียกเก็บต่อการซื้อหรือขาย 1 ครั้ง (per side)\n\nตัวอย่างอัตราค่าธรรมเนียมทั่วไป:\n• Binance Spot Maker: 0.1% · Taker: 0.1%\n• Binance Futures Maker: 0.02% · Taker: 0.05%\n• Bybit Maker: 0.01% · Taker: 0.06%\n\nทุก Position มีค่าธรรมเนียม 2 ครั้ง: ตอนเปิด (Buy) + ตอนปิด (Sell)',
    tip: '⚡ Fee 0.1% × 2 sides = 0.2% Round-trip — ตั้ง R:R ให้ครอบคลุมค่าธรรมเนียมด้วย'
  },
  position_size: {
    icon: '📐',
    title: 'POSITION SIZE · ขนาด Position',
    body: 'จำนวน Units/Lots ที่ควรซื้อ คำนวณจาก:\n\nPosition Size = (Balance × Risk%) ÷ (Entry − SL)\n\nตัวอย่าง: Balance $10,000, Risk 1% ($100), Entry $100, SL $95 → Size = $100 ÷ $5 = 20 units',
    tip: '⚡ อย่าเดา Position Size — ใช้สูตรนี้ทุกครั้งเพื่อควบคุม Risk'
  },
  rr: {
    icon: '⚖',
    title: 'RISK:REWARD · อัตราส่วนความเสี่ยง:กำไร',
    body: 'R:R แสดงว่าทุก $1 ที่เสี่ยง คุณมีโอกาสได้กลับมาเท่าไร\n\nR:R 1:2 = เสี่ยง $100 โอกาสได้ $200\n\nหลักการ: ถ้า Win Rate 40% แต่ R:R = 1:3 ยังมีกำไรได้ในระยะยาว',
    tip: '⚡ ต้องการ Win Rate น้อยลง ถ้า R:R สูงขึ้น — Breakeven: 1 ÷ (1+RR)'
  },
  pnl: {
    icon: '💵',
    title: 'P&L · กำไร/ขาดทุน',
    body: 'กำไรหรือขาดทุนเป็นดอลลาร์จาก Trade นี้ คำนวณจาก:\n\nP&L = R-Multiple × Risk USD\n\nตัวอย่าง: +2R × $100 Risk = +$200 กำไร\n−1R × $100 Risk = −$100 ขาดทุน',
    tip: '⚡ ติดตาม P&L รายเดือนเพื่อประเมินว่าระบบทำงานได้จริงหรือไม่'
  },
  rmul: {
    icon: '✖',
    title: 'R-MULTIPLE · กำไรเป็นกี่เท่าของความเสี่ยง',
    body: 'R-Multiple คือ metric ที่ดีที่สุดในการประเมิน Trade โดยไม่ขึ้นกับขนาดพอร์ต\n\n+2R = กำไร 2 เท่าของที่เสี่ยง\n+1R = กำไรเท่ากับที่เสี่ยง\n−1R = เสียทั้ง SL\n\nระบบที่ดี: Average R > 0 ในระยะยาว',
    tip: '⚡ นักเทรดมืออาชีพติดตาม Average R ไม่ใช่แค่ Win Rate'
  },
  outcome: {
    icon: '🎯',
    title: 'OUTCOME · ผลการเทรด',
    body: 'WIN: ปิดกำไร\nLOSS: โดน SL หรือปิดขาดทุน\nB/E: Break Even — ปิดที่ Entry หรือ Commission เท่านั้น\n\nระบุให้ถูกต้อง — ข้อมูลนี้ใช้คำนวณ Win Rate ใน Summary',
    tip: '⚡ B/E ถือเป็น Half-Win ในเชิงจิตวิทยา — ปกป้องทุนได้สำเร็จ'
  },
  setup: {
    icon: '🔧',
    title: 'SETUP TAG · รูปแบบการเทรด',
    body: 'ระบุรูปแบบ Pattern ที่ใช้เข้า Trade ข้อมูลนี้ช่วยให้คุณวิเคราะห์ได้ว่า Setup ไหนให้ Win Rate สูงสุดในพอร์ตของคุณ',
    tip: '⚡ ดูสถิติ Setup ใน Summary Tab เพื่อหา Edge ที่แท้จริงของคุณ'
  },
  reason: {
    icon: '📝',
    title: 'ENTRY REASON · เหตุผลในการเข้า Trade',
    body: 'อธิบายเหตุผลที่เข้า Trade อย่างละเอียด รวมถึง Signal ที่เห็น เงื่อนไขที่ครบ และ Context ตลาด การบันทึกส่วนนี้ช่วยให้คุณ Review กลับมาได้ว่าตัดสินใจด้วยเหตุผลอะไร',
    tip: '⚡ ถ้าอธิบายไม่ได้ชัดเจน = ยังไม่ควรเข้า Trade'
  },
  lesson: {
    icon: '🎓',
    title: 'LESSON LEARNED · บทเรียน',
    body: 'สิ่งที่เรียนรู้จาก Trade นี้ ไม่ว่าจะ WIN หรือ LOSS\n\nตัวอย่างที่ดี:\n• "ควรรอ Candle ปิดก่อน ไม่ Anticipate"\n• "Volume ต่ำมาก ควร Skip"\n• "FOMO ทำให้เข้าราคาแย่"',
    tip: '⚡ Trade ที่ไม่มี Lesson = เสียเวลาและเงินฟรี — บันทึกทุกครั้ง'
  },
  emotion: {
    icon: '🧠',
    title: 'EMOTION STATE · สภาวะจิตใจ',
    body: 'บันทึกสภาวะจิตใจขณะเทรด เพื่อวิเคราะห์ว่าอารมณ์ใดส่งผลเสียต่อผลลัพธ์\n\nงานวิจัยพบว่า:\n• FOMO → เข้าราคาแย่ Win Rate ต่ำ\n• Revenge Trade → Risk สูงเกินปกติ\n• Calm/Focused → Win Rate สูงสุด',
    tip: '⚡ ดู Emotion Analysis ใน Summary — อย่าเทรดเมื่อ Emotional'
  },
  followed: {
    icon: '📋',
    title: 'FOLLOWED SYSTEM · ทำตามระบบไหม',
    body: 'บันทึกว่า Trade นี้ทำตาม Trading Plan ของคุณหรือไม่\n\nYES: ทำตามกฎ 100%\nPARTIAL: ทำตามบางส่วน\nNO: เบี่ยงเบนจากระบบ\n\nสถิติที่ควรรู้: Trade ที่ "NO" มักมี Win Rate ต่ำกว่า Trade ที่ "YES" อย่างมีนัย',
    tip: '⚡ System Discipline ≥ 80% = เงื่อนไขขั้นต่ำของนักเทรดมืออาชีพ'
  },

  // ─── DASHBOARD KPI CARDS ─────────────────────
  kpi_pnl: {
    icon: '💵',
    title: 'TOTAL P&L · กำไร/ขาดทุนรวม',
    body: 'ยอดรวม P&L ทุก Trade ในพอร์ต คิดเป็น USD จาก Risk-Based Sizing\n\nตัวเลขนี้บอกว่า "ระบบของคุณให้ผลกำไรสะสมเท่าไรจนถึงปัจจุบัน"\n\nถ้าตัวเลขนี้เป็นลบหลังเทรดหลาย Trade — ควร Review กลยุทธ์ทั้งหมด',
    tip: '⚡ P&L ที่ดีต้องเติบโตสม่ำเสมอ ไม่ใช่แค่บาง Trade กำไรมาก'
  },
  kpi_winrate: {
    icon: '🎯',
    title: 'WIN RATE · อัตราชนะ',
    body: 'เปอร์เซ็นต์ Trade ที่ได้กำไร\n\nความเข้าใจผิดที่พบบ่อย: Win Rate 50% ไม่ได้หมายความว่ากำไร ขึ้นอยู่กับ R:R ด้วย\n\nตัวอย่าง:\n• Win Rate 40% + R:R 1:3 = กำไรได้ในระยะยาว\n• Win Rate 70% + R:R 1:0.5 = ขาดทุนระยะยาว',
    tip: '⚡ Breakeven Win Rate = 1 ÷ (1 + Average R:R) — คำนวณของคุณเองก่อนตั้งเป้า'
  },
  kpi_pf: {
    icon: '📊',
    title: 'PROFIT FACTOR · ประสิทธิภาพระบบ',
    body: 'Profit Factor = ผลรวมกำไรทั้งหมด ÷ ผลรวมขาดทุนทั้งหมด\n\nเกณฑ์มาตรฐาน:\n• < 1.0: ระบบขาดทุนระยะยาว\n• 1.0–1.5: ขอบบาง ต้องระวัง\n• 1.5–2.0: ดี\n• > 2.0: ดีมาก / ยอดเยี่ยม\n\nHedge Fund ระดับโลกมักอยู่ที่ 1.5–2.5',
    tip: '⚡ ถ้า PF ต่ำกว่า 1.2 ควรหยุด Review ระบบทันที'
  },
  kpi_r: {
    icon: '✖',
    title: 'TOTAL R · R รวมทั้งพอร์ต',
    body: 'ผลรวม R-Multiple ของทุก Trade\n\nตัวเลขนี้บอกว่า "พอร์ตของคุณทำกำไรได้กี่เท่าของ Risk ที่ใช้ไปทั้งหมด"\n\nตัวอย่าง: Total R = +15R หมายถึงคุณได้กำไร 15 เท่าของ Risk ทั้งหมดที่ลงไป\n\nดีกว่า P&L เพราะไม่ขึ้นกับขนาดพอร์ต',
    tip: '⚡ Average R ต่อ Trade = Total R ÷ จำนวน Trade — ควรเป็น +0.3R ขึ้นไป'
  },
  kpi_avgwin: {
    icon: '📈',
    title: 'AVG WIN · กำไรเฉลี่ยต่อ Trade',
    body: 'กำไรเฉลี่ยของ Trade ที่ชนะ ควรเปรียบเทียบกับ AVG LOSS เสมอ\n\nอัตราส่วนที่ดี: AVG WIN ÷ AVG LOSS ควรมากกว่า 1.5 เท่าขึ้นไป\n\nนั่นหมายความว่าแม้ชนะ 40% ก็ยังมีกำไรในระยะยาว',
    tip: '⚡ Payoff Ratio = AVG WIN ÷ AVG LOSS — ยิ่งสูงยิ่งดี'
  },
  kpi_avgloss: {
    icon: '📉',
    title: 'AVG LOSS · ขาดทุนเฉลี่ยต่อ Trade',
    body: 'ขาดทุนเฉลี่ยของ Trade ที่แพ้ ถ้าตัวเลขนี้ใกล้เคียงกับ Risk% ที่ตั้งไว้ — แสดงว่าคุณ Stop Loss อย่างมีวินัย\n\nถ้า AVG LOSS สูงกว่า Risk ที่ตั้งไว้มาก — อาจมีปัญหาการย้าย SL หรือไม่มี SL',
    tip: '⚡ AVG LOSS ควรใกล้เคียง Risk ที่ตั้งไว้ (1-2%) ไม่ควรเกิน 2x'
  },

  // ─── SUMMARY CARDS ────────────────────────────
  sum_trades: {
    icon: '📋',
    title: 'TOTAL TRADES · จำนวน Trade ทั้งหมด',
    body: 'จำนวน Sample Size ของข้อมูล สำคัญมากในการประเมินว่าสถิติ Win Rate และ Profit Factor มีความน่าเชื่อถือแค่ไหน\n\nน้อยกว่า 30 Trades: สถิติอาจไม่น่าเชื่อถือ\n30–100 Trades: เริ่มน่าเชื่อถือ\nมากกว่า 100 Trades: ข้อมูลเชิงสถิติที่แข็งแกร่ง',
    tip: '⚡ ต้องการอย่างน้อย 50–100 Trade เพื่อ Validate ระบบ'
  },
  sum_avgr: {
    icon: '✖',
    title: 'AVG R/TRADE · R เฉลี่ยต่อ Trade',
    body: 'Expectancy ของระบบ — บอกว่าทุกๆ 1 Trade คุณคาดว่าจะได้ R เฉลี่ยเท่าไร\n\n+0.5R ต่อ Trade = ทุก 100 Trade คาดว่ากำไร 50R\n\nนี่คือ metric ที่สำคัญที่สุดในการประเมินคุณภาพของระบบเทรด',
    tip: '⚡ Expectancy = (Win Rate × Avg Win R) − (Loss Rate × Avg Loss R)'
  },
  sum_maxwin: {
    icon: '🏆',
    title: 'MAX WIN · Trade ที่กำไรสูงสุด',
    body: 'Trade ที่กำไรสูงที่สุดในพอร์ต ระวัง: ถ้า Max Win สูงมากผิดปกติ อาจเป็นสัญญาณของ Outlier ที่ไม่ได้เกิดจากระบบ แต่เกิดจากโชค\n\nลองดูว่า ถ้าเอา Max Win นี้ออก ตัวเลขรวมยังเป็นบวกหรือไม่',
    tip: '⚡ ระบบดีไม่ควรพึ่งพา Outlier เดียว — ผลต้องสม่ำเสมอ'
  },
  sum_maxloss: {
    icon: '💔',
    title: 'MAX LOSS · Trade ที่ขาดทุนสูงสุด',
    body: 'Trade ที่ขาดทุนมากที่สุด ถ้าตัวเลขนี้สูงกว่า Risk ที่ตั้งไว้มาก อาจเป็นสัญญาณว่า:\n• มีการย้าย SL\n• ไม่มี SL ในบาง Trade\n• มี Slippage สูงผิดปกติ',
    tip: '⚡ Max Loss ควรไม่เกิน 2–3x ของ Avg Loss ปกติ'
  },
  sum_drawdown: {
    icon: '📉',
    title: 'MAX DRAWDOWN · ขาดทุนสะสมสูงสุด',
    body: 'ยอดขาดทุนสะสมสูงสุดจากจุดสูงสุดก่อนหน้า\n\nตัวอย่าง: พอร์ตเติบโตถึง $12,000 แล้วลงมาเหลือ $10,500 → Drawdown = $1,500\n\nเกณฑ์ที่ยอมรับได้:\n• < 10%: ดีเยี่ยม\n• 10–20%: ปกติสำหรับ Active Trading\n• > 30%: ต้องระวัง Review ระบบ',
    tip: '⚡ Drawdown ต่ำ = จิตใจนิ่ง เทรดได้สม่ำเสมอ — สำคัญกว่า Return'
  },
  sum_discipline: {
    icon: '🎖',
    title: 'SYSTEM DISCIPLINE · วินัยระบบ',
    body: 'เปอร์เซ็นต์ Trade ที่ทำตามกฎระบบ 100% (Followed = YES)\n\nงานวิจัย: Trader ที่มี Discipline ≥ 80% มีผลลัพธ์ดีกว่ากลุ่ม < 80% อย่างมีนัยสำคัญ\n\nแม้ระบบดี แต่ถ้า Discipline ต่ำ — ผลลัพธ์จะแย่',
    tip: '⚡ วินัย ≥ 80% คือเงื่อนไขขั้นต่ำ — ต่ำกว่านี้ต้อง Pause เทรดแล้ว Review'
  },

  // ─── DASHBOARD SECTION CARDS ──────────────────
  dash_equity: {
    icon: '📈',
    title: 'EQUITY CURVE · กราฟสะสม P&L',
    body: 'กราฟแสดงการเติบโตของพอร์ตตามเวลา เส้นกราฟที่ดีควรมีลักษณะ:\n• เส้นโน้มขึ้นสม่ำเสมอ (ไม่ขึ้น-ลงรุนแรง)\n• Drawdown ต่ำ (ไม่หล่นลึกมาก)\n• Recovery หลัง Drawdown รวดเร็ว\n\nกราฟที่ขึ้นลงเหวี่ยงมาก = Risk Management มีปัญหา',
    tip: '⚡ Equity Curve เรียบและขึ้นสม่ำเสมอ = ระบบมีคุณภาพสูง'
  },
  dash_recent: {
    icon: '⏰',
    title: 'RECENT TRADES · Trade ล่าสุด',
    body: 'แสดง 10 Trade ล่าสุดในพอร์ต ใช้สำหรับ Quick Review ว่า Trade ล่าสุดมีรูปแบบอย่างไร\n\nสิ่งที่ควร Review:\n• ผล WIN/LOSS ต่อเนื่องกันหรือไม่\n• R-Multiple เป็นบวกหรือลบ\n• Setup ที่ใช้ล่าสุดคืออะไร',
    tip: '⚡ ถ้า LOSS ติดกัน 3+ ครั้ง ควร Pause และ Review ก่อนเทรดต่อ'
  },

  // ─── SUMMARY SECTION CARDS ────────────────────
  sum_by_instrument: {
    icon: '💹',
    title: 'PERFORMANCE BY INSTRUMENT · ผลแยกสินทรัพย์',
    body: 'แสดง Win Rate, P&L และ R รวมแยกตามแต่ละสินทรัพย์ที่เทรด\n\nวิธีใช้ข้อมูลนี้:\n• สินทรัพย์ไหน Win Rate สูง → เพิ่ม Frequency\n• สินทรัพย์ไหน P&L ติดลบสม่ำเสมอ → พิจารณา Drop ออก\n• Focus ที่ 2–3 Instrument ที่ถนัดที่สุด',
    tip: '⚡ Specialization ใน 1–3 Instrument ดีกว่า Trade ทุกอย่าง'
  },
  sum_by_setup: {
    icon: '🔧',
    title: 'PERFORMANCE BY SETUP · ผลแยก Setup',
    body: 'แสดงผลลัพธ์แยกตาม Pattern/Setup ที่ใช้เข้า Trade\n\nนี่คือข้อมูลที่มีค่ามากที่สุดในการพัฒนาระบบ:\n• Setup ไหน Win Rate สูง → ใช้บ่อยขึ้น\n• Setup ไหน P&L ติดลบ → หยุดใช้หรือ Refine\n• Focus เฉพาะ High-Probability Setup',
    tip: '⚡ Remove Setup ที่ขาดทุนสม่ำเสมอ — ความเรียบง่ายชนะเสมอ'
  },
  sum_by_emotion: {
    icon: '🧠',
    title: 'EMOTION ANALYSIS · วิเคราะห์ตามจิตใจ',
    body: 'แสดงความสัมพันธ์ระหว่างสภาวะจิตใจและผลลัพธ์การเทรด\n\nรูปแบบที่พบบ่อย:\n• Calm/Focused → Win Rate สูงสุด\n• FOMO → Win Rate ต่ำ ขาดทุนมาก\n• Revenge Trade → P&L แย่ที่สุด\n\nใช้ข้อมูลนี้เพื่อรู้ว่าตัวเองควรเทรดเมื่ออารมณ์ไหน',
    tip: '⚡ ถ้า Revenge Trade มี P&L ติดลบ — กำหนดกฎ "หยุดทันทีหลัง 2 LOSS ติด"'
  }
};

let _tipTimer = null;
let _tipVisible = false;
let _tipAnchor  = null;

function initTooltips() {
  const balloon = document.getElementById('tjBalloon');
  const bTitle  = document.getElementById('tjBalloonTitle');
  const bBody   = document.getElementById('tjBalloonBody');
  const bTip    = document.getElementById('tjBalloonTip');

  function showBalloon(key, targetEl) {
    const data = TIPS[key];
    if (!data) return;
    bTitle.innerHTML  = `${data.icon} ${data.title}`;
    bBody.textContent = data.body;
    bTip.textContent  = data.tip;
    _tipAnchor = targetEl;
    balloon.classList.add('show');
    _tipVisible = true;
    positionBalloon(targetEl);
  }

  function hideBalloon() {
    balloon.classList.remove('show');
    _tipVisible = false;
    clearTimeout(_tipTimer);
    _tipTimer   = null;
    _tipAnchor  = null;
  }

  function positionBalloon(el) {
    const rect = el.getBoundingClientRect();
    const bW   = Math.min(320, window.innerWidth - 24);
    let left   = rect.left;
    let top    = rect.bottom + 10;
    if (left + bW > window.innerWidth - 12) left = window.innerWidth - bW - 12;
    if (left < 8) left = 8;
    // Flip up if no room below (estimate 220px height)
    if (top + 220 > window.innerHeight - 12) top = rect.top - 230;
    if (top < 8) top = 8;
    balloon.style.left  = left + 'px';
    balloon.style.top   = top  + 'px';
    balloon.style.width = bW   + 'px';
  }

  // ── Event delegation on document — works for dynamically rendered cards ──
  document.addEventListener('mouseover', (e) => {
    const host = e.target.closest('[data-tip]');
    if (!host) return;
    if (_tipTimer) return; // already counting
    _tipTimer = setTimeout(() => showBalloon(host.dataset.tip, host), 1500);
  });

  document.addEventListener('mouseout', (e) => {
    const host = e.target.closest('[data-tip]');
    if (!host) return;
    // Only hide if mouse left the host entirely
    if (host.contains(e.relatedTarget)) return;
    clearTimeout(_tipTimer);
    _tipTimer = null;
    if (_tipVisible) hideBalloon();
  });

  // Reposition balloon as mouse moves within the host
  document.addEventListener('mousemove', (e) => {
    if (!_tipVisible || !_tipAnchor) return;
    positionBalloon(_tipAnchor);
  });

  // Hide on scroll or click anywhere
  document.addEventListener('scroll', hideBalloon, { passive: true });
  document.addEventListener('click',  hideBalloon);
}

// ═══════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════
(function init() {
  // วัด layout จริงทันที (ก่อน render อื่นๆ)
  updateLayout();

  // Set today's date using Thailand timezone (UTC+7)
  const now = new Date();
  const thDate = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Bangkok' }));
  const yyyy = thDate.getFullYear();
  const mm   = String(thDate.getMonth() + 1).padStart(2, '0');
  const dd   = String(thDate.getDate()).padStart(2, '0');
  const todayVal = `${yyyy}-${mm}-${dd}`;
  document.getElementById('f_date').value = todayVal;
  updateDateDisplay(todayVal);

  // Listen for date changes to update display
  document.getElementById('f_date').addEventListener('change', function() {
    updateDateDisplay(this.value);
  });

  // Init global balance bar (fetch FX + load saved)
  initGlobalBalance();

  // Initial render
  renderDashboard();

  // Init tooltip system
  initTooltips();

  // Resize: update layout + redraw equity curve
  window.addEventListener('resize', () => {
    updateLayout();
    if (document.getElementById('panel-dashboard').classList.contains('active')) {
      drawEquityCurve(loadTrades());
    }
  });
})();
</script>

</body>
</html>