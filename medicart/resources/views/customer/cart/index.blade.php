@extends('layouts.app')

@section('title', 'My Cart — MediCart')

@section('content')
<h4 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping"></i> My Cart</h4>

@if ($cartItems->isEmpty())
    <div class="text-center py-5">
        <i class="fa-solid fa-cart-shopping fa-3x text-muted mb-3"></i>
        <p class="text-muted">Your cart is empty.</p>
        <a href="{{ route('medicines.index') }}" class="btn btn-success">Browse Medicines</a>
    </div>
@else
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Price</th>
                                <th style="width:180px;">Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item->medicine->image_url }}" style="width:48px;height:48px;object-fit:cover;" class="rounded" alt="">
                                            <div>
                                                <div class="fw-semibold">{{ $item->medicine->name }}</div>
                                                <div class="text-muted small">{{ $item->medicine->brand }}</div>
                                                @if (($item->tablets_per_strip ?: $item->medicine->tablets_per_strip) && ($item->unit ?: $item->medicine->unit) === 'strip')
                                                    <div class="text-muted small">{{ $item->tablets_per_strip ?: $item->medicine->tablets_per_strip }} tablets per strip</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>৳{{ number_format($item->medicine->price, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->medicine->stock }}" class="form-control form-control-sm me-2" style="width:70px;" aria-label="Quantity in {{ $item->unit ?: $item->medicine->unit }}s">
                                            <span class="small text-muted align-self-center me-2">{{ $item->unit ?: $item->medicine->unit }}{{ ($item->unit ?: $item->medicine->unit) === 'box' ? 'es' : 's' }}</span>
                                            <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-rotate"></i></button>
                                        </form>
                                    </td>
                                    <td class="fw-semibold">৳{{ number_format($item->subtotal, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Order Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-semibold">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <p class="text-muted small">Delivery fee is calculated at checkout.</p>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
