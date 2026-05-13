<?php

namespace App\Http\Controllers;

use App\Models\AkunKeuangan;
use App\Models\KategoriAktiva;
use App\Models\KategoriPassiva;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AkunKeuanganController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:akun-keuangan-list|akun-keuangan-create|akun-keuangan-edit|akun-keuangan-delete', only: ['index']),
            new Middleware('permission:akun-keuangan-create', only: ['create', 'store']),
            new Middleware('permission:akun-keuangan-edit', only: ['edit', 'update']),
            new Middleware('permission:akun-keuangan-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $akunKeuangan = AkunKeuangan::with('kategoriAktiva', 'kategoriPassiva')->latest()->get();

        return view('akun-keuangan.index', compact('akunKeuangan'));
    }

    public function create()
    {
        $kategoriAktiva = KategoriAktiva::where('is_active', true)->orderBy('nama')->get();
        $kategoriPassiva = KategoriPassiva::where('is_active', true)->orderBy('nama')->get();

        return view('akun-keuangan.create', compact('kategoriAktiva', 'kategoriPassiva'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:akun_keuangan,kode',
            'nama' => 'required|max:255',
            'kategori_aktiva_id' => 'nullable|exists:kategori_aktiva,id',
            'kategori_passiva_id' => 'nullable|exists:kategori_passiva,id',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        if (! $validated['kategori_aktiva_id'] && ! $validated['kategori_passiva_id']) {
            return back()->withErrors(['kategori_type' => 'Pilih salah satu kategori (Aktiva atau Passiva).'])->withInput();
        }

        AkunKeuangan::create($validated);

        return redirect()->route('admin.akun-keuangan.index')->with('success', 'Akun keuangan created successfully.');
    }

    public function edit(AkunKeuangan $akunKeuangan)
    {
        $kategoriAktiva = KategoriAktiva::where('is_active', true)->orderBy('nama')->get();
        $kategoriPassiva = KategoriPassiva::where('is_active', true)->orderBy('nama')->get();

        return view('akun-keuangan.edit', compact('akunKeuangan', 'kategoriAktiva', 'kategoriPassiva'));
    }

    public function update(Request $request, AkunKeuangan $akunKeuangan)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:akun_keuangan,kode,'.$akunKeuangan->id,
            'nama' => 'required|max:255',
            'kategori_aktiva_id' => 'nullable|exists:kategori_aktiva,id',
            'kategori_passiva_id' => 'nullable|exists:kategori_passiva,id',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        if (! $validated['kategori_aktiva_id'] && ! $validated['kategori_passiva_id']) {
            return back()->withErrors(['kategori_type' => 'Pilih salah satu kategori (Aktiva atau Passiva).'])->withInput();
        }

        $akunKeuangan->update($validated);

        return redirect()->route('admin.akun-keuangan.index')->with('success', 'Akun keuangan updated successfully.');
    }

    public function destroy(AkunKeuangan $akunKeuangan)
    {
        $akunKeuangan->delete();

        return redirect()->route('admin.akun-keuangan.index')->with('success', 'Akun keuangan deleted successfully.');
    }
}
