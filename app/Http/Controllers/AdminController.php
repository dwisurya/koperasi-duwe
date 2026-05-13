<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Menu;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        $periodeAktif = Periode::getActive();
        $periodeId = $periodeAktif?->id;

        $stats = [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'menus' => Menu::count(),
            'anggota' => Anggota::count(),
        ];

        $simpananQuery = Simpanan::query();
        $pinjamanQuery = Pinjaman::query();

        if ($periodeId) {
            $simpananQuery->where('periode_id', $periodeId);
            $pinjamanQuery->where('periode_id', $periodeId);
        }

        $simpanan = [
            'pokok' => (clone $simpananQuery)->where('jenis', 'pokok')->sum('nominal'),
            'wajib' => (clone $simpananQuery)->where('jenis', 'wajib')->sum('nominal'),
            'penyertaan' => (clone $simpananQuery)->where('jenis', 'penyertaan')->sum('nominal'),
            'bagi_hasil' => (clone $simpananQuery)->where('jenis', 'bagi_hasil')->sum('nominal'),
        ];

        $pinjaman = [
            'total' => (clone $pinjamanQuery)->count(),
            'menunggu' => (clone $pinjamanQuery)->menunggu()->count(),
            'disetujui' => (clone $pinjamanQuery)->disetujui()->count(),
            'aktif' => (clone $pinjamanQuery)->aktif()->count(),
        ];

        $totalPinjaman = (clone $pinjamanQuery)->sum('nominal');

        return view('admin.dashboard', compact('stats', 'simpanan', 'pinjaman', 'totalPinjaman', 'periodeAktif'));
    }
}
