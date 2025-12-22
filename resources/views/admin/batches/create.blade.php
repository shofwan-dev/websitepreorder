@extends('layouts.admin')

@section('title', 'Buat Batch Baru')
@section('page-title', 'Buat Batch Baru')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.batches.index') }}">Batches</a></li>
            <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
    </nav>
    <h1 class="page-title">
        <i class="fas fa-plus-circle me-2"></i>Buat Batch Produksi Baru
    </h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.batches.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="batch_number" class="form-label">Batch Number <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('batch_number') is-invalid @enderror" 
                               id="batch_number" 
                               name="batch_number" 
                               value="{{ old('batch_number', 'BATCH-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" 
                               required>
                        @error('batch_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: BATCH-YYYYMMDD-XXX</div>
                    </div>

                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" 
                                name="product_id" 
                                required>
                            <option value="">Pilih Produk</option>
                            @foreach(\App\Models\Product::where('is_active', true)->get() as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_quantity" class="form-label">Target Quantity <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('target_quantity') is-invalid @enderror" 
                               id="target_quantity" 
                               name="target_quantity" 
                               value="{{ old('target_quantity') }}" 
                               min="1"
                               required>
                        @error('target_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Jumlah unit yang ditargetkan untuk batch ini</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="production_start_date" class="form-label">Tanggal Mulai Produksi <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('production_start_date') is-invalid @enderror" 
                                   id="production_start_date" 
                                   name="production_start_date" 
                                   value="{{ old('production_start_date', date('Y-m-d')) }}" 
                                   required>
                            @error('production_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estimated_completion_date" class="form-label">Estimasi Selesai <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('estimated_completion_date') is-invalid @enderror" 
                                   id="estimated_completion_date" 
                                   name="estimated_completion_date" 
                                   value="{{ old('estimated_completion_date') }}" 
                                   required>
                            @error('estimated_completion_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Batch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi
                </h5>
            </div>
            <div class="card-body">
                <p class="small text-muted">
                    <strong>Batch produksi</strong> adalah kumpulan pesanan yang akan diproduksi bersama-sama.
                </p>
                <hr>
                <p class="small text-muted mb-2"><strong>Status Batch:</strong></p>
                <ul class="small text-muted ps-3">
                    <li><strong>Planning:</strong> Tahap perencanaan</li>
                    <li><strong>Collecting:</strong> Mengumpulkan pesanan</li>
                    <li><strong>Production:</strong> Dalam produksi</li>
                    <li><strong>QC:</strong> Quality Control</li>
                    <li><strong>Packaging:</strong> Pengemasan</li>
                    <li><strong>Shipping:</strong> Pengiriman</li>
                    <li><strong>Completed:</strong> Selesai</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
