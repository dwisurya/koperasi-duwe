<x-app-layout>
    <x-slot name="header">Create Permission</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Permission Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. article-list" class="form-control" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
