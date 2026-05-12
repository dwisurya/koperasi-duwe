<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\BungaPinjaman;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PinjamanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:pinjaman-list|pinjaman-create|pinjaman-edit|pinjaman-delete|pinjaman-approve', only: ['index']),
            new Middleware('permission:pinjaman-create', only: ['create', 'store']),
            new Middleware('permission:pinjaman-edit', only: ['edit', 'update']),
            new Middleware('permission:pinjaman-delete', only: ['destroy']),
            new Middleware('permission:pinjaman-approve', only: ['approve', 'reject']),
        ];
    }

    public function index()
    {
        $pinjaman = Pinjaman::with(['anggota', 'bungaPinjaman', 'approver'])->latest()->get();

        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        $anggotas = Anggota::orderBy('nama')->get();
        $bungaPinjaman = BungaPinjaman::where('is_active', true)->orderBy('nama')->get();

        return view('pinjaman.create', compact('anggotas', 'bungaPinjaman'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'bunga_pinjaman_id' => $request->bunga_pinjaman_id ?: null,
            'nominal' => str_replace('.', '', $request->nominal),
        ]);

        $this->parseBungaFromDisplay($request);

        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'tanggal_pengajuan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'bunga_pinjaman_id' => 'nullable|exists:bunga_pinjaman,id',
            'bunga_persen' => 'required|numeric|min:0|max:999.99',
            'tenor' => 'required|integer|min:1',
            'keterangan' => 'nullable|max:500',
        ]);

        $validated['status'] = 'diajukan';
        Pinjaman::create($validated);

        return redirect()->route('admin.pinjaman.index')->with('success', __('Pengajuan pinjaman berhasil dikirim.'));
    }

    private function parseBungaFromDisplay(Request $request): void
    {
        if ($request->filled('bunga_persen')) {
            return;
        }

        $display = $request->input('bunga_display', '');

        if (empty($display)) {
            return;
        }

        $match = BungaPinjaman::whereRaw("nama || ' (' || bunga || '%)' = ?", [$display])->first();

        if ($match) {
            $request->merge([
                'bunga_pinjaman_id' => $match->id,
                'bunga_persen' => $match->bunga,
            ]);
        } else {
            preg_match('/\d+(?:[.,]\d+)?/', $display, $matches);

            if (! empty($matches)) {
                $request->merge([
                    'bunga_pinjaman_id' => null,
                    'bunga_persen' => str_replace(',', '.', $matches[0]),
                ]);
            }
        }
    }

    public function edit(Pinjaman $pinjaman)
    {
        $anggotas = Anggota::orderBy('nama')->get();
        $bungaPinjaman = BungaPinjaman::where('is_active', true)->orderBy('nama')->get();

        return view('pinjaman.edit', compact('pinjaman', 'anggotas', 'bungaPinjaman'));
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        $request->merge([
            'bunga_pinjaman_id' => $request->bunga_pinjaman_id ?: null,
            'nominal' => str_replace('.', '', $request->nominal),
        ]);

        $this->parseBungaFromDisplay($request);

        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'tanggal_pengajuan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'bunga_pinjaman_id' => 'nullable|exists:bunga_pinjaman,id',
            'bunga_persen' => 'required|numeric|min:0|max:999.99',
            'tenor' => 'required|integer|min:1',
            'keterangan' => 'nullable|max:500',
        ]);

        if ($pinjaman->status === 'diajukan') {
            $pinjaman->update($validated);
        } else {
            $pinjaman->update(array_merge($validated, [
                'status' => $request->input('status', $pinjaman->status),
            ]));
        }

        return redirect()->route('admin.pinjaman.index')->with('success', __('Pinjaman updated successfully.'));
    }

    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman->delete();

        return redirect()->route('admin.pinjaman.index')->with('success', __('Pinjaman deleted successfully.'));
    }

    public function approve(Pinjaman $pinjaman)
    {
        if ($pinjaman->status !== 'diajukan') {
            return back()->with('error', __('Only submitted applications can be approved.'));
        }

        $pinjaman->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.pinjaman.index')->with('success', __('Pengajuan pinjaman disetujui.'));
    }

    public function reject(Request $request, Pinjaman $pinjaman)
    {
        if ($pinjaman->status !== 'diajukan') {
            return back()->with('error', __('Only submitted applications can be rejected.'));
        }

        $pinjaman->update([
            'status' => 'ditolak',
            'keterangan' => $request->input('alasan', $pinjaman->keterangan),
        ]);

        return redirect()->route('admin.pinjaman.index')->with('success', __('Pengajuan pinjaman ditolak.'));
    }

    public function simulasi(Request $request)
    {
        $nominal = $request->filled('nominal') ? (float) $request->nominal : null;
        $bunga = $request->filled('bunga') ? (float) $request->bunga : null;
        $tenor = $request->filled('tenor') ? (int) $request->tenor : null;

        $detail = null;
        $cicilanPerBulan = null;
        $totalBunga = null;
        $totalBayar = null;

        if ($nominal && $bunga && $tenor) {
            $request->validate([
                'nominal' => 'required|numeric|min:0',
                'bunga' => 'required|numeric|min:0|max:999.99',
                'tenor' => 'required|integer|min:1',
            ]);

            $bungaPerBulan = ($nominal * ($bunga / 100)) / 12;
            $cicilanPokok = $nominal / $tenor;
            $cicilanPerBulan = $cicilanPokok + $bungaPerBulan;
            $totalBayar = $cicilanPerBulan * $tenor;
            $totalBunga = $bungaPerBulan * $tenor;

            $detail = [];
            for ($i = 1; $i <= $tenor; $i++) {
                $sisaPinjaman = $nominal - ($cicilanPokok * $i);
                $detail[] = [
                    'bulan' => $i,
                    'cicilan_pokok' => round($cicilanPokok, 2),
                    'bunga' => round($bungaPerBulan, 2),
                    'total' => round($cicilanPerBulan, 2),
                    'sisa' => round(max($sisaPinjaman, 0), 2),
                ];
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'cicilan_per_bulan' => round($cicilanPerBulan, 2),
                    'total_bunga' => round($totalBunga, 2),
                    'total_bayar' => round($totalBayar, 2),
                    'detail' => $detail,
                ]);
            }
        }

        return view('pinjaman.simulasi', compact(
            'nominal', 'bunga', 'tenor',
            'cicilanPerBulan', 'totalBunga', 'totalBayar', 'detail'
        ));
    }

    public function cetakKontrak(Pinjaman $pinjaman)
    {
        return view('pinjaman.kontrak', compact('pinjaman'));
    }
}
