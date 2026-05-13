<x-app-layout>
    <x-slot name="header">Laporan Arus Kas</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Arus Kas per Kategori</span>
            @if($periode) <span class="badge bg-info">Periode: {{ $periode->tahun }}</span> @endif
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Pemasukan</small>
                        <strong class="text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Pengeluaran</small>
                        <strong class="text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-primary bg-opacity-10">
                        <small class="text-muted d-block">Saldo Akhir</small>
                        <strong>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kategori</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($perKategori as $k)
                        @php $selisih = $k['masuk'] - $k['keluar']; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k['kategori'] }}</td>
                            <td>Rp {{ number_format($k['masuk'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($k['keluar'], 0, ',', '.') }}</td>
                            <td class="{{ $selisih >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                Rp {{ number_format($selisih, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
