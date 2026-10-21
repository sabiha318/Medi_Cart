@extends('layouts.app')

@section('title', 'Register — MediCart')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3 text-success"><i class="fa-solid fa-user-plus"></i> Create your MediCart account</h4>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-muted small"> </span></label>
                        <div class="input-group">
                            <span class="input-group-text">+880</span>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" minlength="10" autocomplete="tel-national" placeholder="1XXXXXXXXX" oninput="this.value = this.value.replace(/\\D/g, '').slice(0, 10)">
                        </div>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Create Account</button>
                </form>

                <p class="text-center mt-3 mb-0 small">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
