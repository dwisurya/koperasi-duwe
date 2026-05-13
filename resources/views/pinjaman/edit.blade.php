<x-app-layout>
    <x-slot name="header">{{ __('Edit Pengajuan Pinjaman') }}</x-slot>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ __('Terdapat kesalahan pada form:') }}
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.pinjaman.update', $pinjaman) }}" method="POST" id="pinjamanForm">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nama Anggota') }}</label>
                        <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                            <option value="">- {{ __('Pilih') }} -</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('anggota_id', $pinjaman->anggota_id) == $anggota->id ? 'selected' : '' }}>{{ $anggota->kode }} - {{ $anggota->nama }}</option>
                            @endforeach
                        </select>
                        @error('anggota_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Tanggal Pengajuan') }}</label>
                        <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', $pinjaman->tanggal_pengajuan?->format('Y-m-d')) }}" class="form-control @error('tanggal_pengajuan') is-invalid @enderror" required>
                        @error('tanggal_pengajuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nominal') }} (Rp)</label>
                        <input type="text" name="nominal" id="nominal" value="{{ old('nominal') ? number_format((float) old('nominal'), 0, ',', '.') : number_format((float) $pinjaman->nominal, 0, ',', '.') }}" class="form-control rupiah-input @error('nominal') is-invalid @enderror" required>
                        @error('nominal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Bunga Pinjaman') }} (%)</label>
                        @php
                            $bungaDisplay = old('bunga_display');
                            if (!$bungaDisplay && $pinjaman->bungaPinjaman) {
                                $bungaDisplay = $pinjaman->bungaPinjaman->nama . ' (' . $pinjaman->bunga_persen . '%)';
                            } elseif (!$bungaDisplay) {
                                $bungaDisplay = (string) $pinjaman->bunga_persen;
                            }
                        @endphp
                        <input type="text" name="bunga_display" id="bunga_input" class="form-control @error('bunga_persen') is-invalid @enderror" list="bunga_list" placeholder="{{ __('Ketik atau pilih bunga') }}" autocomplete="off" value="{{ $bungaDisplay }}">
                        <datalist id="bunga_list">
                            @foreach($bungaPinjaman as $bp)
                                <option value="{{ $bp->nama }} ({{ $bp->bunga }}%)">
                            @endforeach
                        </datalist>
                        <input type="hidden" name="bunga_pinjaman_id" id="bunga_pinjaman_id" value="{{ old('bunga_pinjaman_id', $pinjaman->bunga_pinjaman_id) }}">
                        <input type="hidden" name="bunga_persen" id="bunga_persen" value="{{ old('bunga_persen', $pinjaman->bunga_persen) }}">
                        @error('bunga_persen') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <small class="text-muted">{{ __('Pilih dari daftar, atau ketik angka persentase kustom') }}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Tenor') }} ({{ __('Bulan') }})</label>
                        <select name="tenor" id="tenor" class="form-select @error('tenor') is-invalid @enderror" required>
                            <option value="">- {{ __('Pilih') }} -</option>
                            <option value="1" {{ old('tenor', $pinjaman->tenor) == 1 ? 'selected' : '' }}>1 {{ __('Bulan') }}</option>
                            <option value="3" {{ old('tenor', $pinjaman->tenor) == 3 ? 'selected' : '' }}>3 {{ __('Bulan') }}</option>
                            <option value="6" {{ old('tenor', $pinjaman->tenor) == 6 ? 'selected' : '' }}>6 {{ __('Bulan') }}</option>
                            <option value="12" {{ old('tenor', $pinjaman->tenor) == 12 ? 'selected' : '' }}>12 {{ __('Bulan') }}</option>
                            <option value="24" {{ old('tenor', $pinjaman->tenor) == 24 ? 'selected' : '' }}>24 {{ __('Bulan') }}</option>
                            <option value="36" {{ old('tenor', $pinjaman->tenor) == 36 ? 'selected' : '' }}>36 {{ __('Bulan') }}</option>
                        </select>
                        @error('tenor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Jatuh Tempo') }}</label>
                        <input type="date" name="jatuh_tempo" id="jatuh_tempo" value="{{ old('jatuh_tempo', $pinjaman->jatuh_tempo?->format('Y-m-d')) }}" class="form-control" readonly>
                        <small class="text-muted">{{ __('Otomatis dihitung dari tanggal pengajuan + tenor') }}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Status') }}</label>
                        @if($pinjaman->status === 'diajukan')
                            <input type="text" class="form-control" value="{{ __('Diajukan (menunggu persetujuan)') }}" readonly>
                        @else
                            <select name="status" class="form-select">
                                <option value="aktif" {{ old('status', $pinjaman->status) === 'aktif' ? 'selected' : '' }}>{{ __('Aktif') }}</option>
                                <option value="lunas" {{ old('status', $pinjaman->status) === 'lunas' ? 'selected' : '' }}>{{ __('Lunas') }}</option>
                                <option value="macet" {{ old('status', $pinjaman->status) === 'macet' ? 'selected' : '' }}>{{ __('Macet') }}</option>
                            </select>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Periode') }}</label>
                        <select name="periode_id" class="form-select">
                            <option value="">- {{ __('Auto (active period)') }} -</option>
                            @foreach(\App\Models\Periode::latest()->get() as $p)
                                <option value="{{ $p->id }}" {{ old('periode_id', $pinjaman->periode_id) == $p->id ? 'selected' : '' }}>{{ $p->tahun }} {{ $p->nama ? '- '.$p->nama : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Keterangan') }}</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $pinjaman->keterangan) }}</textarea>
                        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    <a href="{{ route('admin.pinjaman.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    const bungaMap = @json($bungaPinjaman->mapWithKeys(fn($bp) => [
        "{$bp->nama} ({$bp->bunga}%)" => ['id' => $bp->id, 'bunga' => $bp->bunga, 'tanggal_berlaku' => $bp->tanggal_berlaku?->format('Y-m-d')]
    ]));

    function autoFillBunga() {
        const tgl = document.getElementById('tanggal_pengajuan')?.value;
        if (!tgl || !document.getElementById('bunga_input')?.value) return;
        let bestKey = null;
        let bestDate = null;
        for (const [key, val] of Object.entries(bungaMap)) {
            if (!val.tanggal_berlaku || val.tanggal_berlaku > tgl) continue;
            if (!bestDate || val.tanggal_berlaku > bestDate) {
                bestDate = val.tanggal_berlaku;
                bestKey = key;
            }
        }
        if (bestKey) {
            document.getElementById('bunga_input').value = bestKey;
            document.getElementById('bunga_pinjaman_id').value = bungaMap[bestKey].id;
            document.getElementById('bunga_persen').value = bungaMap[bestKey].bunga;
        }
    }

    function formatRupiah(el) {
        const val = el.value.replace(/\./g, '').replace(/\D/g, '');
        el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.querySelectorAll('.rupiah-input').forEach(function(el) {
        el.addEventListener('input', function() { formatRupiah(this); });
    });

    document.getElementById('pinjamanForm').addEventListener('submit', function() {
        document.querySelectorAll('.rupiah-input').forEach(function(el) {
            el.value = el.value.replace(/\./g, '');
        });
        syncBunga();
    });

    function hitungJatuhTempo() {
        const tgl = document.getElementById('tanggal_pengajuan').value;
        const tenor = parseInt(document.getElementById('tenor').value);
        if (tgl && tenor) {
            const parts = tgl.split('-');
            const date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            date.setMonth(date.getMonth() + tenor);
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            document.getElementById('jatuh_tempo').value = y + '-' + m + '-' + d;
        }
    }

    var tglInput = document.getElementById('tanggal_pengajuan');
    var tenorSelect = document.getElementById('tenor');
    if (tglInput) {
        tglInput.addEventListener('change', function() { hitungJatuhTempo(); autoFillBunga(); });
        tglInput.addEventListener('input', function() { hitungJatuhTempo(); autoFillBunga(); });
    }
    if (tenorSelect) {
        tenorSelect.addEventListener('change', hitungJatuhTempo);
        tenorSelect.addEventListener('input', hitungJatuhTempo);
    }

    hitungJatuhTempo();

    function syncBunga() {
        var el = document.getElementById('bunga_input');
        if (!el) return;
        const val = el.value.trim();
        const match = bungaMap[val];
        if (match) {
            document.getElementById('bunga_pinjaman_id').value = match.id;
            document.getElementById('bunga_persen').value = match.bunga;
        } else {
            document.getElementById('bunga_pinjaman_id').value = '';
            const num = parseFloat(val);
            document.getElementById('bunga_persen').value = isNaN(num) ? '' : num;
        }
    }

    var bungaInput = document.getElementById('bunga_input');
    if (bungaInput) {
        bungaInput.addEventListener('input', syncBunga);
        bungaInput.addEventListener('change', syncBunga);
    }

    syncBunga();
</script>
@endpush
