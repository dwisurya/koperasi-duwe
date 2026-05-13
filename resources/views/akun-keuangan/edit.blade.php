<x-app-layout>
    <x-slot name="header">Edit Akun Keuangan</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.akun-keuangan.update', $akunKeuangan) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $akunKeuangan->kode) }}" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $akunKeuangan->nama) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kategori</label>
                        <select name="kategori_type" class="form-select" id="kategori_type">
                            <option value="">- Select -</option>
                            <option value="aktiva" {{ $akunKeuangan->kategori_aktiva_id ? 'selected' : '' }} {{ old('kategori_type') === 'aktiva' ? 'selected' : '' }}>Aktiva</option>
                            <option value="passiva" {{ $akunKeuangan->kategori_passiva_id ? 'selected' : '' }} {{ old('kategori_type') === 'passiva' ? 'selected' : '' }}>Passiva</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="kategori_aktiva_wrapper" style="display: none;">
                        <label class="form-label">Kategori Aktiva</label>
                        <select name="kategori_aktiva_id" class="form-select">
                            <option value="">- Select -</option>
                            @foreach($kategoriAktiva as $ka)
                                <option value="{{ $ka->id }}" {{ old('kategori_aktiva_id', $akunKeuangan->kategori_aktiva_id) == $ka->id ? 'selected' : '' }}>{{ $ka->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6" id="kategori_passiva_wrapper" style="display: none;">
                        <label class="form-label">Kategori Passiva</label>
                        <select name="kategori_passiva_id" class="form-select">
                            <option value="">- Select -</option>
                            @foreach($kategoriPassiva as $kp)
                                <option value="{{ $kp->id }}" {{ old('kategori_passiva_id', $akunKeuangan->kategori_passiva_id) == $kp->id ? 'selected' : '' }}>{{ $kp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $akunKeuangan->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $akunKeuangan->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $akunKeuangan->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.akun-keuangan.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('kategori_type');
            const aktivaWrapper = document.getElementById('kategori_aktiva_wrapper');
            const passivaWrapper = document.getElementById('kategori_passiva_wrapper');

            function toggleKategori() {
                const val = typeSelect.value;
                aktivaWrapper.style.display = val === 'aktiva' ? '' : 'none';
                passivaWrapper.style.display = val === 'passiva' ? '' : 'none';
            }

            typeSelect.addEventListener('change', toggleKategori);
            toggleKategori();
        });
    </script>
    @endpush
</x-app-layout>
