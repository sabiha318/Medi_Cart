@extends('layouts.admin')

@section('title', $customer->name.' — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">{{ $customer->name }}</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Profile</h6>
                <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</p>
                <p class="mb-1"><strong>Joined:</strong> {{ $customer->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Saved Addresses</h6>
                @forelse ($customer->addresses as $address)
                    <p class="small mb-2 border-bottom pb-2">{{ $address->name }} — {{ $address->address }}, {{ $address->city }}</p>
                @empty
                    <p class="text-muted small">No saved addresses.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order History</h6>
                <table class="table align-middle mb-0">
                    <thead><tr><th>#</th><th>Date</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($customer->orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No orders placed yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
