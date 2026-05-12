<x-app-layout>
    <x-slot name="header">Permissions</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Permissions</span>
            @can('permission-create')
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Create Permission</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Name</th>
                        <th>Guard</th>
                        <th class="no-sort no-search" width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>
                                @can('permission-edit')
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('permission-delete')
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete permission?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
        </div>
    </div>
</x-app-layout>
