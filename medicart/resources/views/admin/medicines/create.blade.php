@extends('layouts.admin')

@section('title', 'Add Medicine — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Add Medicine</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.medicines.store') }}" enctype="multipart/form-data">
            @include('admin.medicines._form', ['medicine' => null])
            <button type="submit" class="btn btn-success">Save Medicine</button>
        </form>
    </div>
</div>
@endsection
