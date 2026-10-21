@extends('layouts.admin')

@section('title', 'Edit Category — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Edit Category</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('admin.categories._form', ['category' => $category])
            <button type="submit" class="btn btn-success">Update Category</button>
        </form>
    </div>
</div>
@endsection
