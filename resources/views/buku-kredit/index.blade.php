<x-app-layout>
    <x-slot name="header">Buku Kredit</x-slot>

    <div class="card">
        <div class="card-header">
            <span>Buku Kredit / Credit Ledger</span>
        </div>
        <div class="card-body">
            @if($pinjaman->count() > 0)
                <div class="table-responsive"><table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Anggota</th>
                            <th>Tgl Pengajuan</th>
                            <th>Pinjaman</th>
                            <th class="d-none d-md-table-cell">Bunga</th>
                            <th class="d-none d-md-table-cell">Tenor</th>
                            <th>Status</th>
                            <th>Total Angsuran</th>
                            <th>Sisa Pinjaman</th>
                            <th class="no-sort no-search" width="80">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjaman as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-dark">{{ $p->anggota?->nama ?? '-' }}</td>
                                <td>{{ $p->tanggal_pengajuan?->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                                <td class="d-none d-md-table-cell">{{ $p->bunga_persen }}%</td>
                                <td class="d-none d-md-table-cell">{{ $p->tenor }} bln</td>
                                <td><span class="badge bg-{{ $p->status_color }}">{{ __($p->status_label) }}</span></td>
                                <td class="text-success fw-semibold">Rp {{ number_format($p->total_angsuran, 0, ',', '.') }}</td>
                                <td class="fw-bold">Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.buku-kredit.show', $p->id) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table></div>
            @else
                <p class="text-muted mb-0">Belum ada data kredit.</p>
            @endif
        </div>
    </div>
</x-app-layout>
