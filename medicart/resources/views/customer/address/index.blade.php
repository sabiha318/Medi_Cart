@extends('layouts.app')

@section('title', 'My Addresses — MediCart')

@section('content')
<h4 class="fw-bold mb-4"><i class="fa-solid fa-location-dot"></i> My Addresses</h4>

<div class="row">
    <div class="col-md-7">
        @forelse ($addresses as $address)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $address->name }}
                                @if ($address->is_default)
                                    <span class="badge bg-success">Default</span>
                                @endif
                            </h6>
                            <p class="mb-1 text-muted">{{ $address->phone }}</p>
                             <p class="mb-1 text-muted">{{ $address->email }}</p>
                            <p class="mb-0">{{ $address->address }}, {{ $address->city }}</p>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editAddress{{ $address->id }}">Edit</button>
                            <form method="POST" action="{{ route('address.destroy', $address) }}" onsubmit="return confirm('Delete this address?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editAddress{{ $address->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('address.update', $address) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Edit Address</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label class="form-label small">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $address->name }}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+880</span>
                                        <input type="text" name="phone" class="form-control" value="{{ preg_replace('/^(?:\+880|0)/', '', $address->phone) }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" minlength="10" autocomplete="tel-national" placeholder="1XXXXXXXXX" oninput="this.value = this.value.replace(/\\D/g, '').slice(0, 10)" required>
                                    </div>
                                </div>
                                 <div class="col-12">
                                <label class="form-label text-muted small fw-semibold">Email Address</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">{{ $address->email }}</div>
                            </div>
                                <div class="mb-2">
                                    <label class="form-label small">Address</label>
                                    <textarea name="address" class="form-control" required>{{ $address->address }}</textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ $address->city }}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Postal Code</label>
                                    <input type="text" name="postal_address" class="form-control" value="{{ $address->postal_address }}" required>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_default" value="1" class="form-check-input" {{ $address->is_default ? 'checked' : '' }}>
                                    <label class="form-check-label">Set as default address</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">You haven't added any addresses yet.</p>
        @endforelse
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Add New Address</h6>
                <form method="POST" action="{{ route('address.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text">+880</span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" minlength="10" autocomplete="tel-national" placeholder="1XXXXXXXXX" oninput="this.value = this.value.replace(/\\D/g, '').slice(0, 10)" required>
                        </div>
                    </div>
                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $address->email) }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Address</label>
                        <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_default" value="1" class="form-check-input">
                        <label class="form-check-label">Set as default address</label>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
