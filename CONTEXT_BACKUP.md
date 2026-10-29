# CONTEXT BACKUP — FinStats
Terakhir diperbarui: 2026-10-29
Catatan: Berkas ini adalah snapshot konteks proyek FinStats untuk dilanjutkan di sesi berikutnya. Tidak ada koneksi dengan proyek LATEKAJE v3.

---

## 1. Ringkasan Proyek & Tujuan

**FinStats** — aplikasi pencatatan keuangan pribadi dan tabungan bersama (kelompok), berbasis Laravel 13 + Breeze + SQLite + Vite + Tailwind + Alpine.js.
- Tujuan utama: mencatat pemasukan/pengeluaran pribadi, mengelola tabungan bersama (grup) dengan kode join, dan melihat laporan keuangan dalam bentuk grafik dan tabel.
- UI: Bahasa Indonesia, dark mode, responsif, Chart.js untuk tren keuangan, PhpSpreadsheet untuk export Excel.
- Lokasi kode: `/home/alfin/FinStats`
- Server dev: `php artisan serve --port=8000` → `http://0.0.0.0:8000`
- Database: SQLite (`database/database.sqlite`)
- Auth: Laravel Breeze (login, register, verifikasi email, lupa password, profil, hapus akun)
- Rate limiting: login maksimal 5 attempt per email+IP

---

## 2. Struktur Direktori (penting)

```
/home/alfin/FinStats/
├── artisan
├── composer.json / composer.lock
├── package.json / package-lock.json
├── vite.config.js / tailwind.config.js / postcss.config.js
├── .env (APP_KEY sudah di-generate, DB_CONNECTION=sqlite)
├── .env.example
├── routes/
│   ├── web.php        (dashboard, transaksi, grup, profil)
│   ├── auth.php       (register, login, password, verifikasi email, logout)
│   └── console.php
├── app/
│   ├── Http/Controllers/
│   │   ├── TransactionController.php   (index, store, update, destroy, export)
│   │   ├── GroupController.php         (index, store, join, show, storeTransaction)
│   │   ├── ProfileController.php       (edit, update, destroy)
│   │   └── Auth/                       (Breeze auth controllers)
│   ├── Http/Requests/Auth/LoginRequest.php   (rate limit 5x, login attempt)
│   ├── Http/Requests/ProfileUpdateRequest.php
│   ├── Models/
│   │   ├── User.php        (groups(), transactions())
│   │   ├── Group.php       (users(), transactions())
│   │   └── Transaction.php (user(), group())
│   ├── View/Components/AppLayout.php, GuestLayout.php
│   └── Providers/AppServiceProvider.php (kosong, siap diisi)
├── database/
│   ├── database.sqlite
│   ├── migrations/
│   │   ├── create_users_table
│   │   ├── create_transactions_table (user_id, type enum, amount decimal(15,2), description, date)
│   │   ├── create_groups_table (name, code unique)
│   │   ├── create_group_user_table (group_id, user_id)
│   │   └── add_group_id_to_transactions_table (nullable)
│   ├── factories/UserFactory.php
│   └── seeders/DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── dashboard.blade.php         (DIGUNAKAN routing)
│   │   ├── welcome.blade.php
│   │   ├── auth/ (login, register, ...)
│   │   ├── groups/ (index, show)
│   │   ├── profile/ (edit + partials)
│   │   ├── layouts/ (app, guest, navigation)
│   │   └── components/ (Breeze blade components)
│   ├── css/app.css
│   └── js/app.js (Alpine.js)
├── public/build/ (Vite output: manifest.json, assets/*.css, *.js)
├── tests/ (default Breeze tests, belum ada test transaksi/grup)
├── storage/logs/laravel.log
├── vendor/ (composer install sudah dijalankan)
├── node_modules/ (npm install sudah dijalankan)
└── PROGRESS_LOG.md   (catatan perkembangan)
└── CONTEXT_BACKUP.md (berkas ini)
```

**CATATAN:** File `./dashboard.blade.php` di root direktori adalah file TIDAK DIGUNAKAN (routing pakai `resources/views/dashboard.blade.php`). Direkomendasikan untuk dihapus atau diabaikan agar tidak menimbulkan kebingungan.

---

## 3. Status Saat Ini (Sudah Berjalan & Terverifikasi)

