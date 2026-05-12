<form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
    @csrf
    @method('delete')

    <p class="text-muted small">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" class="form-control" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-danger">Delete Account</button>
</form>
