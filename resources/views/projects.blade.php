@extends('layouts.app')

@section('title', 'Proyek')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Portofolio</span>
    <h2>Proyek</h2>
    <p>Kumpulan website dan aplikasi yang pernah saya bangun, dari ide sampai rilis.</p>
</section>

<section class="sec glass">
    <div class="grid3" style="margin-top:0">
        @foreach ($projects as $p)
            @include('partials.project', ['p' => $p])
        @endforeach
    </div>
</section>
@endsection
