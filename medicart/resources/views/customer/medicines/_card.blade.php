<div class="col">
    <div class="card h-100 shadow-sm border-0">
        <a href="{{ route('medicines.show', $medicine) }}">
            <img src="{{ $medicine->image_url }}" class="card-img-top" style="height:160px; object-fit:cover;" alt="{{ $medicine->name }}">
        </a>
        <div class="card-body d-flex flex-column">
            <span class="badge bg-light text-success border border-success mb-1 align-self-start">{{ $medicine->category->name }}</span>
            <a href="{{ route('medicines.show', $medicine) }}" class="text-decoration-none text-dark">
                <h6 class="fw-bold mb-1">{{ $medicine->name }}</h6>
            </a>
            <p class="text-muted small mb-2">{{ $medicine->brand }} &middot; per {{ $medicine->unit }}</p>
            @if ($medicine->unit === 'strip' && $medicine->tablets_per_strip)
                <p class="text-info small mb-2">{{ $medicine->tablets_per_strip }} tablets per strip</p>
            @endif
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="fw-bold text-success">৳{{ number_format($medicine->price, 2) }}</span>
                @if ($medicine->isInStock())
                    <span class="badge bg-success-subtle text-success">In Stock</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                @endif
            </div>
            @auth
                @if (! auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('cart.add', $medicine) }}" class="mt-2">
                        @csrf
                        <button class="btn btn-success btn-sm w-100" {{ $medicine->isInStock() ? '' : 'disabled' }}>
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm w-100 mt-2">Login to buy</a>
            @endauth
        </div>
    </div>
</div>
