<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header td {
            vertical-align: top;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 5px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }
        .items-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .total-wrapper {
            width: 100%;
            margin-top: 15px;
        }
        .total-box {
            float: right;
            width: 320px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            background-color: #e5e7eb;
            color: #374151;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Header Invoice -->
    <table class="header">
        <tr>
            <td>
                <div class="title">INVOICE PESANAN</div>
                <div><strong>No. Order:</strong> #{{ $order->order_number }}</div>
                <div><strong>Tanggal:</strong> {{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</div>
            </td>
            <td class="text-right">
                <h3 style="margin: 0 0 5px 0;">Toko E-Commerce</h3>
                <p style="margin: 0;">Status: <span class="badge">{{ strtoupper($order->status ?? 'PENDING') }}</span></p>
            </td>
        </tr>
    </table>

    <!-- Detail Pelanggan & Pengiriman -->
    <table class="info-table">
        <tr>
            <td>
                <strong>Detail Pelanggan:</strong><br>
                Nama: {{ $order->customer_name ?? '-' }}<br>
                Email: {{ $order->customer_email ?? '-' }}<br>
                Telepon: {{ $order->customer_phone ?? '-' }}
            </td>
            <td>
                <strong>Alamat Pengiriman:</strong><br>
                {{ $order->shipping_address ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- Tabel Item Pesanan -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 45%;">Produk</th>
                <th class="text-right" style="width: 20%;">Harga</th>
                <th class="text-center" style="width: 10%;">Jumlah</th>
                <th class="text-right" style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $calculatedTotal = 0; @endphp
            @forelse($order->items as $index => $item)
                @php
                    $unitPrice = ($item->unit_price > 0) ? $item->unit_price : ($item->product->price ?? 0);
                    $subtotal = ($item->subtotal > 0) ? $item->subtotal : ($unitPrice * $item->quantity);
                    $calculatedTotal += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                    <td class="text-right">Rp {{ number_format($unitPrice, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada item pesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total Pembayaran -->
    <div class="total-wrapper">
        <div class="total-box">
            <table class="items-table">
                <tr>
                    <th style="background-color: #f3f4f6;">Total Pembayaran</th>
                    <td class="text-right">
                        <strong>Rp {{ number_format($calculatedTotal, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>