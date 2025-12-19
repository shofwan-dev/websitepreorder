<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['product', 'batch'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create(Request $request)
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get product_id from query parameter if exists
        $selectedProductId = $request->query('product_id');
        $selectedProduct = null;
        
        if ($selectedProductId) {
            $selectedProduct = Product::where('is_active', true)
                ->find($selectedProductId);
        }

        return view('user.orders.create', compact('products', 'selectedProduct'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_address' => ['required', 'string', 'max:500'],
            'customer_city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price' => $product->price,
            'amount' => $product->price * $validated['quantity'],
            'total_amount' => $product->price * $validated['quantity'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'customer_city' => $validated['customer_city'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // TODO: Update product quota jika kolom current_quota sudah ditambahkan
        // $product->increment('current_quota', $validated['quantity']);

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Order berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        $order->load(['product', 'batch']);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Track order (public access with order ID)
     */
    public function track($orderId)
    {
        $order = Order::with(['product', 'batch'])->findOrFail($orderId);

        return view('user.orders.track', compact('order'));
    }
}
