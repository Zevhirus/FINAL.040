@extends('layouts.admin')

@section('title', 'Kelola Foto')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Konten</span>
    <h2>Foto</h2>
    <p>Kelola galeri yang tampil di halaman /fotografi.</p>
</section>

<section class="sec glass">
    <div class="head">
        <div><h3>Daftar Foto</h3></div>
        <a href="{{ route('admin.photos.create') }}" class="btn dark">+ Foto Baru</a>
    </div>

    @if (empty($photos))
        <p class="empty">Belum ada foto. Klik "Foto Baru" untuk menambahkan.</p>
    @else
        <div class="agrid">
            @foreach ($photos as $ph)
                <figure class="acell">
                    <img src="{{ $ph['image_url'] }}" alt="{{ $ph['title'] ?? 'Foto' }}" loading="lazy">
                    <figcaption>
                        <b>{{ $ph['title'] ?? '(tanpa judul)' }}</b>
                        <span>{{ $ph['category'] ?? 'Tanpa kategori' }}</span>
                    </figcaption>
                    <div class="aact">
                        <a href="{{ route('admin.photos.edit', $ph['id']) }}" class="btn light">Ubah</a>
                        <form method="POST" action="{{ route('admin.photos.destroy', $ph['id']) }}"
                              onsubmit="return confirm('Hapus foto ini dari galeri?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </figure>
            @endforeach
        </div>
    @endif
</section>
@endsection
