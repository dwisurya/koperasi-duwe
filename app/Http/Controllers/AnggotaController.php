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
            new Middleware('permission:anggota-edit', only: ['edit', 'update']),
            new Middleware('permission:anggota-delete', only: ['destroy']),
        ];
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
        $saldoAkhir = $anggota->saldo_awal + $totalSimpanan;
        $totalPinjaman = $anggota->pinjaman->sum('nominal');

        return view('anggota.show', compact('anggota', 'totalSimpanan', 'saldoAkhir', 'totalPinjaman'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'saldo_awal' => str_replace('.', '', $request->saldo_awal),
        ]);

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'nik' => 'nullable|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'email' => 'required|email|unique:anggotas,email',
            'no_hp' => 'nullable|max:20',
            'tanggal_daftar' => 'nullable|date',
            'ayah' => 'nullable|max:100',
            'ibu' => 'nullable|max:100',
            'saldo_awal' => 'nullable|numeric|min:0',
        ]);

        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota created successfully.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $request->merge([
            'saldo_awal' => str_replace('.', '', $request->saldo_awal),
        ]);

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'nik' => 'nullable|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'email' => 'required|email|unique:anggotas,email,'.$anggota->id,
            'no_hp' => 'nullable|max:20',
            'tanggal_daftar' => 'nullable|date',
            'ayah' => 'nullable|max:100',
            'ibu' => 'nullable|max:100',
            'saldo_awal' => 'nullable|numeric|min:0',
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
