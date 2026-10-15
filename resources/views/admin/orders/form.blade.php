@extends('layouts.admin')

@section('title', $order ? 'Ubah Pesanan' : 'Pesanan Baru')

@section('content')
<section class="page-title glass">
    <span class="eyebrow">Bisnis</span>
    <h2>{{ $order ? 'Ubah Pesanan' : 'Pesanan Baru' }}</h2>
</section>

<section class="sec glass">
    <form method="POST" action="{{ $order ? route('admin.orders.update', $order['id']) : route('admin.orders.store') }}" class="aform">
        @csrf
        @if ($order) @method('PUT') @endif

        <div class="two">
            <label>No. Nota (otomatis)
                <input type="text" name="invoice_no" maxlength="20" value="{{ old('invoice_no', $order['invoice_no'] ?? $next) }}">
            </label>
            <label>Status *
                <select name="status" required>
                    @php $st = old('status', $order['status'] ?? 'baru'); @endphp
                    <option value="baru" {{ $st==='baru'?'selected':'' }}>Baru</option>
                    <option value="diproses" {{ $st==='diproses'?'selected':'' }}>Diproses</option>
                    <option value="dikerjakan" {{ $st==='dikerjakan'?'selected':'' }}>Dikerjakan</option>
                    <option value="selesai" {{ $st==='selesai'?'selected':'' }}>Selesai</option>
                    <option value="batal" {{ $st==='batal'?'selected':'' }}>Batal</option>
                </select>
            </label>
        </div>

        <div class="two">
            <label>Nama Klien *
                <input type="text" name="client_name" required maxlength="150" value="{{ old('client_name', $order['client_name'] ?? '') }}">
            </label>
            <label>Kontak Klien
                <input type="text" name="client_contact" maxlength="150" placeholder="WA/Email/HP" value="{{ old('client_contact', $order['client_contact'] ?? '') }}">
            </label>
        </div>

        <div class="two">
            <label>Layanan
                <input type="text" name="service" maxlength="80" placeholder="Website / Web App / Fotografi" value="{{ old('service', $order['service'] ?? '') }}">
            </label>
            <label>Nama Proyek *
                <input type="text" name="title" required maxlength="150" value="{{ old('title', $order['title'] ?? '') }}">
            </label>
        </div>

        <label>Brief / Kebutuhan
            <textarea name="brief" rows="3" maxlength="2000">{{ old('brief', $order['brief'] ?? '') }}</textarea>
        </label>

        <div class="two">
            <label>Estimasi/Budget (Rp)
                <input type="number" step="0.01" min="0" name="budget" value="{{ old('budget', $order['budget'] ?? 0) }}">
            </label>
            <label>Total Akhir (Rp)
                <input type="number" step="0.01" min="0" name="total" value="{{ old('total', $order['total'] ?? 0) }}">
            </label>
        </div>

        <div class="two">
            <label>DP (Rp)
                <input type="number" step="0.01" min="0" name="dp" value="{{ old('dp', $order['dp'] ?? 0) }}">
            </label>
            <label>Sisa (auto saat cetak) <small>total - dp</small>
                <input type="text" disabled placeholder="Dihitung otomatis saat cetak nota">
            </label>
        </div>

        <div class="two">
            <label>Tanggal Order
                <input type="date" name="order_date" value="{{ old('order_date', $order['order_date'] ?? date('Y-m-d')) }}">
            </label>
            <label>Tenggat (Due Date)
                <input type="date" name="due_date" value="{{ old('due_date', $order['due_date'] ?? '') }}">
            </label>
        </div>

        <label>Catatan Internal
            <textarea name="notes" rows="2" maxlength="2000">{{ old('notes', $order['notes'] ?? '') }}</textarea>
        </label>

        <div class="form-actions">
            <a class="btn light" href="{{ route('admin.orders.index') }}">Batal</a>
            <button class="btn dark" type="submit">{{ $order ? 'Simpan' : 'Tambah Pesanan' }}</button>
        </div>
    </form>
</section>
@endsection
