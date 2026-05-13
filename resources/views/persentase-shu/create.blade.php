<x-app-layout>
    <x-slot name="header">Tambah Persentase SHU</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.persentase-shu.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Dana</label>
                        <input type="text" name="dana" value="{{ old('dana') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Persentase (%)</label>
                        <input type="number" step="0.01" name="persentase" value="{{ old('persentase') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.persentase-shu.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
