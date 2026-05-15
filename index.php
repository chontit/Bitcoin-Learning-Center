<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Learning Center by Chollatis Bitcoiner</title>
    
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&family=Kanit:wght@200;300;400&family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #050505;
            --card-bg: rgba(20, 20, 30, 0.6);
            --neon-blue: #00f3ff;
            --neon-green: #0aff00;
            --neon-orange: #ff9d00;
            --neon-purple: #bc13fe;
            --neon-gold: #ffd700;
            --neon-red: #ff2a2a;
            --text-main: #e0e0e0;
            --text-muted: #888;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(255, 157, 0, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 243, 255, 0.05), transparent 25%);
            color: var(--text-main);
            font-family: 'Kanit', 'Rajdhani', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            pointer-events: none;
        }

        header {
            text-align: center;
            padding: 70px 20px 20px;
            animation: fadeInDown 1s ease-out;
            max-width: 900px;
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            background: linear-gradient(90deg, #ff9d00, #fff, #ff9d00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 25px rgba(255, 157, 0, 0.4);
            line-height: 1.2;
        }

        p.subtitle {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 1.3rem; 
            font-weight: 400;
            color: var(--neon-blue);
            letter-spacing: 1px;
            opacity: 1;
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            width: 100%;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .section-title {
            grid-column: 1 / -1;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            color: var(--neon-green);
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(10, 255, 0, 0.2);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .section-title span {
            background: rgba(10, 255, 0, 0.1);
            padding: 2px 10px;
            border-radius: 4px;
            margin-right: 10px;
            font-size: 0.8em;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 3px; height: 100%;
            background: var(--neon-blue);
            opacity: 0.6;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
            border-color: var(--neon-blue);
            box-shadow: 0 10px 30px rgba(0, 243, 255, 0.1);
        }

        .card:hover::before {
            box-shadow: 0 0 15px var(--neon-blue);
            opacity: 1;
        }

        .card h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: #fff;
        }

        .card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
            font-family: 'Kanit', sans-serif; 
            font-weight: 300;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: transparent;
            color: var(--neon-blue);
            text-decoration: none;
            border: 1px solid var(--neon-blue);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem;
            text-align: center;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
            align-self: flex-start;
        }

        .btn::after {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 0%; height: 100%;
            background: var(--neon-blue);
            z-index: -1;
            transition: 0.3s;
        }

        .card:hover .btn { color: #000; }
        .card:hover .btn::after { width: 100%; }

        /* Theme Variations */
        .trading-section { color: var(--neon-purple); border-color: rgba(188, 19, 254, 0.3); }
        .trading-section span { background: rgba(188, 19, 254, 0.1); }
        .trading-card::before { background: var(--neon-purple); }
        .trading-card:hover { border-color: var(--neon-purple); box-shadow: 0 10px 30px rgba(188, 19, 254, 0.15); }
        .trading-card .btn { color: var(--neon-purple); border-color: var(--neon-purple); }
        .trading-card .btn::after { background: var(--neon-purple); }

        /* Strategy/Nice to know Theme (Gold/Orange) */
        .strategy-section { color: var(--neon-gold); border-color: rgba(255, 215, 0, 0.3); }
        .strategy-section span { background: rgba(255, 215, 0, 0.1); }
        .strategy-card::before { background: var(--neon-gold); }
        .strategy-card:hover { border-color: var(--neon-gold); box-shadow: 0 10px 30px rgba(255, 215, 0, 0.15); }
        .strategy-card .btn { color: var(--neon-gold); border-color: var(--neon-gold); }
        .strategy-card .btn::after { background: var(--neon-gold); }

        /* Security/Custody Theme (Neon Red) */
        .security-section { color: var(--neon-red); border-color: rgba(255, 42, 42, 0.3); }
        .security-section span { background: rgba(255, 42, 42, 0.1); }
        .security-card::before { background: var(--neon-red); }
        .security-card:hover { border-color: var(--neon-red); box-shadow: 0 10px 30px rgba(255, 42, 42, 0.15); }
        .security-card .btn { color: var(--neon-red); border-color: var(--neon-red); }
        .security-card .btn::after { background: var(--neon-red); }

        /* Utilities/Tools Theme (Neon Blue/Cyan) */
        .utility-section { color: var(--neon-blue); border-color: rgba(0, 243, 255, 0.3); }
        .utility-section span { background: rgba(0, 243, 255, 0.1); }
        .utility-card::before { background: var(--neon-blue); }
        .utility-card:hover { border-color: var(--neon-blue); box-shadow: 0 10px 30px rgba(0, 243, 255, 0.15); }
        .utility-card .btn { color: var(--neon-blue); border-color: var(--neon-blue); }
        .utility-card .btn::after { background: var(--neon-blue); }

        .future-card {
            border: 1px dashed rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.3);
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 200px;
            cursor: default;
        }
        .future-card:hover {
            transform: none;
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: none;
        }
        .future-card::before { display: none; }
        .future-card h3 { color: #555; font-size: 1.1rem; }
        .future-card span { font-size: 2rem; display: block; margin-bottom: 10px; color: #333; }

        footer {
            margin-top: auto;
            padding: 30px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
            width: 100%;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            h1 { font-size: 1.8rem; }
            header { padding-top: 40px; }
            .container { padding: 15px; }
        }
    </style>
</head>
<body>

    <header>
        <h1>Bitcoin Learning Center<br><span style="font-size:0.6em; color: white;">by Chollatis Bitcoiner</span></h1>
        <p class="subtitle">พื้นที่สำหรับเรียนรู้บิตคอยน์ด้วยตนเอง</p>
    </header>

    <div class="container">
        
        <div class="section-title"><span>LEARNING</span> BITCOIN PROTOCOL & CRYPTOGRAPHY</div>

        <div class="card">
            <h3>Bitcoin 101</h3>
            <p>ปูพื้นฐานทำความเข้าใจ Bitcoin ตั้งแต่ศูนย์ ตอบคำถามว่ามันคืออะไร ทำงานอย่างไร และทำไมถึงสำคัญต่อโลกการเงิน</p>
            <a href="bitcoin101.php" class="btn">START LEARNING</a>
        </div>

        <div class="card">
            <h3>Mining Simulator</h3>
            <p>จำลองการขุด Bitcoin เรียนรู้ Proof-of-Work, Hash Difficulty และการทำงานของ Block</p>
            <a href="bitcoin/miner.php" class="btn">LAUNCH SIMULATOR</a>
        </div>

        <div class="card">
            <h3>Bitcoin Mining Mechanics</h3>
            <p>เจาะลึกกระบวนการขุด (Mining) และกลไกการทำงานเบื้องหลังความปลอดภัยของเครือข่าย</p>
            <a href="bitcoin/mining.php" class="btn">EXPLORE MINING</a>
        </div>

        <div class="card">
            <h3>Issuance Simulator</h3>
            <p>จำลองกลไกการออกเหรียญ (Issuance) เรียนรู้ปรากฏการณ์ Halving และเส้นทางสู่ 21 ล้านเหรียญ</p>
            <a href="bitcoin/issuance_simulator.php" class="btn">SIMULATE HALVING</a>
        </div>

        <div class="card">
            <h3>2^256 The Immeasurable</h3>
            <p>ทำความเข้าใจขนาดของตัวเลข 2 ยกกำลัง 256 หัวใจหลักความปลอดภัยของ Private Key</p>
            <a href="bitcoin/binary.php" class="btn">VISUALIZE DATA</a>
        </div>

        <div class="card">
            <h3>SHA-256 Monitor</h3>
            <p>เครื่องมือทดสอบการเข้ารหัส SHA-256 แบบ Real-time Responsive</p>
            <a href="bitcoin/hashing.php" class="btn">ACCESS TERMINAL</a>
        </div>

        <div class="card">
            <h3>UTXO Visualizer</h3>
            <p>เรียนรู้ระบบบัญชีของ Bitcoin แบบ UTXO (Unspent Transaction Output) ที่แตกต่างจากบัญชีธนาคารทั่วไป</p>
            <a href="bitcoin/utxo.php" class="btn">EXPLORE UTXO</a>
        </div>

        <div class="card">
            <h3>BIP39 Mnemonic Code</h3>
            <p>เรียนรู้มาตรฐาน BIP39 การสร้าง Seed Phrase 12/24 คำ และกลไกการแปลงคำศัพท์ให้กลายเป็น Private Key</p>
            <a href="bitcoin/bip39.php" class="btn">GENERATE KEYS</a>
        </div>

        <div class="card">
            <h3>HD Wallet (BIP32/44)</h3>
            <p>เรียนรู้โครงสร้างกระเป๋าเงินแบบ Hierarchical Deterministic และการแตกกิ่งของกุญแจย่อยจาก Seed Phrase เพียงชุดเดียว</p>
            <a href="bitcoin/hd-wallet.php" class="btn">DERIVE KEYS</a>
        </div>

        <div class="card">
            <h3>ECDSA & Public Key</h3>
            <p>เรียนรู้คณิตศาสตร์ Elliptic Curve (secp256k1) เบื้องหลังการสร้าง Public Key และการเซ็นลายเซ็นดิจิทัล</p>
            <a href="bitcoin/ecdsa.php" class="btn">EXPLORE CURVE</a>
        </div>

        <div class="card">
            <h3>Digital Signature</h3>
            <p>จำลองการสร้างและตรวจสอบลายเซ็นดิจิทัล (Digital Signatures) เพื่อยืนยันความเป็นเจ้าของและป้องกันการปลอมแปลง</p>
            <a href="bitcoin/digital_signature.php" class="btn">SIGN MESSAGE</a>
        </div>

        <div class="card">
            <h3>Transaction Flow</h3>
            <p>จำลองวงจรชีวิตของธุรกรรม (Transaction) ตั้งแต่การสร้าง, เซ็นชื่อ, ส่งเข้า Mempool จนถึงการบรรจุลง Block</p>
            <a href="bitcoin/tx_flow_simulator.php" class="btn">SIMULATE TX</a>
        </div>

        <div class="card">
            <h3>Lightning Network (L2)</h3>
            <p>จำลองการทำงานของเครือข่าย Lightning การเปิด Payment Channel และการส่งบิตคอยน์แบบสายฟ้าแลบ</p>
            <a href="bitcoin/lightning.php" class="btn">EXPLORE LIGHTNING</a>
        </div>

        <div class="section-title trading-section"><span>MARKET</span> TRADING TOOLS</div>

        <div class="card trading-card">
            <h3>Position Sizing Calculator</h3>
            <p>เครื่องมือคำนวณขนาดไม้ (Size) บริหารความเสี่ยง รองรับทั้ง Long และ Short Position</p>
            <a href="trade/index.php" class="btn">OPEN CALCULATOR</a>
        </div>

        <div class="card trading-card">
            <h3>Trading Journey</h3>
            <p>บันทึกและติดตามประวัติการเทรด (Trading Journal) วิเคราะห์สถิติ Win Rate เพื่อพัฒนาและปรับปรุงกลยุทธ์ของคุณ</p>
            <a href="trade/trading-journey.php" class="btn">TRACK JOURNEY</a>
        </div>

        <div class="section-title strategy-section"><span>INSIGHTS</span> STRATEGIC INSIGHTS & ECONOMICS</div>

        <div class="card strategy-card">
            <h3>Loan & Financial Plan</h3>
            <p>คำนวณดอกเบี้ยและแผนการผ่อนชำระสำหรับเงินกู้ เพื่อการวางแผนทางการเงินและต้นทุน Leverage</p>
            <a href="strategic/loan_calculator.php" class="btn">CALCULATE LOAN</a>
        </div>

        <div class="card strategy-card">
            <h3>Loan Simulation</h3>
            <p>จำลองสถานการณ์การกู้ยืมเงิน เปรียบเทียบแผนการผ่อนชำระ และวิเคราะห์ต้นทุนดอกเบี้ยอย่างละเอียด</p>
            <a href="strategic/loan_simulation.php" class="btn">SIMULATE LOAN</a>
        </div>

        <div class="card strategy-card">
            <h3>Money Printing & Inflation</h3>
            <p>เรียนรู้กลไกการพิมพ์เงินของธนาคารกลาง (Fiat Currency) และผลกระทบที่ทำให้มูลค่าเงินลดลงตามกาลเวลา</p>
            <a href="strategic/money_printing.php" class="btn">START PRINTING</a>
        </div>

        <div class="card strategy-card">
            <h3>Bitcoin Saving & DCA</h3>
            <p>จำลองผลตอบแทนการออม Bitcoin ระยะยาว และพลังของ Dollar-Cost Averaging (DCA)</p>
            <a href="strategic/saving.php" class="btn">START SAVING</a>
        </div>

        <div class="card strategy-card">
            <h3>Investment Scenarios</h3>
            <p>จำลองและวิเคราะห์ฉากทัศน์การลงทุน (Investment Scenarios) เพื่อเปรียบเทียบผลตอบแทนและความเสี่ยงในรูปแบบต่างๆ</p>
            <a href="strategic/investment.php" class="btn">ANALYZE RETURN</a>
        </div>

        <div class="section-title security-section"><span>VAULT</span> SECURITY & SELF-CUSTODY</div>

        <div class="card security-card">
            <h3>Self-Custody Guide</h3>
            <p>จำลองระบบการเก็บรักษาและบริหารจัดการกระเป๋าเงิน (Wallet) พร้อมทำความเข้าใจหลักการ "Not your keys, not your coins"</p>
            <a href="bitcoin/self-custody.php" class="btn">SECURE YOUR WEALTH</a>
        </div>

        <div class="section-title utility-section"><span>UTILITIES</span> TOOLS & EXTRAS</div>

        <!-- UPDATED CARD: QR Code Generator -->
        <div class="card utility-card">
            <h3>QR Code Generator</h3>
            <p>เครื่องมือสร้าง QR Code อเนกประสงค์ แปลงลิงก์เว็บไซต์ (URL) หรือข้อความทั่วไปให้กลายเป็นรูป QR Code พร้อมนำไปใช้งานต่อได้ทันที</p>
            <a href="tools/qr_generator.html" class="btn">GENERATE QR</a>
        </div>

        <div class="section-title" style="color: #555; border-color: #333;"><span>FUTURE</span> IN DEVELOPMENT</div>
        
        <div class="card future-card">
            <div>
                <span>+</span>
                <h3>Future Project Slot</h3>
                <p style="margin-bottom:0; font-size: 0.8rem;">More tools are being developed...</p>
            </div>
        </div>

    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Chollatis Bitcoiner. | Don't Trust, Verify.</p>
        <p style="margin-top:5px; font-size: 0.7em; opacity: 0.5;">Powered by Bitcoin Protocol & PHP <?php echo phpversion(); ?></p>
    </footer>

</body>
</html>