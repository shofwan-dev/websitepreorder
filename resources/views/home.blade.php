@extends('layouts.app')

@section('title', ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu') . ' - Beranda')

@push('styles')
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@section('content')
<div class="container py-5">
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

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            @php
                $productName = $latestProduct->name ?? 'Kaligrafi Lampu Allah';
                $productDescription = $latestProduct->description ?? 'Kaligrafi lampu dengan tulisan Allah yang indah';
            @endphp
            <h1 class="display-4 fw-bold mb-4">{{ $productName }}</h1>
            <p class="lead mb-4">{{ $productDescription }}</p>
            <div class="d-flex gap-3">
                <a href="{{ route('order.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-cart-plus me-2"></i>Ikut PO Sekarang
                </a>
                <a href="#products" class="btn btn-outline-primary btn-lg">Lihat Produk</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center p-4">
                    <h5 class="card-title mb-3">Progress Pre-Order</h5>
                    <div class="mb-3">
                        <div class="progress" style="height: 30px;">
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
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $progressData['current_orders'] ?? 0 }}</h4>
                            <p class="text-muted">Sudah Bergabung</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $progressData['remaining_slots'] ?? 3 }}</h4>
                            <p class="text-muted">Sisa Kuota</p>
                        </div>
                    </div>
                    <p class="text-muted mt-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Minimal {{ $progressData['min_quota'] ?? 10 }} pemesan untuk produksi
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Products Listing -->
    <div id="products" class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="text-center mb-2">
                <i class="fas fa-box-open me-2 text-primary"></i>Pre-Order Aktif
            </h2>
            <p class="text-center text-muted">Bergabunglah dengan pre-order yang sedang berjalan!</p>
        </div>
        
        <!-- Desktop: Grid Layout, Mobile: Swiper Carousel -->
        <div class="d-none d-md-block">
            <!-- Desktop Grid -->
            <div class="row g-4">
                @forelse($activeProducts as $product)
                <div class="col-lg-3 col-md-6">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada pre-order aktif saat ini.
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Mobile: Swiper Carousel -->
        <div class="d-md-none">
            <div class="swiper productSwiper">
                <div class="swiper-wrapper">
                    @forelse($activeProducts as $product)
                    <div class="swiper-slide">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada pre-order aktif saat ini.
                        </div>
                    </div>
                    @endforelse
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>

    <!-- Orders Section - Orang yang Sudah Order -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="text-center mb-2">
                <i class="fas fa-users me-2 text-primary"></i>Yang Sudah Bergabung
            </h2>
            <p class="text-center text-muted">Mereka yang telah mempercayai kami</p>
        </div>
        
        @if($allOrders && $allOrders->count() > 0)
            <div class="col-12">
                <div class="orders-container">
                    @foreach($allOrders as $order)
                        <div class="order-item">
                            <div class="order-avatar">
                                <div class="avatar-circle">
                                    {{ $order['initials'] }}
                                </div>
                            </div>
                            <div class="order-details">
                                <div class="order-product">{{ $order['product_name'] }}</div>
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
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada pesanan yang ditampilkan.
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper for mobile
    const swiper = new Swiper('.productSwiper', {
        slidesPerView: 1.2,
        spaceBetween: 20,
        centeredSlides: true,
        loop: false, // Disable loop to avoid warnings when not enough slides
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        effect: 'coverflow',
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 100,
            modifier: 2,
            slideShadows: true,
        },
    });
</script>


<style>
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

.order-avatar {
    flex-shrink: 0;
}

