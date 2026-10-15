@extends('layouts.app')

@section('title', $p['title'])
@section('description', $p['description'] ?? config('portfolio.tagline'))

@section('content')
@php
    $tags = array_values(array_filter((array) ($p['tags'] ?? [])));
    $link = $p['link'] ?? null;
@endphp

<section class="detail-top">
    <a href="{{ route('projects') }}" class="btn light">← Semua Proyek</a>
</section>

<article class="detail glass">
    <div class="detail__media">
        <img src="{{ $p['image_url'] }}" alt="{{ $p['title'] }}">
    </div>
    <div class="detail__body">
        <span class="eyebrow">{{ $p['category'] ?? 'Proyek' }}</span>
        <h1>{{ $p['title'] }}</h1>
        @if (!empty($p['description']))
            <p class="lead">{{ $p['description'] }}</p>
        @endif
        @if ($tags)
            <div class="tags">
                @foreach ($tags as $t)<span class="tag">{{ $t }}</span>@endforeach
            </div>
        @endif
        @if ($link)
            <div class="cta">
                <a href="{{ $link }}" class="btn dark" target="_blank" rel="noopener">Kunjungi Proyek ↗</a>
            </div>
        @endif
    </div>
</article>

@if (!empty($others))
    <section class="sec glass">
        <div class="head">
            <div><span class="eyebrow">Proyek Lainnya</span><h2>Karya Terkait</h2></div>
            <a href="{{ route('projects') }}" class="btn light">Semua Proyek ↗</a>
        </div>
        <div class="grid3">
            @foreach ($others as $p)
                @include('partials.project', ['p' => $p])
            @endforeach
        </div>
    </section>
@endif
@endsection
