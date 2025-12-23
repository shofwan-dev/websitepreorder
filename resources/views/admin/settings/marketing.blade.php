@extends('layouts.admin')

@section('title', 'Pengaturan Marketing & Tracking')
@section('page-title', 'Marketing & Tracking')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
                <li class="breadcrumb-item active">Marketing & Tracking</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('admin.settings.marketing.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Facebook Pixel -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-facebook text-primary me-2"></i>Facebook Pixel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facebook Pixel ID</label>
                        <input type="text" name="facebook_pixel_id" class="form-control" 
                               value="{{ $marketing_settings['facebook_pixel_id'] ?? '' }}"
                               placeholder="Contoh: 1234567890123456">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Dapatkan Pixel ID dari <a href="https://business.facebook.com/events_manager" target="_blank">Facebook Events Manager</a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="facebook_pixel_enabled" 
                                   id="fbPixelEnabled" value="1"
                                   {{ ($marketing_settings['facebook_pixel_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="fbPixelEnabled">Aktifkan Facebook Pixel</label>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <strong><i class="fab fa-facebook me-1"></i> Events yang Dilacak:</strong>
                        <ul class="mb-0 mt-2">
                            <li><code>PageView</code> - Setiap halaman dikunjungi</li>
                            <li><code>ViewContent</code> - Halaman produk</li>
                            <li><code>AddToCart</code> - Klik Ikut PO</li>
                            <li><code>InitiateCheckout</code> - Halaman checkout</li>
                            <li><code>Purchase</code> - Pembayaran sukses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Tag Manager -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-google text-warning me-2"></i>Google Tag Manager
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">GTM Container ID</label>
                        <input type="text" name="gtm_container_id" class="form-control" 
                               value="{{ $marketing_settings['gtm_container_id'] ?? '' }}"
                               placeholder="Contoh: GTM-XXXXXXX">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Dapatkan Container ID dari <a href="https://tagmanager.google.com/" target="_blank">Google Tag Manager</a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="gtm_enabled" 
                                   id="gtmEnabled" value="1"
                                   {{ ($marketing_settings['gtm_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gtmEnabled">Aktifkan Google Tag Manager</label>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <strong><i class="fab fa-google me-1"></i> Keuntungan GTM:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Kelola semua tracking dari satu tempat</li>
                            <li>Tambah Google Analytics, Ads, dll</li>
                            <li>Custom events tanpa edit kode</li>
                            <li>Debug mode untuk testing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Analytics -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>Google Analytics 4
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">GA4 Measurement ID</label>
                        <input type="text" name="google_analytics_id" class="form-control" 
                               value="{{ $marketing_settings['google_analytics_id'] ?? '' }}"
                               placeholder="Contoh: G-XXXXXXXXXX">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Dapatkan ID dari <a href="https://analytics.google.com/" target="_blank">Google Analytics</a> → Admin → Data Streams
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="google_analytics_enabled" 
                                   id="gaEnabled" value="1"
                                   {{ ($marketing_settings['google_analytics_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="gaEnabled">Aktifkan Google Analytics</label>
                        </div>
                    </div>

                    <div class="alert alert-success mb-0">
                        <strong><i class="fas fa-chart-bar me-1"></i> Data yang Dilacak:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pengunjung & sumber traffic</li>
                            <li>Halaman populer</li>
                            <li>Konversi & goals</li>
                            <li>Demografi pengunjung</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- UTM Tracking -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-link text-info me-2"></i>UTM Tracking
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="utm_tracking_enabled" 
                                   id="utmEnabled" value="1"
                                   {{ ($marketing_settings['utm_tracking_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="utmEnabled">Aktifkan UTM Tracking</label>
                        </div>
                        <div class="form-text">
                            UTM parameters akan disimpan di order untuk tracking sumber customer.
                        </div>
                    </div>

                    <div class="alert alert-info mb-3">
                        <strong><i class="fas fa-tag me-1"></i> UTM Parameters:</strong>
                        <div class="mt-2">
                            <code class="d-block mb-1">utm_source</code> - Sumber (facebook, google, instagram)
                            <code class="d-block mb-1">utm_medium</code> - Media (cpc, social, email)
                            <code class="d-block mb-1">utm_campaign</code> - Nama kampanye
                            <code class="d-block mb-1">utm_content</code> - Konten iklan
                            <code class="d-block">utm_term</code> - Keyword
                        </div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body p-2">
                            <strong class="small">Contoh URL dengan UTM:</strong>
                            <code class="d-block small text-wrap mt-1" style="word-break: break-all;">
                                {{ url('/') }}?utm_source=facebook&utm_medium=cpc&utm_campaign=ramadhan_sale
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TikTok Pixel (Bonus) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-dark bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-tiktok me-2"></i>TikTok Pixel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">TikTok Pixel ID</label>
                        <input type="text" name="tiktok_pixel_id" class="form-control" 
                               value="{{ $marketing_settings['tiktok_pixel_id'] ?? '' }}"
                               placeholder="Contoh: CXXXXXXXXXXXXXXXXX">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Dapatkan Pixel ID dari <a href="https://ads.tiktok.com/" target="_blank">TikTok Ads Manager</a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="tiktok_pixel_enabled" 
                                   id="tiktokEnabled" value="1"
                                   {{ ($marketing_settings['tiktok_pixel_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tiktokEnabled">Aktifkan TikTok Pixel</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Scripts -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-secondary bg-opacity-10">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-code text-secondary me-2"></i>Custom Scripts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Custom Head Scripts</label>
                        <textarea name="custom_head_scripts" class="form-control font-monospace" rows="4"
                                  placeholder="Paste custom scripts here (akan ditambahkan di <head>)">{{ $marketing_settings['custom_head_scripts'] ?? '' }}</textarea>
                        <div class="form-text text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Hati-hati! Script yang salah bisa merusak website.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i>Simpan Pengaturan
        </button>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</form>

@push('styles')
<style>
    .font-monospace {
        font-family: 'Courier New', monospace;
        font-size: 12px;
    }
</style>
@endpush
@endsection
