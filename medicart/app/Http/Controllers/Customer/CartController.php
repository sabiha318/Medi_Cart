<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('medicine.category')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        return view('customer.cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        if (! $medicine->isInStock()) {
            return back()->with('error', $medicine->name.' is currently out of stock.');
        }

        $existing = Cart::where('user_id', Auth::id())
            ->where('medicine_id', $medicine->id)
            ->first();

        $newQuantity = $existing ? $existing->quantity + $quantity : $quantity;
        $newQuantity = min($newQuantity, $medicine->stock);

        Cart::updateOrCreate(
            ['user_id' => Auth::id(), 'medicine_id' => $medicine->id],
            [
                'quantity' => $newQuantity,
                'unit' => $medicine->unit,
                'tablets_per_strip' => $medicine->tablets_per_strip,
            ]
        );

        return back()->with('success', $medicine->name.' added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = min($validated['quantity'], $cart->medicine->stock);

        $cart->update(['quantity' => $quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
