<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.medicine', 'user', 'address');

        return view('admin.orders.show', compact('order'));
    }

    public function export()
    {
        $orders = Order::with('user')->latest()->get();

        $filename = 'medicart-orders-'.now()->format('Y-m-d-H-i-s').'.csv';

        $csv = fopen('php://temp', 'rb+');
        fputcsv($csv, ['Order ID', 'Customer', 'Recipient Name', 'Phone', 'Shipping Address', 'City', 'Subtotal', 'Delivery Fee', 'Total', 'Status', 'Date']);

        foreach ($orders as $order) {
            fputcsv($csv, [
                $order->id,
                optional($order->user)->name,
                $order->recipient_name,
                $order->recipient_phone,
                $order->shipping_address,
                $order->shipping_city,
                $order->subtotal,
                $order->delivery_fee,
                $order->total_amount,
                $order->status,
                "'".$order->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Order #'.$order->id.' marked as '.$validated['status'].'.');
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order #'.$order->id.' has been cancelled.');
    }
}
