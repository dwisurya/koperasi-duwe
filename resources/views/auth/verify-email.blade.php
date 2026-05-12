<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h3 class="auth-title">Verify Email</h3>
            <p class="auth-subtitle">Please verify your email address by clicking the link we just sent to you.</p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-100">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">Log Out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
