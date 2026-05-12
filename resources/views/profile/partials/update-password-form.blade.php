<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="current_password" class="form-label">Current Password</label>
        <input id="current_password" name="current_password" type="password" class="form-control" required autocomplete="current-password">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password">
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Save</button>
        @if(session('status') === 'password-updated')
            <span class="text-success small"><i class="bi bi-check-circle"></i> Saved.</span>
        @endif
    </div>
</form>
