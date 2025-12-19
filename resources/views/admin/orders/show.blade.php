@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">Detail #{{ $order->id }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <!-- Order Info -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-cart me-2"></i>Informasi Pesanan</span>
                <span class="badge bg-{{ ['pending'=>'warning','confirmed'=>'info','processing'=>'primary','production'=>'secondary','shipping'=>'info','completed'=>'success','cancelled'=>'danger'][$order->status] ?? 'secondary' }} fs-6">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Pesanan:</strong>
                        <p class="mb-0">#{{ $order->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Pesanan:</strong>
                        <p class="mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="mb-3"><i class="fas fa-box me-2"></i>Produk</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->product->name ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($order->price ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $order->quantity ?? 1 }}</td>
                                <td class="text-end"><strong>Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td class="text-end"><strong class="text-primary">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                @if($order->notes)
                <div class="mt-3">
                    <strong>Catatan:</strong>
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>Informasi Customer
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $order->customer_name ?? $order->user->name ?? '-' }}</p>
                        <p><strong>Telepon:</strong> {{ $order->customer_phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Kota:</strong> {{ $order->customer_city ?? '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $order->customer_address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-sync me-2"></i>Update Status
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Status Pesanan</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="production" {{ $order->status == 'production' ? 'selected' : '' }}>Produksi</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Pengiriman</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Update Payment -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-wallet me-2"></i>Status Pembayaran
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="me-2">Status saat ini:</span>
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success">Lunas</span>
                    @elseif($order->payment_status == 'partial')
                        <span class="badge bg-warning">Sebagian</span>
                    @else
                        <span class="badge bg-danger">Belum Bayar</span>
                    @endif
                </div>
                
                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="partial" {{ $order->payment_status == 'partial' ? 'selected' : '' }}>Sebagian</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Update Pembayaran
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Send Notification -->
        <div class="card">
            <div class="card-header">
                <i class="fab fa-whatsapp me-2"></i>Kirim Notifikasi
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.send-notification', $order) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea name="message" class="form-control" rows="3" 
                                  placeholder="Tulis pesan untuk customer..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fab fa-whatsapp me-2"></i>Kirim via WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
    </a>
</div>
@endsection
