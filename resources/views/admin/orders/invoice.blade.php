<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NOTA — {{ $order['invoice_no'] }} — {{ config('portfolio.name') }}</title>
    <style>
        *{box-sizing:border-box;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif}
        body{margin:20px;font-size:11pt;line-height:1.6;color:#0f1f38}
        .nwrap{max-width:800px;margin:0 auto;border:1px solid #dbe5f5;border-radius:12px;overflow:hidden}
        .nhead{display:flex;justify-content:space-between;align-items:flex-start;padding:20px 24px;background:#f7faff;border-bottom:1px solid #dbe5f5}
        .brand h1{margin:0;font-size:16pt}
        .brand small{color:#5a6b85}
        .inv{display:flex;flex-direction:column;align-items:flex-end;text-align:right}
        .muted{color:#5a6b85}
        .nbody{display:grid;grid-template-columns:1fr 1fr;gap:20px;padding:20px 24px}
        .box{border:1px solid #dbe5f5;border-radius:10px;padding:16px 18px}
        .box h3{margin:0 0 8px;font-size:11pt}
        .nitem{padding:0 24px 20px}
        table{width:100%;border-collapse:collapse;border:1px solid #dbe5f5;border-radius:10px;overflow:hidden}
        th,td{padding:10px 14px;border:1px solid #dbe5f5;text-align:left;font-size:10pt}
        thead th{background:#f7faff}
        td.right{text-align:right}
        tfoot td{font-weight:700}
        .nfoot{display:flex;justify-content:space-between;align-items:center;padding:20px 24px;background:#f7faff;border-top:1px solid #dbe5f5}
        .print-btn{padding:8px 16px;border:1px solid #dbe5f5;border-radius:999px;background:#fff;cursor:pointer;font-size:10pt}
        @media print{.no-print{display:none}body{margin:0}.nwrap{border:none}}
    </style>
</head>
@php
  $total = (float)($order['total'] ?? 0);
  $dp    = (float)($order['dp'] ?? 0);
  $sisa  = $total - $dp; if ($sisa < 0) $sisa = 0;
  $statusBayar = $dp <= 0 ? 'Belum Dibayar' : ($sisa <= 0 ? 'Lunas' : 'DP');
@endphp
<body>
    <div class="nwrap">
        <div class="nhead">
            <div class="brand">
                <h1>{{ config('portfolio.name') }}</h1>
                <small>Web Developer & Fotografer</small>
            </div>
            <div class="inv">
                <div class="muted">NOTA</div>
                <div style="font-weight:700">{{ $order['invoice_no'] }}</div>
                <div class="muted">Tanggal: {{ substr($order['order_date'] ?? date('Y-m-d'), 0, 10) }}</div>
                @if (!empty($order['due_date']))<div class="muted">Tenggat: {{ $order['due_date'] }}</div>@endif
            </div>
        </div>
        <div class="nbody">
            <div class="box">
                <h3>Dari</h3>
                <div>{{ config('portfolio.name') }}</div>
                <div class="muted">{{ config('portfolio.email') }}</div>
                <div class="muted">{{ config('portfolio.phone') }}</div>
            </div>
            <div class="box">
                <h3>Kepada</h3>
                <div>{{ $order['client_name'] }}</div>
                <div class="muted">{{ $order['client_contact'] ?? '-' }}</div>
                <div class="muted">Layanan: {{ $order['service'] ?? '-' }}</div>
            </div>
        </div>
        <div class="nitem">
            <table>
                <thead><tr><th>Uraian</th><th class="right">Jumlah</th><th class="right">Harga (Rp)</th><th class="right">Sub Total (Rp)</th></tr></thead>
                <tbody>
                    <tr>
                        <td>{{ $order['title'] }}<br><small class="muted">{{ nl2br(e($order['brief'] ?? '')) }}</small></td>
                        <td class="right">1</td>
                        <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan="3">Total</td><td class="right">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
                    <tr><td colspan="3">DP</td><td class="right">Rp {{ number_format($dp, 0, ',', '.') }}</td></tr>
                    <tr><td colspan="3">Sisa Pembayaran</td><td class="right">Rp {{ number_format($sisa, 0, ',', '.') }}</td></tr>
                    <tr><td colspan="3">Status Pembayaran</td><td class="right">{{ $statusBayar }}</td></tr>
                </tfoot>
            </table>
        </div>
        @if (!empty($order['notes']))
            <div class="nitem"><strong>Catatan:</strong> {{ $order['notes'] }}</div>
        @endif
        <div class="nfoot">
            <div class="muted">Terima kasih atas kepercayaan Anda.</div>
            <div class="no-print"><button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button></div>
        </div>
    </div>
</body>
</html>
