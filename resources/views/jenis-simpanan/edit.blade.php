<x-app-layout>
    <x-slot name="header">Edit Jenis Simpanan</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.jenis-simpanan.update', $jenisSimpanan) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $jenisSimpanan->kode) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $jenisSimpanan->nama) }}" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $jenisSimpanan->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.jenis-simpanan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
