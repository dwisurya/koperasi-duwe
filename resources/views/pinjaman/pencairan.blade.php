<x-app-layout>
    <x-slot name="header">Pencairan Pinjaman</x-slot>

    <div class="card">
        <div class="card-header">
            <span>Daftar Pinjaman Siap Cair</span>
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
                        <th>Nama Anggota</th>
                        <th>Tgl Pengajuan</th>
                        <th>Nominal</th>
                        <th>Bunga</th>
                        <th>Tenor</th>
                        <th>Disetujui Oleh</th>
                        <th class="no-sort no-search" width="140">Aksi</th>
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
                            <td>{{ $p->approver?->name ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.pinjaman.cairkan', $p) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Cairkan pinjaman ini? Status akan berubah menjadi Aktif.')">
                                        <i class="bi bi-cash-coin me-1"></i> Cairkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
