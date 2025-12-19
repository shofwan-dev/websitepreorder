@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-muted">Berikut adalah ringkasan data PO Kaligrafi hari ini.</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['users']['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['orders']['total'] ?? 0 }}</div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['orders']['pending'] ?? 0 }}</div>
                    <div class="stat-label">Pesanan Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="stat-value">Rp {{ number_format($stats['orders']['revenue'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-cart me-2"></i>Pesanan Terbaru</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer_name ?? $order->user->name ?? '-' }}</td>
                                <td>{{ $order->product->name ?? '-' }}</td>
                                <td>Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'processing' => 'primary',
                                            'production' => 'secondary',
                                            'shipping' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Belum ada pesanan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Batches & Quick Actions -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Aksi Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders.pending') }}" class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i>Lihat Pesanan Pending
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tambah Produk Baru
                    </a>
                    <a href="{{ route('admin.production.manager') }}" class="btn btn-primary">
                        <i class="fas fa-industry me-2"></i>Production Manager
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-layer-group me-2"></i>Batch Aktif</span>
                <span class="badge bg-primary">{{ $stats['batches']['active'] ?? 0 }}</span>
            </div>
            <div class="card-body">
                @forelse($activeBatches ?? [] as $batch)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <strong>Batch #{{ $batch->id }}</strong>
                        <div class="small text-muted">{{ $batch->product->name ?? 'Produk N/A' }}</div>
                    </div>
                    <span class="badge bg-{{ $batch->status === 'production' ? 'primary' : 'secondary' }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-layer-group fa-2x mb-2 d-block"></i>
                    Tidak ada batch aktif
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Stats Summary -->
<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['products']['active'] ?? 0 }}</h3>
                        <span>Produk Aktif</span>
                    </div>
                    <i class="fas fa-box fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['orders']['completed'] ?? 0 }}</h3>
                        <span>Pesanan Selesai</span>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['batches']['completed'] ?? 0 }}</h3>
                        <span>Batch Selesai</span>
                    </div>
                    <i class="fas fa-layer-group fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
