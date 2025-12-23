@extends('layouts.admin')

@section('title', 'Pengaturan SEO')
@section('page-title', 'Pengaturan SEO')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item active">SEO</li>
        </ol>
    </nav>
    <h1 class="page-title">Pengaturan SEO</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.settings.seo.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            {{-- Meta Tags Section --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-tags me-2"></i>Meta Tags
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="seo_title" class="form-label">
                            SEO Title <small class="text-muted">(Maks. 60 karakter)</small>
                        </label>
                        <input type="text" 
                               class="form-control @error('seo_title') is-invalid @enderror" 
                               id="seo_title" 
                               name="seo_title" 
                               value="{{ old('seo_title', $settings['seo_title'] ?? '') }}"
                               maxlength="60"
                               placeholder="PO Kaligrafi Lampu - Pre-Order Kaligrafi Lampu Islami">
                        <div class="form-text">
                            <span id="titleCount">0</span>/60 karakter
                        </div>
                        @error('seo_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="seo_description" class="form-label">
                            Meta Description <small class="text-muted">(150-160 karakter)</small>
                        </label>
                        <textarea class="form-control @error('seo_description') is-invalid @enderror" 
                                  id="seo_description" 
                                  name="seo_description" 
                                  rows="3"
                                  maxlength="160"
                                  placeholder="Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim. Pre-order kaligrafi lampu dengan harga terjangkau dan kualitas terbaik.">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                        <div class="form-text">
                            <span id="descCount">0</span>/160 karakter
                        </div>
                        @error('seo_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="seo_keywords" class="form-label">
                            Meta Keywords <small class="text-muted">(Pisahkan dengan koma)</small>
                        </label>
                        <textarea class="form-control @error('seo_keywords') is-invalid @enderror" 
                                  id="seo_keywords" 
                                  name="seo_keywords" 
                                  rows="2"
                                  placeholder="kaligrafi lampu, pre order kaligrafi, lampu islami, dekorasi islami">{{ old('seo_keywords', $settings['seo_keywords'] ?? '') }}</textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Contoh: kaligrafi lampu, pre order, dekorasi islami, lampu kaligrafi
                        </div>
                        @error('seo_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="seo_author" class="form-label">Author</label>
                        <input type="text" 
                               class="form-control @error('seo_author') is-invalid @enderror" 
                               id="seo_author" 
                               name="seo_author" 
                               value="{{ old('seo_author', $settings['seo_author'] ?? '') }}"
                               placeholder="PO Kaligrafi Lampu">
                        @error('seo_author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Open Graph Section --}}
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fab fa-facebook me-2"></i>Open Graph (Facebook)
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" 
                               class="form-control @error('og_title') is-invalid @enderror" 
                               id="og_title" 
                               name="og_title" 
                               value="{{ old('og_title', $settings['og_title'] ?? '') }}"
                               placeholder="Kosongkan untuk menggunakan SEO Title">
                        <div class="form-text">
                            Kosongkan untuk menggunakan SEO Title secara otomatis
                        </div>
                        @error('og_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="og_description" class="form-label">OG Description</label>
                        <textarea class="form-control @error('og_description') is-invalid @enderror" 
                                  id="og_description" 
                                  name="og_description" 
                                  rows="2"
                                  placeholder="Kosongkan untuk menggunakan Meta Description">{{ old('og_description', $settings['og_description'] ?? '') }}</textarea>
                        <div class="form-text">
                            Kosongkan untuk menggunakan Meta Description secara otomatis
                        </div>
                        @error('og_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="og_image" class="form-label">
                            OG Image <small class="text-muted">(1200x630px recommended)</small>
                        </label>
                        
                        @if(!empty($settings['og_image'] ?? ''))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings['og_image']) }}" 
                                 alt="OG Image Preview" 
                                 class="img-thumbnail"
                                 style="max-height: 200px;">
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="remove_og_image" name="remove_og_image" value="1">
                                <label class="form-check-label text-danger" for="remove_og_image">
                                    Hapus gambar
                                </label>
                            </div>
                        </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('og_image') is-invalid @enderror" 
                               id="og_image" 
                               name="og_image"
                               accept="image/*">
                        <div class="form-text">
                            Ukuran optimal: 1200x630px. Format: JPG, PNG, WebP
                        </div>
                        @error('og_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Twitter Card Section --}}
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fab fa-twitter me-2"></i>Twitter Card
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="twitter_title" class="form-label">Twitter Title</label>
                        <input type="text" 
                               class="form-control" 
                               id="twitter_title" 
                               name="twitter_title" 
                               value="{{ old('twitter_title', $settings['twitter_title'] ?? '') }}"
                               placeholder="Kosongkan untuk menggunakan SEO Title">
                        <div class="form-text">
                            Kosongkan untuk menggunakan SEO Title secara otomatis
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="twitter_description" class="form-label">Twitter Description</label>
                        <textarea class="form-control" 
                                  id="twitter_description" 
                                  name="twitter_description" 
                                  rows="2"
                                  placeholder="Kosongkan untuk menggunakan Meta Description">{{ old('twitter_description', $settings['twitter_description'] ?? '') }}</textarea>
                        <div class="form-text">
                            Kosongkan untuk menggunakan Meta Description secara otomatis
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional SEO Section --}}
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-cog me-2"></i>Pengaturan Tambahan
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="google_analytics" class="form-label">
                            Google Analytics ID <small class="text-muted">(G-XXXXXXXXXX)</small>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="google_analytics" 
                               name="google_analytics" 
                               value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                               placeholder="G-XXXXXXXXXX">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Masukkan ID Google Analytics untuk tracking visitor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="google_search_console" class="form-label">
                            Google Search Console Verification Code
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="google_search_console" 
                               name="google_search_console" 
                               value="{{ old('google_search_console', $settings['google_search_console'] ?? '') }}"
                               placeholder="kode-verifikasi">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Kode verifikasi dari Google Search Console
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="seo_noindex" 
                               name="seo_noindex" 
                               value="1"
                               {{ old('seo_noindex', $settings['seo_noindex'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="seo_noindex">
                            <strong class="text-danger">NoIndex Website</strong>
                            <small class="d-block text-muted">
                                Centang untuk mencegah search engine mengindex website (useful saat development)
                            </small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Pengaturan SEO
                </button>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-lightbulb me-2"></i>Tips SEO
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Title Tag</h6>
                <ul class="small">
                    <li>Maksimal 60 karakter</li>
                    <li>Gunakan keyword utama</li>
                    <li>Menarik dan deskriptif</li>
                </ul>

                <h6 class="fw-bold mt-3">Meta Description</h6>
                <ul class="small">
                    <li>Ideal 150-160 karakter</li>
                    <li>Jelaskan value proposition</li>
                    <li>Sertakan call-to-action</li>
                </ul>

                <h6 class="fw-bold mt-3">Keywords</h6>
                <ul class="small">
                    <li>5-10 keyword relevan</li>
                    <li>Gunakan long-tail keywords</li>
                    <li>Pisahkan dengan koma</li>
                </ul>

                <h6 class="fw-bold mt-3">OG Image</h6>
                <ul class="small">
                    <li>Ukuran: 1200x630px</li>
                    <li>Format: JPG atau PNG</li>
                    <li>Maksimal 5MB</li>
                </ul>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-tools me-2"></i>SEO Tools
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('sitemap') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sitemap me-1"></i> View Sitemap
                    </a>
                    <a href="/robots.txt" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-robot me-1"></i> View Robots.txt
                    </a>
                    <a href="https://search.google.com/search-console" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fab fa-google me-1"></i> Google Search Console
                    </a>
                    <a href="https://developers.facebook.com/tools/debug/" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fab fa-facebook me-1"></i> Facebook Debugger
                    </a>
                    <a href="https://cards-dev.twitter.com/validator" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="fab fa-twitter me-1"></i> Twitter Card Validator
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Character counter
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('seo_title');
    const descInput = document.getElementById('seo_description');
    const titleCount = document.getElementById('titleCount');
    const descCount = document.getElementById('descCount');
    
    function updateCount() {
        if (titleInput && titleCount) {
            titleCount.textContent = titleInput.value.length;
            titleCount.className = titleInput.value.length > 60 ? 'text-danger fw-bold' : '';
        }
        if (descInput && descCount) {
            descCount.textContent = descInput.value.length;
            if (descInput.value.length < 150) {
                descCount.className = 'text-warning fw-bold';
            } else if (descInput.value.length > 160) {
                descCount.className = 'text-danger fw-bold';
            } else {
                descCount.className = 'text-success fw-bold';
            }
        }
    }
    
    titleInput?.addEventListener('input', updateCount);
    descInput?.addEventListener('input', updateCount);
    updateCount();
});
</script>
@endpush

@endsection
