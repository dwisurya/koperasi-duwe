<x-app-layout>
    <x-slot name="header">Simpanan</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Simpanan</span>
            @can('simpanan-create')
                <a href="{{ route('admin.simpanan.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
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
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($simpanan as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $s->anggota->nama }}</td>
                            <td>{{ $s->jenis_label }}</td>
                            <td>Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $s->keterangan ?? '-' }}</td>
                            <td>
                                @if($s->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('simpanan-edit')
                                    <a href="{{ route('admin.simpanan.edit', $s) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('simpanan-delete')
                                    <form action="{{ route('admin.simpanan.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete simpanan?')">
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
