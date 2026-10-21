@extends('layouts.app')

@section('title', 'Profile — MediCart')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1"><i class="fa-solid fa-user me-2"></i>Profile</h3>
                        <p class="text-muted mb-0">Manage your account details.</p>
                    </div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                    </button>
                </div>

                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                            <i class="fa-solid fa-user fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $shippingAddress?->name ?? $user->name }}</h4>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Default Name</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">{{ $shippingAddress?->name ?? $user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Phone No</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">{{ $shippingAddress?->phone ?? ($user->phone ?? 'Not provided') }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-semibold">Email</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">{{ $user->email }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-semibold">Shipping Address</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    @if ($shippingAddress)
                                        {{ $shippingAddress->address }}<br>
                                        {{ $shippingAddress->city }}
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Role</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light text-capitalize">{{ $user->role }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Member Since</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                  
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">+880</span>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', preg_replace('/^(?:\+880|0)/', '', $user->phone ?? '')) }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" minlength="10" autocomplete="tel-national" placeholder="1XXXXXXXXX" oninput="this.value = this.value.replace(/\\D/g, '').slice(0, 10)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" placeholder="Enter your shipping address">{{ old('shipping_address', $shippingAddress?->address ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_city" class="form-label">Shipping City</label>
                        <input type="text" name="shipping_city" id="shipping_city" class="form-control" value="{{ old('shipping_city', $shippingAddress?->city ?? '') }}" placeholder="Enter city">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
