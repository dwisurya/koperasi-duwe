<x-app-layout>
    <x-slot name="header">Laporan Rugi Laba</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Pendapatan & Beban</span>
            @if($periode) <span class="badge bg-info">Periode: {{ $periode->tahun }}</span> @endif
        </div>
        <div class="card-body">
            <div class="row g-2 mb-4">
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Pendapatan</small>
                        <strong class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Beban</small>
                        <strong class="text-danger">Rp {{ number_format($totalBeban, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded {{ $labaBersih >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                        <small class="text-muted d-block">Laba / Rugi Bersih</small>
                        <strong class="{{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}</strong>
                        <small>{{ $labaBersih >= 0 ? '(Laba)' : '(Rugi)' }}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold text-success">Pendapatan</h6>
                    <table class="table table-sm table-borderless">
                        @foreach($pendapatan as $p)
                            <tr>
                                <td>{{ $p['nama'] }}</td>
                                <td class="text-end">Rp {{ number_format($p['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold border-top">
                            <td>Total Pendapatan</td>
                            <td class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-danger">Beban</h6>
                    <table class="table table-sm table-borderless">
                        @foreach($beban as $b)
                            <tr>
                                <td>{{ $b['nama'] }}</td>
                                <td class="text-end">Rp {{ number_format($b['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold border-top">
                            <td>Total Beban</td>
                            <td class="text-end text-danger">Rp {{ number_format($totalBeban, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
