<x-app-layout>
    <x-slot name="header">Tambah Jenis Simpanan</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.jenis-simpanan.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode') }}" class="form-control" required placeholder="pokok">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required placeholder="Simpanan Pokok">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.jenis-simpanan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
