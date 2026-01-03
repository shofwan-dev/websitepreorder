@extends('layouts.app')

@section('title', 'Edit Order #' . $order->id . ' - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi'))

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container py-4">
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h3 fw-bold mb-1" style="color: #8b6b2d;">
                    <i class="fas fa-edit me-2"></i> Edit Order #{{ $order->id }}
                </h1>
                <p class="text-muted mb-0">Perbarui data pemesanan Anda</p>
            </div>
            <a href="{{ route('user.orders.show', $order) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        @php
            $freeShippingCode = $site_settings['free_shipping_code'] ?? '';
        @endphp

        @if(!empty($freeShippingCode))
        <!-- Free Shipping Marketing Banner -->
        <div class="mb-4 animate__animated animate__fadeInDown">
            <div class="card border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-warning text-dark px-3 py-2 me-2 animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-fire"></i> PROMO TERBATAS
                                </span>
                                <span class="badge bg-danger px-3 py-2 animate__animated animate__heartBeat animate__infinite animate__slow">
                                    <i class="fas fa-clock"></i> HARI INI SAJA!
                                </span>
                            </div>
                            <h4 class="text-white fw-bold mb-2">
                                🎉 SELAMAT! Kamu Beruntung!
                            </h4>
                            <p class="text-white mb-2 opacity-90">
                                Dapatkan <strong class="text-warning fs-5">GRATIS ONGKIR</strong> untuk order hari ini!
                            </p>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-white small">Pakai kode:</span>
                                <div class="position-relative">
                                    <code class="bg-white text-dark px-4 py-2 rounded fw-bold fs-6 d-inline-block" 
                                          style="letter-spacing: 2px; cursor: pointer;"
                                          onclick="copyCode('{{ $freeShippingCode }}')"
                                          id="shipping-code">
                                        {{ $freeShippingCode }}
                                    </code>
                                    <i class="fas fa-copy position-absolute end-0 top-50 translate-middle-y me-2 text-muted" 
                                       style="pointer-events: none; font-size: 0.8rem;"></i>
                                </div>
                                <button type="button" 
                                        class="btn btn-warning btn-sm" 
                                        onclick="copyCode('{{ $freeShippingCode }}')">
                                    <i class="fas fa-copy"></i> Salin Kode
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="position-relative">
                                <div class="display-1 text-warning fw-bold mb-0" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('user.orders.update', $order) }}">
            @csrf
            @method('PUT')
            
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
                            <div class="row g-3">
                                @foreach($products as $product)
                                <div class="col-md-6">
                                    <div class="card h-100 product-card border-2 {{ old('product_id', $order->product_id) == $product->id ? 'border-warning' : '' }}"
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
                                        <div class="product-order-image">
                                            <img src="{{ asset('storage/' . $images[0]) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="card-img-top">
                                        </div>
                                        @else
                                        <div class="product-order-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                        @endif
                                        
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input product-radio" type="radio" 
                                                       name="product_id" value="{{ $product->id }}" 
                                                       id="product{{ $product->id }}"
                                                       {{ old('product_id', $order->product_id) == $product->id ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="product{{ $product->id }}">
                                                    <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                                    <p class="text-primary fw-bold mb-1">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-muted small mb-0">{{ Str::limit($product->description, 60) }}</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
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
                                           value="{{ old('customer_name', $order->customer_name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" 
                                           value="{{ old('customer_phone', $order->customer_phone) }}" 
                                           placeholder="08xxxxxxxxxx" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" 
                                           value="{{ old('quantity', $order->quantity) }}" min="1" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="province_id" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('province_id') is-invalid @enderror" 
                                            id="province_id" name="province_id" required>
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $order->province_name) }}">
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="city_id" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                    <select class="form-select @error('city_id') is-invalid @enderror" 
                                            id="city_id" name="city_id" required disabled>
                                        <option value="">Pilih Kota</option>
                                    </select>
                                    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $order->city_name) }}">
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="courier" class="form-label">Kurir <span class="text-danger">*</span></label>
                                    <select class="form-select @error('courier') is-invalid @enderror" 
                                            id="courier" name="courier" required>
                                        <option value="">Pilih Kurir</option>
                                        <option value="jne" {{ old('courier', $order->courier) == 'jne' ? 'selected' : '' }}>JNE</option>
                                        <option value="pos" {{ old('courier', $order->courier) == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                                        <option value="tiki" {{ old('courier', $order->courier) == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                    </select>
                                    @error('courier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="courier_service" class="form-label">Layanan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('courier_service') is-invalid @enderror" 
                                            id="courier_service" name="courier_service" required disabled>
                                        <option value="">Pilih Layanan</option>
                                    </select>
                                    @error('courier_service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="customer_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                              id="customer_address" name="customer_address" rows="3" 
                                              placeholder="Masukkan alamat lengkap beserta kode pos" required>{{ old('customer_address', $order->customer_address) }}</textarea>
                                    @error('customer_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="2" 
                                              placeholder="Catatan tambahan untuk pesanan">{{ old('notes', $order->notes) }}</textarea>
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
                                <i class="fas fa-receipt me-2"></i> Ringkasan Perubahan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Produk:</span>
                                <span id="summary-product">{{ $order->product->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Harga:</span>
                                <span id="summary-price">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jumlah:</span>
                                <span id="summary-qty">{{ $order->quantity }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span id="summary-subtotal">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ongkir:</span>
                                <span id="summary-shipping">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 {{ $order->free_shipping_code ? '' : 'd-none' }}" id="discount-row">
                                <span class="text-success">Voucher:</span>
                                <span id="summary-discount" class="text-success">- Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="mb-3">
                                <label for="free_shipping_code_input" class="form-label small fw-bold text-muted">Kode Promo / Gratis Ongkir</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="free_shipping_code_input" 
                                           name="applied_free_shipping_code" placeholder="Punya kode?"
                                           value="{{ old('applied_free_shipping_code', $order->free_shipping_code) }}">
                                    <button class="btn btn-outline-primary" type="button" id="btn-apply-code">Pakai</button>
                                </div>
                                <div id="code-feedback" class="small mt-1 d-none"></div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between mb-3 text-primary">
                                <span class="fw-bold">Total Tagihan:</span>
                                <span class="fw-bold fs-5" id="summary-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="alert alert-success border-0 small mb-3 py-2" id="shipping-success-info" style="{{ $order->shipping_cost > 0 ? '' : 'display: none;' }}">
                                <i class="fas fa-check-circle me-1"></i>
                                Ongkos kirim berhasil dihitung!
                            </div>
                            
                            <button type="submit" class="btn w-100 text-white fw-semibold py-2" 
                                    style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            
                            <p class="text-muted small text-center mt-3 mb-0">
                                Pastikan data sudah benar sebelum menyimpan.
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
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        const courierSelect = document.getElementById('courier');
        const serviceSelect = document.getElementById('courier_service');
        const btnApplyCode = document.getElementById('btn-apply-code');
        const codeInput = document.getElementById('free_shipping_code_input');

        let selectedPrice = {{ $order->price }};
        let shippingCost = {{ $order->shipping_cost }};
        let discountAmount = {{ $order->free_shipping_code ? $order->shipping_cost : 0 }};
        let isFreeShipping = {{ $order->free_shipping_code ? 'true' : 'false' }};
        
        let initialProvinceId = "{{ old('province_id', $order->province_id) }}";
        let initialCityId = "{{ old('city_id', $order->city_id) }}";
        let initialService = "{{ old('courier_service', $order->courier_service) }}";

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function updateSummary() {
            const qty = parseInt(quantityInput.value) || 1;
            const subtotal = selectedPrice * qty;
            
            if (isFreeShipping) {
                discountAmount = shippingCost;
                document.getElementById('discount-row').classList.remove('d-none');
                document.getElementById('summary-discount').textContent = '- ' + formatRupiah(discountAmount);
            } else {
                discountAmount = 0;
                document.getElementById('discount-row').classList.add('d-none');
            }

            const total = subtotal + shippingCost - discountAmount;

            document.getElementById('summary-qty').textContent = qty;
            document.getElementById('summary-subtotal').textContent = formatRupiah(subtotal);
            document.getElementById('summary-shipping').textContent = formatRupiah(shippingCost);
            document.getElementById('summary-total').textContent = formatRupiah(total);
        }

        // Fetch Provinces
        console.log('Fetching provinces from:', '{{ route('user.shipping.provinces') }}');
        fetch('{{ route('user.shipping.provinces') }}')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok: ' + response.statusText);
                return response.json();
            })
            .then(result => {
                console.log('Provinces Result:', result);
                if (result.success) {
                    result.data.forEach(province => {
                        // Komerce API uses 'id' and 'name' fields
                        const option = new Option(province.name, province.id);
                        if (province.id == initialProvinceId) {
                            option.selected = true;
                        }
                        provinceSelect.add(option);
                    });
                    
                    if (initialProvinceId) {
                        loadCities(initialProvinceId, initialCityId);
                    }
                } else {
                    console.error('Provinces Error Status:', result.message);
                    alert('Gagal memuat data provinsi: ' + (result.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error fetching provinces:', error);
            });

        function loadCities(provinceId, selectedCityId = null) {
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;
            
            fetch(`{{ url('my/shipping/cities') }}/${provinceId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        result.data.forEach(city => {
                            // Komerce API uses 'id' and 'name' fields
                            const option = new Option(city.name, city.id);
                            if (selectedCityId && city.id == selectedCityId) {
                                option.selected = true;
                            }
                            citySelect.add(option);
                        });
                        citySelect.disabled = false;
                        
                        if (selectedCityId && courierSelect.value) {
                            calculateShipping(initialService);
                        }
                    }
                });
        }

        provinceSelect.addEventListener('change', function() {
            if (this.value) {
                document.getElementById('province_name').value = this.options[this.selectedIndex].text;
                loadCities(this.value);
            } else {
                citySelect.disabled = true;
                shippingCost = 0;
                updateSummary();
            }
        });

        citySelect.addEventListener('change', function() {
            if (this.value) {
                document.getElementById('city_name').value = this.options[this.selectedIndex].text;
                if (courierSelect.value) calculateShipping();
            }
        });

        courierSelect.addEventListener('change', function() {
            if (this.value && citySelect.value) calculateShipping();
        });

        function calculateShipping(selectedService = null) {
            const formData = new FormData();
            formData.append('destination', citySelect.value);
            formData.append('weight', 1000 * (parseInt(quantityInput.value) || 1));
            formData.append('courier', courierSelect.value);
            formData.append('_token', '{{ csrf_token() }}');

            serviceSelect.innerHTML = '<option value="">Menghitung...</option>';
            serviceSelect.disabled = true;

            fetch('{{ route('user.shipping.calculate') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                if (result.success && result.data && result.data.length > 0) {
                    // Check if this is Komerce API response (flat structure)
                    if (result.data[0].cost !== undefined) {
                        // Komerce API structure
                        result.data.forEach(item => {
                            const option = new Option(
                                `${item.service} - ${formatRupiah(item.cost)} (${item.etd})`, 
                                item.service
                            );
                            option.dataset.price = item.cost;
                            if (selectedService && item.service == selectedService) {
                                option.selected = true;
                                shippingCost = item.cost;
                                document.getElementById('shipping-success-info').style.display = 'block';
                            }
                            serviceSelect.add(option);
                        });
                    } else if (result.data[0].costs !== undefined) {
                        // Official RajaOngkir structure
                        result.data[0].costs.forEach(item => {
                            const option = new Option(
                                `${item.service} - ${formatRupiah(item.cost[0].value)} (${item.cost[0].etd} hari)`, 
                                item.service
                            );
                            option.dataset.price = item.cost[0].value;
                            if (selectedService && item.service == selectedService) {
                                option.selected = true;
                                shippingCost = item.cost[0].value;
                                document.getElementById('shipping-success-info').style.display = 'block';
                            }
                            serviceSelect.add(option);
                        });
                    }
                    serviceSelect.disabled = false;
                    updateSummary();
                }
            });
        }

        serviceSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            shippingCost = opt.dataset.price ? parseInt(opt.dataset.price) : 0;
            document.getElementById('shipping-success-info').style.display = shippingCost > 0 ? 'block' : 'none';
            updateSummary();
        });

        btnApplyCode.addEventListener('click', function() {
            const code = codeInput.value.trim();
            const feedback = document.getElementById('code-feedback');
            if (!code) return;
            btnApplyCode.disabled = true;
            const formData = new FormData();
            formData.append('code', code);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('user.shipping.validate-code') }}', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(result => {
                feedback.classList.remove('d-none', 'text-success', 'text-danger');
                if (result.success) {
                    isFreeShipping = true;
                    feedback.textContent = result.message;
                    feedback.classList.add('text-success');
                } else {
                    isFreeShipping = false;
                    feedback.textContent = result.message;
                    feedback.classList.add('text-danger');
                }
                updateSummary();
            })
            .finally(() => { btnApplyCode.disabled = false; });
        });

        productCards.forEach(card => {
            card.addEventListener('click', function() {
                productCards.forEach(c => c.classList.remove('selected', 'border-warning'));
                this.classList.add('selected', 'border-warning');
                this.querySelector('.product-radio').checked = true;
                selectedPrice = parseInt(this.dataset.price);
                document.getElementById('summary-product').textContent = this.querySelector('h6').textContent;
                document.getElementById('summary-price').textContent = formatRupiah(selectedPrice);
                updateSummary();
                if (citySelect.value && courierSelect.value) calculateShipping();
            });
        });

        quantityInput.addEventListener('input', function() {
            updateSummary();
            if (citySelect.value && courierSelect.value) {
                calculateShipping(serviceSelect.value);
            }
        });
    });

    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            const codeEl = document.getElementById('shipping-code');
            const originalText = codeEl.textContent;
            codeEl.textContent = 'TERSALIN!';
            setTimeout(() => { codeEl.textContent = originalText; }, 2000);
        });
    }
</script>
@endpush
@endsection
