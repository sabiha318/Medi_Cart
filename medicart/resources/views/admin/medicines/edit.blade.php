@extends('layouts.admin')

@section('title', 'Edit Medicine — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Edit Medicine</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.medicines.update', $medicine) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.medicines._form', ['medicine' => $medicine])
            <button type="submit" class="btn btn-success">Update Medicine</button>
        </form>
    </div>
</div>
@endsection
