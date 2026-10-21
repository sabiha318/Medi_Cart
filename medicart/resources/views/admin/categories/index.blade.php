@extends('layouts.admin')

@section('title', 'Manage Categories — MediCart Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Categories</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add Category</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Name</th><th>Description</th><th>Medicines</th><th></th></tr></thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-muted small">{{
                            $category->description ? \Illuminate\Support\Str::limit($category->description, 60) : ''
                        }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $category->medicines_count }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $categories->links() }}</div>
    </div>
</div>
@endsection
