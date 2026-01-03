@extends('layouts.app')

@section('title', 'Detail Order #' . $order->id . ' - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi'))

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
                                <span class="text-muted small d-block">Kota & Provinsi</span>
                                <strong>{{ $order->city_name ?? $order->customer_city }}, {{ $order->province_name ?? '-' }}</strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Metode Pengiriman</span>
                                <strong>
                                    @if($order->courier)
                                        {{ strtoupper($order->courier) }} - {{ $order->courier_service }}
                                    @else
                                        -
                                    @endif
                                </strong>
                            </div>
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Tanggal Order</span>
                                <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                            @if($order->tracking_number)
                            <div class="col-md-6 mb-3">
                                <span class="text-muted small d-block">Nomor Resi</span>
                                <div class="d-flex align-items-center">
                                    <strong class="text-primary me-2">{{ $order->tracking_number }}</strong>
                                    <button class="btn btn-sm btn-outline-secondary py-0" onclick="copyTracking('{{ $order->tracking_number }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
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
                            <i class="fas fa-receipt me-2"></i> Rincian Tagihan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal ({{ $order->quantity }} pcs)</span>
                            <strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkos Kirim</span>
                            <strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                        </div>
                        @if($order->free_shipping_code)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Voucher ({{ $order->free_shipping_code }})</span>
                            <strong class="text-success">- Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3 text-primary">
                            <span class="fw-bold fs-5">Total Tagihan</span>
                            <strong class="fw-bold fs-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
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

                        @if($order->paid_at)
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Tanggal Pembayaran</span>
                            <strong>{{ $order->paid_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @endif

                        {{-- Payment Information Section --}}
                        @if($order->ipaymu_transaction_id || $order->ipaymu_session_id)
                        <div class="border-top pt-3 mt-3 mb-3">
                            <h6 class="text-muted small mb-2">
                                <i class="fas fa-receipt me-1"></i> Informasi Pembayaran
                            </h6>
                            
                            @if($order->ipaymu_transaction_id)
                            <div class="mb-2">
                                <span class="text-muted small d-block">Transaction ID</span>
                                <code class="small">{{ $order->ipaymu_transaction_id }}</code>
                            </div>
                            @endif
                            
                            @if($order->ipaymu_session_id && $order->ipaymu_session_id !== $order->ipaymu_transaction_id)
                            <div class="mb-2">
                                <span class="text-muted small d-block">Session ID</span>
                                <code class="small">{{ $order->ipaymu_session_id }}</code>
                            </div>
                            @endif
                            
                            <div class="mb-2">
                                <span class="text-muted small d-block">Merchant Ref ID</span>
                                <code class="small">ORDER-{{ $order->id }}</code>
                            </div>
                            
                            @if($order->payment_expired_at)
                            <div class="mb-2">
                                <span class="text-muted small d-block">Berlaku Hingga</span>
                                <strong class="small {{ $order->payment_expired_at->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ $order->payment_expired_at->format('d M Y, H:i') }}
                                    @if($order->payment_expired_at->isPast())
                                        <i class="fas fa-exclamation-circle ms-1"></i>
                                    @endif
                                </strong>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                        
                        <!-- Payment Action Section -->
                        <div class="border-top pt-3 mt-3">
                            @if($order->ipaymu_payment_url)
                                <!-- If payment URL exists, show it -->
                                <div class="mb-3">
                                    <div class="alert alert-info small mb-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Link pembayaran sudah dibuat. Klik tombol di bawah untuk melanjutkan pembayaran.
                                    </div>
                                    
                                    <a href="{{ $order->ipaymu_payment_url }}" 
                                       target="_blank"
                                       class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Lanjutkan Pembayaran
                                    </a>
                                </div>
                            @else
                                <!-- If no payment URL, show create payment button -->
                                <form action="{{ route('user.orders.pay', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Bayar Sekarang
                                    </button>
                                </form>
                            @endif
                            
                            <div class="alert alert-warning small mb-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Silakan lakukan pembayaran untuk memproses pesanan Anda.
                            </div>
                            
                            <!-- Edit Order Button -->
                            <a href="{{ route('user.orders.edit', $order) }}" 
                               class="btn btn-outline-primary btn-sm w-100 mb-2">
                                <i class="fas fa-edit me-1"></i> Edit Data Pesanan
                            </a>

                            <!-- Cancel Order Button -->
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelOrderModal">
                                <i class="fas fa-times-circle me-1"></i>
                                Batalkan Order
                            </button>
                        </div>
                        
                        @elseif($order->payment_status === 'paid')
                        <div class="alert alert-success small mt-3">
                            <i class="fas fa-check-circle me-1"></i>
                            Pembayaran telah diterima. Pesanan Anda sedang diproses.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="card-body text-white">
                        <h6 class="fw-bold mb-3"><i class="fas fa-headset me-2"></i> Butuh Bantuan?</h6>
                        <p class="small mb-3">Hubungi kami jika ada pertanyaan tentang pesanan Anda.</p>
                        <a href="https://wa.me/{{ str_replace(['-', ' ', '+'], '', $site_settings['whatsapp'] ?? '6281234567890') }}?text=Halo,%20saya%20ingin%20bertanya%20tentang%20order%20%23{{ $order->id }}" 
                           target="_blank" class="btn btn-light btn-sm w-100">
                            <i class="fab fa-whatsapp me-1"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success') || session('error') || session('info') || session('warning'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        alert('{{ session('success') }}');
    @elseif(session('error'))
        alert('Error: {{ session('error') }}');
    @elseif(session('info'))
        alert('{{ session('info') }}');
    @elseif(session('warning'))
        alert('{{ session('warning') }}');
    @endif
});
</script>
@endif

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelOrderModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Pembatalan Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian!</strong> Anda akan membatalkan order ini.
                </div>
                
                <p class="mb-3">Apakah Anda yakin ingin membatalkan order <strong>#{{ $order->id }}</strong>?</p>
                
                <div class="bg-light p-3 rounded mb-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Produk</small>
                            <strong class="small">{{ $order->product->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total</small>
                            <strong class="small">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('user.orders.cancel', $order) }}" method="POST" id="cancelOrderForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">
                            Alasan Pembatalan <span class="text-muted">(Opsional)</span>
                        </label>
                        <textarea class="form-control" 
                                  id="cancel_reason" 
                                  name="cancel_reason" 
                                  rows="3" 
                                  placeholder="Contoh: Salah pilih produk, Berubah pikiran, dll."></textarea>
                        <small class="text-muted">Alasan ini akan membantu kami meningkatkan layanan.</small>
                    </div>
                </form>
                
                <div class="alert alert-info small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Data order tidak akan dihapus, hanya status yang berubah menjadi "Dibatalkan".
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali
                </button>
                <button type="submit" form="cancelOrderForm" class="btn btn-danger">
                    <i class="fas fa-times-circle me-1"></i>
                    Ya, Batalkan Order
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
