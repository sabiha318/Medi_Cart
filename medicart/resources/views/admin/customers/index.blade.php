@extends('layouts.admin')

@section('title', 'Customers — MediCart Admin')

@section('content')
<h3 class="fw-bold mb-4">Customers</h3>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Search by name or email" value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table align-middle mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined</th><th></th></tr></thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $customer->orders_count }}</span></td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-success">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No customers registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $customers->links() }}</div>
    </div>
</div>
@endsection
