<?php

namespace App\Http\Controllers;

use App\Models\Kas;

class KeuanganController extends Controller
{
    public function kasBrankas()
    {
        return $this->filterByKategori('Kas Brankas', 'Kas Brankas');
    }

    public function rekeningBri()
    {
        return $this->filterByKategori('Bank BRI', 'Rekening BRI');
    }

    public function rekeningLpd()
    {
        return $this->filterByKategori('Rekening LPD', 'Rekening LPD');
    }

    public function pendapatan()
    {
        return $this->filterByJenis('masuk', 'Pendapatan');
    }

    public function pengeluaran()
    {
        return $this->filterByJenis('keluar', 'Pengeluaran');
    }

    public function danaPengurus()
    {
        return $this->filterByKategori('Dana Pengurus', 'Dana Pengurus');
    }

    public function danaSosial()
    {
        return $this->filterByKategori('Dana Sosial', 'Dana Sosial');
    }

    public function danaRapat()
    {
        return $this->filterByKategori('Dana Rapat', 'Dana Rapat');
    }

    public function cadanganModal()
    {
        return $this->filterByKategori('Cadangan Modal', 'Cadangan Modal');
    }

    public function cadanganResiko()
    {
        return $this->filterByKategori('Cadangan Resiko', 'Cadangan Resiko');
    }

    public function penyertaan()
    {
        return $this->filterByKategori('Penyertaan', 'Penyertaan');
    }

    private function filterByKategori(string $kategori, string $judul)
    {
        $entries = Kas::with('periode')
            ->where('kategori', $kategori)
            ->latest()
            ->get();

        $totalMasuk = $entries->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = $entries->where('jenis', 'keluar')->sum('nominal');

        return view('keuangan.index', compact('entries', 'judul', 'totalMasuk', 'totalKeluar'));
    }

    private function filterByJenis(string $jenis, string $judul)
    {
        $entries = Kas::with('periode')
            ->where('jenis', $jenis)
            ->latest()
            ->get();

        $totalMasuk = $jenis === 'masuk' ? $entries->sum('nominal') : 0;
        $totalKeluar = $jenis === 'keluar' ? $entries->sum('nominal') : 0;

        return view('keuangan.index', compact('entries', 'judul', 'totalMasuk', 'totalKeluar'));
    }
}
