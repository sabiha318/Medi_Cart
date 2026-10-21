@extends('layouts.app')

@section('title', 'All Medicines — MediCart')

@section('content')
<div class="row">
    <!-- Filters -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-filter"></i> Filter</h6>
                <form method="GET" action="{{ route('medicines.index') }}">
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Name or brand" value="{{ request('q') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort by</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="latest" @selected($sort == 'latest')>Newest</option>
                            <option value="name" @selected($sort == 'name')>Name (A-Z)</option>
                            <option value="price_asc" @selected($sort == 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected($sort == 'price_desc')>Price: High to Low</option>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm w-100" type="submit">Apply</button>
                    <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">Reset</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">All Medicines</h5>
            <span class="text-muted small">{{ $medicines->total() }} results</span>
        </div>

        <div class="row row-cols-2 row-cols-md-3 g-4">
            @forelse ($medicines as $medicine)
                @include('customer.medicines._card', ['medicine' => $medicine])
            @empty
                <p class="text-muted">No medicines matched your search.</p>
            @endforelse
        </div>

        <div class="mt-4">
                {{ $medicines->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
