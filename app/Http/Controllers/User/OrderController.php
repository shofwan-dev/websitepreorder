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
            'province_id' => ['required', 'string'],
            'province_name' => ['required', 'string'],
            'city_id' => ['required', 'string'],
            'city_name' => ['required', 'string'],
            'courier' => ['required', 'string'],
            'courier_service' => ['required', 'string'],
            'applied_free_shipping_code' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Calculate Shipping on Backend
        $shippingCost = 0;
        $rajaOngkir = app(\App\Services\RajaOngkirService::class);
        $origin = $rajaOngkir->getOriginCityId();
        
        $weight = 1000 * $validated['quantity']; // 1kg per item
        $costResult = $rajaOngkir->calculateCost($origin, $validated['city_id'], $weight, $validated['courier']);
        
        if ($costResult['success']) {
            // Check if this is Komerce API response (flat structure)
            if (isset($costResult['data'][0]['cost'])) {
                // Komerce API structure
                foreach ($costResult['data'] as $service) {
                    if ($service['service'] == $validated['courier_service']) {
                        $shippingCost = $service['cost'];
                        break;
                    }
                }
            } elseif (isset($costResult['data'][0]['costs'])) {
                // Official RajaOngkir structure
                foreach ($costResult['data'][0]['costs'] as $service) {
                    if ($service['service'] == $validated['courier_service']) {
                        $shippingCost = $service['cost'][0]['value'];
                        break;
                    }
                }
            }
        }

        // Apply Free Shipping Code if valid
        $discountAmount = 0;
        $activeShippingCode = \App\Models\Setting::getValue('free_shipping_code', 'website');
        
        if (!empty($validated['applied_free_shipping_code']) && 
            !empty($activeShippingCode) && 
            strtoupper($validated['applied_free_shipping_code']) === strtoupper($activeShippingCode)) {
            $discountAmount = $shippingCost;
        }

        $orderAmount = $product->price * $validated['quantity'];
        $totalAmount = $orderAmount + $shippingCost - $discountAmount;

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price' => $product->price,
            'amount' => $orderAmount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'customer_city' => $validated['city_name'],
            'province_id' => $validated['province_id'],
            'province_name' => $validated['province_name'],
            'city_id' => $validated['city_id'],
            'city_name' => $validated['city_name'],
            'courier' => $validated['courier'],
            'courier_service' => $validated['courier_service'],
            'free_shipping_code' => $discountAmount > 0 ? strtoupper($validated['applied_free_shipping_code']) : null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // TODO: Update product quota jika kolom current_quota sudah ditambahkan
        // $product->increment('current_quota', $validated['quantity']);

        // Send WhatsApp notification to customer
        try {
            $whatsapp = app(\App\Services\WhatsAppService::class);
            $whatsapp->sendOrderCreatedNotification($order);
            
            \Log::info('WhatsApp notification sent for new order', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            // Log error but don't stop the order creation process
            \Log::error('Failed to send WhatsApp notification for order creation', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

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
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        // Ensure user can only edit their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Only allow editing if order is still pending payment
        if ($order->payment_status !== 'pending' || $order->status === 'cancelled') {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Order yang sudah dibayar atau dibatalkan tidak dapat diubah.');
        }

        $products = Product::where('is_active', true)->get();
        $selectedProduct = $order->product;

        return view('user.orders.edit', compact('order', 'products', 'selectedProduct'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        // Ensure user can only update their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Only allow updating if order is still pending payment
        if ($order->payment_status !== 'pending' || $order->status === 'cancelled') {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Order yang sudah dibayar atau dibatalkan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_address' => ['required', 'string', 'max:500'],
            'province_id' => ['required', 'string'],
            'province_name' => ['required', 'string'],
            'city_id' => ['required', 'string'],
            'city_name' => ['required', 'string'],
            'courier' => ['required', 'string'],
            'courier_service' => ['required', 'string'],
            'applied_free_shipping_code' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Calculate Shipping on Backend
        $shippingCost = 0;
        $rajaOngkir = app(\App\Services\RajaOngkirService::class);
        $origin = $rajaOngkir->getOriginCityId();
        
        $weight = 1000 * $validated['quantity']; // 1kg per item
        $costResult = $rajaOngkir->calculateCost($origin, $validated['city_id'], $weight, $validated['courier']);
        
        if ($costResult['success']) {
            // Check if this is Komerce API response (flat structure)
            if (isset($costResult['data'][0]['cost'])) {
                // Komerce API structure
                foreach ($costResult['data'] as $service) {
                    if ($service['service'] == $validated['courier_service']) {
                        $shippingCost = $service['cost'];
                        break;
                    }
                }
            } elseif (isset($costResult['data'][0]['costs'])) {
                // Official RajaOngkir structure
                foreach ($costResult['data'][0]['costs'] as $service) {
                    if ($service['service'] == $validated['courier_service']) {
                        $shippingCost = $service['cost'][0]['value'];
                        break;
                    }
                }
            }
        }

        // Apply Free Shipping Code if valid
        $discountAmount = 0;
        $activeShippingCode = \App\Models\Setting::getValue('free_shipping_code', 'website');
        
        if (!empty($validated['applied_free_shipping_code']) && 
            !empty($activeShippingCode) && 
            strtoupper($validated['applied_free_shipping_code']) === strtoupper($activeShippingCode)) {
            $discountAmount = $shippingCost;
        }

        $orderAmount = $product->price * $validated['quantity'];
        $totalAmount = $orderAmount + $shippingCost - $discountAmount;

        $order->update([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'price' => $product->price,
            'amount' => $orderAmount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'customer_city' => $validated['city_name'],
            'province_id' => $validated['province_id'],
            'province_name' => $validated['province_name'],
            'city_id' => $validated['city_id'],
            'city_name' => $validated['city_name'],
            'courier' => $validated['courier'],
            'courier_service' => $validated['courier_service'],
            'free_shipping_code' => $discountAmount > 0 ? strtoupper($validated['applied_free_shipping_code']) : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Order berhasil diperbarui!');
    }

    /**
     * Track order (public access with order ID)
     */
    public function track($orderId)
    {
        $order = Order::with(['product', 'batch'])->findOrFail($orderId);

        return view('user.orders.track', compact('order'));
    }

    /**
     * Process payment for an order
     */
    public function processPayment(Order $order)
    {
        // Ensure user can only pay for their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('user.orders.show', $order)
                ->with('info', 'Order ini sudah dibayar.');
        }

        // Check if order is cancelled
        if ($order->status === 'cancelled') {
            return redirect()
                ->route('user.orders.show', $order)
                ->with('error', 'Order ini sudah dibatalkan.');
        }

        try {
            $ipaymu = app(\App\Services\IPaymuService::class);
            
            // Prepare order data for iPaymu
            $orderData = [
                'product' => [$order->product->name ?? 'Produk'],
                'qty' => [$order->quantity],
                'price' => [$order->price],
                'returnUrl' => route('ipaymu.return'),
                'cancelUrl' => route('ipaymu.cancel'),
                'notifyUrl' => route('ipaymu.callback'),
                'referenceId' => 'ORDER-' . $order->id,
                'buyerName' => $order->customer_name,
                'buyerEmail' => $order->customer_email ?? Auth::user()->email,
                'buyerPhone' => $order->customer_phone,
            ];

            $result = $ipaymu->createPayment($orderData);

            if ($result['success'] && isset($result['data']['Data'])) {
                $paymentData = $result['data']['Data'];
                
                // iPaymu may return TransactionId or use SessionID as identifier
                $transactionId = $paymentData['TransactionId'] ?? $paymentData['SessionID'] ?? null;
                
                \Log::info('Payment data received from iPaymu', [
                    'order_id' => $order->id,
                    'transaction_id' => $transactionId,
                    'session_id' => $paymentData['SessionID'] ?? null,
                    'payment_url' => $paymentData['Url'] ?? null,
                ]);
                
                // Update order with payment information
                $order->update([
                    'ipaymu_transaction_id' => $transactionId,
                    'ipaymu_payment_url' => $paymentData['Url'] ?? null,
                    'ipaymu_session_id' => $paymentData['SessionID'] ?? null,
                    'payment_expired_at' => isset($paymentData['Expired']) ? 
                        \Carbon\Carbon::parse($paymentData['Expired']) : null,
                ]);

                \Log::info('Order updated with payment info', [
                    'order_id' => $order->id,
                    'ipaymu_transaction_id' => $order->ipaymu_transaction_id,
                ]);

                // Redirect to payment page
                if (!empty($paymentData['Url'])) {
                    return redirect($paymentData['Url']);
                }
            }

            // If payment creation failed
            \Log::error('Payment creation failed', [
                'order_id' => $order->id,
                'result' => $result
            ]);
            
            return redirect()
                ->route('user.orders.show', $order)
                ->with('error', 'Gagal membuat pembayaran. ' . ($result['message'] ?? 'Silakan coba lagi.'));

        } catch (\Exception $e) {
            \Log::error('Payment process error: ' . $e->getMessage());
            
            return redirect()
                ->route('user.orders.show', $order)
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }
    
    /**
     * Cancel an order
     */
    public function cancel(Order $order, Request $request)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }
        
        // Check if order can be cancelled
        if ($order->status === 'cancelled') {
            return redirect()
                ->route('user.orders.show', $order)
                ->with('info', 'Order ini sudah dibatalkan sebelumnya.');
        }
        
        // Don't allow cancellation if already paid
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('user.orders.show', $order)
                ->with('error', 'Order yang sudah dibayar tidak dapat dibatalkan. Silakan hubungi admin untuk refund.');
        }
        
        // Validate cancel reason (optional)
        $validated = $request->validate([
            'cancel_reason' => ['nullable', 'string', 'max:500'],
        ]);
        
        // Update order status
        $order->status = 'cancelled';
        $order->cancel_reason = $validated['cancel_reason'] ?? null;
        $order->cancelled_at = now();
        $order->save();
        
        \Log::info('Order cancelled by user', [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $validated['cancel_reason'] ?? 'No reason provided',
        ]);
        
        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Order berhasil dibatalkan.');
    }
}
