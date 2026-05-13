<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunKeuanganController;
use App\Http\Controllers\AkunModalController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\BukuKreditController;
use App\Http\Controllers\BukuTabunganController;
use App\Http\Controllers\BungaPinjamanController;
use App\Http\Controllers\JenisSimpananController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KategoriAktivaController;
use App\Http\Controllers\KategoriPassivaController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersentaseShuController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShuController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\TitipDanaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show'])->parameters(['user' => 'user']);
    Route::resource('roles', RoleController::class)->except(['show'])->parameters(['role' => 'role']);
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('menus', MenuController::class)->except(['show']);
    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
    Route::post('anggota/{anggota}/keluarkan', [AnggotaController::class, 'keluarkan'])->name('anggota.keluarkan');
    Route::post('anggota/{anggota}/masukkan-kembali', [AnggotaController::class, 'masukkanKembali'])->name('anggota.masukkan-kembali');
    Route::resource('bunga-pinjaman', BungaPinjamanController::class)->except(['show'])->parameters(['bunga_pinjaman' => 'bungaPinjaman']);
    Route::resource('simpanan', SimpananController::class)->except(['show']);
    Route::post('simpanan/{simpanan}/tarik', [SimpananController::class, 'tarik'])->name('simpanan.tarik');
    Route::get('simpanan/pokok', [SimpananController::class, 'pokok'])->name('simpanan.pokok');
    Route::get('simpanan/wajib', [SimpananController::class, 'wajib'])->name('simpanan.wajib');
    Route::get('simpanan/penyertaan', [SimpananController::class, 'penyertaan'])->name('simpanan.penyertaan');

    Route::post('periodes/{periode}/activate', [PeriodeController::class, 'activate'])->name('periodes.activate');
    Route::resource('periodes', PeriodeController::class)->except(['show']);

    Route::resource('angsuran', AngsuranController::class)->except(['show']);

    Route::resource('kas', KasController::class)->except(['show'])->parameters(['kas' => 'kas']);

    Route::resource('akun-aktiva', KategoriAktivaController::class)->except(['show'])->parameters(['akun_aktiva' => 'kategoriAktiva']);
    Route::resource('akun-passiva', KategoriPassivaController::class)->except(['show'])->parameters(['akun_passiva' => 'kategoriPassiva']);
    Route::resource('akun-keuangan', AkunKeuanganController::class)->except(['show'])->parameters(['akun_keuangan' => 'akunKeuangan']);

    Route::resource('jenis-simpanan', JenisSimpananController::class)->except(['show'])->parameters(['jenis_simpanan' => 'jenisSimpanan']);
    Route::resource('akun-modal', AkunModalController::class)->except(['show'])->parameters(['akun_modal' => 'akunModal']);
    Route::resource('persentase-shu', PersentaseShuController::class)->except(['show'])->parameters(['persentase_shu' => 'persentaseShu']);

    Route::get('neraca', [NeracaController::class, 'index'])->name('neraca.index');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('saldo-anggota', [LaporanController::class, 'saldoAnggota'])->name('saldo-anggota');
        Route::get('tunggakan', [LaporanController::class, 'tunggakan'])->name('tunggakan');
        Route::get('rugi-laba', [LaporanController::class, 'rugiLaba'])->name('rugi-laba');
        Route::get('rekap-simpanan', [LaporanController::class, 'rekapSimpanan'])->name('rekap-simpanan');
        Route::get('rekap-pinjaman', [LaporanController::class, 'rekapPinjaman'])->name('rekap-pinjaman');
        Route::get('rekap-angsuran', [LaporanController::class, 'rekapAngsuran'])->name('rekap-angsuran');
        Route::get('arus-kas', [LaporanController::class, 'arusKas'])->name('arus-kas');
    });

    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('kas-brankas', [KeuanganController::class, 'kasBrankas'])->name('kas-brankas');
        Route::get('rekening-bri', [KeuanganController::class, 'rekeningBri'])->name('rekening-bri');
        Route::get('rekening-lpd', [KeuanganController::class, 'rekeningLpd'])->name('rekening-lpd');
        Route::get('pendapatan', [KeuanganController::class, 'pendapatan'])->name('pendapatan');
        Route::get('pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('dana-pengurus', [KeuanganController::class, 'danaPengurus'])->name('dana-pengurus');
        Route::get('dana-sosial', [KeuanganController::class, 'danaSosial'])->name('dana-sosial');
        Route::get('dana-rapat', [KeuanganController::class, 'danaRapat'])->name('dana-rapat');
        Route::get('cadangan-modal', [KeuanganController::class, 'cadanganModal'])->name('cadangan-modal');
        Route::get('cadangan-resiko', [KeuanganController::class, 'cadanganResiko'])->name('cadangan-resiko');
        Route::get('penyertaan', [KeuanganController::class, 'penyertaan'])->name('penyertaan');
    });

    Route::get('shu', [ShuController::class, 'index'])->name('shu.index');
    Route::post('shu/calculate', [ShuController::class, 'calculate'])->name('shu.calculate');
    Route::post('shu/distribute', [ShuController::class, 'distribute'])->name('shu.distribute');

    Route::prefix('rat')->name('rat.')->group(function () {
        Route::get('voting', [VotingController::class, 'index'])->name('voting');
        Route::get('berita-acara', [BeritaAcaraController::class, 'index'])->name('berita-acara');
    });

    Route::get('buku-kredit', [BukuKreditController::class, 'index'])->name('buku-kredit.index');
    Route::get('buku-kredit/{pinjaman}', [BukuKreditController::class, 'show'])->name('buku-kredit.show');

    Route::get('pinjaman/pengajuan', [PinjamanController::class, 'pengajuan'])->name('pinjaman.pengajuan');
    Route::get('pinjaman/approval', [PinjamanController::class, 'approval'])->name('pinjaman.approval');
    Route::get('pinjaman/pencairan', [PinjamanController::class, 'pencairan'])->name('pinjaman.pencairan');
    Route::post('pinjaman/{pinjaman}/cairkan', [PinjamanController::class, 'cairkan'])->name('pinjaman.cairkan');

    Route::get('pinjaman/simulasi', [PinjamanController::class, 'simulasi'])->name('pinjaman.simulasi');
    Route::post('pinjaman/{pinjaman}/approve', [PinjamanController::class, 'approve'])->name('pinjaman.approve');
    Route::post('pinjaman/{pinjaman}/reject', [PinjamanController::class, 'reject'])->name('pinjaman.reject');
    Route::get('pinjaman/{pinjaman}/cetak-kontrak', [PinjamanController::class, 'cetakKontrak'])->name('pinjaman.cetak-kontrak');
    Route::resource('pinjaman', PinjamanController::class)->except(['show']);

    Route::resource('titip-dana', TitipDanaController::class)->except(['show'])->parameters(['titip_dana' => 'titipDana']);

    Route::get('buku-tabungan', [BukuTabunganController::class, 'index'])->name('buku-tabungan.index');
    Route::get('buku-tabungan/{anggota}', [BukuTabunganController::class, 'show'])->name('buku-tabungan.show');

    Route::prefix('utility')->name('utility.')->group(function () {
        Route::get('backup', [UtilityController::class, 'backup'])->name('backup');
        Route::post('backup', [UtilityController::class, 'doBackup'])->name('backup.do');
        Route::get('backup/{filename}/download', [UtilityController::class, 'downloadBackup'])->name('backup.download');
        Route::delete('backup/{filename}', [UtilityController::class, 'deleteBackup'])->name('backup.delete');
        Route::get('import', [UtilityController::class, 'import'])->name('import');
        Route::post('import', [UtilityController::class, 'doImport'])->name('import.do');
        Route::get('export', [UtilityController::class, 'export'])->name('export');
        Route::post('export', [UtilityController::class, 'doExport'])->name('export.do');
        Route::get('activity-log', [UtilityController::class, 'activityLog'])->name('activity-log');
    });
});

require __DIR__.'/auth.php';
