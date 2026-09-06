<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; color: #333; font-size: 14px; }
        .header-table { width: 100%; margin-bottom: 25px; }
        .header-table td { vertical-align: top; }
        .title { font-size: 24px; font-weight: bold; color: #1e293b; margin: 0 0 10px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #f1f5f9; color: #334155; text-align: left; padding: 10px; font-size: 13px; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .totals-table { width: 100%; margin-top: 20px; }
        .totals-table td { padding: 5px 10px; }
        .text-right { text-align: right; }
        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">INVOICE</h1>
                <p style="margin: 2px 0;"><strong>No. Order:</strong> {{ $order->order_number }}</p>
                <p style="margin: 2px 0;"><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p style="margin: 2px 0;"><strong>Status Order:</strong> {{ ucfirst($order->status) }}</p>
                <!-- Tambahan Informasi Pembayaran -->
                <p style="margin: 2px 0;"><strong>Metode Bayar:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
                <p style="margin: 2px 0;">
                    <strong>Status Bayar:</strong> 
                    <span style="font-weight: bold; color: {{ $order->payment_status === 'paid' ? '#16a34a' : ($order->payment_status === 'failed' ? '#dc2626' : '#d97706') }};">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </p>
            </td>
            <td class="text-right">
                <h3 style="margin: 0 0 5px 0;">Tujuan Pengiriman:</h3>
                <p style="margin: 2px 0;"><strong>{{ $order->customer->name ?? '-' }}</strong></p>
                <p style="margin: 2px 0;">{{ $order->customer->phone ?? '' }}</p>
                <p style="margin: 2px 0; max-width: 250px; float: right;">{{ $order->shipping_address ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th>Produk</th>
                <th class="text-right" style="width: 20%;">Harga Satuan</th>
                <th class="text-right" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Produk' }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8;">Tidak ada produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="width: 60%;"></td>
            <td class="text-right" style="width: 20%;"><strong>Subtotal Produk:</strong></td>
            <td class="text-right" style="width: 20%;">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right"><strong>Ongkos Kirim:</strong></td>
            <td class="text-right">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-right" style="font-size: 16px;"><strong>Total Bayar:</strong></td>
            <td class="text-right" style="font-size: 16px; font-weight: bold; color: #0f172a;">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </td>
        </tr>
    </table>

</body>
</html>