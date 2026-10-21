@extends('layouts.admin')

@section('title', 'Manage Orders — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Orders</h3>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                    <option value="processing" @selected(request('status') == 'processing')>Processing</option>
                    <option value="shipped" @selected(request('status') == 'shipped')>Shipped</option>
                    <option value="delivered" @selected(request('status') == 'delivered')>Delivered</option>
                    <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>৳{{ number_format($order->total_amount, 2) }}</td>
                        <td><span class="badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span></td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-success">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
