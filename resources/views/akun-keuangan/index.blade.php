<x-app-layout>
    <x-slot name="header">Akun Keuangan</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Akun Keuangan</span>
            @can('akun-keuangan-create')
                <a href="{{ route('admin.akun-keuangan.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
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
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akunKeuangan as $ak)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $ak->kode }}</td>
                            <td>{{ $ak->nama }}</td>
                            <td>
                                @if($ak->kategori_aktiva_id)
                                    <span class="badge bg-info">Aktiva</span>
                                @else
                                    <span class="badge bg-warning text-dark">Passiva</span>
                                @endif
                            </td>
                            <td>{{ $ak->kategori_nama }}</td>
                            <td class="d-none d-md-table-cell">{{ $ak->keterangan ?? '-' }}</td>
                            <td>
                                @if($ak->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @can('akun-keuangan-edit')
                                    <a href="{{ route('admin.akun-keuangan.edit', $ak) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('akun-keuangan-delete')
                                    <form action="{{ route('admin.akun-keuangan.destroy', $ak) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete akun keuangan?')">
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
