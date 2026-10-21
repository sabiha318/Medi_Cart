<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\PhoneNumber;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->latest()->get();

        return view('customer.address.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateAddress($request);

        if (! empty($validated['is_default'])) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $validated['user_id'] = Auth::id();

        Address::create($validated);

        return back()->with('success', 'Address added successfully.');
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $validated = $this->validateAddress($request);

        if (! empty($validated['is_default'])) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update($validated);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }

    private function validateAddress(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $validated['phone'] = PhoneNumber::normalize($validated['phone']);
        $validated['is_default'] = $request->boolean('is_default');

        return $validated;
    }
}
