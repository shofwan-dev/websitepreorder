@extends('layouts.app')

@section('title', 'Pesanan Saya - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi'))

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1" style="color: #8b6b2d;">
                    <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                </h1>
                <p class="text-muted mb-0">Riwayat dan status pesanan Anda</p>
            </div>
            <a href="{{ route('user.orders.create') }}" class="btn text-white" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                <i class="fas fa-plus me-2"></i> Order Baru
            </a>
        </div>

        <!-- Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('user.orders.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="production" {{ request('status') == 'production' ? 'selected' : '' }}>Produksi</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Pengiriman</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @forelse($orders as $order)
                <div class="border-bottom p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <h6 class="fw-bold mb-0 me-3">{{ $order->product->name ?? 'Produk N/A' }}</h6>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'confirmed' => 'info',
                                        'processing' => 'primary',
                                        'production' => 'success',
                                        'shipping' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">
                                Order #{{ $order->id }} • {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                            <div class="row small">
                                <div class="col-auto">
                                    <strong>Jumlah:</strong> {{ $order->quantity }} pcs
                                </div>
                                <div class="col-auto">
                                    <strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </div>
                                <div class="col-auto">
                                    <strong>Pembayaran:</strong>
                                    @if($order->payment_status == 'paid')
                                        <span class="text-success">Lunas</span>
                                    @elseif($order->payment_status == 'partial')
                                        <span class="text-warning">Sebagian</span>
                                    @else
                                        <span class="text-danger">Belum Bayar</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x mb-3" style="color: #d4a017; opacity: 0.5;"></i>
                    <h5>Belum Ada Pesanan</h5>
                    <p class="text-muted">Anda belum memiliki pesanan. Yuk ikut PO sekarang!</p>
                    <a href="{{ route('user.orders.create') }}" class="btn text-white" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                        <i class="fas fa-plus me-2"></i> Ikut PO Sekarang
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
