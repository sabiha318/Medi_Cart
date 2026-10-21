@extends('layouts.admin')

@section('title', 'Order #'.$order->id.' — MediCart Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Order #{{ $order->id }}</h3>
    <span class="badge {{ $order->statusBadgeClass() }} fs-6">{{ ucfirst($order->status) }}</span>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Items</h6>
                <table class="table align-middle mb-0">
                    <thead><tr><th>Medicine</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
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
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Customer</h6>
                <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                <p class="mb-1 text-muted">{{ $order->user->email }}</p>
                <hr>
                <h6 class="fw-bold mb-2">Shipping</h6>
                <p class="mb-1">{{ $order->recipient_name }} — {{ $order->recipient_phone }}</p>
                <p class="mb-1 text-muted">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                <p class="mb-1">Delivery: <strong>{{ ucfirst($order->delivery_option) }}</strong></p>
                <p class="mb-1">Payment: <strong>{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'bKash' }}</strong></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Delivery Fee</span><span>৳{{ number_format($order->delivery_fee, 2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>৳{{ number_format($order->total_amount, 2) }}</span></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Update Status</h6>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-2">
                        <option value="pending" @selected($order->status == 'pending')>Pending</option>
                        <option value="processing" @selected($order->status == 'processing')>Processing</option>
                        <option value="shipped" @selected($order->status == 'shipped')>Shipped</option>
                        <option value="delivered" @selected($order->status == 'delivered')>Delivered</option>
                        <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-success w-100 mb-2">Update Status</button>
                </form>

                @if ($order->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger w-100">Cancel Order</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
