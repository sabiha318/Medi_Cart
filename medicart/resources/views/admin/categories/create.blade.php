@extends('layouts.admin')

@section('title', 'Add Category — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Add Category</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @include('admin.categories._form', ['category' => null])
            <button type="submit" class="btn btn-success">Save Category</button>
        </form>
    </div>
</div>
@endsection
