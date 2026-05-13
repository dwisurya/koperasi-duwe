<?php

namespace App\Http\Controllers;

use App\Models\KategoriAktiva;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KategoriAktivaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:kategori-aktiva-list|kategori-aktiva-create|kategori-aktiva-edit|kategori-aktiva-delete', only: ['index']),
            new Middleware('permission:kategori-aktiva-create', only: ['create', 'store']),
            new Middleware('permission:kategori-aktiva-edit', only: ['edit', 'update']),
            new Middleware('permission:kategori-aktiva-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $kategoriAktiva = KategoriAktiva::latest()->get();

        return view('kategori-aktiva.index', compact('kategoriAktiva'));
    }

    public function create()
    {
        return view('kategori-aktiva.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        KategoriAktiva::create($validated);

        return redirect()->route('admin.akun-aktiva.index')->with('success', 'Kategori aktiva created successfully.');
    }

    public function edit(KategoriAktiva $kategoriAktiva)
    {
        return view('kategori-aktiva.edit', compact('kategoriAktiva'));
    }

    public function update(Request $request, KategoriAktiva $kategoriAktiva)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        $kategoriAktiva->update($validated);

        return redirect()->route('admin.akun-aktiva.index')->with('success', 'Kategori aktiva updated successfully.');
    }

    public function destroy(KategoriAktiva $kategoriAktiva)
    {
        $kategoriAktiva->delete();

        return redirect()->route('admin.akun-aktiva.index')->with('success', 'Kategori aktiva deleted successfully.');
    }
}