.avatar-circle {
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

.order-time {
    flex-shrink: 0;
    text-align: right;
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


/* Swiper Mobile Styles */

.productSwiper {
    width: 100%;
    padding: 20px 0 60px 0;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-pagination {
    bottom: 20px !important;
}

.swiper-pagination-bullet {
    background: #d4a017;
    opacity: 0.5;
    width: 10px;
    height: 10px;
}

.swiper-pagination-bullet-active {
    opacity: 1;
    width: 30px;
    border-radius: 5px;
}

.swiper-button-next,
.swiper-button-prev {
    color: #d4a017;
    background: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 18px;
    font-weight: bold;
}

/* Product Card Swiper Pagination */
.product-card .swiper-pagination {
    bottom: 10px !important;
    z-index: 10;
}

.product-card .swiper-pagination-bullet {
    background: white;
    opacity: 0.7;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.product-card .swiper-pagination-bullet-active {
    background: #d4a017;
    opacity: 1;
    width: 25px;
}

/* Product Card Styles */
.product-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 20px 60px rgba(212, 160, 23, 0.3);
}

/* Product Image */
.product-image-wrapper {
    position: relative;
    overflow: hidden;
    width: 100%;
    aspect-ratio: 1 / 1; /* Square ratio */
    background: #2c3e50;
}

/* Ensure swiper maintains square ratio */
.product-card .swiper {
    width: 100%;
    height: 100%;
    aspect-ratio: 1 / 1;
}

.product-card .swiper-slide {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image,
.product-card .swiper-slide img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important; /* Fill container completely */
    object-position: center !important;
    transition: transform 0.6s ease;
    display: block;
}

.product-card:hover .product-image,
.product-card:hover .swiper-slide img {
    transform: scale(1.15) rotate(2deg);
}

/* Image Overlay */
.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(212, 160, 23, 0.9), rgba(139, 107, 45, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    transform: translateY(20px);
    transition: transform 0.4s ease;
}

.product-card:hover .overlay-content {
    transform: translateY(0);
}

/* Product Body */
.product-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    min-height: 2.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #d4a017, #f4c542);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Progress Section */
.progress-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
    border: 2px solid #e9ecef;
}

.progress-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
}

.progress-percentage {
    font-size: 0.9rem;
    font-weight: 800;
    color: #d4a017;
}

.custom-progress {
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.custom-progress-bar {
    height: 100%;
    border-radius: 10px;
    position: relative;
    transition: width 1s ease;
    overflow: hidden;
}

.bg-gradient-success {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.bg-gradient-info {
    background: linear-gradient(90deg, #17a2b8, #3498db);
}

.bg-gradient-warning {
    background: linear-gradient(90deg, #ffc107, #ff9800);
}

.bg-gradient-danger {
    background: linear-gradient(90deg, #dc3545, #e74c3c);
}

/* Progress Shine Effect */
.progress-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: shine 2s infinite;
}

@keyframes shine {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Order Count */
.order-count {
    font-size: 0.95rem;
    padding: 0.5rem;
    background: #f0f8ff;
    border-radius: 8px;
    border-left: 4px solid #0d6efd;
}

/* CTA Button */
.btn-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #d4a017, #f4c542);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
    margin-top: auto;
    overflow: hidden;
    position: relative;
}

.btn-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s ease;
}

.btn-cta:hover::before {
    left: 100%;
}

.btn-cta:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 25px rgba(212, 160, 23, 0.5);
    color: white;
}

.btn-text {
    flex: 1;
}

.btn-icon {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.btn-cta:hover .btn-icon {
    transform: translateX(5px);
}

/* Responsive */
@media (max-width: 768px) {
    /* Hero Section Mobile */
    .container {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .display-4 {
        font-size: 2rem !important;
    }
    
    .lead {
        font-size: 1rem !important;
    }
    
    /* Product Card Mobile */
    .product-card {
        margin-bottom: 1rem;
        max-width: 320px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-title {
        font-size: 1.1rem;
        min-height: auto;
    }
    
    .product-price {
        font-size: 1.4rem;
    }
    
    .product-body {
        padding: 1.25rem;
    }
    
    .progress-section {
        padding: 0.75rem;
    }
    
    .btn-cta {
        padding: 0.875rem 1.25rem;
        font-size: 0.95rem;
    }
    
    /* Swiper Mobile Optimization */
    .swiper-slide {
        padding: 0 10px;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        width: 35px;
        height: 35px;
    }
    
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 16px;
    }
    
    /* Hero Progress Card Mobile */
    .card-body {
        padding: 1.5rem !important;
    }
    
    h2 {
        font-size: 1.5rem !important;
    }
    
    h4 {
        font-size: 1.25rem !important;
    }
    
    /* Buttons Mobile */
    .btn-lg {
        padding: 0.75rem 1.5rem !important;
        font-size: 1rem !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .d-flex.gap-3 .btn {
        width: 100%;
    }
    
    /* Orders Section Mobile */
    .orders-container {
        grid-template-columns: 1fr;
        max-height: 500px;
        padding: 0.75rem;
        gap: 1rem;
    }
    
    .order-item {
        padding: 1rem;
    }
    
    .avatar-circle {
        width: 45px;
        height: 45px;
        font-size: 1rem;
    }
    
    .order-product {
        font-size: 0.9rem;
    }
    
    .order-customer {
        font-size: 0.85rem;
    }
    
    .order-location {
        font-size: 0.8rem;
    }
}


/* Extra Small Devices */
@media (max-width: 576px) {
    .product-card {
        max-width: 280px;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        display: none; /* Hide arrows on very small screens */
    }
}

</style>
@endsection