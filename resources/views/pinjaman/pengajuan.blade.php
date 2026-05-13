<x-app-layout>
    <x-slot name="header">Pengajuan Pinjaman</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Pengajuan Pinjaman</span>
            @can('pinjaman-create')
                <a href="{{ route('admin.pinjaman.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Pengajuan Baru</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Anggota</th>
                        <th class="d-none d-md-table-cell">Tgl Pengajuan</th>
                        <th>Nominal</th>
                        <th class="d-none d-md-table-cell">Bunga</th>
                        <th class="d-none d-md-table-cell">Tenor</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $p->anggota?->nama ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $p->tanggal_pengajuan?->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $p->bunga_persen }}%</td>
                            <td class="d-none d-md-table-cell">{{ $p->tenor }} Bulan</td>
                            <td><span class="badge bg-{{ $p->status_color }}">{{ $p->status_label }}</span></td>
                            <td>
                                @can('pinjaman-edit')
                                    <a href="{{ route('admin.pinjaman.edit', $p) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('pinjaman-delete')
                                    <form action="{{ route('admin.pinjaman.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengajuan ini?')">
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
