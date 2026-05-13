<x-app-layout>
    <x-slot name="header">Persentase SHU</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Persentase SHU</span>
            @can('persentase-shu-create')
                <a href="{{ route('admin.persentase-shu.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
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
                        <th>Nama Dana</th>
                        <th>Persentase</th>
                        <th>Keterangan</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($persentaseShu as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->dana }}</td>
                            <td>{{ $item->persentase }}%</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->urutan }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @can('persentase-shu-edit')
                                    <a href="{{ route('admin.persentase-shu.edit', $item) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('persentase-shu-delete')
                                    <form action="{{ route('admin.persentase-shu.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
