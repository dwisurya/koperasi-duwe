<x-app-layout>
    <x-slot name="header">Edit Persentase SHU</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.persentase-shu.update', $persentaseShu) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Dana</label>
                        <input type="text" name="dana" value="{{ old('dana', $persentaseShu->dana) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Persentase (%)</label>
                        <input type="number" step="0.01" name="persentase" value="{{ old('persentase', $persentaseShu->persentase) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="urutan" value="{{ old('urutan', $persentaseShu->urutan) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $persentaseShu->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.persentase-shu.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
