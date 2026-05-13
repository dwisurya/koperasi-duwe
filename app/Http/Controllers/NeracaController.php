<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kas;
use App\Models\Periode;
use App\Models\Pinjaman;
use App\Models\ShuDetail;
use App\Models\Simpanan;
use App\Models\TitipDana;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NeracaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:neraca-list', only: ['index']),
        ];
    }

    public function index()
    {
        $periode = Periode::getActive();
        $periodeId = $periode?->id;

        $baseKas = Kas::query();
        $baseSimpanan = Simpanan::query();
        $basePinjaman = Pinjaman::query();
        $baseAngsuran = Angsuran::query();
        $baseTitipDana = TitipDana::query();

        if ($periodeId) {
            $baseKas->where('periode_id', $periodeId);
            $baseSimpanan->where('periode_id', $periodeId);
            $basePinjaman->where('periode_id', $periodeId);
            $baseAngsuran->whereHas('pinjaman', fn ($q) => $q->where('periode_id', $periodeId));
            $baseTitipDana->where('periode_id', $periodeId);
        }

        $kasAkun = fn ($kategori) => (clone $baseKas)->where('kategori', $kategori)->where('jenis', 'masuk')->sum('nominal')
            - (clone $baseKas)->where('kategori', $kategori)->where('jenis', 'keluar')->sum('nominal');

        $saldoBrankas = $kasAkun('Kas Brankas');
        $saldoBri = $kasAkun('Bank BRI');
        $saldoLpd = $kasAkun('Rekening LPD');
        $saldoKas = $saldoBrankas + $saldoBri + $saldoLpd;

        $totalPinjamanDisalurkan = (clone $basePinjaman)->whereIn('status', ['aktif', 'lunas', 'macet'])->sum('nominal');
        $totalAngsuran = (clone $baseAngsuran)->sum('nominal');
        $piutangPinjaman = max($totalPinjamanDisalurkan - $totalAngsuran, 0);

        $titipMasuk = (clone $baseTitipDana)->where('jenis', 'masuk')->sum('nominal');
        $titipKeluar = (clone $baseTitipDana)->where('jenis', 'keluar')->sum('nominal');
        $saldoTitipDana = $titipMasuk - $titipKeluar;

        $koreksiPinjaman = $kasAkun('Koreksi Pinjaman');

        $aktivaGroups = [
            'AKTIVA LANCAR' => [
                'Kas' => [
                    'children' => [
                        ['nama' => 'Brankas', 'saldo' => $saldoBrankas],
                        ['nama' => 'Rekening BRI', 'saldo' => $saldoBri],
                        ['nama' => 'Rekening LPD', 'saldo' => $saldoLpd],
                    ],
                    'saldo' => $saldoKas,
                ],
                'items' => [
                    ['nama' => 'Piutang Pinjaman', 'saldo' => $piutangPinjaman],
                    ['nama' => 'Titip Dana', 'saldo' => $saldoTitipDana],
                    ['nama' => 'Koreksi Pinjaman', 'saldo' => $koreksiPinjaman],
                ],
            ],
        ];

        $totalAktiva = $saldoKas + $piutangPinjaman + $saldoTitipDana + $koreksiPinjaman;

        $simpananPokok = (clone $baseSimpanan)->where('jenis', 'pokok')->sum('nominal');
        $simpananWajib = (clone $baseSimpanan)->where('jenis', 'wajib')->sum('nominal');
        $simpananPenyertaan = (clone $baseSimpanan)->where('jenis', 'penyertaan')->sum('nominal');
        $bagiHasil = (clone $baseSimpanan)->where('jenis', 'bagi_hasil')->sum('nominal');

        $passivaGroups = [
            'Simpanan' => [
                ['nama' => 'Simpanan Pokok', 'saldo' => $simpananPokok],
                ['nama' => 'Simpanan Wajib', 'saldo' => $simpananWajib],
                ['nama' => 'Tabungan Penyertaan', 'saldo' => $simpananPenyertaan],
                ['nama' => 'Bagi Hasil', 'saldo' => $bagiHasil],
            ],
            'Modal Lainnya' => [
                ['nama' => 'Dana Pengurus', 'saldo' => $kasAkun('Dana Pengurus')],
                ['nama' => 'Dana Sosial', 'saldo' => $kasAkun('Dana Sosial')],
                ['nama' => 'Cadangan Modal', 'saldo' => $kasAkun('Cadangan Modal')],
                ['nama' => 'Cadangan Resiko', 'saldo' => $kasAkun('Cadangan Resiko')],
                ['nama' => 'Dana Rapat', 'saldo' => $kasAkun('Dana Rapat')],
                ['nama' => 'SHU Periode Lalu', 'saldo' => $kasAkun('SHU Periode Lalu')],
                ['nama' => 'Penyertaan', 'saldo' => $kasAkun('Penyertaan')],
            ],
            'Pinjaman' => [
                ['nama' => 'Pinjam Dana Pura', 'saldo' => $kasAkun('Pinjam Dana Pura')],
                ['nama' => 'Pinjam SUKDUK', 'saldo' => $kasAkun('Pinjam SUKDUK')],
            ],
        ];

        $shuDistributedItems = ShuDetail::whereHas('shu', fn ($q) => $q
            ->where('periode_id', $periodeId)
            ->where('is_distributed', true)
        )->get();

        $shuDistributedTotal = $shuDistributedItems->sum('nominal');

        if ($shuDistributedItems->isNotEmpty()) {
            $passivaGroups['Distribusi SHU'] = $shuDistributedItems->groupBy('dana')->map(fn ($items, $dana) => [
                'nama' => $dana,
                'saldo' => $items->sum('nominal'),
            ])->values()->toArray();
        }

        $totalPassiva = collect($passivaGroups)->flatten(1)->sum('saldo');
        $selisih = $totalAktiva - $totalPassiva;

        return view('neraca.index', compact(
            'periode',
            'aktivaGroups',
            'passivaGroups',
            'totalAktiva',
            'totalPassiva',
            'selisih',
            'shuDistributedTotal',
        ));
    }
}
