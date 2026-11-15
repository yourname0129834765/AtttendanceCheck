<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') · {{ config('app.name', 'AttendanceCheck') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">A</span>
                <span>Attendance<span class="brand-light">Check</span></span>
            </a>
            <div class="nav-label">WORKSPACE</div>
            <nav class="primary-nav" aria-label="Main navigation">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon">D</span> Dashboard
                </a>
                <a class="{{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                    <span class="nav-icon">S</span> Students
                </a>
                <a class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                    <span class="nav-icon">A</span> Attendance
                </a>
            </nav>
            <div class="sidebar-footer">
                <span class="online-dot"></span>
                <span>Attendance monitoring</span>
            </div>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">STUDENT MANAGEMENT</p>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="topbar-date">{{ now()->format('l, F j, Y') }}</div>
            </header>

            <div class="page-content">
                @if (session('success'))
                    <div class="flash-message" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="flash-message flash-error" role="alert">Please review the highlighted fields and try again.</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
