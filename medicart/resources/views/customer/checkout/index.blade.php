@extends('layouts.app')

@section('title', 'Checkout — MediCart')

@section('content')
<h4 class="fw-bold mb-4"><i class="fa-solid fa-truck-fast"></i> Checkout</h4>

<form method="POST" action="{{ route('checkout.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <!-- Address -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">1. Select Delivery Address</h6>
                    @forelse ($addresses as $address)
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="address_id" value="{{ $address->id }}" id="addr{{ $address->id }}" {{ $loop->first ? 'checked' : '' }} required>
                            <label class="form-check-label w-100" for="addr{{ $address->id }}">
                                <strong>{{ $address->name }}</strong> — {{ $address->phone }}<br>
                                <span class="text-muted">{{ $address->address }}, {{ $address->city }}</span>
                                @if ($address->is_default) <span class="badge bg-success ms-2">Default</span> @endif
                            </label>
                        </div>
                    @empty
                        <p class="text-muted">You have no saved addresses yet.</p>
                    @endforelse
                    <a href="{{ route('address.index') }}" class="btn btn-outline-success btn-sm mt-2">
                        <i class="fa-solid fa-plus"></i> Add New Address
                    </a>
                </div>
            </div>

            <!-- Delivery -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">2. Delivery Option</h6>
                    <div class="form-check border rounded p-3 mb-2">
                        <input class="form-check-input" type="radio" name="delivery_option" value="standard" id="standard" checked>
                        <label class="form-check-label w-100" for="standard">
                            <strong>Standard Delivery</strong> — 3-5 business days <span class="float-end">৳60.00</span>
                        </label>
                    </div>
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input" type="radio" name="delivery_option" value="express" id="express">
                        <label class="form-check-label w-100" for="express">
                            <strong>Express Delivery</strong> — Same/next day <span class="float-end">৳120.00</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">3. Payment Method</h6>
                    <div class="form-check border rounded p-3 mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                        <label class="form-check-label" for="cod"><strong>Cash on Delivery</strong></label>
                    </div>
                   
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input" type="radio" name="payment_method" value="sslcommerz" id="sslcommerz">
                        <label class="form-check-label" for="sslcommerz">
                            <strong>SSLCommerz</strong> <span class="text-muted">(online payment)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Order Summary</h6>
                    @foreach ($cartItems as $item)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ $item->medicine->name }} &times; {{ $item->quantity }} {{ $item->unit ?: $item->medicine->unit }}{{ ($item->unit ?: $item->medicine->unit) === 'box' ? 'es' : 's' }}{{ ($item->tablets_per_strip ?: $item->medicine->tablets_per_strip) && ($item->unit ?: $item->medicine->unit) === 'strip' ? ' ('.($item->tablets_per_strip ?: $item->medicine->tablets_per_strip).' tablets each)' : '' }}</span>
                            <span>৳{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-semibold">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <p class="text-muted small">Final total (incl. delivery fee) is confirmed on the next screen.</p>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa-solid fa-check"></i> Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