- ✅ Composer install sukses (vendor terpasang)
- ✅ APP_KEY di-generate di `.env`
- ✅ SQLite database terbuat + semua migrasi berjalan
- ✅ npm install + npm run build sukses (public/build/ ada)
- ✅ `php artisan serve --port=8000` berjalan (PID background)
- ✅ `GET /` → welcome (200 OK)
- ✅ `GET /login` → halaman login (200 OK)
- ✅ Registrasi, login, logout, profil berfungsi (Breeze standar)
- ✅ Buat grup, join grup via kode, lihat detail grup berfungsi
- ✅ Tambah/edit/hapus transaksi pribadi dan grup berfungsi
- ✅ Export Excel (`.xlsx`) transaksi pribadi **dan grup** (via `?group_id=`) berfungsi
- ✅ Chart.js grafik tren 7 transaksi terakhir

---

## 4. Hasil Audit Kode (Temuan & Risiko)

### ✅ Sudah Diperbaiki (Batch 1-5)

| No | Masalah Sebelum | Perbaikan | Lokasi |
|----|----------------|-----------|--------|
| 1 | `APP_NAME=Laravel` di `.env` | Diubah jadi `APP_NAME=FinStats` | `.env` |
| 2 | File `dashboard.blade.php` duplikat di root | Dihapus | Root folder |
| 3 | `storeTransaction` tidak cek keanggotaan | Tambah `abort(403)` jika bukan anggota | `GroupController@storeTransaction` |
| 4 | Kode grup bisa tabrakan di DB | Loop generate sampai unik | `GroupController@store` |
| 5 | Edit/hapus transaksi grup tidak verifikasi keanggotaan | Tambah cek `group->users->contains()` | `TransactionController@update`, `@destroy` |
| 6 | Tanggal transaksi boleh masa depan | Validasi `before_or_equal:today` | `TransactionController@store`, `@update`; `GroupController@storeTransaction` |
| 7 | Export Excel hanya pribadi | Dukung `?group_id=` dengan cek otorisasi | `TransactionController@export` + link di `groups/show.blade.php` |
| 8 | N+1 query dashboard (`kas->transactions()`) | Eager load `with('transactions')`, pakai koleksi | `TransactionController@index` + view |
| 9 | N+1 query detail grup (per member) | Eager load `transactions.user`, hitung dari koleksi | `GroupController@show` |
| 10 | Tidak ada pagination transaksi | `paginate(15)` + `$transactions->links()` | `TransactionController@index` + view |
| 11 | API `open.er-api.com` dipanggil setiap halaman | `Cache::remember('kurs_usd_idr', 3600)` | `routes/auth.php` |
| 12 | Hapus transaksi = permanen | Soft deletes (`deleted_at`) + `SoftDeletes` trait | Migration baru + `Transaction` model |

### ⚠️ Masalah yang Masih Terbuka (Backlog)

| No | Masalah | Lokasi | Prioritas |
|----|---------|--------|-----------|
| 1 | Belum ada test fitur transaksi/grup | `tests/` | Tinggi |
| 2 | Belum ada fitur kategori transaksi | `transactions` table | Sedang |
| 3 | Belum ada budget/target harian/bulanan | Baru | Sedang |
| 4 | `APP_URL` di `.env` masih `http://localhost` | `.env` | Rendah |

---

## 5. Daftar File Relevan (untuk pengembangan fitur baru)

### ✅ Hal yang baik
- Auth lengkap Breeze dengan rate limiting login (5 attempt).
- Model relasi tepat: User↔Transaction (1:N), User↔Group (M:N), Transaction↔Group (N:1).
- Authorization cek `user_id !== auth()->id()` pada edit/hapus transaksi.
- Cek keanggotaan grup pada `GroupController@show`.
- CSRF protection, Blade auto-escape (anti-XSS).
- Dark mode, Tailwind, responsive.
- `SESSION_DRIVER=database` (tabel sessions sudah ada).

### ⚠️ Masalah yang ditemukan (perlu diperbaiki)

