<x-app-layout>
    <x-slot name="header">Akun Modal</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Akun Modal</span>
            @can('akun-modal-create')
                <a href="{{ route('admin.akun-modal.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
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
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th class="no-sort no-search" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akunModal as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @can('akun-modal-edit')
                                    <a href="{{ route('admin.akun-modal.edit', $item) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('akun-modal-delete')
                                    <form action="{{ route('admin.akun-modal.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
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
