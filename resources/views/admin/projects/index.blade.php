@extends('layouts.admin')

@section('title', 'Kelola Proyek')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Konten</span>
    <h2>Proyek</h2>
    <p>Tambah, ubah, atau hapus proyek yang tampil di halaman /proyek.</p>
</section>

<section class="sec glass">
    <div class="head">
        <div><h3>Daftar Proyek</h3></div>
        <a href="{{ route('admin.projects.create') }}" class="btn dark">+ Proyek Baru</a>
    </div>

    @if (empty($projects))
        <p class="empty">Belum ada proyek. Klik "Proyek Baru" untuk menambahkan.</p>
    @else
        <div class="alist">
            @foreach ($projects as $p)
                <div class="arow">
                    <img src="{{ $p['image_url'] }}" alt="{{ $p['title'] }}" class="athumb" loading="lazy">
                    <div class="ainfo">
                        <b>{{ $p['title'] }}</b>
                        <span>{{ $p['category'] ?? 'Tanpa kategori' }} · urutan {{ $p['sort'] ?? 0 }}</span>
                        @if (!empty($p['tags']))
                            <div class="tags">@foreach ($p['tags'] as $t)<span class="tag">{{ $t }}</span>@endforeach</div>
                        @endif
                    </div>
                    <div class="aact">
                        <a href="{{ route('admin.projects.edit', $p['id']) }}" class="btn light">Ubah</a>
                        <form method="POST" action="{{ route('admin.projects.destroy', $p['id']) }}"
                              onsubmit="return confirm('Hapus proyek ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
