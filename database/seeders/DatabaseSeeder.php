<?php

namespace Database\Seeders;

use App\Models\AkunKeuangan;
use App\Models\AkunModal;
use App\Models\BungaPinjaman;
use App\Models\JenisSimpanan;
use App\Models\Kas;
use App\Models\KategoriAktiva;
use App\Models\KategoriPassiva;
use App\Models\Periode;
use App\Models\PersentaseShu;
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

        try {
            $kasBank = KategoriAktiva::firstOrCreate(
                ['nama' => 'Kas & Bank'],
                ['keterangan' => 'Kas dan Rekening Bank', 'is_active' => true]
            );
            $danaSosial = KategoriPassiva::firstOrCreate(
                ['nama' => 'Dana Sosial'],
                ['keterangan' => 'Dana Sosial dan Kegiatan', 'is_active' => true]
            );
            $modal = KategoriPassiva::firstOrCreate(
                ['nama' => 'Modal'],
                ['keterangan' => 'Modal dan Ekuitas', 'is_active' => true]
            );

            AkunKeuangan::updateOrCreate(
                ['kode' => '101'],
                ['nama' => 'Kas', 'kategori_aktiva_id' => $kasBank->id, 'kategori_passiva_id' => null, 'is_active' => true]
            );
            AkunKeuangan::updateOrCreate(
                ['kode' => '102'],
                ['nama' => 'Bank BRI', 'kategori_aktiva_id' => $kasBank->id, 'kategori_passiva_id' => null, 'is_active' => true]
            );
            AkunKeuangan::updateOrCreate(
                ['kode' => '201'],
                ['nama' => 'Dana Sosial', 'kategori_aktiva_id' => null, 'kategori_passiva_id' => $danaSosial->id, 'is_active' => true]
            );
            AkunKeuangan::updateOrCreate(
                ['kode' => '301'],
                ['nama' => 'Simpanan Pokok', 'kategori_aktiva_id' => null, 'kategori_passiva_id' => $modal->id, 'is_active' => true]
            );
        } catch (\Throwable $e) {
            echo 'Akun keuangan seed skipped: '.$e->getMessage().PHP_EOL;
        }

        try {
            $activePeriode = Periode::getActive();
            if ($activePeriode) {
                $openingEntries = [
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Kas Brankas', 'nominal' => 50000000, 'keterangan' => 'Saldo awal Kas Brankas'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Bank BRI', 'nominal' => 200000000, 'keterangan' => 'Saldo awal Rekening BRI'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Rekening LPD', 'nominal' => 60000000, 'keterangan' => 'Saldo awal Rekening LPD'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Dana Sosial', 'nominal' => 10000000, 'keterangan' => 'Saldo awal Dana Sosial'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Cadangan Modal', 'nominal' => 25000000, 'keterangan' => 'Saldo awal Cadangan Modal'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Cadangan Resiko', 'nominal' => 15000000, 'keterangan' => 'Saldo awal Cadangan Resiko'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Dana Rapat', 'nominal' => 5000000, 'keterangan' => 'Saldo awal Dana Rapat'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'SHU Periode Lalu', 'nominal' => 30000000, 'keterangan' => 'Saldo awal SHU Periode Lalu'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Penyertaan', 'nominal' => 50000000, 'keterangan' => 'Saldo awal Penyertaan'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Pinjam Dana Pura', 'nominal' => 100000000, 'keterangan' => 'Saldo awal Pinjam Dana Pura'],
                    ['tanggal' => $activePeriode->tahun.'-01-01', 'jenis' => 'masuk', 'kategori' => 'Pinjam SUKDUK', 'nominal' => 75000000, 'keterangan' => 'Saldo awal Pinjam SUKDUK'],
                ];

                foreach ($openingEntries as $entry) {
                    Kas::firstOrCreate(
                        ['kategori' => $entry['kategori'], 'keterangan' => $entry['keterangan']],
                        $entry
                    );
                }
            }
        } catch (\Throwable $e) {
            echo 'Kas seed skipped: '.$e->getMessage().PHP_EOL;
        }

        try {
            if (PersentaseShu::count() === 0) {
                $shuData = [
                    ['dana' => 'Dana Pengurus', 'persentase' => 10, 'urutan' => 1],
                    ['dana' => 'Dana Sosial', 'persentase' => 22, 'urutan' => 2],
                    ['dana' => 'Cadangan Modal', 'persentase' => 5, 'urutan' => 3],
                    ['dana' => 'Cadangan Resiko', 'persentase' => 1, 'urutan' => 4],
                    ['dana' => 'Dibagi ke Anggota', 'persentase' => 60, 'urutan' => 5],
                    ['dana' => 'Dana Rapat', 'persentase' => 3, 'urutan' => 6],
                ];
                foreach ($shuData as $data) {
                    PersentaseShu::create($data);
                }
            }
        } catch (\Throwable $e) {
            echo 'Persentase SHU seed skipped: '.$e->getMessage().PHP_EOL;
        }

        try {
            if (BungaPinjaman::count() === 0) {
                $bungaData = [
                    ['nama' => 'Freeze', 'bunga' => 1.5, 'tanggal_berlaku' => '2023-01-01', 'jenis' => 'Flat', 'keterangan' => 'Periode freeze 1.5%', 'is_active' => false],
                    ['nama' => 'Pasca Freeze', 'bunga' => 1.0, 'tanggal_berlaku' => '2023-07-01', 'jenis' => 'Flat', 'keterangan' => 'Setelah freeze 1%', 'is_active' => false],
                    ['nama' => 'Reguler', 'bunga' => 1.0, 'tanggal_berlaku' => '2024-07-01', 'jenis' => 'Flat', 'keterangan' => 'Setelah Juli 2024 1%', 'is_active' => false],
                    ['nama' => 'Baru', 'bunga' => 0.8, 'tanggal_berlaku' => '2025-03-01', 'jenis' => 'Flat', 'keterangan' => 'Setelah Maret 2025 0.8%', 'is_active' => true],
                ];
                foreach ($bungaData as $data) {
                    BungaPinjaman::create($data);
                }
            }
        } catch (\Throwable $e) {
            echo 'Bunga Pinjaman seed skipped: '.$e->getMessage().PHP_EOL;
        }

        try {
            if (JenisSimpanan::count() === 0) {
                $jenis = [
                    ['kode' => 'pokok', 'nama' => 'Simpanan Pokok', 'keterangan' => 'Simpanan pokok anggota', 'is_active' => true],
                    ['kode' => 'wajib', 'nama' => 'Simpanan Wajib', 'keterangan' => 'Simpanan wajib anggota', 'is_active' => true],
                    ['kode' => 'penyertaan', 'nama' => 'Tabungan Penyertaan', 'keterangan' => 'Tabungan penyertaan anggota', 'is_active' => true],
                    ['kode' => 'bagi_hasil', 'nama' => 'Bagi Hasil', 'keterangan' => 'Distribusi bagi hasil', 'is_active' => true],
                ];
                foreach ($jenis as $data) {
                    JenisSimpanan::create($data);
                }
            }
        } catch (\Throwable $e) {
            echo 'Jenis Simpanan seed skipped: '.$e->getMessage().PHP_EOL;
        }

        try {
            if (AkunModal::count() === 0) {
                $modal = [
                    ['kode' => 'M-001', 'nama' => 'Dana Sosial', 'keterangan' => 'Modal Dana Sosial', 'is_active' => true],
                    ['kode' => 'M-002', 'nama' => 'Cadangan Modal', 'keterangan' => 'Cadangan modal koperasi', 'is_active' => true],
                    ['kode' => 'M-003', 'nama' => 'Cadangan Resiko', 'keterangan' => 'Cadangan resiko', 'is_active' => true],
                    ['kode' => 'M-004', 'nama' => 'Dana Rapat', 'keterangan' => 'Dana untuk rapat anggota', 'is_active' => true],
                    ['kode' => 'M-005', 'nama' => 'SHU Periode Lalu', 'keterangan' => 'SHU dari periode sebelumnya', 'is_active' => true],
                    ['kode' => 'M-006', 'nama' => 'Penyertaan', 'keterangan' => 'Penyertaan modal', 'is_active' => true],
                ];
                foreach ($modal as $data) {
                    AkunModal::create($data);
                }
            }
        } catch (\Throwable $e) {
            echo 'Akun Modal seed skipped: '.$e->getMessage().PHP_EOL;
        }
    }
}
