@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Tambah Baru</li>
        </ol>
    </nav>
    <h1 class="page-title">Tambah Produk Baru</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Kaligrafi Lampu Allah">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" min="0" required
                                   placeholder="350000">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="min_quota" class="form-label">Minimal Kuota <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_quota') is-invalid @enderror" 
                                   id="min_quota" name="min_quota" value="{{ old('min_quota', 10) }}" min="1" required>
                            <div class="form-text">Jumlah minimal pesanan untuk memulai produksi</div>
                            @error('min_quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Spesifikasi</label>
                        <textarea class="form-control @error('specifications') is-invalid @enderror" 
                                  id="specifications" name="specifications" rows="3"
                                  placeholder="Ukuran: 40x60cm&#10;Bahan: Akrilik Premium&#10;LED: Warm White">{{ old('specifications') }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="images" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">Bisa upload beberapa gambar. Format: JPG, PNG, GIF. Maks: 2MB per gambar</div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="video" class="form-label">
                            Video Produk <span class="text-muted small">(Opsional)</span>
                        </label>
                        <input type="file" class="form-control @error('video') is-invalid @enderror" 
                               id="video" name="video" accept="video/mp4,video/mov,video/avi,video/wmv">
                        <div class="form-text">
                            Format: MP4, MOV, AVI, WMV. Maksimal 50MB.
                        </div>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
