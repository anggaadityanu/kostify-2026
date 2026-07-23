<div align="center">

# UJIAN AKHIR SEMESTER (UAS)
# Kostify

**Sistem Pemesanan dan Pengelolaan Kos & Kontrakan Berbasis Web**

[🌐 Website](https://kostify.my.id) · [📄 Laporan Capstone](./dokumen/Laporan%20Capstone.pdf) · [📋 BRD](./dokumen/BRD%20Kostify%20revised.pdf) · [📋 PRD](./dokumen/PRODUCT%20REQUIREMENT%20DOCUMENT.pdf)

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Filament](https://img.shields.io/badge/Filament-v3-F59E0B?style=for-the-badge)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![MariaDB](https://img.shields.io/badge/MariaDB-10.11-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com)
[![Midtrans](https://img.shields.io/badge/Midtrans-Payment-1F73B7?style=for-the-badge)](https://midtrans.com)

</div>

---

## Informasi Proyek

| Informasi | Keterangan |
|---|---|
| Nama proyek | Kostify |
| Judul lengkap | Sistem Pemesanan dan Pengelolaan Kos & Kontrakan Berbasis Web |
| Jenis proyek | Capstone Project — Jalur Web |
| Nama | Angga Aditya Nugraha |
| NIM | 20240801165 |
| Program Studi | Teknik Informatika |
| Fakultas | Fakultas Ilmu Komputer |
| Universitas | Universitas Esa Unggul |
| Semester / TA | Genap 2025–2026 |
| Dosen Pembimbing | Jefry Sunupurwa Asri, S.Kom., M.Kom. |
| Metodologi | Agile Development — Scrum |
| Website | [kostify.my.id](https://kostify.my.id) |

---

## Tentang Proyek

Kostify adalah sistem informasi berbasis web yang mengintegrasikan seluruh proses bisnis pengelolaan kos dan kontrakan ke dalam satu platform — mulai dari pencarian properti, pemesanan kamar, pembayaran daring, pengelolaan tagihan, perpanjangan masa sewa, hingga penanganan keluhan penyewa.

Proyek ini disusun sebagai **Capstone Project mata kuliah Pemrograman Web**, Program Studi Teknik Informatika, Fakultas Ilmu Komputer, Universitas Esa Unggul, dengan mengacu pada *Business Requirement Document* (BRD) dan *Product Requirement Document* (PRD) yang telah disusun sebelum tahap implementasi.

### Latar Belakang

Sebelum Kostify dikembangkan, proses pencarian kamar, pemesanan, pembayaran, hingga pencatatan data penyewa pada usaha kos umumnya masih dilakukan secara manual melalui media sosial, aplikasi pesan instan, atau pencatatan spreadsheet. Cara ini rawan menimbulkan keterlambatan pembaruan data, risiko *double booking*, sulitnya memantau status pembayaran, serta pelayanan yang kurang optimal kepada penyewa.

Kostify dikembangkan untuk menjawab permasalahan tersebut dengan menyediakan satu platform terpusat yang dapat diakses oleh tiga aktor utama: **Penyewa (Tenant)**, **Admin**, dan **Pemilik Usaha (Owner)** — masing-masing dengan hak akses yang disesuaikan dengan tanggung jawabnya.

---

## Fitur Utama

### Dasbor Penyewa (Tenant)
- Registrasi & login (email/kata sandi atau Google OAuth), reset password
- Pelengkapan profil dan pengunggahan dokumen identitas (KTP/KK)
- Pencarian dan detail kamar lengkap dengan foto, fasilitas, dan lokasi pada peta (Google Maps)
- Pemesanan kamar dengan kalkulasi estimasi biaya otomatis
- Pemantauan status booking secara real-time
- Pembayaran sewa daring via Midtrans, serta unduh bukti pembayaran
- Perpanjangan masa sewa online tanpa perlu booking ulang
- Pengajuan keluhan dan percakapan langsung dengan admin

### Dasbor Admin
- Manajemen data properti, kamar, dan penyewa
- Verifikasi dokumen identitas serta persetujuan booking
- Penerbitan tagihan otomatis dan konfirmasi pembayaran manual (fallback)
- Pengelolaan status dan percakapan keluhan
- Pengelolaan konten halaman publik (CMS) dan pengaturan situs
- Manajemen pengguna serta role & akses (Filament Shield)
- Monitoring aktivitas sistem dan statistik operasional

### Dasbor Pemilik Usaha (Owner)
- Monitoring properti, kamar, dan tingkat hunian miliknya
- Monitoring booking dan status kamar secara real-time
- Ringkasan pendapatan dan riwayat pembayaran
- Monitoring keluhan yang masuk pada propertinya
- Akses bersifat *read-only* terhadap data operasional (tidak dapat mengubah data master)

> Rincian kebutuhan fungsional per aktor (User Story) dan kebutuhan non-fungsional (keamanan, performa, usability, dsb.) didokumentasikan lengkap pada Bab IV Laporan Capstone.

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend framework | Laravel 12 (PHP 8.2) |
| Admin/Owner panel | Filament v3 |
| Antarmuka interaktif | Livewire 3, Blade |
| Styling | Tailwind CSS |
| Basis data | MariaDB 10.11 |
| Containerization | Docker & Docker Compose, Nginx |
| Payment gateway | Midtrans |
| Autentikasi sosial | Google OAuth |
| Peta lokasi | Google Maps API |
| Role & permission | Spatie Permission / Filament Shield |
| Version control | Git & GitHub |

---

## Metodologi Pengembangan

Kostify dikembangkan menggunakan pendekatan **Agile Development dengan framework Scrum**, dipilih karena kemampuannya mengakomodasi perubahan kebutuhan serta mendukung penyelesaian proyek secara bertahap melalui evaluasi pada setiap siklus (*sprint*).

Alur pengembangan mengikuti tahapan: **Identifikasi Masalah → Pengumpulan Data → Analisis Kebutuhan → Perancangan Sistem (UML, ERD, wireframe) → Implementasi → Pengujian → Evaluasi**, sebagaimana dijabarkan pada Bab III Laporan Capstone.

| Sprint | Fokus Pengembangan |
|---|---|
| Sprint 1 | Autentikasi, pengelolaan properti & kamar, dashboard admin |
| Sprint 2 | Proses booking, pembaruan status kamar otomatis, estimasi biaya |
| Sprint 3 | Verifikasi booking, tagihan otomatis, pembayaran Midtrans, perpanjangan sewa |
| Sprint 4 | Verifikasi dokumen identitas, pengajuan & percakapan keluhan |
| Sprint 5 | CMS website, integrasi Google Maps, dashboard owner, notifikasi surel, ekspor laporan |

---

## Perancangan Sistem

Perancangan sistem disusun menggunakan **Unified Modeling Language (UML)** untuk pemodelan proses bisnis dan **Entity Relationship Diagram (ERD)** untuk struktur basis data, dengan tiga aktor utama: Tenant, Admin, dan Owner.

Entitas basis data utama meliputi: `users`, `properties`, `rooms`, `bookings`, `payments`, `complaints`, `complaint_messages`, `tenants`, `contracts`, dan `settings` — dengan `properties` memiliki relasi *one-to-many* terhadap `rooms`, dan setiap `booking` berelasi dengan `payment` serta (bila ada) `complaint`.

Dokumentasi lengkap use case diagram, activity diagram (pemesanan & pembayaran), ERD, serta wireframe seluruh halaman (landing page, login, registrasi, dashboard tiap aktor, booking, pembayaran, keluhan) tersedia pada Bab IV Laporan Capstone.

---

## Pengujian

Pengujian sistem dilakukan menggunakan dua metode:

- **Black Box Testing** — menguji kesesuaian input dan output setiap fitur utama (registrasi, login, pencarian properti, booking, pembayaran, upload dokumen, keluhan, dashboard admin/owner, CMS) tanpa melihat struktur kode. Seluruh skenario pengujian dinyatakan **berhasil**.
- **User Acceptance Testing (UAT)** — menggunakan kuesioner skala Likert 5 tingkat untuk mengukur penerimaan pengguna dari sisi kemudahan penggunaan, kelengkapan fitur, tampilan antarmuka, dan kinerja aplikasi.

Hasil pengujian selengkapnya terdapat pada Bab V Laporan Capstone.

---

## Struktur Proyek

```text
.
├── db/                     # Konfigurasi MariaDB
├── nginx/                  # Konfigurasi web server & SSL
├── php/                    # Dockerfile & konfigurasi PHP-FPM
├── dokumen/                # BRD, PRD, Laporan Capstone
└── src/                    # Source code aplikasi Laravel
    ├── app/
    │   ├── Filament/Admin/     # Resource & panel Admin/Owner
    │   ├── Livewire/           # Komponen Tenant (Booking, Dashboard, dll.)
    │   ├── Models/             # User, Property, Room, Booking, Payment, dll.
    │   └── Policies/           # Otorisasi akses per role
    ├── database/
    │   ├── migrations/
    │   └── seeders/
    ├── resources/views/
    └── routes/
```

---

## Instalasi & Menjalankan Proyek

### Requirement

- Docker & Docker Compose
- Git
- Node.js (untuk build asset frontend)

### 1. Clone repository

```bash
git clone https://github.com/anggaadityanu/kostify-2026.git
cd kostify-2026
```

### 2. Siapkan file environment

```bash
cp src/.env.example src/.env
```

Sesuaikan variabel berikut pada `src/.env`:

```env
APP_NAME="Kostify"
APP_URL=http://localhost

DB_CONNECTION=mariadb
DB_HOST=db
DB_DATABASE=kostify
DB_USERNAME=root
DB_PASSWORD=p455w0rd

MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

### 3. Jalankan container

```bash
docker compose up -d --build
```

### 4. Install dependency & siapkan basis data

```bash
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate --seed
```

### 5. Build asset frontend

```bash
docker compose exec php npm install
docker compose exec php npm run build
```


> **Catatan:** panel Admin dan Owner menggunakan satu panel Filament yang sama (`/admin`), dengan hak akses dibedakan melalui role & policy — Owner memperoleh akses *read-only* terhadap sebagian besar resource.

### Perintah pemeliharaan yang berguna

```bash
docker compose exec php php artisan optimize:clear
docker compose exec php php artisan filament:clear-cached-components
docker compose exec php npm run build
```

---

## Ruang Lingkup & Batasan

**Termasuk dalam ruang lingkup:** pencarian & booking properti, pembayaran daring, perpanjangan sewa, pengelolaan tagihan, penanganan keluhan, autentikasi Google, integrasi peta lokasi, serta dashboard multi-role.

**Tidak termasuk (batasan penelitian):** verifikasi identitas otomatis (OCR KTP), refund otomatis, aplikasi mobile Android/iOS, sistem akuntansi lengkap, dan dukungan multi-bahasa.

---

## Dokumentasi Proyek

- [Business Requirement Document (BRD)](./dokumen/BRD%20Kostify%20revised.pdf)
- [Product Requirement Document (PRD)](./dokumen/PRODUCT%20REQUIREMENT%20DOCUMENT.pdf)
- [Laporan Capstone Project](./dokumen/Laporan%20Capstone.pdf)

---

## Pengembang

**Angga Aditya Nugraha**
NIM: 20240801165
Program Studi Teknik Informatika
Fakultas Ilmu Komputer, Universitas Esa Unggul

GitHub: [@anggaadityanu](https://github.com/anggaadityanu)

---

## Catatan Keamanan

Repository ini **tidak boleh** memuat:
- File `.env` beserta kredensial di dalamnya
- Midtrans Server Key / Client Key produksi
- Kredensial Google OAuth
- Data pribadi penyewa (KTP/KK) atau bukti pembayaran nyata

Seluruh data pada dokumentasi publik dan demo menggunakan data dummy/seeder.

---

## Lisensi

Proyek ini disusun untuk memenuhi tugas **Capstone Project — Pemrograman Web**, Universitas Esa Unggul. Hak penggunaan dan pengembangan lebih lanjut berada pada pengembang.