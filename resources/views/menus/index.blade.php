<x-app-layout>
    <x-slot name="header">Menus</x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Manage Menus</span>
            @can('menu-create')
                <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Create Menu</a>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive"><table id="dataTable" class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Parent</th>
                        <th class="d-none d-lg-table-cell">Route / URL</th>
                        <th class="d-none d-md-table-cell">Order</th>
                        <th>Active</th>
                        <th class="no-sort no-search" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">
                                {{ $menu->name }}
                                @if($menu->icon)
                                    <span class="badge bg-light text-muted ms-1"><i class="{{ $menu->icon }}"></i></span>
                                @endif
                            </td>
                            <td class="text-muted d-none d-md-table-cell">{{ $menu->parent?->name ?? '-' }}</td>
                            <td class="text-muted small d-none d-lg-table-cell">{{ $menu->route ?? $menu->url ?? '-' }}</td>
                            <td class="d-none d-md-table-cell">{{ $menu->order }}</td>
                            <td>
                                <span class="badge {{ $menu->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $menu->is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                @can('menu-edit')
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('menu-delete')
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete menu?')">
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
