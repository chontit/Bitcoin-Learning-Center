<div align="center">

# ⚡ Bitcoin Learning Center

**Interactive PHP Modules for Cryptography & Bitcoin Education**

[![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Docker Ready](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
[![Bitcoin](https://img.shields.io/badge/Bitcoin-Education-F7931A?style=for-the-badge&logo=bitcoin&logoColor=white)](https://bitcoin.org)

</div>

---

## 🚀 Overview
ระบบจำลองและเรียนรู้การทำงานของเครือข่าย Bitcoin (Bitcoin Learning Center) พัฒนาด้วย PHP ออกแบบมาเพื่อจำลองกระบวนการทาง Cryptography เช่น การคำนวณ Hash (SHA-256), การสุ่ม Mnemonic Seed Phrase และระบบจำลองโครงสร้างข้อมูล

## 📂 Project Structure
```text
bitcoin-learning-center/
├── src/                # โค้ด PHP หลักทั้งหมด
│   ├── hash_sim.php    # โมดูลคำนวณ SHA-256
│   └── mnemonic.php    # เครื่องมือสร้าง BIP-39 (จำกัดคำตามมาตรฐาน)
├── assets/             # ไฟล์ CSS (Dark Mode / Cyberpunk UI) และ JS
├── Dockerfile          # การตั้งค่าสำหรับ PaaS (Render)
└── README.md
