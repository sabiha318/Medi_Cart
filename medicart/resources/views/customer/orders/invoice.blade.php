<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 30px;
        }
        .header {
            border-bottom: 2px solid #198754;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #198754;
        }
        .meta {
            font-size: 12px;
            color: #666;
            margin-top: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background: #f8f9fa;
        }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals td {
            border: none;
            padding: 6px 0;
            font-size: 12px;
        }
        .grand {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">MediCart</div>
        <div class="meta">Invoice #{{ $order->id }} · Generated on {{ now()->format('d M Y, h:i A') }}</div>
    </div>

    <table>
        <tr>
            <td><strong>Customer</strong></td>
            <td>{{ $order->user->name }}</td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td>{{ $order->user->email }}</td>
        </tr>
        <tr>
            <td><strong>Phone</strong></td>
            <td>{{ $order->recipient_phone }}</td>
        </tr>
        <tr>
            <td><strong>Shipping Address</strong></td>
            <td>{{ $order->shipping_address }}, {{ $order->shipping_city }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->medicine_name }}</td>
                    <td>৳{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }} {{ $item->unit ?: ($item->medicine->unit ?? 'unit') }}{{ ($item->unit ?: ($item->medicine->unit ?? 'unit')) === 'box' ? 'es' : 's' }}</td>
                    <td>৳{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td>৳{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Delivery Fee</td>
            <td>৳{{ number_format($order->delivery_fee, 2) }}</td>
        </tr>
        <tr class="grand">
            <td>Total</td>
            <td>৳{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
</body>
</html>
