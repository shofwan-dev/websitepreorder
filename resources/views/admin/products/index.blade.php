@extends('layouts.admin')

@section('title', 'Daftar Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Daftar Produk</h1>
        <p class="text-muted">Kelola produk kaligrafi</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Tambah Produk
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Cari Produk</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama produk..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Products Grid -->
<div class="row g-4">
    @forelse($products as $product)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            @php
                $images = $product->images;
                $hasImages = is_array($images) && count($images) > 0;
            @endphp
            @if($hasImages)
                <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top" 
                     style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <i class="fas fa-image fa-3x text-muted"></i>
                </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">{{ $product->name }}</h5>
                    @if($product->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
                <p class="text-primary fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small">
                        <span class="text-muted">Kuota: </span>
                        <span class="fw-semibold">{{ $product->current_quota ?? 0 }}/{{ $product->min_quota }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="btn-group w-100">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.products.toggle-active', $product) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }} btn-sm">
                            <i class="fas fa-{{ $product->is_active ? 'pause' : 'play' }} me-1"></i>
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>Belum Ada Produk</h4>
                <p class="text-muted">Tambahkan produk pertama Anda</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Produk
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endif
@endsection
