# AGENTS.md — Laravel 12.x app (RBAC admin panel)

## Setup

```powershell
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed                 # creates roles, permissions, default menus + users
```

`DB_CONNECTION=sqlite` → `database/database.sqlite`. Session/cache/queue default to `database` driver.

## Dev servers

```powershell
composer run dev          # concurrently: php artisan serve + queue:listen + pail + npm run dev
php artisan serve         # individual
npm run dev               # Vite HMR
php artisan pail          # tail logs
```

## Testing

```powershell
php artisan test                  # preferred
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
- `User` model uses `HasRoles` trait
- Middleware aliases in `bootstrap/app.php`: `role`, `permission`, `role_or_permission`
- Controllers implement `HasMiddleware` with `permission:*` middleware
- Admin routes under `/admin` prefix, auth+verified required, permission-gated per CRUD action

Seeder creates 4 roles: Super Admin (all perms), Admin (all perms), Manager (view + approve), User (no perms).

| Email | Password | Role |
|---|---|---|
| admin@example.com | password | Super Admin |
| manager@example.com | password | Manager |
| user@example.com | password | User |

Permissions: `role-list/create/edit/delete`, `permission-list/create/edit/delete`, `menu-list/create/edit/delete`, `anggota-list/create/edit/delete`, `bunga-pinjaman-list/create/edit/delete`, `simpanan-list/create/edit/delete`, `pinjaman-list/create/edit/delete`, `pinjaman-approve`.

## Menu system

- `menus` table: parent/child hierarchy, icon, route/url, permission, order, active flag
- `menu_role` pivot for role-based visibility
- `Menu::isVisibleByUser()` — Super Admin sees all; otherwise checks permission + role
- Bunga Pinjaman CRUD under System menu with `bunga-pinjaman-*` permissions; table `bunga_pinjaman`, model `BungaPinjaman`
- Simpanan CRUD (**standalone** menu, not under System) with `simpanan-*` permissions; table `simpanan`, model `Simpanan`; jenis: pokok, wajib, sukarela, bagi_hasil
- Admin dashboard (`/admin/dashboard`) displays anggota count, simpanan totals per jenis, and Chart.js bar chart for simpanan vs pinjaman
- Pengajuan (Pinjaman) CRUD as standalone menu with `pinjaman-*` permissions; table `pinjaman`, model `Pinjaman`; linked to anggota + bunga_pinjaman; bunga can be overridden per loan
  - Approval workflow: Manager role with `pinjaman-approve` permission can approve/reject diajukan records via POST `/admin/pinjaman/{pinjaman}/approve` or `/reject`
  - Installment simulation at `/admin/pinjaman/simulasi` (GET, params: nominal, bunga, tenor), also available inline in create form
  - Contract printing at `/admin/pinjaman/{pinjaman}/cetak-kontrak` (printable HTML view)

## Gotchas

- **`.env` is gitignored** — copy from `.env.example` on fresh clone
- `php artisan optimize:clear` after adding/modifying middleware aliases or config
- Seeder (`RolePermissionSeeder`) must run after `migrate`
- Indonesian model names need `protected $table` to avoid English pluralizer bug (e.g. `BungaPinjaman` → `bunga_pinjamen`)
- Feature tests use `array` cache + `sync` queue (see `phpunit.xml`)
- Route parameter naming differs per resource: `bunga-pinjaman` → `bungaPinjaman` (explicit override), `anggota` → `anggota`; others use default singular snake_case
- `Anggota` auto-generates `kode` on create (`AG-00001`, `AG-00002`, …)
- `Anggota` has `saldo_awal` field (decimal, default 0), shown/edited in create/edit forms with rupiah formatting
- `Anggota` has `simpanan()` and `pinjaman()` hasMany relationships
- `/admin/anggota/{anggota}` (`admin.anggota.show`) displays member detail, saldo (awal + total simpanan = akhir), list simpanan per jenis, and list pinjaman per status
- `Pinjaman` status values: `diajukan`, `disetujui`, `ditolak`, `aktif`, `lunas`, `macet` (used in `getStatusLabelAttribute` / `getStatusColorAttribute`). Default on create: `diajukan`.
