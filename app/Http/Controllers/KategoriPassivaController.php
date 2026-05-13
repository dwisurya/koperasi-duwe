<?php

namespace App\Http\Controllers;

use App\Models\KategoriPassiva;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KategoriPassivaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:kategori-passiva-list|kategori-passiva-create|kategori-passiva-edit|kategori-passiva-delete', only: ['index']),
            new Middleware('permission:kategori-passiva-create', only: ['create', 'store']),
            new Middleware('permission:kategori-passiva-edit', only: ['edit', 'update']),
            new Middleware('permission:kategori-passiva-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $kategoriPassiva = KategoriPassiva::latest()->get();

        return view('kategori-passiva.index', compact('kategoriPassiva'));
    }

    public function create()
    {
        return view('kategori-passiva.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        KategoriPassiva::create($validated);

        return redirect()->route('admin.akun-passiva.index')->with('success', 'Kategori passiva created successfully.');
    }

    public function edit(KategoriPassiva $kategoriPassiva)
    {
        return view('kategori-passiva.edit', compact('kategoriPassiva'));
    }

    public function update(Request $request, KategoriPassiva $kategoriPassiva)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        $kategoriPassiva->update($validated);

        return redirect()->route('admin.akun-passiva.index')->with('success', 'Kategori passiva updated successfully.');
    }

    public function destroy(KategoriPassiva $kategoriPassiva)
    {
        $kategoriPassiva->delete();

        return redirect()->route('admin.akun-passiva.index')->with('success', 'Kategori passiva deleted successfully.');
    }
}
