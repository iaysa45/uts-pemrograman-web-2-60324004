UTS Pemrograman Web - Sistem Manajemen Kategori Buku

1. Deskripsi Project

Project ini merupakan tugas Ujian Tengah Semester (UTS) mata kuliah Pemrograman Web yang bertujuan untuk membuat sistem manajemen kategori buku berbasis PHP Native dan MySQL.

Aplikasi ini memungkinkan pengguna untuk melakukan operasi CRUD (Create, Read, Update, Delete) terhadap data kategori buku dengan menerapkan konsep keamanan seperti prepared statement dan validasi input.

2. Struktur Folder
uts_[NIM]/
├── config/
│   └── database.php
├── index.php
├── create.php
├── edit.php
└── delete.php

4. Spesifikasi Database

Nama Database: uts_perpustakaan_[60324004]
Charset: utf8mb4
Collation: utf8mb4_unicode_ci


 Fitur Aplikasi

Read (Menampilkan Data)

* Menampilkan daftar kategori dalam tabel
* Data diurutkan berdasarkan terbaru
* Menggunakan Bootstrap untuk tampilan

Create (Tambah Data)

* Form input kategori baru
* Validasi:

  * Kode harus diawali `KAT-`
  * Panjang kode 4–10 karakter
  * Tidak boleh duplikat
  * Nama minimal 3 karakter
* Menggunakan prepared statement

Update (Edit Data)

* Edit data kategori berdasarkan ID
* Form otomatis terisi (pre-filled)
* Validasi sama seperti create
* Cek duplikasi kecuali data sendiri

 Delete (Hapus Data)

* Hapus data berdasarkan ID
* Konfirmasi sebelum delete
* Menggunakan prepared statement

Keamanan

* Menggunakan Prepared Statement untuk mencegah SQL Injection
* Sanitasi input dengan:

  * `htmlspecialchars()`
  * `trim()`
* Validasi server-side untuk semua input


Teknologi yang Digunakan

* PHP Native
* MySQL / MariaDB
* Bootstrap 5
* HTML & CSS

Cara Menjalankan Project

1. Import database ke phpMyAdmin
2. Buat database dengan nama:

   uts_perpustakaan_[60324004]

3. Jalankan query untuk membuat tabel dan insert data
4. Letakkan folder project di:

   htdocs/ (XAMPP)

5. Jalankan di browser:

   http://localhost/uts_[]60324004
   

## ✅ Kesimpulan

Project ini berhasil mengimplementasikan operasi CRUD dengan baik menggunakan PHP Native dan MySQL, serta menerapkan keamanan dasar dalam pengolahan data.

---
