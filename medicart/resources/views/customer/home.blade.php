@extends('layouts.app')

@section('title', 'MediCart — Your Trusted Online Pharmacy')

@section('content')
<div class="p-5 mb-4 bg-success bg-gradient text-white rounded-3 shadow-sm">
    <div class="container-fluid py-3">
        <h1 class="display-6 fw-bold"><i class="fa-solid fa-truck-medical"></i> Medicines delivered to your door</h1>
        <p class="col-md-8 fs-5">Browse trusted brands, compare prices, and get your medicines delivered fast — standard or express.</p>
        <a class="btn btn-light btn-lg text-success fw-semibold" href="{{ route('medicines.index') }}">Shop Medicines <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>

<h4 class="fw-bold mb-3">Shop by Category</h4>
<div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
    @foreach ($categories as $category)
        <div class="col">
            <a href="{{ route('medicines.index', ['category' => $category->id]) }}" class="text-decoration-none">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fa-solid fa-capsules fa-2x text-success mb-2"></i>
                        <p class="fw-semibold text-dark mb-0">{{ $category->name }}</p>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<h4 class="fw-bold mb-3">Featured Medicines</h4>
<div class="row row-cols-2 row-cols-md-4 g-4">
    @forelse ($featuredMedicines as $medicine)
        @include('customer.medicines._card', ['medicine' => $medicine])
    @empty
        <p class="text-muted">No medicines available yet. Please check back soon.</p>
    @endforelse
</div>
@endsection
