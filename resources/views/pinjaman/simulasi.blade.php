<x-app-layout>
    <x-slot name="header">Simulasi Cicilan Pinjaman</x-slot>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><span>Parameter Pinjaman</span></div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.pinjaman.simulasi') }}">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Pinjaman (Rp)</label>
                            <input type="text" name="nominal" value="{{ old('nominal', $nominal ?? '') ? number_format((float) (old('nominal', $nominal ?? 0)), 0, ',', '.') : '' }}" class="form-control rupiah-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bunga (%)</label>
                            <div class="mb-2">
                                <select name="bunga_pinjaman_id" id="bunga_select" class="form-select">
                                    <option value="">-- Pilih dari database atau isi manual --</option>
                                    @foreach($bungaPinjaman as $bp)
                                        <option value="{{ $bp->id }}" {{ (old('bunga_pinjaman_id', $selectedBungaId ?? '') == $bp->id) ? 'selected' : '' }}>
                                            {{ $bp->nama }} ({{ $bp->bunga }}%)
                                        </option>
                                    @endforeach
                                    <option value="custom">Lainnya (isi manual)</option>
                                </select>
                            </div>
                            <div id="manual_bunga_wrapper" class="{{ $selectedBungaId ? 'd-none' : '' }}">
                                <input type="number" step="0.01" name="bunga" id="bunga_manual" value="{{ old('bunga', $bunga ?? '') }}" class="form-control" placeholder="Masukkan bunga (%)">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tenor (Bulan)</label>
                            <select name="tenor" class="form-select" required>
                                <option value="">- Pilih -</option>
                                @foreach([1, 3, 6, 12, 24, 36] as $bln)
                                    <option value="{{ $bln }}" {{ (old('tenor', $tenor ?? '') == $bln) ? 'selected' : '' }}>{{ $bln }} Bulan</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-calculator"></i> Hitung Simulasi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            @if(isset($detail))
                <div class="card">
                    <div class="card-header"><span>Hasil Simulasi</span></div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <small class="text-muted">Cicilan/Bulan</small>
                                    <h5 class="mb-0 text-primary">Rp {{ number_format($cicilanPerBulan, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <small class="text-muted">Total Bunga</small>
                                    <h5 class="mb-0 text-danger">Rp {{ number_format($totalBunga, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2 text-center">
                                    <small class="text-muted">Total Pembayaran</small>
                                    <h5 class="mb-0 text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>

                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Bulan</th>
                                    <th class="text-end">Cicilan Pokok</th>
                                    <th class="text-end">Bunga</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Sisa Pinjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detail as $d)
                                    <tr>
                                        <td class="text-center">{{ $d['bulan'] }}</td>
                                        <td class="text-end">Rp {{ number_format($d['cicilan_pokok'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($d['bunga'], 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold">Rp {{ number_format($d['total'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($d['sisa'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-2">
                            <small class="text-muted">* Simulasi menggunakan metode flat rate. Hasil aktual dapat berbeda.</small>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calculator" style="font-size: 3rem; color: #ddd;"></i>
                        <p class="text-muted mt-3">Masukkan parameter pinjaman di samping untuk melihat simulasi cicilan.</p>
                    </div>
                </div>
            @endif
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

    document.querySelector('form').addEventListener('submit', function() {
        document.querySelectorAll('.rupiah-input').forEach(function(el) {
            el.value = el.value.replace(/\./g, '');
        });
        document.querySelector('#bunga_manual').removeAttribute('disabled');
    });

    const bungaSelect = document.querySelector('#bunga_select');
    const manualWrapper = document.querySelector('#manual_bunga_wrapper');
    const manualInput = document.querySelector('#bunga_manual');

    function toggleBungaInput() {
        if (bungaSelect.value === 'custom' || bungaSelect.value === '') {
            manualWrapper.classList.remove('d-none');
            manualInput.removeAttribute('disabled');
            if (bungaSelect.value === 'custom') manualInput.focus();
        } else {
            manualWrapper.classList.add('d-none');
            manualInput.setAttribute('disabled', 'disabled');
        }
    }

    bungaSelect.addEventListener('change', toggleBungaInput);
    toggleBungaInput();
</script>
@endpush
