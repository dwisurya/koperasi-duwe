<x-app-layout>
    <x-slot name="header">Create Anggota</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.anggota.store') }}" method="POST" id="anggotaForm">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">- Select -</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Daftar</label>
                        <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ayah</label>
                        <input type="text" name="ayah" value="{{ old('ayah') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ibu</label>
                        <input type="text" name="ibu" value="{{ old('ibu') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Simpanan Pokok (Rp)</label>
                        <input type="text" name="simpanan_pokok" value="{{ old('simpanan_pokok') ? number_format((float) old('simpanan_pokok'), 0, ',', '.') : '0' }}" class="form-control rupiah-input">
                        <small class="text-muted">{{ __('Simpanan pokok awal saat pendaftaran, tidak bisa ditarik') }}</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    function formatRupiah(el) {
        const val = el.value.replace(/\./g, '').replace(/\D/g, '');
        el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.querySelectorAll('.rupiah-input').forEach(function(el) {
        el.addEventListener('input', function() { formatRupiah(this); });
    });

    document.getElementById('anggotaForm')?.addEventListener('submit', function() {
        document.querySelectorAll('.rupiah-input').forEach(function(el) {
            el.value = el.value.replace(/\./g, '');
        });
    });
</script>
@endpush
