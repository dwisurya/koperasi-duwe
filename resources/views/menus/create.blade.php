<x-app-layout>
    <x-slot name="header">Create Menu</x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon (Bootstrap Icons class)</label>
                        <input type="text" name="icon" value="{{ old('icon') }}" placeholder="e.g. bi bi-gear" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Route Name</label>
                        <input type="text" name="route" value="{{ old('route') }}" placeholder="e.g. roles.index" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">URL (if no route)</label>
                        <input type="text" name="url" value="{{ old('url') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Parent Menu</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (top level)</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Required Permission</label>
                        <input type="text" name="permission" value="{{ old('permission') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="form-control">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Visible to Roles</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input" id="role_{{ $role->id }}">
                                    <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
