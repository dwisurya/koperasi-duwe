<x-app-layout>
    <x-slot name="header">Approval Pinjaman</x-slot>

    <div class="card">
        <div class="card-header">
            <span>Daftar Pengajuan Menunggu Persetujuan</span>
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
                        <th>Tgl Pengajuan</th>
                        <th>Nominal</th>
                        <th>Bunga</th>
                        <th>Tenor</th>
                        <th class="no-sort no-search" width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinjaman as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $p->anggota?->nama ?? '-' }}</td>
                            <td>{{ $p->tanggal_pengajuan?->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                            <td>{{ $p->bunga_persen }}%</td>
                            <td>{{ $p->tenor }} Bulan</td>
                            <td>
                                <form action="{{ route('admin.pinjaman.approve', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success me-1" onclick="return confirm('Setujui pengajuan ini?')"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <button class="btn btn-sm btn-dark me-1" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $p->id }}"><i class="bi bi-x-lg"></i> Tolak</button>
                                <a href="{{ route('admin.pinjaman.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>

                                <div class="modal fade" id="rejectModal-{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form class="modal-content" action="{{ route('admin.pinjaman.reject', $p) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Pengajuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Nama Anggota: <strong>{{ $p->anggota?->nama ?? '-' }}</strong></p>
                                                <p>Nominal: <strong>Rp {{ number_format($p->nominal, 0, ',', '.') }}</strong></p>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Penolakan</label>
                                                    <textarea name="alasan" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-dark">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
