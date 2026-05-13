<x-app-layout>
    <x-slot name="header">Kategori Passiva</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Kategori Passiva</span>
            @can('kategori-passiva-create')
                <a href="{{ route('admin.akun-passiva.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
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
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoriPassiva as $kp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $kp->nama }}</td>
                            <td class="d-none d-md-table-cell">{{ $kp->keterangan ?? '-' }}</td>
                            <td>
                                @if($kp->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('kategori-passiva-edit')
                                    <a href="{{ route('admin.akun-passiva.edit', $kp) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('kategori-passiva-delete')
                                    <form action="{{ route('admin.akun-passiva.destroy', $kp) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete kategori passiva?')">
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
