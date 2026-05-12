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
            'bunga-pinjaman-list', 'bunga-pinjaman-create', 'bunga-pinjaman-edit', 'bunga-pinjaman-delete',
            'simpanan-list', 'simpanan-create', 'simpanan-edit', 'simpanan-delete',
            'pinjaman-list', 'pinjaman-create', 'pinjaman-edit', 'pinjaman-delete',
            'pinjaman-approve',
            'periode-list', 'periode-create', 'periode-edit', 'periode-delete',
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
        ]);

        $user = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        $systemManagement = Menu::updateOrCreate(
            ['name' => 'System'],
            [
                'icon' => null,
                'route' => null,
                'url' => null,
                'order' => 99,
                'is_active' => true,
            ]
        );

        $rolesMenu = Menu::firstOrCreate(
            ['name' => 'Roles', 'parent_id' => $systemManagement->id],
            [
                'route' => 'admin.roles.index',
                'icon' => null,
                'url' => null,
                'permission' => 'role-list',
                'order' => 1,
                'is_active' => true,
            ]
        );
        $rolesMenu->roles()->sync([$superAdmin->id, $admin->id]);

        $permissionsMenu = Menu::firstOrCreate(
            ['name' => 'Permissions', 'parent_id' => $systemManagement->id],
            [
                'route' => 'admin.permissions.index',
                'icon' => null,
                'url' => null,
                'permission' => 'permission-list',
                'order' => 2,
                'is_active' => true,
            ]
        );
        $permissionsMenu->roles()->sync([$superAdmin->id, $admin->id]);

        $menusMenu = Menu::firstOrCreate(
            ['name' => 'Menus', 'parent_id' => $systemManagement->id],
            [
                'route' => 'admin.menus.index',
                'icon' => null,
                'url' => null,
                'permission' => 'menu-list',
                'order' => 3,
                'is_active' => true,
            ]
        );
        $menusMenu->roles()->sync([$superAdmin->id, $admin->id]);

        $bungaPinjamanMenu = Menu::firstOrCreate(
            ['name' => 'Bunga Pinjaman', 'parent_id' => $systemManagement->id],
            [
                'route' => 'admin.bunga-pinjaman.index',
                'icon' => null,
                'url' => null,
                'permission' => 'bunga-pinjaman-list',
                'order' => 4,
                'is_active' => true,
            ]
        );
        $bungaPinjamanMenu->roles()->sync([$superAdmin->id, $admin->id]);

        $simpananMenu = Menu::firstOrCreate(
            ['name' => 'Simpanan'],
            [
                'parent_id' => null,
                'route' => 'admin.simpanan.index',
                'icon' => null,
                'url' => null,
                'permission' => 'simpanan-list',
                'order' => 6,
                'is_active' => true,
            ]
        );
        $simpananMenu->roles()->sync([$superAdmin->id, $admin->id, $manager->id]);

        Menu::where('route', 'admin.members.index')->delete();

        $anggotaMenu = Menu::firstOrCreate(
            ['name' => 'Anggota'],
            [
                'parent_id' => null,
                'route' => 'admin.anggota.index',
                'icon' => null,
                'url' => null,
                'permission' => 'anggota-list',
                'order' => 5,
                'is_active' => true,
            ]
        );
        $anggotaMenu->roles()->sync([$superAdmin->id, $admin->id, $manager->id]);

        Menu::where('name', 'Pinjaman')->orWhere('name', 'Pengajuan')->delete();

        $pinjamanMenu = Menu::create([
            'name' => 'Pengajuan',
            'parent_id' => null,
            'route' => 'admin.pinjaman.index',
            'icon' => null,
            'url' => null,
            'permission' => 'pinjaman-list',
            'order' => 7,
            'is_active' => true,
        ]);
        $pinjamanMenu->roles()->sync([$superAdmin->id, $admin->id, $manager->id]);

        $periodeMenu = Menu::firstOrCreate(
            ['name' => 'Periode', 'parent_id' => $systemManagement->id],
            [
                'route' => 'admin.periodes.index',
                'icon' => null,
                'url' => null,
                'permission' => 'periode-list',
                'order' => 5,
                'is_active' => true,
            ]
        );
        $periodeMenu->roles()->sync([$superAdmin->id, $admin->id]);
    }
}
