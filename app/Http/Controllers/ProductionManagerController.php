<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Batch;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductionManagerController extends Controller
{
    /**
     * Display the production manager dashboard.
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentBatches = Batch::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $pendingOrders = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'delivered')
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('production-manager.index', compact('stats', 'recentBatches', 'pendingOrders'));
    }
    
    /**
     * Display all production batches.
     */
    public function batches()
    {
        $batches = Batch::with(['product', 'orders'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $products = Product::where('is_active', true)->get();
        
        return view('production-manager.batches', compact('batches', 'products'));
    }
    
    /**
     * Display batch details.
     */
    public function batchDetail($id)
    {
        $batch = Batch::with(['product', 'orders.customer'])
            ->findOrFail($id);
            
        return view('production-manager.batch-detail', compact('batch'));
    }
    
    /**
     * Update batch status.
     */
    public function updateBatchStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:planning,collecting,production,qc,packaging,shipping,completed,cancelled'
        ]);
        
        $batch = Batch::findOrFail($id);
        $oldStatus = $batch->status;
        $batch->status = $request->status;
        $batch->save();
        
        // Send WhatsApp notifications if status changed to production stages
        if (in_array($request->status, ['production', 'qc', 'packaging', 'shipping', 'completed'])) {
            $this->notifyBatchCustomers($batch, $request->status);
        }
        
        return redirect()->back()
            ->with('success', 'Status batch berhasil diperbarui dari ' . $oldStatus . ' menjadi ' . $request->status);
    }
    
    /**
     * Create a new production batch.
     */
    public function createBatch(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|unique:batches,batch_number',
            'target_quantity' => 'required|integer|min:1',
            'production_start_date' => 'required|date',
            'estimated_completion_date' => 'required|date|after:production_start_date'
        ]);
        
        $batch = Batch::create([
            'product_id' => $request->product_id,
            'batch_number' => $request->batch_number,
            'target_quantity' => $request->target_quantity,
            'current_quantity' => 0,
            'status' => 'planning',
            'production_start_date' => $request->production_start_date,
            'estimated_completion_date' => $request->estimated_completion_date,
            'created_by' => Auth::id()
        ]);
        
        // Perbaikan di sini: route 'production.batch.detail' mungkin belum ada
        // Ganti dengan redirect yang sesuai
        return redirect()->route('production.batches')
            ->with('success', 'Batch produksi berhasil dibuat');
        
        // Atau jika Anda ingin redirect ke detail batch:
        // return redirect()->route('production.batch.detail', ['id' => $batch->id])
        //     ->with('success', 'Batch produksi berhasil dibuat');
    }
    
    /**
     * Display all orders for production management.
     */
    public function orders()
    {
        $orders = Order::with(['product', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('production-manager.orders', compact('orders'));
    }
    
    /**
     * Display pending orders.
     */
    public function pendingOrders()
    {
        $orders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing'])
            ->with(['product', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('production-manager.orders-pending', compact('orders'));
    }
    
    /**
     * Display order details.
     */
    public function orderDetail($id)
    {
        $order = Order::with(['product', 'customer', 'batch'])
            ->findOrFail($id);
            
        return view('production-manager.order-detail', compact('order'));
    }
    
    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready_to_ship,shipped,delivered,cancelled'
        ]);
        
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();
        
        // Send WhatsApp notification
        if ($request->status !== $oldStatus) {
            $this->sendOrderStatusUpdate($order, $request->status);
        }
        
        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui');
    }
    
    /**
     * Send order update to customer via WhatsApp.
     */
    public function sendOrderUpdate(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'stage' => 'nullable|in:po_open,waiting_quota,production,qc,packaging,shipping,delivered'
        ]);
        
        $order = Order::with('customer')->findOrFail($id);
        $whatsappService = new WhatsAppService();
        
        if ($request->has('stage')) {
            $result = $whatsappService->sendProductionUpdate($order, $request->stage);
        } else {
            $result = $whatsappService->sendMessage(
                $order->customer->phone,
                $request->message
            );
        }
        
        if ($result['success'] ?? false) {
            return redirect()->back()
                ->with('success', 'Update berhasil dikirim ke pelanggan via WhatsApp');
        } else {
            return redirect()->back()
                ->with('error', 'Gagal mengirim update: ' . ($result['error'] ?? 'Unknown error'));
        }
    }
    
    /**
     * Display reports dashboard.
     */
    public function reports()
    {
        return view('production-manager.reports');
    }
    
    /**
     * Display production report.
     */
    public function productionReport()
    {
        $startDate = request('start_date', Carbon::now()->subMonth());
        $endDate = request('end_date', Carbon::now());
        
        $productionData = Batch::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_batches'),
                DB::raw('SUM(target_quantity) as total_target'),
                'status'
            )
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('production-manager.reports-production', compact('productionData', 'startDate', 'endDate'));
    }
    
    /**
     * Display sales report.
     */
    public function salesReport()
    {
        $startDate = request('start_date', Carbon::now()->subMonth());
        $endDate = request('end_date', Carbon::now());
        
        $salesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue'),
                'status'
            )
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('production-manager.reports-sales', compact('salesData', 'startDate', 'endDate'));
    }
    
    /**
     * Export report.
     */
    public function exportReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:production,sales',
            'format' => 'required|in:csv,excel,pdf'
        ]);
        
        // Logic untuk export report
        // ...
        
        return back()->with('success', 'Laporan berhasil diekspor');
    }
    
    /**
     * Display WhatsApp automation page.
     */
    public function whatsappAutomation()
    {
        $templates = [
            'production_start' => 'Pesanan Anda sudah mulai diproduksi',
            'qc_passed' => 'Pesanan Anda telah lolos quality control',
            'shipped' => 'Pesanan Anda telah dikirim',
            'delivered' => 'Pesanan Anda telah sampai'
        ];
        
        return view('production-manager.whatsapp-automation', compact('templates'));
    }
    
    /**
     * Send bulk WhatsApp messages.
     */
    public function sendBulkWhatsApp(Request $request)
    {
        $request->validate([
            'template' => 'required|string',
            'batch_ids' => 'nullable|array',
            'order_ids' => 'nullable|array',
            'custom_message' => 'nullable|string'
        ]);
        
        // Logic untuk mengirim WhatsApp ke banyak pelanggan
        // ...
        
        return back()->with('success', 'WhatsApp berhasil dikirim ke pelanggan');
    }
    
    /**
     * Display WhatsApp templates.
     */
    public function whatsappTemplates()
    {
        return view('production-manager.whatsapp-templates');
    }
    
    /**
     * Save WhatsApp template.
     */
    public function saveWhatsAppTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|in:production_update,payment_reminder,general'
        ]);
        
        // Logic untuk menyimpan template
        // ...
        
        return back()->with('success', 'Template berhasil disimpan');
    }
    
    /**
     * Get dashboard statistics (API).
     */
    public function getStats()
    {
        $stats = $this->getDashboardStats();
        return response()->json($stats);
    }
    
    /**
     * Get batches (API).
     */
    public function getBatches(Request $request)
    {
        $status = $request->get('status');
        
        $query = Batch::with('product');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $batches = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json($batches);
    }
    
    /**
     * Get latest orders (API).
     */
    public function getLatestOrders()
    {
        $orders = Order::with(['product', 'customer'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json($orders);
    }
    
    /**
     * Notify customers (API).
     */
    public function notifyCustomers(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'message' => 'required|string'
        ]);
        
        $batch = Batch::with('orders.customer')->findOrFail($request->batch_id);
        $whatsappService = new WhatsAppService();
        $successCount = 0;
        $failCount = 0;
        
        foreach ($batch->orders as $order) {
            if ($order->customer && $order->customer->phone) {
                $result = $whatsappService->sendMessage(
                    $order->customer->phone,
                    $request->message
                );
                
                if ($result['success'] ?? false) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Notifikasi berhasil dikirim ke $successCount pelanggan, gagal: $failCount"
        ]);
    }
    
    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats()
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_batches' => Batch::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'todays_orders' => Order::whereDate('created_at', today())->count(),
            'completion_rate' => $this->calculateCompletionRate(),
        ];
    }
    
    /**
     * Calculate production completion rate.
     */
    private function calculateCompletionRate()
    {
        $totalBatches = Batch::count();
        $completedBatches = Batch::where('status', 'completed')->count();
        
        return $totalBatches > 0 ? ($completedBatches / $totalBatches) * 100 : 0;
    }
    
    /**
     * Send order status update via WhatsApp.
     */
    private function sendOrderStatusUpdate($order, $newStatus)
    {
        $whatsappService = new WhatsAppService();
        
        $statusMessages = [
            'processing' => 'Pesanan Anda sedang diproses',
            'ready_to_ship' => 'Pesanan Anda siap dikirim',
            'shipped' => 'Pesanan Anda telah dikirim',
            'delivered' => 'Pesanan Anda telah sampai'
        ];
        
        if (isset($statusMessages[$newStatus]) && $order->customer && $order->customer->phone) {
            $message = "Assalamu'alaikum " . $order->customer->name . ",\n\n";
            $message .= $statusMessages[$newStatus] . "\n";
            $message .= "No. Pesanan: #" . $order->id . "\n";
            $message .= "Produk: " . ($order->product->name ?? 'N/A') . "\n\n";
            $message .= "Terima kasih telah memesan di PO Kaligrafi Lampu.";
            
            $whatsappService->sendMessage($order->customer->phone, $message);
        }
    }
    
    /**
     * Notify batch customers about status change.
     */
    private function notifyBatchCustomers($batch, $newStatus)
    {
        $statusMessages = [
            'production' => 'Pesanan Anda sudah mulai diproduksi',
            'qc' => 'Pesanan Anda sedang dalam quality control',
            'packaging' => 'Pesanan Anda sedang dikemas',
            'shipping' => 'Pesanan Anda sedang dikirim',
            'completed' => 'Pesanan Anda telah selesai diproduksi'
        ];
        
        if (isset($statusMessages[$newStatus])) {
            $whatsappService = new WhatsAppService();
            
            foreach ($batch->orders as $order) {
                if ($order->customer && $order->customer->phone) {
                    $whatsappService->sendProductionUpdate($order, $newStatus);
                }
            }
        }
    }
}