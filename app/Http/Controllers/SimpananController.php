<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SimpananController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:simpanan-list|simpanan-create|simpanan-edit|simpanan-delete', only: ['index']),
            new Middleware('permission:simpanan-create', only: ['create', 'store']),
            new Middleware('permission:simpanan-edit', only: ['edit', 'update']),
            new Middleware('permission:simpanan-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $simpanan = Simpanan::with('anggota', 'periode')->latest()->get();

        return view('simpanan.index', compact('simpanan'));
    }

    public function create()
    {
        $anggotas = Anggota::orderBy('nama')->get();

        return view('simpanan.create', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jenis' => 'required|in:pokok,wajib,sukarela,bagi_hasil',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        Simpanan::create($validated);

        return redirect()->route('admin.simpanan.index')->with('success', 'Simpanan created successfully.');
    }

    public function edit(Simpanan $simpanan)
    {
        $anggotas = Anggota::orderBy('nama')->get();

        return view('simpanan.edit', compact('simpanan', 'anggotas'));
    }

    public function update(Request $request, Simpanan $simpanan)
    {
        if ($simpanan->jenis === 'pokok' && $request->jenis !== 'pokok') {
            return back()->with('error', 'Tidak bisa mengubah jenis Simpanan Pokok.');
        }

        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jenis' => 'required|in:pokok,wajib,sukarela,bagi_hasil',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
            'periode_id' => 'nullable|exists:periodes,id',
        ]);

        $simpanan->update($validated);

        return redirect()->route('admin.simpanan.index')->with('success', 'Simpanan updated successfully.');
    }

    public function destroy(Simpanan $simpanan)
    {
        if ($simpanan->jenis === 'pokok') {
            return back()->with('error', 'Simpanan Pokok tidak bisa dihapus selama anggota masih aktif.');
        }

        $simpanan->delete();

        return redirect()->route('admin.simpanan.index')->with('success', 'Simpanan deleted successfully.');
    }
}
