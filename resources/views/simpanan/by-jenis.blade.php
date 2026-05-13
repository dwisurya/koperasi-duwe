<x-app-layout>
    <x-slot name="header">{{ $judul }}</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar {{ $judul }}</span>
            @can('simpanan-create')
                <a href="{{ route('admin.simpanan.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="mb-3">
                <div class="p-2 border rounded bg-primary bg-opacity-10 d-inline-block">
                    <small class="text-muted d-block">Total {{ $judul }}</small>
                    <strong>Rp {{ number_format($totalNominal, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Anggota</th>
                        <th>Nominal</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th class="d-none d-md-table-cell">Periode</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($simpanan as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $s->anggota->nama }}</td>
                            <td>Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $s->keterangan ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $s->periode?->tahun ?? '-' }}</td>
                            <td>
                                @if($s->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @can('simpanan-edit')
                                    @if(in_array($s->jenis, ['pokok', 'wajib']) && !$s->anggota->isAktif() && $s->is_active)
                                        <form action="{{ route('admin.simpanan.tarik', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Tarik simpanan ini? Kas keluar akan dibuat.')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning me-1" title="Tarik Simpanan"><i class="bi bi-cash"></i></button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.simpanan.edit', $s) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('simpanan-delete')
                                    @if($s->jenis !== 'pokok')
                                        <form action="{{ route('admin.simpanan.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus simpanan?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @else
                                        <span class="text-muted small" title="Simpanan Pokok tidak bisa dihapus"><i class="bi bi-lock"></i></span>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
