<?php

namespace App\Http\Controllers;

use App\Models\BungaPinjaman;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BungaPinjamanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:bunga-pinjaman-list|bunga-pinjaman-create|bunga-pinjaman-edit|bunga-pinjaman-delete', only: ['index']),
            new Middleware('permission:bunga-pinjaman-create', only: ['create', 'store']),
            new Middleware('permission:bunga-pinjaman-edit', only: ['edit', 'update']),
            new Middleware('permission:bunga-pinjaman-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $bungaPinjaman = BungaPinjaman::latest()->get();

        return view('bunga-pinjaman.index', compact('bungaPinjaman'));
    }

    public function create()
    {
        return view('bunga-pinjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'bunga' => 'required|numeric|min:0|max:999.99',
            'tanggal_berlaku' => 'nullable|date',
            'jenis' => 'nullable|max:50',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        BungaPinjaman::create($validated);

        return redirect()->route('admin.bunga-pinjaman.index')->with('success', 'Bunga pinjaman created successfully.');
    }

    public function edit(BungaPinjaman $bungaPinjaman)
    {
        return view('bunga-pinjaman.edit', compact('bungaPinjaman'));
    }

    public function update(Request $request, BungaPinjaman $bungaPinjaman)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'bunga' => 'required|numeric|min:0|max:999.99',
            'tanggal_berlaku' => 'nullable|date',
            'jenis' => 'nullable|max:50',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        $bungaPinjaman->update($validated);

        return redirect()->route('admin.bunga-pinjaman.index')->with('success', 'Bunga pinjaman updated successfully.');
    }

    public function destroy(BungaPinjaman $bungaPinjaman)
    {
        $bungaPinjaman->delete();

        return redirect()->route('admin.bunga-pinjaman.index')->with('success', 'Bunga pinjaman deleted successfully.');
    }
}
