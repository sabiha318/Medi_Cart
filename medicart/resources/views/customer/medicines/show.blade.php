@extends('layouts.app')

@section('title', $medicine->name.' — MediCart')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">Medicines</a></li>
        <li class="breadcrumb-item active">{{ $medicine->name }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        <img src="{{ $medicine->image_url }}" class="img-fluid rounded shadow-sm" alt="{{ $medicine->name }}">
    </div>
    <div class="col-md-7">
        <span class="badge bg-light text-success border border-success mb-2">{{ $medicine->category->name }}</span>
        <h2 class="fw-bold">{{ $medicine->name }}</h2>
        <p class="text-muted">{{ $medicine->brand }} &middot; Sold per {{ $medicine->unit }}</p>
        @if ($medicine->unit === 'strip' && $medicine->tablets_per_strip)
            <p class="text-info mb-2"><i class="fa-solid fa-pills"></i> {{ $medicine->tablets_per_strip }} tablets per strip</p>
        @endif
        <h3 class="text-success fw-bold">৳{{ number_format($medicine->price, 2) }}</h3>

        @if ($medicine->isInStock())
            <span class="badge bg-success-subtle text-success mb-3">In Stock ({{ $medicine->stock }} available)</span>
        @else
            <span class="badge bg-danger-subtle text-danger mb-3">Out of Stock</span>
        @endif

        <p>{{ $medicine->description }}</p>

        @auth
            @if (! auth()->user()->isAdmin())
                <form method="POST" action="{{ route('cart.add', $medicine) }}" class="d-flex align-items-center gap-2 mt-3">
                    @csrf
                    <label for="quantity" class="visually-hidden">Quantity in {{ $medicine->unit }}s</label>
                    <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $medicine->stock }}" class="form-control" style="width:100px;" {{ $medicine->isInStock() ? '' : 'disabled' }}>
                    <span class="text-muted">{{ $medicine->unit }}{{ $medicine->unit === 'box' ? 'es' : 's' }}</span>
                    <button class="btn btn-success" {{ $medicine->isInStock() ? '' : 'disabled' }}>
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-success mt-3">Login to purchase</a>
        @endauth
    </div>
</div>

@if ($related->isNotEmpty())
    <h5 class="fw-bold mt-5 mb-3">Related Medicines</h5>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach ($related as $item)
            @include('customer.medicines._card', ['medicine' => $item])
        @endforeach
    </div>
@endif
@endsection
