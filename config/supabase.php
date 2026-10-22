<?php

return [
    'url' => rtrim(env('SUPABASE_URL', ''), '/'),
    // Key publik (anon) untuk membaca data; service key hanya dipakai di server untuk menyimpan pesan kontak.
    'anon_key' => env('SUPABASE_ANON_KEY', ''),
    'service_key' => env('SUPABASE_SERVICE_KEY', ''),
    'cache_ttl' => (int) env('SUPABASE_CACHE_TTL', 300), // detik
];
