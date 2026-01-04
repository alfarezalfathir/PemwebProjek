<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->invoice_code }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RestoEnak</h2>
        <p>Jl. Teknologi No. 123, Bandung</p>
        <hr>
        <p>Invoice: {{ $order->invoice_code }}</p>
        <p>Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
        <p>Pelanggan: {{ $order->user->name }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p class="total">Total Bayar: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p>Status: LUNAS</p>
    </div>
    
    <div style="text-align: center; margin-top: 50px; font-size: 12px; color: #777;">
        <p>Terima kasih atas kunjungan Anda!</p>
    </div>
</body>
</html>