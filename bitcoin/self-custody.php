<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Self-Custody Decision | by Chollatis Bitcoiner</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-red: #ff0055;
            --neon-yellow: #ffee00;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(247, 147, 26, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 255, 65, 0.05) 0%, transparent 25%);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .brand-font { font-family: 'Orbitron', sans-serif; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: var(--neon-btc); border-radius: 4px; }

        .neon-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .btn-yes {
            background: linear-gradient(45deg, #00ff41, #00b32d);
            color: #000;
            box-shadow: 0 0 10px rgba(0, 255, 65, 0.3);
        }
        .btn-yes:hover {
            box-shadow: 0 0 20px #00ff41;
            transform: translateY(-2px);
        }

        .btn-no {
            background: linear-gradient(45deg, #ff0055, #cc0044);
            color: #fff;
            box-shadow: 0 0 10px rgba(255, 0, 85, 0.3);
        }
        .btn-no:hover {
            box-shadow: 0 0 20px #ff0055;
            transform: translateY(-2px);
        }

        .btn-action {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
            border-radius: 0.5rem;
            padding: 1rem 2rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modal / Balloon */
        #balloon_modal {
            backdrop-filter: blur(5px);
        }

        .star-rating i {
            color: #444; 
        }
        .star-rating i.active {
            color: var(--neon-btc);
            text-shadow: 0 0 10px var(--neon-btc);
        }
        .star-rating i.half {
            background: linear-gradient(90deg, var(--neon-btc) 50%, #444 50%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .detail-list li {
            margin-bottom: 0.5rem;
            position: relative;
            padding-left: 1.25rem;
        }
        .detail-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: currentColor;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-40 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-brands fa-bitcoin text-4xl text-[#F7931A] animate-pulse"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#ffee00] brand-font">
                    SELF-CUSTODY
                </h1>
                <p class="text-xs text-gray-400">Decision Tree สำหรับการเก็บ Bitcoin ด้วยตนเอง</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm">
                <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
            </a>
            <button onclick="resetFlow()" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm">
                <i class="fa-solid fa-rotate-left"></i> <span class="hidden md:inline">Reset</span>
            </button>
        </div>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6 flex flex-col items-center justify-center relative">
        
        <div id="question_section" class="w-full max-w-2xl neon-box p-8 md:p-12 rounded-2xl text-center fade-in">
            <div class="mb-8">
                <i id="q_icon" class="fa-solid fa-circle-question text-5xl text-[#F7931A] mb-6 drop-shadow-[0_0_15px_rgba(247,147,26,0.5)]"></i>
                <h2 id="q_text" class="text-2xl md:text-3xl font-bold text-white leading-relaxed">
                    </h2>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="handleAnswer('yes')" class="btn-action btn-yes">
                    <i class="fa-solid fa-check"></i> ใช่ (Yes)
                </button>
                <button onclick="handleAnswer('no')" class="btn-action btn-no">
                    <i class="fa-solid fa-xmark"></i> ไม่ (No)
                </button>
            </div>

            <div class="mt-8 flex justify-center hidden" id="back_btn_container">
                <button onclick="goBack()" class="text-gray-400 hover:text-[#F7931A] transition flex items-center gap-2 text-sm underline">
                    <i class="fa-solid fa-arrow-left"></i> ย้อนกลับข้อก่อนหน้า
                </button>
            </div>
        </div>

        <div id="result_section" class="w-full max-w-5xl hidden fade-in">
            
            <div class="neon-box p-6 md:p-10 rounded-2xl mb-6 text-center border-t-4 border-[#00ff41]" id="result_header_box">
                <h3 class="text-gray-400 text-sm tracking-widest uppercase mb-2">ข้อสรุปแนวทางที่เหมาะสมกับคุณ</h3>
                <h2 id="result_title" class="text-2xl md:text-3xl font-bold mb-4">
                    </h2>
                <p id="result_desc" class="text-gray-300 text-sm md:text-base bg-black/40 p-4 rounded-lg border border-gray-800 inline-block max-w-3xl">
                    </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="neon-box p-6 rounded-xl border-t-2 border-[#00ff41] bg-gradient-to-b from-[rgba(0,255,65,0.05)] to-transparent">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa-solid fa-shield-check text-2xl text-[#00ff41]"></i>
                        <h3 class="text-lg font-bold text-[#00ff41]">ความปลอดภัย</h3>
                    </div>
                    <ul id="result_sec_detail" class="text-sm text-gray-300 detail-list text-[#00ff41]">
                        </ul>
                </div>

                <div class="neon-box p-6 rounded-xl border-t-2 border-[#F7931A] bg-gradient-to-b from-[rgba(247,147,26,0.05)] to-transparent">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa-solid fa-bullseye text-2xl text-[#F7931A]"></i>
                        <h3 class="text-lg font-bold text-[#F7931A]">จุดอ่อนที่ต้องระวัง</h3>
                    </div>
                    <ul id="result_weakness" class="text-sm text-gray-300 detail-list text-[#F7931A]">
                        </ul>
                </div>

                <div class="neon-box p-6 rounded-xl border-t-2 border-[#ff0055] bg-gradient-to-b from-[rgba(255,0,85,0.05)] to-transparent">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-[#ff0055]"></i>
                        <h3 class="text-lg font-bold text-[#ff0055]">ความเสี่ยง (Risks)</h3>
                    </div>
                    <ul id="result_risks" class="text-sm text-gray-300 detail-list text-[#ff0055]">
                        </ul>
                </div>
            </div>

            <div class="neon-box p-6 rounded-2xl bg-black/60 mb-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-[#F7931A]"></i> ตารางเปรียบเทียบระดับความปลอดภัย (ภาพรวม)
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                            <tr>
                                <th class="px-4 py-3">รูปแบบการเก็บ (Custody Method)</th>
                                <th class="px-4 py-3 text-center">คะแนน (เต็ม 5)</th>
                                <th class="px-4 py-3 min-w-[120px]">Rating</th>
                            </tr>
                        </thead>
                        <tbody id="security_table" class="divide-y divide-gray-800">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-center mb-8 gap-4">
                <button onclick="goBackFromResult()" class="btn-action bg-gray-800 hover:bg-gray-700 text-white transition border border-gray-600 sm:max-w-xs">
                    <i class="fa-solid fa-arrow-left"></i> เปลี่ยนคำตอบล่าสุด
                </button>
                <button onclick="resetFlow()" class="btn-action bg-gray-800 hover:bg-gray-700 text-white transition border border-gray-600 sm:max-w-xs">
                    <i class="fa-solid fa-arrow-rotate-left"></i> ทำแบบประเมินอีกครั้ง
                </button>
            </div>
        </div>

    </main>

    <div id="balloon_modal" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="neon-box bg-gray-900 p-8 rounded-2xl max-w-md w-full text-center mx-4 border-l-4 border-[#F7931A] transform scale-95 transition-transform duration-300" id="balloon_content">
            <i class="fa-solid fa-lightbulb text-4xl text-[#F7931A] mb-4 animate-bounce"></i>
            <h3 class="text-xl font-bold text-white mb-2">คำแนะนำเพิ่มเติม</h3>
            <p id="balloon_text" class="text-gray-300 mb-6 text-sm md:text-base"></p>
            <button onclick="closeBalloon()" class="bg-[#F7931A] hover:bg-[#e08516] text-black font-bold py-2 px-6 rounded-lg transition w-full">
                รับทราบ และดำเนินการต่อ
            </button>
        </div>
    </div>

    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-auto bg-black/30 backdrop-blur-sm">
        <p>© 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner.</span> <span class="mx-2 text-gray-700">|</span> Not your keys, not your coins.</p>
    </footer>

    <script>
        // Data Structure สำหรับ Security Ratings
        const securityRatings = [
            { method: "เก็บใน Exchange", score: 0, desc: "Not your key, not your coin. บิตคอยน์นั้นยัง 'ไม่ใช่' ของคุณ และมีความเสี่ยงจากแพลตฟอร์ม 'ล่ม'" },
            { method: "Hardware Wallet (เชื่อมต่อผ่าน Bluetooth ร่วมกับ Smart Phone) + Single Signature", score: 2.5, desc: "สะดวก แต่มีความเสี่ยงจากความเสถียรของระบบ Bluetooth และมัลแวร์บนมือถือ" },
            { method: "Hardware Wallet (เชื่อมต่อผ่าน USB ร่วมกับ PC) + Software Wallet พื้นฐานของผู้ผลิต + Single Signature", score: 3.0, desc: "ความปลอดภัยมาตรฐาน เริ่มต้นได้ง่าย" },
            { method: "Hardware Wallet (เชื่อมต่อผ่าน USB ร่วมกับ PC) + Software Wallet พื้นฐานของผู้ผลิต + Single Signature + Passphrase", score: 4.0, desc: "มีรหัสผ่านเสริม (เปรียบเสมือน Seedphrase คำที่ 25) ช่วยป้องกันกรณีอุปกรณ์โดนขโมยได้เป็นอย่างดี" },
            { method: "Air-Gapped Hardware Wallet + Software Wallet ขั้นสูง + Single Signature", score: 4.2, desc: "ไร้การเชื่อมต่อกับอินเทอร์เน็ต ลดโอกาสโดนแฮ็กทางไซเบอร์อย่างสมบูรณ์แบบ" },
            { method: "Air-Gapped Hardware Wallet + Software Wallet ขั้นสูง + Single Signature + Passphrase", score: 4.5, desc: "รวมข้อดีของ Air-Gap และการใช้รหัสผ่านเสริม (เปรียบเสมือน Seedphrase คำที่ 25) ช่วยป้องกันกรณีอุปกรณ์โดนขโมยได้เป็นอย่างดี" },
            { method: "Multi-Signature Wallet (แบบ 2-of-3 หรือ 3-of-5) โดยไม่มี Passphrase", score: 4.8, desc: "กระจายความเสี่ยง (ไร้ปัญหาความเสี่ยงจุดเดียว หรือ Single Point of Failure) เนื่องจากต้องใช้อุปกรณ์หลายตัวร่วมกันในการทำธุรกรรมบิตคอยน์" },
            { method: "Multi-Signature Wallet + Passphrase", score: 5.0, desc: "สูงสุดระดับสถาบัน ไม่มีปัญหาความเสี่ยงจุดเดียว (Single Point of Failure) และปกป้องตัวอุปกรณ์ด้วยรหัสผ่านเสริม **แต่ต้องระวังลืม Passphrase**" }
        ];

        // ระบบคำถาม (Decision Logic)
        const questions = {
            q_start: {
                text: "ต้องการเก็บบิตคอยน์ด้วยตนเอง (Self-Custody) ใช่มั้ย ?",
                icon: "fa-wallet",
                yes: "q_has_pc",
                no: "q_afraid_lose"
            },
            q_afraid_lose: {
                text: "กลัวบิตคอยน์หายหรือไม่ ?",
                icon: "fa-ghost",
                yes: "q_start", 
                no: "res_exchange"
            },
            q_has_pc: {
                text: "มีอุปกรณ์คอมพิวเตอร์ 'ส่วนตัว' ไว้ใช้งานหรือไม่ ?",
                icon: "fa-laptop",
                yes: "q_pc_skill",
                no: "q_max_sec_no_pc"
            },
            q_max_sec_no_pc: {
                text: "ต้องการให้การเก็บบิตคอยน์มีความปลอดภัย 'สูงสุด' หรือไม่ ?",
                icon: "fa-shield-virus",
                yes: "action_buy_pc", 
                no: "res_bluetooth"
            },
            q_pc_skill: {
				text: "มีพื้นฐานด้านคอมพิวเตอร์ (ระดับปานกลาง, ติดตั้งโปรแกรม หรือแก้ไขปัญหาคอมพิวเตอร์ง่าย ๆ ได้) หรือไม่ ?",
                icon: "fa-code",
                yes: "q_advance",
                no: "q_max_sec_low_skill"
            },
            q_max_sec_low_skill: {
                text: "ยังต้องการเก็บบิตคอยน์ให้ปลอดภัยแบบสุด ๆ หรือไม่ ?",
                icon: "fa-shield-halved",
                yes: "action_study_software", 
                no: "res_usb_basic"
            },
            q_advance: {
                text: "ต้องการเก็บบิตคอยน์ขั้น Advance หรือไม่ ? (เช่น Multisig Wallet / Passphrase)",
                icon: "fa-microchip",
                yes: "q_forget_pass",
                no: "res_airgap_single"
            },
            q_forget_pass: {
                text: "มีความกังวลว่าจะลืม Passphrase หรือไม่ ?",
                icon: "fa-brain",
                yes: "res_multisig_nopass",
                no: "q_max_sec_adv"
            },
            q_max_sec_adv: {
                text: "ต้องการปลอดภัยขั้นสุด (Maximum Security) มั้ย ?",
                icon: "fa-lock",
                yes: "res_multisig_pass",
                no: "res_single_pass"
            }
        };

        // ข้อมูลผลลัพธ์แบบเจาะลึก
        // ปรับสี color ตามเกณฑ์คะแนน (0 = แดง, 1-2.5 = ส้ม, 2.6-4.0 = เหลือง, 4.1-5 = เขียว)
        const results = {
            res_exchange: {
                title: "ซื้อแล้วเก็บไว้ใน App Exchange ต่อไป",
                desc: "บิตคอยน์นั้นยัง 'ไม่ใช่' ของคุณอย่างแท้จริง หากคุณรับความเสี่ยงกรณีแพลตฟอร์มปิดตัวได้ ,, นี่คือวิธีที่ง่ายที่สุด",
                color: "#ff0055", // 0 = แดง
                score_idx: 0,
                sec_detail: ["ไม่ต้องดูแลกุญแจส่วนตัว (Private Key) ด้วยตัวเอง", "มีระบบยืนยันตัวตนแบบสองขั้นตอน (Two-Factor Authentication) และกระบวนการทำความรู้จักลูกค้า (Know Your Customer) ของแพลตฟอร์มคอยป้องกันคนอื่นเข้าถึงบัญชี", "ง่ายต่อการซื้อขายและแปลงเป็นเงินสดอย่างรวดเร็ว"],
                weakness: ["Not your keys, not your coins เรายังไม่ใช่เจ้าของบิตคอยน์", "อาจถูกระงับบัญชี (Freeze) ได้ตลอดเวลาหากมีปัญหาทางกฎหมาย", "ต้องพึ่งพาและเชื่อใจ (Trust) แพลตฟอร์มเต็ม 100%"],
                risks: ["Exchange ล้มละลายหรือโดนโกง (เช่น กรณี FTX, Mt.Gox, Zipmex)", "บัญชีอาจถูกแฮ็กจากการที่ผู้ใช้งานโดนขโมยรหัสผ่านหรือ Phishing", "นโยบายของรัฐเปลี่ยนแปลงทำให้ถอนเงิน/เหรียญไม่ได้"]
            },
            res_bluetooth: {
                title: "ใช้ Hardware Wallet ร่วมกับ Smart Phone (เชื่อมต่อผ่านระบบ Bluetooth)",
                desc: "ต้องเลือก Hardware Wallet รุ่นที่รองรับการเชื่อมต่อผ่าน Bluetooth และเก็บแบบ Single Signature Wallet ซึ่งสะดวก แต่อาจมีความเสี่ยงจากมือถือเล็กน้อย",
                color: "#F7931A", // 2.5 = ส้ม
                score_idx: 1,
                sec_detail: ["Private Key ถูกเก็บออฟไลน์ในชิปความปลอดภัย (Secure Element) ของตัวอุปกรณ์", "ใช้งานง่ายผ่านแอปบนมือถือ เหมาะกับไลฟ์สไตล์ปัจจุบัน", "หากมือถือพัง เหรียญก็ไม่หาย เพราะกุญแจอยู่ใน Hardware Wallet"],
                weakness: ["เป็นการเก็บแบบมีจุดอ่อนจุดเดียว (Single Point of Failure) ถ้ากระดาษจด Seed รั่วไหลคือจบ", "การส่งข้อมูลผ่าน Bluetooth แม้เข้ารหัสแต่ก็มีโอกาสถูกดักจับข้อมูลได้ (ในทางทฤษฎี)"],
                risks: ["สมาร์ทโฟนมีโอกาสติดมัลแวร์หรือแอปปลอมได้ง่ายกว่าคอมพิวเตอร์เฉพาะทาง", "คนใกล้ตัวแอบดูกดรหัส PIN บน Hardware Wallet หรือมือถือ", "เก็บรักษากระดาษจด Seed Phrase ไม่ดีจนสูญหายหรือถูกขโมย"]
            },
            res_usb_basic: {
                title: "ใช้ USB Hardware Wallet + Sofware Wallet พื้นฐานของทางผู้ผลิตอุปกรณ์ (รุ่นเริ่มต้น)",
                desc: "เชื่อมต่อผ่าน USB เข้าคอมพิวเตอร์ ใช้งานร่วมกับ Software ของผู้ผลิต เหมาะสำหรับผู้เริ่มต้นการเก็บบิตคอยน์ด้วยตนเองอย่างยิ่ง",
                color: "#ffee00", // 3.0 = เหลือง
                score_idx: 2,
                sec_detail: ["เชื่อมต่อผ่านสาย USB ทำให้ไม่มีการแผ่สัญญาณไร้สายให้ถูกดักจับ", "กุญแจอยู่ในอุปกรณ์เสมอ คอมพิวเตอร์ไม่สามารถดึง Private Key ออกไปได้", "หน้าจอคอมพิวเตอร์ใหญ่และตรวจสอบ Address ได้ง่ายกว่ามือถือ"],
                weakness: ["มีความเสี่ยงจากจุดอ่อนจุดเดียว (Single Point of Failure) เนื่องจากอาศัยกระดาษจด Seed Phrase แผ่นเดียว", "Software ของผู้ผลิตบางค่ายอาจติดตามข้อมูลความเป็นส่วนตัว (Privacy Data) ส่งกลับไปยังเซิร์ฟเวอร์", "ต้องพึ่งพาคอมพิวเตอร์ซึ่งอาจมีไวรัสหรือมัลแวร์"],
                risks: ["เครื่องติดไวรัสประเภทขโมยข้อมูลการคัดลอก (Clipboard Hijacker) แอบเปลี่ยน Address ปลายทางตอน Copy-Paste", "การเก็บ Seed แบบแผ่นเดียวเสี่ยงต่อภัยพิบัติต่าง ๆ (ไฟไหม้, น้ำท่วม, ปลวก)", "Hardware Wallet สูญหายหรือถูกขโมย แล้วขโมยสามารถคาดเดา PIN ถูก (คนใกล้ตัว)"]
            },
            res_airgap_single: {
                title: "Air-Gapped Hardware Wallet + Software Wallet ขั้นสูง",
                desc: "ใช้ Hardware Wallet โดยไม่มีการเชื่อมต่อกับคอมพิวเตอร์โดยตรง (ใช้สแกน QR Code / ส่งไฟล์ผ่าน SD Card) ร่วมกับ Software Wallet อย่างเช่น Sparrow Wallet",
                color: "#00ff41", // 4.2 = เขียว
                score_idx: 4,
                sec_detail: ["อุปกรณ์ไม่เคยถูกเชื่อมต่อทางกายภาพกับคอมที่มีอินเทอร์เน็ต (Air-Gapped) ตัดปัญหาไวรัสเจาะทางสาย USB", "ใช้ Software ขั้นสูง (Sparrow/Electrum) ทำให้เราสามารถเชื่อมต่อเซิร์ฟเวอร์ (Node) ของตัวเอง เพิ่มความเป็นส่วนตัวขั้นสุด", "กุญแจออฟไลน์แบบ 100% ปลอดภัยจากการโจมตีระยะไกล"],
                weakness: ["ขั้นตอนการทำธุรกรรม (สแกน QR Code ไปมา หรือบันทึกข้อมูลลง SD Card) ค่อนข้างยุ่งยาก ซับซ้อน ไม่เหมาะกับมือใหม่", "ยังคงเป็นระบบ Single Signature ซึ่งมีความเสี่ยงจากจุดอ่อนจุดเดียว (Single Point of Failure)"],
                risks: ["หากใช้ SD Card ก็ยังมีโอกาสที่มัลแวร์จะซ่อนมาใน Card ได้ (ทำลายกฎ Air-Gap ทางอ้อม)", "ผู้ใช้ทำผิดพลาดเองระหว่างโอน (Human Error)", "ความเสี่ยงเดิมๆ คือการจด Seed Phrase ชุดเดียวสูญหายหรือถูกขโมย"]
            },
            res_single_pass: {
                title: "Single Signature Wallet + Passphrase",
                desc: "ใช้ Hardware Wallet ตามปกติ แต่เพิ่มรหัสผ่านเฉพาะ (Passphrase) เข้าไป เพื่อกำหนดเป็นเงื่อนไขเพิ่มเติมในการยืนยันธุรกรรมหรือโอนบิตคอยน์",
                color: "#ffee00", // 4.0 = เหลือง 
                score_idx: 3, 
                sec_detail: ["สามารถป้องกันภัยคุกคามทางกายภาพ (Physical Attack) ได้ดี เช่น โจรขโมย Hardware หรือขโมยกระดาษจด Seed ไป ก็ยังไม่สามารถโอนบิตคอยน์ได้", "สามารถทำกระเป๋าหลอก (Decoy Wallet) เอาไว้ตบตาโจรได้", "แม้ผู้ผลิตจะบังเอิญทำกุญแจหลุดจากขั้นตอนการผลิต (Supply Chain Attack) เหรียญเราก็ยังรอด"],
                weakness: ["Passphrase ไม่ได้ถูกจัดเก็บไว้ในอุปกรณ์ ต้องจำหรือจดแยกต่างหาก", "ยังเป็นการพึ่งพาอุปกรณ์เพียง 1 เครื่องในการทำงาน (มีความเสี่ยงแบบ Single Point of Failure ด้าน Hardware)"],
                risks: ["ลืม Passphrase หรือพิมพ์ผิดตอนสร้างกระเป๋าครั้งแรก (พิมพ์ผิด 1 ตัวอักษรคือกระเป๋าคนละใบเลย)", "มีอันเป็นไปกะทันหันแล้วทายาทไม่รู้ Passphrase ทำให้การเข้าถึงเหรียญเป็นไปไม่ได้", "หากเก็บ Seed กับ Passphrase ไว้ที่เดียวกัน โจรได้ไปก็จบเกม"]
            },
            res_multisig_nopass: {
                title: "Multisig Wallet แบบ 2-of-3 หรือ 3-of-5 (No Passphrase)",
                desc: "กระจายความเสี่ยงด้วยกระเป๋าแบบ Multi-signature ซึ่งจำเป็นต้องใช้อุปกรณ์หลายตัวเซ็นร่วมกัน เหมาะกับคนกลัวลืมคำที่ 25 แต่ยังได้ความปลอดภัยที่สูงมากพอ",
                color: "#00ff41", // 4.8 = เขียว
                score_idx: 6,
                sec_detail: ["กำจัดความเสี่ยงจากการมีจุดอ่อนจุดเดียว (Single Point of Failure) โดยสมบูรณ์ การขโมยอุปกรณ์หรือ Seed ไปเพียงแค่ 1 ชุดก็ไม่สามารถทำอะไรได้", "ไม่ต้องจำหรือจด Passphrase ซึ่งช่วยลดความเสี่ยงจากการลืมรหัสผ่านของตัวเอง", "ระดับความปลอดภัยมาตรฐานเดียวกับกองทุนหรือสถาบันการเงินใช้"],
                weakness: ["มีความซับซ้อนสูงมาก ต้องบริหารจัดการ Hardware หลายตัว", "ต้องทำการสำรองไฟล์การตั้งค่ากระเป๋า (Wallet Descriptor / xpub file) ไว้ให้ดี หากไฟล์นี้หาย แม้มี Seed ครบก็อาจจะหาเหรียญไม่เจอ"],
                risks: ["Seed Phrase สูญหายพร้อมกันหลายชุดจนมีไม่ถึงเกณฑ์ขั้นต่ำ (เช่น หาย 2 ใน 3)", "ทายาทหรือคนข้างหลังไม่เข้าใจวิธีการกู้คืนระบบ Multisig Wallet", "ไม่เคยซ้อมการกู้คืน (Recovery Test) เมื่อเกิดเหตุฉุกเฉินจริงจึงทำไม่เป็น"]
            },
            res_multisig_pass: {
                title: "Multisig Wallet + Passphrase (ทุกอุปกรณ์)",
                desc: "ความปลอดภัยระดับสูงสุดของมนุษยชาติ! ปกป้องการเข้าถึงตัว Hardware Wallet แต่ละอันด้วยรหัสผ่านอีกชั้น",
                color: "#00ff41", // 5.0 = เขียว
                score_idx: 7,
                sec_detail: ["เกราะป้องกันสมบูรณ์แบบ แฮ็กเกอร์ต้องได้ทั้ง Hardware Wallet (หลายตัว) และ Passphrase (หลายชุด) ถึงจะสามารถโอนเงินได้", "แม้ช่างเทคนิคที่ชำนาญการหรือรัฐบาลก็ไม่สามารถยึดเหรียญคุณได้โดยง่าย", "มีความยืดหยุ่นสูง สามารถจัดเก็บแยกคนละประเทศ หรือแบ่งให้ทนายความดูแลได้"],
                weakness: ["ซับซ้อนระดับเกินความจำเป็น (Extreme Overkill) ไม่เหมาะกับคนทั่วไป", "โอกาสสูญเสียการเข้าถึงเหรียญจากความผิดพลาดของตัวเอง (Human Error) มีสูงกว่าการโดนแฮ็ก", "การทำธุรกรรมแต่ละครั้งใช้เวลานานและยุ่งยากมาก"],
                risks: ["ลืม Passphrase ของบางอุปกรณ์ซึ่งส่งผลให้เข้าใช้งานระบบไม่ได้", "สูญเสียไฟล์ตั้งค่า (Wallet Descriptor) ทำให้โครงสร้าง Multisig พังทลาย", "มีอันเป็นไปกะทันหันโดยไม่ได้ทำคู่มือส่งมอบมรดกที่ชัดเจน ทายาทจะไม่มีทางนำเหรียญออกมาได้เลย"]
            }
        };

        let currentStep = "q_start";
        let historyStack = []; // สำหรับเก็บประวัติการตอบเพื่อทำปุ่ม ย้อนกลับ
        let nextStepAfterBalloon = "";

        // ฟังก์ชันช่วยจัดการสีตามเกณฑ์ที่กำหนด (ปรับช่วงคะแนนใหม่)
        function getColorClassByScore(score) {
            if (score === 0) return 'text-[#ff0055]'; // 0 = แดง
            if (score >= 1 && score <= 2.5) return 'text-[#F7931A]'; // 1 - 2.5 = ส้ม
            if (score >= 2.6 && score <= 4.0) return 'text-[#ffee00]'; // 2.6 - 4.0 = เหลือง
            return 'text-[#00ff41]'; // 4.1 - 5 = เขียว
        }

        function getHighlightClasses(score) {
            if (score === 0) return ['bg-[#ff0055]/20', 'border-l-4', 'border-[#ff0055]'];
            if (score <= 2.5) return ['bg-[#F7931A]/20', 'border-l-4', 'border-[#F7931A]'];
            if (score <= 4.0) return ['bg-[#ffee00]/20', 'border-l-4', 'border-[#ffee00]'];
            return ['bg-[#00ff41]/20', 'border-l-4', 'border-[#00ff41]'];
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderQuestion(currentStep);
            renderSecurityTable();
        });

        function renderQuestion(stepId) {
            const q = questions[stepId];
            if (!q) return;

            const qSection = document.getElementById('question_section');
            qSection.classList.remove('fade-in');
            
            void qSection.offsetWidth; // Force reflow
            
            document.getElementById('q_text').innerText = q.text;
            document.getElementById('q_icon').className = `fa-solid ${q.icon} text-5xl text-[#F7931A] mb-6 drop-shadow-[0_0_15px_rgba(247,147,26,0.5)]`;
            
            // ซ่อนหรือแสดงปุ่มย้อนกลับตามประวัติ (ถ้าอยู่หน้าแรกสุดจะไม่มีประวัติให้ย้อน)
            const backBtnContainer = document.getElementById('back_btn_container');
            if(historyStack.length > 0) {
                backBtnContainer.classList.remove('hidden');
            } else {
                backBtnContainer.classList.add('hidden');
            }

            qSection.classList.add('fade-in');
        }

        function handleAnswer(answer) {
            const q = questions[currentStep];
            const nextNode = q[answer];

            // บันทึกข้อปัจจุบันลงในประวัติก่อนเปลี่ยนหน้า
            historyStack.push(currentStep);

            if (nextNode.startsWith('res_')) {
                showResult(nextNode);
            } else if (nextNode.startsWith('action_')) {
                handleBalloon(nextNode);
            } else {
                currentStep = nextNode;
                renderQuestion(currentStep);
            }
        }

        function handleBalloon(action) {
            const modal = document.getElementById('balloon_modal');
            const bText = document.getElementById('balloon_text');
            const bContent = document.getElementById('balloon_content');

            if (action === 'action_buy_pc') {
                bText.innerHTML = "เพื่อความปลอดภัยสูงสุด <span class='text-[#F7931A] font-bold'>คุณจำเป็นต้องหาซื้อคอมพิวเตอร์มาไว้ใช้งานสักเครื่องครับ!</span> เพราะการทำธุรกรรมผ่านมือถือมีความเสี่ยงจากระบบไร้สายมากกว่ามาก";
                nextStepAfterBalloon = "q_has_pc"; 
            } else if (action === 'action_study_software') {
                bText.innerHTML = "เพื่อยกระดับความปลอดภัย <span class='text-[#F7931A] font-bold'>คุณต้องศึกษาการใช้งาน Software Wallet ขั้นสูง</span> เช่น Sparrow Wallet หรือ Electrum เพื่อหลีกเลี่ยงการใช้โปรแกรมพื้นฐานของผู้ผลิตที่อาจละเมิดความเป็นส่วนตัว";
                nextStepAfterBalloon = "q_advance";
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                bContent.classList.remove('scale-95');
                bContent.classList.add('scale-100');
            }, 10);
        }

        function closeBalloon() {
            const modal = document.getElementById('balloon_modal');
            const bContent = document.getElementById('balloon_content');
            
            modal.classList.add('opacity-0');
            bContent.classList.remove('scale-100');
            bContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                if(nextStepAfterBalloon === "q_has_pc"){
                    currentStep = "q_pc_skill"; 
                } else {
                    currentStep = nextStepAfterBalloon;
                }
                renderQuestion(currentStep);
            }, 300);
        }

        function showResult(resId) {
            const res = results[resId];
            
            document.getElementById('question_section').classList.add('hidden');
            
            const rSec = document.getElementById('result_section');
            rSec.classList.remove('hidden');
            
            // Header
            const titleEl = document.getElementById('result_title');
            titleEl.innerText = res.title;
            titleEl.style.color = res.color;
            
            document.getElementById('result_header_box').style.borderColor = res.color;
            document.getElementById('result_desc').innerHTML = res.desc;

            // Details Mapping Function
            const mapList = (arr) => arr.map(text => `<li>${text}</li>`).join('');

            document.getElementById('result_sec_detail').innerHTML = mapList(res.sec_detail);
            document.getElementById('result_weakness').innerHTML = mapList(res.weakness);
            document.getElementById('result_risks').innerHTML = mapList(res.risks);

            // Highlight ตารางแถวที่ตรงกับผลลัพธ์
            const targetScore = securityRatings[res.score_idx].score;
            const activeClasses = getHighlightClasses(targetScore);
            const allPossibleClasses = [
                'bg-[#ff0055]/20', 'border-[#ff0055]',
                'bg-[#F7931A]/20', 'border-[#F7931A]',
                'bg-[#ffee00]/20', 'border-[#ffee00]',
                'bg-[#00ff41]/20', 'border-[#00ff41]',
                'border-l-4'
            ];

            const rows = document.querySelectorAll('.score-row');
            rows.forEach((row, idx) => {
                row.classList.remove(...allPossibleClasses); // Clear prev classes
                if (idx === res.score_idx) {
                    row.classList.add(...activeClasses);
                    row.classList.remove('hover:bg-gray-800');
                } else {
                    row.classList.add('hover:bg-gray-800');
                }
            });
        }

        // ฟังก์ชันสำหรับกดย้อนกลับในหน้าคำถาม
        function goBack() {
            if (historyStack.length > 0) {
                currentStep = historyStack.pop();
                renderQuestion(currentStep);
            }
        }

        // ฟังก์ชันสำหรับกดย้อนกลับจากหน้าผลลัพธ์
        function goBackFromResult() {
            if (historyStack.length > 0) {
                document.getElementById('result_section').classList.add('hidden');
                document.getElementById('question_section').classList.remove('hidden');
                currentStep = historyStack.pop();
                renderQuestion(currentStep);
            }
        }

        // ฟังก์ชันเริ่มทำแบบประเมินใหม่
        function resetFlow() {
            currentStep = "q_start";
            historyStack = []; // ล้างประวัติการตอบ
            document.getElementById('result_section').classList.add('hidden');
            document.getElementById('question_section').classList.remove('hidden');
            renderQuestion(currentStep);
        }

        function generateStars(score) {
            let starsHtml = '<div class="star-rating flex gap-1">';
            for (let i = 1; i <= 5; i++) {
                if (score >= i) {
                    starsHtml += '<i class="fa-solid fa-star active"></i>';
                } else if (score >= i - 0.5) {
                    starsHtml += '<i class="fa-solid fa-star-half-stroke active"></i>';
                } else {
                    starsHtml += '<i class="fa-solid fa-star text-gray-700"></i>';
                }
            }
            starsHtml += '</div>';
            return starsHtml;
        }

        function renderSecurityTable() {
            const tbody = document.getElementById('security_table');
            let html = '';
            
            securityRatings.forEach((item, index) => {
                let colorClass = getColorClassByScore(item.score);
                
                html += `
                <tr class="score-row hover:bg-gray-800 transition border-b border-gray-800/50">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-white">${item.method}</div>
                        <div class="text-xs text-gray-500 mt-1">${item.desc}</div>
                    </td>
                    <td class="px-4 py-3 text-center font-mono font-bold ${colorClass}">
                        ${item.score.toFixed(1)}
                    </td>
                    <td class="px-4 py-3">
                        ${generateStars(item.score)}
                    </td>
                </tr>
                `;
            });
            tbody.innerHTML = html;
        }

    </script>
</body>
</html>