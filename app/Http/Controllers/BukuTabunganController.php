<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;

class BukuTabunganController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::with(['simpanan' => function ($q) {
            $q->where('is_active', true)->latest();
        }])->orderBy('nama')->get();

        $anggotas = $anggotas->filter(fn ($a) => $a->simpanan->isNotEmpty());

        return view('buku-tabungan.index', compact('anggotas'));
    }

    public function show(Anggota $anggota)
    {
        $simpanan = Simpanan::with('periode')
            ->where('anggota_id', $anggota->id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        $totalPerJenis = $simpanan->groupBy('jenis')->map(fn ($items) => $items->sum('nominal'));

        return view('buku-tabungan.show', compact('anggota', 'simpanan', 'totalPerJenis'));
    }
}
