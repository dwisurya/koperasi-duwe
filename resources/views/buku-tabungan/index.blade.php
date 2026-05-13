<x-app-layout>
    <x-slot name="header">Buku Tabungan</x-slot>

    <div class="card">
        <div class="card-header">
            <span>Pilih Anggota</span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="row g-3">
                @forelse($anggotas as $anggota)
                    <div class="col-md-4">
                        <a href="{{ route('admin.buku-tabungan.show', $anggota) }}" class="text-decoration-none">
                            <div class="card card-hover border">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">{{ $anggota->nama }}</h6>
                                    <small class="text-muted">{{ $anggota->kode ?? '-' }}</small>
                                    <div class="mt-2">
                                        <strong>Rp {{ number_format($anggota->simpanan->sum('nominal'), 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted mb-0">Belum ada anggota dengan simpanan aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
