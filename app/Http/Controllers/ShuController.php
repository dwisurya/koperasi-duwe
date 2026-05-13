<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kas;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\Shu;
use App\Models\ShuDetail;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ShuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:shu-list', only: ['index']),
            new Middleware('permission:shu-distribute', only: ['calculate', 'distribute']),
        ];
    }

    public function index()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $shuList = Shu::with('details', 'periode')
            ->where('periode_id', $periodeId)
            ->latest()
            ->get();

        $neraca = $this->hitungNeraca($periodeId);

        $schema = Shu::distributionSchema();

        return view('shu.index', compact(
            'periode',
            'shuList',
            'neraca',
            'schema',
        ));
    }

    public function calculate(Request $request)
    {
        $periode = Periode::getActive();
        if (! $periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $neraca = $this->hitungNeraca($periode->id);
        $totalShu = $neraca['selisih'];

        if ($totalShu <= 0) {
            return back()->with('error', 'Tidak ada SHU yang dapat dihitung (surplus <= 0).');
        }

        $alreadyCalculated = Shu::where('periode_id', $periode->id)
            ->where('is_distributed', false)
            ->exists();

        if ($alreadyCalculated) {
            return back()->with('error', 'SHU sudah dihitung untuk periode ini. Distribusikan atau hapus yang existing terlebih dahulu.');
        }

        $shu = Shu::create([
            'periode_id' => $periode->id,
            'total_shu' => $totalShu,
            'total_aktiva' => $neraca['total_aktiva'],
            'total_passiva' => $neraca['total_passiva_sebelum_shu'],
            'is_distributed' => false,
        ]);

        $schema = Shu::distributionSchema();
        foreach ($schema as $item) {
            ShuDetail::create([
                'shu_id' => $shu->id,
                'dana' => $item['dana'],
                'persentase' => $item['persentase'],
                'nominal' => round($totalShu * $item['persentase'] / 100, 2),
            ]);
        }

        return redirect()->route('admin.shu.index')
            ->with('success', 'SHU berhasil dihitung: Rp '.number_format($totalShu, 0, ',', '.'));
    }

    public function distribute(Request $request)
    {
        $periode = Periode::getActive();
        if (! $periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $shu = Shu::where('periode_id', $periode->id)
            ->where('is_distributed', false)
            ->latest()
            ->first();

        if (! $shu) {
            return back()->with('error', 'Tidak ada SHU yang menunggu distribusi. Hitung SHU terlebih dahulu.');
        }

        $detailTotal = $shu->details()->sum('nominal');

        if ($detailTotal <= 0) {
            return back()->with('error', 'Detail SHU kosong.');
        }

        $shu->update([
            'is_distributed' => true,
            'distributed_at' => now(),
        ]);

        return redirect()->route('admin.shu.index')
            ->with('success', 'SHU berhasil didistribusikan sebesar Rp '.number_format($detailTotal, 0, ',', '.'));
    }

    private function hitungNeraca(?int $periodeId): array
    {
        $baseKas = Kas::query();
        $baseSimpanan = Simpanan::query();
        $basePinjaman = Pinjaman::query();
        $baseAngsuran = Angsuran::query();

        if ($periodeId) {
            $baseKas->where('periode_id', $periodeId);
            $baseSimpanan->where('periode_id', $periodeId);
            $basePinjaman->where('periode_id', $periodeId);
            $baseAngsuran->whereHas('pinjaman', fn ($q) => $q->where('periode_id', $periodeId));
        }

        $kasMasuk = (clone $baseKas)->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = (clone $baseKas)->where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $kasMasuk - $kasKeluar;

        $totalPinjamanDisalurkan = (clone $basePinjaman)->whereIn('status', ['aktif', 'lunas', 'macet'])->sum('nominal');
        $totalAngsuran = (clone $baseAngsuran)->sum('nominal');
        $piutangPinjaman = max($totalPinjamanDisalurkan - $totalAngsuran, 0);

        $totalAktiva = $saldoKas + $piutangPinjaman;

        $simpananMap = [
            'pokok' => (clone $baseSimpanan)->where('jenis', 'pokok')->sum('nominal'),
            'wajib' => (clone $baseSimpanan)->where('jenis', 'wajib')->sum('nominal'),
            'penyertaan' => (clone $baseSimpanan)->where('jenis', 'penyertaan')->sum('nominal'),
            'bagi_hasil' => (clone $baseSimpanan)->where('jenis', 'bagi_hasil')->sum('nominal'),
        ];

        $kasAkun = fn ($kategori) => (clone $baseKas)->where('kategori', $kategori)->where('jenis', 'masuk')->sum('nominal')
            - (clone $baseKas)->where('kategori', $kategori)->where('jenis', 'keluar')->sum('nominal');

        $totalSimpanan = array_sum($simpananMap);
        $totalModalLainnya = $kasAkun('Dana Sosial') + $kasAkun('Cadangan Modal')
            + $kasAkun('Cadangan Resiko') + $kasAkun('Dana Rapat')
            + $kasAkun('SHU Periode Lalu') + $kasAkun('Penyertaan');
        $totalPinjamanPassiva = $kasAkun('Pinjam Dana Pura') + $kasAkun('Pinjam SUKDUK');

        $totalPassiva = $totalSimpanan + $totalModalLainnya + $totalPinjamanPassiva;

        $selisih = $totalAktiva - $totalPassiva;

        return [
            'total_aktiva' => $totalAktiva,
            'total_passiva_sebelum_shu' => $totalPassiva,
            'selisih' => $selisih,
        ];
    }
}
