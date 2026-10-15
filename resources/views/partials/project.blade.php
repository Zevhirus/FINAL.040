@php
    $link = $p['link'] ?? null;
    $tags = array_values(array_filter((array) ($p['tags'] ?? [])));
    $ref = $p['id'] ?? \Illuminate\Support\Str::slug($p['title']);
@endphp
<a class="work" href="{{ route('project', $ref) }}" target="_blank" rel="noopener">
    <div class="img"><img src="{{ $p['image_url'] }}" alt="{{ $p['title'] }}" loading="lazy"></div>
    <div class="body">
        <h3>{{ $p['title'] }}</h3>
        <p>{{ $p['category'] ?? '' }}@if (!empty($p['description'])) — {{ $p['description'] }}@endif</p>
        @if ($tags)
            <div class="tags">
                @foreach ($tags as $t)<span class="tag">{{ $t }}</span>@endforeach
            </div>
        @endif
        <span class="work-more">Lihat rincian ↗</span>
    </div>
</a>
