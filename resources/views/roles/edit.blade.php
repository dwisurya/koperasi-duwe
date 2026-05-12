<x-app-layout>
    <x-slot name="header">Edit Role: {{ $role->name }}</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Role Name</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="mb-2">
                            <strong class="text-muted small text-uppercase">{{ $group }}</strong>
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                @foreach($groupPermissions as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="perm_{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
