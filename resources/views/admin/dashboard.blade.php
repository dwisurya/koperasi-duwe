<x-app-layout>
    <x-slot name="header">{{ __('Dashboard') }}</x-slot>

    @if($periodeAktif)
        <div class="alert alert-info py-2 mb-3">
            <i class="bi bi-calendar3"></i> {{ __('Periode aktif') }}: <strong>{{ $periodeAktif->tahun }}</strong>
            @if($periodeAktif->nama) - {{ $periodeAktif->nama }} @endif
        </div>
    @endif

    <div class="row g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon bg-purple"><i class="bi bi-people"></i></div>
                    <div class="stat-content">
                        <h4>{{ $stats['anggota'] }}</h4>
                        <p>{{ __('Jumlah Anggota') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon bg-green"><i class="bi bi-piggy-bank"></i></div>
                    <div class="stat-content">
                        <h4>Rp {{ number_format($simpanan['pokok'], 0, ',', '.') }}</h4>
                        <p>{{ __('Simpanan Pokok') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon bg-yellow"><i class="bi bi-piggy-bank"></i></div>
                    <div class="stat-content">
                        <h4>Rp {{ number_format($simpanan['wajib'], 0, ',', '.') }}</h4>
                        <p>{{ __('Simpanan Wajib') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon bg-blue"><i class="bi bi-piggy-bank"></i></div>
                    <div class="stat-content">
                        <h4>Rp {{ number_format($simpanan['penyertaan'], 0, ',', '.') }}</h4>
                        <p>{{ __('Tabungan Penyertaan') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#ea54551a;color:#ea5455"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="stat-content">
                        <h4>Rp {{ number_format($simpanan['bagi_hasil'], 0, ',', '.') }}</h4>
                        <p>{{ __('Simpanan Bagi Hasil') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><span>{{ __('Simpanan vs Pinjaman') }}</span></div>
                <div class="card-body">
                    <canvas id="chartSimpanan" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartSimpanan'), {
        type: 'bar',
        data: {
            labels: ['Pokok', 'Wajib', 'Penyertaan', 'Bagi Hasil', 'Pinjaman'],
            datasets: [{
                label: 'Total (Rp)',
                data: [
                    {{ $simpanan['pokok'] }},
                    {{ $simpanan['wajib'] }},
                    {{ $simpanan['penyertaan'] }},
                    {{ $simpanan['bagi_hasil'] }},
                    {{ $totalPinjaman }}
                ],
                backgroundColor: ['#7367f0', '#28c76f', '#ff9f43', '#00cfe8', '#ea5455'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString('id-ID'),
                    }
                }
            }
        }
    });
</script>
@endpush
