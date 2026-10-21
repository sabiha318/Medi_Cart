@extends('layouts.admin')

@section('title', 'Manage Medicines — MediCart Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Medicines</h3>
    <a href="{{ route('admin.medicines.create') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add Medicine</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Search by name or brand" value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-success w-100" type="submit">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Unit</th><th>Pack details</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr>
                        <td><img src="{{ $medicine->image_url }}" style="width:40px;height:40px;object-fit:cover;" class="rounded"></td>
                        <td>{{ $medicine->name }} <div class="text-muted small">{{ $medicine->brand }}</div></td>
                        <td>{{ $medicine->category->name }}</td>
                        <td>৳{{ number_format($medicine->price, 2) }}</td>
                        <td>
                            @if ($medicine->stock <= 400)
                                <span class="badge bg-danger-subtle text-danger">{{ $medicine->stock }}</span>
                            @elseif ($medicine->stock >= 100)
                                <span class="badge bg-info-subtle text-info">{{ $medicine->stock }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success">{{ $medicine->stock }}</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($medicine->unit) }}</td>
                        <td>
                            @if ($medicine->unit === 'strip' && $medicine->tablets_per_strip)
                                {{ $medicine->tablets_per_strip }} tablets/strip
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.medicines.destroy', $medicine) }}" class="d-inline" onsubmit="return confirm('Delete this medicine?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No medicines found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $medicines->links() }}</div>
    </div>
</div>
@endsection
