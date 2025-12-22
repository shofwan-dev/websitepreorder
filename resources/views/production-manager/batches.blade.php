@extends('layouts.admin')

@section('title', 'Batch Management')
@section('page-title', 'Batch Management')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.production.manager') }}">Production</a></li>
            <li class="breadcrumb-item active">Batches</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="fas fa-layer-group me-2"></i>Batch Management
            </h1>
            <p class="text-muted">Kelola semua batch produksi</p>
        </div>
        <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Buat Batch Baru
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.batches.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="product" class="form-label">Produk</label>
                    <select name="product_id" id="product" class="form-select">
                        <option value="">Semua Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="collecting" {{ request('status') == 'collecting' ? 'selected' : '' }}>Pengumpulan</option>
                        <option value="production" {{ request('status') == 'production' ? 'selected' : '' }}>Produksi</option>
                        <option value="qc" {{ request('status') == 'qc' ? 'selected' : '' }}>QC</option>
                        <option value="packaging" {{ request('status') == 'packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Pengiriman</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Batches List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Daftar Batch Produksi
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Produk</th>
                        <th>Target/Current</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Mulai</th>
                        <th>Estimasi Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                    <tr>
                        <td>
                            <a href="{{ route('admin.batches.show', $batch->id) }}" class="fw-bold text-decoration-none">
                                #{{ $batch->batch_number }}
                            </a>
                        </td>
                        <td>{{ $batch->product->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $batch->current_quantity }} / {{ $batch->target_quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $batch->status_color }}">
                                {{ $batch->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="progress" style="height: 8px; min-width: 100px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $batch->progress_percentage }}%"
                                     aria-valuenow="{{ $batch->progress_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format($batch->progress_percentage, 0) }}%</small>
                        </td>
                        <td>
                            {{ $batch->production_start_date ? $batch->production_start_date->format('d M Y') : '-' }}
                        </td>
                        <td>
                            @if($batch->estimated_completion_date)
                                {{ $batch->estimated_completion_date->format('d M Y') }}
                                @if($batch->estimated_completion_date < now() && $batch->status !== 'completed')
                                    <i class="fas fa-exclamation-triangle text-danger ms-1" 
                                       data-bs-toggle="tooltip" 
                                       title="Terlambat"></i>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.batches.show', $batch->id) }}" 
                                   class="btn btn-outline-primary"
                                   data-bs-toggle="tooltip" 
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete({{ $batch->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            <p>Belum ada batch produksi</p>
                            <a href="{{ route('admin.batches.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Buat Batch Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($batches->hasPages())
    <div class="card-footer">
        {{ $batches->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(batchId) {
    if (confirm('Apakah Anda yakin ingin menghapus batch ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/batches/${batchId}`;
        form.submit();
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
