@extends('layouts.app')

@section('title', 'Login — MediCart')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3 text-success"><i class="fa-solid fa-capsules"></i> Login to MediCart</h4>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Login</button>
                </form>

                <p class="text-center mt-3 mb-0 small">
                    Don't have an account? <a href="{{ route('register') }}">Register here</a>
                </p>

                <hr>
                
            </div>
        </div>
    </div>
</div>
@endsection
