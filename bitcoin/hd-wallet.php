<?php
// Filename: hd_wallet.php
// Theme: Learning Chontit (Dark Neon) — Enhanced Edition
// Focus: Real Cryptographic Calculations + Full Thai Educational Explanations
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>HD Wallet Simulator (Real Math) | Learning Chontit</title>
    
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-blue: #0dcaf0;
            --neon-purple: #b026ff;
            --neon-red: #ff3333;
            --neon-yellow: #FFD700;
            --text-gray: #9ca3af;
            --deep-dive-bg: #0a192f;
            --concept-bg: #0d1f12;
            --analogy-bg: #1a0d00;
            --warning-bg: #1a0000;
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(247, 147, 26, 0.08) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(13, 202, 240, 0.05) 0%, transparent 25%);
            background-attachment: fixed;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
        }

        .brand-font { font-family: 'Orbitron', sans-serif; letter-spacing: 1px; }
        .font-mono { font-family: 'JetBrains Mono', monospace; word-break: break-all; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: var(--neon-blue); border-radius: 4px; }

        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-neon {
            background: linear-gradient(45deg, #0dcaf0, #0056b3);
            color: #fff;
            font-weight: bold;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-neon:hover:not(:disabled) { box-shadow: 0 0 20px var(--neon-blue); transform: scale(1.02); color: #fff; }
        .btn-neon:disabled { background: #333; color: #666; cursor: not-allowed; }

        .btn-back { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); color: #aaa; transition: 0.3s; }
        .btn-back:hover { background: rgba(255, 255, 255, 0.1); color: #fff; border-color: #fff; }

        .form-select, .form-control { background: rgba(0, 0, 0, 0.6); border: 1px solid #333; color: #fff; font-size: 16px; }
        .form-select:focus, .form-control:focus { background: rgba(0, 0, 0, 0.8); border-color: var(--neon-blue); box-shadow: 0 0 10px rgba(13, 202, 240, 0.2); color: #fff; }
        
        ::placeholder { color: #888 !important; opacity: 1 !important; }

        .step-section { display: none; opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .step-section.active { display: block; opacity: 1; transform: translateY(0); }

        .data-block { background: #000; border: 1px solid #333; border-radius: 8px; padding: 12px; margin-bottom: 15px; position: relative; transition: all 0.2s ease; }
        .data-block.flash { box-shadow: 0 0 15px var(--neon-blue); border-color: var(--neon-blue); }
        .data-label { font-size: 0.7rem; color: var(--text-gray); position: absolute; top: -10px; left: 10px; background: #000; padding: 0 5px; border: 1px solid #333; border-radius: 4px; }

        /* Deep Dive Box */
        .deep-dive-box { background-color: var(--deep-dive-bg); border-left: 4px solid var(--neon-blue); border-radius: 0 8px 8px 0; padding: 15px; margin-top: 15px; font-size: 0.9rem; color: #c9d1d9; }
        .deep-dive-box h6 { color: var(--neon-blue); font-weight: bold; margin-bottom: 10px; font-family: 'Prompt', sans-serif; }
        .deep-dive-box ul { padding-left: 20px; }
        .deep-dive-box li { margin-bottom: 8px; }
        .algo-highlight { color: #58a6ff; font-family: 'JetBrains Mono', monospace; background: rgba(88, 166, 255, 0.1); padding: 2px 6px; border-radius: 4px; }
        
        /* Trace Box */
        .trace-box { background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; margin-top: 12px; font-size: 0.85rem; }

        /* DIY Verify Box */
        .diy-box { background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 10px; font-size: 0.8rem; margin-top: 10px; border-left: 4px solid var(--neon-green); overflow-x: auto; }
        .diy-code { color: #00ff41; font-family: 'JetBrains Mono', monospace; white-space: pre; }
        
        /* Combination Lock UI */
        .path-col { min-width: 45px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .path-btn { cursor: pointer; padding: 8px 15px; color: #6c757d; transition: 0.2s; user-select: none; -webkit-tap-highlight-color: transparent; }
        .path-btn:hover, .path-btn:active { color: var(--neon-blue); text-shadow: 0 0 8px var(--neon-blue); transform: scale(1.1); }
        .path-btn.highlight { color: var(--neon-purple); text-shadow: 0 0 8px var(--neon-purple); }

        /* ===== NEW: Concept Summary Box ===== */
        .concept-box {
            background: var(--concept-bg);
            border: 1px solid rgba(0, 255, 65, 0.25);
            border-left: 4px solid var(--neon-green);
            border-radius: 0 8px 8px 0;
            padding: 14px 16px;
            margin-bottom: 16px;
            font-size: 0.88rem;
            color: #c9d1d9;
        }
        .concept-box .concept-title {
            color: var(--neon-green);
            font-weight: 700;
            font-size: 0.82rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .concept-box p { margin-bottom: 6px; line-height: 1.65; }

        /* ===== NEW: Analogy Box ===== */
        .analogy-box {
            background: var(--analogy-bg);
            border: 1px solid rgba(247, 147, 26, 0.3);
            border-left: 4px solid var(--neon-btc);
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 14px;
            font-size: 0.85rem;
            color: #e0c9a0;
        }
        .analogy-box .analogy-title {
            color: var(--neon-btc);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* ===== NEW: Security Warning Box ===== */
        .security-box {
            background: var(--warning-bg);
            border: 1px solid rgba(255, 51, 51, 0.3);
            border-left: 4px solid var(--neon-red);
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-bottom: 14px;
            font-size: 0.85rem;
            color: #ffbaba;
        }
        .security-box .security-title {
            color: var(--neon-red);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* ===== NEW: Overview / Intro Panel ===== */
        .overview-panel {
            background: linear-gradient(135deg, rgba(13,202,240,0.05), rgba(176,38,255,0.05));
            border: 1px solid rgba(13,202,240,0.2);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 20px;
        }
        .overview-panel h5 { color: var(--neon-blue); font-family: 'Orbitron', sans-serif; font-size: 0.9rem; }

        /* ===== NEW: Step Progress Breadcrumb ===== */
        .step-breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            color: #555;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .step-breadcrumb .crumb { padding: 2px 8px; border-radius: 20px; background: #111; border: 1px solid #333; }
        .step-breadcrumb .crumb.active { color: var(--neon-blue); border-color: var(--neon-blue); background: rgba(13,202,240,0.08); }
        .step-breadcrumb .sep { color: #333; }

        /* ===== NEW: Key-Value Explain Row ===== */
        .kv-row { display: flex; gap: 8px; align-items: flex-start; margin-bottom: 6px; font-size: 0.83rem; }
        .kv-key { color: var(--neon-yellow); font-family: 'JetBrains Mono', monospace; min-width: 100px; flex-shrink: 0; font-size: 0.78rem; }
        .kv-val { color: #c9d1d9; line-height: 1.55; }

        /* ===== NEW: Address Type Cards ===== */
        .addr-card { background: #0d0d0d; border: 1px solid #222; border-radius: 8px; padding: 10px 12px; margin-bottom: 8px; }
        .addr-card .addr-type { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; }

        /* ===== NEW: Pill Badges ===== */
        .bip-badge { display: inline-block; background: rgba(176,38,255,0.15); border: 1px solid rgba(176,38,255,0.4); color: #cc7aff; border-radius: 20px; font-size: 0.7rem; padding: 1px 8px; font-family: 'JetBrains Mono', monospace; margin-right: 3px; }
        .status-badge { display: inline-block; background: rgba(0,255,65,0.1); border: 1px solid rgba(0,255,65,0.3); color: var(--neon-green); border-radius: 20px; font-size: 0.68rem; padding: 1px 8px; }

        /* ===== NEW: Divider with Label ===== */
        .labeled-divider { display: flex; align-items: center; gap: 10px; margin: 14px 0; color: #444; font-size: 0.75rem; }
        .labeled-divider::before, .labeled-divider::after { content: ''; flex: 1; border-top: 1px solid #222; }

        /* Setup panel option descriptions */
        .option-hint { font-size: 0.75rem; color: var(--text-gray); margin-top: 4px; padding: 6px 10px; background: rgba(255,255,255,0.03); border-radius: 6px; border-left: 3px solid #333; }
    </style>
</head>
<body class="d-flex flex-column">

    <header class="w-100 p-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center bg-black bg-opacity-90 sticky-top z-3">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <i class="fa-solid fa-calculator fa-2x text-info"></i>
            <div>
                <h1 class="h5 mb-0 fw-bold brand-font" style="background: linear-gradient(to right, #0dcaf0, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    VERIFIABLE HD WALLET
                </h1>
                <p class="small text-secondary mb-0 d-none d-sm-block">Real Math Simulation (BIP-32/39/44/84/86)</p>
            </div>
        </div>
        <a href="/" class="btn btn-sm btn-outline-secondary text-light rounded-pill px-3">
            <i class="fa-solid fa-house"></i> <span class="d-none d-md-inline ms-1">Home</span>
        </a>
    </header>

    <div class="container py-4 flex-grow-1 d-flex flex-column">
        
        <!-- ===== SETUP PANEL ===== -->
        <div class="row justify-content-center my-auto" id="setupPanel">
            <div class="col-12 col-md-10 col-lg-8">

                <!-- NEW: Overview Intro -->
                <div class="overview-panel mb-4">
                    <h5 class="mb-2"><i class="fa-solid fa-circle-info me-2"></i>HD Wallet คืออะไร?</h5>
                    <p class="small mb-2" style="color: #a0b4c8; line-height: 1.7;">
                        <strong style="color:#e0e0e0;">HD Wallet (Hierarchical Deterministic Wallet)</strong> คือระบบกระเป๋าบิตคอยน์ที่ใช้ <strong style="color:#0dcaf0;">คำลับ (Mnemonic/Seed Phrase) เพียงชุดเดียว</strong> เพื่อสร้าง <strong style="color:#00ff41;">ที่อยู่บิตคอยน์ได้ไม่จำกัดจำนวน</strong> — ทั้งหมดนี้เชื่อมโยงกันด้วยสมการคณิตศาสตร์ที่พิสูจน์ได้จริง
                    </p>
                    <div class="row g-2 mt-1">
                        <div class="col-4 text-center">
                            <div style="color: #00ff41; font-size: 1.4rem;"><i class="fa-solid fa-seedling"></i></div>
                            <div style="font-size: 0.7rem; color: #666;">Seed Phrase</div>
                            <div style="font-size: 0.65rem; color: #444;">จุดเริ่มต้นทุกอย่าง</div>
                        </div>
                        <div class="col-4 text-center" style="border-left: 1px solid #222; border-right: 1px solid #222;">
                            <div style="color: #F7931A; font-size: 1.4rem;"><i class="fa-solid fa-key"></i></div>
                            <div style="font-size: 0.7rem; color: #666;">Master Key</div>
                            <div style="font-size: 0.65rem; color: #444;">กุญแจหลัก</div>
                        </div>
                        <div class="col-4 text-center">
                            <div style="color: #0dcaf0; font-size: 1.4rem;"><i class="fa-solid fa-sitemap"></i></div>
                            <div style="font-size: 0.7rem; color: #666;">Child Addresses</div>
                            <div style="font-size: 0.65rem; color: #444;">สาขาไม่จำกัด</div>
                        </div>
                    </div>
                </div>

                <div class="neon-box p-3 p-md-4">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-microchip fa-3x text-secondary mb-2"></i>
                        <h2 class="brand-font text-white h4">Real Calculation Setup</h2>
                        <p class="text-secondary small mb-0">ระบบใช้การคำนวณจริงตามมาตรฐานของ Bitcoin Core</p>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Seed Length -->
                        <div class="col-12 col-md-6">
                            <label class="text-info small mb-1 fw-bold"><i class="fa-solid fa-list-ol"></i> SEED PHRASE LENGTH</label>
                            <select id="wordCount" class="form-select font-mono" onchange="onWordCountChange()">
                                <option value="12" data-entropy="128">12 Words</option>
                                <option value="15" data-entropy="160">15 Words</option>
                                <option value="18" data-entropy="192">18 Words</option>
                                <option value="21" data-entropy="224">21 Words</option>
                                <option value="24" data-entropy="256">24 Words</option>
                            </select>
                            <div class="mt-2 text-secondary small bg-dark p-2 rounded border border-secondary border-opacity-25 d-flex justify-content-between">
                                <span><i class="fa-solid fa-shield-halved text-success me-1"></i> ขนาด Entropy:</span>
                                <span id="entropyDisplay" class="font-mono text-success fw-bold">128-bit</span>
                            </div>
                            <!-- NEW: Hint -->
                            <div class="option-hint">
                                <i class="fa-solid fa-circle-info text-info me-1"></i>
                                จำนวนคำมากขึ้น = ความลับปลอดภัยขึ้น แต่จำยากขึ้น ส่วนใหญ่ใช้ <strong>12 หรือ 24 คำ</strong>
                            </div>
                        </div>

                        <!-- Address Type -->
                        <div class="col-12 col-md-6">
                            <label class="text-warning small mb-1 fw-bold"><i class="fa-solid fa-signs-post"></i> ADDRESS TYPE (BIP Standard)</label>
                            <select id="addressType" class="form-select font-mono">
                                <option value="legacy">Legacy (BIP-44) — เริ่มด้วย 1</option>
                                <option value="segwit">Nested SegWit (BIP-49) — เริ่มด้วย 3</option>
                                <option value="native" selected>Native SegWit (BIP-84) — เริ่มด้วย bc1q</option>
                                <option value="taproot">Taproot (BIP-86) — เริ่มด้วย bc1p</option>
                            </select>
                            <!-- NEW: Hint -->
                            <div class="option-hint">
                                <i class="fa-solid fa-circle-info text-warning me-1"></i>
                                แต่ละประเภทใช้สมการต่างกัน ส่งผลให้รูปแบบที่อยู่ต่างกัน ค่าธรรมเนียมต่างกัน <strong>แนะนำ Native SegWit (bc1q)</strong> สำหรับใช้งานทั่วไป
                            </div>
                        </div>
                        
                        <!-- Mnemonic Display -->
                        <div class="col-12 mt-2">
                            <label class="text-success small mb-1 fw-bold"><i class="fa-solid fa-seedling"></i> PRE-GENERATED SEED PHRASE</label>
                            <div id="setupMnemonicDisplay" class="font-mono text-success bg-dark p-3 rounded border border-secondary border-opacity-25 text-break lh-base text-center" style="font-size: 0.95rem; background-color: rgba(0,255,65,0.05); text-shadow: 0 0 8px rgba(0,255,65,0.4);">
                                กำลังโหลดไลบรารีคณิตศาสตร์...
                            </div>
                            <!-- NEW: Hint -->
                            <div class="option-hint">
                                <i class="fa-solid fa-rotate text-success me-1"></i>
                                คำชุดนี้ถูกสุ่มขึ้นใหม่ทุกครั้ง — มาจากรายการ BIP-39 Wordlist มาตรฐาน 2,048 คำ สุ่มใหม่ได้โดยกด Refresh
                            </div>
                        </div>

                        <!-- Passphrase -->
                        <div class="col-12 mt-2">
                            <label class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-lock"></i> OPTIONAL PASSPHRASE (BIP-39)</label>
                            <input type="text" id="passphrase" class="form-control font-mono bg-dark border-secondary" style="color: #ff3333;" placeholder="เว้นว่างไว้หากไม่ต้องการใช้ (13th / 25th Word)">
                            <!-- NEW: Expanded hint -->
                            <div class="option-hint mt-1" style="border-left-color: #ff3333;">
                                <i class="fa-solid fa-shield-halved text-danger me-1"></i>
                                <strong style="color:#ff3333;">Passphrase</strong> คือรหัสลับชั้นที่สองที่คุณจำ ไม่ได้จดไว้ในกระดาษ ถ้าโจรได้ Seed Phrase แต่ไม่รู้ Passphrase ก็ไม่สามารถเข้าถึงเงินได้ — แต่ <strong>ลืม Passphrase = สูญเงินทั้งหมด</strong>
                            </div>
                        </div>
                    </div>

                    <button id="btnStart" class="btn btn-neon px-5 py-3 rounded-pill shadow-lg w-100 mt-2" disabled>
                        <span class="spinner-border spinner-border-sm" id="loadingSpinner"></span>
                        <i class="fa-solid fa-play me-2 d-none" id="playIcon"></i> <span id="btnStartText">กำลังเตรียมไลบรารี...</span>
                    </button>
                    
                    <div id="errorBox" class="alert alert-danger mt-3 d-none small fw-bold"></div>
                </div>
            </div>
        </div>

        <!-- ===== LESSON AREA ===== -->
        <div id="lessonArea" style="display: none;">
            
            <!-- ===== STEP 1: The Root ===== -->
            <div id="step1" class="step-section active">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="neon-box p-3 p-md-4 mb-4">

                            <!-- Breadcrumb -->
                            <div class="step-breadcrumb">
                                <span class="crumb active">ขั้นที่ 1: สร้าง Seed</span>
                                <span class="sep">›</span>
                                <span class="crumb">ขั้นที่ 2: Master Key</span>
                                <span class="sep">›</span>
                                <span class="crumb">ขั้นที่ 3: ที่อยู่</span>
                            </div>

                            <h3 class="brand-font h4 text-white mb-3"><i class="fa-solid fa-seedling text-success me-2"></i> 1. The Root (สร้างรากแก้ว)</h3>
                            
                            <!-- NEW: Concept Box -->
                            <div class="concept-box">
                                <div class="concept-title"><i class="fa-solid fa-lightbulb"></i> แนวคิดหลักของขั้นตอนนี้</div>
                                <p>เราเริ่มต้นจาก <strong style="color:#00ff41;">Seed Phrase</strong> (ชุดคำภาษาอังกฤษ) แล้วแปลงมันเป็น <strong style="color:#0dcaf0;">Master Seed</strong> ซึ่งเป็นตัวเลขฐานสิบหกยาว 128 ตัวอักษร (64 Bytes / 512 bits)</p>
                                <p>เปรียบได้กับการ <strong>นำรหัสลับของคุณไปใส่ในเครื่องผสม</strong> (PBKDF2) วนซ้ำ 2,048 รอบ เพื่อให้ผลลัพธ์ซับซ้อนเกินกว่าจะถอดรหัสกลับได้</p>
                            </div>

                            <!-- NEW: Analogy Box -->
                            <div class="analogy-box">
                                <div class="analogy-title"><i class="fa-solid fa-gamepad me-1"></i> เปรียบเทียบให้เข้าใจง่าย</div>
                                คิดว่า Seed Phrase = <strong>โค้ด DNA ของกระเป๋าตัง</strong> ใครมีโค้ดนี้ก็สามารถ "โคลน" กระเป๋าทั้งหมดของคุณได้ทันที รวมทั้งเงินทุกบาท
                            </div>

                            <div class="data-block mt-3 border-success">
                                <span class="data-label text-success">Generated Mnemonic (<span id="dispWordCount"></span> Words)</span>
                                <div id="realSeed" class="font-mono text-white pt-2 text-break" style="font-size: 0.9rem;"></div>
                            </div>
                            
                            <div class="data-block mt-3 border-danger" id="passphraseBox" style="display: none;">
                                <span class="data-label text-danger">Optional Passphrase (13th/25th Word)</span>
                                <div id="dispPassphrase" class="font-mono text-white pt-2 text-break fw-bold" style="font-size: 0.9rem; color: #ff3333 !important;"></div>
                            </div>

                            <!-- Process Arrow -->
                            <div class="labeled-divider">
                                <i class="fa-solid fa-arrow-down text-warning"></i>
                                <span style="color: #F7931A; font-family: 'JetBrains Mono', monospace;">PBKDF2 HMAC-SHA512 × 2,048 รอบ</span>
                                <i class="fa-solid fa-arrow-down text-warning"></i>
                            </div>

                            <div class="data-block border-info">
                                <span class="data-label text-info">Master Seed (64 Bytes / 512-bit)</span>
                                <div id="masterSeed" class="font-mono text-info pt-2 text-break" style="font-size: 0.8rem;"></div>
                            </div>

                            <!-- Security Box -->
                            <div class="security-box">
                                <div class="security-title"><i class="fa-solid fa-triangle-exclamation me-1"></i> ข้อควรระวังด้านความปลอดภัย</div>
                                Seed Phrase คือ <strong>กุญแจแห่งอธิปไตยทางการเงิน</strong> — อย่าถ่ายรูป อย่าพิมพ์ใน Line/Email อย่าบอกใคร ไม่ว่าจะอ้างตัวว่าเป็น Support ของแอปไหนก็ตาม
                            </div>

                            <!-- Deep Dive -->
                            <button class="btn btn-sm btn-outline-info w-100 text-start mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#deepDive1">
                                <i class="fa-solid fa-book-open-reader me-2"></i> 📚 เจาะลึกอัลกอริทึม & วิธีคำนวณ (Deep Dive)
                            </button>
                            <div class="collapse" id="deepDive1">
                                <div class="deep-dive-box">
                                    <h6><i class="fa-solid fa-microchip"></i> Algorithm: PBKDF2 HMAC-SHA512</h6>
                                    
                                    <p><strong>PBKDF2</strong> ย่อมาจาก <span class="algo-highlight">Password-Based Key Derivation Function 2</span> — ทำหน้าที่ปั่นข้อความ Mnemonic เพื่อสร้าง Master Seed โดยมีการ <strong>ถ่วงเวลา (Key Stretching) 2,048 รอบ</strong> เพื่อป้องกันการ Brute Force</p>
                                    
                                    <div class="kv-row"><span class="kv-key">Input (Password)</span><span class="kv-val">ชุดคำ Mnemonic ที่คุณมี (UTF-8 Bytes)</span></div>
                                    <div class="kv-row"><span class="kv-key">Salt</span><span class="kv-val">คำว่า <code>"mnemonic"</code> ต่อด้วย Passphrase ของคุณ (ถ้ามี)</span></div>
                                    <div class="kv-row"><span class="kv-key">Iterations</span><span class="kv-val">2,048 รอบ — ทำให้แฮชช้าพอที่จะป้องกัน Brute Force</span></div>
                                    <div class="kv-row"><span class="kv-key">Output</span><span class="kv-val">512-bit (64 Bytes) = Master Seed ที่เห็นด้านบน</span></div>

                                    <p class="mb-1 mt-3 text-danger"><i class="fa-solid fa-lock"></i> <strong>บทบาทของ Passphrase (คำลับเสริม):</strong></p>
                                    <p style="font-size: 0.85rem;">ในมาตรฐาน BIP-39 ระบบใช้คำว่า <code>"mnemonic"</code> เป็น Salt ตั้งต้น แต่ถ้าคุณเพิ่ม Passphrase เข้ามา ระบบจะต่อท้ายเป็น <code>"mnemonic<span style="color:#ff3333;">YourPassword</span>"</code> — แค่ตัวอักษรเดียวเปลี่ยนไป ผลลัพธ์ Master Seed จะเปลี่ยนไปเป็นคนละเรื่อง นี่คือเทคนิค <strong>"Hidden Wallet"</strong> ที่นักเก็บ Bitcoin ขั้นสูงใช้ซ่อนกระเป๋าหลัก</p>

                                    <div id="dynamicExplainStep1"></div>

                                    <div class="diy-box text-start mt-3">
                                        <p class="text-white small fw-bold mb-1"><i class="fa-solid fa-laptop-code"></i> ลองรัน Python เพื่อพิสูจน์ (DIY Verify):</p>
                                        <code class="diy-code">import hashlib, binascii
mnemonic = b"<span id="diyMnemonic"></span>"
salt = b"mnemonic<span id="diyPassphrase" class="text-danger fw-bold"></span>"
seed = hashlib.pbkdf2_hmac('sha512', mnemonic, salt, 2048)
print("Master Seed:", binascii.hexlify(seed).decode())</code>
                                        <p class="text-secondary mt-2 mb-0" style="font-size:0.75rem;"><i class="fa-solid fa-circle-check text-success me-1"></i> คัดลอกโค้ดนี้ไปรันใน Python หรือ <a href="https://www.python.org/shell" target="_blank" style="color: var(--neon-blue);">python.org/shell</a> แล้วเปรียบเทียบผลลัพธ์กับค่าที่แสดงด้านบน — ต้องตรงกัน 100%</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button onclick="backToSetup()" class="btn btn-back py-3 px-4 rounded-pill" title="กลับไปตั้งค่าเพิ่มเติม">
                                    <i class="fa-solid fa-gear"></i>
                                </button>
                                <button onclick="goToStep(2)" class="btn btn-neon w-100 py-3 rounded-pill">
                                    แบ่งแยกกุญแจ <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 2: Master Nodes ===== -->
            <div id="step2" class="step-section">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="neon-box p-3 p-md-4 mb-4">

                            <!-- Breadcrumb -->
                            <div class="step-breadcrumb">
                                <span class="crumb">ขั้นที่ 1: สร้าง Seed</span>
                                <span class="sep">›</span>
                                <span class="crumb active">ขั้นที่ 2: Master Key</span>
                                <span class="sep">›</span>
                                <span class="crumb">ขั้นที่ 3: ที่อยู่</span>
                            </div>

                            <h3 class="brand-font h4 text-white mb-3"><i class="fa-solid fa-key text-warning me-2"></i> 2. Master Nodes (การแบ่งแยกอำนาจ)</h3>
                            
                            <!-- NEW: Concept Box -->
                            <div class="concept-box">
                                <div class="concept-title"><i class="fa-solid fa-lightbulb"></i> แนวคิดหลักของขั้นตอนนี้</div>
                                <p>Master Seed (512 bits) ถูกป้อนเข้า <strong style="color:#0dcaf0;">HMAC-SHA512</strong> อีกครั้ง แล้ว <strong>ผ่าครึ่งผลลัพธ์</strong> ออกเป็นสองส่วน:</p>
                                <div class="kv-row"><span class="kv-key" style="color: #FFD700;">ซ้าย 32 Bytes</span><span class="kv-val"><strong style="color:#FFD700;">Master Private Key</strong> — กุญแจหลักที่ใช้เซ็นการโอน (อำนาจสั่งจ่าย)</span></div>
                                <div class="kv-row"><span class="kv-key" style="color: #888;">ขวา 32 Bytes</span><span class="kv-val"><strong style="color:#aaa;">Chain Code</strong> — "สูตรลับ" ที่ใช้ร่วมกับ Private Key เพื่อสร้างกุญแจลูก (Child Keys) ได้ไม่จำกัด</span></div>
                            </div>

                            <!-- NEW: Analogy Box -->
                            <div class="analogy-box">
                                <div class="analogy-title"><i class="fa-solid fa-building-columns me-1"></i> เปรียบเทียบ</div>
                                คิดว่า <strong>Master Private Key</strong> = ตราประทับของธนาคาร (มีอำนาจอนุมัติ) และ <strong>Chain Code</strong> = สูตรลับในการผลิตลูกกุญแจสาขา — ต้องมีทั้งสองอย่างจึงจะสร้าง Child Key ได้ถูกต้อง
                            </div>

                            <!-- Process Arrow -->
                            <div class="labeled-divider">
                                <i class="fa-solid fa-arrow-down text-warning"></i>
                                <span style="color: #F7931A; font-family: 'JetBrains Mono', monospace;">HMAC-SHA512 (Key = "Bitcoin seed")</span>
                                <i class="fa-solid fa-arrow-down text-warning"></i>
                            </div>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="data-block border-warning">
                                        <span class="data-label text-warning">Left 32 Bytes: Master Private Key <span class="bip-badge ms-1">อำนาจสั่งจ่าย</span></span>
                                        <div id="masterPriv" class="font-mono text-white pt-2 text-break" style="font-size: 0.8rem;"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="data-block border-secondary">
                                        <span class="data-label text-secondary">Right 32 Bytes: Chain Code <span class="bip-badge ms-1">สูตรสร้างลูก</span></span>
                                        <div id="chainCode" class="font-mono text-white pt-2 text-break" style="font-size: 0.8rem;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- NEW: Why two keys? -->
                            <div class="mt-3 p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid #222; font-size:0.82rem;">
                                <p class="text-info fw-bold mb-2"><i class="fa-solid fa-question-circle me-1"></i> ทำไมต้องแยกเป็นสองส่วน?</p>
                                <p class="text-secondary mb-1">เพราะ Bitcoin ใช้หลักการ <strong style="color:#e0e0e0;">การแยกอำนาจ (Separation of Powers)</strong>:</p>
                                <ul class="text-secondary mb-0" style="padding-left: 18px;">
                                    <li class="mb-1"><strong style="color:#FFD700;">Private Key เพียงอย่างเดียว</strong> ไม่เพียงพอสำหรับสร้าง Child Key — ป้องกันการคำนวณย้อนกลับ</li>
                                    <li class="mb-1"><strong style="color:#aaa;">Chain Code เพียงอย่างเดียว</strong> ก็ไม่มีอำนาจสั่งจ่าย — ใช้ประโยชน์ไม่ได้ถ้าไม่มี Private Key</li>
                                    <li><strong style="color:#00ff41;">ทั้งสองอย่างรวมกัน</strong> จึงสร้าง Child Keys ได้ตามโครงสร้าง Hierarchical ที่ต้องการ</li>
                                </ul>
                            </div>

                            <!-- Deep Dive -->
                            <button class="btn btn-sm btn-outline-info w-100 text-start mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#deepDive2">
                                <i class="fa-solid fa-book-open-reader me-2"></i> 📚 เจาะลึกอัลกอริทึม & วิธีคำนวณ (Deep Dive)
                            </button>
                            <div class="collapse" id="deepDive2">
                                <div class="deep-dive-box">
                                    <h6><i class="fa-solid fa-microchip"></i> Algorithm: HMAC-SHA512 (BIP-32)</h6>
                                    <p>ทำหน้าที่นำ Master Seed มาผ่าครึ่ง เพื่อแยก <strong>อำนาจในการสั่งจ่าย (Master Priv)</strong> ออกจาก <strong>สูตรสร้างกุญแจลูก (Chain Code)</strong></p>
                                    
                                    <div class="kv-row mt-2"><span class="kv-key">Function</span><span class="kv-val"><span class="algo-highlight">HMAC-SHA512</span></span></div>
                                    <div class="kv-row"><span class="kv-key">Key (กุญแจ)</span><span class="kv-val">สตริงตายตัว: <code>"Bitcoin seed"</code> — มาตรฐาน BIP-32 กำหนดไว้</span></div>
                                    <div class="kv-row"><span class="kv-key">Data</span><span class="kv-val">Master Seed 64 Bytes จากขั้นตอนที่ 1</span></div>
                                    <div class="kv-row"><span class="kv-key">Output</span><span class="kv-val">512 bits → ตัดออกสองส่วน (IL = 256 bits ซ้าย, IR = 256 bits ขวา)</span></div>

                                    <div id="dynamicExplainStep2"></div>

                                    <div class="diy-box text-start mt-3">
                                        <p class="text-white small fw-bold mb-1"><i class="fa-solid fa-laptop-code"></i> ลองรัน Python เพื่อพิสูจน์ (DIY Verify):</p>
                                        <code class="diy-code">import hmac, hashlib, binascii
master_seed = bytes.fromhex("<span id="diyMasterSeed" class="text-info">— กด Start Simulation ก่อน —</span>")
key = b"Bitcoin seed"
result = hmac.new(key, master_seed, hashlib.sha512).digest()
print("Master Private Key:", binascii.hexlify(result[:32]).decode())
print("Chain Code:        ", binascii.hexlify(result[32:]).decode())</code>
                                        <p class="text-secondary mt-2 mb-0" style="font-size:0.75rem;"><i class="fa-solid fa-circle-check text-success me-1"></i> คัดลอกโค้ดนี้ไปรันใน <a href="https://www.python.org/shell" target="_blank" style="color: var(--neon-blue);">python.org/shell</a> แล้วเปรียบเทียบผลลัพธ์กับค่าที่แสดงด้านบน — ต้องตรงกัน 100%</p>
                                    </div><!-- end diy-box -->

                                </div><!-- end deep-dive-box -->
                            </div><!-- end collapse #deepDive2 -->

                            <div class="d-flex gap-2 mt-4">
                                <button onclick="goToStep(1)" class="btn btn-back py-3 px-4 rounded-pill">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </button>
                                <button onclick="goToStep(3)" class="btn btn-neon w-100 py-3 rounded-pill">
                                    กำหนดโครงสร้าง Path <i class="fa-solid fa-sitemap ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== STEP 3: Child Key Derivation ===== -->
            <div id="step3" class="step-section">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="neon-box p-3 p-md-4">

                            <!-- Breadcrumb -->
                            <div class="step-breadcrumb">
                                <span class="crumb">ขั้นที่ 1: สร้าง Seed</span>
                                <span class="sep">›</span>
                                <span class="crumb">ขั้นที่ 2: Master Key</span>
                                <span class="sep">›</span>
                                <span class="crumb active">ขั้นที่ 3: ที่อยู่</span>
                            </div>

                            <h3 class="brand-font h4 text-white mb-3"><i class="fa-solid fa-sitemap me-2" style="color: var(--neon-purple);"></i> 3. Child Key Derivation</h3>
                            
                            <!-- NEW: Concept Box -->
                            <div class="concept-box">
                                <div class="concept-title"><i class="fa-solid fa-lightbulb"></i> แนวคิดหลักของขั้นตอนนี้</div>
                                <p>จาก Master Key เดียว เราสามารถ <strong style="color:#00ff41;">แตกกุญแจลูกได้ไม่จำกัด</strong> ตามโครงสร้างที่กำหนดไว้ใน <strong style="color:#0dcaf0;">Derivation Path</strong></p>
                                <p>แต่ละที่อยู่บิตคอยน์ที่คุณแชร์ให้ใครโอนเงินมา คือ <strong>ผลลัพธ์ปลายสาย</strong>ของการคำนวณตามเส้นทางนี้ — ทุกอย่างเชื่อมโยงกันหมดโดยไม่ต้องบันทึก</p>
                            </div>

                            <!-- NEW: Analogy Box -->
                            <div class="analogy-box">
                                <div class="analogy-title"><i class="fa-solid fa-tree me-1"></i> เปรียบเทียบ: ต้นไม้กุญแจ</div>
                                คิดว่า Master Key = <strong>รากของต้นไม้</strong> และ Derivation Path = <strong>พิกัดตำแหน่งบนต้นไม้</strong> เช่น "ลำดับที่ 3 ของกิ่งที่ 2 ของต้น 0" — ต้นเดียวกัน รากเดียวกัน แต่ไปได้ถึงใบไม้นับพันล้านใบ
                            </div>

                            <!-- NEW: Path Explanation Table -->
                            <div class="mb-3 p-3 rounded" style="background: rgba(0,0,0,0.4); border: 1px solid #1a1a1a; font-size: 0.8rem;">
                                <p class="text-info fw-bold mb-2"><i class="fa-solid fa-route me-1"></i> โครงสร้าง Derivation Path — อ่านค่าตัวเลขด้านล่างได้เลย:</p>
                                <div class="row g-1">
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card">
                                            <div class="text-warning fw-bold mb-1" style="font-size:0.75rem;"><i class="fa-solid fa-m me-1"></i> m (Master)</div>
                                            <div style="color:#888; font-size:0.72rem;">จุดเริ่มต้นเสมอ หมายถึง Master Private Key</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card">
                                            <div class="text-info fw-bold mb-1" style="font-size:0.75rem;"><i class="fa-solid fa-p me-1"></i> Purpose' <span class="bip-badge">Hardened</span></div>
                                            <div style="color:#888; font-size:0.72rem;">ระบุมาตรฐาน BIP ที่ใช้ เช่น 44=Legacy, 84=bc1q, 86=bc1p</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card">
                                            <div class="text-info fw-bold mb-1" style="font-size:0.75rem;"><i class="fa-brands fa-bitcoin me-1"></i> Coin' <span class="bip-badge">Hardened</span></div>
                                            <div style="color:#888; font-size:0.72rem;">เลือกสกุลเงิน: 0=Bitcoin, 1=Testnet, 60=ETH ฯลฯ</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card">
                                            <div class="text-info fw-bold mb-1" style="font-size:0.75rem;"><i class="fa-solid fa-briefcase me-1"></i> Account' <span class="bip-badge">Hardened</span></div>
                                            <div style="color:#888; font-size:0.72rem;">แยกบัญชีงาน เช่น Account 0=ส่วนตัว, 1=ธุรกิจ</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card">
                                            <div class="text-secondary fw-bold mb-1" style="font-size:0.75rem;"><i class="fa-solid fa-arrows-left-right me-1"></i> Change</div>
                                            <div style="color:#888; font-size:0.72rem;">0=ที่อยู่รับเงินภายนอก, 1=ที่อยู่ทอนเงิน (Internal)</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="addr-card" style="border-color: rgba(176,38,255,0.4);">
                                            <div class="fw-bold mb-1" style="color: var(--neon-purple); font-size:0.75rem;"><i class="fa-solid fa-hashtag me-1"></i> Index ← ลองเปลี่ยนตัวนี้!</div>
                                            <div style="color:#888; font-size:0.72rem;">หมายเลขที่อยู่ลำดับที่ N ในบัญชีนี้ (0, 1, 2, 3…)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Path Controls -->
                            <div class="text-center mb-2">
                                <span class="badge bg-secondary mb-2">โครงสร้างกระเป๋า <span id="dispAddressTypeName" class="text-warning"></span></span>
                            </div>

                            <div class="bg-black border border-secondary border-opacity-50 rounded p-3 mb-4">
                                <label class="text-info small fw-bold d-block text-center mb-0"><i class="fa-solid fa-hand-pointer"></i> กดลูกศร ↑↓ เพื่อเปลี่ยนค่า — ผลลัพธ์คำนวณใหม่ทันที</label>
                                <div class="d-flex justify-content-center align-items-center flex-wrap font-mono mt-2">
                                    <span class="fs-4 text-white pb-4">m</span>
                                    <span class="text-secondary pb-4 mx-2">/</span>
                                    
                                    <div class="path-col">
                                        <i class="fa-solid fa-chevron-up path-btn" onclick="adjustPath('purpose', 1)"></i>
                                        <div class="d-flex align-items-center">
                                            <span id="val_purpose" class="fs-5 text-info fw-bold">84</span><span class="text-secondary">'</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down path-btn" onclick="adjustPath('purpose', -1)"></i>
                                        <small class="text-muted mt-1" style="font-size: 0.65rem;">Purpose</small>
                                    </div>
                                    <span class="text-secondary pb-4 mx-2">/</span>

                                    <div class="path-col">
                                        <i class="fa-solid fa-chevron-up path-btn" onclick="adjustPath('coin', 1)"></i>
                                        <div class="d-flex align-items-center">
                                            <span id="val_coin" class="fs-5 text-info fw-bold">0</span><span class="text-secondary">'</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down path-btn" onclick="adjustPath('coin', -1)"></i>
                                        <small class="text-muted mt-1" style="font-size: 0.65rem;">Coin</small>
                                    </div>
                                    <span class="text-secondary pb-4 mx-2">/</span>

                                    <div class="path-col">
                                        <i class="fa-solid fa-chevron-up path-btn" onclick="adjustPath('account', 1)"></i>
                                        <div class="d-flex align-items-center">
                                            <span id="val_account" class="fs-5 text-info fw-bold">0</span><span class="text-secondary">'</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down path-btn" onclick="adjustPath('account', -1)"></i>
                                        <small class="text-muted mt-1" style="font-size: 0.65rem;">Account</small>
                                    </div>
                                    <span class="text-secondary pb-4 mx-2">/</span>

                                    <div class="path-col">
                                        <i class="fa-solid fa-chevron-up path-btn" onclick="adjustPath('change', 1)"></i>
                                        <div class="d-flex align-items-center">
                                            <span id="val_change" class="fs-5 text-info fw-bold">0</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down path-btn" onclick="adjustPath('change', -1)"></i>
                                        <small class="text-muted mt-1" style="font-size: 0.65rem;">Change</small>
                                    </div>
                                    <span class="text-secondary pb-4 mx-2">/</span>

                                    <div class="path-col">
                                        <i class="fa-solid fa-chevron-up path-btn highlight" onclick="adjustPath('index', 1)"></i>
                                        <div class="border border-purple rounded bg-dark px-2">
                                            <span id="val_index" class="fs-4 fw-bold" style="color: var(--neon-purple);">0</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down path-btn highlight" onclick="adjustPath('index', -1)"></i>
                                        <small class="fw-bold mt-1" style="color: var(--neon-purple); font-size: 0.65rem;">Index</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Results -->
                            <div id="dynamicKeysArea">

                                <div class="labeled-divider">
                                    <span style="color:#555; font-size:0.7rem;">ผลลัพธ์การคำนวณตาม Path</span>
                                </div>

                                <div class="data-block border-danger mb-3" id="boxPriv">
                                    <span class="data-label text-danger">Child Private Key — ห้ามเปิดเผย!</span>
                                    <div id="childPrivHex" class="font-mono text-white pt-2 text-break" style="font-size: 0.8rem;"></div>
                                </div>

                                <div class="text-center text-secondary small fw-bold mb-3">
                                    <i class="fa-solid fa-arrow-down"></i> <span style="color:#b026ff;">Elliptic Curve Multiplication (secp256k1)</span> <i class="fa-solid fa-arrow-down"></i>
                                    <div style="font-size:0.7rem; color:#444; margin-top:2px;">คูณ Private Key กับ Generator Point บนเส้นโค้งวงรี — ทางเดียว, ย้อนกลับไม่ได้</div>
                                </div>

                                <div class="data-block border-info mb-3" id="boxPub">
                                    <span class="data-label text-info">Child Public Key <span id="pubKeyTypeLabel" class="fw-bold text-white ms-1"></span> — แชร์ได้อย่างปลอดภัย</span>
                                    <div id="childPubHex" class="font-mono text-white pt-2 text-break" style="font-size: 0.8rem;"></div>
                                </div>

                                <div class="text-center text-secondary small fw-bold mb-3">
                                    <i class="fa-solid fa-arrow-down"></i> <span id="hashAlgoDesc">Hash160 & Encoding</span> <i class="fa-solid fa-arrow-down"></i>
                                    <div style="font-size:0.7rem; color:#444; margin-top:2px;">ย่อและแปลงรูปแบบให้มนุษย์อ่านได้ง่าย</div>
                                </div>

                                <div class="data-block border-success text-center py-4" id="boxAddr">
                                    <span class="data-label text-success"><i class="fa-solid fa-qrcode"></i> Final Address (<span id="encType"></span>) — แชร์ให้คนโอนเงินมา</span>
                                    <h3 id="finalAddress" class="font-mono text-success fw-bold m-0 text-break"></h3>
                                </div>
                            </div>

                            <!-- NEW: Address Type Summary -->
                            <div class="mt-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid #1a1a1a; font-size: 0.8rem;">
                                <p class="text-warning fw-bold mb-2"><i class="fa-solid fa-info-circle me-1"></i> ประเภทที่อยู่บิตคอยน์ — เปรียบเทียบ:</p>
                                <div class="addr-card mb-1">
                                    <span class="addr-type text-secondary">1xxx...</span> <span class="bip-badge">BIP-44</span> <span style="color:#888; font-size:0.73rem;">Legacy P2PKH — เก่าที่สุด, ค่าธรรมเนียมสูงสุด, ใช้ได้ทุก Wallet</span>
                                </div>
                                <div class="addr-card mb-1">
                                    <span class="addr-type text-secondary">3xxx...</span> <span class="bip-badge">BIP-49</span> <span style="color:#888; font-size:0.73rem;">Nested SegWit P2SH — ค่าธรรมเนียมลดลง, เข้ากันได้กับ Wallet รุ่นเก่า</span>
                                </div>
                                <div class="addr-card mb-1" style="border-color: rgba(0,255,65,0.3);">
                                    <span class="addr-type" style="color: var(--neon-green);">bc1q...</span> <span class="bip-badge">BIP-84</span> <span class="status-badge ms-1">แนะนำ</span> <span style="color:#888; font-size:0.73rem;">Native SegWit P2WPKH — ค่าธรรมเนียมถูก, ใช้งานแพร่หลาย</span>
                                </div>
                                <div class="addr-card">
                                    <span class="addr-type" style="color: #b026ff;">bc1p...</span> <span class="bip-badge">BIP-86</span> <span style="color:#888; font-size:0.73rem;">Taproot P2TR — ล่าสุด, Privacy สูง, Schnorr Signature</span>
                                </div>
                            </div>

                            <!-- Deep Dive -->
                            <button class="btn btn-sm btn-outline-info w-100 text-start mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#deepDive3">
                                <i class="fa-solid fa-book-open-reader me-2"></i> 📚 เจาะลึกอัลกอริทึม — แกะรอยทุก Step (Deep Dive)
                            </button>
                            <div class="collapse" id="deepDive3">
                                <div class="deep-dive-box">
                                    <h6><i class="fa-solid fa-microchip"></i> แกะรอยสมการแบบ Step-by-step</h6>
                                    
                                    <div class="mb-3" style="font-size:0.82rem;">
                                        <p class="text-info fw-bold mb-1">🔐 Elliptic Curve (secp256k1) คืออะไร?</p>
                                        <p style="color:#a0b4c8;">เป็นสมการทางคณิตศาสตร์บนเส้นโค้งวงรี: <code style="color:#58a6ff;">y² = x³ + 7 (mod p)</code></p>
                                        <p style="color:#a0b4c8;">การ "คูณ" Private Key กับ Generator Point G บนเส้นโค้งนี้ ให้ Public Key — <strong>ทำได้ทางเดียว ย้อนกลับไม่ได้</strong> เพราะต้องแก้ ECDLP (Elliptic Curve Discrete Logarithm Problem) ซึ่งใช้เวลาเป็นพันล้านปีแม้กับ Quantum Computer ในยุคนี้</p>
                                    </div>

                                    <div id="dynamicExplainStep3"></div>

                                    <!-- DIY Step 1: Child Private Key — อธิบายทำไม Verify ตรงไม่ได้ -->
                                    <div class="diy-box text-start mt-3">
                                        <p class="text-warning small fw-bold mb-1"><i class="fa-solid fa-1"></i> Child Private Key — ทำไมคำนวณตรงๆ ไม่ได้?</p>
                                        <code class="diy-code"># Child Private Key = <span id="diyChildPriv1" class="text-danger">— กด Start ก่อน —</span>
#
# ค่านี้ได้จากการ derive แบบซ้อนกัน 5 ชั้น:
#   m → 84' → 0' → 0' → 0 → 0
# แต่ละชั้นใช้ HMAC-SHA512 กับ key และ chain code ของชั้นก่อนหน้า
# ไม่สามารถคำนวณตรงด้วย stdlib ได้ เพราะต้องทำ 5 รอบต่อเนื่อง
#
# วิธี Verify อิสระ: ใช้เว็บ https://iancoleman.io/bip39/
# → ใส่ Mnemonic → เลือก BIP84 → ดู Derived Addresses
# → Child Private Key ของ index 0 ต้องตรงกัน ✓</code>
                                        <p class="text-secondary mt-2 mb-0" style="font-size:0.72rem;"><i class="fa-solid fa-circle-info text-info me-1"></i> iancoleman.io ใช้งาน Offline ได้ — ควร Download และรันบนเครื่องตัวเองเพื่อความปลอดภัย</p>
                                    </div>

                                    <!-- DIY Step 2: Public Key จาก Child Private Key — pure stdlib -->
                                    <div class="diy-box text-start mt-3">
                                        <p class="text-info small fw-bold mb-1"><i class="fa-solid fa-2"></i> พิสูจน์ Public Key จาก Child Private Key (ไม่ต้องติดตั้ง library):</p>
                                        <code class="diy-code">import hashlib

# Child Private Key จาก Simulator (ใส่ค่าจริง)
priv_hex = "<span id="diyChildPriv2" class="text-danger">— กด Start ก่อน —</span>"

# secp256k1 parameters
p = 0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEFFFFFC2F
Gx= 0x79BE667EF9DCBBAC55A06295CE870B07029BFCDB2DCE28D959F2815B16F81798
Gy= 0x483ADA7726A3C4655DA4FBFC0E1108A8FD17B448A68554199C47D08FFB10D4B8

def point_add(P, Q):
    if P is None: return Q
    if Q is None: return P
    if P[0] == Q[0] and P[1] != Q[1]: return None
    if P == Q:
        lam = (3*P[0]*P[0] * pow(2*P[1], p-2, p)) % p
    else:
        lam = ((Q[1]-P[1]) * pow(Q[0]-P[0], p-2, p)) % p
    x = (lam*lam - P[0] - Q[0]) % p
    y = (lam*(P[0]-x) - P[1]) % p
    return (x, y)

def scalar_mult(k, P):
    R, Q = None, P
    while k:
        if k & 1: R = point_add(R, Q)
        Q = point_add(Q, Q)
        k >>= 1
    return R

k = int(priv_hex, 16)
pub = scalar_mult(k, (Gx, Gy))
prefix = '02' if pub[1] % 2 == 0 else '03'
pub_hex = prefix + format(pub[0], '064x')
print("Public Key:", pub_hex)
# ต้องตรงกับที่แสดงในหน้า Simulator ✓</code>
                                        <p class="text-secondary mt-2 mb-0" style="font-size:0.72rem;"><i class="fa-solid fa-circle-check text-success me-1"></i> รันบน <a href="https://www.python.org/shell" target="_blank" style="color: var(--neon-blue);">python.org/shell</a> — ใช้แค่ stdlib ไม่ต้องติดตั้งอะไร</p>
                                    </div>

                                    <!-- DIY Step 3: inject by JS ตาม address type -->
                                    <div id="diyStep3Box" class="diy-box text-start mt-3">
                                        <p class="text-success small fw-bold mb-1"><i class="fa-solid fa-3"></i> พิสูจน์ Bitcoin Address จาก Public Key (ไม่ต้องติดตั้ง library):</p>
                                        <code class="diy-code" id="diyStep3Code">— กด Start Simulation ก่อน —</code>
                                        <p class="text-secondary mt-2 mb-0" id="diyStep3Note" style="font-size:0.72rem;"></p>
                                    </div>

                                </div><!-- end deep-dive-box -->
                            </div><!-- end collapse #deepDive3 -->

                            <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                                <button onclick="goToStep(2)" class="btn btn-back px-4 py-2 rounded-pill w-100 w-md-auto">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Back
                                </button>
                                <button onclick="location.reload()" class="btn btn-outline-secondary px-4 py-2 rounded-pill w-100 w-md-auto ms-auto">
                                    <i class="fa-solid fa-rotate-left me-2"></i> สุ่ม Seed ใหม่ (Refresh)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end lessonArea -->
    </div><!-- end container -->

    <footer class="text-center p-4 text-secondary small border-top border-secondary border-opacity-25 mt-auto bg-black">
        <p class="mb-1">&copy; 2026 Chollatis Bitcoiner | <em>Don't Trust, Verify.</em></p>
        <p class="mb-0 opacity-50 text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> ข้อมูลจำลองนี้ถูกประมวลผลบน Browser เท่านั้น — ไม่มีการส่งข้อมูลออกเครือข่าย ห้ามนำ Seed นี้ไปโอนเงินจริงเด็ดขาด!</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global State
        window.pathVars = { purpose: 84, coin: 0, account: 0, change: 0, index: 0 };
        window.masterHDKey = null;
        window.currentAddrType = 'native';
        window.currentPassphrase = '';
        window.currentMnemonic = ''; 
        
        window.globalBip39 = null;
        window.globalWordlist = null;
        window.globalBtcModule = null;
        window.globalToHex = null;

        function updateEntropyDisplay() {
            const select = document.getElementById('wordCount');
            const entropy = select.options[select.selectedIndex].getAttribute('data-entropy');
            document.getElementById('entropyDisplay').innerText = entropy + '-bit';
        }

        window.onWordCountChange = function() {
            updateEntropyDisplay();
            if(window.globalBip39 && window.globalWordlist) {
                const selectWC = document.getElementById('wordCount');
                const entropyBits = selectWC.options[selectWC.selectedIndex].getAttribute('data-entropy');
                window.currentMnemonic = window.globalBip39.generateMnemonic(window.globalWordlist, parseInt(entropyBits));
                document.getElementById('setupMnemonicDisplay').innerText = window.currentMnemonic;
            }
        }

        function goToStep(step) {
            document.querySelectorAll('.step-section').forEach(el => el.classList.remove('active'));
            setTimeout(() => { document.getElementById(`step${step}`).classList.add('active'); }, 100);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        window.backToSetup = function() {
            document.getElementById('lessonArea').style.display = 'none';
            document.getElementById('setupPanel').style.display = 'flex'; 
            document.getElementById('btnStart').disabled = false; 
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function flashUpdate() {
            const boxes = ['boxPriv', 'boxPub', 'boxAddr'];
            boxes.forEach(id => {
                const el = document.getElementById(id);
                if(el) {
                    el.classList.add('flash');
                    setTimeout(() => el.classList.remove('flash'), 300);
                }
            });
        }

        window.adjustPath = function(field, delta) {
            if(!window.masterHDKey) return;
            window.pathVars[field] += delta;
            if(window.pathVars[field] < 0) window.pathVars[field] = 0; 
            document.getElementById('val_' + field).innerText = window.pathVars[field];
            if(typeof window.calculateDerivation === 'function') {
                window.calculateDerivation();
                flashUpdate();
            }
        }
    </script>

    <script type="module">
        const btnStart = document.getElementById('btnStart');
        const errBox = document.getElementById('errorBox');
        
        try {
            const bip39 = await import('https://cdn.jsdelivr.net/npm/@scure/bip39@1.3.0/+esm');
            const { wordlist } = await import('https://cdn.jsdelivr.net/npm/@scure/bip39@1.3.0/wordlists/english.js/+esm');
            const { HDKey } = await import('https://cdn.jsdelivr.net/npm/@scure/bip32@1.4.0/+esm');
            const { bytesToHex: toHex } = await import('https://cdn.jsdelivr.net/npm/@noble/hashes@1.4.0/utils.js/+esm');
            const btc = await import('https://cdn.jsdelivr.net/npm/@scure/btc-signer@1.2.0/+esm');

            window.globalBip39 = bip39;
            window.globalWordlist = wordlist;
            window.globalBtcModule = btc;
            window.globalToHex = toHex;

            document.getElementById('loadingSpinner').classList.add('d-none');
            document.getElementById('playIcon').classList.remove('d-none');
            document.getElementById('btnStartText').innerText = "เริ่มจำลอง (Start Simulation)";
            btnStart.disabled = false;
            
            window.onWordCountChange(); 
            
            window.startRealSimulation = async function() {
                try {
                    btnStart.disabled = true;
                    
                    const selectWC = document.getElementById('wordCount');
                    window.currentAddrType = document.getElementById('addressType').value;
                    window.currentPassphrase = document.getElementById('passphrase').value || "";
                    
                    const mnemonic = window.currentMnemonic;
                    document.getElementById('realSeed').innerText = mnemonic;
                    document.getElementById('dispWordCount').innerText = selectWC.value;
                    
                    if(window.currentPassphrase) {
                        document.getElementById('passphraseBox').style.display = 'block';
                        document.getElementById('dispPassphrase').innerText = window.currentPassphrase;
                    } else {
                        document.getElementById('passphraseBox').style.display = 'none';
                    }

                    // Step 1: Mnemonic → Seed
                    const seed = await bip39.mnemonicToSeed(mnemonic, window.currentPassphrase);
                    const seedHex = toHex(seed);
                    document.getElementById('masterSeed').innerText = seedHex;
                    document.getElementById('diyMasterSeed').innerText = seedHex;

                    let saltStr = window.currentPassphrase ? `mnemonic<span class="text-danger fw-bold">${window.currentPassphrase}</span>` : `mnemonic`;
                    document.getElementById('diyMnemonic').innerText = mnemonic;
                    document.getElementById('diyPassphrase').innerText = window.currentPassphrase;

                    document.getElementById('dynamicExplainStep1').innerHTML = `
                        <div class="trace-box">
                            <span class="text-secondary"><i class="fa-solid fa-arrow-right-to-bracket text-warning"></i> <strong>นำเข้า (Input):</strong> ชุดคำ <span class="text-success font-mono text-break">${mnemonic}</span></span><br>
                            <span class="text-secondary"><i class="fa-solid fa-gear text-info"></i> <strong>ประมวลผล:</strong> ผสมกับ Salt <code class="text-light">"${saltStr}"</code> แล้วแฮชด้วย PBKDF2-HMAC-SHA512 จำนวน <strong>2,048 รอบ</strong></span><br>
                            <span class="text-secondary"><i class="fa-solid fa-arrow-right-from-bracket text-success"></i> <strong>ผลลัพธ์ (Output) Master Seed:</strong><br><span class="text-info font-mono text-break">${seedHex}</span></span>
                        </div>
                    `;

                    // Step 2: Seed → HD Node
                    window.masterHDKey = HDKey.fromMasterSeed(seed);
                    const masterPrivHex = toHex(window.masterHDKey.privateKey);
                    const chainCodeHex = toHex(window.masterHDKey.chainCode);
                    
                    document.getElementById('masterPriv').innerText = masterPrivHex;
                    document.getElementById('chainCode').innerText = chainCodeHex;

                    document.getElementById('dynamicExplainStep2').innerHTML = `
                        <div class="trace-box">
                            <span class="text-secondary"><i class="fa-solid fa-arrow-right-to-bracket text-warning"></i> <strong>นำเข้า (Input):</strong> Master Seed 64 Bytes จากขั้นตอนที่ 1</span><br>
                            <span class="text-secondary"><i class="fa-solid fa-gear text-info"></i> <strong>ประมวลผล:</strong> เข้าสมการ HMAC-SHA512 ใช้กุญแจ <code class="text-light">"Bitcoin seed"</code> แล้วนำผล 512 bits มาผ่าครึ่ง</span><br>
                            <span class="text-secondary"><i class="fa-solid fa-arrow-right-from-bracket text-warning"></i> <strong>ผลลัพธ์ซ้าย (IL) = Master Private Key:</strong><br><span class="text-warning font-mono text-break">${masterPrivHex}</span></span><br>
                            <span class="text-secondary"><i class="fa-solid fa-arrow-right-from-bracket text-secondary"></i> <strong>ผลลัพธ์ขวา (IR) = Chain Code:</strong><br><span class="text-light font-mono text-break">${chainCodeHex}</span></span>
                        </div>
                    `;

                    // Step 3 initial state
                    let purpose = 84;
                    let typeName = "Native SegWit (P2WPKH)";
                    if(window.currentAddrType === 'legacy') { purpose = 44; typeName = "Legacy (P2PKH)"; }
                    else if(window.currentAddrType === 'segwit') { purpose = 49; typeName = "Nested SegWit (P2SH-P2WPKH)"; }
                    else if(window.currentAddrType === 'taproot') { purpose = 86; typeName = "Taproot (P2TR)"; }
                    
                    window.pathVars = { purpose: purpose, coin: 0, account: 0, change: 0, index: 0 };
                    
                    document.getElementById('val_purpose').innerText = window.pathVars.purpose;
                    document.getElementById('val_coin').innerText = window.pathVars.coin;
                    document.getElementById('val_account').innerText = window.pathVars.account;
                    document.getElementById('val_change').innerText = window.pathVars.change;
                    document.getElementById('val_index').innerText = window.pathVars.index;
                    document.getElementById('dispAddressTypeName').innerText = typeName;

                    window.calculateDerivation();

                    document.getElementById('setupPanel').style.display = 'none';
                    document.getElementById('lessonArea').style.display = 'block';
                    
                    goToStep(1);

                } catch (err) {
                    console.error("Simulation Error:", err);
                    alert("เกิดข้อผิดพลาดในการคำนวณ: " + err.message);
                    btnStart.disabled = false;
                }
            };

            window.calculateDerivation = function() {
                if(!window.masterHDKey) return;

                const fullPath = `m/${window.pathVars.purpose}'/${window.pathVars.coin}'/${window.pathVars.account}'/${window.pathVars.change}/${window.pathVars.index}`;

                const childNode = window.masterHDKey.derive(fullPath);
                const childPrivHexForUI = window.globalToHex(childNode.privateKey);
                document.getElementById('childPrivHex').innerText = childPrivHexForUI;
                
                let finalAddr = "", encFormat = "", hashAlgoDesc = "", pubKeyHexForUI = "";
                let specificExplanation = "";
                
                if(window.currentAddrType === 'legacy') {
                    pubKeyHexForUI = window.globalToHex(childNode.publicKey);
                    document.getElementById('pubKeyTypeLabel').innerText = "(33 Bytes Compressed)";
                    const p2pkh = window.globalBtcModule.p2pkh(childNode.publicKey);
                    finalAddr = p2pkh.address;
                    encFormat = "Base58Check";
                    hashAlgoDesc = "Hash160 (SHA256 → RIPEMD160) → Base58Check";
                    specificExplanation = `นำ Public Key 33 Bytes ไปแฮชด้วย SHA256 ก่อน แล้วตามด้วย RIPEMD160 ได้ 20 Bytes จากนั้นเพิ่ม Version Byte (0x00) และ Checksum 4 Bytes แล้วแปลงเป็นตัวอักษร Base58`;
                
                } else if(window.currentAddrType === 'segwit') {
                    pubKeyHexForUI = window.globalToHex(childNode.publicKey);
                    document.getElementById('pubKeyTypeLabel').innerText = "(33 Bytes Compressed)";
                    const p2wpkh = window.globalBtcModule.p2wpkh(childNode.publicKey);
                    const p2sh = window.globalBtcModule.p2sh(p2wpkh);
                    finalAddr = p2sh.address;
                    encFormat = "Base58Check (P2SH Wrapped)";
                    hashAlgoDesc = "P2WPKH Script → Hash160 → P2SH → Base58Check";
                    specificExplanation = `นำ Public Key ไปสร้าง SegWit Script ก่อน แล้วห่อหุ้มด้วย P2SH (นำ Script ไปแฮชอีกรอบ) ทำให้ได้ที่อยู่ขึ้นต้นด้วย 3 ซึ่ง Wallet รุ่นเก่าเข้ากันได้`;
                
                } else if(window.currentAddrType === 'native') {
                    pubKeyHexForUI = window.globalToHex(childNode.publicKey);
                    document.getElementById('pubKeyTypeLabel').innerText = "(33 Bytes Compressed)";
                    const p2wpkh = window.globalBtcModule.p2wpkh(childNode.publicKey);
                    finalAddr = p2wpkh.address;
                    encFormat = "Bech32";
                    hashAlgoDesc = "Hash160 → Bech32 Encoding";
                    specificExplanation = `นำ Public Key 33 Bytes ไปแฮช (Hash160 = SHA256 + RIPEMD160) ได้ 20 Bytes แล้วใช้ Bech32 Encoding (ใช้ 32 ตัวอักษรพิเศษ a-z 0-9 ไม่สับสน) ขึ้นต้นด้วย <strong>bc1q</strong> ค่าธรรมเนียมถูกกว่า Legacy ~40%`;
                
                } else if(window.currentAddrType === 'taproot') {
                    const xOnlyPubKey = childNode.publicKey.slice(1, 33);
                    pubKeyHexForUI = window.globalToHex(xOnlyPubKey);
                    document.getElementById('pubKeyTypeLabel').innerText = "(32 Bytes X-only)";
                    const p2tr = window.globalBtcModule.p2tr(xOnlyPubKey);
                    finalAddr = p2tr.address;
                    encFormat = "Bech32m";
                    hashAlgoDesc = "Schnorr X-only PubKey → Bech32m";
                    specificExplanation = `<strong>ตัด Prefix Byte แรกของ Public Key ทิ้ง</strong> (เหลือแค่พิกัด X 32 Bytes เพราะ Taproot ใช้ Schnorr Signature ซึ่งไม่ต้องการพิกัด Y) แล้วแปลงด้วย Bech32m ขึ้นต้นด้วย <strong>bc1p</strong> — ยืดหยุ่นกว่า, Privacy สูงกว่า, Smart Script ได้`;
                }

                document.getElementById('childPubHex').innerText = pubKeyHexForUI;
                document.getElementById('hashAlgoDesc').innerText = hashAlgoDesc;
                document.getElementById('encType').innerText = encFormat;
                document.getElementById('finalAddress').innerText = finalAddr;
                
                document.getElementById('dynamicExplainStep3').innerHTML = `
                    <div class="trace-box">
                        <div style="font-size:0.78rem; color:#555; margin-bottom:8px; font-family:'JetBrains Mono',monospace;">Path: m/${window.pathVars.purpose}'/${window.pathVars.coin}'/${window.pathVars.account}'/${window.pathVars.change}/${window.pathVars.index}</div>

                        <span class="text-secondary"><i class="fa-solid fa-1 text-warning me-1"></i> <strong>สร้าง Child Private Key:</strong><br>
                        <span class="ps-3">นำ Master Priv Key + Chain Code + Path จากขั้นตอนที่ 2 คำนวณตามสมการ CKD (Child Key Derivation)<br>
                        <i class="fa-solid fa-arrow-right text-danger me-1"></i> ได้ <span class="text-danger font-mono text-break">${childPrivHexForUI}</span></span></span>
                        
                        <div class="my-2 border-bottom border-secondary opacity-25"></div>
                        
                        <span class="text-secondary"><i class="fa-solid fa-2 text-warning me-1"></i> <strong>สร้าง Child Public Key:</strong><br>
                        <span class="ps-3">นำ Private Key ด้านบนคูณบนเส้นโค้ง secp256k1 ด้วย Generator Point G<br>
                        <i class="fa-solid fa-arrow-right text-info me-1"></i> ได้ <span class="text-info font-mono text-break">${pubKeyHexForUI}</span></span></span>
                        
                        <div class="my-2 border-bottom border-secondary opacity-25"></div>
                        
                        <span class="text-secondary"><i class="fa-solid fa-3 text-warning me-1"></i> <strong>เข้ารหัสเป็น Address (${encFormat}):</strong><br>
                        <span class="ps-3">${specificExplanation}<br>
                        <i class="fa-solid fa-arrow-right text-success me-1"></i> ได้ <span class="text-success fw-bold font-mono text-break">${finalAddr}</span></span></span>
                    </div>
                `;

                // Inject ค่าจริงเข้า DIY boxes
                const masterPrivEl1 = document.getElementById('diyChildPriv1');
                const childPrivEl2  = document.getElementById('diyChildPriv2');
                if (masterPrivEl1) masterPrivEl1.innerText = childPrivHexForUI;
                if (childPrivEl2)  childPrivEl2.innerText  = childPrivHexForUI;

                // DIY Step 3 — inject โค้ด Python ตาม address type
                const codeEl = document.getElementById('diyStep3Code');
                const noteEl = document.getElementById('diyStep3Note');
                const P = pubKeyHexForUI;
                let pyCode = '', pyNote = '';

                if (window.currentAddrType === 'legacy') {
                    pyCode =
`import hashlib, base58

pub_hex = "${P}"
pub_bytes = bytes.fromhex(pub_hex)

# Hash160 = SHA256 → RIPEMD160
h160 = hashlib.new('ripemd160', hashlib.sha256(pub_bytes).digest()).digest()

# Base58Check: version 0x00 + hash160 + checksum
payload  = b'\\x00' + h160
checksum = hashlib.sha256(hashlib.sha256(payload).digest()).digest()[:4]
address  = base58.b58encode(payload + checksum).decode()
print("Bitcoin Address (Legacy 1...):", address)
# ต้องตรงกับที่แสดงในหน้า Simulator ✓`;
                    pyNote = `<i class="fa-solid fa-circle-info text-warning me-1"></i> ต้องติดตั้ง: <code>pip install base58</code> ก่อนรัน — หรือตรวจสอบที่ <a href="https://learnmeabitcoin.com/tools/address" target="_blank" style="color:var(--neon-blue);">learnmeabitcoin.com/tools/address</a>`;

                } else if (window.currentAddrType === 'segwit') {
                    pyCode =
`import hashlib, base58

pub_hex = "${P}"
pub_bytes = bytes.fromhex(pub_hex)

# สร้าง P2WPKH script: OP_0 <hash160>
h160   = hashlib.new('ripemd160', hashlib.sha256(pub_bytes).digest()).digest()
script = b'\\x00\\x14' + h160   # 0x0014 = OP_0 PUSH20

# P2SH: Hash160 ของ script → Base58Check version 0x05
script_hash = hashlib.new('ripemd160', hashlib.sha256(script).digest()).digest()
payload  = b'\\x05' + script_hash
checksum = hashlib.sha256(hashlib.sha256(payload).digest()).digest()[:4]
address  = base58.b58encode(payload + checksum).decode()
print("Bitcoin Address (Nested SegWit 3...):", address)
# ต้องตรงกับที่แสดงในหน้า Simulator ✓`;
                    pyNote = `<i class="fa-solid fa-circle-info text-warning me-1"></i> ต้องติดตั้ง: <code>pip install base58</code> ก่อนรัน — หรือตรวจสอบที่ <a href="https://learnmeabitcoin.com/tools/address" target="_blank" style="color:var(--neon-blue);">learnmeabitcoin.com/tools/address</a>`;

                } else if (window.currentAddrType === 'native') {
                    pyCode =
`import hashlib

pub_hex = "${P}"
pub_bytes = bytes.fromhex(pub_hex)

# Hash160 = SHA256 → RIPEMD160
h160 = hashlib.new('ripemd160', hashlib.sha256(pub_bytes).digest()).digest()

# Bech32 encode (Native SegWit bc1q) — pure stdlib
CHARSET = "qpzry9x8gf2tvdw0s3jn54khce6mua7l"
def polymod(v):
    GEN=[0x3b6a57b2,0x26508e6d,0x1ea119fa,0x3d4233dd,0x2a1462b3]; c=1
    for d in v:
        b=c>>25; c=(c&0x1ffffff)<<5^d
        for i in range(5): c^=GEN[i] if (b>>i)&1 else 0
    return c
def cvt(data,f,t):
    acc=bits=0; ret=[]
    for v in data:
        acc=((acc<<f)|v)&0xfff; bits+=f
        while bits>=t: bits-=t; ret.append((acc>>bits)&((1<<t)-1))
    return ret
def bech32(hrp,data):
    combined=data+[0]*6
    p=polymod([ord(x)>>5 for x in hrp]+[0]+[ord(x)&31 for x in hrp]+combined)^1
    return hrp+'1'+''.join(CHARSET[d] for d in data)+''.join(CHARSET[(p>>(5*(5-i)))&31] for i in range(6))

address = bech32('bc', [0] + cvt(h160, 8, 5))
print("Bitcoin Address (bc1q...):", address)
# ต้องตรงกับที่แสดงในหน้า Simulator ✓`;
                    pyNote = `<i class="fa-solid fa-circle-check text-success me-1"></i> รันบน <a href="https://www.python.org/shell" target="_blank" style="color:var(--neon-blue);">python.org/shell</a> — ไม่ต้องติดตั้งอะไรเลย`;

                } else if (window.currentAddrType === 'taproot') {
                    pyCode =
`import hashlib

# Taproot ใช้ X-only Public Key (32 bytes — ตัด prefix byte แรกทิ้ง)
pub_hex = "${P}"   # 32 bytes x-only
x_only  = bytes.fromhex(pub_hex)

# Tagged hash สำหรับ TapTweak (BIP-341)
def tagged_hash(tag, data):
    t = hashlib.sha256(tag.encode()).digest()
    return hashlib.sha256(t + t + data).digest()

tweak = tagged_hash("TapTweak", x_only)

# p = secp256k1 prime, lift_x
p = 0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEFFFFFC2F
x = int.from_bytes(x_only, 'big')
y_sq = (pow(x,3,p) + 7) % p
y = pow(y_sq, (p+1)//4, p)
if y % 2 != 0: y = p - y   # ensure even y

# t = tweak scalar, Q = P + t*G (simplified: tweak x-coord only for output key)
t = int.from_bytes(tweak, 'big')
# output key x-coordinate (BIP-341 taproot output key)
# For simple keypath: output_key = x_only XOR tweaked (approximation shown)
output_key = x_only   # จริงๆ ต้องบวก tweak บนเส้นโค้ง

# Bech32m encode
CHARSET = "qpzry9x8gf2tvdw0s3jn54khce6mua7l"
def polymod(v):
    GEN=[0x3b6a57b2,0x26508e6d,0x1ea119fa,0x3d4233dd,0x2a1462b3]; c=1
    for d in v:
        b=c>>25; c=(c&0x1ffffff)<<5^d
        for i in range(5): c^=GEN[i] if (b>>i)&1 else 0
    return c
def cvt(data,f,t):
    acc=bits=0; ret=[]
    for v in data:
        acc=((acc<<f)|v)&0xfff; bits+=f
        while bits>=t: bits-=t; ret.append((acc>>bits)&((1<<t)-1))
    return ret
def bech32m(hrp,data):
    combined=data+[0]*6
    p=polymod([ord(x)>>5 for x in hrp]+[0]+[ord(x)&31 for x in hrp]+combined)^0x2bc830a3
    return hrp+'1'+''.join(CHARSET[d] for d in data)+''.join(CHARSET[(p>>(5*(5-i)))&31] for i in range(6))

address = bech32m('bc', [1] + cvt(output_key, 8, 5))
print("Bitcoin Address (bc1p...) approx:", address)
print("สำหรับ Taproot ที่แม่นยำ ใช้: https://iancoleman.io/bip39/")`;
                    pyNote = `<i class="fa-solid fa-circle-info text-warning me-1"></i> Taproot (BIP-341) มี TapTweak ซึ่งซับซ้อนมาก — แนะนำใช้ <a href="https://iancoleman.io/bip39/" target="_blank" style="color:var(--neon-blue);">iancoleman.io/bip39</a> เพื่อ Verify แทน`;
                }

                if (codeEl) codeEl.textContent = pyCode;
                if (noteEl) noteEl.innerHTML = pyNote;
            };

            btnStart.onclick = window.startRealSimulation;

        } catch (error) {
            console.error("Library Loading Failed:", error);
            document.getElementById('loadingSpinner').classList.add('d-none');
            document.getElementById('btnStartText').innerText = "โหลดข้อมูลล้มเหลว";
            errBox.classList.remove('d-none');
            errBox.innerText = "ไม่สามารถเชื่อมต่อไลบรารี Cryptography ได้ (" + error.message + ") โปรดตรวจสอบอินเทอร์เน็ต หรือปิดส่วนขยายเบราว์เซอร์ที่บล็อก CDN";
        }
    </script>
</body>
</html>