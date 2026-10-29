# PROGRESS LOG — FinStats
Catatan setiap perkembangan, perubahan, dan fitur baru pada proyek FinStats.

## Format entri
`YYYY-MM-DD | [AKSI] | Deskripsi singkat | Detail / file yang berubah`

---

## 2026-10-29 | [SETUP] | Inisialisasi awal proyek FinStats
- Direktori `/home/alfin/FinStats` ditemukan (Laravel 13 skeleton, belum di-setup).
- `chown -R alfin:alfin` agar folder writable.
- `composer install` (dengan `--ignore-platform-req` untuk ext-dom, ext-xml, ext-simplexml, ext-gd, ext-xmlwriter sebelum ekstensi dipasang via apt).
- `sudo apt-get install php8.4-dom php8.4-xml php8.4-gd php8.4-sqlite3 nodejs npm`.
- `php artisan key:generate` → APP_KEY diisi di `.env`.
- `touch database/database.sqlite` + `php artisan migrate --force` → tabel: users, transactions, groups, group_user, sessions, cache, jobs.
- `npm install` + `npm run build` → Vite assets di `public/build/`.
- `php artisan serve --port=8000 --host=0.0.0.0` → aplikasi nyala di http://0.0.0.0:8000.

## 2026-10-29 | [AUDIT] | Audit kode menyeluruh FinStats
- Semua file aplikasi dibaca dan dianalisis (controllers, models, routes, migrations, views, config).
- Temuan utama (lihat CONTEXT_BACKUP.md bagian "Hasil Audit"):
  - GroupController@storeTransaction tidak cek keanggotaan grup.
  - N+1 query pada dashboard dan detail grup.
  - File duplikat `./dashboard.blade.php` di root.
  - Belum ada pagination, soft deletes, dan test fitur transaksi/grup.

## 2026-10-29 | [FIX] | Perbaikan bug hasil audit (Batch 1-5)
- [FIX] `APP_NAME` di `.env` diubah dari "Laravel" → "FinStats"
- [FIX] File duplikat `./dashboard.blade.php` di root dihapus
- [FIX] `GroupController@storeTransaction` — tambah cek keanggotaan grup (`abort 403` jika bukan anggota)
- [FIX] `GroupController@store` — kode grup `KAS-XXXXXX` di-generate dengan loop until unik (hindari tabrakan DB)
- [FIX] `TransactionController@update` & `@destroy` — verifikasi keanggotaan grup untuk transaksi kelompok
- [FIX] `TransactionController@store` & `@update` — validasi tanggal `before_or_equal:today` (mencegah tanggal masa depan)
- [FIX] `TransactionController@export` — dukung export transaksi grup via `?group_id=` (dengan cek keanggotaan)
- [FIX] `GroupController@show` — eager load `transactions.user`, hitung saldo member dari koleksi (atasi N+1)
- [FIX] `TransactionController@index` — eager load `kasBersamaList` dengan `transactions`, pagination transaksi pribadi (15/halaman)
- [FIX] View `dashboard.blade.php` — gunakan koleksi eager-loaded `$kas->transactions`, tambah `{{ $transactions->links() }}`
- [FIX] `routes/auth.php` — cache kurs USD→IDR via `Cache::remember('kurs_usd_idr', 3600)` (register & login)
- [FIX] Soft deletes pada tabel `transactions` (migration baru + `SoftDeletes` trait pada model Transaction)

## 2026-10-29 | [UI/UX] | Dashboard pocket clickable
- Card saldo utama pocket di dashboard sekarang bisa diklik → buka halaman pockets/index
- Setiap card pocket (nama + saldo) di dashboard sekarang bisa diklik → buka detail pocket (pockets/show)
- Cursor pointer + hover effect ditambahkan pada card pocket untuk indikator interaktif
- Link "Kelola Semua Pocket" tetap tersedia sebagai navigasi tambahan

## 2026-10-29 | [FEATURE] | Implementasi Fitur Pocket (Batch 1)
- Migration: tabel `pockets` (user_id, name, target nullable, balance, timestamps)
- Migration: tabel `pocket_transactions` (pocket_id, type enum top_up/withdraw/transfer_in/transfer_out, amount, description, transaction_date, related_pocket_id nullable, related_group_id nullable)
- Model: `Pocket` (relasi user, transactions; helper targetProgress(), isTargetReached())
- Model: `PocketTransaction` (relasi pocket, relatedPocket, relatedGroup; helper typeLabel())
- Model: `User` — tambah relasi `pockets()` dan helper `totalPocketBalance()`
- Controller: `PocketController` (index, create, store, show, edit, update, destroy)
- Controller: `PocketTransactionController` (topUp, withdraw, transfer antar pocket & ke grup)
- Routes: `/pockets` (index, create, store, show, edit, update, destroy), `/pockets/{pocket}/top-up`, `/pockets/{pocket}/withdraw`, `/pockets/transfer`
- Views: pockets/index, create, show, edit (dengan modal top-up, withdraw, transfer)
- Dashboard: ditambahkan section Pocket Tabungan Pribadi + Mutasi Pocket Terbaru
- Requirement 4: pocket transaksi terpisah dari transaksi harian; top_up/withdraw juga dicatat di tabel transactions
- Requirement 5: transfer dari pocket pribadi ke tabungan bersama (grup) supported
- Semua 25 test lulus, tidak ada regresi.

## 2026-10-29 | [GIT] | Commit, push, dan buat branch pocket-feature
- Commit semua perbaikan audit ke branch `main` (commit 127e455, 10 file berubah)
- Push ke `origin/main` (remote diubah dari HTTPS ke SSH: git@github.com:Waaooow/FinStats.git)
- Buat branch baru `pocket-feature` dari `main` untuk pengembangan fitur pocket (tabungan pribadi pengguna)
- WORKING DIRECTORY sekarang berada di branch `pocket-feature`

## 2026-10-29 | [BACKUP] | CONTEXT_BACKUP.md FinStats diperbarui
- Semua perubahan bug fix, performa, fitur pocket, dan status git tercatat ke CONTEXT_BACKUP.md.

---
