@extends('layouts.admin')

@section('title', $project ? 'Ubah Proyek' : 'Proyek Baru')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Konten</span>
    <h2>{{ $project ? 'Ubah Proyek' : 'Proyek Baru' }}</h2>
    <p>Isi detail proyek. Gambar otomatis diunggah ke Supabase Storage.</p>
</section>

<section class="sec glass">
    <form method="POST"
          action="{{ $project ? route('admin.projects.update', $project['id']) : route('admin.projects.store') }}"
          enctype="multipart/form-data" class="aform">
        @csrf
        @if ($project) @method('PUT') @endif

        <label>Nama Proyek *
            <input type="text" name="title" required maxlength="150"
                   placeholder="Mis. Toko Online UMKM" value="{{ old('title', $project['title'] ?? '') }}">
        </label>

        <div class="two">
            <label>Kategori
                <input type="text" name="category" maxlength="80"
                       list="cats" placeholder="Web App / Website / API / Dashboard"
                       value="{{ old('category', $project['category'] ?? '') }}">
                <datalist id="cats">
                    <option>Website</option><option>Web App</option>
                    <option>Dashboard</option><option>API</option>
                </datalist>
            </label>
            <label>Urutan (sort)
                <input type="number" name="sort" min="0" max="9999"
                       value="{{ old('sort', $project['sort'] ?? 0) }}">
            </label>
        </div>

        <label>Deskripsi
            <textarea name="description" rows="3" maxlength="500"
                      placeholder="Penjelasan singkat soal proyek ini">{{ old('description', $project['description'] ?? '') }}</textarea>
        </label>

        <label>Link (repo / live demo)
            <input type="url" name="link" maxlength="300" placeholder="https://github.com/..."
                   value="{{ old('link', $project['link'] ?? '') }}">
        </label>

        <label>Tag <small>pisahkan dengan koma</small>
            <input type="text" name="tags" maxlength="300" placeholder="Laravel, Supabase, Tailwind"
                   value="{{ old('tags', isset($project['tags']) && is_array($project['tags']) ? implode(', ', $project['tags']) : '') }}">
        </label>

        <label>Gambar <small>{{ $project ? 'Kosongkan kalau tidak diganti' : 'JPG/PNG/WEBP, maks 4 MB' }}</small>
            <input type="file" name="image" accept="image/*">
        </label>

        @if (!empty($project['image_url']))
            <div class="preview">
                <span>Saat ini:</span>
                <img src="{{ $project['image_url'] }}" alt="Pratinjau">
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.projects.index') }}" class="btn light">Batal</a>
            <button class="btn dark" type="submit">{{ $project ? 'Simpan Perubahan' : 'Tambah Proyek' }}</button>
        </div>
    </form>
</section>
@endsection
