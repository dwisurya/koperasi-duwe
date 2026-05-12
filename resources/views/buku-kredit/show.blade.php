<x-app-layout>
    <x-slot name="header">Buku Kredit - {{ $pinjaman->anggota?->nama }}</x-slot>

    <div class="row g-3">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><span>Informasi Pinjaman</span></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><td class="fw-semibold">Anggota</td><td>{{ $pinjaman->anggota?->nama }}</td></tr>
                        <tr><td class="fw-semibold">Tgl Pengajuan</td><td>{{ $pinjaman->tanggal_pengajuan?->format('d/m/Y') }}</td></tr>
                        <tr><td class="fw-semibold">Nominal Pinjaman</td><td>Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</td></tr>
                        <tr><td class="fw-semibold">Bunga</td><td>{{ $pinjaman->bunga_persen }}%</td></tr>
                        <tr><td class="fw-semibold">Tenor</td><td>{{ $pinjaman->tenor }} bulan</td></tr>
                        <tr><td class="fw-semibold">Status</td><td><span class="badge bg-{{ $pinjaman->status_color }}">{{ __($pinjaman->status_label) }}</span></td></tr>
                        <tr><td class="fw-semibold">Periode</td><td>{{ $pinjaman->periode?->tahun ?? '-' }}</td></tr>
                    </table>
                    <hr>
                    <table class="table table-sm">
                        <tr><td class="fw-semibold">Total Angsuran</td><td class="text-success fw-bold">Rp {{ number_format($totalAngsuran, 0, ',', '.') }}</td></tr>
                        <tr class="table-active"><td class="fw-bold">Sisa Pinjaman</td><td class="fw-bold">Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><span>Riwayat Angsuran</span></div>
                <div class="card-body">
                    @if($pinjaman->angsurans->count() > 0)
                        <div class="table-responsive"><table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ke</th>
                                    <th>Tgl Bayar</th>
                                    <th>Nominal</th>
                                    <th>Denda</th>
                                    <th class="d-none d-md-table-cell">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pinjaman->angsurans as $a)
                                    <tr>
                                        <td>{{ $a->angsuran_ke }}</td>
                                        <td>{{ $a->tanggal_bayar?->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($a->nominal, 0, ',', '.') }}</td>
                                        <td>@if($a->denda > 0) Rp {{ number_format($a->denda, 0, ',', '.') }} @else - @endif</td>
                                        <td class="d-none d-md-table-cell">{{ $a->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    @else
                        <p class="text-muted mb-0">Belum ada angsuran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
