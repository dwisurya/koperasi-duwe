<x-app-layout>
    <x-slot name="header">{{ $judul }}</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Transaksi {{ $judul }}</span>
            @can('kas-create')
                <a href="{{ route('admin.kas.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Transaksi</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Masuk</small>
                        <strong class="text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block">Total Keluar</small>
                        <strong class="text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2 border rounded bg-primary bg-opacity-10">
                        <small class="text-muted d-block">Saldo</small>
                        <strong>Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Nominal</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th class="d-none d-md-table-cell">Periode</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if($k->jenis === 'masuk')
                                    <span class="badge bg-success">Masuk</span>
                                @else
                                    <span class="badge bg-danger">Keluar</span>
                                @endif
                            </td>
                            <td>{{ $k->kategori }}</td>
                            <td>Rp {{ number_format($k->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $k->keterangan ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $k->periode?->tahun ?? '-' }}</td>
                            <td>
                                @can('kas-edit')
                                    <a href="{{ route('admin.kas.edit', $k) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('kas-delete')
                                    <form action="{{ route('admin.kas.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi?')">
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
