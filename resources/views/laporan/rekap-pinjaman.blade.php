<x-app-layout>
    <x-slot name="header">Rekap Pinjaman</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Rekapitulasi Pinjaman per Status</span>
            @if($periode) <span class="badge bg-info">Periode: {{ $periode->tahun }}</span> @endif
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Transaksi</small>
                        <strong>{{ $grandCount }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Grand Total</small>
                        <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Total Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-{{ $r['status_color'] }}">{{ $r['status'] }}</span></td>
                            <td>{{ $r['total'] }}</td>
                            <td>Rp {{ number_format($r['total_nominal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
