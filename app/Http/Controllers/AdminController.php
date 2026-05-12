<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Menu;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'menus' => Menu::count(),
            'anggota' => Anggota::count(),
        ];

        $simpanan = [
            'pokok' => Simpanan::where('jenis', 'pokok')->sum('nominal'),
            'wajib' => Simpanan::where('jenis', 'wajib')->sum('nominal'),
            'sukarela' => Simpanan::where('jenis', 'sukarela')->sum('nominal'),
            'bagi_hasil' => Simpanan::where('jenis', 'bagi_hasil')->sum('nominal'),
        ];

        $pinjaman = [
            'total' => Pinjaman::count(),
            'menunggu' => Pinjaman::menunggu()->count(),
            'disetujui' => Pinjaman::disetujui()->count(),
            'aktif' => Pinjaman::aktif()->count(),
        ];

        $totalPinjaman = Pinjaman::sum('nominal');

        return view('admin.dashboard', compact('stats', 'simpanan', 'pinjaman', 'totalPinjaman'));
    }
}
