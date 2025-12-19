@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
    <h1 class="page-title">Edit: {{ $product->name }}</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="min_quota" class="form-label">Minimal Kuota <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_quota') is-invalid @enderror" 
                                   id="min_quota" name="min_quota" value="{{ old('min_quota', $product->min_quota) }}" min="1" required>
                            @error('min_quota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kuota Saat Ini</label>
                            <input type="text" class="form-control" value="{{ $product->current_quota ?? 0 }}" disabled>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="specifications" class="form-label">Spesifikasi</label>
                        <textarea class="form-control @error('specifications') is-invalid @enderror" 
                                  id="specifications" name="specifications" rows="3">{{ old('specifications', $product->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Current Images -->
                    @php
                        $images = $product->images;
                        $hasImages = is_array($images) && count($images) > 0;
                    @endphp
                    @if($hasImages)
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div class="row g-2">
                            @foreach($images as $image)
                            <div class="col-auto">
                                <img src="{{ asset('storage/' . $image) }}" alt="Product Image" 
                                     class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        <div class="form-text">Upload gambar baru untuk mengganti semua gambar</div>
                    </div>
                    @endif
                    
                    
                    <div class="mb-4">
                        <label for="images" class="form-label">Upload Gambar Baru <span class="text-muted small">(Opsional)</span></label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Maksimal 4 gambar. Upload gambar baru akan mengganti semua gambar lama. Format: JPEG, PNG, JPG, GIF (Max 2MB/gambar)
                        </div>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Current Video -->
                    @if($product->video_url)
                    <div class="mb-3">
                        <label class="form-label">Video Saat Ini</label>
                        <div class="ratio ratio-16x9" style="max-width: 500px;">
                            <video controls class="rounded">
                                <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        </div>
                        <div class="form-text">Upload video baru untuk mengganti video saat ini</div>
                    </div>
                    @endif
                    
                    <!-- Upload Video -->
                    <div class="mb-4">
                        <label for="video" class="form-label">
                            Upload Video {{ $product->video_url ? 'Baru' : '' }}
                            <span class="text-muted small">(Opsional)</span>
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
                            <i class="fas fa-save me-2"></i> Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        // Phase 2: Disable empty file inputs instead of removing name
        const imageInput = document.getElementById('images');
        const videoInput = document.getElementById('video');
        
        // Disable inputs that have no files selected
        if (imageInput && imageInput.files.length === 0) {
            imageInput.disabled = true;
        }
        
        if (videoInput && videoInput.files.length === 0) {
            videoInput.disabled = true;
        }
    });
});
</script>
@endsection
