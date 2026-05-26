# Sistem Pelaporan Parkir Liar

Website pelaporan parkir liar berbasis PHP Native dan MySQL yang memungkinkan masyarakat melaporkan kendaraan atau lokasi parkir ilegal secara online. Sistem ini dilengkapi fitur GPS otomatis, monitoring progress laporan, peta pelanggaran, dan dashboard admin untuk verifikasi laporan.

---

## Deskripsi Proyek

Sistem ini dibuat untuk membantu masyarakat melaporkan area parkir liar atau kendaraan yang mengganggu ketertiban umum secara cepat dan terdokumentasi.

Pengguna dapat:

- Mengirim laporan parkir liar
- Upload foto kendaraan/lokasi
- Menggunakan GPS otomatis
- Melihat progress penanganan laporan
- Melihat riwayat laporan

Admin dapat:

- Mengelola laporan masyarakat
- Mengubah status laporan
- Monitoring lokasi pada peta
- Melihat grafik statistik laporan
- Mengelola akun pengguna

---

## Fitur Sistem

### User

- Login & Register
- Dashboard User
- Membuat Laporan Parkir Liar
- Upload Foto Lokasi/Kendaraan
- GPS Otomatis (Latitude & Longitude)
- Riwayat Laporan
- Tracking Progress Laporan
- Logout

### Admin

- Dashboard Admin
- Monitoring Data Laporan
- Detail Laporan
- Update Status Laporan
- Peta Pelanggaran
- Grafik Pelaporan
- Manajemen User (CRUD)
- Logout

---

## Status Laporan

Laporan memiliki beberapa tahapan proses:

```text
Dikirim
↓
Diverifikasi
↓
Diproses
↓
Ditindak
↓
Selesai
```

---

## Teknologi yang Digunakan

### Backend

- PHP Native
- MySQL
- Session Authentication

### Frontend

- HTML5
- CSS3
- Bootstrap 5
- Bootstrap Icons
- JavaScript

### Library Tambahan

- Leaflet.js (Peta)
- Chart.js (Grafik)

---

## Struktur Folder

```text
project/
│── admin/
│   ├── dashboard.php
│   ├── laporan.php
│   ├── detail_laporan.php
│   ├── update_status.php
│   ├── peta.php
│   ├── grafik.php
│   ├── users.php
│   ├── tambah_user.php
│   ├── update_user.php
│   └── hapus_user.php
│
│── user/
│   ├── dashboard.php
│   ├── lapor.php
│   ├── simpan_laporan.php
│   ├── riwayat.php
│   └── tracking.php
│
│── components/
│   ├── sidebar.php
│   ├── sidebar_user.php
│   └── header.php
│
│── assets/
│   └── uploads/
│       └── laporan/
│
│── config/
│   └── koneksi.php
│
│── database/
│   └── parkir_liar.sql
│
│── index.php
│── login.php
│── register.php
│── logout.php
└── README.md
```

---

## Database

Database yang digunakan:

```text
parkir_liar
```

Import file SQL:

```text
database/parkir_liar.sql
```

---

## Cara Menjalankan Project

### 1. Clone atau Download Project

Simpan project ke folder:

```text
htdocs/
```

Contoh:

```text
C:/xampp/htdocs/parkir-liar
```

---

### 2. Jalankan XAMPP

Aktifkan:

- Apache
- MySQL

---

### 3. Import Database

1. Buka phpMyAdmin
2. Buat database:

```text
parkir_liar
```

3. Import file:

```text
parkir_liar.sql
```

---

### 4. Konfigurasi Database

Edit file:

```text
config/koneksi.php
```

Contoh:

```php
<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "parkir_liar"
);
?>
```

---

### 5. Jalankan Website

Buka browser:

```text
http://localhost/parkir-liar
```

---

## Akun Default

### Admin

```text
Email    : admin@mail.com
Password : admin123
```

### User

Buat akun melalui menu register.

---

## Screenshot Fitur

### Dashboard User

- Statistik laporan
- Riwayat laporan
- Monitoring progress

### Dashboard Admin

- Statistik laporan
- Data laporan masyarakat
- Grafik pelaporan
- Peta pelanggaran

---

## Alur Sistem

```text
User Login
    ↓
Buat Laporan
    ↓
Upload Foto + GPS
    ↓
Data Masuk ke Admin
    ↓
Admin Verifikasi
    ↓
Admin Update Status
    ↓
User Melihat Progress
```

---

## Pengembang

Proyek dibuat untuk kebutuhan tugas / pengembangan sistem informasi pelaporan parkir liar masyarakat.

---

## Lisensi

Project ini bebas digunakan untuk kebutuhan pembelajaran dan pengembangan akademik.
