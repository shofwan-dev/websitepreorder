@extends('layouts.app')

@section('title', 'Buat Order Baru - PO Kaligrafi')

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1" style="color: #8b6b2d;">
                <i class="fas fa-shopping-cart me-2"></i> Ikut Pre-Order
            </h1>
            <p class="text-muted mb-0">Pilih produk dan lengkapi data pemesanan</p>
        </div>

        <form method="POST" action="{{ route('user.orders.store') }}">
            @csrf
            
            <div class="row g-4">
                <!-- Pilih Produk -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                                <i class="fas fa-box me-2"></i> Pilih Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($products->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada produk tersedia saat ini</p>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($products as $product)
                                    <div class="col-md-6">
                                        <div class="card h-100 product-card border-2 {{ old('product_id', $selectedProduct?->id) == $product->id ? 'border-warning' : '' }}"
                                             data-product-id="{{ $product->id }}" 
                                             data-price="{{ $product->price }}">
                                            @php
                                                $images = $product->images;
                                                if (!is_array($images)) {
                                                    $images = $images ? json_decode($images, true) : [];
                                                }
                                                $hasImages = is_array($images) && count($images) > 0;
                                            @endphp
                                            
                                            @if($hasImages)
                                            <!-- Product Image -->
                                            <div class="product-order-image">
                                                <img src="{{ asset('storage/' . $images[0]) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="card-img-top">
                                            </div>
                                            @else
                                            <!-- Placeholder -->
                                            <div class="product-order-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                            @endif
                                            
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input product-radio" type="radio" 
                                                           name="product_id" value="{{ $product->id }}" 
                                                           id="product{{ $product->id }}"
                                                           {{ old('product_id', $selectedProduct?->id) == $product->id ? 'checked' : '' }}
                                                           required>
                                                    <label class="form-check-label w-100" for="product{{ $product->id }}">
                                                        <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                                        <p class="text-primary fw-bold mb-1">
                                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                                        </p>
                                                        <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                                                        <div class="d-flex justify-content-between small">
                                                            <span class="text-muted">
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $product->current_quota ?? 0 }}/{{ $product->min_quota }} kuota
                                                            </span>
                                                            @php
                                                                $remaining = $product->min_quota - ($product->current_quota ?? 0);
                                                            @endphp
                                                            @if($remaining > 0)
                                                                <span class="text-warning">{{ $remaining }} slot lagi</span>
                                                            @else
                                                                <span class="text-success">Kuota terpenuhi!</span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('product_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <!-- Data Penerima -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                                <i class="fas fa-user me-2"></i> Data Penerima
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', Auth::user()->name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone', Auth::user()->phone) }}" 
                                           placeholder="08xxxxxxxxxx" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_city" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_city') is-invalid @enderror" 
                                           id="customer_city" name="customer_city" 
                                           value="{{ old('customer_city') }}" placeholder="Contoh: Jakarta Selatan" required>
                                    @error('customer_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', 1) }}" min="1" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="customer_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                              id="customer_address" name="customer_address" rows="3" 
                                              placeholder="Masukkan alamat lengkap beserta kode pos" required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="2" 
                                              placeholder="Catatan tambahan untuk pesanan">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                                <i class="fas fa-receipt me-2"></i> Ringkasan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Produk:</span>
                                <span id="summary-product">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Harga:</span>
                                <span id="summary-price">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jumlah:</span>
                                <span id="summary-qty">1</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-primary fs-5" id="summary-total">Rp 0</span>
                            </div>
                            
                            <div class="alert alert-info small mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Ongkos kirim dihitung terpisah setelah produk siap dikirim.
                            </div>
                            
                            <button type="submit" class="btn w-100 text-white fw-semibold py-2" 
                                    style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);"
                                    {{ $products->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-2"></i> Buat Order
                            </button>
                            
                            <p class="text-muted small text-center mt-3 mb-0">
                                Dengan memesan, Anda menyetujui syarat dan ketentuan yang berlaku.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Product Image in Order Form */
    .product-order-image {
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #f0f0f0;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    
    .product-order-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-order-image img {
        transform: scale(1.05);
    }
    
    .product-card {
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
    }
    .product-card:hover {
        border-color: #d4a017 !important;
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
    }
    .product-card.selected {
        border-color: #d4a017 !important;
        background-color: rgba(212, 160, 23, 0.05);
        box-shadow: 0 6px 16px rgba(212, 160, 23, 0.3);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productCards = document.querySelectorAll('.product-card');
        const quantityInput = document.getElementById('quantity');
        let selectedPrice = 0;

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function updateSummary() {
            const qty = parseInt(quantityInput.value) || 1;
            document.getElementById('summary-qty').textContent = qty;
            document.getElementById('summary-total').textContent = formatRupiah(selectedPrice * qty);
        }

        productCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('.product-radio');
                radio.checked = true;
                
                productCards.forEach(c => c.classList.remove('selected', 'border-warning'));
                this.classList.add('selected', 'border-warning');
                
                selectedPrice = parseInt(this.dataset.price);
                const productName = this.querySelector('h6').textContent;
                
                document.getElementById('summary-product').textContent = productName;
                document.getElementById('summary-price').textContent = formatRupiah(selectedPrice);
                
                updateSummary();
            });
        });

        quantityInput.addEventListener('input', updateSummary);

        // Initialize if product is already selected
        const checkedRadio = document.querySelector('.product-radio:checked');
        if (checkedRadio) {
            checkedRadio.closest('.product-card').click();
        }
    });
</script>
@endpush
@endsection
