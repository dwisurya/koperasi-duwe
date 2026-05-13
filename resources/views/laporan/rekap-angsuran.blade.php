<x-app-layout>
    <x-slot name="header">Rekap Angsuran</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Rekapitulasi Angsuran</span>
            @if($periode) <span class="badge bg-info">Periode: {{ $periode->tahun }}</span> @endif
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Transaksi</small>
                        <strong>{{ $totalTransaksi }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Nominal</small>
                        <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Denda</small>
                        <strong class="text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Bulan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Nominal</th>
                        <th>Total Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perBulan as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b['bulan'] }}</td>
                            <td>{{ $b['total'] }}</td>
                            <td>Rp {{ number_format($b['nominal'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($b['denda'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
