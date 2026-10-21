@extends('layouts.app')

@section('title', 'My Orders — MediCart')

@section('content')
<h4 class="fw-bold mb-4"><i class="fa-solid fa-receipt"></i> My Orders</h4>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $order->items()->count() }} item(s)</td>
                            <td class="fw-semibold">৳{{ number_format($order->total_amount, 2) }}</td>
                            <td><span class="badge {{ $order->statusBadgeClass() }}">{{ $order->customerStatusLabel() }}</span></td>
                            <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-success">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">You haven't placed any orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