| No | Masalah | Lokasi | Risiko | Rekomendasi |
|----|---------|--------|--------|-------------|
| 1 | `storeTransaction` tidak cek keanggotaan grup | `GroupController.php` line 92 | User bisa transaksi ke grup lain hanya dengan mengetahui ID | Tambahkan cek `$group->users->contains(auth()->id())` |
| 2 | Edit/hapus transaksi grup tidak membedakan pemilik vs anggota | `TransactionController.php` | Anggota grup bisa edit/hapus transaksi teman grup | Cek apakah transaksi grup, lalu verifikasi pemilik atau peran admin |
| 3 | N+1 query pada dashboard (`totalSemuaKasBersama`) | `TransactionController@index` + view | Lambat saat grup bertambah | Eager load `transactions` atau hitung via query agregat |
| 4 | N+1 query pada detail grup (`memberSavings`) | `GroupController@show` | Lambat saat anggota bertambah | Eager load + agregasi |
| 5 | File `./dashboard.blade.php` di root (tidak digunakan) | Root folder | Kebingungan, file duplikat | Hapus atau pindah ke arsip |
| 6 | Tidak ada pagination transaksi | Controller + views | Lambat saat data banyak | Tambahkan `paginate()` |
| 7 | Tidak ada soft deletes | Migrations | Hapus = permanen, tidak ada audit trail | Pertimbangkan `softDeletes` pada transactions |
| 8 | Kode grup `Str::random(6)` tidak cek uniqueness | `GroupController@store` | Error DB jika tabrakan kode | Loop generate sampai unik |
| 9 | Ekspor Excel hanya transaksi pribadi | `TransactionController@export` | Transaksi grup tidak bisa diekspor | Tambahkan parameter group_id / export gabungan |
| 10 | Validasi tanggal tidak mencegah masa depan | `store()` / `update()` | Data tanggal tidak akurat | Tambahkan rule `before_or_equal:today` |
| 11 | API eksternal `open.er-api.com` dipanggil setiap halaman register/login | `routes/auth.php` | Lambat jika API down | Cache hasil kurs, atau pindahkan ke pengaturan |
| 12 | `APP_NAME=Laravel` di `.env` | `.env` | Inconsistensi dengan tampilan "FinStats" | Ubah jadi `APP_NAME=FinStats` |
| 13 | Belum ada test fitur transaksi/grup | `tests/` | Tidak ada jaminan regresi | Tambahkan FeatureTest untuk transaksi & grup |

---

## 5. Daftar File Relevan (untuk pengembangan fitur baru)

- `app/Http/Controllers/TransactionController.php` — logika transaksi (index, store, update, destroy, export)
- `app/Http/Controllers/GroupController.php` — logika grup (index, store, join, show, storeTransaction)
- `app/Models/Transaction.php`, `app/Models/Group.php`, `app/Models/User.php` — model & relasi
- `database/migrations/` — skema tabel
- `resources/views/dashboard.blade.php` — halaman utama (yang digunakan routing)
- `resources/views/groups/show.blade.php` — detail grup + form transaksi grup
- `routes/web.php` — definisi semua route
- `config/database.php`, `.env` — konfigurasi DB

---

## 6. Status Git & Branch

- Remote: `git@github.com:Waaooow/FinStats.git` (SSH)
- Branch utama: `main` (sudah push commit 127e455)
- Branch aktif saat ini: `pocket-feature` (dibuat dari `main`, belum di-push)
- Commit terbaru: `fix: perbaikan hasil audit FinStats (batch 1-5)`

## 7. Langkah Selanjutnya (Next Steps)

1. **Tambah fitur Pocket** pada branch `pocket-feature`: konsep tabungan pribadi (pocket/saku) untuk pengguna, terpisah dari transaksi harian dan tabungan bersama.
2. **Tambah fitur**: kategori transaksi, budget/target, pagination, export laporan bulanan.
3. **Tambah test**: FeatureTest untuk transaksi pribadi dan grup.
4. **Perbarui `.env`**: `APP_URL` sesuai domain produksi.

---

## 7. Perintah Verifikasi Cepat

```bash
cd /home/alfin/FinStats
# Jalankan server (jika belum)
php artisan serve --port=8000 --host=0.0.0.0

# Jalankan migrasi (jika DB belum siap)
php artisan migrate --force

# Jalankan test
php artisan test

# Build ulang aset frontend
npm run build

# Lihat log error
tail -f storage/logs/laravel.log
```

---
