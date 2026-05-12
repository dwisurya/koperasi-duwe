<x-app-layout>
    <x-slot name="header">Anggota</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Anggota</span>
            @can('anggota-create')
                <a href="{{ route('admin.anggota.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">NIK</th>
                        <th class="d-none d-lg-table-cell">Tgl. Lahir</th>
                        <th class="d-none d-lg-table-cell">Jenis Kelamin</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">No. HP</th>
                        <th class="d-none d-lg-table-cell">Tgl. Daftar</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                    <tbody>
                        @foreach($anggotas as $anggota)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $anggota->kode }}</td>
                            <td>{{ $anggota->nama }}</td>
                            <td class="d-none d-md-table-cell">{{ $anggota->nik ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $anggota->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : ($anggota->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</td>
                            <td class="d-none d-md-table-cell">{{ $anggota->email }}</td>
                            <td class="d-none d-lg-table-cell">{{ $anggota->no_hp ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $anggota->tanggal_daftar?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.anggota.show', $anggota) }}" class="btn btn-sm btn-outline-info me-1" title="Detail"><i class="bi bi-eye"></i></a>
                                @can('anggota-edit')
                                    <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('anggota-delete')
                                    <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete anggota?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div></div>
    </div>
</x-app-layout>
