<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's orders stats
        $stats = [
            'active_orders' => Order::where('user_id', $user->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'completed_orders' => Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
        ];

        // Get active orders
        $activeOrders = Order::with(['product', 'batch'])
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent activities (based on order updates)
        $recentOrders = Order::with(['product'])
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'activeOrders', 'recentOrders'));
    }
}
