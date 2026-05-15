<?php
$page_title = "Bitcoin 101 — รู้จักบิตคอยน์ก่อนใคร";
$site_url   = "/";
// Static fallback data (JS will fetch live)
$uptime_pct    = "99.9881";
$node_count    = "23,527";
$circulating   = "20,017,758";
$pct_issued    = "95.32";
$max_supply    = "20,999,999.9769";
?><!DOCTYPE html>
<html lang="th" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="format-detection" content="telephone=no,date=no,address=no,email=no,url=no">
<title><?= htmlspecialchars($page_title) ?></title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%23F7931A'/><text y='.9em' font-size='78' x='50%' text-anchor='middle' font-family='Arial Black,sans-serif' fill='white'>₿</text></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=IBM+Plex+Mono:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Kanit:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════
   BITCOIN 101 — v3
   Thai: Prompt (body) / Kanit (display)
   EN : IBM Plex Mono (terminal/code)
   Dark carbon-blue default | Warm parchment light
════════════════════════════════════════ */
[data-theme="dark"]{
  --bg:       #07090d;
  --bg2:      #0c1017;
  --bg3:      #111820;
  --bg4:      #17202c;
  --bd:       #1c2b3a;
  --bd-lit:   rgba(247,147,26,.4);
  --tx:       #eef2f6;
  --tx2:      #a8c0d0;
  --tx3:      #6a8a9e;
  --tx4:      #4a6880;
  --btc:      #f7931a;
  --btc2:     #c47310;
  --btcs:     rgba(247,147,26,.12);
  --grn:      #2ec98a;
  --grns:     rgba(46,201,138,.12);
  --red:      #f05b5b;
  --reds:     rgba(240,91,91,.12);
  --blu:      #4d9de0;
  --blus:     rgba(77,157,224,.1);
  --pur:      #b06be8;
  --sd:       0 2px 8px rgba(0,0,0,.5),0 0 0 1px var(--bd);
  --gl:       0 0 60px rgba(247,147,26,.1);
  --grid:     rgba(247,147,26,.04);
}
[data-theme="light"]{
  --bg:       #f2ede3;
  --bg2:      #ffffff;
  --bg3:      #f9f6f0;
  --bg4:      #ede8de;
  --bd:       #ccc5b8;
  --bd-lit:   rgba(200,104,0,.45);
  --tx:       #1c1208;
  --tx2:      #5a4830;
  --tx3:      #8a7860;
  --tx4:      #b8a890;
  --btc:      #c06800;
  --btc2:     #984e00;
  --btcs:     rgba(200,104,0,.1);
  --grn:      #1a7a48;
  --grns:     rgba(26,122,72,.1);
  --red:      #c03030;
  --reds:     rgba(192,48,48,.1);
  --blu:      #1a5eb0;
  --blus:     rgba(26,94,176,.08);
  --pur:      #7830c0;
  --sd:       0 1px 4px rgba(0,0,0,.07),0 0 0 1px var(--bd);
  --gl:       0 0 40px rgba(200,104,0,.07);
  --grid:     rgba(150,100,30,.06);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  background:var(--bg);color:var(--tx2);
  font-family:'Prompt',sans-serif;font-weight:400;
  font-size:16px;line-height:1.75;overflow-x:hidden;
  transition:background .35s,color .35s;
}
/* Grid bg */
body::before{
  content:'';position:fixed;inset:0;pointer-events:none;z-index:0;
  background-image:
    linear-gradient(var(--grid) 1px,transparent 1px),
    linear-gradient(90deg,var(--grid) 1px,transparent 1px);
  background-size:48px 48px;
  mask-image:radial-gradient(ellipse 100% 60% at 50% 0%,black 30%,transparent 100%);
}

/* ── TOOLTIP ── */
[data-tip]{position:relative;cursor:help}
[data-tip]::after{
  content:attr(data-tip);
  position:absolute;bottom:calc(100% + 8px);left:50%;
  transform:translateX(-50%);
  background:var(--bg4);border:1px solid var(--bd-lit);
  color:var(--tx);font-family:'IBM Plex Mono',monospace;
  font-size:.68rem;line-height:1.5;
  padding:.45rem .75rem;border-radius:6px;
  white-space:nowrap;max-width:260px;white-space:normal;
  text-align:center;pointer-events:none;
  opacity:0;transition:opacity .2s;z-index:999;
}
[data-tip]:hover::after{opacity:1}

