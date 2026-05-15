<div align="center">

# ⚡ BITCOIN LEARNING CENTER

**Interactive PHP Modules for Cryptography & Bitcoin Education**

[![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Docker Ready](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Bitcoin Education](https://img.shields.io/badge/Bitcoin-Education-F7931A?style=for-the-badge&logo=bitcoin&logoColor=white)](https://bitcoin.org)
[![UI Theme](https://img.shields.io/badge/UI-Cyberpunk_Dark-050505?style=for-the-badge)](https://github.com/chontit/Bitcoin-Learning-Center)

</div>

---

## 🚀 System Overview
**Bitcoin Learning Center** คือระบบจำลองการทำงานเชิงลึกของเครือข่าย Bitcoin และ Cryptography พัฒนาด้วย PHP พร้อมอินเทอร์เฟซสไตล์ **Cyberpunk / Dark Mode** ดุดัน ออกแบบมาให้นักพัฒนาและผู้ที่สนใจสามารถทดลองเล่น (Interactive) เพื่อทำความเข้าใจกลไกที่ซับซ้อนได้แบบเห็นภาพ

## 📂 Project Directory
```text
bitcoin-learning-center/
├── bitcoin/                   # ⚙️ Core Modules 
│   ├── bip39.php              # BIP-39 Mnemonic Generator
│   ├── hd-wallet.php          # Hierarchical Deterministic Wallet 
│   ├── ecdsa.php              # Elliptic Curve & Digital Signatures
│   ├── hashing.php            # SHA-256 Real-time Visualizer
│   ├── binary.php             # Binary & Bitwise Operations
│   ├── miner.php              # Proof-of-Work & Nonce Simulation
│   ├── issuance_simulator.php # Halving & Supply Issuance
│   └── lightning.php          # Layer 2 Payment Channels
├── Dockerfile                 # 🐳 Production-ready Container Config
└── README.md                  # 📖 System Documentation
```

## 🧩 Core Architecture
* **🔐 Cryptography & Keys:** จำลองการสร้าง Seed Phrase (ตรวจสอบคำจากมาตรฐาน), การแตกคีย์ (Derivation) และกระบวนการสร้างลายเซ็นดิจิทัล (Digital Signature)
* **⛓️ Blockchain Mechanics:** เจาะลึกการทำงานของ Hash Function, การจัดเรียงข้อมูลระดับไบนารี (Binary) และระบบจำลองการขุด (Proof-of-Work) เพื่อหาค่า Nonce
* **📈 Network & Economics:** โมเดลจำลองการเกิด Halving, วิเคราะห์การออกเหรียญ (Issuance) และคอนเซปต์ของระบบ Layer 2 อย่าง Lightning Network

## 🛠️ Deployment Setup (PaaS / Docker)
โปรเจกต์นี้ออกแบบมาแบบ `Container-first` รองรับการจับโยนขึ้น PaaS (เช่น Render, Railway) หรือรันผ่าน Home Lab (Portainer) ได้ทันทีโดยไม่ต้องเซ็ตอัปเซิร์ฟเวอร์ใหม่

```bash
# 1. Build the image
docker build -t btc-learning-center .

# 2. Spin up the container (Detached mode)
docker run -d -p 8080:80 --name btc-app btc-learning-center
```
> 🌐 **Access Point:** `http://localhost:8080`
