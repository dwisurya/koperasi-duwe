# AGENTS.md — Laravel 12 RBAC koperasi app (Breeze Blade + Spatie)

## Setup

```powershell
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed                 # creates roles, permissions, menus, periode, seed data
php artisan optimize:clear          # after any config/middleware change
```

`DB_CONNECTION=sqlite` → `database/database.sqlite`. Session/cache/queue all use `database` driver — side effects on DB are expected.

## Dev

```powershell
composer run dev          # concurrently: artisan serve + queue:listen + pail + npm run dev
php artisan serve
npm run dev               # Vite HMR
php artisan pail          # tail logs
.\vendor\bin\pint         # PSR-12 formatting (Laravel Pint)
npm run build             # production Vite build
```

## Testing

```powershell
php artisan test
php artisan test --filter=SomeTest --testsuite=Feature
```

Unit tests extend `PHPUnit\Framework\TestCase` (no Laravel boot); Feature tests extend `Tests\TestCase`.

## Stack notes

- **Web only** — no API routes, no Sanctum/Passport
- **No CI/CD** — no `.github/` workflows
- **Frontend**: Bootstrap 5, jQuery, DataTables, Bootstrap Icons — all via npm/Vite. Chart.js loaded via CDN in dashboard only.
- **No Horizon, Telescope, or Pulse** installed

## Auth & RBAC

- **Breeze** (Blade stack) + **Spatie/laravel-permission**
- `User` uses `HasRoles` trait (`User.php:14`)
- Middleware aliases in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`
- Controllers implement `HasMiddleware` with `permission:*` middleware — see `AnggotaController.php`, `PinjamanController.php`
- Admin routes under `/admin` prefix, auth+verified required, permission-gated per CRUD action

Seeder creates 4 roles: Super Admin (all perms), Admin (all perms), Manager (list+approve), User (none). 3 users:

| Email | Password | Role |
|---|---|---|
| admin@example.com | password | Super Admin |
| manager@example.com | password | Manager |
| user@example.com | password | User |

Permissions: `{resource}-{list,create,edit,delete}`. Resources: role, permission, menu, anggota, bunga-pinjaman, simpanan, pinjaman, angsuran, kas, periode, kategori-aktiva, kategori-passiva, akun-keuangan, jenis-simpanan, akun-modal, persentase-shu, titip-dana, user. Extra: `pinjaman-approve`, `buku-kredit-list`, `buku-tabungan-list`, `neraca-list`, `shu-list`, `shu-distribute`, `voting-list`, `berita-acara-list`, `utility-{backup,import,export,log}`.

## Menu structure (defined in `RolePermissionSeeder.php`)

- **Master Data**: Anggota, Jenis Simpanan, Pengaturan Bunga, Persentase SHU, Akun Keuangan (→ Aktiva, Passiva, Modal)
- **Simpanan**: Simpanan Pokok, Simpanan Wajib, Tabungan Penyertaan, Buku Tabungan, Titipan Dana
- **Pinjaman**: Pengajuan, Approval, Pencairan, Angsuran, Buku Kredit, Simulasi
- **Keuangan**: Kas & Bank (Brankas, BRI, LPD), Pendapatan, Pengeluaran, Dana Sosial, Dana Pengurus, Dana RAT, Cadangan Modal, Cadangan Risiko, Penyertaan
- **RAT & SHU**: Pembagian SHU, Voting, Berita Acara
- **Laporan**: Neraca, Rugi Laba, Arus Kas, Saldo Anggota, Tunggakan, Rekap Simpanan/Pinjaman/Angsuran
- **Utility**: Backup, Import/Export, Activity Log
- **Sistem**: Menu, Pengguna, Role, Permission, Periode

## Periode (Tahun Buku)

- `periodes` table: `tahun` (string 4), `nama`, `is_active` (exactly one active at a time)
- `Periode::getActive()` / `getActiveId()` helpers
- `Simpanan` & `Pinjaman` auto-assign `periode_id` from active periode via `creating` boot event
- `scopePeriodeAktif()` on models filters by active periode

## Model side effects (key gotchas)

- `Simpanan::created` → auto-creates `Kas` entry (jenis: masuk)
- `Pinjaman::updated` (status→aktif) → auto-creates `Kas` entry (jenis: keluar)
- `Angsuran::created` → auto-creates `Kas` entry (jenis: masuk)
- `Anggota::creating` → auto-generates `kode` format: `{0001}/Duwe/{VI}/26` (4-digit padded ID / Duwe / Roman month / 2-digit year)
- `AnggotaController::store` checks `simpanan_pokok` field → creates `Simpanan` record (jenis: pokok)

## Indonesian model table names

Must set `protected $table` to avoid pluralizer bugs:

| Model | Table |
|---|---|
| `Simpanan` | `simpanan` (not `simpanans`) |
| `Pinjaman` | `pinjaman` (not `pinjamen`) |
| `BungaPinjaman` | `bunga_pinjaman` (not `bunga_pinjamen`) |
| `Anggota` | `anggotas` |
| `Angsuran` | `angsurans` |

## Route param overrides

`routes/web.php` maps URI slugs to route model binding keys:
- `bunga-pinjaman` → `{bungaPinjaman}`
- `akun-aktiva` → `{kategoriAktiva}`
- `akun-passiva` → `{kategoriPassiva}`
- `akun-keuangan` → `{akunKeuangan}`
- `jenis-simpanan` → `{jenisSimpanan}`
- `akun-modal` → `{akunModal}`
- `persentase-shu` → `{persentaseShu}`
- `titip-dana` → `{titipDana}`

## Pinjaman status values

`diajukan` (default), `disetujui`, `ditolak`, `aktif`, `lunas`, `macet`

## Localization

`GET /lang/{locale}` (accepts `id` or `en`). `Localization` middleware on all web routes. Lang files in `lang/{id,en}`.

## Seed data detail

`php artisan db:seed` creates: 3 users + roles, current-year periode, KategoriAktiva (Kas & Bank), KategoriPassiva (Dana Sosial, Modal), AkunKeuangan (Kas/BRI/Dana Sosial/Simpanan Pokok), 11 Kas opening entries, 6 PersentaseSHU allocations, 4 BungaPinjaman tiers, 4 JenisSimpanan, 6 AkunModal, full menu tree with role pivots.
