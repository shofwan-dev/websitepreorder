<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Batch;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'users' => [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'managers' => User::where('role', 'manager')->count(),
                'regular' => User::where('role', 'user')->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
            ],
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            ],
            'batches' => [
                'total' => Batch::count(),
                'active' => Batch::whereNotIn('status', ['completed', 'cancelled'])->count(),
                'completed' => Batch::where('status', 'completed')->count(),
            ]
        ];

        $recentOrders = Order::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $activeBatches = Batch::with('product')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'activeBatches'));
    }
}
