<?php

namespace App\Http\Controllers;

use App\Models\JenisSimpanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JenisSimpananController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:jenis-simpanan-list|jenis-simpanan-create|jenis-simpanan-edit|jenis-simpanan-delete', only: ['index']),
            new Middleware('permission:jenis-simpanan-create', only: ['create', 'store']),
            new Middleware('permission:jenis-simpanan-edit', only: ['edit', 'update']),
            new Middleware('permission:jenis-simpanan-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $jenisSimpanan = JenisSimpanan::latest()->get();

        return view('jenis-simpanan.index', compact('jenisSimpanan'));
    }

    public function create()
    {
        return view('jenis-simpanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:jenis_simpanan,kode',
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:1000',
        ]);

        JenisSimpanan::create($validated);

        return redirect()->route('admin.jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil ditambahkan.');
    }

    public function edit(JenisSimpanan $jenisSimpanan)
    {
        return view('jenis-simpanan.edit', compact('jenisSimpanan'));
    }

    public function update(Request $request, JenisSimpanan $jenisSimpanan)
    {
        $validated = $request->validate([
            'kode' => 'required|max:50|unique:jenis_simpanan,kode,'.$jenisSimpanan->id,
            'nama' => 'required|max:255',
            'keterangan' => 'nullable|max:1000',
        ]);

        $jenisSimpanan->update($validated);

        return redirect()->route('admin.jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil diperbarui.');
    }

    public function destroy(JenisSimpanan $jenisSimpanan)
    {
        $jenisSimpanan->delete();

        return redirect()->route('admin.jenis-simpanan.index')->with('success', 'Jenis simpanan berhasil dihapus.');
    }
}
