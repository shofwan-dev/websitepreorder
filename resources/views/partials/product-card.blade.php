@php
    // Handle both array and Product model
    $productId = is_array($product) ? $product['id'] : $product->id;
    $productName = is_array($product) ? $product['name'] : $product->name;
    $productPrice = is_array($product) ? $product['price'] : $product->price;
    
    // Handle images - get all images
    if (is_array($product)) {
        $productImages = isset($product['image']) ? [$product['image']] : [];
    } else {
        $images = is_array($product->images) ? $product->images : (is_string($product->images) ? json_decode($product->images, true) : []);
        $productImages = !empty($images) ? $images : ['https://via.placeholder.com/400x300/d4a017/ffffff?text=' . urlencode($productName)];
    }
    
    // Calculate progress
    if (is_array($product)) {
        $currentOrders = $product['current_orders'] ?? 0;
        $minQuota = $product['min_quota'] ?? 10;
        $progressPercentage = $product['progress_percentage'] ?? 0;
    } else {
        $currentOrders = $product->paid_orders_count ?? 0;
        $minQuota = $product->min_quota ?? 10;
        $progressPercentage = $minQuota > 0 ? min(100, ($currentOrders / $minQuota) * 100) : 0;
    }
    
    $hasMultipleImages = count($productImages) > 1;
    $swiperClass = 'product-swiper-' . $productId;
@endphp

<div class="product-card">
    <!-- Product Image with Overlay and Swiper -->
    <a href="{{ route('product.detail', $productId) }}" class="text-decoration-none">
        <div class="product-image-wrapper">
            @if($hasMultipleImages)
                <!-- Swiper for multiple images -->
                <div class="swiper {{ $swiperClass }}">
                    <div class="swiper-wrapper">
                        @foreach($productImages as $img)
                        <div class="swiper-slide">
                            <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" 
                                 class="product-image" alt="{{ $productName }}">
                        </div>
                        @endforeach
                    </div>
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <!-- Single image -->
                <img src="{{ str_starts_with($productImages[0], 'http') ? $productImages[0] : asset('storage/' . $productImages[0]) }}" 
                     class="product-image" alt="{{ $productName }}">
            @endif
            
            <div class="product-overlay">
                <div class="overlay-content">
                    <i class="fas fa-eye fa-2x text-white mb-2"></i>
                    <p class="text-white mb-0 small">Lihat Detail</p>
                </div>
            </div>
        </div>
    </a>
    
    <!-- Product Info -->
    <div class="product-body">
        <!-- Product Name -->
        <a href="{{ route('product.detail', $productId) }}" class="text-decoration-none">
            <h5 class="product-title">{{ $productName }}</h5>
        </a>
        
        <!-- Price -->
        <div class="product-price mb-3">
            Rp {{ number_format($productPrice, 0, ',', '.') }}
        </div>
        
        <!-- Progress Section -->
        <div class="progress-section mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="progress-label">
                    <i class="fas fa-chart-line me-1"></i>Progress
                </span>
                <span class="progress-percentage">{{ number_format($progressPercentage, 0) }}%</span>
            </div>
            <div class="custom-progress">
                <div class="custom-progress-bar 
                    @if($progressPercentage >= 100) bg-gradient-success
                    @elseif($progressPercentage >= 70) bg-gradient-info
                    @elseif($progressPercentage >= 40) bg-gradient-warning
                    @else bg-gradient-danger
                    @endif" 
                     style="width: {{ min($progressPercentage, 100) }}%">
                    <span class="progress-shine"></span>
                </div>
            </div>
        </div>
        
        <!-- Order Count -->
        <div class="order-count mb-3">
            <i class="fas fa-users text-primary me-2"></i>
            <span class="fw-bold text-primary">{{ $currentOrders }}</span>
            <span class="text-muted"> orang bergabung</span>
        </div>
        
        <!-- CTA Button -->
        <a href="{{ route('user.orders.create', ['product_id' => $productId]) }}" 
           class="btn-cta">
            <span class="btn-text">
                <i class="fas fa-cart-plus me-2"></i>Ikut PO
            </span>
            <span class="btn-icon">
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>
    </div>
</div>

@if($hasMultipleImages)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.{{ $swiperClass }}', {
        loop: true,
        pagination: {
            el: '.{{ $swiperClass }} .swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });
});
</script>
@endpush
@endif
