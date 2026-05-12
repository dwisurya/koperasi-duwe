<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h3 class="auth-title">Confirm Password</h3>
            <p class="auth-subtitle">Please confirm your password before continuing</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary w-100">Confirm</button>
            </form>
        </div>
    </div>
</x-guest-layout>
