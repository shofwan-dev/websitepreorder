@extends('layouts.admin')

@section('title', 'Production Manager')
@section('page-title', 'Production Manager')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Production Manager</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="fas fa-industry me-2"></i>Production Manager
            </h1>
            <p class="text-muted">Dashboard untuk mengelola seluruh proses produksi PO Kaligrafi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.batches.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Tambah Batch
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Export Laporan
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3 bg-primary bg-opacity-10">
                        <i class="fas fa-box-open text-primary fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Total Batch</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Batch::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3 bg-warning bg-opacity-10">
                        <i class="fas fa-spinner text-warning fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Dalam Produksi</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Batch::whereNotIn('status', ['completed', 'cancelled'])->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3 bg-success bg-opacity-10">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Selesai</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Batch::where('status', 'completed')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3 bg-danger bg-opacity-10">
                        <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Terlambat</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Batch::where('estimated_completion_date', '<', now())->whereNotIn('status', ['completed', 'cancelled'])->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4">
    <!-- Production Timeline -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="fas fa-stream me-2"></i>Timeline Produksi Aktif
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Batch</th>
                                <th>Produk</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Deadline</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $activeBatches = \App\Models\Batch::with('product')
                                    ->whereNotIn('status', ['completed', 'cancelled'])
                                    ->orderBy('estimated_completion_date')
                                    ->limit(10)
                                    ->get();
                            @endphp
                            
                            @forelse($activeBatches as $batch)
                            <tr>
                                <td><a href="{{ route('admin.batches.show', $batch->id) }}">#{{ $batch->batch_number }}</a></td>
                                <td>{{ $batch->product->name ?? 'N/A' }}</td>
                                <td>
                                    @if($batch->status === 'pending')
                                        <span class="badge bg-secondary">Pending</span>
                                    @elseif($batch->status === 'production')
                                        <span class="badge bg-warning">Produksi</span>
                                    @elseif($batch->status === 'qc')
                                        <span class="badge bg-info">QC</span>
                                    @elseif($batch->status === 'packaging')
                                        <span class="badge bg-primary">Packaging</span>
                                    @elseif($batch->status === 'ready')
                                        <span class="badge bg-success">Ready</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $progress = 0;
                                        if($batch->status === 'production') $progress = 40;
                                        elseif($batch->status === 'qc') $progress = 60;
                                        elseif($batch->status === 'packaging') $progress = 80;
                                        elseif($batch->status === 'ready') $progress = 100;
                                    @endphp
                                    <div class="progress" style="height: 6px; width: 100px;">
                                        <div class="progress-bar" style="width: {{ $progress }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($batch->estimated_completion_date)
                                        {{ \Carbon\Carbon::parse($batch->estimated_completion_date)->format('d M Y') }}
                                        @if($batch->estimated_completion_date < now() && $batch->status !== 'completed')
                                            <i class="fas fa-exclamation-circle text-danger ms-1"></i>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.batches.show', $batch->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox me-2"></i>Tidak ada batch aktif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Updates -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.production.orders') }}" class="btn btn-outline-primary text-start">
                        <i class="fas fa-bullhorn me-2"></i>Lihat Pesanan
                    </a>
                    <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-success text-start">
                        <i class="fas fa-sync-alt me-2"></i>Kelola Batch
                    </a>
                    <a href="{{ route('admin.production.reports') }}" class="btn btn-outline-info text-start">
                        <i class="fas fa-chart-line me-2"></i>Laporan Produksi
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Updates -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Update Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $recentBatches = \App\Models\Batch::with('product')
                            ->orderBy('updated_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($recentBatches as $batch)
                    <div class="list-group-item border-0 px-0 py-3">
                        <div class="d-flex">
                            <div class="rounded-circle p-2 me-3 bg-primary bg-opacity-10">
                                <i class="fas fa-box text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="small mb-1">Batch #{{ $batch->batch_number }} - {{ $batch->product->name ?? 'N/A' }}</p>
                                <p class="text-muted small mb-0">{{ $batch->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-inbox me-2"></i>Belum ada update
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(90deg, #d4a017 0%, #f4c542 100%);
}
</style>
@endpush
