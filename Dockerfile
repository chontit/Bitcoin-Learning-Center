# เลือก Base Image เป็น PHP 8.2 พร้อม Apache Web Server
FROM php:8.2-apache

# ติดตั้ง PHP Extensions ที่จำเป็น (เช่น bcmath ที่มักใช้คำนวณตัวเลขทางคณิตศาสตร์ในระบบ Crypto)
RUN docker-php-ext-install bcmath

# เปิดใช้งาน Apache mod_rewrite (เผื่อมีการทำ Clean URL ในโปรเจกต์)
RUN a2enmod rewrite

# คัดลอกไฟล์ทั้งหมดในโฟลเดอร์โปรเจกต์ปัจจุบัน ไปไว้ในโฟลเดอร์เว็บของ Apache
COPY . /var/www/html/

# กำหนดสิทธิ์ให้ Apache อ่านและเขียนไฟล์ได้
RUN chown -R www-data:www-data /var/www/html/

# เปิดพอร์ต 80
EXPOSE 80
