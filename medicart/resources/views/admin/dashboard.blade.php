@extends('layouts.admin')

@section('title', 'Admin Dashboard — MediCart')

@section('content')
<h3 class="fw-bold mb-5">Dashboard</h3>

<div class="row g-3 mb-5 " style=" display:flex; flex-direction: row;">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Medicines</div>
                <div class="fs-3 fw-bold text-success">{{ $stats['total_medicines'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Orders</div>
                <div class="fs-3 fw-bold text-primary">{{ $stats['total_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="text-muted small">Collected Revenue</div>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.dashboard', ['revenue' => 'monthly']) }}" class="btn btn-{{ request('revenue', 'monthly') === 'monthly' ? 'success' : 'outline-secondary' }}">Monthly</a>
                        <a href="{{ route('admin.dashboard', ['revenue' => 'yearly']) }}" class="btn btn-{{ request('revenue', 'monthly') === 'yearly' ? 'success' : 'outline-secondary' }}">Yearly</a>
                    </div>
                </div>
                <div class="fs-3 fw-bold text-success">
                    ৳{{ number_format($stats['current_revenue'], 2) }}
                </div>
                <div class="small text-muted mt-2">
                    Pending COD: ৳{{ number_format($stats['pending_cod_revenue'], 2) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Customers</div>
                <div class="fs-3 fw-bold text-info">{{ $stats['total_customers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Pending Orders</div>
                <div class="fs-3 fw-bold text-warning">{{ $stats['pending_orders'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.orders.export') }}" class="btn btn-success">
        <i class="fa-solid fa-file-excel me-1"></i> Download Excel
    </a>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Recent Orders</h6>
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                                <td>{{ $order->user->name }}</td>
                                <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-triangle-exclamation text-warning"></i> Low Stock Alerts</h6>
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Medicine</th><th>Stock</th></tr></thead>
                    <tbody>
                        @forelse ($lowStockMedicines as $medicine)
                            <tr>
                                <td>{{ $medicine->name }}</td>
                                <td><span class="badge bg-danger-subtle text-danger">{{ $medicine->stock }} left</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">Stock levels look healthy.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
