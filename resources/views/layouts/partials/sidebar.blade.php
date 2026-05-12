@php
    $menus = \App\Models\Menu::with('children', 'roles')
        ->whereNull('parent_id')
        ->orderBy('order')
        ->get()
        ->filter(fn($menu) => $menu->isVisibleByUser(Auth::user()))
        ->filter(function ($menu) {
            $visibleChildren = $menu->children->filter(fn($c) => $c->isVisibleByUser(Auth::user()));
            return $menu->permission || $visibleChildren->count() > 0;
        });
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>{{ config('app.name', 'Laravel') }}</h4>
    </div>

    <div class="nav-section">
        <div class="nav-section-title">{{ __('Menu') }}</div>
        <ul class="list-unstyled mb-0">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            @foreach($menus as $menu)
                @php
                    $visibleChildren = $menu->children->filter(fn($c) => $c->isVisibleByUser(Auth::user()));
                    $isActive = $visibleChildren->contains(fn($c) => request()->routeIs($c->route));
                @endphp

                @if($visibleChildren->count() > 0)
                    <li class="nav-item">
                        <a href="#" onclick="event.preventDefault(); this.nextElementSibling.classList.toggle('d-none'); this.querySelector('.arrow').classList.toggle('open');" class="{{ $isActive ? 'active' : '' }}">
                            @if($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @else
                                <i class="bi bi-folder"></i>
                            @endif
                            <span>{{ __($menu->name) }}</span>
                            <i class="bi bi-chevron-right arrow {{ $isActive ? 'open' : '' }}"></i>
                        </a>
                        <ul class="sub-menu {{ $isActive ? '' : 'd-none' }}">
                            @foreach($visibleChildren as $child)
                                <li>
                                    <a href="{{ $child->route ? route($child->route) : ($child->url ?: '#') }}"
                                       class="{{ request()->routeIs($child->route) ? 'active' : '' }}">
                                        {{ __($child->name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ $menu->route ? route($menu->route) : ($menu->url ?: '#') }}"
                           class="{{ request()->routeIs($menu->route) ? 'active' : '' }}">
                            @if($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @else
                                <i class="bi bi-circle"></i>
                            @endif
                            <span>{{ __($menu->name) }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>
