<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kas;
use App\Models\Periode;
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
            new Middleware('permission:simpanan-edit', only: ['edit', 'update', 'tarik']),
            new Middleware('permission:simpanan-delete', only: ['destroy']),
        ];
    }

    public function tarik(Simpanan $simpanan)
    {
        if ($simpanan->anggota->isAktif()) {
            return back()->with('error', 'Anggota masih aktif, tidak bisa menarik simpanan.');
        }

        if (!in_array($simpanan->jenis, ['pokok', 'wajib'])) {
            return back()->with('error', 'Hanya Simpanan Pokok dan Wajib yang bisa ditarik.');
        }

        if (!$simpanan->is_active) {
            return back()->with('error', 'Simpanan sudah ditarik sebelumnya.');
        }

        $simpanan->update(['is_active' => false]);

        Kas::create([
            'tanggal' => now(),
            'jenis' => 'keluar',
            'kategori' => 'Simpanan ' . $simpanan->jenis_label,
            'nominal' => $simpanan->nominal,
            'keterangan' => 'Penarikan ' . $simpanan->jenis_label . ' a.n. ' . $simpanan->anggota->nama,
            'periode_id' => $simpanan->periode_id ?? Periode::getActiveId(),
        ]);

        return back()->with('success', 'Simpanan berhasil ditarik.');
    }

    public function index()
    {
        $simpanan = Simpanan::with('anggota', 'periode')->latest()->get();

        $totalPerJenis = [
            'pokok' => Simpanan::where('jenis', 'pokok')->sum('nominal'),
            'wajib' => Simpanan::where('jenis', 'wajib')->sum('nominal'),
            'penyertaan' => Simpanan::where('jenis', 'penyertaan')->sum('nominal'),
            'bagi_hasil' => Simpanan::where('jenis', 'bagi_hasil')->sum('nominal'),
        ];
        $grandTotal = array_sum($totalPerJenis);

        return view('simpanan.index', compact('simpanan', 'totalPerJenis', 'grandTotal'));
    }

    public function pokok()
    {
        return $this->indexByJenis('pokok');
    }

    public function wajib()
    {
        return $this->indexByJenis('wajib');
    }

    public function penyertaan()
    {
        return $this->indexByJenis('penyertaan');
    }

    private function indexByJenis(string $jenis)
    {
        $judul = Simpanan::jenisLabel($jenis);
        $simpanan = Simpanan::with('anggota', 'periode')
            ->where('jenis', $jenis)
            ->latest()
            ->get();

        $totalNominal = $simpanan->sum('nominal');

        return view('simpanan.by-jenis', compact('simpanan', 'jenis', 'judul', 'totalNominal'));
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
            'jenis' => 'required|in:pokok,wajib,penyertaan,bagi_hasil',
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
            'jenis' => 'required|in:pokok,wajib,penyertaan,bagi_hasil',
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
        if ($simpanan->jenis === 'pokok' && $simpanan->anggota->isAktif()) {
            return back()->with('error', 'Simpanan Pokok tidak bisa dihapus selama anggota masih aktif.');
        }

        $simpanan->delete();

        return redirect()->route('admin.simpanan.index')->with('success', 'Simpanan deleted successfully.');
    }
}
