<x-app-layout>
    <x-slot name="header">Neraca</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Neraca {{ $periode ? 'Periode ' . $periode->tahun : '' }}</span>
            <span class="text-muted small">{{ now()->format('d/m/Y') }}</span>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border rounded p-3 bg-light">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="bi bi-box me-1"></i> AKTIVA
                        </h5>
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="ps-0">Akun</th>
                                    <th class="text-end pe-0">Saldo (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aktivaGroups as $groupName => $group)
                                    <tr class="table-primary">
                                        <td colspan="2" class="ps-0 fw-semibold small text-muted py-1">{{ $groupName }}</td>
                                    </tr>
                                    @if(!empty($group['Kas']))
                                        <tr class="fw-semibold">
                                            <td class="ps-2">Kas</td>
                                            <td class="text-end pe-0">{{ number_format($group['Kas']['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                        @foreach($group['Kas']['children'] as $child)
                                            <tr>
                                                <td class="ps-4 text-muted">{{ $child['nama'] }}</td>
                                                <td class="text-end pe-0 text-muted">{{ number_format($child['saldo'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @foreach($group['items'] as $item)
                                        <tr>
                                            <td class="ps-2">{{ $item['nama'] }}</td>
                                            <td class="text-end pe-0">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="border-top fw-bold">
                                    <td class="ps-0">Total Aktiva</td>
                                    <td class="text-end pe-0">{{ number_format($totalAktiva, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded p-3 bg-light">
                        <h5 class="fw-bold text-success mb-3">
                            <i class="bi bi-wallet me-1"></i> PASSIVA
                        </h5>
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="ps-0">Akun</th>
                                    <th class="text-end pe-0">Saldo (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($passivaGroups as $groupName => $items)
                                    <tr class="table-active">
                                        <td colspan="2" class="ps-0 fw-semibold small text-muted py-1">{{ $groupName }}</td>
                                    </tr>
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="ps-3">{{ $item['nama'] }}</td>
                                            <td class="text-end pe-0">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="border-top fw-bold">
                                    <td class="ps-0">Total Passiva</td>
                                    <td class="text-end pe-0">{{ number_format($totalPassiva, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="border rounded p-3 {{ $selisih == 0 ? 'bg-success bg-opacity-10 border-success' : 'bg-warning bg-opacity-10 border-warning' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">
                                @if($selisih == 0)
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> Balance Check
                                @elseif($selisih > 0)
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Surplus (SHU)
                                @else
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Defisit
                                @endif
                            </span>
                            <span class="fs-5 fw-bold {{ $selisih == 0 ? 'text-success' : ($selisih > 0 ? 'text-warning' : 'text-danger') }}">
                                Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="small text-muted mt-1">
                            @if($selisih == 0)
                                Total Aktiva = Total Passiva (Balance)
                            @elseif($selisih > 0)
                                SHU / Laba Berjalan (Total Aktiva - Total Passiva)
                            @else
                                Defisit / Rugi Berjalan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
