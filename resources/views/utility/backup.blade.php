<x-app-layout>
    <x-slot name="header">Backup Database</x-slot>

    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <p class="text-muted mb-3">Buat salinan database SQLite untuk pengamanan data.</p>
            <form action="{{ route('admin.utility.backup.do') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-cloud-arrow-down"></i> Backup Sekarang</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Riwayat Backup</div>
        <div class="card-body">
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Tanggal</th>
                        <th class="no-sort no-search" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b['name'] }}</td>
                            <td>{{ round($b['size'] / 1024, 1) }} KB</td>
                            <td>{{ date('d/m/Y H:i', $b['date']) }}</td>
                            <td>
                                <a href="{{ route('admin.utility.backup.download', $b['name']) }}" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-download"></i></a>
                                <form action="{{ route('admin.utility.backup.delete', $b['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus backup ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada backup.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
