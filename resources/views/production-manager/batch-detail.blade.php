@extends('layouts.admin')

@section('title', 'Batch Detail')
@section('page-title', 'Batch Detail')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.production.manager') }}">Production</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
            <li class="breadcrumb-item active">#{{ $batch->batch_number }}</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="fas fa-box me-2"></i>Batch #{{ $batch->batch_number }}
            </h1>
            <p class="text-muted">Detail informasi batch produksi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Batch Information -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Batch
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Batch Number</label>
                        <p class="mb-0">#{{ $batch->batch_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Produk</label>
                        <p class="mb-0">{{ $batch->product->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Target Quantity</label>
                        <p class="mb-0">{{ $batch->target_quantity }} unit</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Current Quantity</label>
                        <p class="mb-0">{{ $batch->current_quantity }} unit</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $batch->status_color }}">{{ $batch->status_label }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Progress</label>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $batch->progress_percentage }}%">
                                {{ number_format($batch->progress_percentage, 0) }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Tanggal Mulai Produksi</label>
                        <p class="mb-0">
                            {{ $batch->production_start_date ? $batch->production_start_date->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Estimasi Selesai</label>
                        <p class="mb-0">
                            {{ $batch->estimated_completion_date ? $batch->estimated_completion_date->format('d M Y') : '-' }}
                        </p>
                    </div>
                    @if($batch->actual_completion_date)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Tanggal Selesai Aktual</label>
                        <p class="mb-0">{{ $batch->actual_completion_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small">Dibuat Oleh</label>
                        <p class="mb-0">{{ $batch->creator->name ?? 'N/A' }}</p>
                    </div>
                    @if($batch->notes)
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted small">Catatan</label>
                        <p class="mb-0">{{ $batch->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Orders in Batch -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Pesanan dalam Batch ({{ $batch->orders->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->orders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->id }}</a></td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->quantity ?? 1 }} unit</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada pesanan dalam batch ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tasks me-2"></i>Update Status
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.batches.update-status', $batch->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="planning" {{ $batch->status === 'planning' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="collecting" {{ $batch->status === 'collecting' ? 'selected' : '' }}>Pengumpulan</option>
                            <option value="production" {{ $batch->status === 'production' ? 'selected' : '' }}>Produksi</option>
                            <option value="qc" {{ $batch->status === 'qc' ? 'selected' : '' }}>QC</option>
                            <option value="packaging" {{ $batch->status === 'packaging' ? 'selected' : '' }}>Packaging</option>
                            <option value="shipping" {{ $batch->status === 'shipping' ? 'selected' : '' }}>Pengiriman</option>
                            <option value="completed" {{ $batch->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $batch->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistik
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Total Pesanan</span>
                        <span class="fw-bold">{{ $batch->orders->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Target Quantity</span>
                        <span class="fw-bold">{{ $batch->target_quantity }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Current Quantity</span>
                        <span class="fw-bold">{{ $batch->current_quantity }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Progress</span>
                        <span class="fw-bold text-success">{{ number_format($batch->progress_percentage, 0) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
