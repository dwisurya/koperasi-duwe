<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
            'menu-list', 'menu-create', 'menu-edit', 'menu-delete',
            'anggota-list', 'anggota-create', 'anggota-edit', 'anggota-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'bunga-pinjaman-list', 'bunga-pinjaman-create', 'bunga-pinjaman-edit', 'bunga-pinjaman-delete',
            'simpanan-list', 'simpanan-create', 'simpanan-edit', 'simpanan-delete',
            'pinjaman-list', 'pinjaman-create', 'pinjaman-edit', 'pinjaman-delete',
            'pinjaman-approve',
            'angsuran-list', 'angsuran-create', 'angsuran-edit', 'angsuran-delete',
            'kas-list', 'kas-create', 'kas-edit', 'kas-delete',
            'buku-kredit-list',
            'buku-tabungan-list',
            'titip-dana-list', 'titip-dana-create', 'titip-dana-edit', 'titip-dana-delete',
            'periode-list', 'periode-create', 'periode-edit', 'periode-delete',
            'kategori-aktiva-list', 'kategori-aktiva-create', 'kategori-aktiva-edit', 'kategori-aktiva-delete',
            'kategori-passiva-list', 'kategori-passiva-create', 'kategori-passiva-edit', 'kategori-passiva-delete',
            'akun-keuangan-list', 'akun-keuangan-create', 'akun-keuangan-edit', 'akun-keuangan-delete',
            'akun-modal-list', 'akun-modal-create', 'akun-modal-edit', 'akun-modal-delete',
            'jenis-simpanan-list', 'jenis-simpanan-create', 'jenis-simpanan-edit', 'jenis-simpanan-delete',
            'persentase-shu-list', 'persentase-shu-create', 'persentase-shu-edit', 'persentase-shu-delete',
            'neraca-list',
            'shu-list', 'shu-distribute',
            'voting-list',
            'berita-acara-list',
            'utility-backup', 'utility-import', 'utility-export', 'utility-log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'pinjaman-list', 'pinjaman-approve',
            'anggota-list',
            'simpanan-list',
            'angsuran-list',
            'kas-list',
            'buku-kredit-list',
            'neraca-list',
            'shu-list',
            'buku-tabungan-list',
        ]);

        $user = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        // === CLEANUP: remove old/renamed menus ===
        Menu::where('route', 'admin.members.index')->delete();
        Menu::where('route', 'admin.kategori-aktiva.index')->delete();
        Menu::where('route', 'admin.kategori-passiva.index')->delete();
        Menu::where('route', 'admin.akun-keuangan.index')->delete();
        Menu::whereIn('name', ['Transaksi', 'Transaksi Simpanan', 'Transaksi Pinjaman', 'RAT'])->delete();

        // === 1. MASTER DATA ===
        $masterData = Menu::updateOrCreate(
            ['name' => 'Master Data'],
            [
                'icon' => 'bi bi-database',
                'route' => null,
                'url' => null,
                'order' => 1,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $masterData->id)->delete();

        $masterDataItems = [
            ['name' => 'Anggota', 'route' => 'admin.anggota.index', 'permission' => 'anggota-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Jenis Simpanan', 'route' => 'admin.jenis-simpanan.index', 'permission' => 'jenis-simpanan-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Pengaturan Bunga', 'route' => 'admin.bunga-pinjaman.index', 'permission' => 'bunga-pinjaman-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Persentase SHU', 'route' => 'admin.persentase-shu.index', 'permission' => 'persentase-shu-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($masterDataItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $masterData->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // Akun Keuangan sub-parent under Master Data
        $akunKeuangan = Menu::updateOrCreate(
            ['name' => 'Akun Keuangan', 'parent_id' => $masterData->id],
            [
                'route' => null,
                'icon' => 'bi bi-journal',
                'url' => null,
                'permission' => null,
                'order' => 7,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $akunKeuangan->id)->delete();

        $akunKeuanganItems = [
            ['name' => 'Aktiva', 'route' => 'admin.akun-aktiva.index', 'permission' => 'kategori-aktiva-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Passiva', 'route' => 'admin.akun-passiva.index', 'permission' => 'kategori-passiva-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Modal', 'route' => 'admin.akun-modal.index', 'permission' => 'akun-modal-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($akunKeuanganItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $akunKeuangan->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 2. SIMPANAN ===
        $simpanan = Menu::updateOrCreate(
            ['name' => 'Simpanan'],
            [
                'icon' => 'bi bi-piggy-bank',
                'route' => null,
                'url' => null,
                'order' => 2,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $simpanan->id)->delete();

        $simpananItems = [
            ['name' => 'Simpanan Pokok', 'route' => 'admin.simpanan.pokok', 'permission' => 'simpanan-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Simpanan Wajib', 'route' => 'admin.simpanan.wajib', 'permission' => 'simpanan-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Tabungan Penyertaan', 'route' => 'admin.simpanan.penyertaan', 'permission' => 'simpanan-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Buku Tabungan', 'route' => 'admin.buku-tabungan.index', 'permission' => 'buku-tabungan-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Titipan Dana', 'route' => 'admin.titip-dana.index', 'permission' => 'titip-dana-list', 'order' => 5, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($simpananItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $simpanan->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 3. PINJAMAN ===
        $pinjaman = Menu::updateOrCreate(
            ['name' => 'Pinjaman'],
            [
                'icon' => 'bi bi-credit-card',
                'route' => null,
                'url' => null,
                'order' => 3,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $pinjaman->id)->delete();

        $pinjamanItems = [
            ['name' => 'Pengajuan Pinjaman', 'route' => 'admin.pinjaman.pengajuan', 'permission' => 'pinjaman-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Approval Pinjaman', 'route' => 'admin.pinjaman.approval', 'permission' => 'pinjaman-approve', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Pencairan Pinjaman', 'route' => 'admin.pinjaman.pencairan', 'permission' => 'pinjaman-approve', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Angsuran', 'route' => 'admin.angsuran.index', 'permission' => 'angsuran-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Buku Kredit', 'route' => 'admin.buku-kredit.index', 'permission' => 'buku-kredit-list', 'order' => 5, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Simulasi Pinjaman', 'route' => 'admin.pinjaman.simulasi', 'permission' => 'pinjaman-list', 'order' => 6, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
        ];

        foreach ($pinjamanItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $pinjaman->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 4. KEUANGAN ===
        $keuangan = Menu::updateOrCreate(
            ['name' => 'Keuangan'],
            [
                'icon' => 'bi bi-wallet2',
                'route' => null,
                'url' => null,
                'order' => 4,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $keuangan->id)->delete();

        // Kas & Bank sub-parent
        $kasBank = Menu::updateOrCreate(
            ['name' => 'Kas & Bank', 'parent_id' => $keuangan->id],
            [
                'route' => null,
                'icon' => 'bi bi-bank',
                'url' => null,
                'permission' => null,
                'order' => 1,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $kasBank->id)->delete();

        $kasBankItems = [
            ['name' => 'Kas Brankas', 'route' => 'admin.keuangan.kas-brankas', 'permission' => 'kas-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rekening BRI', 'route' => 'admin.keuangan.rekening-bri', 'permission' => 'kas-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rekening LPD', 'route' => 'admin.keuangan.rekening-lpd', 'permission' => 'kas-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
        ];

        foreach ($kasBankItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $kasBank->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // Direct children of Keuangan (after Kas & Bank)
        $keuanganDirectItems = [
            ['name' => 'Pendapatan', 'route' => 'admin.keuangan.pendapatan', 'permission' => 'kas-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Pengeluaran', 'route' => 'admin.keuangan.pengeluaran', 'permission' => 'kas-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Dana Sosial', 'route' => 'admin.keuangan.dana-sosial', 'permission' => 'kas-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Dana Pengurus', 'route' => 'admin.keuangan.dana-pengurus', 'permission' => 'kas-list', 'order' => 5, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Dana RAT', 'route' => 'admin.keuangan.dana-rapat', 'permission' => 'kas-list', 'order' => 6, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Cadangan Modal', 'route' => 'admin.keuangan.cadangan-modal', 'permission' => 'kas-list', 'order' => 7, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Cadangan Risiko', 'route' => 'admin.keuangan.cadangan-resiko', 'permission' => 'kas-list', 'order' => 8, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Penyertaan', 'route' => 'admin.keuangan.penyertaan', 'permission' => 'kas-list', 'order' => 9, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
        ];

        foreach ($keuanganDirectItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $keuangan->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 5. RAT & SHU ===
        $rat = Menu::updateOrCreate(
            ['name' => 'RAT & SHU'],
            [
                'icon' => 'bi bi-people',
                'route' => null,
                'url' => null,
                'order' => 5,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $rat->id)->delete();

        $ratItems = [
            ['name' => 'Pembagian SHU', 'route' => 'admin.shu.index', 'permission' => 'shu-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Voting', 'route' => 'admin.rat.voting', 'permission' => 'voting-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Berita Acara', 'route' => 'admin.rat.berita-acara', 'permission' => 'berita-acara-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($ratItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $rat->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 6. LAPORAN ===
        $laporan = Menu::updateOrCreate(
            ['name' => 'Laporan'],
            [
                'icon' => 'bi bi-file-earmark-text',
                'route' => null,
                'url' => null,
                'order' => 6,
                'is_active' => true,
            ]
        );
        Menu::where('parent_id', $laporan->id)->delete();

        $laporanItems = [
            ['name' => 'Neraca', 'route' => 'admin.neraca.index', 'permission' => 'neraca-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rugi Laba', 'route' => 'admin.laporan.rugi-laba', 'permission' => 'neraca-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Arus Kas', 'route' => 'admin.laporan.arus-kas', 'permission' => 'neraca-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Saldo Anggota', 'route' => 'admin.laporan.saldo-anggota', 'permission' => 'neraca-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Tunggakan', 'route' => 'admin.laporan.tunggakan', 'permission' => 'neraca-list', 'order' => 5, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rekap Simpanan', 'route' => 'admin.laporan.rekap-simpanan', 'permission' => 'neraca-list', 'order' => 6, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rekap Pinjaman', 'route' => 'admin.laporan.rekap-pinjaman', 'permission' => 'neraca-list', 'order' => 7, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
            ['name' => 'Rekap Angsuran', 'route' => 'admin.laporan.rekap-angsuran', 'permission' => 'neraca-list', 'order' => 8, 'roles' => [$superAdmin->id, $admin->id, $manager->id]],
        ];

        foreach ($laporanItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $laporan->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 7. UTILITY ===
        $utility = Menu::updateOrCreate(
            ['name' => 'Utility'],
            [
                'icon' => 'bi bi-tools',
                'route' => null,
                'url' => null,
                'order' => 9,
                'is_active' => true,
            ]
        );

        $utilityItems = [
            ['name' => 'Backup Database', 'route' => 'admin.utility.backup', 'permission' => 'utility-backup', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Import Excel', 'route' => 'admin.utility.import', 'permission' => 'utility-import', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Export Excel', 'route' => 'admin.utility.export', 'permission' => 'utility-export', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Activity Log', 'route' => 'admin.utility.activity-log', 'permission' => 'utility-log', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($utilityItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $utility->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }

        // === 8. SISTEM ===
        $sistem = Menu::updateOrCreate(
            ['name' => 'Sistem'],
            [
                'icon' => null,
                'route' => null,
                'url' => null,
                'order' => 99,
                'is_active' => true,
            ]
        );

        $sistemItems = [
            ['name' => 'Menu', 'route' => 'admin.menus.index', 'permission' => 'menu-list', 'order' => 1, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Pengguna', 'route' => 'admin.users.index', 'permission' => 'user-list', 'order' => 2, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Role', 'route' => 'admin.roles.index', 'permission' => 'role-list', 'order' => 3, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Permission', 'route' => 'admin.permissions.index', 'permission' => 'permission-list', 'order' => 4, 'roles' => [$superAdmin->id, $admin->id]],
            ['name' => 'Periode', 'route' => 'admin.periodes.index', 'permission' => 'periode-list', 'order' => 5, 'roles' => [$superAdmin->id, $admin->id]],
        ];

        foreach ($sistemItems as $item) {
            $menu = Menu::updateOrCreate(
                ['name' => $item['name'], 'parent_id' => $sistem->id],
                [
                    'route' => $item['route'],
                    'icon' => null,
                    'url' => null,
                    'permission' => $item['permission'],
                    'order' => $item['order'],
                    'is_active' => true,
                ]
            );
            $menu->roles()->sync($item['roles']);
        }
    }
}
