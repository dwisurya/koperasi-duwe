<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h3 class="auth-title">Forgot Password</h3>
            <p class="auth-subtitle">Enter your email to receive a reset link</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>

            <p class="text-center mt-3 mb-0 small">
                <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a>
            </p>
        </div>
    </div>
</x-guest-layout>
