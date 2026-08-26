# 📚 Digital Library Management System (PHP Native & MySQL)

Aplikasi sistem manajemen perpustakaan digital interaktif dengan autentikasi multi-role, katalog berbasis AJAX, sistem manajemen denda otomatis, serta visualisasi data analitik untuk administrator.

---

## 🚀 Fitur Utama

- **Autentikasi & Multi-Role:** Pemisahan hak akses antara Member dan Administrator menggunakan `password_hash()` yang aman.
- **Katalog Interaktif (AJAX Fetch API):** Pencarian instan berdasarkan judul/pengarang dan filter kategori tanpa reload halaman.
- **Sistem Peminjaman & Denda Otomatis:** Perhitungan masa pinjam 7 hari dengan deteksi denda keterlambatan dinamis dan pemblokiran akun otomatis.
- **Admin Analytics Dashboard:** Visualisasi data ringkasan status peminjaman dan distribusi kategori buku menggunakan **Chart.js**.
- **Modern UI & UX:** Notifikasi modal interaktif dengan **SweetAlert2** dan dukungan mode gelap (*Dark Mode*).

---

## 🛠️ Tech Stack

- **Backend:** PHP Native (Procedural Clean Code)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 Variables, Vanilla JavaScript (ES6+)
- **Libraries:** [Chart.js](https://www.chartjs.org/), [SweetAlert2](https://sweetalert2.github.io/)
- **Local Server:** Laragon / XAMPP

---

## ⚙️ Panduan Instalasi Lokal

1. **Clone Repository:**
   ```bash
   git clone [https://github.com/ArlV4/digital-library-php.git](https://github.com/ArlV4/digital-library-php.git)
   cd digital-library-php
