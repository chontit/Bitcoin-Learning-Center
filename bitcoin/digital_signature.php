<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signature Simulator | by Chollatis Bitcoiner</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/4/46/Bitcoin.svg" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/elliptic/6.5.4/elliptic.min.js"></script>

    <style>
        :root {
            --neon-btc: #F7931A;
            --neon-green: #00ff41;
            --neon-blue: #00e5ff;
            --neon-red: #ff3333;
            --bg-dark: #050505;
            --glass: rgba(255, 255, 255, 0.05);
        }
        
        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-dark);
            color: #e0e0e0;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(247, 147, 26, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 90% 80%, rgba(0, 229, 255, 0.05) 0%, transparent 25%);
            overflow-x: hidden;
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
        }

        .btn-neon {
            background: linear-gradient(45deg, #F7931A, #ffb347);
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-neon:hover:not(:disabled) { box-shadow: 0 0 20px #F7931A; transform: scale(1.02); }
        .btn-neon:disabled { background: #333; color: #666; cursor: not-allowed; box-shadow: none; }

        .btn-blue {
            background: linear-gradient(45deg, #0088cc, #00e5ff);
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-blue:hover:not(:disabled) { box-shadow: 0 0 20px #00e5ff; transform: scale(1.02); }
        .btn-blue:disabled { background: #112; color: #445; cursor: not-allowed; border: 1px solid #00e5ff33; box-shadow: none; }

        .btn-hack {
            background: rgba(255, 51, 51, 0.1);
            color: var(--neon-red);
            border: 1px solid var(--neon-red);
            font-size: 0.75rem;
            transition: 0.2s;
        }
        .btn-hack:hover { background: rgba(255, 51, 51, 0.3); box-shadow: 0 0 10px rgba(255,51,51,0.5); }

        .hash-text { font-family: 'Courier New', Courier, monospace; word-break: break-all; }
        .data-box { font-family: 'Courier New', Courier, monospace; }
        
        input[type="text"], textarea {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            color: #fff;
            transition: 0.3s;
        }
        input[type="text"]:focus, textarea:focus {
            outline: none;
            border-color: var(--neon-btc);
            box-shadow: 0 0 10px rgba(247, 147, 26, 0.3);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .shake-active { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        
        .highlight-red { animation: pulseRed 1.5s infinite; }
        @keyframes pulseRed {
            0% { box-shadow: 0 0 0 0 rgba(255, 51, 51, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(255, 51, 51, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 51, 51, 0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-4 border-b border-gray-800 flex justify-between items-center bg-black/50 sticky top-0 z-50 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-file-signature text-4xl text-[#F7931A]"></i>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#F7931A] to-[#00e5ff] brand-font">
                    DIGITAL SIGNATURE
                </h1>
                <p class="text-xs text-gray-400">ECDSA Signing & Verification Simulator</p>
            </div>
        </div>
        <a href="/" class="flex items-center gap-2 px-4 py-2 rounded border border-gray-600 hover:border-[#F7931A] hover:text-[#F7931A] transition text-sm">
            <i class="fa-solid fa-house"></i> <span class="hidden md:inline">Home</span>
        </a>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6">
        
        <div class="mb-6 bg-[#111] border border-gray-800 rounded-lg p-4 flex items-start gap-4 shadow-lg">
            <div class="text-[#00e5ff] text-2xl mt-1"><i class="fa-solid fa-circle-info"></i></div>
            <div>
                <h2 class="font-bold text-white text-sm mb-1">กลไกการเซ็นธุรกรรมด้วยสมการ secp256k1 ของจริง</h2>
                <p class="text-xs text-gray-400 leading-relaxed">
                    ระบบนี้ใช้คณิตศาสตร์ Elliptic Curve Cryptography (ECC) แบบเดียวกับที่เครือข่าย Bitcoin ใช้งานจริง คุณสามารถพิมพ์ Private Key ของจริงเพื่อทดสอบคำนวณหา Public Key และสร้างลายเซ็นดิจิทัล (DER Format) แบบเรียลไทม์ได้เลย
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="flex flex-col gap-6">
                
                <div class="neon-box p-5 rounded-xl border-t-2 border-[#F7931A] relative overflow-hidden">
                    <div class="flex justify-between items-center mb-4 relative z-10">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-key text-[#F7931A]"></i> 1. Key Generation
                        </h3>
                        <button onclick="generateKeys()" class="bg-gray-800 hover:bg-gray-700 text-xs px-3 py-1.5 rounded transition border border-gray-600">
                            <i class="fa-solid fa-arrows-rotate"></i> สุ่ม Key ใหม่
                        </button>
                    </div>

                    <div class="relative z-10 flex flex-col gap-2">
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-widest block mb-1">Private Key ของคุณ (Alice)</label>
                            <div class="relative">
                                <input type="text" id="privKey" class="w-full p-2.5 rounded text-xs hash-text text-red-400 pr-10 bg-black/80" oninput="calculateRealPublicKey()" value="กำลังสร้าง...">
                                <button onclick="toggleKeyVisibility()" class="absolute right-3 top-2.5 text-gray-500 hover:text-white">
                                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <p class="text-[9px] text-gray-500 mt-1"><i class="fa-solid fa-pen"></i> สามารถลบแล้วพิมพ์แก้ไขเองได้ (ต้องเป็น Hexadecimal 64 ตัวอักษร)</p>
                        </div>

                        <div class="flex flex-col items-center my-1">
                            <i class="fa-solid fa-arrow-down text-gray-600 text-xs mb-1"></i>
                            <div class="bg-black/60 p-3 rounded border border-[#00e5ff]/30 text-[10px] data-box w-full shadow-inner">
                                <div class="flex justify-between items-center border-b border-gray-800 pb-1.5 mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">สมการ Elliptic Curve:</span>
                                        <a href="ecdsa.php" target="_blank" class="text-[9px] bg-[#00e5ff]/10 hover:bg-[#00e5ff]/30 text-[#00e5ff] hover:text-white px-1.5 py-0.5 rounded border border-[#00e5ff]/30 transition-all flex items-center gap-1" title="จำลองการทำงานของสมการ ECC">
                                            <i class="fa-solid fa-link"></i> Simulator
                                        </a>
                                    </div>
                                    <span class="text-[#00e5ff] bg-[#00e5ff]/10 px-1.5 py-0.5 rounded border border-[#00e5ff]/20 font-bold tracking-wider">K = k * G</span>
                                </div>
                                <div class="text-[9px] text-gray-500 leading-relaxed">
                                    <span class="text-[#00e5ff]">K</span> = Public Key, <span class="text-[#F7931A]">k</span> = Private Key, <span class="text-white">G</span> = Generator Point (secp256k1)<br>
                                    <div class="mt-1 pt-1 border-t border-gray-800/50 flex items-start gap-1">
                                        <i class="fa-solid fa-lock text-[#00ff41] mt-0.5 text-[8px]"></i>
                                        <span><b>One-way Function:</b> คำนวณหา <span class="text-[#00e5ff]">K</span> ได้ง่าย แต่ไม่สามารถสลายสมการเพื่อย้อนกลับหา <span class="text-[#F7931A]">k</span> (Private Key) ได้</span>
                                    </div>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-down text-gray-600 text-xs mt-1"></i>
                        </div>

                        <div>
                            <label class="text-[10px] text-gray-400 uppercase tracking-widest block mb-1">Public Key Compressed Address (เปิดเผยให้เครือข่ายรู้ได้)</label>
                            <input type="text" id="pubKey" readonly class="w-full p-2.5 rounded text-xs hash-text text-[#00e5ff] bg-black/80" value="กำลังสร้าง...">
                        </div>
                    </div>

                    <div id="spoofAlert" class="hidden absolute inset-0 bg-red-950/95 z-20 flex-col items-center justify-center p-4 text-center backdrop-blur-md transition-all">
                        <i class="fa-solid fa-skull-crossbones text-4xl text-red-500 mb-2 animate-pulse"></i>
                        <p class="text-sm text-white font-bold mb-1">Spoofed Key Active!</p>
                        <p class="text-[10px] text-red-200 mb-4">ตอนนี้คุณกำลังใช้ Key ของแฮกเกอร์ในการแอบอ้างเซ็นธุรกรรม</p>
                        
                        <div class="w-full bg-black/80 border border-red-500/50 rounded p-3 text-left mb-4 shadow-[0_0_15px_rgba(255,0,0,0.3)]">
                            <div class="mb-2">
                                <span class="text-[9px] text-red-400 uppercase tracking-widest block mb-1"><i class="fa-solid fa-user-ninja mr-1"></i> Hacker's Private Key:</span>
                                <div id="hackerPrivKey" class="text-[10px] text-red-500 hash-text break-all bg-red-900/20 p-2 rounded border border-red-900/50">...</div>
                            </div>
                            <div>
                                <span class="text-[9px] text-red-400 uppercase tracking-widest block mb-1"><i class="fa-solid fa-key mr-1"></i> Hacker's Public Key:</span>
                                <div id="hackerPubKey" class="text-[10px] text-red-500 hash-text break-all bg-red-900/20 p-2 rounded border border-red-900/50">...</div>
                            </div>
                        </div>

                        <button onclick="cancelSpoof()" class="px-4 py-2 bg-black text-white text-[10px] border border-gray-600 rounded hover:bg-gray-800 hover:border-red-500 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left"></i> กลับไปใช้ Key ของ Alice
                        </button>
                    </div>
                </div>

                <div class="neon-box p-5 rounded-xl border-t-2 border-[#333]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-file-invoice text-gray-400"></i> 2. Transaction Data
                        </h3>
                        <button onclick="copyTxData()" id="btnCopyTx" class="text-[10px] bg-gray-800 hover:bg-gray-700 text-gray-300 px-2 py-1 rounded border border-gray-600 transition flex items-center gap-1" title="คัดลอกข้อความไปลอง Hash เอง">
                            <i class="fa-solid fa-copy"></i> Copy Raw
                        </button>
                    </div>
                    
                    <p class="text-[10px] text-gray-500 mb-2">จำลองข้อมูลโครงสร้าง JSON ของจริง คุณสามารถพิมพ์แก้ไขเองได้</p>
                    <textarea id="txData" class="w-full h-80 p-3 rounded text-[11px] data-box text-gray-300 resize-none leading-tight overflow-y-auto" oninput="resetVerification(); updateNetworkView();"></textarea>
                    
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button onclick="signTransaction()" class="btn-neon flex-grow py-2 rounded text-xs flex justify-center items-center gap-2">
                            <i class="fa-solid fa-pen-nib"></i> Sign (เซ็นธุรกรรม)
                        </button>
                        <button onclick="hackData()" class="btn-hack px-3 py-2 rounded flex items-center gap-1 group relative" title="แอบเปลี่ยนจำนวนเงิน (Tamper Data)">
                            <i class="fa-solid fa-user-ninja"></i> 
                            <span class="hidden sm:inline">Tamper Data</span>
                        </button>
                        <button onclick="resetData()" id="btnResetData" class="hidden px-3 py-2 rounded border border-gray-600 text-gray-400 hover:text-white hover:bg-gray-800 text-xs flex items-center gap-1 transition" title="คืนค่าข้อมูลต้นฉบับ">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        <button onclick="spoofKey()" class="btn-hack px-3 py-2 rounded flex items-center gap-1 group relative" title="แอบอ้างเซ็นด้วย Key คนอื่น">
                            <i class="fa-solid fa-mask"></i>
                            <span class="hidden sm:inline">Spoof Key</span>
                        </button>
                    </div>
                </div>

                <div class="neon-box p-5 rounded-xl border-t-2 border-[#F7931A] bg-[#1a1300] flex-grow">
                    <h3 class="text-sm font-bold text-[#F7931A] mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-file-signature"></i> 3. Digital Signature (ลายเซ็นที่ได้)
                    </h3>
                    
                    <div class="mb-4 space-y-2">
                        <p class="text-[10px] text-gray-400 leading-relaxed">
                            กระบวนการเบื้องหลังสมการ <span class="text-white font-bold">ECDSA</span> (Elliptic Curve Digital Signature Algorithm):
                        </p>
                        <div class="bg-black/60 p-3 rounded border border-[#F7931A]/30 text-[10px] data-box shadow-inner">
                            <div class="flex justify-between items-center border-b border-gray-800 pb-1.5 mb-1.5">
                                <span class="text-gray-500">1. ย่อข้อมูล (Hashing):</span>
                                <span class="text-[#00e5ff] bg-[#00e5ff]/10 px-1.5 py-0.5 rounded border border-[#00e5ff]/20">h = SHA256(Tx_Data)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-gray-800 pb-1.5 mb-1.5">
                                <span class="text-gray-500">2. เซ็นด้วยกุญแจ (Signing):</span>
                                <span class="text-[#F7931A] bg-[#F7931A]/10 px-1.5 py-0.5 rounded border border-[#F7931A]/20">Sig = Sign(h, Private_Key)</span>
                            </div>
                            <div class="text-gray-500 mt-2 leading-relaxed">
                                <i class="fa-solid fa-info-circle text-[#F7931A] mr-1"></i> ผลลัพธ์ที่ได้คือพารามิเตอร์ <span class="text-white">(r, s)</span> จะถูกนำมาจัดเรียงในมาตรฐาน <span class="text-white">DER Format</span> (มักจะขึ้นต้นด้วย 3044, 3045 หรือ 3046 ตามขนาดของ Byte) ของจริง
                            </div>
                        </div>
                    </div>

                    <div id="signatureOutput" class="w-full min-h-[80px] p-3 rounded bg-black border border-gray-800 text-xs hash-text text-gray-600 flex items-center justify-center break-all transition-colors duration-300">
                        รอการเซ็นธุรกรรม...
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6 h-full lg:sticky lg:top-24">
                <div class="neon-box p-5 rounded-xl border-t-2 border-[#00e5ff] bg-gradient-to-b from-[#001a22] to-transparent flex flex-col h-full min-h-[500px]">
                    <h3 class="text-sm font-bold text-[#00e5ff] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> 4. Network Verification
                    </h3>
                    
                    <p class="text-[11px] text-gray-400 mb-3 leading-relaxed">
                        เครือข่ายจะนำข้อมูล 3 ส่วนนี้ไปเข้าสมการเพื่อตรวจสอบว่า <br><span class="text-gray-300">"ใช่เจ้าของตัวจริงหรือไม่?"</span> และ <span class="text-gray-300">"ข้อมูลถูกดัดแปลงหรือไม่?"</span>
                    </p>

                    <div class="bg-black/60 p-3 rounded border border-[#00e5ff]/30 text-[10px] data-box mb-4 shadow-inner flex-grow overflow-y-auto space-y-4">
                        
                        <div>
                            <span class="text-gray-500 block mb-1"><i class="fa-solid fa-key text-[#00e5ff] mr-1"></i> Public Key (จากส่วนที่ 1):</span>
                            <div id="verify-pubkey" class="text-[#00e5ff] bg-[#00e5ff]/10 px-2 py-1.5 rounded border border-[#00e5ff]/20 break-all">รอข้อมูล...</div>
                        </div>
                        
                        <div id="verify-txdata-container">
                            <span class="text-gray-500 block mb-2"><i class="fa-solid fa-file-invoice text-gray-400 mr-1"></i> Transaction Data (จากส่วนที่ 2):</span>
                            
                            <div id="verify-txdata" class="text-gray-300 bg-gray-900/50 p-3 rounded border border-gray-700">
                                รอข้อมูล...
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-500 block mb-1"><i class="fa-solid fa-file-signature text-[#F7931A] mr-1"></i> Signature (จากส่วนที่ 3):</span>
                            <div id="verify-sig" class="text-gray-600 bg-black px-2 py-1.5 rounded border border-gray-800 break-all transition-colors">รอการเซ็น...</div>
                        </div>
                    </div>

                    <button onclick="verifyTransaction()" id="btnVerify" disabled class="btn-blue w-full py-3 rounded text-sm flex justify-center items-center gap-2 opacity-50 cursor-not-allowed mt-auto flex-shrink-0">
                        <i class="fa-solid fa-microchip"></i> ส่งให้เครือข่ายตรวจสอบ (VERIFY)
                    </button>

                    <div id="verifyResult" class="mt-4 hidden transition-all duration-300 flex-shrink-0">
                        </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center p-6 text-xs text-gray-600 border-t border-gray-900 mt-6 bg-black/30 backdrop-blur-sm">
        <p>© 2026 <span class="text-[#F7931A] font-bold">Chollatis Bitcoiner.</span> <span class="mx-2 text-gray-700">|</span> Don't Trust, Verify.</p>
    </footer>

    <script>
        const EC = elliptic.ec;
        const ec = new EC('secp256k1');

        let alicePrivKey = "";
        let alicePubKey = "";
        
        let currentPrivKeyInUse = "";
        let generatedSignature = "";
        let isKeyHidden = true;
        
        const defaultTxDataTemplate = `{
  "txid": "e40d05e1cd9cd34c5c7568a13f865b0407a741d7c91cc80a6ecbd875e7bff4ae",
  "version": 2,
  "locktime": 0,
  "fee": 2840,
  "inputs": [
    {
      "txid": "52a7a90f060fafddcfa19ca1da1d531472d68fdafa8b94d7255fb4dd7e7d8eda",
      "output": 1,
      "value": 13573179,
      "address": "bc1q5e52e2h96c4pf88uk5hr87ul8wggtqek5lm07h",
      "witness": [] 
    }
  ],
  "outputs": [
    {
      "address": "38TKzmY18eAVT3wbrtcNx6m5qdp5vXyTPq",
      "value": 440000
    },
    {
      "address": "bc1qkklhewvpa5fdrdvctlw0dt5zd7ahcq22pv2aq4",
      "value": 13130339
    }
  ]
}`;
        let originalSignedData = "";
        let originalSignedHashDisplay = "";

        // อัปเดตฟังก์ชัน SHA256: บังคับแปลง Line Endings ให้เป็น \n เสมอก่อน Hash
        async function sha256(message) {
            // ป้องกันปัญหา OS แตกต่างกัน (CRLF vs LF)
            const normalizedMessage = message.replace(/\r\n/g, '\n');
            const msgBuffer = new TextEncoder().encode(normalizedMessage);
            const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        }

        function toggleKeyVisibility() {
            const pkInput = document.getElementById('privKey');
            const icon = document.getElementById('eyeIcon');
            isKeyHidden = !isKeyHidden;
            
            if (isKeyHidden) {
                pkInput.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                pkInput.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        function generateKeys() {
            const keyPair = ec.genKeyPair();
            alicePrivKey = keyPair.getPrivate('hex').padStart(64, '0');
            alicePubKey = keyPair.getPublic(true, 'hex'); 
            currentPrivKeyInUse = alicePrivKey;
            
            document.getElementById('privKey').value = alicePrivKey;
            document.getElementById('pubKey').value = alicePubKey;
            document.getElementById('privKey').type = isKeyHidden ? "password" : "text";

            cancelSpoof();
            resetState();
            updateNetworkView();
        }

        function calculateRealPublicKey() {
            const inputKey = document.getElementById('privKey').value.trim();
            const pubKeyDisplay = document.getElementById('pubKey');
            
            if (inputKey.length === 64) {
                try {
                    const keyPair = ec.keyFromPrivate(inputKey, 'hex');
                    const newPubKey = keyPair.getPublic(true, 'hex');
                    pubKeyDisplay.value = newPubKey;
                    pubKeyDisplay.classList.remove('text-red-500');
                    pubKeyDisplay.classList.add('text-[#00e5ff]');
                    
                    alicePrivKey = inputKey;
                    currentPrivKeyInUse = alicePrivKey;
                    alicePubKey = newPubKey;
                    
                    cancelSpoof();
                    resetState();
                    updateNetworkView();
                } catch (error) {
                    pubKeyDisplay.value = "รูปแบบ Private Key ไม่ถูกต้อง";
                    pubKeyDisplay.classList.add('text-red-500');
                    pubKeyDisplay.classList.remove('text-[#00e5ff]');
                }
            } else {
                pubKeyDisplay.value = "กรุณาใส่ค่า Hexadecimal 64 ตัวอักษร";
                pubKeyDisplay.classList.add('text-red-500');
                pubKeyDisplay.classList.remove('text-[#00e5ff]');
            }
        }

        // อัปเดตฟังก์ชันคัดลอก: จัดการ Line Endings ให้ตรงกับการ Hash
        function copyTxData() {
            const txBox = document.getElementById('txData');
            // แปลง \r\n เป็น \n ก่อนก๊อปปี้ลง Clipboard เพื่อให้ไปคำนวณที่อื่นแล้วค่าตรงกัน
            const normalizedText = txBox.value.trim().replace(/\r\n/g, '\n');
            navigator.clipboard.writeText(normalizedText).then(() => {
                const btn = document.getElementById('btnCopyTx');
                const originalHTML = btn.innerHTML;
                
                btn.innerHTML = '<i class="fa-solid fa-check text-[#00ff41]"></i> Copied!';
                btn.classList.add('border-[#00ff41]', 'text-[#00ff41]');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('border-[#00ff41]', 'text-[#00ff41]');
                }, 2000);
            });
        }

        function hackData() {
            const txBox = document.getElementById('txData');
            let currentData = txBox.value;
            
            if(currentData.includes('"value": 440000')) {
                txBox.value = currentData.replace(/"value":\s*440000/, '"value": 5000000000');
            } else {
                alert("ไม่พบยอดเงินเป้าหมาย 440000 Sats ที่จะแฮก (อาจถูกแก้ไขไปแล้ว)");
                return;
            }
            
            txBox.classList.add('highlight-red');
            setTimeout(() => txBox.classList.remove('highlight-red'), 1500);
            
            document.getElementById('btnResetData').classList.remove('hidden');
            
            resetVerification();
            updateNetworkView();
        }

        function resetData() {
            const txBox = document.getElementById('txData');
            txBox.value = originalSignedData || defaultTxDataTemplate;
            txBox.classList.remove('highlight-red');
            
            document.getElementById('btnResetData').classList.add('hidden');
            resetVerification();
            updateNetworkView();
        }

        function clearSignatureUI() {
            generatedSignature = "";
            const sigBox = document.getElementById('signatureOutput');
            sigBox.innerText = "รอการเซ็นธุรกรรม...";
            sigBox.className = "w-full min-h-[80px] p-3 rounded bg-black border border-gray-800 text-xs hash-text text-gray-600 flex items-center justify-center break-all transition-colors duration-300";
            resetVerification();
            updateNetworkView();
        }

        function spoofKey() {
            const hackerKeyPair = ec.genKeyPair();
            currentPrivKeyInUse = hackerKeyPair.getPrivate('hex').padStart(64, '0');
            const hackerPubKey = hackerKeyPair.getPublic(true, 'hex');
            
            document.getElementById('hackerPrivKey').innerText = currentPrivKeyInUse;
            document.getElementById('hackerPubKey').innerText = hackerPubKey;
            
            document.getElementById('spoofAlert').classList.remove('hidden');
            document.getElementById('spoofAlert').classList.add('flex');
            clearSignatureUI(); 
        }

        function cancelSpoof() {
            currentPrivKeyInUse = alicePrivKey;
            document.getElementById('spoofAlert').classList.add('hidden');
            document.getElementById('spoofAlert').classList.remove('flex');
            clearSignatureUI(); 
        }

        function resetState() {
            clearSignatureUI();
            originalSignedData = "";
            document.getElementById('txData').value = defaultTxDataTemplate;
            document.getElementById('btnResetData').classList.add('hidden');
        }

        function resetVerification() {
            const resultBox = document.getElementById('verifyResult');
            resultBox.classList.add('hidden');
            resultBox.classList.remove('shake-active');
            
            const btnVerify = document.getElementById('btnVerify');
            if(generatedSignature) {
                btnVerify.disabled = false;
                btnVerify.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnVerify.disabled = true;
                btnVerify.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        async function updateNetworkView() {
            const pubKeyDisplay = document.getElementById('verify-pubkey');
            pubKeyDisplay.innerText = alicePubKey || "รอข้อมูล...";

            // ใช้ .trim() เพื่อลบช่องว่างส่วนเกินหน้า/หลังทิ้ง เพื่อให้สอดคล้องกับฟังก์ชัน Copy และ Sign
            const txDataBox = document.getElementById('txData').value.trim();
            const txDataDisplay = document.getElementById('verify-txdata');
            
            if (txDataBox) {
                const currentHash = await sha256(txDataBox);
                
                let humanReadableHTML = "";
                try {
                    const parsedTx = JSON.parse(txDataBox);
                    
                    let feeInfo = parsedTx.fee ? `
                        <div class="flex justify-between items-center bg-black/40 p-2.5 rounded border border-gray-700/50 mt-2">
                            <span class="text-gray-400 font-bold"><i class="fa-solid fa-gas-pump w-4"></i> ค่าธรรมเนียม (Miner Fee)</span>
                            <span class="text-[#F7931A] font-bold">${(parsedTx.fee / 100000000).toFixed(8)} BTC <span class="text-[9px] font-normal text-gray-500">(${parsedTx.fee} sats)</span></span>
                        </div>` : '';

                    let inputsText = "";
                    if(parsedTx.inputs && parsedTx.inputs.length > 0) {
                        inputsText = parsedTx.inputs.map(v => {
                            let btcVal = v.value ? (v.value / 100000000).toFixed(8) : "0";
                            let shortTx = v.txid ? `${v.txid.substring(0,8)}...${v.txid.substring(v.txid.length-4)}` : 'Unknown';
                            return `
                            <div class="bg-black/40 p-2.5 rounded border border-gray-700/50 flex justify-between items-center group hover:border-gray-500 transition-colors">
                                <div class="overflow-hidden pr-2">
                                    <div class="text-gray-400 text-[9px] mb-0.5">อ้างอิงจาก TX: <span class="text-[#00e5ff] font-mono">${shortTx}</span> <span class="text-gray-500">[ลำดับ ${v.output}]</span></div>
                                    <div class="text-gray-300 text-[10px] font-mono truncate w-40 md:w-56" title="${v.address || 'Unknown'}">${v.address || 'Unknown'}</div>
                                </div>
                                <div class="text-red-400 font-bold shrink-0 text-right">
                                    -${btcVal} <span class="text-[9px]">BTC</span>
                                </div>
                            </div>`;
                        }).join('<div class="h-1.5"></div>'); 
                    }
                    
                    let outputsList = "";
                    if (parsedTx.outputs && parsedTx.outputs.length > 0) {
                        outputsList = parsedTx.outputs.map((v, index) => {
                            let isHacked = v.value > 15000000; 
                            let amountColor = isHacked ? "text-red-500 animate-pulse bg-red-900/30 px-1 rounded" : (index === 0 ? "text-green-400" : "text-blue-400");
                            
                            let btcValue = (v.value / 100000000).toFixed(8);
                            
                            let icon, label, displayAddress;
                            if (v.address === null || v.address === undefined || v.address === "") {
                                icon = '<i class="fa-solid fa-database text-purple-400"></i>';
                                label = 'บันทึกข้อมูล (OP_RETURN)';
                                displayAddress = '<span class="text-purple-400/70 italic">Data saved to blockchain</span>';
                            } else if (index === 0) {
                                icon = '<i class="fa-solid fa-money-bill-trend-up text-green-400"></i>';
                                label = 'ชำระเงิน (Payment)';
                                displayAddress = v.address;
                            } else {
                                icon = '<i class="fa-solid fa-rotate-left text-blue-400"></i>';
                                label = 'เงินทอน (Change)';
                                displayAddress = v.address;
                            }
                            
                            return `
                            <div class="bg-black/40 p-2.5 rounded border border-gray-700/50 flex justify-between items-center group hover:border-gray-500 transition-colors">
                                <div class="overflow-hidden pr-2">
                                    <div class="text-gray-400 text-[9px] mb-0.5">${icon} <span class="ml-1">${label}</span></div>
                                    <div class="text-gray-300 text-[10px] font-mono truncate w-40 md:w-56" title="${v.address || 'OP_RETURN'}">${displayAddress}</div>
                                </div>
                                <div class="${amountColor} font-bold shrink-0 text-right">
                                    +${btcValue} <span class="text-[9px]">BTC</span>
                                </div>
                            </div>`;
                        }).join('<div class="h-1.5"></div>'); 
                    }

                    humanReadableHTML = `
                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] text-gray-400 block mb-1.5 font-bold"><i class="fa-solid fa-wallet text-gray-500 w-4"></i> Inputs (ดึงเงินจาก UTXO เดิมมาใช้)</span>
                                ${inputsText}
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 block mb-1.5 font-bold"><i class="fa-solid fa-money-bill-transfer text-gray-500 w-4"></i> Outputs (จุดหมายปลายทางของเงิน)</span>
                                ${outputsList}
                            </div>
                            ${feeInfo}
                            
                            <div class="text-[9px] text-gray-400 mt-2 bg-[#00e5ff]/5 border border-[#00e5ff]/20 p-2 rounded leading-relaxed shadow-inner">
                                <i class="fa-solid fa-lightbulb text-[#00e5ff] mr-1"></i> <b>เกร็ดความรู้:</b> สังเกตว่าในกล่อง 2 ค่า <code class="text-[#00e5ff] bg-black px-1 rounded">witness: []</code> ของ Input ยังเป็น Array ว่างอยู่ 
                                เครือข่ายจะรอให้คุณทำการ <b>"เซ็น (Sign)"</b> ด้วย Private Key แล้วนำ Signature ที่ได้จากกล่อง 3 มาเติมลงไป ธุรกรรมถึงจะสมบูรณ์และโอนได้สำเร็จ
                            </div>
                        </div>
                    `;
                } catch(e) {
                    humanReadableHTML = `<div class="p-3 bg-red-900/20 border border-red-500/50 rounded text-red-400 text-xs flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-lg"></i> ไม่สามารถแปลผลได้ (รูปแบบ JSON ไม่ถูกต้อง)</div>`;
                }

                txDataDisplay.innerHTML = `
${humanReadableHTML}
<div class="mt-4 pt-3 border-t border-gray-800">
    <span class="text-[9px] text-gray-500 flex items-center gap-1"><i class="fa-solid fa-microchip"></i> Node นำข้อความดิบทั้งหมดไป Hash ก่อนเข้าสมการ:</span>
    <div class="text-[10px] text-[#00e5ff] font-mono break-all mt-1 bg-black p-2 rounded border border-gray-800 shadow-inner">${currentHash}</div>
</div>`;
            } else {
                txDataDisplay.innerText = "รอข้อมูล...";
            }

            const sigDisplay = document.getElementById('verify-sig');
            if (generatedSignature) {
                sigDisplay.innerText = generatedSignature;
                sigDisplay.className = "text-[#F7931A] bg-[#F7931A]/10 px-2 py-1.5 rounded border border-[#F7931A]/20 break-all transition-colors font-mono";
            } else {
                sigDisplay.innerText = "รอการเซ็น...";
                sigDisplay.className = "text-gray-600 bg-black px-2 py-1.5 rounded border border-gray-800 break-all transition-colors font-mono";
            }
        }

        async function signTransaction() {
            const txData = document.getElementById('txData').value.trim();
            if (!txData) return alert("กรุณาใส่ข้อมูลธุรกรรม");

            const rawHash = await sha256(txData);
            
            try {
                const keyPair = ec.keyFromPrivate(currentPrivKeyInUse, 'hex');
                const signature = keyPair.sign(rawHash);
                generatedSignature = signature.toDER('hex');
                
                originalSignedData = txData;
                originalSignedHashDisplay = rawHash;

                const sigBox = document.getElementById('signatureOutput');
                sigBox.innerText = generatedSignature;
                sigBox.classList.remove('text-gray-600', 'bg-black');
                
                if(currentPrivKeyInUse === alicePrivKey) {
                    sigBox.className = "w-full min-h-[80px] p-3 rounded text-xs hash-text flex items-center justify-center break-all transition-colors duration-300 text-[#F7931A] bg-[#F7931A]/10 border border-[#F7931A]/50";
                } else {
                    sigBox.className = "w-full min-h-[80px] p-3 rounded text-xs hash-text flex items-center justify-center break-all transition-colors duration-300 text-red-400 bg-red-900/30 border border-red-500/50";
                }

                document.getElementById('btnResetData').classList.add('hidden');
                resetVerification();
                updateNetworkView();
            } catch (error) {
                alert("เกิดข้อผิดพลาดในการเซ็นธุรกรรม (Private Key อาจไม่ถูกต้อง)");
            }
        }

        async function verifyTransaction() {
            if (!generatedSignature) return;

            const currentTxData = document.getElementById('txData').value.trim();
            const resultBox = document.getElementById('verifyResult');
            resultBox.classList.remove('hidden', 'shake-active');

            resultBox.innerHTML = '<div class="p-4 rounded text-center border border-gray-700 bg-gray-900"><i class="fa-solid fa-spinner fa-spin text-xl text-gray-400"></i><br><span class="text-xs text-gray-400 mt-2 block">Node is verifying using real secp256k1 math...</span></div>';

            setTimeout(async () => {
                const currentDataHashDisplay = await sha256(currentTxData);
                
                let isValid = false;
                let errorReason = "";
                let analysisHTML = ""; 

                try {
                    isValid = ec.verify(currentDataHashDisplay, generatedSignature, alicePubKey, 'hex');
                } catch(e) {
                    isValid = false;
                }

                if (!isValid) {
                    if (currentTxData !== originalSignedData) {
                        errorReason = "Data Tampering Detected! (ข้อมูลถูกดัดแปลง)";
                        analysisHTML = `
                            <div class="text-left">
                                <p class="text-[10px] text-gray-400 mb-1">🔍 <b>Avalanche Effect Analysis:</b></p>
                                <div class="bg-black/50 p-2 rounded border border-gray-800 mb-2">
                                    <p class="text-[9px] text-gray-500 mb-0.5">Original Message Hash (ตอนเซ็น):</p>
                                    <p class="text-[10px] hash-text text-[#00ff41] break-all">${originalSignedHashDisplay}</p>
                                </div>
                                <div class="bg-black/50 p-2 rounded border border-gray-800">
                                    <p class="text-[9px] text-gray-500 mb-0.5">Current Message Hash (ตอนตรวจ):</p>
                                    <p class="text-[10px] hash-text text-red-500 break-all">${currentDataHashDisplay}</p>
                                </div>
                                <p class="text-[9px] text-red-400 mt-2 text-center">ค่า Hash เปลี่ยนไป ทำให้สมการ ec.verify() รีเทิร์นค่าเป็น FALSE!</p>
                            </div>
                        `;
                    } else {
                        errorReason = "Public Key Mismatch! (ไม่ใช่ลายเซ็นของ Alice)";
                        analysisHTML = `
                            <div class="text-left">
                                <p class="text-[10px] text-gray-400 mb-1">🔍 <b>Key Verification Analysis:</b></p>
                                <div class="bg-black/50 p-2 rounded border border-gray-800 mb-2">
                                    <p class="text-[9px] text-gray-500 mb-0.5">Public Key ของ Alice (ที่เครือข่ายใช้อ้างอิง):</p>
                                    <p class="text-[10px] hash-text text-[#00e5ff] break-all">${alicePubKey}</p>
                                </div>
                                <div class="bg-black/50 p-2 rounded border border-gray-800">
                                    <p class="text-[9px] text-gray-500 mb-0.5">ผลลัพธ์ Verify(Hash, Signature, PublicKey):</p>
                                    <p class="text-[11px] text-red-500 font-bold break-all">FALSE (สมการไม่ลงตัว)</p>
                                </div>
                                <p class="text-[9px] text-red-400 mt-2 text-center">ลายเซ็นนี้ ไม่ได้ถูกสร้างขึ้นมาจาก Private Key คู่บุญของอลิซ!</p>
                            </div>
                        `;
                    }
                }

                if (isValid) {
                    resultBox.innerHTML = `
                        <div class="p-4 rounded border border-[#00ff41] bg-[#00ff41]/10 shadow-[0_0_15px_rgba(0,255,65,0.2)]">
                            <div class="text-center mb-3">
                                <i class="fa-solid fa-shield-check text-3xl mb-2 text-[#00ff41]"></i>
                                <h4 class="font-bold text-[#00ff41] tracking-wider">SIGNATURE VALID</h4>
                                <p class="text-[11px] text-[#00ff41]/80 mt-1">ข้อความถูกเซ็นโดยเจ้าของกุญแจจริง และไม่ถูกดัดแปลงระหว่างทาง</p>
                            </div>
                        </div>
                    `;
                } else {
                    void resultBox.offsetWidth;
                    resultBox.classList.add('shake-active');
                    
                    resultBox.innerHTML = `
                        <div class="p-4 rounded border border-red-500 bg-red-500/10 shadow-[0_0_15px_rgba(255,51,51,0.2)]">
                            <div class="text-center mb-3 border-b border-red-500/30 pb-3">
                                <i class="fa-solid fa-triangle-exclamation text-3xl mb-2 text-red-500"></i>
                                <h4 class="font-bold text-red-500 tracking-wider">TRANSACTION REJECTED</h4>
                                <p class="text-xs text-red-400 font-bold mt-1">${errorReason}</p>
                            </div>
                            ${analysisHTML}
                        </div>
                    `;
                }
            }, 800);
        }

        window.onload = () => {
            document.getElementById('privKey').type = "password";
            document.getElementById('txData').value = defaultTxDataTemplate;
            generateKeys();
        };
    </script>
</body>
</html>