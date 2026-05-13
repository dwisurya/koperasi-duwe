<x-app-layout>
    <x-slot name="header">{{ $anggota->kode }} - {{ $anggota->nama }}</x-slot>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><span>Data Anggota</span></div>
                <div class="card-body">
                    <div class="table-responsive"><table class="table table-sm">
                        <tr><td class="fw-semibold" width="40%">Kode</td><td>{{ $anggota->kode }}</td></tr>
                        <tr><td class="fw-semibold">Nama</td><td>{{ $anggota->nama }}</td></tr>
                        <tr><td class="fw-semibold">NIK</td><td>{{ $anggota->nik ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">No. KK</td><td>{{ $anggota->no_kk ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Tempat Lahir</td><td>{{ $anggota->tempat_lahir ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Tgl. Lahir</td><td>{{ $anggota->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Jenis Kelamin</td><td>{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : ($anggota->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</td></tr>
                        <tr><td class="fw-semibold">Email</td><td>{{ $anggota->email }}</td></tr>
                        <tr><td class="fw-semibold">No. HP</td><td>{{ $anggota->no_hp ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Tgl. Daftar</td><td>{{ $anggota->tanggal_daftar?->format('d/m/Y') ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Ayah</td><td>{{ $anggota->ayah ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Ibu</td><td>{{ $anggota->ibu ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Alamat</td><td>{{ $anggota->alamat ?? '-' }}</td></tr>
                        <tr><td class="fw-semibold">Status</td><td>
                            @if($anggota->isAktif())
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Keluar ({{ $anggota->tanggal_keluar->format('d/m/Y') }})</span>
                            @endif
                        </td></tr>
                    </table></div>
                    <div class="mt-3 d-flex gap-2">
                        @can('anggota-edit')
                            <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                            @if($anggota->isAktif())
                                <form action="{{ route('admin.anggota.keluarkan', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai anggota ini keluar? Simpanan Pokok dan Wajib dapat ditarik setelahnya.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i> Keluarkan</button>
                                </form>
                            @else
                                <form action="{{ route('admin.anggota.masukkan-kembali', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan kembali anggota ini?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success"><i class="bi bi-person-check"></i> Aktifkan Kembali</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><span>Saldo Simpanan</span></div>
                <div class="card-body">
                    @php
                        $perJenis = $anggota->simpanan->groupBy('jenis');
                    @endphp
                    <div class="table-responsive"><table class="table table-sm">
                        @foreach(['pokok', 'wajib', 'penyertaan', 'bagi_hasil'] as $jenis)
                            @php $sub = $perJenis->get($jenis, collect()); @endphp
                            <tr>
                                <td class="fw-semibold">{{ \App\Models\Simpanan::jenisLabel($jenis) }}</td>
                                <td class="text-end">Rp {{ number_format($sub->sum('nominal'), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-active"><td class="fw-bold">Total Simpanan</td><td class="fw-bold text-end">Rp {{ number_format($totalSimpanan, 2, ',', '.') }}</td></tr>
                    </table></div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><span>Simpanan</span></div>
                <div class="card-body">
                    @if($anggota->simpanan->count() > 0)
                        <div class="table-responsive"><table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Tgl. Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anggota->simpanan as $s)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $s->jenis_label }}</td>
                                        <td>Rp {{ number_format($s->nominal, 2, ',', '.') }}</td>
                                        <td>{{ $s->keterangan ?? '-' }}</td>
                                        <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    @else
                        <p class="text-muted mb-0">Belum ada simpanan.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><span>Pinjaman</span></div>
                <div class="card-body">
                    @if($anggota->pinjaman->count() > 0)
                        <div class="table-responsive"><table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Nominal</th>
                                    <th>Bunga</th>
                                    <th>Tenor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anggota->pinjaman as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->tanggal_pengajuan?->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($p->nominal, 2, ',', '.') }}</td>
                                        <td>{{ $p->bunga_persen }}%</td>
                                        <td>{{ $p->tenor }} bln</td>
                                        <td><span class="badge bg-{{ $p->status_color }}">{{ __($p->status_label) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    @else
                        <p class="text-muted mb-0">Belum ada pinjaman.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
