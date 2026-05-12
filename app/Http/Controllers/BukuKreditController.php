<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BukuKreditController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:buku-kredit-list', only: ['index']),
        ];
    }

    public function index()
    {
        $pinjaman = Pinjaman::with(['anggota', 'angsurans', 'periode'])
            ->whereIn('status', ['disetujui', 'aktif', 'lunas'])
            ->latest()
            ->get()
            ->map(function ($p) {
                $totalAngsuran = $p->angsurans->sum('nominal');
                $sisaPinjaman = $p->nominal - $totalAngsuran;

                return (object) [
                    'id' => $p->id,
                    'anggota' => $p->anggota,
                    'tanggal_pengajuan' => $p->tanggal_pengajuan,
                    'nominal' => $p->nominal,
                    'bunga_persen' => $p->bunga_persen,
                    'tenor' => $p->tenor,
                    'status' => $p->status,
                    'status_label' => $p->status_label,
                    'status_color' => $p->status_color,
                    'total_angsuran' => $totalAngsuran,
                    'sisa_pinjaman' => max($sisaPinjaman, 0),
                    'angsuran_count' => $p->angsurans->count(),
                    'periode' => $p->periode,
                ];
            });

        return view('buku-kredit.index', compact('pinjaman'));
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load(['anggota', 'angsurans' => fn ($q) => $q->latest('angsuran_ke'), 'bungaPinjaman', 'periode']);

        $totalAngsuran = $pinjaman->angsurans->sum('nominal');
        $sisaPinjaman = $pinjaman->nominal - $totalAngsuran;

        return view('buku-kredit.show', compact('pinjaman', 'totalAngsuran', 'sisaPinjaman'));
    }
}
