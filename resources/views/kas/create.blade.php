<x-app-layout>
    <x-slot name="header">Tambah Transaksi Kas</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kas.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <option value="masuk" {{ old('jenis') === 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="keluar" {{ old('jenis') === 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <optgroup label="Pemasukan">
                                <option value="Simpanan Pokok" {{ old('kategori') === 'Simpanan Pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                                <option value="Simpanan Wajib" {{ old('kategori') === 'Simpanan Wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                                <option value="Simpanan Sukarela" {{ old('kategori') === 'Simpanan Sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                                <option value="Bagi Hasil" {{ old('kategori') === 'Bagi Hasil' ? 'selected' : '' }}>Bagi Hasil</option>
                                <option value="Angsuran Pinjaman" {{ old('kategori') === 'Angsuran Pinjaman' ? 'selected' : '' }}>Angsuran Pinjaman</option>
                                <option value="Bunga Pinjaman" {{ old('kategori') === 'Bunga Pinjaman' ? 'selected' : '' }}>Bunga Pinjaman</option>
                                <option value="Pendapatan Lain" {{ old('kategori') === 'Pendapatan Lain' ? 'selected' : '' }}>Pendapatan Lain</option>
                            </optgroup>
                            <optgroup label="Pengeluaran">
                                <option value="Biaya Operasional" {{ old('kategori') === 'Biaya Operasional' ? 'selected' : '' }}>Biaya Operasional</option>
                                <option value="Biaya Administrasi" {{ old('kategori') === 'Biaya Administrasi' ? 'selected' : '' }}>Biaya Administrasi</option>
                                <option value="Pinjaman Cair" {{ old('kategori') === 'Pinjaman Cair' ? 'selected' : '' }}>Pinjaman Cair</option>
                                <option value="Pengeluaran Lain" {{ old('kategori') === 'Pengeluaran Lain' ? 'selected' : '' }}>Pengeluaran Lain</option>
                            </optgroup>
                            <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya (isi manual)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori (Manual)</label>
                        <input type="text" name="kategori_manual" value="{{ old('kategori_manual') }}" class="form-control" placeholder="Jika memilih 'Lainnya', isi di sini">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" step="0.01" name="nominal" value="{{ old('nominal') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select">
                            <option value="">- Auto (periode aktif) -</option>
                            @foreach(\App\Models\Periode::latest()->get() as $p)
                                <option value="{{ $p->id }}" {{ old('periode_id') == $p->id ? 'selected' : '' }}>{{ $p->tahun }} {{ $p->nama ? '- '.$p->nama : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.kas.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
