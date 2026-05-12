<x-app-layout>
    <x-slot name="header">{{ __('Pengajuan') }}</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Daftar Pengajuan Pinjaman') }}</span>
            <div>
                @can('pinjaman-create')
                    <a href="{{ route('admin.pinjaman.simulasi') }}" class="btn btn-sm btn-outline-info me-1"><i class="bi bi-calculator"></i> {{ __('Simulasi Cicilan') }}</a>
                    <a href="{{ route('admin.pinjaman.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> {{ __('Pengajuan Baru') }}</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>{{ __('Nama Anggota') }}</th>
                        <th class="d-none d-md-table-cell">{{ __('Tgl Pengajuan') }}</th>
                        <th>{{ __('Nominal') }}</th>
                        <th class="d-none d-md-table-cell">{{ __('Bunga') }}</th>
                        <th class="d-none d-md-table-cell">{{ __('Tenor') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="d-none d-lg-table-cell">{{ __('Disetujui Oleh') }}</th>
                        <th class="no-sort no-search" width="180">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $p->anggota?->nama ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $p->tanggal_pengajuan?->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $p->bunga_persen }}% ({{ $p->bungaPinjaman?->nama ?? '-' }})</td>
                            <td class="d-none d-md-table-cell">{{ $p->tenor }} {{ __('Bulan') }}</td>
                            <td><span class="badge bg-{{ $p->status_color }}">{{ __($p->status_label) }}</span></td>
                            <td class="d-none d-lg-table-cell">{{ $p->approver?->name ?? '-' }}</td>
                            <td>
                                @can('pinjaman-approve')
                                    @if($p->status === 'diajukan')
                                        <form action="{{ route('admin.pinjaman.approve', $p) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success me-1" title="{{ __('Setujui') }}"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-dark me-1" title="{{ __('Tolak') }}" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $p->id }}"><i class="bi bi-x-lg"></i></button>
                                    @endif
                                @endcan
                                @can('pinjaman-edit')
                                    <a href="{{ route('admin.pinjaman.edit', $p) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @if(in_array($p->status, ['disetujui', 'aktif', 'lunas']))
                                    <a href="{{ route('admin.pinjaman.cetak-kontrak', $p) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1" title="{{ __('Cetak Kontrak') }}"><i class="bi bi-file-text"></i></a>
                                @endif
                                @can('pinjaman-delete')
                                    <form action="{{ route('admin.pinjaman.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Hapus pengajuan ini?') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>

                        @can('pinjaman-approve')
                            @if($p->status === 'diajukan')
                                <div class="modal fade" id="rejectModal-{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form class="modal-content" action="{{ route('admin.pinjaman.reject', $p) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('Tolak') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('Nama Anggota') }}: <strong>{{ $p->anggota?->nama ?? '-' }}</strong></p>
                                                <p>{{ __('Nominal') }}: <strong>Rp {{ number_format($p->nominal, 0, ',', '.') }}</strong></p>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Alasan Penolakan') }}</label>
                                                    <textarea name="alasan" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                                                <button type="submit" class="btn btn-dark">{{ __('Tolak') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endcan
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
