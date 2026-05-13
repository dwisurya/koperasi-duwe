<x-app-layout>
    <x-slot name="header">Tambah Titip Dana</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.titip-dana.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Penitip</label>
                        <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <option value="masuk" {{ old('jenis') === 'masuk' ? 'selected' : '' }}>Titipan Masuk</option>
                            <option value="keluar" {{ old('jenis') === 'keluar' ? 'selected' : '' }}>Titipan Keluar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">- Pilih -</option>
                            <option value="belum_diketahui" {{ old('status') === 'belum_diketahui' ? 'selected' : '' }}>Belum Diketahui</option>
                            <option value="sudah_diketahui" {{ old('status') === 'sudah_diketahui' ? 'selected' : '' }}>Sudah Diketahui</option>
                        </select>
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
                    <a href="{{ route('admin.titip-dana.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
