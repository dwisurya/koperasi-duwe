<x-app-layout>
    <x-slot name="header">Create Bunga Pinjaman</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.bunga-pinjaman.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bunga (%)</label>
                        <input type="number" step="0.01" name="bunga" value="{{ old('bunga') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select">
                            <option value="">- Select -</option>
                            <option value="Flat" {{ old('jenis') === 'Flat' ? 'selected' : '' }}>Flat</option>
                            <option value="Efektif" {{ old('jenis') === 'Efektif' ? 'selected' : '' }}>Efektif</option>
                            <option value="Anuitas" {{ old('jenis') === 'Anuitas' ? 'selected' : '' }}>Anuitas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.bunga-pinjaman.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
