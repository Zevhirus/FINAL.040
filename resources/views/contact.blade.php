@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<section class="sec glass contact" style="margin-top:22px">
    <div>
        <span class="eyebrow">Mari Terhubung</span>
        <h2>Punya proyek di pikiran?<br>Ayo buat sesuatu yang luar biasa.</h2>
        <div class="info">
            <a href="mailto:{{ config('portfolio.email') }}"><i>✉</i>{{ config('portfolio.email') }}</a>
            <a href="tel:{{ preg_replace('/\s+/', '', config('portfolio.phone')) }}"><i>☎</i>{{ config('portfolio.phone') }}</a>
            <div><i>⌖</i>{{ config('portfolio.location') }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('contact.send') }}" class="card" style="padding:24px">
        @csrf
        @if (session('status'))<div class="alert ok" role="status">{{ session('status') }}</div>@endif
        @if ($errors->any())
            <div class="alert err" role="alert">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="two">
            <input type="text" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required aria-label="Nama">
            <input type="email" name="email" placeholder="Email Anda" value="{{ old('email') }}" required aria-label="Email">
        </div>
        <input type="text" name="subject" placeholder="Topik / jenis proyek" value="{{ old('subject') }}" aria-label="Topik">
        <textarea name="message" placeholder="Ceritakan kebutuhan Anda…" required aria-label="Pesan">{{ old('message') }}</textarea>
        <button type="submit" class="btn dark" style="justify-content:center">Kirim Pesan ➤</button>
    </form>
</section>
@endsection
