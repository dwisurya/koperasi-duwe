<x-app-layout>
    <x-slot name="header">Edit Bunga Pinjaman</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.bunga-pinjaman.update', $bungaPinjaman) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $bungaPinjaman->nama) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bunga (%)</label>
                        <input type="number" step="0.01" name="bunga" value="{{ old('bunga', $bungaPinjaman->bunga) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="">- Select -</option>
                            <option value="Flat" {{ old('jenis', $bungaPinjaman->jenis) === 'Flat' ? 'selected' : '' }}>Flat</option>
                            <option value="Efektif" {{ old('jenis', $bungaPinjaman->jenis) === 'Efektif' ? 'selected' : '' }}>Efektif</option>
                            <option value="Anuitas" {{ old('jenis', $bungaPinjaman->jenis) === 'Anuitas' ? 'selected' : '' }}>Anuitas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $bungaPinjaman->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $bungaPinjaman->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $bungaPinjaman->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.bunga-pinjaman.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
