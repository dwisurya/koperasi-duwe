<x-app-layout>
    <x-slot name="header">Activity Log</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Riwayat Aktivitas Sistem</span>
            <form method="GET" class="d-flex gap-2">
                <select name="action" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
