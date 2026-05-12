<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h3 class="auth-title">Welcome Back</h3>
            <p class="auth-subtitle">Sign in to your account</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input id="remember" type="checkbox" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

            @if(Route::has('register'))
                <p class="text-center mt-3 mb-0 small">
                    Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>
                </p>
            @endif
        </div>
    </div>
</x-guest-layout>
