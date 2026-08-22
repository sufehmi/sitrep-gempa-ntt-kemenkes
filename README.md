# Sitrep Gempa NTT — Kemenkes

Dashboard monitoring dampak gempa bumi NTT (7 kabupaten terdampak) untuk **Pusat Krisis Kesehatan (Pusat Krisis) Kemenkes**.

🌐 **Live:** https://ntt.tanggap-bencana.go.id
📡 **API Docs:** https://ntt.tanggap-bencana.go.id/api-docs
🔐 **API:** `GET /api/v1/*` (read-only, butuh `X-API-Key`)

---

## Kabupaten Terdampak

1. Sikka
2. Manggarai Timur
3. Manggarai
4. Ngada
5. Nagekeo
6. Ende
7. Manggarai Barat

## 7 Tab Dashboard

| # | Tab | Sumber Data |
|---|-----|-------------|
| 1 | Analisa Ringkasan Harian | Excel dataset (4 sheet) |
| 2 | Situasi Kesehatan & Populasi | Excel dataset |
| 3 | Kondisi Pasien RS | Excel dataset (Triase) |
| 4 | Kondisi Pasien Puskesmas | Excel dataset (Triase) |
| 5 | Data Studio (Google) | Embedded iframe |
| 6 | Linktree Sitrep NTT | `https://linktr.ee/gempantt` |
| 7 | Input Data | Form (butuh login) |

## Tech Stack

- **Framework:** Laravel 13.26.1
- **PHP:** 8.4.24
- **Database:** SQLite (file `database/database.sqlite`)
- **UI:** Tailwind CSS via CDN + Inter / JetBrains Mono
- **Auth:** Bcrypt (form login) + SHA1 gate (Manage User, `/update`)
- **Excel import:** OpenSpout 5.10
- **API:** REST, JSON, read-only

## Quick Start (Local)

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Setup database
touch database/database.sqlite
php artisan migrate --seed

# 4. Run
php artisan serve
# → http://127.0.0.1:8000
```

**Default login** (set via seeder, lihat `password.ini` lokal untuk nilai):
- Username: `admin`
- Password: lihat `password.ini` → `[default_user] password` (file di-gitignore, **tidak ada di repo**)

**SHA1 gates** (lihat `password.ini` lokal untuk nilai):
- `Manage User` page: lihat `password.ini` → `[sha1_gates] manage_user_sha1`
- `/update` (edit/hapus data): SHA1 sama (lihat `password.ini` → `[sha1_gates] update_gate_sha1`)

> File `password.ini` di-gitignore. Untuk dapat nilai, ambil langsung dari
> server (`/home/rcce/<env>/password.ini`) atau dari catatan internal tim.

## Project Structure

```
app/
  Http/Controllers/
    ApiController.php          # /api/v1/* (public read-only)
    DashboardController.php    # / (main dashboard)
    InputDataController.php    # /input/* (form input, auth required)
    UpdateController.php       # /update/* (edit/hapus, SHA1 gate)
    UserController.php         # /users (Manage User, API key CRUD)
  Http/Middleware/
    UpdateSession.php          # Gate session check
  Models/
    Kabupaten.php
    AnalisaRingkasan.php
    SituasiKesehatan.php
    KondisiPasienRs.php
    KondisiPasienPuskesmas.php
    User.php
    ApiKey.php
database/
  migrations/                  # 6 tables
  seeders/
    DashboardSeeder.php        # Import from xlsx (OpenSpout)
resources/
  views/
    dashboard.blade.php        # Main 7-tab dashboard
    input/                     # 4 form views
    update/                    # Gate, list, 4 edit forms
    users/                     # Manage User + API key UI
    public/api-docs.blade.php  # /api-docs documentation page
    auth/                      # Login
routes/
  web.php                      # All routes (dashboard, input, update, api-docs)
  api.php                      # (Currently empty — API lives in web.php for v1)
```

## API Endpoints (v1)

```
GET  /api/v1/             → metadata + endpoint list
GET  /api/v1/kabupaten    → 7 kabupaten
GET  /api/v1/analisa      → analisa ringkasan harian
GET  /api/v1/situasi      → situasi kesehatan
GET  /api/v1/rs           → kondisi pasien RS
GET  /api/v1/puskesmas    → kondisi pasien Puskesmas
```

**Auth:** `X-API-Key: ntt_xxx` header atau `?api_key=...` query
**Filter:** `?kabupaten_id=67&tanggal=2026-08-19`
**Response:** `{ "data": [...], "meta": { "count", "generated_at", "filters" } }`

API key dibuat di menu **Manage User** (gate SHA1). Plaintext key hanya disimpan sebagai SHA-256 hash.

## Features

- ✅ **Read-only API** untuk konsumsi eksternal (portal, dashboard lain)
- ✅ **API key management** dengan tracking usage & last_used_at
- ✅ **Input form** dengan validasi (merah/kuning/hijau/hitam minimal salah satu)
- ✅ **Upsert** (insert-or-update by kabupaten + tanggal) agar user tidak duplicate input
- ✅ **Edit/Hapus** via `/update` dengan SHA1 gate (tidak butuh login user)
- ✅ **Multi-user** dengan role admin
- ✅ **Responsive** (mobile-friendly)
- ✅ **Static API docs page** (`/api-docs`)

## Deployment

```bash
# Upload source
rsync -avz --exclude='.env' --exclude='vendor' --exclude='database/*.sqlite' \
  ./ user@server:/path/to/app/

# On server:
cd /path/to/app
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --force   # Import Excel dataset
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run with PHP built-in (or use php-fpm + nginx)
php artisan serve --host=0.0.0.0 --port=9501
```

Lokasi deployment: `/home/rcce/ntt.tanggap-bencana.go.id/` di server `202.153.128.66` (port 9501).

## License

Internal — Kementerian Kesehatan RI (Kemenkes)
© 2026 Pokja RCCE (Risk Communication and Community Engagement)
