<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductionManagerController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

// User Controllers
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController as UserOrderController;

// ============================================================================
// PUBLIC ROUTES (Tanpa Authentication)
// ============================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');
Route::get('/cara-kerja', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::get('/refund-policy', [HomeController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-conditions');

// Product detail (public)
Route::get('/produk/{product}', [HomeController::class, 'productDetail'])->name('product.detail');


// Order tracking (public - tanpa login)
Route::get('/track/{order}', [UserOrderController::class, 'track'])->name('order.tracking');

// iPaymu Callback Routes (public - untuk notifikasi dari iPaymu)
Route::post('/ipaymu/callback', [App\Http\Controllers\IPaymuCallbackController::class, 'callback'])->name('ipaymu.callback');
Route::get('/ipaymu/return', [App\Http\Controllers\IPaymuCallbackController::class, 'return'])->name('ipaymu.return');
Route::get('/ipaymu/cancel', [App\Http\Controllers\IPaymuCallbackController::class, 'cancel'])->name('ipaymu.cancel');

// ============================================================================
// AUTH ROUTES
// ============================================================================

require __DIR__.'/auth.php';

// ============================================================================
// PROTECTED ROUTES - Semua User yang Login
// ============================================================================

Route::middleware(['auth'])->group(function () {
    
    // Dashboard routing berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Redirect admin/manager ke admin dashboard
        if ($user->isAdminOrManager()) {
            return redirect()->route('admin.dashboard');
        }
        
        // User regular ke user dashboard
        return redirect()->route('user.dashboard');
    })->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================================
// USER ROUTES (Customer Dashboard)
// ============================================================================

Route::middleware(['auth'])->prefix('my')->name('user.')->group(function () {
    
    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // User Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [UserOrderController::class, 'index'])->name('index');
        Route::get('/create', [UserOrderController::class, 'create'])->name('create');
        Route::post('/', [UserOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [UserOrderController::class, 'show'])->name('show');
    });
});

// Legacy routes - redirect ke routes baru
Route::middleware(['auth'])->group(function () {
    Route::get('/order/create', function () {
        return redirect()->route('user.orders.create');
    })->name('order.create');
    
    Route::get('/my-orders', function () {
        return redirect()->route('user.orders.index');
    })->name('my-orders');
    
    Route::get('/my-orders/{id}', function ($id) {
        return redirect()->route('user.orders.show', $id);
    })->name('user.order.detail');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // WhatsApp Test
    Route::get('/whatsapp-test', function () {
        return view('admin.whatsapp-test');
    })->name('whatsapp-test');
    
    // ========== PRODUCTION MANAGER ==========
    Route::prefix('production')->name('production.')->group(function () {
        Route::get('/manager', function () {
            return view('admin.production-manager-livewire');
        })->name('manager');
        
        Route::get('/batches', function () {
            return view('admin.production-batches-livewire');
        })->name('batches');
        
        Route::get('/orders', function () {
            return view('admin.production-orders-livewire');
        })->name('orders');
        
        Route::get('/reports', function () {
            return view('admin.production-reports-livewire');
        })->name('reports');
    });
    
    // ========== USER MANAGEMENT (Admin Only) ==========
    Route::middleware(['role:admin'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // ========== PRODUCT MANAGEMENT ==========
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('toggle-active');
    });
    
    // ========== ORDER MANAGEMENT ==========
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/pending', [AdminOrderController::class, 'pending'])->name('pending');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
        Route::put('/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::post('/{order}/send-notification', [AdminOrderController::class, 'sendNotification'])->name('send-notification');
    });
    
    // ========== BATCH MANAGEMENT ==========
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [ProductionManagerController::class, 'batches'])->name('index');
        Route::get('/create', function () {
            return view('admin.batches.create');
        })->name('create');
        Route::post('/', [ProductionManagerController::class, 'createBatch'])->name('store');
        Route::get('/{batch}', [ProductionManagerController::class, 'batchDetail'])->name('show');
        Route::put('/{batch}/status', [ProductionManagerController::class, 'updateBatchStatus'])->name('update-status');
        Route::put('/{batch}', [ProductionManagerController::class, 'update'])->name('update');
        Route::delete('/{batch}', [ProductionManagerController::class, 'destroy'])->name('destroy');
    });
    
    // ========== SETTINGS ==========
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');
        
        Route::get('/website', [AdminSettingController::class, 'website'])->name('website');
        Route::put('/website', [AdminSettingController::class, 'updateWebsite'])->name('website.update');
        
        Route::get('/payment', [AdminSettingController::class, 'payment'])->name('payment');
        Route::put('/payment', [AdminSettingController::class, 'updatePayment'])->name('payment.update');
        Route::get('/payment/test', [AdminSettingController::class, 'testPayment'])->name('payment.test');
        
        Route::get('/whatsapp', [AdminSettingController::class, 'whatsapp'])->name('whatsapp');
        Route::put('/whatsapp', [AdminSettingController::class, 'updateWhatsapp'])->name('whatsapp.update');
        
        Route::get('/content', [AdminSettingController::class, 'content'])->name('content');
        Route::put('/content', [AdminSettingController::class, 'updateContent'])->name('content.update');
    });
});

