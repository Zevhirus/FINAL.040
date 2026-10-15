@extends('layouts.admin')

@section('title', $photo ? 'Ubah Foto' : 'Foto Baru')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Konten</span>
    <h2>{{ $photo ? 'Ubah Foto' : 'Foto Baru' }}</h2>
    <p>Upload foto ke galeri. File disimpan di Supabase Storage.</p>
</section>

<section class="sec glass">
    <form method="POST"
          action="{{ $photo ? route('admin.photos.update', $photo['id']) : route('admin.photos.store') }}"
          enctype="multipart/form-data" class="aform">
        @csrf
        @if ($photo) @method('PUT') @endif

        <label>Judul
            <input type="text" name="title" maxlength="150" placeholder="Mis. Senja di Pantai"
                   value="{{ old('title', $photo['title'] ?? '') }}">
        </label>

        <div class="two">
            <label>Kategori
                <input type="text" name="category" maxlength="80" list="pcats"
                       placeholder="Lanskap / Potret / Acara" value="{{ old('category', $photo['category'] ?? '') }}">
                <datalist id="pcats">
                    <option>Lanskap</option><option>Potret</option>
                    <option>Jalanan</option><option>Acara</option><option>Produk</option>
                </datalist>
            </label>
            <label>Urutan (sort)
                <input type="number" name="sort" min="0" max="9999"
                       value="{{ old('sort', $photo['sort'] ?? 0) }}">
            </label>
        </div>

        <label>Gambar <small>{{ $photo ? 'Kosongkan untuk memakai gambar lama' : 'JPG/PNG/WEBP, maks 6 MB' }}</small>
            <input type="file" name="image" accept="image/*" {{ $photo ? '' : 'required' }}>
        </label>

        @if (!empty($photo['image_url']))
            <div class="preview">
                <span>Saat ini:</span>
                <img src="{{ $photo['image_url'] }}" alt="Pratinjau">
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.photos.index') }}" class="btn light">Batal</a>
            <button class="btn dark" type="submit">{{ $photo ? 'Simpan Perubahan' : 'Upload Foto' }}</button>
        </div>
    </form>
</section>
@endsection
