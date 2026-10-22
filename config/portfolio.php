<?php

/*
|--------------------------------------------------------------------------
| Data profil & contoh konten
|--------------------------------------------------------------------------
| Ubah nilai di sini untuk profil. Proyek & foto asli diambil dari Supabase;
| data "sample_*" hanya tampil jika Supabase belum diisi / belum dikonfigurasi.
*/

return [
    'name' => 'Aan Setiawan',
    'initials' => 'AS',
    'role' => 'Web Developer & Fotografer',
    'tagline' => 'Saya membangun aplikasi web yang rapi, cepat, dan bermakna, serta mengabadikan momen lewat lensa kamera.',
    'bio' => 'Saya Aan Setiawan, web developer dan fotografer. Saya senang mengubah masalah yang rumit menjadi pengalaman digital yang sederhana, serta menangkap cerita lewat foto yang jujur dan hangat.',
    'photo_url' => env('PROFILE_PHOTO_URL'), // URL foto profil (mis. dari Supabase Storage)

    'email' => env('CONTACT_EMAIL', 'hello@aansetiawan.com'),
    'phone' => env('CONTACT_PHONE', '+62 800 0000 0000'),
    'location' => 'Indonesia',

    'socials' => [
        'Instagram' => 'https://www.instagram.com/jelly_fish.env/',
        'GitHub' => 'https://github.com/Zevhirus',
        'LinkedIn' => 'https://linkedin.com/',
    ],

    'stats' => [
        ['value' => '3+',  'label' => 'Tahun Pengalaman'],
        ['value' => '20+', 'label' => 'Proyek Selesai'],
        ['value' => '10+', 'label' => 'Klien Puas'],
    ],

    'services' => [
        ['icon' => '</>', 'title' => 'Web Development', 'text' => 'Website dan aplikasi web dengan Laravel yang cepat, aman, dan mudah dikembangkan.', 'tone' => 'amber'],
        ['icon' => '◧',   'title' => 'UI / UX Design', 'text' => 'Antarmuka bersih dan intuitif, dari wireframe sampai prototipe interaktif.', 'tone' => 'navy'],
        ['icon' => '◉',   'title' => 'Fotografi',      'text' => 'Potret, produk, dan dokumentasi acara dengan gaya natural dan bercerita.', 'tone' => 'blue'],
        ['icon' => '⛁',   'title' => 'Backend & API',  'text' => 'Database, autentikasi, dan REST API menggunakan Supabase dan PostgreSQL.', 'tone' => 'teal'],
    ],

    'tools' => ['Laravel', 'PHP', 'Supabase', 'PostgreSQL', 'JavaScript', 'Tailwind', 'Git', 'Figma', 'Lightroom', 'Photoshop'],

    'tech_stack' => [
        ['name' => 'Laravel',    'slug' => 'laravel'],
        ['name' => 'PHP',        'slug' => 'php'],
        ['name' => 'Supabase',   'slug' => 'supabase'],
        ['name' => 'PostgreSQL', 'slug' => 'postgresql'],
        ['name' => 'JavaScript', 'slug' => 'javascript'],
        ['name' => 'Tailwind',   'slug' => 'tailwindcss'],
        ['name' => 'Git',        'slug' => 'git'],
        ['name' => 'Figma',      'slug' => 'figma'],
        ['name' => 'Lightroom',  'mark' => 'Lr', 'tone' => '#31a8ff', 'bg' => '#001e36'],
        ['name' => 'Photoshop',  'mark' => 'Ps', 'tone' => '#31a8ff', 'bg' => '#001e36'],
    ],

    'process' => [
        ['Pahami',    'Menggali kebutuhan, tujuan, dan target pengguna.'],
        ['Rancang',   'Menyusun struktur, wireframe, dan arah visual.'],
        ['Bangun',    'Mengembangkan fitur dengan kode yang bersih.'],
        ['Uji',       'Menguji di berbagai perangkat dan memperbaiki detail.'],
        ['Rilis',     'Meluncurkan, memantau, dan terus menyempurnakan.'],
    ],

    'sample_projects' => [
        ['title' => 'Toko Online UMKM', 'category' => 'Web App', 'description' => 'Katalog produk dan checkout sederhana untuk usaha kecil.', 'image_url' => 'https://picsum.photos/seed/aan-p1/900/600', 'tags' => ['Laravel', 'Supabase'], 'link' => null],
        ['title' => 'Dashboard Analitik', 'category' => 'Dashboard', 'description' => 'Visualisasi data penjualan dengan grafik interaktif.', 'image_url' => 'https://picsum.photos/seed/aan-p2/900/600', 'tags' => ['Laravel', 'Chart.js'], 'link' => null],
        ['title' => 'Landing Page Studio Foto', 'category' => 'Website', 'description' => 'Halaman promosi dengan galeri dan formulir booking.', 'image_url' => 'https://picsum.photos/seed/aan-p3/900/600', 'tags' => ['Blade', 'CSS'], 'link' => null],
        ['title' => 'Sistem Reservasi', 'category' => 'Web App', 'description' => 'Pemesanan jadwal online dengan notifikasi email.', 'image_url' => 'https://picsum.photos/seed/aan-p4/900/600', 'tags' => ['Laravel', 'PostgreSQL'], 'link' => null],
        ['title' => 'Company Profile', 'category' => 'Website', 'description' => 'Situs profil perusahaan responsif dan ringan.', 'image_url' => 'https://picsum.photos/seed/aan-p5/900/600', 'tags' => ['Laravel', 'Tailwind'], 'link' => null],
        ['title' => 'API Aplikasi Mobile', 'category' => 'API', 'description' => 'REST API dengan autentikasi token untuk aplikasi mobile.', 'image_url' => 'https://picsum.photos/seed/aan-p6/900/600', 'tags' => ['Laravel', 'Sanctum'], 'link' => null],
    ],

    'sample_photos' => [
        ['title' => 'Senja di Pantai', 'category' => 'Lanskap', 'image_url' => 'https://picsum.photos/seed/aan-f1/900/1200'],
        ['title' => 'Potret Pagi', 'category' => 'Potret', 'image_url' => 'https://picsum.photos/seed/aan-f2/900/700'],
        ['title' => 'Jalan Kota', 'category' => 'Jalanan', 'image_url' => 'https://picsum.photos/seed/aan-f3/900/900'],
        ['title' => 'Kabut Pegunungan', 'category' => 'Lanskap', 'image_url' => 'https://picsum.photos/seed/aan-f4/900/1100'],
        ['title' => 'Momen Pernikahan', 'category' => 'Acara', 'image_url' => 'https://picsum.photos/seed/aan-f5/900/650'],
        ['title' => 'Kopi & Cahaya', 'category' => 'Produk', 'image_url' => 'https://picsum.photos/seed/aan-f6/900/900'],
        ['title' => 'Wajah Pasar', 'category' => 'Potret', 'image_url' => 'https://picsum.photos/seed/aan-f7/900/1150'],
        ['title' => 'Langit Malam', 'category' => 'Lanskap', 'image_url' => 'https://picsum.photos/seed/aan-f8/900/700'],
    ],
];
