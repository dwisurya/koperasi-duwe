<x-app-layout>
    <x-slot name="header">Angsuran</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Angsuran</span>
            @can('angsuran-create')
                <a href="{{ route('admin.angsuran.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Create</a>
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
                        <th>Anggota</th>
                        <th>Pinjaman</th>
                        <th>Angsuran Ke</th>
                        <th>Tgl Bayar</th>
                        <th>Nominal</th>
                        <th>Denda</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($angsurans as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $a->anggota?->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($a->pinjaman?->nominal ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $a->angsuran_ke }}</td>
                            <td>{{ $a->tanggal_bayar?->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($a->nominal, 0, ',', '.') }}</td>
                            <td>@if($a->denda > 0) Rp {{ number_format($a->denda, 0, ',', '.') }} @else - @endif</td>
                            <td class="d-none d-md-table-cell">{{ $a->keterangan ?? '-' }}</td>
                            <td>
                                @can('angsuran-edit')
                                    <a href="{{ route('admin.angsuran.edit', $a) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('angsuran-delete')
                                    <form action="{{ route('admin.angsuran.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete angsuran?')">
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
