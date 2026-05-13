<x-app-layout>
    <x-slot name="header">Buku Tabungan: {{ $anggota->nama }}</x-slot>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">{{ $anggota->nama }}</h6>
                    <small class="text-muted">{{ $anggota->kode ?? '-' }}</small>

                    <hr>

                    @foreach(['pokok' => 'Simpanan Pokok', 'wajib' => 'Simpanan Wajib', 'penyertaan' => 'Tabungan Penyertaan', 'bagi_hasil' => 'Bagi Hasil'] as $key => $label)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $label }}</span>
                            <strong>Rp {{ number_format($totalPerJenis->get($key, 0), 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <strong>Rp {{ number_format($totalPerJenis->sum(), 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.buku-tabungan.index') }}" class="btn btn-outline-secondary mt-2 w-100">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Riwayat Simpanan</div>
                <div class="card-body">
                    <div class="table-responsive"><table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($simpanan as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $s->jenis_label }}</td>
                                    <td>Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $s->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada transaksi simpanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
