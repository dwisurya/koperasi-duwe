<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Angsuran;
use App\Models\Kas;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:neraca-list', only: ['saldoAnggota', 'tunggakan', 'rugiLaba', 'rekapSimpanan', 'rekapPinjaman', 'rekapAngsuran', 'arusKas']),
        ];
    }

    public function saldoAnggota()
    {
        $periode = Periode::getActive();
        $anggota = Anggota::with([
            'simpanan' => fn ($q) => $q->when($periode, fn ($q) => $q->where('periode_id', $periode->id)),
            'pinjaman' => fn ($q) => $q->when($periode, fn ($q) => $q->where('periode_id', $periode->id)),
        ])->get();

        $data = $anggota->map(fn ($a) => [
            'nama' => $a->nama,
            'kode' => $a->kode,
            'simpanan_pokok' => $a->simpanan->where('jenis', 'pokok')->sum('nominal'),
            'simpanan_wajib' => $a->simpanan->where('jenis', 'wajib')->sum('nominal'),
            'simpanan_penyertaan' => $a->simpanan->where('jenis', 'penyertaan')->sum('nominal'),
            'total_simpanan' => $a->simpanan->sum('nominal'),
            'pinjaman_aktif' => $a->pinjaman->whereIn('status', ['aktif'])->sum('nominal'),
            'total_pinjaman' => $a->pinjaman->sum('nominal'),
        ]);

        $totalSimpanan = $data->sum('total_simpanan');
        $totalPinjaman = $data->sum('total_pinjaman');
        $totalPinjamanAktif = $data->sum('pinjaman_aktif');

        return view('laporan.saldo-anggota', compact('data', 'periode', 'totalSimpanan', 'totalPinjaman', 'totalPinjamanAktif'));
    }

    public function tunggakan()
    {
        $pinjamanMacet = Pinjaman::with('anggota', 'periode')
            ->whereIn('status', ['aktif', 'macet'])
            ->get()
            ->map(fn ($p) => [
                'anggota' => $p->anggota?->nama ?? '-',
                'kode' => $p->anggota?->kode ?? '-',
                'nominal' => $p->nominal,
                'status' => $p->status_label,
                'status_color' => $p->status_color,
                'tanggal_pengajuan' => $p->tanggal_pengajuan?->format('d/m/Y'),
                'jatuh_tempo' => $p->jatuh_tempo?->format('d/m/Y'),
                'tenor' => $p->tenor,
                'total_angsuran' => $p->angsurans->sum('nominal'),
                'sisa' => max($p->nominal - $p->angsurans->sum('nominal'), 0),
                'angsuran_ke' => $p->angsurans->count(),
                'periode' => $p->periode?->tahun ?? '-',
            ]);

        $totalTunggakan = $pinjamanMacet->sum('sisa');

        return view('laporan.tunggakan', compact('pinjamanMacet', 'totalTunggakan'));
    }

    public function rugiLaba()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $baseKas = Kas::query()->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId));

        $pendapatanKategori = [
            'Pendapatan Lain',
        ];
        $bebanKategori = [
            'Biaya Operasional',
            'Biaya Administrasi',
            'Pengeluaran Lain',
        ];

        $pendapatan = collect();
        foreach ($pendapatanKategori as $kat) {
            $pendapatan->push([
                'nama' => $kat,
                'total' => (clone $baseKas)->where('kategori', $kat)->where('jenis', 'masuk')->sum('nominal'),
            ]);
        }
        $pendapatan->push([
            'nama' => 'Bunga Pinjaman',
            'total' => (clone $baseKas)->where('kategori', 'Bunga Pinjaman')->where('jenis', 'masuk')->sum('nominal'),
        ]);

        $beban = collect();
        foreach ($bebanKategori as $kat) {
            $beban->push([
                'nama' => $kat,
                'total' => (clone $baseKas)->where('kategori', $kat)->where('jenis', 'keluar')->sum('nominal'),
            ]);
        }

        $totalPendapatan = $pendapatan->sum('total');
        $totalBeban = $beban->sum('total');
        $labaBersih = $totalPendapatan - $totalBeban;

        return view('laporan.rugi-laba', compact('periode', 'pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaBersih'));
    }

    public function rekapSimpanan()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $base = Simpanan::query()->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId));

        $jenisList = ['pokok', 'wajib', 'penyertaan', 'bagi_hasil'];
        $rekap = collect();
        foreach ($jenisList as $jenis) {
            $q = (clone $base)->where('jenis', $jenis);
            $rekap->push([
                'jenis' => Simpanan::jenisLabel($jenis),
                'total_transaksi' => $q->count(),
                'total_nominal' => $q->sum('nominal'),
                'anggota_terlibat' => $q->distinct('anggota_id')->count('anggota_id'),
            ]);
        }

        $grandTotal = $rekap->sum('total_nominal');
        $grandCount = $rekap->sum('total_transaksi');

        return view('laporan.rekap-simpanan', compact('periode', 'rekap', 'grandTotal', 'grandCount'));
    }

    public function rekapPinjaman()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $base = Pinjaman::query()->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId));

        $statusList = ['diajukan', 'disetujui', 'ditolak', 'aktif', 'lunas', 'macet'];
        $rekap = collect();
        foreach ($statusList as $status) {
            $q = (clone $base)->where('status', $status);
            $rekap->push([
                'status' => Pinjaman::make()->setAttribute('status', $status)->status_label,
                'status_color' => Pinjaman::make()->setAttribute('status', $status)->status_color,
                'total' => $q->count(),
                'total_nominal' => $q->sum('nominal'),
            ]);
        }

        $grandTotal = $rekap->sum('total_nominal');
        $grandCount = $rekap->sum('total');

        return view('laporan.rekap-pinjaman', compact('periode', 'rekap', 'grandTotal', 'grandCount'));
    }

    public function rekapAngsuran()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $angsuran = Angsuran::with('anggota', 'pinjaman')
            ->when($periodeId, fn ($q) => $q->whereHas('pinjaman', fn ($q) => $q->where('periode_id', $periodeId)))
            ->latest('tanggal_bayar')
            ->get();

        $totalNominal = $angsuran->sum('nominal');
        $totalDenda = $angsuran->sum('denda');
        $totalTransaksi = $angsuran->count();

        $perBulan = $angsuran->groupBy(fn ($a) => $a->tanggal_bayar?->format('F Y') ?? 'Tanpa Tanggal')
            ->map(fn ($items, $bulan) => [
                'bulan' => $bulan,
                'total' => $items->count(),
                'nominal' => $items->sum('nominal'),
                'denda' => $items->sum('denda'),
            ])->values();

        return view('laporan.rekap-angsuran', compact('periode', 'totalNominal', 'totalDenda', 'totalTransaksi', 'perBulan'));
    }

    public function arusKas()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $kas = Kas::with('periode')
            ->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId))
            ->orderBy('tanggal')
            ->get();

        $perKategori = $kas->groupBy('kategori')->map(fn ($items, $kategori) => [
            'kategori' => $kategori,
            'masuk' => $items->where('jenis', 'masuk')->sum('nominal'),
            'keluar' => $items->where('jenis', 'keluar')->sum('nominal'),
        ])->values();

        $totalMasuk = $kas->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = $kas->where('jenis', 'keluar')->sum('nominal');
        $saldoAkhir = $totalMasuk - $totalKeluar;

        return view('laporan.arus-kas', compact('periode', 'perKategori', 'totalMasuk', 'totalKeluar', 'saldoAkhir'));
    }
}
