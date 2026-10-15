# Portofolio — Aan Setiawan

Website portofolio pribadi **Aan Setiawan** (Web Developer & Fotografer).
Dibangun dengan **Laravel 13** + **Supabase** (PostgREST & Storage). Tampilan
memakai CSS murni (tanpa build step) dan JavaScript vanilla, jadi ringan dan
mudah dirawat sendiri.

Repo ini pribadi. Jangan bagikan `.env` atau kunci Supabase ke siapa pun.

---

## Daftar isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Prasyarat](#prasyarat)
- [Struktur berkas penting](#struktur-berkas-penting)
- [Instalasi](#instalasi)
- [Konfigurasi `.env`](#konfigurasi-env)
- [Setup Supabase](#setup-supabase)
- [Menjalankan](#menjalankan)
- [Panel Admin](#panel-admin)
- [Data & Cache](#data--cache)
- [Konfigurasi profil & konten](#konfigurasi-profil--konten)
- [Testing](#testing)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Keamanan](#keamanan)

---

## Fitur

Halaman publik:
- **Beranda** — hero, tentang, layanan, grid logo teknologi, barisan kartu
  proyek unggulan, cuplikan fotografi, proses kerja, ajakan kontak.
- **Proyek** — kumpulan seluruh proyek (grid).
- **Detail Proyek** — tiap kartu proyek membuka halaman rincian di **tab baru**
  (`/proyek/{id}`), berisi gambar besar, kategori, deskripsi, tag, tombol link,
  dan daftar proyek terkait.
- **Fotografi** — galeri masonry dengan filter kategori.
- **Kontak** — formulir pesan (tersimpan ke Supabase) + info kontak & sosial.

Sentuhan interaktif:
- **Splash intro** animasi saat pertama kali dibuka (sekali per sesi).
- **Animasi reveal** saat scroll, progress bar, efek parallax, dan hover.
- Menghormati `prefers-reduced-motion`.

Panel admin:
- Login sederhana dengan password (tanpa database user).
- CRUD **Proyek** dan **Foto**.
- Upload gambar langsung ke **Supabase Storage** (bucket `portfolio`).
- Perubahan otomatis membersihkan cache halaman publik.

Ketahanan:
- Bila Supabase belum dikonfigurasi / kosong, halaman memakai **data contoh**
  dari `config/portfolio.php` agar situs tetap tampil rapi.

## Teknologi

| Lapisan        | Dipakai                                             |
|----------------|-----------------------------------------------------|
| Bahasa         | PHP `^8.3` (diuji di 8.4)                           |
| Framework      | Laravel `^13.0`                                     |
| Backend data   | Supabase (PostgreSQL, PostgREST, Storage)           |
| Tampilan       | Blade + CSS murni (`public/css`) + JS vanilla       |
| Testing        | PHPUnit `^12`                                       |
| Build (opsional) | Vite + Tailwind (`package.json`) — **tidak wajib** |

Tidak ada dependensi Composer di luar Laravel/Tinker. Aset dimuat langsung dari
`public/` (bukan lewat Vite), jadi **tidak perlu** `npm run build` untuk
menjalankan situs.

## Prasyarat

- PHP 8.3+ dengan ekstensi umum (`curl`, `openssl`, `mbstring`, `fileinfo`,
  `pdo_sqlite`, `json`).
- Composer 2.
- Akun [Supabase](https://supabase.com).
- Node.js (opsional, hanya bila ingin mengutak-atik Tailwind/Vite).

## Struktur berkas penting

```
app/
  Http/Controllers/PageController.php        # halaman publik + simpan kontak
  Http/Controllers/Admin/AuthController.php  # login/logout admin
  Http/Controllers/Admin/ProjectController.php
  Http/Controllers/Admin/PhotoController.php
  Http/Middleware/EnsureAdmin.php            # proteksi rute admin
  Services/Supabase.php                      # klien REST + Storage Supabase

config/
  portfolio.php   # profil, sosial, layanan, tools, tech_stack, data contoh
  supabase.php    # url, anon_key, service_key, cache_ttl
  admin.php       # password admin & nama bucket storage

resources/views/
  layouts/app.blade.php        layouts/admin.blade.php
  home.blade.php  projects.blade.php  project.blade.php
  photography.blade.php  contact.blade.php
  partials/  (nav, intro, project, dsb.)
  admin/     (login, projects, photos)

public/
  css/site.css  css/intro.css  css/admin.css
  js/site.js
  supabase/schema.sql          # SQL tabel + RLS (jalankan di Supabase)

routes/web.php                 # rute publik & admin
supabase/schema.sql
tests/Feature/AdminAccessTest.php
```

## Instalasi

```bash
# 1. Dependensi PHP
composer install

# 2. Siapkan file lingkungan
copy .env.example .env          # Windows (PowerShell)
# cp .env.example .env           # Linux/macOS
php artisan key:generate

# 3. Isi .env (lihat bagian berikut) lalu siapkan Supabase
php artisan migrate            # membuat tabel cache & session lokal (sqlite)

# 4. Jalankan
php artisan serve --port=8001
```

Buka `http://127.0.0.1:8001`.

> Catatan: `.env` memakai `SESSION_DRIVER=database` dan `CACHE_STORE=database`,
> jadi `php artisan migrate` wajib dijalankan sekali agar sesi admin dan cache
> bekerja.

## Konfigurasi `.env`

Variabel yang relevan untuk proyek ini:

```dotenv
APP_NAME="Aan Setiawan"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=id
APP_TIMEZONE=Asia/Makassar    # opsional

# Supabase (Settings > API di dashboard)
SUPABASE_URL=https://xxxxxxxx.supabase.co
SUPABASE_ANON_KEY=...          # key publik (publishable) — untuk MEMBACA data
SUPABASE_SERVICE_KEY=...       # RAHASIA (secret) — server saja, jangan dibagikan
SUPABASE_CACHE_TTL=300

# Admin
ADMIN_PASSWORD=ganti-password-ku

# Kontak & profil
CONTACT_EMAIL=email-anda@contoh.com
CONTACT_PHONE=+62...
PROFILE_PHOTO_URL=
```

| Variabel | Wajib | Keterangan |
|----------|:---:|------------|
| `SUPABASE_URL` | ya | URL proyek Supabase. |
| `SUPABASE_ANON_KEY` | ya | Kunci publik, untuk **membaca** proyek & foto. |
| `SUPABASE_SERVICE_KEY` | untuk admin/kontak | Kunci rahasia; dipakai server untuk simpan pesan, CRUD admin, upload file. |
| `SUPABASE_CACHE_TTL` | tidak | Masa cache detik (default `300`). |
| `ADMIN_PASSWORD` | ya | Password masuk panel admin. |
| `CONTACT_EMAIL`, `CONTACT_PHONE` | tidak | Ditampilkan di halaman kontak. |
| `PROFILE_PHOTO_URL` | tidak | URL foto profil di beranda. |

## Setup Supabase

1. Buat proyek baru di https://supabase.com.
2. **SQL Editor → New query**, tempel isi `supabase/schema.sql`, lalu **Run**.
   Ini membuat tabel `projects`, `photos`, `messages` beserta kebijakan RLS.
3. **Storage → New bucket** bernama `portfolio`, centang **Public**. Bucket ini
   menampung gambar proyek & foto yang diunggah lewat panel admin.
4. **Settings → API**: salin **Project URL**, **anon/public key**, dan
   **service_role/secret key** ke `.env`.

Skema tabel:

- `projects` — `id, title, category, description, image_url, tags text[], link, sort, created_at`
- `photos`   — `id, title, category, image_url, sort, created_at`
- `messages` — `id, name, email, subject, message, created_at`

RLS: `projects` & `photos` boleh dibaca publik; `messages` hanya bisa ditulis
server (service key) dan dibaca dari dashboard Supabase.

## Menjalankan

```bash
php artisan serve --port=8001
```

Port `8000` sering sudah terpakai di mesin ini, jadi dipakai `8001`. Rute utama:

| URL | Halaman |
|-----|---------|
| `/` | Beranda |
| `/proyek` | Daftar proyek |
| `/proyek/{id}` | Detail proyek (buka tab baru dari kartu) |
| `/fotografi` | Galeri foto |
| `/kontak` | Kontak |
| `/admin/login` | Login admin |

## Panel Admin

- Masuk: `http://127.0.0.1:8001/admin/login`
- Password: nilai `ADMIN_PASSWORD` di `.env` (saat ini `aan123` — **segera ganti**).
- Setelah masuk: `/admin` → otomatis ke daftar proyek.
  - `/admin/proyek` — daftar proyek; tambah / edit / hapus.
  - `/admin/foto` — kelola foto.
- Unggah gambar dari form akan otomatis dikirim ke Supabase Storage dan URL
  publiknya disimpan ke kolom `image_url`.
- Setelah setiap perubahan, cache halaman publik dibersihkan otomatis.

Autentikasi memakai password disimpan di sesi (`is_admin`), dilindungi middleware
`EnsureAdmin`. Tidak ada tabel user — cukup untuk pemakaian pribadi.

## Data & Cache

- Hasil query Supabase di-cache selama `SUPABASE_CACHE_TTL` detik (default 300).
- Panel admin memanggil `Cache::forget(...)` tiap menyimpan/menghapus, jadi
  perubahan langsung terlihat.
- Paksa bersihkan manual: `php artisan cache:clear`.
- Bila Supabase kosong, data contoh dari `config/portfolio.php` (`sample_projects`,
  `sample_photos`) yang dipakai.

## Konfigurasi profil & konten

Sebagian besar teks profil diatur di `config/portfolio.php`:

- `name`, `initials`, `role`, `tagline`, `bio`
- `socials` (Instagram, GitHub, LinkedIn)
- `stats`, `services`, `tools`, `tech_stack`, `process`
- `sample_projects`, `sample_photos` (fallback)
- `email`, `phone`, `location`

Identitas dinamis lain lewat `.env`: `CONTACT_EMAIL`, `CONTACT_PHONE`,
`PROFILE_PHOTO_URL`.

Logo teknologi di beranda memakai ikon dari `https://cdn.simpleicons.org/{slug}`.
Karena Simple Icons menghapus logo Adobe, Lightroom & Photoshop ditampilkan
sebagai badge monogram (`Lr`/`Ps`) — atur lewat kunci `mark`, `tone`, `bg`.

## Testing

```bash
php artisan test
```

Saat ini: **6 test / 16 asersi** (berjalan). Konfigurasi test ada di
`phpunit.xml` (memakai `SESSION_DRIVER=array`, `ADMIN_PASSWORD=secret-test`,
dan mengosongkan kredensial Supabase agar tidak menyentuh jaringan).
Test admin ada di `tests/Feature/AdminAccessTest.php`.

## Deployment

Karena aset dimuat dari `public/`, cukup arahkan document root web server ke
folder `public/`. Langkah umum:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`, lalu isi kredensial
Supabase produksi. Tidak perlu `npm run build` kecuali ikut memakai Vite/Tailwind.

## Troubleshooting

- **Error SSL / `cURL error 60` (Windows)** — unduh `cacert.pem`, lalu set
  `curl.cainfo` dan `openssl.cafile` di `php.ini` ke path berkas itu, lalu
  restart server.
- **Port 8000 terpakai** — jalankan di port lain: `php artisan serve --port=8001`.
- **Data tampil lama / tidak berubah** — `php artisan cache:clear`.
- **Upload gambar gagal** — pastikan bucket Storage `portfolio` ada & **Public**,
  dan `SUPABASE_SERVICE_KEY` benar di `.env`.
- **Kontak gagal terkirim** — pastikan `SUPABASE_SERVICE_KEY` terisi dan tabel
  `messages` sudah dibuat (`supabase/schema.sql`).
- **Gambar rusak di sel foto** — pastikan `sqlite` sudah di-migrate
  (`php artisan migrate`).

## Keamanan

- `.env` **jangan** di-commit. Pastikan `.gitignore` memuat `.env`.
- `SUPABASE_SERVICE_KEY` adalah kunci rahasia level server — jangan pernah
  diekspos ke sisi klien atau dibagikan. Bila pernah bocor, **rotate** di
  Supabase (Settings → API → Reset).
- Ganti `ADMIN_PASSWORD` dari nilai contoh. Login admin memakai `hash_equals`
  untuk perbandingan aman, dan sesi di-regenerate saat login.
- Formulir kontak dilindungi CSRF, validasi, honeypot, dan rate limit
  (5 kiriman / 10 menit per IP).

---

Dibuat untuk pemakaian pribadi Aan Setiawan.
#   F I N A L . 0 4 0  
 