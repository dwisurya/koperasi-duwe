<?php

namespace Database\Seeders;

use App\Models\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        $users = [
            ['name' => 'Super Admin', 'email' => 'admin@example.com', 'role' => 'Super Admin'],
            ['name' => 'Manager', 'email' => 'manager@example.com', 'role' => 'Manager'],
            ['name' => 'Test User', 'email' => 'user@example.com', 'role' => 'User'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($data['role']);
        }

        try {
            Periode::firstOrCreate(
                ['tahun' => date('Y')],
                ['nama' => 'Tahun Buku '.date('Y'), 'is_active' => true]
            );
        } catch (\Throwable $e) {
            echo 'Periode creation skipped: '.$e->getMessage().PHP_EOL;
        }
    }
}
