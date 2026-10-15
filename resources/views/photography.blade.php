@extends('layouts.app')

@section('title', 'Fotografi')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Galeri</span>
    <h2>Fotografi</h2>
    <p>Potret, lanskap, dan momen sehari-hari yang saya abadikan.</p>
    <div class="filters" id="filters">
        <button class="on" data-cat="all">Semua</button>
        @foreach ($categories as $c)
            <button data-cat="{{ $c }}">{{ $c }}</button>
        @endforeach
    </div>
</section>

<section class="sec glass">
    <div class="masonry" style="margin-top:0" id="gallery">
        @foreach ($photos as $ph)
            <figure class="shot" data-cat="{{ $ph['category'] ?? '' }}">
                <img src="{{ $ph['image_url'] }}" alt="{{ $ph['title'] ?? 'Foto' }}" loading="lazy">
                <figcaption class="cap">{{ $ph['title'] ?? '' }}</figcaption>
            </figure>
        @endforeach
    </div>
</section>

<div class="lightbox" id="lightbox" role="dialog" aria-label="Pratinjau foto"><img alt=""></div>
@endsection

@push('scripts')
<script>
    const shots = document.querySelectorAll('#gallery .shot');
    document.getElementById('filters').addEventListener('click', e => {
        const b = e.target.closest('button'); if (!b) return;
        document.querySelectorAll('#filters button').forEach(x => x.classList.toggle('on', x === b));
        shots.forEach(s => s.style.display = (b.dataset.cat === 'all' || s.dataset.cat === b.dataset.cat) ? '' : 'none');
    });
    const lb = document.getElementById('lightbox'), lbImg = lb.querySelector('img');
    shots.forEach(s => s.addEventListener('click', () => {
        const img = s.querySelector('img'); lbImg.src = img.src; lbImg.alt = img.alt; lb.classList.add('open');
    }));
    lb.addEventListener('click', () => lb.classList.remove('open'));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') lb.classList.remove('open'); });
</script>
@endpush
