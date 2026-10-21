<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard stats: totals, recent orders, and low-stock alerts.
     */
    public function index()
    {
        $revenueMode = request('revenue', 'monthly');

        $collectedRevenue = (float) Order::where(function ($query) {
            $query->whereIn('payment_method', ['sslcommerz', 'bkash'])
                ->orWhere(function ($codQuery) {
                    $codQuery->where('payment_method', 'cod')
                        ->where('status', 'delivered');
                });
        })->where('status', '!=', 'cancelled')->sum('total_amount');
        $allOrdersRevenue = $collectedRevenue;
        $pendingCodRevenue = (float) Order::where('payment_method', 'cod')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthlyCollectedRevenue = (float) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where(function ($query) {
                $query->whereIn('payment_method', ['sslcommerz', 'bkash'])
                    ->orWhere(function ($codQuery) {
                        $codQuery->where('payment_method', 'cod')
                            ->where('status', 'delivered');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $yearlyCollectedRevenue = (float) Order::whereYear('created_at', now()->year)
            ->where(function ($query) {
                $query->whereIn('payment_method', ['sslcommerz', 'bkash'])
                    ->orWhere(function ($codQuery) {
                        $codQuery->where('payment_method', 'cod')
                            ->where('status', 'delivered');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $monthlyPendingCodRevenue = (float) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_method', 'cod')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $yearlyPendingCodRevenue = (float) Order::whereYear('created_at', now()->year)
            ->where('payment_method', 'cod')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $stats = [
            'total_medicines' => Medicine::count(),
            'total_orders' => Order::count(),
            'total_revenue' => $allOrdersRevenue,
            'collected_revenue' => $collectedRevenue,
            'pending_cod_revenue' => $revenueMode === 'yearly' ? $yearlyPendingCodRevenue : $monthlyPendingCodRevenue,
            'monthly_revenue' => $monthlyCollectedRevenue,
            'yearly_revenue' => $yearlyCollectedRevenue,
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue_mode' => $revenueMode,
            'current_revenue' => $revenueMode === 'yearly' ? $yearlyCollectedRevenue : $monthlyCollectedRevenue,
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $lowStockMedicines = Medicine::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(8)
            ->get();
 $highStockMedicine = Medicine::where('stock', '>=', 20)
            ->orderBy('stock')
            ->take(8)
            ->get();
        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockMedicines', 'highStockMedicine'));
    }
}
