<x-app-layout>
    <x-slot name="header">Create Angsuran</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.angsuran.store') }}" method="POST" id="angsuranForm">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota</label>
                        <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                            <option value="">- Select -</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>{{ $anggota->kode }} - {{ $anggota->nama }}</option>
                            @endforeach
                        </select>
                        @error('anggota_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pinjaman</label>
                        <select name="pinjaman_id" class="form-select @error('pinjaman_id') is-invalid @enderror" required>
                            <option value="">- Select -</option>
                            @foreach($pinjaman as $p)
                                <option value="{{ $p->id }}" {{ old('pinjaman_id') == $p->id ? 'selected' : '' }}>{{ $p->anggota?->nama }} - Rp {{ number_format($p->nominal, 0, ',', '.') }} ({{ $p->status }})</option>
                            @endforeach
                        </select>
                        @error('pinjaman_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Angsuran Ke</label>
                        <input type="number" name="angsuran_ke" value="{{ old('angsuran_ke') }}" class="form-control @error('angsuran_ke') is-invalid @enderror" required min="1">
                        @error('angsuran_ke') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" class="form-control @error('tanggal_bayar') is-invalid @enderror" required>
                        @error('tanggal_bayar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="text" name="nominal" value="{{ old('nominal') }}" class="form-control rupiah-input @error('nominal') is-invalid @enderror" required>
                        @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Denda (Rp)</label>
                        <input type="text" name="denda" value="{{ old('denda', '0') }}" class="form-control rupiah-input">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.angsuran.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
    document.getElementById('angsuranForm')?.addEventListener('submit', function() {
        document.querySelectorAll('.rupiah-input').forEach(function(el) {
            el.value = el.value.replace(/\./g, '');
        });
    });
</script>
@endpush
