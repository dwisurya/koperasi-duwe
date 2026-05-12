<x-app-layout>
    <x-slot name="header">Create Periode</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.periodes.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tahun</label>
                        <input type="text" name="tahun" value="{{ old('tahun', date('Y')) }}" class="form-control @error('tahun') is-invalid @enderror" required maxlength="4">
                        @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama (optional)</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="e.g. Tahun Buku 2026">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Set as active period</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.periodes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
