<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnggotaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:anggota-list|anggota-create|anggota-edit|anggota-delete', only: ['index', 'show']),
            new Middleware('permission:anggota-create', only: ['create', 'store']),
            new Middleware('permission:anggota-edit', only: ['edit', 'update', 'keluarkan', 'masukkanKembali']),
            new Middleware('permission:anggota-delete', only: ['destroy']),
        ];
    }

    public function keluarkan(Anggota $anggota)
    {
        $anggota->update(['tanggal_keluar' => now()]);
        return redirect()->route('admin.anggota.show', $anggota)->with('success', 'Anggota ditandai keluar.');
    }

    public function masukkanKembali(Anggota $anggota)
    {
        $anggota->update(['tanggal_keluar' => null]);
        return redirect()->route('admin.anggota.show', $anggota)->with('success', 'Anggota diaktifkan kembali.');
    }

    public function index()
    {
        $anggotas = Anggota::latest()->get();

        return view('anggota.index', compact('anggotas'));
    }

    public function show(Anggota $anggota)
    {
        $anggota->load(['simpanan', 'pinjaman.anggota', 'pinjaman.bungaPinjaman', 'pinjaman.approver']);

        $totalSimpanan = $anggota->simpanan->sum('nominal');
        $totalPinjaman = $anggota->pinjaman->sum('nominal');

        return view('anggota.show', compact('anggota', 'totalSimpanan', 'totalPinjaman'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'simpanan_pokok' => str_replace('.', '', $request->simpanan_pokok),
        ]);

        $validated = $request->validate([
            'nama' => 'required|max:255|unique:anggotas,nama',
            'nik' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/|unique:anggotas,nik',
            'no_kk' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/',
            'alamat' => 'required|max:500',
            'tempat_lahir' => 'required|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|email|unique:anggotas,email',
            'no_hp' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/|unique:anggotas,no_hp',
            'tanggal_daftar' => 'required|date',
            'ayah' => 'required|max:100',
            'ibu' => 'required|max:100',
            'simpanan_pokok' => 'nullable|numeric|min:0',
        ]);

        $anggota = Anggota::create($validated);

        if (($request->simpanan_pokok ?? 0) > 0) {
            $anggota->simpanan()->create([
                'jenis' => 'pokok',
                'nominal' => $request->simpanan_pokok,
                'keterangan' => 'Simpanan Pokok awal',
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota created successfully.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255|unique:anggotas,nama,'.$anggota->id,
            'nik' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/|unique:anggotas,nik,'.$anggota->id,
            'no_kk' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/',
            'alamat' => 'required|max:500',
            'tempat_lahir' => 'required|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|email|unique:anggotas,email,'.$anggota->id,
            'no_hp' => 'required|regex:/^[\d\s\-\+\(\)\.\/]+$/|unique:anggotas,no_hp,'.$anggota->id,
            'tanggal_daftar' => 'required|date',
            'ayah' => 'required|max:100',
            'ibu' => 'required|max:100',
        ]);

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota updated successfully.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota deleted successfully.');
    }
}
