<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
<div class="wrap admin-wrap">

    <header class="nav glass admin-nav">
        <a href="{{ route('admin.projects.index') }}" class="brand">
            <span class="logo">AS</span>
            <span>Panel Admin<small>Kelola konten portfolio</small></span>
        </a>
        <nav class="links admin-links">
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'on' : '' }}">Proyek</a>
            <a href="{{ route('admin.photos.index') }}" class="{{ request()->routeIs('admin.photos.*') ? 'on' : '' }}">Foto</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'on' : '' }}">Pesanan</a>
            <a href="{{ route('home') }}" target="_blank">Lihat Website ↗</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn light" type="submit">Keluar</button>
        </form>
    </header>

    <main>
        @if (session('status'))
            <div class="alert ok">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert err">
                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="glass admin-foot">
        <span>© {{ date('Y') }} — Panel admin {{ config('portfolio.name') }}</span>
    </footer>
</div>
</body>
</html>
