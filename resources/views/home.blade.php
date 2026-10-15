@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<section class="hero glass">
    <div>
        <span class="eyebrow">Halo, saya</span>
        <h1>{{ config('portfolio.name') }}</h1>
        <div class="grad">{{ config('portfolio.role') }}</div>
        <p class="lead">{{ config('portfolio.tagline') }}</p>
        <div class="cta">
            <a href="{{ route('projects') }}" class="btn dark">Lihat Proyek ↗</a>
            <a href="{{ route('photography') }}" class="btn light">Galeri Foto</a>
        </div>
        <div class="socials">
            @foreach (config('portfolio.socials') as $label => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="portrait">
        <div class="frame">
            @if (config('portfolio.photo_url'))
                <img src="{{ config('portfolio.photo_url') }}" alt="Foto {{ config('portfolio.name') }}">
            @else
                <span class="mono">{{ config('portfolio.initials') }}</span>
            @endif
        </div>
        <div class="badge glass b1"><b>{{ config('portfolio.stats')[0]['value'] }}</b><span>Tahun Pengalaman</span></div>
        <div class="badge glass b2"><span>Proyek Selesai</span><b>{{ config('portfolio.stats')[1]['value'] }}</b></div>
        <div class="spark glass">✦</div>
    </div>
</section>

{{-- Tentang --}}
<section class="sec glass about">
    <div>
        <span class="eyebrow">Tentang</span>
        <h2>Membangun dengan Teliti,<br>Memotret dengan Rasa</h2>
        <div class="stats">
            @foreach (config('portfolio.stats') as $s)
                <div class="stat"><b>{{ $s['value'] }}</b><span>{{ $s['label'] }}</span></div>
            @endforeach
        </div>
    </div>
    <div>
        <p>{{ config('portfolio.bio') }}</p>
        <a href="{{ route('contact') }}" class="btn light">Hubungi Saya ↗</a>
    </div>
</section>

{{-- Layanan --}}
<section class="sec glass">
    <span class="eyebrow">Layanan</span>
    <h2>Yang Bisa Saya Kerjakan</h2>
    <div class="grid4">
        @foreach (config('portfolio.services') as $svc)
            <article class="card">
                <div class="ico {{ $svc['tone'] }}">{{ $svc['icon'] }}</div>
                <h3>{{ $svc['title'] }}</h3>
                <p>{{ $svc['text'] }}</p>
            </article>
        @endforeach
    </div>
</section>

{{-- Teknologi --}}
<section class="sec glass">
    <span class="eyebrow">Alat & Keahlian</span>
    <h2>Teknologi yang Saya Pakai</h2>
    <div class="tech">
        @foreach (config('portfolio.tech_stack') as $t)
            <div class="tech__item" title="{{ $t['name'] }}">
                @if (!empty($t['slug']))
                    <img src="https://cdn.simpleicons.org/{{ $t['slug'] }}" alt="{{ $t['name'] }}" loading="lazy">
                @else
                    <span class="tech__mark" style="background:{{ $t['bg'] ?? 'var(--accent)' }};color:{{ $t['tone'] ?? '#fff' }}">{{ $t['mark'] }}</span>
                @endif
                <span>{{ $t['name'] }}</span>
            </div>
        @endforeach
    </div>
</section>

{{-- Proyek pilihan --}}
<section class="sec glass">
    <div class="head">
        <div><span class="eyebrow">Proyek Unggulan</span><h2>Karya Pilihan</h2></div>
        <a href="{{ route('projects') }}" class="btn light">Semua Proyek ↗</a>
    </div>
    <div class="prow">
        @foreach ($projects as $p)
            <div class="prow__cell">@include('partials.project', ['p' => $p])</div>
        @endforeach
    </div>
</section>

{{-- Fotografi --}}
<section class="sec glass">
    <div class="head">
        <div><span class="eyebrow">Fotografi</span><h2>Dari Balik Lensa</h2></div>
        <a href="{{ route('photography') }}" class="btn light">Lihat Galeri ↗</a>
    </div>
    <div class="masonry">
        @foreach ($photos as $ph)
            <figure class="shot">
                <img src="{{ $ph['image_url'] }}" alt="{{ $ph['title'] ?? 'Foto' }}" loading="lazy">
                <figcaption class="cap">{{ $ph['title'] ?? '' }}</figcaption>
            </figure>
        @endforeach
    </div>
</section>

{{-- Proses --}}
<section class="sec glass">
    <span class="eyebrow">Proses Kerja</span>
    <h2>Cara Saya Bekerja</h2>
    <div class="steps">
        @foreach (config('portfolio.process') as $i => $step)
            <div class="step">
                <em>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</em>
                <h3>{{ $step[0] }}</h3>
                <p>{{ $step[1] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="sec glass" style="text-align:center">
    <span class="eyebrow">Mari Terhubung</span>
    <h2>Punya proyek di pikiran?<br>Ayo wujudkan bersama.</h2>
    <p style="color:var(--muted);margin:10px 0 20px">{{ config('portfolio.email') }}</p>
    <a href="{{ route('contact') }}" class="btn dark">Kirim Pesan ↗</a>
</section>
@endsection
