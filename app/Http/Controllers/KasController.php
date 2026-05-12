<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KasController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:kas-list|kas-create|kas-edit|kas-delete', only: ['index']),
            new Middleware('permission:kas-create', only: ['create', 'store']),
            new Middleware('permission:kas-edit', only: ['edit', 'update']),
            new Middleware('permission:kas-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $kas = Kas::with('periode')->latest()->get();

        $totalMasuk = Kas::where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = Kas::where('jenis', 'keluar')->sum('nominal');

        return view('kas.index', compact('kas', 'totalMasuk', 'totalKeluar'));
    }

    public function create()
    {
        return view('kas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:1000',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        Kas::create($validated);

        return redirect()->route('admin.kas.index')->with('success', 'Transaksi kas berhasil ditambahkan.');
    }

    public function edit(Kas $kas)
    {
        return view('kas.edit', compact('kas'));
    }

    public function update(Request $request, Kas $kas)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:1000',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        $kas->update($validated);

        return redirect()->route('admin.kas.index')->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    public function destroy(Kas $kas)
    {
        $kas->delete();

        return redirect()->route('admin.kas.index')->with('success', 'Transaksi kas berhasil dihapus.');
    }
}
