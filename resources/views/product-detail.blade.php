@extends('layouts.app')

@section('title', $product->name . ' - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image/Video -->
        <div class="col-lg-6 mb-4">
            @if($product->video_url)
                <!-- Video Section -->
                <div class="product-detail-video mb-3">
                    <div class="ratio ratio-16x9 rounded shadow overflow-hidden">
                        <video controls class="w-100 h-100" style="object-fit: cover;">
                            <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                </div>
            @endif
            
            
            <!-- Product Image Gallery -->
            <div class="product-detail-image">
                @php
                    $images = $product->images;
                    if (!is_array($images)) {
                        $images = $images ? json_decode($images, true) : [];
                    }
                    $hasImages = is_array($images) && count($images) > 0;
                @endphp
                
                @if($hasImages)
                    <!-- Main Image -->
                    <div class="mb-3">
                        <img id="mainImage" 
                             src="{{ asset('storage/' . $images[0]) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow w-100"
                             style="max-height: 500px; object-fit: cover;">
                    </div>
                    
                    <!-- Image Thumbnails (if multiple images) -->
                    @if(count($images) > 1)
                    <div class="row g-2">
                        @foreach($images as $index => $image)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }} - {{ $index + 1 }}" 
                                 class="img-fluid rounded shadow-sm cursor-pointer thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                                 style="height: 100px; object-fit: cover; cursor: pointer; border: 3px solid transparent;"
                                 onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                        </div>
                        @endforeach
                    </div>
                    @endif
                @else
                    <img src="https://via.placeholder.com/600x600/d4a017/ffffff?text={{ urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow">
                @endif
            </div>
            
            <script>
            function changeMainImage(imageSrc, thumbnail) {
                document.getElementById('mainImage').src = imageSrc;
                
                // Remove active class from all thumbnails
                document.querySelectorAll('.thumbnail-image').forEach(img => {
                    img.classList.remove('active');
                    img.style.borderColor = 'transparent';
                });
                
                // Add active class to clicked thumbnail
                thumbnail.classList.add('active');
                thumbnail.style.borderColor = '#d4a017';
            }
            
            // Set first thumbnail as active on load
            document.addEventListener('DOMContentLoaded', function() {
                const firstThumbnail = document.querySelector('.thumbnail-image');
                if (firstThumbnail) {
                    firstThumbnail.style.borderColor = '#d4a017';
                }
            });
            </script>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <h1 class="product-detail-title mb-3">{{ $product->name }}</h1>
            
            <div class="product-detail-price mb-4">
                <span class="price-label">Harga:</span>
                <span class="price-value">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>

            <!-- Progress Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-line text-primary me-2"></i>Progress Pre-Order
                    </h5>
                    
                    <div class="progress mb-3" style="height: 25px;">
                        @php
                            $progressPercentage = $progressData['progress_percentage'] ?? 0;
                        @endphp
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" 
                             style="width: {{ $progressPercentage }}%"
                             aria-valuenow="{{ $progressPercentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($progressPercentage, 0) }}%
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-primary mb-0">{{ $progressData['current_orders'] ?? 0 }}</h4>
                            <small class="text-muted">Bergabung</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning mb-0">{{ $progressData['remaining_slots'] ?? 0 }}</h4>
                            <small class="text-muted">Sisa Kuota</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-0">{{ $progressData['min_quota'] ?? 10 }}</h4>
                            <small class="text-muted">Target</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Status -->
            @if($activeBatch)
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Status Batch:</strong> {{ $activeBatch->status_label }}
                @if($activeBatch->production_start_date)
                    <br><small>Produksi dimulai: {{ $activeBatch->production_start_date->format('d M Y') }}</small>
                @endif
            </div>
            @endif

            <!-- Description -->
            @if($product->description)
            <div class="product-description mb-4">
                <h5 class="mb-3"><i class="fas fa-align-left me-2"></i>Deskripsi</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            @endif

            <!-- Specifications -->
            @if($product->specifications)
            <div class="product-specifications mb-4">
                <h5 class="mb-3"><i class="fas fa-list-ul me-2"></i>Spesifikasi</h5>
                
                @php
                    $specs = $product->specifications;
                    // Check if it's array or string
                    $isArray = is_array($specs);
                    if (!$isArray && is_string($specs)) {
                        // Try to decode JSON
                        $decoded = json_decode($specs, true);
                        if (is_array($decoded)) {
                            $specs = $decoded;
                            $isArray = true;
                        }
                    }
                @endphp
                
                @if($isArray)
                    <ul class="list-unstyled">
                        @foreach($specs as $key => $value)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $specs }}</p>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <!-- CTA Button -->
            <div class="d-grid gap-2">
                <a href="{{ route('user.orders.create', ['product_id' => $product->id]) }}" 
                   class="btn btn-primary btn-lg">
                    <i class="fas fa-cart-plus me-2"></i>Ikut Pre-Order Sekarang
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Section - Yang Sudah Bergabung -->
    @if($recentOrders && $recentOrders->count() > 0)
    <div class="row mt-5 mb-5">
        <div class="col-12 mb-4">
            <h2 class="text-center mb-2">
                <i class="fas fa-users me-2 text-primary"></i>Yang Sudah Bergabung
            </h2>
            <p class="text-center text-muted">{{ $recentOrders->count() }} orang telah bergabung untuk produk ini</p>
        </div>
        
        <div class="col-12">
            <div class="orders-container">
                @foreach($recentOrders as $order)
                    <div class="order-item">
                        <div class="order-avatar">
                            <div class="avatar-circle">
                                {{ $order['initials'] }}
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="order-product">{{ $product->name }}</div>
                            <div class="order-customer">
                                <i class="fas fa-user me-1"></i>
                                <strong>{{ $order['masked_name'] }}</strong>
                            </div>
                            <div class="order-location">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $order['city'] }}
                            </div>
                        </div>
                        <div class="order-time">
                            <small class="text-muted">{{ $order['time_ago'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada yang bergabung untuk produk ini. Jadilah yang pertama!
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Product Detail Styles */
.product-detail-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 15px;
}

.product-detail-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.product-detail-price {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #d4a017;
}

.price-label {
    display: block;
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.price-value {
    font-size: 2rem;
    font-weight: 800;
    background: linear-gradient(135deg, #d4a017, #f4c542);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.product-description p {
    font-size: 1.1rem;
    line-height: 1.8;
}

.product-specifications ul li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.product-specifications ul li:last-child {
    border-bottom: none;
}

/* Image Gallery Thumbnails */
.thumbnail-image {
    transition: all 0.3s ease;
}

.thumbnail-image:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.thumbnail-image.active {
    border-color: #d4a017 !important;
    box-shadow: 0 4px 12px rgba(212, 160, 23, 0.4);
}

/* Orders Grid */
.orders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

/* Orders Section Styles */
.orders-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    max-height: 600px;
    overflow-y: auto;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 15px;
}

.order-item {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.order-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(212, 160, 23, 0.2);
    border-color: #d4a017;
}

.order-details {
    flex: 1;
    min-width: 0;
}

.order-product {
    font-weight: 700;
    color: #2c3e50;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.order-customer {
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.15rem;
}

.order-location {
    color: #6c757d;
    font-size: 0.85rem;
}

/* Scrollbar Styling */
.orders-container::-webkit-scrollbar {
    width: 8px;
}

.orders-container::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 10px;
}

.orders-container::-webkit-scrollbar-thumb {
    background: #d4a017;
    border-radius: 10px;
}

.orders-container::-webkit-scrollbar-thumb:hover {
    background: #b8860b;
}

.order-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(212, 160, 23, 0.2);
    border-color: #d4a017;
}

.order-avatar .avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d4a017, #f4c542);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 10px rgba(212, 160, 23, 0.3);
}

.order-info {
    flex: 1;
}

.order-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.order-city {
    font-size: 0.9rem;
    color: #6c757d;
}

.order-time {
    text-align: right;
}

/* Responsive */
@media (max-width: 768px) {
    .product-detail-title {
        font-size: 1.75rem;
    }
    
    .price-value {
        font-size: 1.5rem;
    }
    
    .orders-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
