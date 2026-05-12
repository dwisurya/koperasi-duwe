<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AngsuranController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:angsuran-list|angsuran-create|angsuran-edit|angsuran-delete', only: ['index']),
            new Middleware('permission:angsuran-create', only: ['create', 'store']),
            new Middleware('permission:angsuran-edit', only: ['edit', 'update']),
            new Middleware('permission:angsuran-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $angsurans = Angsuran::with(['pinjaman', 'anggota'])->latest()->get();

        return view('angsuran.index', compact('angsurans'));
    }

    public function create()
    {
        $pinjaman = Pinjaman::with('anggota')->whereIn('status', ['disetujui', 'aktif'])->latest()->get();
        $anggotas = Anggota::orderBy('nama')->get();

        return view('angsuran.create', compact('pinjaman', 'anggotas'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'denda' => str_replace('.', '', $request->denda),
        ]);

        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:pinjaman,id',
            'anggota_id' => 'required|exists:anggotas,id',
            'angsuran_ke' => 'required|integer|min:1',
            'tanggal_bayar' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        Angsuran::create($validated);

        return redirect()->route('admin.angsuran.index')->with('success', 'Angsuran created successfully.');
    }

    public function edit(Angsuran $angsuran)
    {
        $pinjaman = Pinjaman::with('anggota')->whereIn('status', ['disetujui', 'aktif'])->latest()->get();
        $anggotas = Anggota::orderBy('nama')->get();

        return view('angsuran.edit', compact('angsuran', 'pinjaman', 'anggotas'));
    }

    public function update(Request $request, Angsuran $angsuran)
    {
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
            'denda' => str_replace('.', '', $request->denda),
        ]);

        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:pinjaman,id',
            'anggota_id' => 'required|exists:anggotas,id',
            'angsuran_ke' => 'required|integer|min:1',
            'tanggal_bayar' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        $angsuran->update($validated);

        return redirect()->route('admin.angsuran.index')->with('success', 'Angsuran updated successfully.');
    }

    public function destroy(Angsuran $angsuran)
    {
        $angsuran->delete();

        return redirect()->route('admin.angsuran.index')->with('success', 'Angsuran deleted successfully.');
    }
}