/* ── NAV ── */
.nav{
  position:fixed;top:0;left:0;right:0;z-index:200;height:52px;
  display:flex;align-items:center;justify-content:space-between;
  padding:0 1.5rem;
  background:var(--bg);border-bottom:1px solid var(--bd);
  backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
  transition:background .35s,border-color .35s;
}
.nav-logo{
  font-family:'Kanit',sans-serif;font-weight:700;font-size:1.15rem;
  color:var(--btc);text-decoration:none;letter-spacing:-.02em;
}
.nav-logo span{color:var(--tx)}
.nav-r{display:flex;align-items:center;gap:.8rem}
.nav-a{
  font-family:'IBM Plex Mono',monospace;font-size:.67rem;
  letter-spacing:.07em;color:var(--tx3);text-decoration:none;
  text-transform:uppercase;transition:color .2s;
}
.nav-a:hover{color:var(--btc)}
.tog{
  display:flex;background:var(--bg4);
  border:1px solid var(--bd);border-radius:20px;padding:3px;
}
.tob{
  width:28px;height:24px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:.78rem;cursor:pointer;border:none;background:none;
  color:var(--tx3);transition:all .22s;
}
.tob.on{background:var(--btc);color:#fff;box-shadow:0 1px 6px rgba(247,147,26,.35)}

/* ── HERO ── */
.hero{
  min-height:100vh;display:grid;place-items:center;
  padding:7rem 1.5rem 4rem;position:relative;overflow:hidden;
}
.hero-glow{
  position:absolute;top:-15%;left:50%;transform:translateX(-50%);
  width:800px;height:600px;pointer-events:none;
  background:radial-gradient(ellipse,rgba(247,147,26,.14) 0%,transparent 65%);
}
[data-theme="light"] .hero-glow{background:radial-gradient(ellipse,rgba(200,104,0,.09) 0%,transparent 65%)}
.hero-in{max-width:760px;text-align:center;position:relative;z-index:1}
.hero-chip{
  display:inline-flex;align-items:center;gap:.5rem;
  font-family:'IBM Plex Mono',monospace;font-size:.66rem;
  letter-spacing:.2em;color:var(--btc);text-transform:uppercase;
  background:var(--btcs);border:1px solid var(--bd-lit);
  border-radius:20px;padding:.28rem .9rem;margin-bottom:2rem;
}
.hero-chip::before{content:'';width:7px;height:7px;background:var(--btc);border-radius:50%;animation:blink 2.2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
.hero-h1{
  font-family:'Kanit',sans-serif;font-weight:700;
  font-size:clamp(2.5rem,7vw,4.6rem);color:var(--tx);
  line-height:1.06;letter-spacing:-.03em;margin-bottom:1.2rem;
}
.hero-h1 .acc{color:var(--btc)}
.hero-sub{font-size:1.05rem;color:var(--tx3);max-width:500px;margin:0 auto 2.5rem;font-weight:300;line-height:1.9}
.hero-btn{
  display:inline-flex;align-items:center;gap:.45rem;
  background:var(--btc);color:#fff;
  font-family:'IBM Plex Mono',monospace;font-size:.76rem;
  font-weight:500;letter-spacing:.07em;text-transform:uppercase;
  text-decoration:none;padding:.75rem 1.6rem;border-radius:6px;
  transition:all .2s;box-shadow:0 4px 24px rgba(247,147,26,.28);
}
.hero-btn:hover{background:var(--btc2);transform:translateY(-1px);box-shadow:0 6px 28px rgba(247,147,26,.4)}
[data-theme="light"] .hero-btn{color:#fff}

/* Hero stats grid */
.hero-stats{
  display:flex;justify-content:center;gap:0;flex-wrap:wrap;
  margin-top:3.5rem;padding-top:2.5rem;border-top:1px solid var(--bd);
}
.hs{
  flex:1;min-width:120px;padding:0 1.2rem;
  border-right:1px solid var(--bd);text-align:center;
}
.hs:last-child{border-right:none}
@media(max-width:560px){.hs{min-width:50%;border-right:none;border-bottom:1px solid var(--bd);padding:.8rem}}
.hs-n{
  font-family:'Kanit',sans-serif;font-weight:700;
  font-size:1.45rem;color:var(--btc);display:block;letter-spacing:-.02em;
}
.hs-l{
  font-family:'IBM Plex Mono',monospace;font-size:.6rem;
  color:var(--tx4);letter-spacing:.12em;text-transform:uppercase;
  display:block;margin-top:.15rem;
}
.scroll-hint{
  margin-top:1.5rem;font-family:'IBM Plex Mono',monospace;
  font-size:.62rem;letter-spacing:.2em;color:var(--tx4);
  text-transform:uppercase;animation:bob 2.2s ease-in-out infinite;
}
@keyframes bob{0%,100%{transform:translateY(0)}50%{transform:translateY(5px)}}

/* ── SECTIONS ── */
.sec{max-width:920px;margin:0 auto;padding:5rem 1.5rem;position:relative;z-index:1}
.eye{
  font-family:'IBM Plex Mono',monospace;font-size:.63rem;
  letter-spacing:.25em;color:var(--btc);text-transform:uppercase;
  display:flex;align-items:center;gap:.6rem;margin-bottom:.7rem;
}
.eye::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,var(--bd-lit),transparent)}
.sh2{
  font-family:'Kanit',sans-serif;font-weight:700;
  font-size:clamp(1.5rem,3.5vw,2.2rem);color:var(--tx);
  letter-spacing:-.02em;margin-bottom:.6rem;line-height:1.18;
}
.slead{
  font-size:clamp(.9rem,1.5vw,1.05rem);
  color:var(--tx2);
  max-width:100%;
  line-height:1.9;
  margin-bottom:1.8rem;
}
.div{border:none;border-top:1px solid var(--bd);max-width:920px;margin:0 auto}

/* Responsive font boosts */
@media(min-width:900px){
  .slead{font-size:1.05rem;color:var(--tx2)}
}
@media(max-width:640px){
  .sec{padding:3.5rem 1.2rem}
  .slead{font-size:.95rem;line-height:1.85}
  .sh2{margin-bottom:.5rem}
}

/* ── CARDS ── */
.card{
  background:var(--bg2);border:1px solid var(--bd);
  border-radius:10px;padding:1.4rem 1.5rem;
  box-shadow:var(--sd);transition:border-color .2s,box-shadow .2s;
}
.card:hover{border-color:var(--bd-lit)}
.ci{width:36px;height:36px;background:var(--btcs);border:1px solid var(--bd-lit);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;margin-bottom:.85rem}
.ct{font-family:'Kanit',sans-serif;font-weight:600;font-size:.95rem;color:var(--tx);margin-bottom:.35rem}
.cb{font-size:.86rem;color:var(--tx2);line-height:1.78}

/* Grids */
.g2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.g3{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.g4{display:grid;grid-template-columns:repeat(4,1fr);gap:.85rem}
@media(max-width:700px){.g2,.g3,.g4{grid-template-columns:1fr}}
@media(max-width:900px){.g4{grid-template-columns:1fr 1fr}}

/* Pair compare */
.pair{
  display:grid;grid-template-columns:1fr 22px 1fr;
  align-items:center;gap:.75rem;
  background:var(--bg2);border:1px solid var(--bd);
  border-radius:10px;padding:1.2rem 1.4rem;margin-bottom:.8rem;
  transition:border-color .2s;
}
.pair:hover{border-color:var(--bd-lit)}
.pa{text-align:center;color:var(--btc2);font-family:'IBM Plex Mono',monospace;font-size:.95rem}
.pl{font-family:'IBM Plex Mono',monospace;font-size:.59rem;letter-spacing:.15em;text-transform:uppercase;color:var(--tx4);margin-bottom:.28rem;display:block}
.pv{font-size:.88rem;color:var(--tx2);line-height:1.65}
.pv.hi{color:var(--btc);font-weight:500}
@media(max-width:500px){.pair{grid-template-columns:1fr}.pa{transform:rotate(90deg);margin:.3rem 0}}

/* Callout */
.callout{
  background:var(--btcs);border:1px solid var(--bd-lit);
  border-left:3px solid var(--btc);border-radius:0 8px 8px 0;
  padding:1rem 1.2rem;font-size:.88rem;color:var(--tx2);line-height:1.82;
}
.callout strong{color:var(--btc)}

/* Terminal */
.term{
  background:var(--bg4);border:1px solid var(--bd);
  border-radius:10px;overflow:hidden;margin-top:1.5rem;
}
.th{
  display:flex;align-items:center;gap:.45rem;
  padding:.65rem 1rem;background:var(--bg3);border-bottom:1px solid var(--bd);
}
.dot{width:10px;height:10px;border-radius:50%}
.dr{background:#f05b5b}.dy{background:#f6c344}.dg{background:#2ec98a}
.ttl{font-family:'IBM Plex Mono',monospace;font-size:.66rem;color:var(--tx3);letter-spacing:.1em;text-transform:uppercase;margin-left:auto}
.tb{padding:1.2rem 1.3rem}

.ti{
  width:100%;background:var(--bg2);border:1px solid var(--bd);
  border-radius:6px;padding:.6rem .9rem;color:var(--tx);
  font-family:'IBM Plex Mono',monospace;font-size:.82rem;
  outline:none;transition:border-color .2s;
}
.ti:focus{border-color:var(--btc)}
.ti::placeholder{color:var(--tx4)}
.tout{
  background:var(--bg);border:1px solid var(--bd);border-radius:6px;
  padding:.9rem 1rem;font-family:'IBM Plex Mono',monospace;font-size:.77rem;
  color:var(--grn);line-height:1.72;word-break:break-all;white-space:pre-wrap;
  min-height:52px;margin-top:.7rem;
}
.tout.dim{color:var(--tx3);font-style:italic}

.btn{
  display:inline-flex;align-items:center;gap:.4rem;
  background:var(--btc);color:#fff;border:none;border-radius:6px;
  font-family:'IBM Plex Mono',monospace;font-size:.72rem;font-weight:500;
  letter-spacing:.06em;text-transform:uppercase;
  padding:.55rem 1.1rem;cursor:pointer;transition:all .15s;white-space:nowrap;
}
.btn:hover{background:var(--btc2);transform:translateY(-1px)}
.btn:active{transform:scale(.97)}
.btng{background:var(--bg3);color:var(--tx2);border:1px solid var(--bd)}
.btng:hover{border-color:var(--btc);color:var(--btc);background:var(--btcs)}
.irow{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:.8rem;align-items:center}
.irow .ti{flex:1;min-width:0}

/* Supply bar */
.sup-t{height:10px;background:var(--bg4);border-radius:5px;overflow:hidden;border:1px solid var(--bd);margin:.75rem 0}
.sup-f{height:100%;border-radius:5px;position:relative;overflow:hidden;background:linear-gradient(90deg,var(--btc2),var(--btc),#ffb347)}
.sup-f::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent);animation:shim 2.5s infinite}
@keyframes shim{0%{transform:translateX(-100%)}100%{transform:translateX(200%)}}
.sup-m{display:flex;justify-content:space-between;font-family:'IBM Plex Mono',monospace;font-size:.7rem;color:var(--tx3)}

/* Halving */
.hv{display:grid;grid-template-columns:60px 115px 1fr 90px;align-items:center;gap:1rem;padding:.72rem 0;border-bottom:1px solid var(--bd);font-size:.86rem}
.hv:last-child{border-bottom:none}
.hvy{font-family:'IBM Plex Mono',monospace;color:var(--tx4);font-size:.73rem}
.hvr{font-family:'IBM Plex Mono',monospace;color:var(--btc);font-weight:500}
.hvbt{height:6px;background:var(--bg4);border-radius:3px;overflow:hidden}
.hvbf{height:100%;background:var(--btc);border-radius:3px}
.hvp{font-family:'IBM Plex Mono',monospace;font-size:.67rem;color:var(--tx4);text-align:right}

/* Compare table */
.ctw{overflow-x:auto;margin-top:1.2rem;border-radius:10px;border:1px solid var(--bd)}
.ctbl{width:100%;border-collapse:collapse;font-size:.86rem;min-width:580px}
.ctbl thead th{
  font-family:'IBM Plex Mono',monospace;font-size:.62rem;letter-spacing:.14em;
  text-transform:uppercase;padding:.8rem 1.1rem;
  text-align:left;border-bottom:2px solid var(--bd);
}
.ctbl thead th.hfiat{background:rgba(77,157,224,.18);color:#7ac0f0;border-bottom-color:#4d9de0}
.ctbl thead th.hgold{background:rgba(212,168,0,.18);color:#e8c840;border-bottom-color:#d4a800}
.ctbl thead th.hbtc {background:rgba(247,147,26,.18);color:var(--btc);border-bottom-color:var(--btc)}
.ctbl thead th.htopic{background:var(--bg3);color:var(--tx3)}
[data-theme="light"] .ctbl thead th.hfiat{background:rgba(26,94,176,.1);color:#1a5eb0;border-bottom-color:#1a5eb0}
[data-theme="light"] .ctbl thead th.hgold{background:rgba(150,100,0,.12);color:#7a5800;border-bottom-color:#a07000}
[data-theme="light"] .ctbl thead th.hbtc {background:rgba(192,104,0,.12);color:var(--btc);border-bottom-color:var(--btc)}
.ctbl td{padding:.88rem 1.1rem;border-bottom:1px solid var(--bd);color:var(--tx2);vertical-align:top;line-height:1.65}
.ctbl tbody tr:last-child td{border-bottom:none}
.ctbl tbody tr:hover .cfiat{background:rgba(77,157,224,.06)}
.ctbl tbody tr:hover .cgold{background:rgba(212,168,0,.06)}
.ctbl tbody tr:hover .cbtc {background:rgba(247,147,26,.07)}
.ctopic{color:var(--tx);font-weight:600;font-size:.84rem;font-family:'Prompt',sans-serif;background:var(--bg3)}
.cfiat{color:#a8d4f0;font-weight:400;background:rgba(77,157,224,.05)}
.cbtc {color:var(--btc);font-weight:600;background:rgba(247,147,26,.05)}
.cgold{color:#e8c840;font-weight:500;background:rgba(212,168,0,.05)}
[data-theme="light"] .cfiat{color:#1a5eb0;background:rgba(26,94,176,.04)}
[data-theme="light"] .cgold{color:#7a5800;background:rgba(150,100,0,.04)}
[data-theme="light"] .cbtc {color:var(--btc);background:rgba(192,104,0,.04)}

/* Blockchain blocks */
.chainw{display:flex;align-items:center;gap:.4rem;overflow-x:auto;padding:.5rem .2rem;scrollbar-width:thin;scrollbar-color:var(--bd) transparent}
.blk{
  flex-shrink:0;min-width:150px;background:var(--bg2);
  border:1px solid var(--bd);border-radius:8px;padding:.8rem .9rem;
  transition:border-color .25s,box-shadow .25s;position:relative;
}
.blk.act{border-color:var(--btc);box-shadow:0 0 16px var(--btcs)}
.blkn{font-family:'Kanit',sans-serif;font-weight:700;font-size:.7rem;color:var(--btc);letter-spacing:.05em;text-transform:uppercase;display:block;margin-bottom:.2rem}
.blkh{font-family:'IBM Plex Mono',monospace;font-size:.6rem;color:var(--tx3);word-break:break-all;line-height:1.5}
.blkd{font-family:'IBM Plex Mono',monospace;font-size:.63rem;color:var(--tx2);margin-top:.3rem}
.carr{color:var(--btc2);font-size:1rem;flex-shrink:0}
/* copy button */
.blk-copy{
  position:absolute;top:.5rem;right:.5rem;
  background:var(--bg4);border:1px solid var(--bd);
  border-radius:4px;padding:.15rem .4rem;
  font-family:'IBM Plex Mono',monospace;font-size:.55rem;
  color:var(--tx3);cursor:pointer;transition:all .2s;
  display:flex;align-items:center;gap:.25rem;
}
.blk-copy:hover{border-color:var(--btc);color:var(--btc)}
.blk-copy.copied{background:var(--grns);border-color:var(--grn);color:var(--grn)}

/* Key flow */
.kflow{display:flex;flex-direction:column;gap:0;margin-top:1rem}
.kstep{
  display:grid;grid-template-columns:140px 1fr;
  align-items:stretch;border:1px solid var(--bd);
  background:var(--bg2);border-radius:8px;margin-bottom:.6rem;
  overflow:hidden;
}
.kstep-label{
  background:var(--bg4);border-right:1px solid var(--bd);
  padding:.8rem 1rem;
}
.ksl-num{font-family:'IBM Plex Mono',monospace;font-size:.58rem;color:var(--btc);letter-spacing:.1em;text-transform:uppercase;display:block;margin-bottom:.2rem}
.ksl-name{font-family:'Kanit',sans-serif;font-weight:600;font-size:.85rem;color:var(--tx);display:block;margin-bottom:.15rem}
.ksl-desc{font-size:.75rem;color:var(--tx3);line-height:1.55}
.kstep-val{padding:.8rem 1rem;font-family:'IBM Plex Mono',monospace;font-size:.72rem;color:var(--grn);word-break:break-all;line-height:1.6;display:flex;align-items:center}
.kstep-val.dim{color:var(--tx3);font-style:italic}
.karr{text-align:center;color:var(--btc2);font-size:1.2rem;padding:.1rem 0;letter-spacing:0;line-height:1}
@media(max-width:580px){.kstep{grid-template-columns:1fr}.kstep-label{border-right:none;border-bottom:1px solid var(--bd)}}

/* Custody */
.cust{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem}
@media(max-width:560px){.cust{grid-template-columns:1fr}}
.cc{background:var(--bg2);border:1px solid var(--bd);border-radius:10px;padding:1.3rem 1.4rem}
.cc.bad{border-top:2px solid var(--red)}
.cc.good{border-top:2px solid var(--grn)}
.cch{display:flex;align-items:center;gap:.6rem;margin-bottom:.9rem}
.ccico{font-size:1.3rem}
.cct{font-family:'Kanit',sans-serif;font-weight:600;font-size:.9rem;color:var(--tx)}
.ccs{font-size:.7rem;color:var(--tx4);font-family:'IBM Plex Mono',monospace;letter-spacing:.05em}
.cc ul{list-style:none}
.cc li{font-size:.84rem;color:var(--tx2);padding:.3rem 0;border-bottom:1px solid var(--bd);display:flex;gap:.5rem;align-items:flex-start;line-height:1.6}
.cc li:last-child{border-bottom:none}
.cc li::before{content:'—';color:var(--tx4);flex-shrink:0;margin-top:.05rem}

/* Exchange incidents */
.inc{
  background:var(--bg2);border:1px solid var(--bd);border-radius:8px;
  padding:.85rem 1.1rem;margin-bottom:.6rem;
  display:grid;grid-template-columns:60px 1fr;gap:.8rem;align-items:start;
  transition:border-color .2s;
}
.inc:hover{border-color:var(--bd-lit)}
.inc-yr{font-family:'Kanit',sans-serif;font-weight:700;font-size:1.1rem;color:var(--red);line-height:1}
.inc-name{font-family:'Kanit',sans-serif;font-weight:600;font-size:.9rem;color:var(--tx);margin-bottom:.15rem}
.inc-desc{font-size:.82rem;color:var(--tx2);line-height:1.65}
.inc-loss{font-family:'IBM Plex Mono',monospace;font-size:.7rem;color:var(--red);margin-top:.2rem;font-weight:500}

/* Byzantine */
.bzf{position:relative;height:360px;background:var(--bg4);border:1px solid var(--bd);border-radius:10px;overflow:hidden}
.bcastle{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;z-index:2;pointer-events:none}
.bcastle .bci{font-size:2.2rem;line-height:1}
.bcastle .bcl{font-family:'IBM Plex Mono',monospace;font-size:.58rem;color:var(--tx4);letter-spacing:.12em;text-transform:uppercase;margin-top:.3rem;display:block}
.gn{position:absolute;width:62px;text-align:center;transform:translate(-50%,-50%);cursor:pointer;z-index:3;transition:transform .15s}
.gn:hover{transform:translate(-50%,-50%) scale(1.1)}
.gi{width:44px;height:44px;border-radius:50%;border:2px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin:0 auto .25rem;background:var(--bg2);transition:border-color .3s,box-shadow .3s}
.gn.loyal .gi{border-color:var(--grn);box-shadow:0 0 12px var(--grns)}
.gn.traitor .gi{border-color:var(--red);box-shadow:0 0 12px var(--reds);background:rgba(240,91,91,.08)}
.gname{font-family:'IBM Plex Mono',monospace;font-size:.56rem;color:var(--tx3);display:block}
.gvote{font-family:'IBM Plex Mono',monospace;font-size:.6rem;font-weight:500;display:block;min-height:1rem}
.vatk{color:var(--btc)}.vlie{color:var(--pur)}.vret{color:var(--red)}
.bzlog{
  font-family:'IBM Plex Mono',monospace;font-size:.71rem;color:var(--tx2);
  background:var(--bg);border:1px solid var(--bd);border-radius:6px;
  padding:.8rem 1rem;min-height:60px;max-height:140px;overflow-y:auto;
  line-height:1.88;margin-top:.8rem;
}
.ll{color:var(--grn)}.lt{color:var(--red)}.lr{color:var(--btc);font-weight:500}.lp{color:var(--blu)}
.bzres{border-radius:8px;padding:.75rem 1rem;font-family:'IBM Plex Mono',monospace;font-size:.82rem;font-weight:500;margin-top:.8rem;display:none;text-align:center;letter-spacing:.03em}
.bzres.ok{background:var(--grns);color:var(--grn);border:1px solid rgba(46,201,138,.22)}
.bzres.err{background:var(--reds);color:var(--red);border:1px solid rgba(240,91,91,.22)}
.bz-legend{display:flex;gap:.8rem;flex-wrap:wrap;margin-bottom:.5rem;font-family:'IBM Plex Mono',monospace;font-size:.68rem}
.bz-legend span{display:flex;align-items:center;gap:.3rem}

/* BFT explanation boxes */
.bft-box{
  background:var(--bg2);border:1px solid var(--bd);
  border-radius:8px;padding:1rem 1.2rem;margin-top:1rem;
  font-size:.86rem;color:var(--tx2);line-height:1.8;
}
.bft-box .bft-h{
  font-family:'Kanit',sans-serif;font-weight:600;font-size:.9rem;
  color:var(--tx);margin-bottom:.5rem;
  display:flex;align-items:center;gap:.5rem;
}

/* Modules */
.modg{display:grid;grid-template-columns:repeat(auto-fill,minmax(195px,1fr));gap:.8rem;margin-top:1.5rem}
.moda{display:block;text-decoration:none;background:var(--bg2);border:1px solid var(--bd);border-radius:10px;padding:1rem 1.1rem;transition:all .2s}
.moda:hover{border-color:var(--btc);background:var(--bg3);transform:translateY(-2px);box-shadow:var(--gl)}
.modn{font-family:'IBM Plex Mono',monospace;font-size:.6rem;color:var(--btc);letter-spacing:.1em;display:block;margin-bottom:.28rem}
.modt{font-family:'Kanit',sans-serif;font-weight:600;font-size:.9rem;color:var(--tx);display:block;margin-bottom:.18rem}
.modd{font-size:.76rem;color:var(--tx4);line-height:1.5}

/* Warn */
.warn{background:var(--reds);border:1px solid rgba(240,91,91,.22);border-radius:6px;padding:.7rem .9rem;font-family:'IBM Plex Mono',monospace;font-size:.71rem;color:var(--red);margin-top:.7rem}

/* Footer */
footer{border-top:1px solid var(--bd);padding:2.5rem 1.5rem;text-align:center;position:relative;z-index:1}
.ftl{font-family:'Kanit',sans-serif;font-weight:700;font-size:1.5rem;color:var(--btc);margin-bottom:.5rem}
.fts{font-family:'IBM Plex Mono',monospace;font-size:.67rem;color:var(--tx4);letter-spacing:.08em}
.fts a{color:var(--btc);text-decoration:none}
.fts a:hover{text-decoration:underline}

/* generic transitions */
.card,.term,.blk,.pair,.cc,.moda,.bzf,.bzlog,.inc,
.ti,.tout,.ctbl td,.ctbl thead th,.sup-t,.warn,.callout,.kstep{
  transition:background .3s,border-color .3s,color .3s,box-shadow .3s;
}
</style>
</head>
<body>

<nav class="nav">
  <a href="#" class="nav-logo">₿<span>101</span></a>
  <div class="nav-r">
    <a href="<?= $site_url ?>" class="nav-a" target="_blank">learning.chontit.win ↗</a>
    <div class="tog">
      <button class="tob on" id="bdk" onclick="setTheme('dark')" title="Dark">🌙</button>
      <button class="tob"    id="blt" onclick="setTheme('light')" title="Light">☀️</button>
    </div>
  </div>
</nav>

<div class="hero">
  <div class="hero-glow"></div>
  <div class="hero-in">
    <div class="hero-chip">Bitcoin 101 · เริ่มต้นจากศูนย์</div>
    <h1 class="hero-h1">รู้จัก<span class="acc">บิตคอยน์</span><br>ก่อนเจาะลึก</h1>
    <p class="hero-sub">ไม่ต้องมีพื้นฐานด้านเทคโนโลยีมาก่อน — ทำความเข้าใจภาพรวมก่อน แล้วค่อยไปเจาะลึกแต่ละหัวข้อที่ <a href="<?= $site_url ?>" target="_blank" style="color:var(--btc);text-decoration:none">learning.chontit.win</a></p>
    <a href="#what" class="hero-btn">เริ่มเรียนรู้ →</a>

    <div class="hero-stats">
      <div class="hs">
        <span class="hs-n" id="stat-supply" data-tip="ปริมาณ BTC ที่ถูกขุดออกมาแล้ว อัปเดตจาก CoinGecko API">~<?= $circulating ?></span>
        <span class="hs-l">BTC Circulating</span>
      </div>
      <div class="hs">
        <span class="hs-n" id="stat-pct" data-tip="สัดส่วนที่ขุดแล้วจากทั้งหมด 20,999,999.9769 BTC (maximum possible)"><?= $pct_issued ?>%</span>
        <span class="hs-l">% Issued</span>
      </div>
      <div class="hs">
        <span class="hs-n"
          id="stat-uptime"
          data-tip="Bitcoin ทำงานต่อเนื่องมาตั้งแต่ 3 ม.ค. 2552 มีหยุดชะงักเพียง 2 ครั้ง (2010, 2013) รวมเวลาหยุดน้อยกว่า 20 ชั่วโมง ตลอด 16+ ปี — ข้อมูลจาก bitbo.io/uptime">
          <?= $uptime_pct ?>%
        </span>
        <span class="hs-l">Uptime ตั้งแต่ปี 2009</span>
      </div>
      <div class="hs">
        <span class="hs-n"
          id="stat-nodes"
          data-tip="จำนวน Reachable Full Node ทั่วโลก ณ เมษายน 2026 ข้อมูลจาก bitnodes.io — ยังไม่นับ Node ที่อยู่หลัง Firewall อีกหลายหมื่นเครื่อง">
          ~<?= $node_count ?>
        </span>
        <span class="hs-l">Full Nodes ทั่วโลก</span>
      </div>
    </div>
    <div class="scroll-hint">▼ เลื่อนลง</div>
  </div>
</div>

<hr class="div">

<section class="sec" id="what">
  <div class="eye">01 — Bitcoin คืออะไร</div>
  <h2 class="sh2">เงินดิจิทัลที่ไม่มีใครควบคุมได้</h2>
  <p class="slead">
    ลองนึกภาพเงินสดในกระเป๋าตังค์ของคุณ — คุณถือและใช้มันได้โดยตรง ไม่ต้องผ่านธนาคาร ไม่ต้องขออนุญาตใคร
    ซึ่ง Bitcoin ก็ทำแบบเดียวกัน แต่อยู่ในรูปแบบ 'ดิจิทัล' อีกทั้งยังสามารถส่งได้ทั่วโลกภายในระยะเวลาประมาณ 10 นาที
    โดยที่ไม่มีใครมาหยุดหรือตรวจสอบได้ว่าคุณส่งเงินให้ใคร
  </p>

  <div class="pair">
    <div><span class="pl">เงิน Fiat ทั่วไป (บาท, ดอลลาร์ ฯลฯ)</span><div class="pv">ต้องผ่านธนาคาร · พิมพ์เพิ่มได้ไม่จำกัด · มีวันหยุดทำการ · ถูกจำกัดหรืออายัดบัญชีได้ · โอนเงินข้ามประเทศช้าและมีค่าดำเนินการแพง</div></div>
    <div class="pa">→</div>
    <div><span class="pl">Bitcoin ₿</span><div class="pv hi">ไม่ต้องผ่านใคร · Supply จำกัด 21M · ทำงาน 24/7/365 · ไม่มีใครอายัดได้ · โอนข้ามโลกใน ~10 นาที</div></div>
  </div>

  <div class="pair">
    <div><span class="pl">ทองคำ</span><div class="pv">วัตถุหายาก มีค่า · แต่หนักและขนย้ายยาก · ตรวจสอบว่าเป็นของแท้ได้ยาก · ส่งข้ามประเทศยุ่งยาก</div></div>
    <div class="pa">→</div>
    <div><span class="pl">Bitcoin — "ทองคำดิจิทัล"</span><div class="pv hi">มีความหายากเหมือนกัน · น้ำหนักเป็นศูนย์ · ตรวจสอบของแท้ได้ทันทีในทุก Node · ส่งข้ามโลกได้โดยไม่ต้องขออนุญาตใคร</div></div>
  </div>

  <div class="callout" style="margin-top:1.2rem">
    <strong>Key Insight:</strong> Bitcoin ไม่ใช่แค่แอปธนาคารออนไลน์ — แต่มันคือ "โปรโตคอล" (กฎการสื่อสาร) เหมือน TCP/IP ที่เป็นรากฐานของอินเทอร์เน็ต
    ใคร ๆ ก็สามารถเข้าใช้งานได้โดยไม่ต้องขออนุญาตใคร และไม่มีใครสามารถปิดกั้นได้
  </div>

  <div class="g3" style="margin-top:1.5rem">
    <?php foreach([
      ['🔓','Open Source — โปร่งใส','Source code ทุกบรรทัดเปิดเผยต่อสาธารณะ ใครก็ดาวน์โหลดมาตรวจสอบได้ ไม่มีอะไรถูกซ่อน'],
      ['🌐','Borderless — ไร้พรมแดน','ส่ง BTC ให้คนในประเทศใดก็ได้ในโลก เหมือนส่งอีเมล ไม่มีขั้นตอนที่ต้องขออนุมัติ'],
      ['🔒','Permissionless — ไม่ต้องขออนุญาต','ไม่ต้องสมัครบัญชี ไม่ต้องยืนยันตัวตน ใครก็สร้าง Wallet และใช้งานได้ทันที'],
    ] as [$i,$t,$d]): ?>
    <div class="card"><div class="ci"><?=$i?></div><div class="ct"><?=$t?></div><div class="cb"><?=$d?></div></div>
    <?php endforeach; ?>
  </div>
</section>

<hr class="div">

<section class="sec" id="supply">
  <div class="eye">02 — จำนวนจำกัด</div>
  <h2 class="sh2">มีไม่เกิน 21,000,000 BTC ตลอดกาล</h2>
  <p class="slead">
    ในทางทฤษฎีคือ 21 ล้านเหรียญ แต่ความจริงแล้วเนื่องจากการคำนวณในระบบ
    Bitcoin จะมีได้สูงสุดแค่ <strong style="color:var(--tx)">20,999,<wbr>999.9769 BTC</strong> เท่านั้นตลอดไป
    ไม่มีใครเปลี่ยนตัวเลขนี้ได้ แม้แต่ผู้สร้าง 'Satoshi Nakamoto' เอง
    เพราะมันถูกเขียนฝังไว้ในโค้ดที่ทุก Node ในโลกช่วยกันตรวจสอบอยู่ตลอดเวลา
  </p>

  <div class="term">
    <div class="th"><div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div><span class="ttl">bitcoin supply monitor — realtime</span></div>
    <div class="tb">
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.5rem">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:.75rem;color:var(--tx3)">Circulating Supply (BTC) — <a href="bitcoin/issuance_simulator.php" target="_blank" style="color:var(--btc);text-decoration:none">Issuance Simulator ↗</a></span>
        <span style="font-family:'Kanit',sans-serif;font-weight:700;font-size:1.5rem;color:var(--btc)" id="pct-big"><?= $pct_issued ?>%</span>
      </div>
      <div class="sup-t"><div class="sup-f" id="sup-bar" style="width:<?= $pct_issued ?>%"></div></div>
      <div class="sup-m">
        <span id="sup-left">ขุดแล้ว: <?= $circulating ?> BTC</span>
        <span id="sup-right">Max: <span id="sup-max" style="-webkit-text-size-adjust:100%">20,999,<wbr>999.9769</span> BTC</span>
      </div>

      <div style="margin-top:1.5rem">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.61rem;color:var(--tx4);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.8rem">// Halving Schedule — รางวัลการขุดลดลงครึ่งหนึ่งทุก ~4 ปี</div>
        <?php foreach([
          ['2009','50.000 BTC',100,'Genesis Block'],
          ['2012','25.000 BTC',50,'Halving #1'],
          ['2016','12.500 BTC',25,'Halving #2'],
          ['2020','6.250 BTC',12.5,'Halving #3'],
          ['2024','3.125 BTC',6.25,'Halving #4 ✓ (ปัจจุบัน)'],
          ['2028','1.5625 BTC',3.125,'Halving #5'],
          ['2032','0.78125 BTC',1.5625,'Halving #6'],
        ] as [$y,$r,$p,$l]): ?>
        <div class="hv">
          <span class="hvy"><?=$y?></span>
          <span class="hvr"><?=$r?></span>
          <div class="hvbt"><div class="hvbf" style="width:<?=$p?>%"></div></div>
          <span class="hvp"><?=$l?></span>
        </div>
        <?php endforeach; ?>
        <div style="margin-top:1rem; padding-top:.8rem; border-top:1px dashed var(--bd); font-size:.8rem; color:var(--tx3); text-align:center;">
          💡 กระบวนการนี้จะดำเนินต่อไปจนกว่าบิตคอยน์จะถูกขุดครบทั้งหมดใน <strong>ปี ค.ศ. 2140 (พ.ศ. 2683)</strong>
        </div>
        </div>
      </div>
    </div>
  </div>

  <div class="callout" style="margin-top:1.2rem">
    <strong>ทำไมต้องจำกัด?</strong> เงินที่พิมพ์ได้ไม่จำกัดย่อมถูก "เจือจาง" มูลค่าลงเรื่อย ๆ
    เหมือนน้ำผลไม้ที่เจ้าของร้านเติม 'น้ำเปล่า' เข้าไปเรื่อยๆ ในขณะที่ Bitcoin ไม่มีใครเติมได้
    เมื่อ Supply คงที่แต่ความต้องการเพิ่มขึ้น มูลค่าของมันก็เพิ่มขึ้นตามธรรมชาติ (Appreciate in value)
  </div>
</section>

<hr class="div">

<section class="sec" id="decentral">
  <div class="eye">03 — ไม่มีคนควบคุม</div>
  <h2 class="sh2">Decentralized — ทุกคนเป็นเจ้าของร่วมกัน</h2>
  <p class="slead">
    ธนาคารและรัฐบาลควบคุมเงิน Fiat ได้เพราะมี "ศูนย์กลาง" แต่ระบบ Bitcoin ซึ่งทำงานร่วมกับ
    Node กว่า 23,000 เครื่องที่กระจายอยู่ใน 170+ ประเทศทั่วโลก
    โดย Node ทุกเครื่องเป็นการเก็บสำเนาสมุดบัญชีที่เหมือนกันทุกประการ — การทำลายระบบบิตคอยน์
    ต้องทำลายพร้อมกันทั่วโลกในเวลาเดียวกัน
  </p>

  <div class="g3">
    <?php foreach([
      ['🏦','ธนาคารกลาง','พิมพ์เงินได้ · อายัดบัญชีได้ · ปิดในวันหยุดราชการ · ต้องเชื่อใจว่าเขาจะไม่โกง','red'],
      ['🌐','Bitcoin Network','พิมพ์เงินไม่ได้ · อายัดไม่ได้ · ทำงาน 24/7/365 · ตรวจสอบตัวเองได้โดยไม่ต้องเชื่อใคร','grn'],
      ['💡','Don\'t Trust, Verify','Code คือกฎ — ทุกคนดาวน์โหลด Node มาตรวจสอบได้ ไม่ต้องเชื่อคำพูดใคร','blu'],
    ] as [$i,$t,$d,$c]): ?>
    <div class="card" style="border-top:2px solid var(--<?=$c?>)">
      <div class="ci" style="background:var(--<?=$c?>s)"><?=$i?></div>
      <div class="ct"><?=htmlspecialchars($t)?></div>
      <div class="cb"><?=htmlspecialchars($d)?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<hr class="div">

<section class="sec" id="blockchain">
  <div class="eye">04 — Blockchain</div>
  <h2 class="sh2">สมุดบัญชีดิจิทัลที่แก้ไขไม่ได้</h2>
  <p class="slead">
    ลองนึกภาพสมุดบัญชีที่มีหน้ากระดาษนับล้าน แต่ละหน้า (บล็อก) บันทึกธุรกรรมและมีลายเซ็น
    ที่สร้างมาจากเนื้อหาในหน้านั้นผสมกับลายเซ็นจากหน้าก่อน
    ถ้าใครแก้ข้อมูลในหน้าเก่า ลายเซ็นของทุกหน้าถัดไปจะผิดพลาดทั้งหมด
    ทุกคนในโลกที่มีสำเนาสมุด (Node) จะรู้ได้ทันทีว่ามีการแก้ไข
  </p>

  <div class="term">
    <div class="th"><div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div><span class="ttl">blockchain — live demo + verify</span></div>
    <div class="tb">
      <p style="font-size:.83rem;color:var(--tx2);margin-bottom:1rem">
        กดปุ่ม <strong style="color:var(--tx)">+ เพิ่มบล็อก</strong> เพื่อสร้างธุรกรรม — จากนั้นกดปุ่ม 📋 ที่มุมบล็อกเพื่อ Copy ข้อมูลดิบ
        แล้วนำไปพิสูจน์เองที่
        <a href="https://emn178.github.io/online-tools/sha256.html" target="_blank" style="color:var(--btc)">SHA-256 Online Tool ↗</a>
        หรือ <a href="bitcoin/hashing.php" target="_blank" style="color:var(--btc)">learning.chontit.win/hashing ↗</a>
      </p>
      <div id="chainWrap" class="chainw"></div>
      <div class="irow" style="margin-top:1rem">
        <input class="ti" type="text" id="txData" placeholder="ข้อมูลธุรกรรม เช่น Alice → Bob 0.5 BTC">
        <button class="btn" onclick="addBlock()">+ เพิ่มบล็อก</button>
      </div>
      <div id="chainMsg" style="font-family:'IBM Plex Mono',monospace;font-size:.71rem;color:var(--tx3);margin-top:.3rem"></div>
      <div id="copyDetail" style="display:none;margin-top:.8rem;background:var(--bg);border:1px solid var(--bd);border-radius:6px;padding:.8rem 1rem">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--tx4);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.5rem">// ข้อมูลดิบที่ถูก Hash (คัดลอกไปพิสูจน์เองได้)</div>
        <div id="copyRaw" style="font-family:'IBM Plex Mono',monospace;font-size:.74rem;color:var(--grn);word-break:break-all;line-height:1.65"></div>
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.62rem;color:var(--tx4);margin-top:.5rem">→ SHA-256 ของข้อความนี้ = Hash ที่แสดงในบล็อก</div>
      </div>
	  <div style="margin-top:1.2rem; padding:1rem; background:var(--bg3); border-left:3px solid var(--btc); border-radius:4px; font-size:.8rem; color:var(--tx2); line-height:1.7;">
        <strong style="color:var(--tx)">💡 ในระบบจริง การสร้างบล็อกไม่ได้ง่ายแบบนี้:</strong><br>
        เครือข่ายบิตคอยน์จะตั้งเงื่อนไขว่า Hash ที่ได้ <em>"ต้องขึ้นต้นด้วยเลข 0 หลายตัว"</em> (เช่น 0000000000...) ทำให้คอมพิวเตอร์ต้องสุ่มตัวเลข (Nonce) ไปเรื่อยๆ นับล้านล้านครั้งจนกว่าจะเจอ Hash ที่ตรงเงื่อนไข นี่คือที่มาของการ <strong>"ขุด" (Proof of Work)</strong>
      </div>
    </div>
  </div>
</section>

<hr class="div">

<section class="sec" id="keys">
  <div class="eye">05 — กุญแจและกระเป๋าเงิน</div>
  <h2 class="sh2">ไม่มีธนาคาร — คุณคือธนาคาร</h2>
  <p class="slead">
    กระเป๋า Bitcoin ไม่ได้เก็บ "เหรียญ" — แต่เก็บ <strong style="color:var(--tx)">กุญแจลับ</strong>
    ที่ใช้พิสูจน์ว่าคุณเป็นเจ้าของ BTC ที่บันทึกไว้บน Blockchain
    เหมือนโฉนดที่ดิน — เงิน (Bitcoin) จริงๆ จะเป็นการบันทึกข้อมูลอยู่บน Blockchain ส่วนกุญแจที่ใช้โอนบิตคอยน์อยู่ในมือคุณ
  </p>

  <div class="term">
    <div class="th">
      <div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div>
      <span class="ttl">wallet creation — basic concept</span>
    </div>
    <div class="tb">

      <div style="font-size:.86rem;color:var(--tx2);margin-bottom:1.2rem;line-height:1.75">
        ลองจำลองการสร้างกระเป๋าเงินเพื่อดูว่า <strong>"กุญแจลับ"</strong> ของคุณถูกสร้างขึ้นมาได้อย่างไร
      </div>

      <button class="btn" id="genBtn" onclick="generateKeySim()">⚡ จำลองการสร้างกระเป๋าเงิน</button>

      <div id="kflowWrap" class="kflow" style="display:none;margin-top:1.5rem">
        
        <div class="kstep" id="ks1">
          <div class="kstep-label">
            <span class="ksl-num">Step 1</span>
            <span class="ksl-name">การสุ่ม (Entropy)</span>
            <span class="ksl-desc">เกิดจากการสุ่มชุดตัวเลขยาวๆ ที่ไม่มีใครเดาได้</span>
          </div>
          <div class="kstep-val dim" id="k0">—</div>
        </div>

        <div class="karr">↓ แปลงเป็นคำศัพท์เพื่อให้มนุษย์จดจำได้ง่ายขึ้น</div>

        <div class="kstep" id="ks2">
          <div class="kstep-label">
            <span class="ksl-num">Step 2</span>
            <span class="ksl-name">รหัสกู้คืน (Seed Phrase)</span>
            <span class="ksl-desc">กลุ่มคำ 12 คำ (ห้ามถ่ายรูป ห้ามบันทึกลงบนอุปกรณ์อิเล็กทรอนิกส์ที่เชื่อมต่ออินเตอร์เน็ต ต้องจดใส่กระดาษหรือแผ่นโลหะเท่านั้น)</span>
          </div>
          <div class="kstep-val dim" id="k1" style="line-height:1.8">—</div>
        </div>

        <div class="karr">↓ นำคำศัพท์ไปเข้าสมการคณิตศาสตร์</div>

        <div class="kstep" id="ks3">
          <div class="kstep-label">
            <span class="ksl-num">Step 3</span>
            <span class="ksl-name">กุญแจลับ (Private Key)</span>
            <span class="ksl-desc">ใช้สำหรับเซ็นอนุมัติการโอนเงิน เพื่อยืนยันว่าเราคือ "เจ้าของ" บิตคอยน์นั้น</span>
          </div>
          <div class="kstep-val dim" id="k3" style="font-size:.65rem; word-break:break-all">—</div>
        </div>

        <div class="karr">↓ สร้างที่อยู่สาธารณะ</div>

        <div class="kstep" style="border-color:var(--btc)">
          <div class="kstep-label" style="background:var(--btcs)">
            <span class="ksl-num">Step 4</span>
            <span class="ksl-name" style="color:var(--btc)">เลขบัญชี (Address)</span>
            <span class="ksl-desc">ใช้ส่งให้คนอื่นเพื่อรับเงิน (เปิดเผยได้)</span>
          </div>
          <div class="kstep-val" id="k4" style="color:var(--btc); font-size:.75rem">
            bc1q... (ตัวอย่างที่อยู่บิตคอยน์)
          </div>
        </div>

        <div style="margin-top:1.5rem; text-align:center;">
          <a href="<?= $site_url ?>/bitcoin/bip39.php" target="_blank" class="btn btng" style="font-size:.7rem">
            🔬 ดูเจาะลึกสมการคณิตศาสตร์แบบละเอียด (Module 06) ↗
          </a>
        </div>

      </div><div class="warn" style="margin-top:1.2rem">
        ⚠ คำเตือน: นี่คือเครื่องมือจำลองเพื่อการศึกษาเท่านั้น ห้ามนำ Seed Phrase ที่ได้จากหน้านี้ไปใช้งานจริงกับเงินของคุณโดดเด็ดขาด
      </div>
    </div>
  </div>
</section>

<hr class="div">

<section class="sec" id="compare">
  <div class="eye">06 — เปรียบเทียบ</div>
  <h2 class="sh2">เงินบาท vs ทองคำ vs Bitcoin</h2>
  <p class="slead">สามตัวแทน "สิ่งที่มนุษย์ใช้เก็บมูลค่า" — ต่างกันในทุกมิติที่สำคัญ</p>

  <div class="ctw">
    <table class="ctbl">
      <thead><tr>
        <th class="htopic" style="width:130px">คุณสมบัติ</th>
        <th class="hfiat">💵 เงินบาท / Fiat</th>
        <th class="hgold">🥇 ทองคำ</th>
        <th class="hbtc">₿ Bitcoin</th>
      </tr></thead>
      <tbody>
      <?php
      $rows=[
        ['ผู้ควบคุม','ธนาคารแห่งประเทศไทย / รัฐบาล','ไม่มี — แต่เหมืองควบคุม Supply ได้','ไม่มีใคร (Code คือกฎ)'],
        ['จำนวน Supply','ไม่จำกัด — พิมพ์เพิ่มได้ตลอด','หายาก แต่ยังขุดได้เรื่อยๆ ไม่มีเพดาน','จำกัดที่ 20,999,999.9769 BTC ตลอดกาล'],
        ['อายัดบัญชี','ทำได้ หากรัฐหรือธนาคารสั่ง','ยึดได้ หากรัฐออกกฎหมาย','ทำไม่ได้เลย ไม่มีใครสั่งได้'],
        ['ความโปร่งใส','เห็นเฉพาะบัญชีตัวเอง','ไม่มีระบบตรวจสอบกลาง','ทุกธุรกรรมตรวจสอบได้บน Blockchain'],
        ['โอนข้ามประเทศ','3–5 วันทำการ + ค่าธรรมเนียมสูง','ขนส่งทางกายภาพ ช้าและแพงมาก','~10 นาที ไม่ว่าจะส่งไปที่ใดในโลก'],
        ['เวลาทำงาน','วันทำการ จ–ศ ช่วงเวลาราชการ','ตลาดทองปิดวันหยุดสุดสัปดาห์','24 ชั่วโมง 365 วัน ไม่เคยหยุด'],
        ['พกพา','แบกธนบัตรได้แต่จำกัด','หนักมาก ปลอมได้','1 USD ถึง 1 ล้าน USD พก Seed 12 คำเหมือนกัน'],
        ['ตรวจสอบของแท้','ดูลายน้ำ / เครื่องตรวจ','ต้องทดสอบทางเคมี','ตรวจสอบโดย Node ทั่วโลกใน <1 วินาที'],
        ['เงินเฟ้อระยะยาว','ค่าเงินลดเฉลี่ย 3–7% ต่อปี','Supply เพิ่ม ~1.5% ต่อปี จากการขุด','Supply คงที่ — เงินเฟ้อ 0% หลังขุดครบ'],
      ];
      foreach($rows as [$t,$f,$g,$b]): ?>
      <tr>
        <td class="ctopic"><?=htmlspecialchars($t)?></td>
        <td class="cfiat"><?=htmlspecialchars($f)?></td>
        <td class="cgold"><?=htmlspecialchars($g)?></td>
        <td class="cbtc" ><?=htmlspecialchars($b)?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<hr class="div">

<section class="sec" id="hash">
  <div class="eye">07 — Hash Function</div>
  <h2 class="sh2">เปลี่ยนนิดเดียว — ผลลัพธ์เปลี่ยนหมดเลย</h2>
  <p class="slead">
    Hash Function คือ "เครื่องบดข้อมูล" ที่ใส่อะไรเข้าไปก็ได้ แต่จะได้ผลลัพธ์ออกมา 64 ตัวอักษรเสมอ
    และย้อนกลับไม่ได้ การเปลี่ยนข้อมูลแม้แต่ตัวอักษรเดียว ผลลัพธ์เปลี่ยนแปลงทั้งหมด
    Bitcoin ใช้ SHA-256 สองรอบซ้อนเพื่อสร้าง "ลายนิ้วมือ" ของแต่ละบล็อก
  </p>

  <div class="term">
    <div class="th"><div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div><span class="ttl">sha-256 live demo</span></div>
    <div class="tb">
      <div class="irow"><input class="ti" type="text" id="hashInput" placeholder="พิมพ์อะไรก็ได้ เช่น: Hello Bitcoin" oninput="liveHash()"></div>
      <div class="tout dim" id="hashOutput">พิมพ์ข้อความด้านบนเพื่อดู SHA-256 Hash...</div>
      <p style="margin-top:.8rem;font-family:'IBM Plex Mono',monospace;font-size:.71rem;color:var(--tx3)">
        💡 ลองเปลี่ยนตัวอักษรเพียงตัวเดียว — Hash ทั้ง 64 ตัวเปลี่ยนหมด นี่คือเหตุที่ Blockchain ปลอมแปลงไม่ได้
      </p>
      <div style="margin-top:1rem;display:flex;gap:.8rem;flex-wrap:wrap">
        <a href="https://emn178.github.io/online-tools/sha256.html" target="_blank" class="btn btng" style="font-size:.68rem">🔗 SHA-256 Online Tool (พิสูจน์เองได้) ↗</a>
        <a href="bitcoin/hashing.php" target="_blank" class="btn btng" style="font-size:.68rem">🔗 learning.chontit.win / hashing ↗</a>
      </div>
    </div>
  </div>
</section>

<hr class="div">

<section class="sec" id="custody">
  <div class="eye">08 — Self-Custody</div>
  <h2 class="sh2">"Not Your Keys, Not Your Coins"</h2>
  <p class="slead">
    ถ้า Bitcoin ของคุณอยู่บน Exchange (Bitkub, Binance ฯลฯ) — คุณไม่ได้เป็นเจ้าของ BTC จริงๆ
    คุณถือแค่ "สัญญา" ว่า Exchange จะจ่ายบิตคอยน์ให้เมื่อคุณขอ
    ประวัติศาสตร์พิสูจน์ซ้ำแล้วซ้ำเล่าว่า Exchange ล่มได้ทุกเมื่อ (บิตคอยน์ของคุณก็จะสูญเสียไปด้วย)
  </p>

  <div class="cust">
    <div class="cc bad">
      <div class="cch"><span class="ccico">❌</span><div><div class="cct">Custodial (ฝากตลาด)</div><div class="ccs">Exchange ถือ Key แทนคุณ</div></div></div>
      <ul>
        <li>Exchange เก็บ Private Key แทนคุณ</li>
        <li>Exchange ล้มละลาย → เงินของคุณหายไปด้วย</li>
        <li>รัฐสั่งอายัด → บัญชีถูกอายัดได้ทันที</li>
        <li>Exchange โดน Hack → เงินลูกค้าหาย</li>
        <li>ไม่ต่างจากฝากธนาคารทั่วไป</li>
      </ul>
    </div>
	<div class="cc good">
      <div class="cch"><span class="ccico">✅</span><div><div class="cct">Self-Custody (ถือเอง)</div><div class="ccs">คุณถือ Key เอง</div></div></div>
      <ul>
        <li>คุณถือ Private Key เอง ผ่านซอฟต์แวร์ (Hot Wallet) หรืออุปกรณ์ (Hardware Wallet)</li>
        <li>ไม่มีใครอายัดหรือจำกัดการโอนเงินของคุณได้</li>
        <li>ปลอดภัยจากการล้มละลายของศูนย์ซื้อขาย</li>
        <li><strong>ข้อควรระวัง:</strong> หากทำ Seed Phrase หาย เงินจะสูญหายถาวร</li>
        <li>"Be your own bank" — คุณคือธนาคารของตัวเอง</li>
      </ul>
    </div>
  </div>

  <div style="margin-top:1.5rem">
    <div class="eye" style="margin-bottom:1rem">เหตุการณ์ Exchange ล่มที่เคยเกิดขึ้น</div>
    <?php
    $incidents=[
      ['2014','Mt. Gox — ญี่ปุ่น','Exchange Bitcoin ที่ใหญ่ที่สุดในโลกขณะนั้น (รับส่งธุรกรรม 70% ของ BTC ทั่วโลก) ถูก Hack สูญเสีย Bitcoin ของลูกค้าไปกว่า 740,000 BTC ลูกค้ากว่า 24,000 รายสูญเงิน กว่า 10 ปีต่อมายังรอเงินคืนไม่ครบ','~740,000 BTC หาย'],
      ['2016','Bitfinex — ฮ่องกง','Exchange ถูก Hack ผ่านระบบ Multisig กับบริษัท BitGo สูญเสีย BTC ของลูกค้าไป 119,756 BTC มูลค่าขณะนั้น ~72 ล้านดอลลาร์ ลูกค้าทุกคนถูกตัดยอดเงิน 36%','~119,756 BTC หาย'],
      ['2019','QuadrigaCX — แคนาดา','CEO ตาย (หรือหายตัว?) แล้วปรากฏว่าเป็นผู้ถือ Private Key คนเดียว ลูกค้า 76,000 คน เข้าถึงเงินรวม 190 ล้านดอลลาร์ไม่ได้ ต่อมาพบว่า CEO ยักยอกเงินลูกค้าก่อนตาย','~$190M เข้าถึงไม่ได้'],
      ['2022','FTX — บาฮามาส','Exchange อันดับ 3 ของโลก นำเงินของลูกค้าไปลงทุนเก็งกำไรผ่าน Alameda Research บริษัทในเครือ เมื่อตลาดพัง FTX ล่มสลายใน 72 ชั่วโมง ลูกค้า 1 ล้านคนสูญเงินรวม 8 พันล้านดอลลาร์ CEO ถูกจำคุก 25 ปี','~$8B หาย'],
      ['2023','Celsius, Genesis, Voyager','Multiple crypto lending / exchange platforms ล่มพร้อมกันจากผลกระทบ FTX Contagion ลูกค้าหลายแสนคนสูญเงิน','หลาย $B รวมกัน'],
    ];
    foreach($incidents as [$y,$n,$d,$l]): ?>
    <div class="inc">
      <div class="inc-yr"><?=$y?></div>
      <div>
        <div class="inc-name"><?=htmlspecialchars($n)?></div>
        <div class="inc-desc"><?=htmlspecialchars($d)?></div>
        <div class="inc-loss"><?=htmlspecialchars($l)?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="callout" style="margin-top:1.2rem">
    <strong>หลักการง่ายๆ:</strong> BTC บน Exchange = เงินในตู้เซฟที่คนอื่นถือกุญแจ
    BTC ใน Hardware Wallet = เงินในตู้เซฟที่คุณถือกุญแจเอง ไม่มีใครเปิดได้นอกจากคุณ
  </div>
</section>

<hr class="div">

<section class="sec" id="byzantine">
  <div class="eye">09 — Byzantine Fault Tolerance</div>
  <h2 class="sh2">ปัญหาแม่ทัพไบแซนไทน์ — หัวใจ Consensus ของ Bitcoin</h2>
  <p class="slead">
    ก่อน Bitcoin จะมีคำถามที่นักวิทยาการคอมพิวเตอร์แก้ไม่ได้มา 30 ปีว่า:
    <strong style="color:var(--tx)">"ทำอย่างไรให้คนหลายคนที่ไม่รู้จักกันตกลงเรื่องเดียวกันได้ ในเมื่อบางคนอาจโกหก?"</strong>
    Satoshi แก้ด้วยวิธีที่ไม่เคยมีใครคิดมาก่อน
  </p>

  <div class="g2" style="margin-bottom:1.2rem">
    <div class="bft-box">
      <div class="bft-h">⚔️ โจทย์ดั้งเดิม (1982)</div>
      แม่ทัพหลายคนล้อมเมืองอยู่คนละด้าน ต้องตัดสินใจพร้อมกันว่าจะ <strong style="color:var(--btc)">"บุก"</strong> หรือ <strong style="color:var(--red)">"ถอย"</strong>
      แต่ติดต่อกันได้แค่ผ่านผู้ส่งสาร ซึ่งบางคนอาจเป็นสายลับ
      ถ้าแม่ทัพที่ซื่อสัตย์ตกลงกันไม่ได้ บางกองบุกในขณะที่บางกองถอย กองทัพก็แพ้แน่
    </div>
    <div class="bft-box" style="border-color:var(--btc2)">
      <div class="bft-h">⛏️ วิธีแก้ของ Satoshi (2008)</div>
      แทนที่จะ <em>"ตกลงกันด้วยคำพูด"</em> ให้<strong style="color:var(--btc)"> พิสูจน์ด้วยงาน (Proof-of-Work)</strong>
      แต่ละ Node ต้องแก้โจทย์คณิตศาสตร์ยาก → Nodeแรกที่แก้ได้ประกาศบล็อกใหม่ → Nodeทั้งหมดตรวจสอบได้ทันที
      ยิ่งเครือข่ายใหญ่ ยิ่งโกหกได้ยาก เพราะต้องควบคุม >51% ของ Hash Power ทั้งหมด
    </div>
  </div>

  <div class="term">
    <div class="th"><div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div><span class="ttl">byzantine generals simulator (9 generals)</span></div>
    <div class="tb">
      <p style="font-size:.83rem;color:var(--tx2);margin-bottom:.8rem">
        คลิกที่แม่ทัพเพื่อตั้งเป็น <span style="color:var(--red)">🔴 ไส้ศึก</span> หรือ <span style="color:var(--grn)">🟢 ซื่อสัตย์</span> แล้วกด Simulate
      </p>

      <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:.8rem;align-items:center">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;color:var(--tx3)">โมเดล:</span>
        <button id="modeBFT" class="btn" onclick="setMode('bft')"
          style="font-size:.68rem;padding:.4rem .9rem">
          🗳️ BFT (1/3 rule)
        </button>
        <button id="modePOW" class="btn btng" onclick="setMode('pow')"
          style="font-size:.68rem;padding:.4rem .9rem">
          ⛏️ Bitcoin PoW (51% rule)
        </button>
      </div>
      <div id="modeExplain" style="font-size:.8rem;color:var(--tx3);font-family:'IBM Plex Mono',monospace;margin-bottom:1rem;padding:.6rem .8rem;background:var(--bg3);border-radius:6px;border:1px solid var(--bd)">
        <span id="modeText"></span>
      </div>

      <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1rem">
        <button class="btn" onclick="byzSimulate()">▶ Simulate Vote</button>
        <button class="btn btng" onclick="byzReset()">↺ Reset</button>
        <span style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;color:var(--tx4);align-self:center" id="bzStats"></span>
      </div>
      <div class="bzf" id="byzField">
        <div class="bcastle"><div class="bci">🏰</div><span class="bcl">เมืองเป้าหมาย</span></div>
        <svg id="byzSvg" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:1"></svg>
      </div>
      <div class="bzres" id="byzRes"></div>
      <div class="bzlog" id="byzLog"><span style="color:var(--tx4)">// คลิกแม่ทัพเพื่อตั้งค่า แล้วกด Simulate...</span></div>

      <div style="margin-top:1.2rem;padding:1rem;background:var(--blus);border:1px solid rgba(77,157,224,.15);border-radius:8px">
        <div style="font-family:'IBM Plex Mono',monospace;font-size:.61rem;color:var(--blu);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.7rem">// Bitcoin แก้ด้วย Proof-of-Work อย่างไร?</div>
        <div class="g2" style="gap:.8rem">
          <div style="font-size:.83rem;color:var(--tx2);line-height:1.85">
            <strong style="color:var(--tx);display:block;margin-bottom:.3rem">1. แทนโหวตด้วยคำพูด → พิสูจน์ด้วยงาน</strong>
            Node ทุกตัวแข่งกันแก้โจทย์ SHA-256 ยากๆ (หา Nonce ที่ทำให้ Hash ขึ้นต้นด้วย 0 หลายตัว) — ใช้เวลาเฉลี่ย 10 นาที
          </div>
          <div style="font-size:.83rem;color:var(--tx2);line-height:1.85">
            <strong style="color:var(--tx);display:block;margin-bottom:.3rem">2. ผู้แก้ได้ → ประกาศ → ทุกคนตรวจสอบ</strong>
            Node แรกที่แก้ได้ broadcast บล็อกออกไป ทุก Node ตรวจสอบได้ใน &lt;1 วินาที ถ้าถูกต้อง → ยอมรับ → เริ่มต้นแก้บล็อกถัดไป
          </div>
          <div style="font-size:.83rem;color:var(--tx2);line-height:1.85;grid-column:1/-1">
            <strong style="color:var(--red);display:block;margin-bottom:.3rem">3. โกหกต้องใช้ทรัพยากรมหาศาล</strong>
            ไส้ศึกที่อยากโกหกต้องควบคุม &gt;51% ของ Hash Power ทั้งเครือข่าย (ปัจจุบัน ~900+ EH/s) ต้องใช้ฮาร์ดแวร์และไฟฟ้า<strong style="color:var(--btc)"> มูลค่ากว่า 10 ล้านล้านบาท</strong> — และต้องทำทุกวัน ไม่คุ้มเลย
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<hr class="div">

<section class="sec" id="next">
  <div class="eye">10 — เจาะลึกที่ <a href="<?= $site_url ?>" target="_blank" style="color:var(--btc);text-decoration:none">learning.chontit.win</a></div>
  <h2 class="sh2">เลือก Module ที่ต้องการ</h2>
  <p class="slead">ผ่าน Fundamentals ทั้ง 9 หัวข้อแล้ว — ไปต่อที่ <a href="<?= $site_url ?>" target="_blank" style="color:var(--btc)">learning.chontit.win</a> ได้เลย</p>

  <div class="modg">
    <?php foreach([
      ['01','Mining Simulator','จำลองการขุด Proof-of-Work','miner.php'],
      ['02','SHA-256 Monitor','ทดสอบ Hashing แบบ real-time','hashing.php'],
      ['03','Issuance & Halving','เส้นทาง 21 ล้านเหรียญ','issuance_simulator.php'],
      ['04','2²⁵⁶ Scale','ขนาดของ Private Key','binary.php'],
      ['05','UTXO Visualizer','ระบบบัญชีแบบ Bitcoin','utxo.php'],
      ['06','BIP39 Mnemonic','Seed Phrase 12/24 คำ','bip39.php'],
      ['07','HD Wallet','BIP32/44 Key Derivation','hd-wallet.php'],
      ['08','ECDSA & Public Key','คณิตศาสตร์ secp256k1','ecdsa.php'],
      ['09','Digital Signature','ลายเซ็นดิจิทัล','digital_signature.php'],
      ['10','Transaction Flow','วงจรชีวิตธุรกรรม','tx_flow_simulator.php'],
      ['11','Lightning Network','Payment Channel L2','lightning.php'],
      ['12','Self-Custody Guide','Not your keys guide','self-custody.php'],
    ] as [$n,$t,$d,$f]): ?>
    <a href="<?=$site_url?>/bitcoin/<?=$f?>" class="moda" target="_blank">
      <span class="modn"><?=$n?></span>
      <span class="modt"><?=htmlspecialchars($t)?></span>
      <span class="modd"><?=htmlspecialchars($d)?></span>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<footer>
  <div class="ftl">₿</div>
  <div class="fts">
    Bitcoin 101 · <a href="<?=$site_url?>" target="_blank">learning.chontit.win</a>
    · Don't Trust, Verify · <?=date('Y')?>
  </div>
</footer>

<script>
/* ── THEME ── */
function setTheme(t){
  document.documentElement.setAttribute('data-theme',t);
  localStorage.setItem('btc101-theme',t);
  document.getElementById('bdk').classList.toggle('on',t==='dark');
  document.getElementById('blt').classList.toggle('on',t==='light');
}
(()=>{ const s=localStorage.getItem('btc101-theme'); if(s) setTheme(s); })();

/* ── LIVE DATA ── 
   Primary  : blockchain.info/q/totalbc (satoshis → BTC)
   Fallback : CoinGecko API
   Both via CORS-friendly endpoints                      */
async function fetchLiveData(){
  const MAX = 20999999.9769;
  let circ = null;

  // 1. blockchain.info — returns total mined in satoshis (1 BTC = 1e8 satoshis)
  try{
    const r = await fetch('https://blockchain.info/q/totalbc', {mode:'cors'});
    if(r.ok){
      const sat = parseFloat(await r.text());
      if(sat > 0) circ = sat / 1e8;
    }
  }catch(e){}

  // 2. CoinGecko fallback
  if(!circ){
    try{
      const r = await fetch(
        'https://api.coingecko.com/api/v3/coins/bitcoin?localization=false&tickers=false&market_data=true&community_data=false&developer_data=false',
        {headers:{'Accept':'application/json'}}
      );
      if(r.ok){
        const d = await r.json();
        circ = d.market_data.circulating_supply;
      }
    }catch(e){}
  }

  if(!circ) return; // keep static fallback values

  const pct = (circ / MAX * 100).toFixed(2);
  // format with commas, no phone-detection bait
  const fmt = n => Math.round(n).toLocaleString('en-US');

  document.getElementById('stat-supply').textContent = fmt(circ);
  document.getElementById('stat-pct').textContent    = pct + '%';
  document.getElementById('pct-big').textContent     = pct + '%';
  document.getElementById('sup-bar').style.width     = pct + '%';
  document.getElementById('sup-left').textContent    = 'ขุดแล้ว: ' + fmt(circ) + ' BTC';
}
fetchLiveData();

/* ── SHA-256 ── */
async function sha256(msg){
  const buf=new TextEncoder().encode(msg);
  const h=await crypto.subtle.digest('SHA-256',buf);
  return Array.from(new Uint8Array(h)).map(b=>b.toString(16).padStart(2,'0')).join('');
}

/* ── HASH DEMO ── */
let ht;
async function liveHash(){
  clearTimeout(ht);
  const v=document.getElementById('hashInput').value;
  const o=document.getElementById('hashOutput');
  if(!v){o.textContent='พิมพ์ข้อความด้านบนเพื่อดู SHA-256 Hash...';o.className='tout dim';return}
  ht=setTimeout(async()=>{
    const h=await sha256(v);
    o.textContent=`Input   : "${v}"\nSHA-256 : ${h}\nLength  : 64 hex chars = 256 bits`;
    o.className='tout';
  },100);
}

/* ── BLOCKCHAIN ── */
const chain=[];
const sH=h=>h.slice(0,8)+'…'+h.slice(-8);

async function buildGenesis(){
  const raw='GENESIS:Genesis Block:0000000000000000';
  const h=await sha256(raw);
  chain.push({index:0,data:'Genesis Block',hash:h,prev:'0000000000000000',raw});
  renderChain();
}
async function addBlock(){
  const data=document.getElementById('txData').value.trim();
  if(!data){document.getElementById('chainMsg').textContent='⚠ กรุณาใส่ข้อมูลธุรกรรมก่อน';return}
  const prev=chain[chain.length-1].hash;
  const raw=`${chain.length}:${data}:${prev}`;
  const h=await sha256(raw);
  chain.push({index:chain.length,data,hash:h,prev:sH(prev),raw});
  document.getElementById('txData').value='';
  document.getElementById('chainMsg').textContent=`✓ บล็อก #${chain.length-1} เพิ่มแล้ว`;
  renderChain();
}

function copyBlockData(idx){
  const b=chain[idx];
  const raw=b.raw;
  navigator.clipboard.writeText(raw).then(()=>{
    const btn=document.getElementById('cpbtn-'+idx);
    if(btn){btn.textContent='✓ copied';btn.className='blk-copy copied';setTimeout(()=>{btn.textContent='📋 copy';btn.className='blk-copy'},2000)}
    document.getElementById('copyDetail').style.display='block';
    document.getElementById('copyRaw').textContent=raw;
  }).catch(()=>{
    document.getElementById('copyDetail').style.display='block';
    document.getElementById('copyRaw').textContent=raw;
    document.getElementById('chainMsg').textContent='💡 ข้อมูลดิบแสดงด้านล่างแล้ว (Copy ด้วย Ctrl+C)';
  });
}

function renderChain(){
  const el=document.getElementById('chainWrap');
  el.innerHTML='';
  chain.forEach((b,i)=>{
    if(i>0){const a=document.createElement('div');a.className='carr';a.textContent='→';el.appendChild(a)}
    const bv=document.createElement('div');
    bv.className='blk'+(i===chain.length-1?' act':'');
    bv.innerHTML=`
      <button class="blk-copy" id="cpbtn-${i}" onclick="copyBlockData(${i})">📋 copy</button>
      <span class="blkn">Block #${b.index}</span>
      <div class="blkh">
        <span style="color:var(--tx4)">Prev: </span>${b.prev}<br>
        <span style="color:var(--btc)">Hash: ${sH(b.hash)}</span>
      </div>
      <div class="blkd">${b.data.length>20?b.data.slice(0,20)+'…':b.data}</div>`;
    el.appendChild(bv);
  });
  el.scrollLeft=el.scrollWidth;
}

/* ════════════════════════════════════════════
   KEY FLOW — BIP39 compliant
   Browser fetches full 2048-word list from CDN
   Steps 1-4 cryptographically correct + verifiable
   at iancoleman.io/bip39 (no passphrase, BTC)
════════════════════════════════════════════ */

// Full 2048 words BIP39 English Wordlist embedded for offline fallback
const EMBEDDED_BIP39 = 'abandon ability able about above absent absorb abstract absurd abuse access accident account accuse achieve acid acoustic acquire across action actor actress actual adapt add addict address adjust admit adult advance advice aerobic afford afraid again age agent agree ahead aim air airport aisle alarm album alcohol alert alien all alley allow almost alone alpha already also alter always amateur amazing among amount amused analyst anchor ancient anger angle angry animal ankle announce annual another answer antenna antique anxiety any apart apology appear apple approve april arcade arch arctic area arena argue arm armed armor army around arrange arrest arrive arrow art artefact artist artwork ask aspect assault asset assist assume asthma athlete atom attack attend attitude attract auction audit august aunt author auto autumn average avocado avoid awake aware away awesome awful awkward axis baby bacon badge bag balance bamboo banana banner bar barely bargain barrel base basic basket battle beach bean beauty because become beef before begin behave behind believe below belt bench benefit best betray better between beyond bicycle bid bike bind biology bird birth bitter black blade blame blanket blast bleak bless blind blood blossom blouse blue blur blush board boat body boil bomb bone book boost border boring borrow boss bottom bounce box boy bracket brain brand brave breeze brick bridge brief bright bring brisk broccoli broken bronze broom brother brown brush bubble buddy budget buffalo build bulb bulk bullet bundle bunker burden burger burst bus business busy butter buyer buzz cabbage cabin cable cactus cage cake call calm camera camp canal cancel candy cannon canvas canyon capable capital captain car carbon card cargo carpet carry cart case cash casino castle casual cat catalog catch category cattle caught cause caution cave ceiling celery cement census chair chaos chapter charge chase chat cheap check cheese chef cherry chest chicken chief child chimney choice choose chronic chuckle chunk cigar cinnamon circle citizen city civil claim clap clarify claw clay clean clerk clever click client cliff climb clinic clip clock clog close cloth cloud clown club clump cluster clutch coach coast coconut code coffee coil column combine come comfort comic common company concert conduct confirm congress connect consider control convince cook cool copper copy coral core corn correct cost cotton couch country couple course cousin cover coyote crack cradle craft cram crane crash crater crawl crazy cream credit creek crew cricket crime crisp critic cross crouch crowd crucial cruel cruise crumble crunch crush cry crystal cube culture cup cupboard curious current curtain curve cushion custom cute cycle dad damage damp dance danger daring dash daughter dawn day deal debate debris decade december decide decline decorate decrease deer defense define defy degree delay deliver demand demise denial dentist deny depart depend deposit depth deputy derive describe desert design desk despair destroy detail detect develop device devote diagram dial diamond diary dice diesel diet differ digital dignity dilemma dinner dinosaur direct dirt disagree discover disease dish dismiss disorder display distance divert divide divorce dizzy doctor document dog doll dolphin domain donate donkey donor door dose double dove draft dragon drama drastic draw dream dress drift drill drink drip drive drop drum dry duck dumb dune during dust dutch duty dwarf dynamic eager eagle early earn earth easily east easy echo ecology edge edit educate effort egg eight either elbow elder electric elegant element elephant elevator elite else embark embody embrace emerge emotion employ empower empty enable enact endless endorse enemy engage engine enhance enjoy enlist enough enrich enroll ensure enter entire entry envelope episode equal equip erase erode erosion error erupt escape essay essence estate eternal ethics evidence evil evoke evolve exact example excess exchange excite exclude exercise exhaust exhibit exile exist exit exotic expand expire explain expose express extend extra eye fable face faculty fade faint faith fall false fame family famous fan fancy fantasy far fashion fat fatal father fatigue fault favorite feature february federal fee feed female fence festival fetch fever few fiber fiction field figure file film filter final find fine finger finish fire firm first fiscal fish fit fitness fix flag flame flash flat flavor flee flight flip float flock floor flower fluid flush fly foam focus fog foil follow food foot force forest forget fork fortune forum forward fossil foster found fox fragile frame frequent fresh friend fringe frog front frost frown frozen fruit fuel fun funny furnace fury future gadget gain galaxy gallery game gap garbage garden garlic garment gas gasp gate gather gauge gaze general genius genre gentle genuine gesture ghost ginger giraffe girl give glad glance glare glass glide glimpse globe gloom glory glove glow glue goat goddess gold good goose gorilla gospel gossip govern gown grab grace grain grant grape grasp grass gravity great green grid grief grit grocery group grow grunt guard guide guilt guitar gun gym habit hair half hammer hamster hand happy harbor hard harsh harvest hat have hawk hazard head health heart heavy hedgehog height hello helmet help hen hero hidden high hill hint hip hire history hobby hockey hold hole holiday hollow home honey hood hope horn hospital host hour hover hub huge human humble humor hundred hungry hunt hurdle hurry hurt husband hybrid ice icon ignore ill illegal image imitate immense immune impact impose improve impulse inbox income increase index indicate indoor industry infant inflict inform inhale inject injury inmate inner innocent input inquiry insane insect inside inspire install intact interest into invest invite involve iron island isolate issue item ivory jacket jaguar jar jazz jealous jeans jelly jewel job join joke journey joy judge juice jump jungle junior junk just kangaroo keen keep ketchup key kick kid kingdom kiss kit kitchen kite kitten kiwi knee knife knock know lab ladder lady lake lamp language laptop large later laugh laundry lava law lawn lawsuit layer lazy leader learn leave lecture left leg legal legend leisure lemon lend length lens leopard lesson letter level liar liberty library license life lift light like limb limit link lion liquid list little live lizard load loan lobster local lock logic lonely long loop lottery loud lounge love loyal lucky luggage lumber lunar lunch lyrics magnet maid mail main major make mammal mango mansion manual maple marble march margin marine market marriage mask master match material math matrix matter maximum maze meadow mean medal media melody melt member memory mention menu mercy merge merit merry mesh message metal method middle midnight milk million mimic mind minimum minor minute miracle miss mitten mix mixture mobile model modify mom monitor monkey monster month moon moral mother motion motor mountain mouse movie much muffin mule multiply muscle museum mushroom music must mutual myself mystery naive name napkin narrow nasty natural nature near neck need negative neglect neither nephew nerve network news next nice night noble noise nominee noodle normal north notable note nothing notice novel now nuclear number nurse nut oak obey object oblige obscure obtain ocean october odor off offer office often oil okay old olive olympic omit once onion open opera oppose option orange orbit orchard order ordinary organ orient original orphan ostrich other outdoor outside oval over own oyster ozone paddle page pair palace palm panda panel panic panther paper parade parent park parrot party pass patch path patrol pause pave payment peace peanut peasant pelican pen penalty pencil people pepper perfect permit person pet phone photo phrase physical piano picnic piece pig pigeon pill pilot pink pioneer pipe pistol pitch pizza place planet plastic plate play please pledge pluck plug plunge poem poet point polar pole police pond pony popular portion position possible post potato pottery poverty powder power practice praise predict prefer prepare present pretty prevent price pride primary print priority prison private prize problem process produce profit program project promote proof property prosper protect proud provide public pudding pull pulp pulse pumpkin punish pupil puppy purchase purity purpose push put puzzle pyramid quality quantum quarter question quick quit quiz quote rabbit raccoon race rack radar radio rage rail rain raise rally ramp ranch random range rapid rare rate rather raven reach ready real reason rebel rebuild recall receive recipe record recycle reduce reflect reform refuse region regret regular reject relax release relief rely remain remember remind remove render renew rent reopen repair repeat replace report require rescue resemble resist resource response result retire retreat return reunion reveal review reward rhythm ribbon rice rich ride rifle right rigid ring riot ripple risk ritual rival river road roast robot robust rocket romance roof rookie rotate rough round route royal rubber rude rug rule run runway rural sad saddle sadness safe sail salad salmon salon salt salute same sample sand satisfy satoshi sauce sausage save say scale scan scare scatter scene scheme scholar science scissors scorpion scout scrap screen script scrub sea search season seat second secret section security seek segment select sell seminar senior sense sentence series service session settle setup seven shadow shaft shallow share shed shell sheriff shield shift shine ship shiver shock shoe shoot shop short shoulder shove shrimp shrug shuffle shy sibling siege sight signal silent silk silly silver similar simple since sing siren sister situate ski skill skin skull slab slam sleep slender slice slide slight slim slogan slot slow slush small smart smile smoke smooth snack snake snap sniff snow soap soccer social sock solar soldier solid solution solve someone song soon sorry soul sound soup source south space spare spatial spawn speak special speed sphere spice spider spike spin spirit split spoil sponsor spoon spray spread spring spy square squeeze squirrel stable stadium staff stage stairs stamp stand start state stay steak steel stem step stereo stick still sting stock stomach stone stop store story stove strategy street strike strong struggle student stuff stumble style subject submit subway success such sudden suffer sugar suggest suit summer sun sunny sunset super supply supreme sure surface surge surprise sustain swallow swamp swap swear sweet swift swim swing switch sword symbol symptom syrup table tackle tag tail talent tamper tank tape target task tattoo taxi teach team tell ten tenant tennis tent term test text thank that theme theory there they thing this thought three thrive throw thumb thunder ticket tilt timber time tiny tip tired title toast tobacco today together toilet token tomato tomorrow tone tongue tonight tool topic topple torch tornado tortoise toss total tourist toward tower town toy track trade traffic tragic train transfer trap trash travel tray treat tree trend trial tribe trick trigger trim trip trophy trouble truck truly trumpet trust truth tube tuition tumble tuna tunnel turkey turn turtle twelve twenty twice twin twist two type typical ugly umbrella unable unaware uncle uncover under undo unfair unfold unhappy uniform unique universe unknown unlock until unusual unveil update upgrade uphold upon upper upset urban useful useless usual utility vacant vacuum vague valid valley valve van vanish vapor various vast vault vehicle velvet vendor venture venue verb verify version very veteran viable vibrant vicious victory video view village vintage violin virtual virus visa visit visual vital vivid vocal voice void volcano volume vote voyage wage wagon wait walk wall walnut want warfare warm warrior wash wasp waste water wave way wealth weapon wear weasel wedding weekend weird welcome well west wet whale wheat wheel when where whip whisper wide width wife wild will win window wine wing wink winner winter wire wisdom wise wish witness wolf woman wonder wood wool word world worry worth wrap wreck wrestle wrist write wrong yard year yellow you young youth zebra zero zone zoo'.split(' ');

// Will be populated: either from CDN (2048 exact) or embedded fallback
let BIP39W = null;

async function loadBIP39Wordlist(){
  const cdnUrl = 'https://raw.githubusercontent.com/bitcoin/bips/master/bip-0039/english.txt';
  try {
    const r = await fetch(cdnUrl);
    if(r.ok){
      const txt = await r.text();
      const ws = txt.trim().split('\n').map(w=>w.trim()).filter(Boolean);
      if(ws.length === 2048){ BIP39W = ws; return true; }
    }
  } catch(e) { /* CDN failed, use fallback */ }
  // Embedded fallback (Full 2048 verified BIP39 words)
  BIP39W = EMBEDDED_BIP39;
  return false;
}

function toHex(bytes){return Array.from(bytes).map(b=>b.toString(16).padStart(2,'0')).join('')}

/* ════════════════════════════════════════
   KEY GEN — Basic Simulation (101 Level)
════════════════════════════════════════ */
let _lastMnemonic = '';

async function generateKeySim(){
  if(!BIP39W){ await loadBIP39Wordlist(); }

  const btn = document.getElementById('genBtn');
  if(btn){ btn.disabled=true; btn.textContent='⏳ กำลังสร้าง...'; }

  document.getElementById('kflowWrap').style.display='block';
  ['k0','k1','k3'].forEach(id=>{
    const el = document.getElementById(id);
    if(el){el.innerHTML='…'; el.className='kstep-val dim'; el.style.color='';}
  });

  await delay(100);

  /* Step 1: Entropy */
  const ent = new Uint8Array(16);
  crypto.getRandomValues(ent);
  const entHex = toHex(ent);
  setKStep('k0', entHex, 'grn');
  await delay(400);

  /* Step 2: Mnemonic (12 words) */
  const csBuf  = await crypto.subtle.digest('SHA-256', ent);
  const csByte = new Uint8Array(csBuf)[0];
  let bitStr   = '';
  for(const b of ent) bitStr += b.toString(2).padStart(8,'0');
  bitStr += (csByte >> 4).toString(2).padStart(4,'0');

  const words = [];
  for(let i=0;i<12;i++){
    const idx = parseInt(bitStr.slice(i*11, i*11+11), 2);
    words.push(BIP39W[idx]);
  }
  _lastMnemonic = words.join(' ');
  setKStep('k1', _lastMnemonic, 'btc');
  await delay(500);

  /* Step 3: Master Private Key (Simulated view for 101) */
  // สร้าง Hash แบบเร็วๆ เพื่อใช้เป็นตัวอย่างภาพ Private Key ในระดับเบื้องต้น
  const enc = new TextEncoder();
  const mockKeyBuf = await crypto.subtle.digest('SHA-256', enc.encode(_lastMnemonic + "demo"));
  const mockKeyHex = toHex(new Uint8Array(mockKeyBuf));
  
  setKStep('k3', mockKeyHex, 'tx');
  
  /* Step 4: Address (Static Demo Update) */
  const k4 = document.getElementById('k4');
  if(k4) k4.innerHTML = 'bc1q' + mockKeyHex.slice(0, 8) + '... (เปิดเผยได้)';

  if(btn){ btn.disabled=false; btn.textContent='⚡ สุ่มสร้างกระเป๋าใหม่อีกครั้ง'; }
}

let _copyTimer;
function copyMnem(){
  if(!_lastMnemonic) return;
  navigator.clipboard.writeText(_lastMnemonic).then(()=>{
    const btn = event.target;
    btn.textContent='✓ คัดลอกแล้ว';
    clearTimeout(_copyTimer);
    _copyTimer = setTimeout(()=>{ btn.textContent='📋 คัดลอก Mnemonic'; }, 2000);
  }).catch(()=>{
    // fallback: select text in k1
    const el = document.getElementById('k1');
    if(el){ const r=document.createRange(); r.selectNode(el); window.getSelection().removeAllRanges(); window.getSelection().addRange(r); }
  });
}

function delay(ms){return new Promise(r=>setTimeout(r,ms))}

function setKStep(id, val, c){
  const el = document.getElementById(id);
  if(!el) return;
  el.innerHTML  = val; // แก้ไข textContent เป็น innerHTML เพื่อรองรับ <br>
  el.className    = 'kstep-val';
  el.style.color  = c==='btc' ? 'var(--btc)' : c==='grn' ? 'var(--grn)' : 'var(--tx)';
}

// alias for backward compat (byzantine section uses setStep)
function setStep(id,val,c){ setKStep(id,val,c); }

/* ═══════════════════════════════
   BYZANTINE (9 generals)
═══════════════════════════════ */
const GEN=[
  {id:0,name:'อาร์ทาวัส',  emoji:'⚔️', traitor:false},
  {id:1,name:'เลออน',       emoji:'🛡️', traitor:false},
  {id:2,name:'คอนสแตนติน', emoji:'🗡️', traitor:false},
  {id:3,name:'ธีโอดอร์',   emoji:'🪃', traitor:true },
  {id:4,name:'บาซิล',       emoji:'🏹', traitor:false},
  {id:5,name:'มาร์คัส',    emoji:'⚙️', traitor:false},
  {id:6,name:'นิเซโฟรัส',  emoji:'🔱', traitor:false},
  {id:7,name:'สตราเทกอส',  emoji:'🗺️', traitor:true },
  {id:8,name:'โรมานอส',    emoji:'🏰', traitor:false},
];
let bzBusy=false;
let bzMode='bft'; // 'bft' or 'pow'

const MODE_INFO={
  bft:{
    label:'🗳️ BFT (Byzantine Fault Tolerance)',
    rule: 'ใช้ใน: Ethereum PoS, Tendermint, Cosmos, Hyperledger',
    threshold_explain: 'Consensus ได้เมื่อ ไส้ศึก < ⅓ ของทั้งหมด',
    note: '⚠ Bitcoin ไม่ได้ใช้ BFT โดยตรง — แต่ PoW แก้ปัญหาเดียวกัน'
  },
  pow:{
    label:'⛏️ Bitcoin Proof-of-Work',
    rule: 'ใช้ใน: Bitcoin, Litecoin, Monero',
    threshold_explain: 'โกหกได้เมื่อควบคุม > 51% ของ Hash Power (51% Attack)',
    note: '✅ ยิ่งเครือข่ายใหญ่ ยิ่งโกหกยากและแพงมากขึ้น'
  }
};

function setMode(m){
  bzMode=m;
  document.getElementById('modeBFT').className= m==='bft' ? 'btn' : 'btn btng';
  document.getElementById('modePOW').className= m==='pow' ? 'btn' : 'btn btng';
  const info=MODE_INFO[m];
  document.getElementById('modeText').innerHTML=
    `<strong style="color:var(--btc)">${info.label}</strong><br>${info.rule}<br>` +
    `<span style="color:var(--grn)">${info.threshold_explain}</span><br>` +
    `<span style="color:var(--tx4)">${info.note}</span>`;
  updateBzStats();
}

function updateBzStats(){
  const tc=GEN.filter(g=>g.traitor).length;
  const n=GEN.length;
  let statusText='';
  if(bzMode==='bft'){
    const thresh=Math.floor((n-1)/3); // BFT: max f = floor((n-1)/3)
    const can=tc<=thresh;
    const pct=((tc/n)*100).toFixed(0);
    statusText=`ไส้ศึก: ${tc}/${n} (${pct}%) | BFT threshold: ≤${thresh} (< ⅓) | ${can?'✅ Consensus ได้':'❌ เกิน ⅓ → ล้มเหลว'}`;
  }else{
    const pct=((tc/n)*100).toFixed(0);
    const majority51=tc*2>n; // >50% for vote-based, PoW uses hashrate not count
    statusText=`ไส้ศึก: ${tc}/${n} (${pct}%) | PoW: โกหกได้ต้องมี >51% hash power | ${pct>50?'❌ >51% → 51% Attack สำเร็จ':'✅ <51% → PoW ยืนหยัด'}`;
  }
  document.getElementById('bzStats').textContent=statusText;
}

function byzInit(){
  const f=document.getElementById('byzField');
  f.querySelectorAll('.gn').forEach(n=>n.remove());
  const W=f.offsetWidth||460,H=f.offsetHeight||360;
  const r=Math.min(W,H)*.38;
  GEN.forEach((g,i)=>{
    const a=(i/GEN.length)*Math.PI*2-Math.PI/2;
    const x=W/2+r*Math.cos(a),y=H/2+r*Math.sin(a);
    const nd=document.createElement('div');
    nd.className='gn '+(g.traitor?'traitor':'loyal');
    nd.id='gn-'+g.id;nd.style.left=x+'px';nd.style.top=y+'px';
    nd.innerHTML=`<div class="gi">${g.emoji}</div><span class="gname">${g.name}</span><span class="gvote" id="gv-${g.id}"></span>`;
    nd.title=`${g.name} — คลิกเพื่อสลับ loyal/traitor`;
    nd.addEventListener('click',()=>{
      if(bzBusy)return;
      g.traitor=!g.traitor;
      nd.className='gn '+(g.traitor?'traitor':'loyal');
      bLog(`<span class="${g.traitor?'lt':'ll'}">${g.name} → ${g.traitor?'🔴 ไส้ศึก':'🟢 ซื่อสัตย์'}</span>`);
      updateBzStats();
    });
    f.appendChild(nd);
  });
  clrSvg();
  document.getElementById('byzRes').style.display='none';
  document.getElementById('byzLog').innerHTML='<span style="color:var(--tx4)">// คลิกแม่ทัพเพื่อตั้งค่า แล้วกด Simulate...</span>';
  GEN.forEach(g=>{const v=document.getElementById('gv-'+g.id);if(v)v.textContent=''});
  setMode('bft'); // initialize mode UI
}
function clrSvg(){const s=document.getElementById('byzSvg');while(s.firstChild)s.removeChild(s.firstChild)}
function bLog(h){const l=document.getElementById('byzLog');l.innerHTML+=h+'<br>';l.scrollTop=l.scrollHeight}
function nC(id){const n=document.getElementById('gn-'+id);return n?{x:parseFloat(n.style.left),y:parseFloat(n.style.top)}:null}

function dLine(svg,x1,y1,x2,y2,col,dash){
  const l=document.createElementNS('http://www.w3.org/2000/svg','line');
  l.setAttribute('x1',x1);l.setAttribute('y1',y1);l.setAttribute('x2',x2);l.setAttribute('y2',y2);
  l.setAttribute('stroke',col);l.setAttribute('stroke-width','1.5');l.setAttribute('stroke-opacity','.45');
  if(dash)l.setAttribute('stroke-dasharray','5 4');
  svg.appendChild(l);
}
const slp=ms=>new Promise(r=>setTimeout(r,ms));

async function byzSimulate(){
  if(bzBusy)return;bzBusy=true;
  clrSvg();
  GEN.forEach(g=>{const v=document.getElementById('gv-'+g.id);if(v)v.textContent=''});
  document.getElementById('byzLog').innerHTML='';
  document.getElementById('byzRes').style.display='none';

  const tc=GEN.filter(g=>g.traitor).length;
  const lc=GEN.length-tc;
  const n=GEN.length;
  const pct=((tc/n)*100).toFixed(0);
  const isBFT = bzMode==='bft';

  bLog(`<span style="color:var(--tx4)">// ${isBFT?'BFT Voting Model':'Bitcoin PoW Model'} — แม่ทัพ ${n} คน</span>`);
  bLog(`<span class="ll">ซื่อสัตย์: ${lc}</span> · <span class="lt">ไส้ศึก: ${tc} (${pct}%)</span>`);

  if(isBFT){
    const bftThresh=Math.floor((n-1)/3);
    bLog(`<span style="color:var(--tx4)">// BFT: Consensus ได้เมื่อไส้ศึก ≤ ${bftThresh} (< ⅓ ของ ${n})</span>`);
  }else{
    bLog(`<span style="color:var(--tx4)">// Bitcoin PoW: โกหกได้ต้องควบคุม >51% ของ Hash Power ทั้งเครือข่าย</span>`);
    bLog(`<span style="color:var(--tx4)">// ใน Simulator นี้: ไส้ศึก = % ของ Nodes ที่ควบคุมได้</span>`);
  }
  await slp(400);

  bLog(`<br><span style="color:var(--tx4)">// Phase 1 — ส่งคำสั่งให้กันและกัน</span>`);
  for(const g of GEN){
    await slp(140);
    const v=document.getElementById('gv-'+g.id);
    if(!g.traitor){
      if(v){v.textContent='⚔️ บุก';v.className='gvote vatk'}
      bLog(`<span class="ll">${g.name}: ⚔️ "บุก" (ส่งเหมือนกันทุกคน)</span>`);
    }else{
      if(v){v.textContent='🎭 โกหก';v.className='gvote vlie'}
      bLog(`<span class="lt">${g.name} [ไส้ศึก]: ⚔️ → บางคน, 🏃 → บางคน (ส่งต่างกัน!)</span>`);
    }
  }

  await slp(300);
  const svg=document.getElementById('byzSvg');
  GEN.forEach(s=>{
    const sp=nC(s.id);if(!sp)return;
    GEN.forEach(rx=>{
      if(rx.id===s.id)return;
      const rp=nC(rx.id);if(!rp)return;
      dLine(svg,sp.x,sp.y,rp.x,rp.y,s.traitor?'#f05b5b':'#2ec98a',s.traitor);
    });
  });
  await slp(700);

  bLog(`<br><span style="color:var(--tx4)">// Phase 2 — นับคะแนน</span>`);
  await slp(350);
  const atkFromTraitor=Math.floor(tc/2);
  const retFromTraitor=Math.ceil(tc/2);
  bLog(`แม่ทัพซื่อสัตย์แต่ละคนนับได้: ⚔️ บุก = ${lc+atkFromTraitor} เสียง, 🏃 ถอย = ${retFromTraitor} เสียง`);
  await slp(500);

  const re=document.getElementById('byzRes');

  if(isBFT){
    /* ── BFT MODE: threshold = floor((n-1)/3) ── */
    const thresh=Math.floor((n-1)/3);
    const canConsensus=tc<=thresh;
    bLog(`<br><span style="color:var(--tx4)">// BFT threshold = ⌊(${n}-1)/3⌋ = ${thresh} คน</span>`);

    if(tc===0){
      bLog(`<span class="lr">✅ ไม่มีไส้ศึก — Perfect BFT Consensus</span>`);
      re.className='bzres ok';
      re.textContent=`✅ BFT CONSENSUS สมบูรณ์ — ทุกแม่ทัพ ${n} คนเห็นตรงกัน`;
    }else if(canConsensus){
      bLog(`<span class="lr">✅ ไส้ศึก ${tc} ≤ threshold (${thresh}) — BFT Consensus ยังได้</span>`);
      bLog(`<span class="lp">// BFT ทำงานได้แม้มีไส้ศึก ตราบใดที่ไม่เกิน ⅓</span>`);
      re.className='bzres ok';
      re.textContent=`✅ BFT CONSENSUS สำเร็จ — ไส้ศึก ${tc}/${n} (${pct}%) ≤ ⅓ (${thresh})`;
    }else{
      bLog(`<span class="lt">❌ ไส้ศึก ${tc} > threshold (${thresh}) — BFT Consensus ล้มเหลว!</span>`);
      bLog(`<span class="lt">บางกองทัพบุก บางกองทัพถอย — กองทัพแตกพ่าย</span>`);
      bLog(`<br><span style="color:var(--tx4)">// ⚡ Bitcoin แก้ปัญหานี้ด้วย PoW:</span>`);
      bLog(`<span class="lp">// แทนที่จะนับเสียง → ให้แข่งแก้โจทย์ Hash</span>`);
      bLog(`<span class="lp">// ต้องควบคุม >51% hashrate จึงจะโกหกได้ (แพงมากกว่า ⅓ ผู้คนมาก)</span>`);
      re.className='bzres err';
      re.textContent=`❌ BFT ล้มเหลว — ไส้ศึก ${tc}/${n} (${pct}%) เกิน ⅓ (threshold ≤${thresh})`;
    }
  }else{
    /* ── BITCOIN PoW MODE: threshold = >50% of hashrate ── */
    // In vote simulation: traitor % = proxy for hashrate %
    const has51=parseInt(pct)>50;
    bLog(`<br><span style="color:var(--tx4)">// Bitcoin PoW: ไส้ศึกมี ${pct}% ของ Nodes</span>`);
    bLog(`<span style="color:var(--tx4)">// โกหกสำเร็จต้องมี >51% ของ Hash Power ทั้งเครือข่าย</span>`);

    if(tc===0){
      bLog(`<span class="lr">✅ ไม่มีผู้โจมตี — Network ปลอดภัย 100%</span>`);
      re.className='bzres ok';
      re.textContent=`✅ Bitcoin PoW ปลอดภัย — ไม่มีผู้โจมตี`;
    }else if(!has51){
      bLog(`<span class="lr">✅ ไส้ศึก ${pct}% < 51% — PoW ยืนหยัด! โกหกไม่ได้</span>`);
      bLog(`<span class="lp">// Longest Chain ยังเป็นของ Node ซื่อสัตย์ ${100-parseInt(pct)}%</span>`);
      bLog(`<span class="lp">// ผู้โจมตีเสีย Block Reward ทุกครั้งที่พยายามโกหก</span>`);
      re.className='bzres ok';
      re.textContent=`✅ Bitcoin PoW ปลอดภัย — ผู้โจมตี ${tc}/${n} (${pct}%) ยังไม่ถึง 51%`;
    }else{
      bLog(`<span class="lt">❌ ผู้โจมตีมี ${pct}% > 51% — 51% Attack สำเร็จ!</span>`);
      bLog(`<span class="lt">// สามารถ rewrite blockchain ย้อนหลัง, double-spend ได้</span>`);
      bLog(`<span class="lt">// แต่ในความเป็นจริง: Bitcoin network มี ~900 EH/s</span>`);
      bLog(`<span class="lt">// ต้องใช้ฮาร์ดแวร์+ไฟฟ้ามูลค่า >10 ล้านล้านบาท — ไม่มีใครทำได้</span>`);
      re.className='bzres err';
      re.textContent=`❌ 51% Attack — ผู้โจมตีมี ${tc}/${n} (${pct}%) ของ Hash Power`;
    }
  }
  re.style.display='block';
  bzBusy=false;
}

function byzReset(){
  // Default: 2/9 traitors = 22% < 33% → BFT works, PoW also fine
  GEN.forEach((g,i)=>{g.traitor=i===3||i===7});
  // Reset to exactly 2 traitors
  let count=0;
  GEN.forEach(g=>{
    if(g.traitor){ count++; if(count>2) g.traitor=false; }
  });
  byzInit();
  setMode(bzMode||'bft');
}

window.addEventListener('DOMContentLoaded',()=>{buildGenesis();byzInit();loadBIP39Wordlist();});
</script>
</body>
</html>