<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        @auth
            @include('layouts.partials.sidebar')
        @endauth

        <div class="main-content">
            @auth
                <nav class="navbar-admin">
                    <div class="navbar-left">
                        <button class="toggle-sidebar d-lg-none" id="toggle-sidebar" type="button">
                            <i class="bi bi-list"></i>
                        </button>
                        <h6 class="mb-0 d-none d-lg-block fw-semibold text-dark">
                            @isset($header)
                                {{ strip_tags($header) }}
                            @else
                                Dashboard
                            @endisset
                        </h6>
                    </div>
                    <div class="navbar-right">
                        <div class="dropdown me-2">
                            <button class="dropdown-toggle btn btn-sm btn-outline-secondary border-0" data-bs-toggle="dropdown" title="Language">
                                <i class="bi bi-globe"></i>
                                <span class="d-none d-md-inline">{{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}"><i class="bi bi-check2 me-2 {{ app()->getLocale() === 'id' ? '' : 'invisible' }}"></i>Indonesia</a></li>
                                <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}"><i class="bi bi-check2 me-2 {{ app()->getLocale() === 'en' ? '' : 'invisible' }}"></i>English</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>{{ __('Profile') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @endauth

            @isset($header)
                @guest
                    <nav class="navbar-admin">
                        <div class="navbar-left">
                            <h6 class="mb-0 fw-semibold text-dark">{{ strip_tags($header) }}</h6>
                        </div>
                    </nav>
                @endguest
            @endisset

            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    @stack('scripts')
</body>
</html>
