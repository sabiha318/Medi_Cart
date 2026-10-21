@extends('layouts.app')

@section('title', 'Order #'.$order->id.' — MediCart')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Order #{{ $order->id }}</h4>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-success btn-sm">
            <i class="fa-solid fa-file-pdf me-1"></i> Download Invoice PDF
        </a>
        <span class="badge {{ $order->statusBadgeClass() }} fs-6">{{ $order->customerStatusLabel() }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Items</h6>
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>Medicine</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->medicine_name }}</td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }} {{ $item->unit ?: ($item->medicine->unit ?? 'unit') }}{{ ($item->unit ?: ($item->medicine->unit ?? 'unit')) === 'box' ? 'es' : 's' }}
                                    @if (($item->tablets_per_strip ?: $item->medicine?->tablets_per_strip) && ($item->unit ?: $item->medicine?->unit) === 'strip')
                                        <div class="text-muted small">{{ $item->tablets_per_strip ?: $item->medicine->tablets_per_strip }} tablets each</div>
                                    @endif
                                </td>
                                <td>৳{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order tracking -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order Tracking</h6>
                @php
                    $steps = ['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
                    $stepKeys = array_keys($steps);
                    $currentIndex = $order->status === 'cancelled' ? -1 : array_search($order->status, $stepKeys, true);
                @endphp
                @if ($order->status === 'cancelled')
                    <div class="alert alert-danger mb-0">This order was cancelled by the admin.</div>
                @else
                    <div class="d-flex justify-content-between position-relative">
                        @foreach ($steps as $key => $label)
                            <div class="text-center flex-fill">
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                                    {{ $loop->index <= $currentIndex ? 'bg-success text-white' : 'bg-light text-muted border' }}"
                                    style="width:36px;height:36px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="small mt-1 {{ $loop->index <= $currentIndex ? 'fw-semibold text-success' : 'text-muted' }}">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Shipping Details</h6>
                <p class="mb-1"><strong>{{ $order->recipient_name }}</strong></p>
                <p class="mb-1 text-muted">{{ $order->recipient_phone }}</p>
                <p class="mb-1 text-muted">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                <hr>
                <p class="mb-1">Delivery: <strong>{{ ucfirst($order->delivery_option) }}</strong></p>
                <p class="mb-1">Payment: <strong>{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'bKash' }}</strong></p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Payment Summary</h6>
                <div class="d-flex justify-content-between small mb-1"><span>Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="d-flex justify-content-between small mb-1"><span>Delivery Fee</span><span>৳{{ number_format($order->delivery_fee, 2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>৳{{ number_format($order->total_amount, 2) }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
