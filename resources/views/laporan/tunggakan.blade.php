<x-app-layout>
    <x-slot name="header">Laporan Tunggakan</x-slot>

    <div class="card">
        <div class="card-header">
            <span>Pinjaman Aktif / Macet (Belum Lunas)</span>
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Tunggakan</small>
                        <strong class="text-danger">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Anggota</th>
                        <th>Kode</th>
                        <th>Nominal Pinjaman</th>
                        <th>Status</th>
                        <th>Tenor</th>
                        <th>Angsuran ke-</th>
                        <th>Total Angsuran</th>
                        <th>Sisa</th>
                        <th>Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjamanMacet as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p['anggota'] }}</td>
                            <td>{{ $p['kode'] }}</td>
                            <td>Rp {{ number_format($p['nominal'], 0, ',', '.') }}</td>
                            <td><span class="badge bg-{{ $p['status_color'] }}">{{ $p['status'] }}</span></td>
                            <td>{{ $p['tenor'] }} bln</td>
                            <td>{{ $p['angsuran_ke'] }}</td>
                            <td>Rp {{ number_format($p['total_angsuran'], 0, ',', '.') }}</td>
                            <td class="text-danger fw-bold">Rp {{ number_format($p['sisa'], 0, ',', '.') }}</td>
                            <td>{{ $p['jatuh_tempo'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
