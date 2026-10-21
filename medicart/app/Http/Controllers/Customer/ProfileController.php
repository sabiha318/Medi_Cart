<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Support\PhoneNumber;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shippingAddress = $user->addresses()->where('is_default', true)->first()
            ?? $user->addresses()->latest()->first();

        return view('customer.profile', compact('user', 'shippingAddress'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'digits:10'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'shipping_city' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => isset($validated['phone']) ? PhoneNumber::normalize($validated['phone']) : $user->phone,
        ]);

        $shippingAddress = $user->addresses()->where('is_default', true)->first();

        if ($request->filled('shipping_address') || $request->filled('shipping_city')) {
            $defaultName = $request->input('name', $user->name);
            $defaultPhone = isset($validated['phone'])
                ? PhoneNumber::normalize($validated['phone'])
                : ($user->phone ?? $shippingAddress?->phone ?? '');

            $addressData = [
                'name' => $defaultName,
                'phone' => $defaultPhone,
                'address' => $request->input('shipping_address', $shippingAddress?->address ?? ''),
                'city' => $request->input('shipping_city', $shippingAddress?->city ?? ''),
                'is_default' => true,
            ];

            if ($shippingAddress) {
                $shippingAddress->update($addressData);
            } else {
                $user->addresses()->create($addressData);
            }
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
