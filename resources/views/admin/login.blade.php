<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body login-body">
<div class="login-card glass">
    <span class="eyebrow">Panel Admin</span>
    <h2>Masuk</h2>
    <p class="muted-txt">Masukkan password untuk mengelola proyek & foto.</p>

    @if ($errors->any())
        <div class="alert err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}" class="aform">
        @csrf
        <label>Password
            <input type="password" name="password" required autofocus placeholder="••••••••" value="{{ old('password') }}">
        </label>
        <button class="btn dark" type="submit">Masuk ↗</button>
    </form>
</div>
</body>
</html>
