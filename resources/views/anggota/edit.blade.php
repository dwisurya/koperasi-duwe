<x-app-layout>
    <x-slot name="header">Edit Anggota</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.anggota.update', $anggota) }}" method="POST" id="anggotaForm">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <input type="text" value="{{ $anggota->kode }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $anggota->nama) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $anggota->nik) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">- Select -</option>
                            <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $anggota->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Daftar</label>
                        <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', $anggota->tanggal_daftar?->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ayah</label>
                        <input type="text" name="ayah" value="{{ old('ayah', $anggota->ayah) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ibu</label>
                        <input type="text" name="ibu" value="{{ old('ibu', $anggota->ibu) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Saldo Awal (Rp)</label>
                        <input type="text" name="saldo_awal" value="{{ old('saldo_awal') ? number_format((float) old('saldo_awal'), 0, ',', '.') : number_format((float) $anggota->saldo_awal, 0, ',', '.') }}" class="form-control rupiah-input">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
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
