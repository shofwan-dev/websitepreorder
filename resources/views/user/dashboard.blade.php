@extends('layouts.app')

@section('title', 'Dashboard - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi'))

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="mb-4 mb-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h1 class="h2 fw-bold mb-2" style="color: #8b6b2d; font-family: 'Georgia', serif;">
                        <i class="fas fa-hand-peace me-2"></i> Assalamu'alaikum, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-muted mb-0">Selamat datang di dashboard PO Kaligrafi Anda</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('user.orders.create') }}" class="btn btn-lg fw-semibold text-white border-0"
                       style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                        <i class="fas fa-plus-circle me-2"></i> Ikut PO Baru
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-3 g-md-4 mb-4 mb-md-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #d4a017;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(212, 160, 23, 0.1);">
                                <i class="fas fa-shopping-cart fs-4" style="color: #d4a017;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">PO Aktif</p>
                                <h3 class="fw-bold mb-0" style="color: #8b6b2d;">{{ $stats['active_orders'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                <i class="fas fa-history fs-4" style="color: #0d6efd;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total PO</p>
                                <h3 class="fw-bold mb-0" style="color: #0d6efd;">{{ $stats['total_orders'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                <i class="fas fa-check-circle fs-4" style="color: #198754;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Selesai</p>
                                <h3 class="fw-bold mb-0" style="color: #198754;">{{ $stats['completed_orders'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6f42c1;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(111, 66, 193, 0.1);">
                                <i class="fas fa-wallet fs-4" style="color: #6f42c1;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total Belanja</p>
                                <h3 class="fw-bold mb-0" style="color: #6f42c1; font-size: 1.25rem;">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row g-4">
            <!-- Active PO Section -->
            <div class="col-lg-8">
                <!-- PO Aktif Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                                <i class="fas fa-bolt me-2"></i> PO Aktif Anda
                            </h5>
                            <span class="badge rounded-pill" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                {{ count($activeOrders ?? []) }} Active
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($activeOrders ?? [] as $order)
                        <div class="card border mb-3 border-hover" style="border-color: #f0e6d2;">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                    <div class="mb-2 mb-md-0">
                                        <h6 class="fw-bold mb-1">{{ $order->product->name ?? 'Produk N/A' }}</h6>
                                        <p class="text-muted small mb-0">
                                            Order #{{ $order->id }} • {{ $order->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['icon' => 'clock', 'class' => 'warning', 'text' => 'Menunggu'],
                                            'confirmed' => ['icon' => 'check', 'class' => 'info', 'text' => 'Dikonfirmasi'],
                                            'processing' => ['icon' => 'cog', 'class' => 'primary', 'text' => 'Diproses'],
                                            'production' => ['icon' => 'hammer', 'class' => 'success', 'text' => 'Produksi'],
                                            'shipping' => ['icon' => 'truck', 'class' => 'info', 'text' => 'Pengiriman'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['icon' => 'question', 'class' => 'secondary', 'text' => ucfirst($order->status)];
                                    @endphp
                                    <span class="badge bg-{{ $config['class'] }} bg-opacity-10 text-{{ $config['class'] }} border border-{{ $config['class'] }} border-opacity-25">
                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i> {{ $config['text'] }}
                                    </span>
                                </div>
                                
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                    <div class="mb-2 mb-md-0">
                                        <span class="text-muted small">
                                            <strong>Qty:</strong> {{ $order->quantity }} pcs | 
                                            <strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline" style="border-color: #d4a017; color: #d4a017;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x mb-3" style="color: #d4a017; opacity: 0.5;"></i>
                            <p class="text-muted">Belum ada pesanan aktif</p>
                            <a href="{{ route('user.orders.create') }}" class="btn text-white" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                <i class="fas fa-plus me-2"></i> Ikut PO Sekarang
                            </a>
                        </div>
                        @endforelse

                        @if(count($activeOrders ?? []) > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline" style="border-color: #d4a017; color: #d4a017;">
                                <i class="fas fa-list me-1"></i> Lihat Semua Pesanan
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white fs-3 fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                        <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>
                        
                        <div class="list-group list-group-flush">
                            <a href="{{ route('profile.edit') }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(212, 160, 23, 0.1);">
                                    <i class="fas fa-user-edit" style="color: #d4a017;"></i>
                                </div>
                                <span>Edit Profil</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                            <a href="{{ route('user.orders.index') }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="fas fa-shopping-bag" style="color: #0d6efd;"></i>
                                </div>
                                <span>Riwayat PO</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                            <a href="{{ route('user.orders.create') }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                    <i class="fas fa-plus-circle" style="color: #198754;"></i>
                                </div>
                                <span>Buat Order Baru</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="card border-0 text-white overflow-hidden" 
                     style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4">Statistik Anda</h5>
                        
                        <div class="mb-4">
                            <p class="small opacity-90 mb-1">Total Pengeluaran</p>
                            <p class="h4 fw-bold mb-0">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="small opacity-90 mb-1">Member Sejak</p>
                            <p class="h5 fw-semibold mb-0">{{ Auth::user()->created_at->format('F Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="small opacity-90 mb-2">Status</p>
                            <span class="badge bg-white text-dark px-3 py-2">
                                <i class="fas fa-user me-2" style="color: #d4a017;"></i> Member Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card.border-hover:hover {
        border-color: #d4a017 !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .list-group-item-action:hover {
        background-color: rgba(212, 160, 23, 0.05) !important;
        color: #8b6b2d !important;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
        transition: all 0.3s ease;
    }
</style>
@endsection
