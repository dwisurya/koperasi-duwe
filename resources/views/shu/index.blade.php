<x-app-layout>
    <x-slot name="header">Perhitungan SHU</x-slot>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Ringkasan Keuangan {{ $periode ? 'Periode '.$periode->tahun : '' }}</span>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td>Total Aktiva</td>
                            <td class="text-end">Rp {{ number_format($neraca['total_aktiva'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Passiva (sebelum SHU)</td>
                            <td class="text-end">Rp {{ number_format($neraca['total_passiva_sebelum_shu'], 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top fw-bold">
                            <td>SHU / Surplus</td>
                            <td class="text-end {{ $neraca['selisih'] > 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($neraca['selisih'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    @if($neraca['selisih'] > 0)
                        <div class="mt-3">
                            <form action="{{ route('admin.shu.calculate') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary" {{ $shuList->where('is_distributed', false)->count() > 0 ? 'disabled' : '' }}>
                                    <i class="bi bi-calculator me-1"></i> Hitung SHU
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Tidak ada surplus untuk didistribusikan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Skema Distribusi SHU</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="border-bottom">
                                <th>Dana</th>
                                <th class="text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schema as $item)
                                <tr>
                                    <td>{{ $item['dana'] }}</td>
                                    <td class="text-end">{{ $item['persentase'] }}%</td>
                                </tr>
                            @endforeach
                            <tr class="border-top fw-bold">
                                <td>Total</td>
                                <td class="text-end">{{ collect($schema)->sum('persentase') }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Riwayat Perhitungan SHU</span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    @endif

                    @if($shuList->isEmpty())
                        <p class="text-muted mb-0">Belum ada perhitungan SHU untuk periode ini.</p>
                    @else
                        @foreach($shuList as $shu)
                            <div class="border rounded p-3 mb-3 {{ $shu->is_distributed ? 'bg-success bg-opacity-10 border-success' : 'bg-warning bg-opacity-10 border-warning' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>SHU: Rp {{ number_format($shu->total_shu, 0, ',', '.') }}</strong>
                                        <span class="badge {{ $shu->is_distributed ? 'bg-success' : 'bg-warning' }} ms-2">
                                            {{ $shu->is_distributed ? 'Sudah Didistribusikan' : 'Menunggu Distribusi' }}
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $shu->created_at->format('d/m/Y H:i') }}</small>
                                </div>

                                @if($shu->details->isNotEmpty())
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="border-bottom">
                                                <th>Dana</th>
                                                <th class="text-end">Persentase</th>
                                                <th class="text-end">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($shu->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->dana }}</td>
                                                    <td class="text-end">{{ $detail->persentase }}%</td>
                                                    <td class="text-end">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="border-top fw-bold">
                                                <td colspan="2">Total Distribusi</td>
                                                <td class="text-end">Rp {{ number_format($shu->details->sum('nominal'), 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if(!$shu->is_distributed)
                                    <div class="mt-2">
                                        <form action="{{ route('admin.shu.distribute') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle me-1"></i> Distribusikan SHU
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
