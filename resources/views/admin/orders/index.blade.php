@extends('layouts.admin')

@section('title', 'Pesanan Masuk')

@section('content')
<?php use Illuminate\Support\Str; ?>
<section class="page-title glass">
    <span class="eyebrow">Bisnis</span>
    <h2>Pesanan Masuk</h2>
    <p>Kelola orderan klien, ubah status, dan cetak nota sederhana.</p>
</section>

<section class="sec glass">
    <div class="head">
        <div><h3>Daftar Pesanan</h3>
            <div class="muted-txt" style="margin-top:4px">Filter: <a href="{{ route('admin.orders.index') }}">Semua</a> ·
                <a href="{{ route('admin.orders.index', ['status'=>'baru']) }}">Baru</a> ·
                <a href="{{ route('admin.orders.index', ['status'=>'diproses']) }}">Diproses</a> ·
                <a href="{{ route('admin.orders.index', ['status'=>'dikerjakan']) }}">Dikerjakan</a> ·
                <a href="{{ route('admin.orders.index', ['status'=>'selesai']) }}">Selesai</a> ·
                <a href="{{ route('admin.orders.index', ['status'=>'batal']) }}">Batal</a>
            </div>
        </div>
        <a href="{{ route('admin.orders.create') }}" class="btn dark">+ Pesanan Baru</a>
    </div>

    @if (empty($orders))
        <p class="empty">Belum ada pesanan.</p>
    @else
        <div class="alist">
            @foreach ($orders as $o)
                <div class="arow">
                    <div class="ainfo">
                        <b>{{ $o['invoice_no'] }} — {{ $o['title'] }}</b>
                        <span>{{ $o['client_name'] }} · {{ $o['service'] ?? '-' }} · {{ $o['order_date'] }}</span>
                        <span>Status: <span class="badge">{{ $o['status'] }}</span></span>
                    </div>
                    <div class="aact">
                        <a class="btn light" href="{{ route('admin.orders.invoice', $o['id']) }}" target="_blank">Cetak Nota</a>
                        <a class="btn light" href="{{ route('admin.orders.edit', $o['id']) }}">Ubah</a>
                        <form method="POST" action="{{ route('admin.orders.destroy', $o['id']) }}" onsubmit="return confirm('Hapus pesanan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section class="sec glass">
    <div class="head"><div><h3>Pesan dari Kontak</h3><p class="muted-txt">Bisa dikonversi jadi pesanan.</p></div></div>
    @if (empty($messages))
        <p class="empty">Belum ada pesan kontak.</p>
    @else
        <div class="alist">
            @foreach ($messages as $m)
                <div class="arow">
                    <div class="ainfo">
                        <b>{{ $m['name'] }} &lt;{{ $m['email'] }}&gt;</b>
                        <span>{{ $m['subject'] ?? '-' }} · {{ substr($m['created_at'] ?? '', 0, 10) }}</span>
                        <p class="muted-txt" style="margin-top:6px">{{ Str::limit($m['message'] ?? '', 160) }}</p>
                    </div>
                    <div class="aact">
                        <form method="POST" action="{{ route('admin.orders.convert', $m['id']) }}">
                            @csrf
                            <button class="btn light" type="submit">Konversi jadi Pesanan</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
