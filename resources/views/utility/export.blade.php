<x-app-layout>
    <x-slot name="header">Export Excel</x-slot>

    <div class="card">
        <div class="card-header">Export Data ke Excel</div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($tables as $key => $label)
                    <div class="col-md-4">
                        <div class="card border h-100">
                            <div class="card-body text-center py-4">
                                <h5 class="card-title">{{ $label }}</h5>
                                <p class="text-muted small">Export data {{ strtolower($label) }} ke format Excel</p>
                                <form action="{{ route('admin.utility.export.do') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="table" value="{{ $key }}">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export {{ $label }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
