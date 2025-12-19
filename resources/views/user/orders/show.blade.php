@extends('layouts.app')

@section('title', 'Detail Order #' . $order->id . ' - PO Kaligrafi')

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('user.orders.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Pesanan
                </a>
                <h1 class="h3 fw-bold mb-0" style="color: #8b6b2d;">
                    <i class="fas fa-receipt me-2"></i> Order #{{ $order->id }}
                </h1>
            </div>
            @php
                $statusConfig = [
                    'pending' => ['icon' => 'clock', 'class' => 'warning', 'text' => 'Menunggu Konfirmasi'],
                    'confirmed' => ['icon' => 'check', 'class' => 'info', 'text' => 'Dikonfirmasi'],
                    'processing' => ['icon' => 'cog', 'class' => 'primary', 'text' => 'Diproses'],
                    'production' => ['icon' => 'hammer', 'class' => 'success', 'text' => 'Dalam Produksi'],
                    'shipping' => ['icon' => 'truck', 'class' => 'info', 'text' => 'Dalam Pengiriman'],
                    'completed' => ['icon' => 'check-circle', 'class' => 'success', 'text' => 'Selesai'],
                    'cancelled' => ['icon' => 'times-circle', 'class' => 'danger', 'text' => 'Dibatalkan'],
                ];
                $config = $statusConfig[$order->status] ?? ['icon' => 'question', 'class' => 'secondary', 'text' => ucfirst($order->status)];
            @endphp
            <span class="badge bg-{{ $config['class'] }} fs-6 px-3 py-2">
                <i class="fas fa-{{ $config['icon'] }} me-1"></i> {{ $config['text'] }}
            </span>
        </div>

        <div class="row g-4">
            <!-- Order Details -->
            <div class="col-lg-8">
                <!-- Product Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                            <i class="fas fa-box me-2"></i> Detail Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            @if($order->product && $order->product->images && count($order->product->images) > 0)
                                <img src="{{ asset('storage/' . $order->product->images[0]) }}" 
                                     alt="{{ $order->product->name }}" 
                                     class="rounded me-3" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $order->product->name ?? 'Produk N/A' }}</h5>
                                <p class="text-muted small mb-2">{{ Str::limit($order->product->description ?? '', 100) }}</p>
                                <div class="d-flex gap-4">
                                    <div>
                                        <span class="text-muted small">Harga:</span>
                                        <strong>Rp {{ number_format($order->price ?? 0, 0, ',', '.') }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-muted small">Jumlah:</span>
                                        <strong>{{ $order->quantity ?? 1 }} pcs</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                            <i class="fas fa-map-marker-alt me-2"></i> Informasi Pengiriman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Nama Penerima</span>
                                <strong>{{ $order->customer_name ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">No. Telepon</span>
                                <strong>{{ $order->customer_phone ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Kota</span>
                                <strong>{{ $order->customer_city ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Tanggal Order</span>
                                <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                            <div class="col-12">
                                <span class="text-muted small d-block">Alamat Lengkap</span>
                                <strong>{{ $order->customer_address ?? '-' }}</strong>
                            </div>
                        </div>
                        @if($order->notes)
                        <div class="mt-3 p-3 bg-light rounded">
                            <span class="text-muted small d-block mb-1">Catatan:</span>
                            {{ $order->notes }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline (if batch available) -->
                @if($order->batch)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                            <i class="fas fa-history me-2"></i> Status Produksi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-secondary me-2">Batch #{{ $order->batch->id }}</span>
                            <span class="text-muted">{{ $order->batch->product->name ?? '' }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $batchProgress = [
                                    'waiting_quota' => 20,
                                    'production' => 50,
                                    'quality_check' => 70,
                                    'packaging' => 85,
                                    'shipping' => 95,
                                    'completed' => 100,
                                ];
                                $progress = $batchProgress[$order->batch->status] ?? 10;
                            @endphp
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $progress }}%; background: linear-gradient(90deg, #d4a017 0%, #f4c542 100%);">
                            </div>
                        </div>
                        <div class="text-muted small mt-2">
                            Status: <strong>{{ ucfirst(str_replace('_', ' ', $order->batch->status)) }}</strong>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Payment Summary -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                            <i class="fas fa-credit-card me-2"></i> Ringkasan Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp {{ number_format(($order->price ?? 0) * ($order->quantity ?? 1), 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkir</span>
                            <span class="text-muted">Dihitung nanti</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong class="text-primary fs-5">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Status Pembayaran</span>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Lunas</span>
                            @elseif($order->payment_status == 'partial')
                                <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> Sebagian</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Belum Bayar</span>
                            @endif
                        </div>

                        @if($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                        <div class="alert alert-warning small">
                            <i class="fas fa-info-circle me-1"></i>
                            Silakan lakukan pembayaran untuk memproses pesanan Anda.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="card-body text-white">
                        <h6 class="fw-bold mb-3"><i class="fas fa-headset me-2"></i> Butuh Bantuan?</h6>
                        <p class="small mb-3">Hubungi kami jika ada pertanyaan tentang pesanan Anda.</p>
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20order%20%23{{ $order->id }}" 
                           target="_blank" class="btn btn-light btn-sm w-100">
                            <i class="fab fa-whatsapp me-1"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
