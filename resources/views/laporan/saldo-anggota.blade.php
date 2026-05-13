<x-app-layout>
    <x-slot name="header">Laporan Saldo Anggota</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Saldo Simpanan & Pinjaman per Anggota</span>
            @if($periode) <span class="badge bg-info">Periode: {{ $periode->tahun }}</span> @endif
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Simpanan</small>
                        <strong>Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Pinjaman Aktif</small>
                        <strong class="text-danger">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Pinjaman (Semua)</small>
                        <strong>Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Anggota</th>
                        <th>Simpanan Pokok</th>
                        <th>Simpanan Wajib</th>
                        <th>Tabungan Penyertaan</th>
                        <th>Total Simpanan</th>
                        <th>Pinjaman Aktif</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d['kode'] }}</td>
                            <td>{{ $d['nama'] }}</td>
                            <td>Rp {{ number_format($d['simpanan_pokok'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d['simpanan_wajib'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($d['simpanan_penyertaan'], 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($d['total_simpanan'], 0, ',', '.') }}</strong></td>
                            <td>Rp {{ number_format($d['pinjaman_aktif'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
