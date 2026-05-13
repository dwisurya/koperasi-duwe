<x-app-layout>
    <x-slot name="header">Titip Dana</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Transaksi Titip Dana</span>
            @can('titip-dana-create')
                <a href="{{ route('admin.titip-dana.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Titipan Masuk</small>
                        <strong class="text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Titipan Keluar</small>
                        <strong class="text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-primary bg-opacity-10">
                        <small class="text-muted d-block">Saldo Titipan</small>
                        <strong>Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Penitip</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th class="d-none d-md-table-cell">Periode</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($titipDana as $td)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $td->nama_penitip }}</td>
                            <td>{{ $td->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if($td->jenis === 'masuk')
                                    <span class="badge bg-success">Titipan Masuk</span>
                                @else
                                    <span class="badge bg-danger">Titipan Keluar</span>
                                @endif
                            </td>
                            <td>
                                @if($td->status === 'sudah_diketahui')
                                    <span class="badge bg-info">Sudah Diketahui</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diketahui</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($td->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $td->keterangan ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $td->periode?->tahun ?? '-' }}</td>
                            <td>
                                @can('titip-dana-edit')
                                    <a href="{{ route('admin.titip-dana.edit', $td) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('titip-dana-delete')
                                    <form action="{{ route('admin.titip-dana.destroy', $td) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi titip dana?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
