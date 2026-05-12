# AGENTS.md — Laravel 12 RBAC koperasi app (Breeze Blade + Spatie)

## Setup

```powershell
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed                 # creates roles, permissions, menus, periode, 3 users
```

`DB_CONNECTION=sqlite` → `database/database.sqlite`. Session/cache/queue default to `database` driver.

## Dev servers

```powershell
composer run dev          # concurrently: artisan serve + queue:listen + pail + npm run dev
php artisan serve
npm run dev               # Vite HMR
php artisan pail          # tail logs
```

## Testing

```powershell
php artisan test
php artisan test --filter=SomeTest --testsuite=Feature
```

Unit tests extend `PHPUnit\Framework\TestCase` (no Laravel boot); Feature tests extend `Tests\TestCase`.

## Code quality

```powershell
.\vendor\bin\pint         # PSR-12 formatting (Laravel Pint)
npm run build             # production Vite build
```

## Auth & RBAC

- **Breeze** (Blade stack) + **Spatie/laravel-permission**
- `User` uses `HasRoles` trait
- Middleware aliases in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`
- Controllers implement `HasMiddleware` with `permission:*` middleware
- Admin routes under `/admin` prefix, auth+verified required, permission-gated per CRUD action

Seeder creates 4 roles: Super Admin (all perms), Admin (all perms), Manager (view + approve), User (none). 3 users seeded:

| Email | Password | Role |
|---|---|---|
| admin@example.com | password | Super Admin |
| manager@example.com | password | Manager |
| user@example.com | password | User |

Permissions: `role-{list,create,edit,delete}`, `permission-*`, `menu-*`, `anggota-*`, `bunga-pinjaman-*`, `simpanan-*`, `pinjaman-*` + `pinjaman-approve`, `angsuran-*`, `kas-*`, `buku-kredit-list`, `periode-*`.

## Menu system

- `menus` table: parent/child hierarchy, icon, route/url, permission, order, active flag
- `menu_role` pivot for role-based visibility
- `Menu::isVisibleByUser()` — Super Admin sees all; otherwise checks permission + role

Menu tree:
- **System** (admin only): Roles, Permissions, Menus, Bunga Pinjaman, Periode
- **Anggota** (admin + manager)
- **Transaksi**: Simpanan, Pengajuan (pinjaman CRUD), Angsuran, Kas (Buku Kas), Buku Kredit

Extra routes: `POST /admin/pinjaman/{pinjaman}/approve|reject`, `GET /admin/pinjaman/simulasi`, `GET /admin/pinjaman/{pinjaman}/cetak-kontrak`. Dashboard (`/admin/dashboard`) shows anggota count, simpanan totals per jenis, Chart.js bar chart — filtered by active periode.

## Localization

- Default locale `id` (`APP_LOCALE` in `.env`). Switch via `GET /lang/{locale}` (accepts `id` or `en`). `Localization` middleware appended to all web routes in `bootstrap/app.php`. Lang files in `lang/{id,en}`.

## Periode (Tahun Buku)

- `periodes` table: `tahun` (string 4), `nama` (nullable), `is_active` (one active at a time)
- `Periode::getActive()` / `getActiveId()` helpers
- `Simpanan` & `Pinjaman` auto-assigned `periode_id` from active periode via `creating` boot event
- Dashboard, Simpanan & Pinjaman index display Periode column, filtered by active periode
- CRUD under System menu, `periode-*` permissions; seed creates current year

## Gotchas

- `.env` gitignored — copy from `.env.example` on fresh clone
- `php artisan optimize:clear` after modifying middleware aliases or config
- Indonesian model names need `protected $table` to avoid English pluralizer bug (e.g. `BungaPinjaman` → `bunga_pinjamen`, `Angsuran` → `angsurans`)
- Route parameter naming differs per resource: `bunga-pinjaman` → `bungaPinjaman` (explicit override), `anggota` → `anggota`; others use default singular snake_case
- Feature tests use `array` cache + `sync` queue (see `phpunit.xml`)
- `Pinjaman` status values: `diajukan` (default), `disetujui`, `ditolak`, `aktif`, `lunas`, `macet`
- `Anggota` auto-generates `kode` on create (`AG-00001`, `AG-00002`, …)
- Create anggota can also create **Simpanan Pokok** (via `simpanan_pokok` field) — auto-creates Simpanan record with `jenis: pokok`
- Simpanan Pokok can't be deleted or have its `jenis` changed while anggota is active
- `/admin/anggota/{anggota}` shows member detail, total simpanan per jenis, list pinjaman per status
