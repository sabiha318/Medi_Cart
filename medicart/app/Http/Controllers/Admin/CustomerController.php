<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->when($request->query('q'), function ($q, $term) {
                $q->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                          ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $customer->load(['orders' => fn ($q) => $q->latest(), 'addresses']);

        return view('admin.customers.show', compact('customer'));
    }
}
