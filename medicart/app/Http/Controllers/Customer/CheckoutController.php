<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    const DELIVERY_FEES = [
        'standard' => 60,
        'express' => 120,
    ];

    /**
     * Show the checkout page: address selection, delivery + payment options, order summary.
     */
    public function index()
    {
        $cartItems = Cart::with('medicine')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = Address::where('user_id', Auth::id())->latest()->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        return view('customer.checkout.index', compact('cartItems', 'addresses', 'subtotal'));
    }

    /**
     * Place the order: validate stock, snapshot order items, decrement stock, clear cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => ['required', 'exists:addresses,id'],
            'delivery_option' => ['required', 'in:standard,express'],
            'payment_method' => ['required', 'in:cod,bkash,sslcommerz'],
        ]);

        $address = Address::where('user_id', Auth::id())->findOrFail($validated['address_id']);

        $cartItems = Cart::with('medicine')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Verify stock is still sufficient for every item before committing anything.
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->medicine->stock) {
                return redirect()->route('cart.index')
                    ->with('error', $item->medicine->name.' no longer has enough stock. Please update your cart.');
            }
        }

        $order = DB::transaction(function () use ($cartItems, $address, $validated) {
            $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
            $deliveryFee = self::DELIVERY_FEES[$validated['delivery_option']];

            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address->id,
                'recipient_name' => $address->name,
                'recipient_phone' => $address->phone,
                'shipping_address' => $address->address,
                'shipping_city' => $address->city,
                'delivery_option' => $validated['delivery_option'],
                'delivery_fee' => $deliveryFee,
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + $deliveryFee,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item->medicine_id,
                    'unit' => $item->unit ?: $item->medicine->unit,
                    'tablets_per_strip' => $item->tablets_per_strip ?: $item->medicine->tablets_per_strip,
                    'medicine_name' => $item->medicine->name,
                    'price' => $item->medicine->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Reduce stock now that the order is confirmed.
                $item->medicine->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();

            return $order;
        });

        if ($validated['payment_method'] === 'sslcommerz') {
            return redirect()->route('payment.initiate', $order);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order #'.$order->id.' placed successfully!');
    }
}
