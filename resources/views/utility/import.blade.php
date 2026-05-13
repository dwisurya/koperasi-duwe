<x-app-layout>
    <x-slot name="header">Import Excel</x-slot>

    <div class="card">
        <div class="card-header">Import Data dari File Excel / CSV</div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <form action="{{ route('admin.utility.import.do') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tujuan Tabel</label>
                        <select name="table" class="form-select" required>
                            <option value="">- Pilih -</option>
                            @foreach($tables as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">File (xlsx, xls, csv)</label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <strong>Panduan:</strong>
                            <ul class="mb-0">
                                <li>Baris pertama file harus berisi <strong>header kolom</strong> yang sesuai dengan field di database.</li>
                                <li>Format file: <strong>.xlsx</strong>, <strong>.xls</strong>, atau <strong>.csv</strong>.</li>
                                <li>Maksimal 10MB per file.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Import</button>
                    <a href="{{ route('admin.utility.export') }}" class="btn btn-outline-info"><i class="bi bi-download"></i> Download Template</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
