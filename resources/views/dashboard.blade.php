<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold text-dark mb-2">Welcome, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted mb-0">You're logged in.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
