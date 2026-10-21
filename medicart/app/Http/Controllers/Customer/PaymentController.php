<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function initiate(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $successUrl = $this->callbackUrl('SSLCZ_SUCCESS_URL', 'payment.success', $order);
        $failUrl = $this->callbackUrl('SSLCZ_FAIL_URL', 'payment.fail', $order);
        $cancelUrl = $this->callbackUrl('SSLCZ_CANCEL_URL', 'payment.cancel', $order);
        $ipnUrl = $this->callbackUrl('SSLCZ_IPN_URL', 'payment.ipn', $order);

        $payload = [
            'store_id' => env('SSLCZ_STORE_ID'),
            'store_passwd' => env('SSLCZ_STORE_PASSWORD'),
            'total_amount' => number_format((float) $order->total_amount, 2, '.', ''),
            'currency' => 'BDT',
            'tran_id' => 'MEDI-' . $order->id . '-' . time(),
            'success_url' => $successUrl,
            'fail_url' => $failUrl,
            'cancel_url' => $cancelUrl,
            'ipn_url' => $ipnUrl,
            'cus_name' => $order->recipient_name,
            'cus_email' => Auth::user()->email,
            'cus_phone' => $order->recipient_phone,
            'cus_add1' => $order->shipping_address,
            'cus_city' => $order->shipping_city,
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'product_name' => 'MediCart Order #'.$order->id,
            'product_category' => 'Healthcare',
            'product_profile' => 'general',
        ];

        $response = Http::asForm()->post($this->gatewayUrl(), $payload);

        if ($response->successful()) {
            $data = $response->json();
            $gatewayUrl = $data['GatewayPageURL'] ?? $data['gatewayPageURL'] ?? null;

            if ($gatewayUrl) {
                return redirect()->away($gatewayUrl);
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('error', 'SSLCommerz could not initialize the payment. Please try again.');
    }

    public function success(Request $request, Order $order)
    {
        $order->update(['status' => 'processing']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Payment successful. Your order is now being processed.');
    }

    public function fail(Request $request, Order $order)
    {
        $order->update(['status' => 'pending']);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment failed. Please try again.');
    }

    public function cancel(Request $request, Order $order)
    {
        $order->update(['status' => 'pending']);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment cancelled.');
    }

    public function ipn(Request $request, Order $order)
    {
        $status = $request->input('status');

        if ($status === 'VALID') {
            $order->update(['status' => 'processing']);
        }

        return response()->json(['success' => true]);
    }

    protected function gatewayUrl(): string
    {
        return env('SSLCZ_SANDBOX', true)
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
    }

    protected function callbackUrl(string $envKey, string $routeName, Order $order): string
    {
        $configured = env($envKey);

        if ($configured && str_contains($configured, '{order}')) {
            return str_replace('{order}', (string) $order->id, $configured);
        }

        if ($configured && !str_contains($configured, '/'.$order->id)) {
            return route($routeName, $order);
        }

        return $configured ?: route($routeName, $order);
    }
}
