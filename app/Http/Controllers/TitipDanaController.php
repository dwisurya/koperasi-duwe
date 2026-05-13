<?php

namespace App\Http\Controllers;

use App\Models\TitipDana;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TitipDanaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:titip-dana-list|titip-dana-create|titip-dana-edit|titip-dana-delete', only: ['index']),
            new Middleware('permission:titip-dana-create', only: ['create', 'store']),
            new Middleware('permission:titip-dana-edit', only: ['edit', 'update']),
            new Middleware('permission:titip-dana-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $titipDana = TitipDana::with('periode')->latest()->get();

        $totalMasuk = TitipDana::where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = TitipDana::where('jenis', 'keluar')->sum('nominal');

        return view('titip-dana.index', compact('titipDana', 'totalMasuk', 'totalKeluar'));
    }

    public function create()
    {
        return view('titip-dana.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penitip' => 'required|max:255',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'status' => 'required|in:belum_diketahui,sudah_diketahui',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:1000',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        TitipDana::create($validated);

        return redirect()->route('admin.titip-dana.index')->with('success', 'Transaksi titip dana berhasil ditambahkan.');
    }

    public function edit(TitipDana $titipDana)
    {
        return view('titip-dana.edit', compact('titipDana'));
    }

    public function update(Request $request, TitipDana $titipDana)
    {
        $validated = $request->validate([
            'nama_penitip' => 'required|max:255',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'status' => 'required|in:belum_diketahui,sudah_diketahui',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:1000',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        $titipDana->update($validated);

        return redirect()->route('admin.titip-dana.index')->with('success', 'Transaksi titip dana berhasil diperbarui.');
    }

    public function destroy(TitipDana $titipDana)
    {
        $titipDana->delete();

        return redirect()->route('admin.titip-dana.index')->with('success', 'Transaksi titip dana berhasil dihapus.');
    }
}