// ============================================================================
// ADMIN API ROUTES
// ============================================================================

Route::middleware(['auth', 'role:admin,manager'])->prefix('api/admin')->name('api.admin.')->group(function () {
    
    // WhatsApp API
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/test', function () {
            $service = new WhatsAppService();
            
            $connectionTest = $service->testConnection();
            $result = $service->sendMessage('081234567890', 'Test API WhatsApp dari PO Kaligrafi');
            
            return response()->json([
                'connection_test' => $connectionTest,
                'message_test' => $result,
                'config' => [
                    'api_key_configured' => !empty(config('services.whatsapp.api_key')),
                    'sender_configured' => !empty(config('services.whatsapp.sender')),
                ]
            ]);
        })->name('test');
        
        Route::post('/send-test', function (Request $request) {
            $validated = $request->validate([
                'phone' => 'required|string',
                'message' => 'required|string'
            ]);
            
            $service = new WhatsAppService();
            $result = $service->sendMessage($validated['phone'], $validated['message']);
            
            return response()->json($result);
        })->name('send-test');
        
        Route::post('/test-production', function (Request $request) {
            $validated = $request->validate([
                'stage' => 'required|in:po_open,waiting_quota,production,qc,packaging,shipping,delivered'
            ]);
            
            $dummyOrder = (object) [
                'id' => 999,
                'customer_name' => 'Test Customer',
                'customer_phone' => '081234567890',
                'customer_city' => 'Jakarta',
                'product' => (object) [
                    'name' => 'Kaligrafi Lampu Test',
                    'min_quota' => 10,
                    'paid_orders_count' => 7
                ]
            ];
            
            $service = new WhatsAppService();
            $result = $service->sendProductionUpdate($dummyOrder, $validated['stage'], 'RESI123456789');
            
            $messagePreview = strlen($result['message_preview'] ?? '') > 100 
                ? substr($result['message_preview'], 0, 100) . '...' 
                : ($result['message_preview'] ?? '');
            
            return response()->json([
                'success' => $result['success'] ?? false,
                'to' => $result['to'] ?? '081234567890',
                'message_preview' => $messagePreview,
                'stage' => $validated['stage']
            ]);
        })->name('test-production');
    });
    
    // Production API
    Route::prefix('production')->name('production.')->group(function () {
        Route::get('/stats', [ProductionManagerController::class, 'getStats'])->name('stats');
        Route::get('/batches', [ProductionManagerController::class, 'getBatches'])->name('batches');
        Route::get('/orders/latest', [ProductionManagerController::class, 'getLatestOrders'])->name('orders.latest');
        Route::post('/notify-customers', [ProductionManagerController::class, 'notifyCustomers'])->name('notify-customers');
        Route::post('/update-batch-status/{id}', [ProductionManagerController::class, 'updateBatchStatus'])->name('update-batch-status');
        Route::post('/update-order-status/{id}', [ProductionManagerController::class, 'updateOrderStatus'])->name('update-order-status');
    });
    
    // Dashboard Stats API
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/stats', function () {
            $stats = [
                'users' => [
                    'total' => \App\Models\User::count(),
                    'admins' => \App\Models\User::where('role', 'admin')->count(),
                    'managers' => \App\Models\User::where('role', 'manager')->count(),
                    'regular' => \App\Models\User::where('role', 'user')->count(),
                ],
                'products' => [
                    'total' => \App\Models\Product::count(),
                    'active' => \App\Models\Product::where('is_active', true)->count(),
                ],
                'orders' => [
                    'total' => \App\Models\Order::count(),
                    'pending' => \App\Models\Order::where('status', 'pending')->count(),
                    'completed' => \App\Models\Order::where('status', 'completed')->count(),
                    'revenue' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
                ],
                'batches' => [
                    'total' => \App\Models\Batch::count(),
                    'active' => \App\Models\Batch::whereNotIn('status', ['completed', 'cancelled'])->count(),
                    'completed' => \App\Models\Batch::where('status', 'completed')->count(),
                ]
            ];
            
            return response()->json($stats);
        })->name('stats');
    });
});

// ============================================================================
// PUBLIC API ROUTES
// ============================================================================

Route::prefix('api')->group(function () {
    // Public product listing
    Route::get('/products', function () {
        $products = \App\Models\Product::where('is_active', true)
            ->select('id', 'name', 'description', 'price', 'min_quota', 'current_quota', 'images')
            ->get();
        
        return response()->json($products);
    })->name('api.products');
    
    // Public product detail
    Route::get('/products/{id}', function ($id) {
        $product = \App\Models\Product::where('is_active', true)
            ->select('id', 'name', 'description', 'price', 'min_quota', 'current_quota', 'images', 'specifications')
            ->findOrFail($id);
        
        return response()->json($product);
    })->name('api.products.show');
    
    // Check order status (public)
    Route::get('/orders/{id}/status', function ($id) {
        $order = \App\Models\Order::select('id', 'status', 'payment_status', 'created_at')
            ->find($id);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
        ]);
    })->name('api.orders.status');
});

// ============================================================================
// FALLBACK ROUTE
// ============================================================================

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});