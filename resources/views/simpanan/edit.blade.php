<x-app-layout>
    <x-slot name="header">Edit Simpanan</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.simpanan.update', $simpanan) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Anggota</label>
                        <select name="anggota_id" class="form-select" required>
                            <option value="">- Select -</option>
                            @foreach($anggotas as $anggota)
                                <option value="{{ $anggota->id }}" {{ old('anggota_id', $simpanan->anggota_id) == $anggota->id ? 'selected' : '' }}>{{ $anggota->kode }} - {{ $anggota->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">- Select -</option>
                            <option value="pokok" {{ old('jenis', $simpanan->jenis) === 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                            <option value="wajib" {{ old('jenis', $simpanan->jenis) === 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                            <option value="sukarela" {{ old('jenis', $simpanan->jenis) === 'sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                            <option value="bagi_hasil" {{ old('jenis', $simpanan->jenis) === 'bagi_hasil' ? 'selected' : '' }}>Bagi Hasil</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" step="0.01" name="nominal" value="{{ old('nominal', $simpanan->nominal) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $simpanan->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $simpanan->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Periode</label>
                        <select name="periode_id" class="form-select">
                            <option value="">- Auto (active period) -</option>
                            @foreach(\App\Models\Periode::latest()->get() as $p)
                                <option value="{{ $p->id }}" {{ old('periode_id', $simpanan->periode_id) == $p->id ? 'selected' : '' }}>{{ $p->tahun }} {{ $p->nama ? '- '.$p->nama : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $simpanan->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.simpanan.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
