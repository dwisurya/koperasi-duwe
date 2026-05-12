<x-app-layout>
    <x-slot name="header">Edit Transaksi Kas</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.kas.update', $kas) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $kas->tanggal->format('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <option value="masuk" {{ old('jenis', $kas->jenis) === 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="keluar" {{ old('jenis', $kas->jenis) === 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" id="edit-kategori">
                            <option value="">- Pilih -</option>
                            <optgroup label="Pemasukan">
                                <option value="Simpanan Pokok" {{ old('kategori', $kas->kategori) === 'Simpanan Pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                                <option value="Simpanan Wajib" {{ old('kategori', $kas->kategori) === 'Simpanan Wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                                <option value="Simpanan Sukarela" {{ old('kategori', $kas->kategori) === 'Simpanan Sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                                <option value="Bagi Hasil" {{ old('kategori', $kas->kategori) === 'Bagi Hasil' ? 'selected' : '' }}>Bagi Hasil</option>
                                <option value="Angsuran Pinjaman" {{ old('kategori', $kas->kategori) === 'Angsuran Pinjaman' ? 'selected' : '' }}>Angsuran Pinjaman</option>
                                <option value="Bunga Pinjaman" {{ old('kategori', $kas->kategori) === 'Bunga Pinjaman' ? 'selected' : '' }}>Bunga Pinjaman</option>
                                <option value="Pendapatan Lain" {{ old('kategori', $kas->kategori) === 'Pendapatan Lain' ? 'selected' : '' }}>Pendapatan Lain</option>
                            </optgroup>
                            <optgroup label="Pengeluaran">
                                <option value="Biaya Operasional" {{ old('kategori', $kas->kategori) === 'Biaya Operasional' ? 'selected' : '' }}>Biaya Operasional</option>
                                <option value="Biaya Administrasi" {{ old('kategori', $kas->kategori) === 'Biaya Administrasi' ? 'selected' : '' }}>Biaya Administrasi</option>
                                <option value="Pinjaman Cair" {{ old('kategori', $kas->kategori) === 'Pinjaman Cair' ? 'selected' : '' }}>Pinjaman Cair</option>
                                <option value="Pengeluaran Lain" {{ old('kategori', $kas->kategori) === 'Pengeluaran Lain' ? 'selected' : '' }}>Pengeluaran Lain</option>
                            </optgroup>
                            <option value="Lainnya" {{ !in_array(old('kategori', $kas->kategori), ['Simpanan Pokok','Simpanan Wajib','Simpanan Sukarela','Bagi Hasil','Angsuran Pinjaman','Bunga Pinjaman','Pendapatan Lain','Biaya Operasional','Biaya Administrasi','Pinjaman Cair','Pengeluaran Lain']) ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" step="0.01" name="nominal" value="{{ old('nominal', $kas->nominal) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select">
                            <option value="">- Auto (periode aktif) -</option>
                            @foreach(\App\Models\Periode::latest()->get() as $p)
                                <option value="{{ $p->id }}" {{ old('periode_id', $kas->periode_id) == $p->id ? 'selected' : '' }}>{{ $p->tahun }} {{ $p->nama ? '- '.$p->nama : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $kas->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.kas.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
