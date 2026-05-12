<x-app-layout>
    <x-slot name="header">Periode</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Periode</span>
            @can('periode-create')
                <a href="{{ route('admin.periodes.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Tahun</th>
                        <th class="d-none d-md-table-cell">Nama</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periodes as $periode)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $periode->tahun }}</td>
                            <td class="d-none d-md-table-cell">{{ $periode->nama ?? '-' }}</td>
                            <td>
                                @if($periode->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('periode-edit')
                                    <a href="{{ route('admin.periodes.edit', $periode) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @if(!$periode->is_active)
                                    <form action="{{ route('admin.periodes.activate', $periode) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success me-1" title="Activate"><i class="bi bi-check-circle"></i></button>
                                    </form>
                                @endif
                                @can('periode-delete')
                                    @if(!$periode->is_active)
                                        <form action="{{ route('admin.periodes.destroy', $periode) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete periode?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
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
