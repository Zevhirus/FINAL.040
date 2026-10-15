<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') — {{ config('portfolio.name') }}</title>
    <meta name="description" content="@yield('description', config('portfolio.tagline'))">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/intro.css') }}">
    <script>
        (function () {
            if (!('IntersectionObserver' in window)) return;
            var el = document.documentElement;
            el.classList.add('js-reveal');
            // jaring pengaman: bila site.js gagal dimuat, tampilkan konten kembali
            setTimeout(function () {
                if (!el.classList.contains('reveal-ready')) el.classList.remove('js-reveal');
            }, 2500);
        })();
    </script>
</head>
<body>
@include('partials.intro')
<div class="wrap">
    <header class="nav glass">
        <a href="{{ route('home') }}" class="brand">
            <span class="logo">{{ config('portfolio.initials') }}</span>
            <span>{{ config('portfolio.name') }}<small>{{ config('portfolio.role') }}</small></span>
        </a>
        <nav class="links" id="links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'on' : '' }}">Beranda</a>
            <a href="{{ route('projects') }}" class="{{ request()->routeIs('projects') ? 'on' : '' }}">Proyek</a>
            <a href="{{ route('photography') }}" class="{{ request()->routeIs('photography') ? 'on' : '' }}">Fotografi</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'on' : '' }}">Kontak</a>
        </nav>
        <a href="{{ route('contact') }}" class="btn light">Ayo Bicara ↗</a>
        <button class="burger" id="burger" aria-label="Menu" aria-expanded="false">☰</button>
    </header>

    <main>@yield('content')</main>

    <footer class="glass">
        <span>© {{ date('Y') }} {{ config('portfolio.name') }}. Semua hak dilindungi.</span>
        <span>
            @foreach (config('portfolio.socials') as $label => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" style="margin-left:14px">{{ $label }}</a>
            @endforeach
        </span>
    </footer>
</div>
<script>
    const burger = document.getElementById('burger'), links = document.getElementById('links');
    burger.addEventListener('click', () => {
        const open = links.classList.toggle('open');
        burger.setAttribute('aria-expanded', open);
    });
</script>
<script src="{{ asset('js/site.js') }}" defer></script>
@stack('scripts')
</body>
</html>
