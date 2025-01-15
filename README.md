# Laravel Vue Stack Sanbercampus
Laravel Vue Stack Sanbercampus adalah sebuah proyek yang dibangun dengan Laravel sebagai backend dan Vue.js sebagai frontend. Proyek ini memiliki fitur autentikasi, verifikasi email, dan manajemen produk, kategori, dan order.

Proyek ini dibangun dengan menggunakan Laravel 9 dan Vue.js 3, yang memungkinkan pengembangan aplikasi web yang cepat, aman, dan scalable. Proyek ini dirancang untuk memenuhi kebutuhan bisnis yang memerlukan sistem manajemen yang efektif dan efisien.

Proyek ini memiliki beberapa fitur, seperti autentikasi dengan JWT, verifikasi email, manajemen produk, manajemen kategori, dan manajemen order. Fitur-fitur ini memungkinkan pengguna untuk melakukan login, mengelola produk, kategori, dan order dengan mudah dan aman.

Proyek ini juga memiliki dokumentasi API yang lengkap, sehingga memudahkan pengembang lain untuk mengintegrasikan aplikasi ini dengan aplikasi lainnya. Dokumentasi API dapat diakses pada [https://documenter.getpostman.com/view/30209921/2sAYQXpt6H#f3ee72d2-ae30-4b21-abe9-106016ac75f6](https://documenter.getpostman.com/view/30209921/2sAYQXpt6H#f3ee72d2-ae30-4b21-abe9-106016ac75f6).

## Table of Contents
- [Tentang Proyek](#tentang-proyek)
- [Fitur dan Fungsi](#fitur-dan-fungsi)
- [Teknologi Stack](#teknologi-stack)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
- [API Dokumentasi](#api-dokumentasi)
- [Lisensi](#lisensi)
- [Kontak](#kontak)

## Fitur dan Fungsi
Berikut adalah beberapa fitur dan fungsi yang ada dalam proyek ini:
* **Autentikasi**: Proyek ini menggunakan autentikasi dengan JWT. Pengguna dapat melakukan login dan logout.
* **Verifikasi Email**: Proyek ini memiliki fitur verifikasi email. Pengguna harus melakukan verifikasi email sebelum dapat menggunakan fitur lainnya.
* **Manajemen Produk**: Proyek ini memiliki fitur manajemen produk. Admin dapat melakukan CRUD (Create, Read, Update, Delete) pada produk.
* **Manajemen Kategori**: Proyek ini memiliki fitur manajemen kategori. Admin dapat melakukan CRUD pada kategori.
* **Manajemen Order**: Proyek ini memiliki fitur manajemen order. Admin dapat melakukan CRUD pada order.

## Teknologi Stack
Berikut adalah teknologi stack yang digunakan dalam proyek ini:
* **Backend**: Laravel 9
* **Frontend**: Vue.js 3
* **Database**: MySQL
* **API**: RESTful API

## Prasyarat
Berikut adalah prasyarat yang dibutuhkan untuk menjalankan proyek ini:
* **PHP**: 8.0 atau lebih tinggi
* **MySQL**: 5.7 atau lebih tinggi
* **Node.js**: 14.17.0 atau lebih tinggi
* **npm**: 6.14.13 atau lebih tinggi

## Instalasi
Berikut adalah langkah-langkah untuk melakukan instalasi proyek ini:
```bash
# Clone repository
git clone https://github.com/bimapopo345/Laravel-Vue-Stack-Sanbercampus.git

# Masuk ke direktori proyek
cd Laravel-Vue-Stack-Sanbercampus

# Install dependencies
composer install

# Copy file .env.example ke .env
cp .env.example .env

# Generate key
php artisan key:generate

# Migrasi database
php artisan migrate

# Install dependencies frontend
npm install

# Jalankan aplikasi
php artisan serve

# Jalankan frontend
npm run dev
```

## Penggunaan
Berikut adalah langkah-langkah untuk menggunakan proyek ini:
* **Login**: Pengguna dapat melakukan login dengan menggunakan email dan password.
* **Verifikasi Email**: Pengguna harus melakukan verifikasi email sebelum dapat menggunakan fitur lainnya.
* **Manajemen Produk**: Admin dapat melakukan CRUD pada produk.
* **Manajemen Kategori**: Admin dapat melakukan CRUD pada kategori.
* **Manajemen Order**: Admin dapat melakukan CRUD pada order.

## API Dokumentasi
API dokumentasi dapat diakses pada [https://documenter.getpostman.com/view/30209921/2sAYQXpt6H#f3ee72d2-ae30-4b21-abe9-106016ac75f6](https://documenter.getpostman.com/view/30209921/2sAYQXpt6H#f3ee72d2-ae30-4b21-abe9-106016ac75f6)

## Lisensi
Proyek ini menggunakan lisensi MIT.

## Kontak
Jika Anda memiliki pertanyaan atau ingin mengetahui lebih lanjut tentang proyek ini, silakan hubungi [bimapopo345](https://github.com/bimapopo345).
