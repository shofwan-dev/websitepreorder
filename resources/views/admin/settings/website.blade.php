@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item active">Website</li>
        </ol>
    </nav>
    <h1 class="page-title">Pengaturan Website</h1>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Terdapat kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.settings.website.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Logo Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-image me-2"></i>Logo Website
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="logo-preview-container p-3 bg-light rounded" style="min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                @if(isset($settings['site_logo']) && !empty($settings['site_logo']))
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                         alt="Logo Website" 
                                         id="logoPreview"
                                         class="img-fluid rounded" 
                                         style="max-height: 120px; max-width: 100%;">
                                @else
                                    <div id="logoPlaceholder" class="text-center text-muted">
                                        <i class="fas fa-mosque fa-3x mb-2" style="color: #d4a017;"></i>
                                        <p class="mb-0 small">Belum ada logo</p>
                                    </div>
                                    <img src="" 
                                         alt="Logo Preview" 
                                         id="logoPreview"
                                         class="img-fluid rounded d-none" 
                                         style="max-height: 120px; max-width: 100%;">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Upload Logo Baru</label>
                            <input type="file" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   name="logo" 
                                   id="logoInput"
                                   accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/x-icon,image/webp">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: PNG, JPG, JPEG, SVG, ICO, WEBP. Maksimal 2MB.
                                <br>Logo akan digunakan di navigasi, favicon, dan seluruh halaman website.
                            </div>
                            
                            @if(isset($settings['site_logo']) && !empty($settings['site_logo']))
                            <div class="mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogo">
                                    <label class="form-check-label text-danger" for="removeLogo">
                                        <i class="fas fa-trash-alt me-1"></i>Hapus logo saat ini
                                    </label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Website Info Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-globe me-2"></i>Informasi Website
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('site_name') is-invalid @enderror" 
                               name="site_name" 
                               value="{{ old('site_name', $settings['site_name'] ?? 'PO Kaligrafi Lampu') }}"
                               required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tagline</label>
                        <input type="text" 
                               class="form-control @error('tagline') is-invalid @enderror" 
                               name="tagline" 
                               value="{{ old('tagline', $settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim') }}"
                               placeholder="Tagline atau slogan website">
                        @error('tagline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email', $settings['email'] ?? 'admin@pokaligrafi.com') }}"
                                   placeholder="email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   value="{{ old('phone', $settings['phone'] ?? '0812-3456-7890') }}"
                                   placeholder="0812-xxxx-xxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">WhatsApp (Publik)</label>
                            <input type="text" 
                                   class="form-control @error('whatsapp') is-invalid @enderror" 
                                   name="whatsapp" 
                                   value="{{ old('whatsapp', $settings['whatsapp'] ?? '6281234567890') }}"
                                   placeholder="6281234567890">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor WhatsApp untuk ditampilkan di website (format: 6281234567890)
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">WhatsApp Penerima Notifikasi</label>
                            <input type="text" 
                                   class="form-control @error('whatsapp_notification') is-invalid @enderror" 
                                   name="whatsapp_notification" 
                                   value="{{ old('whatsapp_notification', $settings['whatsapp_notification'] ?? $settings['whatsapp'] ?? '6281234567890') }}"
                                   placeholder="6281234567890">
                            @error('whatsapp_notification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-bell me-1"></i>
                                Nomor untuk menerima notifikasi order baru (bisa berbeda dari WhatsApp publik)
                            </div>
                        </div>
                    </div> <!-- Row for WhatsApp closed here -->
                    
                    <!-- Free Shipping Code Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info border-0 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shipping-fast fa-2x me-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">🎁 Kode Gratis Ongkir (Marketing Tool)</h6>
                                        <small>Buat kode khusus untuk customer yang beruntung! Meningkatkan konversi hingga 40%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-gift text-success me-1"></i>
                                Kode Gratis Ongkir
                            </label>
                            <input type="text" 
                                   class="form-control @error('free_shipping_code') is-invalid @enderror" 
                                   name="free_shipping_code" 
                                   value="{{ old('free_shipping_code', $settings['free_shipping_code'] ?? '') }}"
                                   placeholder="Contoh: GRATISONGKIR2026"
                                   style="text-transform: uppercase;">
                            @error('free_shipping_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                <strong>Tips Marketing:</strong> Gunakan kode yang mudah diingat dan menciptakan urgency
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-percentage text-danger me-1"></i>
                                Status Kode
                            </label>
                            <div class="form-control bg-light border-0" style="height: auto; padding: 0.75rem;">
                                @if(!empty($settings['free_shipping_code']))
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-check-circle"></i> AKTIF
                                        </span>
                                        <span class="text-muted small">
                                            Kode "<strong class="text-success">{{ $settings['free_shipping_code'] }}</strong>" akan ditampilkan di halaman order
                                        </span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary me-2">
                                            <i class="fas fa-times-circle"></i> TIDAK AKTIF
                                        </span>
                                        <span class="text-muted small">
                                            Notifikasi gratis ongkir tidak akan ditampilkan
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Kosongkan untuk menonaktifkan promo gratis ongkir
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-warning bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="card-title text-warning mb-2">
                                        <i class="fas fa-star"></i> Psikologi Marketing - Gratis Ongkir
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li><strong>FOMO (Fear of Missing Out):</strong> "Buruan! Gratis ongkir terbatas!"</li>
                                        <li><strong>Eksklusivitas:</strong> "Kode khusus untuk customer setia"</li>
                                        <li><strong>Urgency:</strong> Kombinasikan dengan countdown timer</li>
                                        <li><strong>Social Proof:</strong> "1.234 customer sudah pakai kode ini!"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Instagram</label>
                            <input type="text" 
                                   class="form-control @error('instagram') is-invalid @enderror" 
                                   name="instagram" 
                                   value="{{ old('instagram', $settings['instagram'] ?? '@pokaligrafi') }}"
                                   placeholder="@pokaligrafi">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Username Instagram (dengan atau tanpa @)
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Facebook</label>
                            <input type="text" 
                                   class="form-control @error('facebook') is-invalid @enderror" 
                                   name="facebook" 
                                   value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                                   placeholder="pokaligrafi atau URL lengkap">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Username/halaman Facebook atau URL lengkap
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Twitter/X</label>
                            <input type="text" 
                                   class="form-control @error('twitter') is-invalid @enderror" 
                                   name="twitter" 
                                   value="{{ old('twitter', $settings['twitter'] ?? '') }}"
                                   placeholder="@pokaligrafi">
                            @error('twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Username Twitter/X (dengan atau tanpa @)
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Operasional</label>
                        <input type="text" 
                               class="form-control @error('business_hours') is-invalid @enderror" 
                               name="business_hours" 
                               value="{{ old('business_hours', $settings['business_hours'] ?? 'Senin - Jumat: 09:00 - 17:00 WIB') }}"
                               placeholder="Senin - Jumat: 09:00 - 17:00 WIB">
                        @error('business_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Jam operasional bisnis untuk ditampilkan di halaman kontak
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" 
                                  rows="3"
                                  placeholder="Alamat lengkap bisnis">{{ old('address', $settings['address'] ?? 'Jl. Pengrajin No. 123, Yogyakarta') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- RajaOngkir Configuration -->
                    <div class="border-top pt-4 mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-truck-moving fa-lg me-2 text-primary"></i>
                            <h5 class="mb-0 fw-bold">Pengaturan RajaOngkir</h5>
                        </div>
                        
                        <div class="alert alert-light border small mb-3">
                            <i class="fas fa-info-circle me-1 text-info"></i>
                            Daftar di <a href="https://rajaongkir.com" target="_blank">rajaongkir.com</a> untuk mendapatkan API Key.
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">API Base URL (Opsional)</label>
                                <input type="text" 
                                       class="form-control @error('rajaongkir_base_url') is-invalid @enderror" 
                                       name="rajaongkir_base_url" 
                                       id="rajaongkir_base_url"
                                       value="{{ old('rajaongkir_base_url', $settings['rajaongkir_base_url'] ?? '') }}"
                                       placeholder="Default: https://api.rajaongkir.com/starter">
                                <div class="form-text small">
                                    Gunakan <code>https://rajaongkir.komerce.id/api/v1</code> jika Anda menggunakan API dari Komerce.
                                </div>
                                @error('rajaongkir_base_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">RajaOngkir API Key</label>
                                <input type="password" 
                                       class="form-control @error('rajaongkir_api_key') is-invalid @enderror" 
                                       name="rajaongkir_api_key" 
                                       id="rajaongkir_api_key"
                                       value="{{ old('rajaongkir_api_key', $settings['rajaongkir_api_key'] ?? '') }}"
                                       placeholder="Masukkan API Key Anda">
                                @error('rajaongkir_api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tipe Akun RajaOngkir</label>
                                <select class="form-select @error('rajaongkir_account_type') is-invalid @enderror" 
                                        name="rajaongkir_account_type">
                                    <option value="starter" {{ old('rajaongkir_account_type', $settings['rajaongkir_account_type'] ?? 'starter') == 'starter' ? 'selected' : '' }}>Starter (Gratis)</option>
                                    <option value="basic" {{ old('rajaongkir_account_type', $settings['rajaongkir_account_type'] ?? 'starter') == 'basic' ? 'selected' : '' }}>Basic (Berbayar)</option>
                                    <option value="pro" {{ old('rajaongkir_account_type', $settings['rajaongkir_account_type'] ?? 'starter') == 'pro' ? 'selected' : '' }}>Pro (Berbayar)</option>
                                </select>
                                @error('rajaongkir_account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">City ID (Asal Pengiriman)</label>
                                <input type="text" 
                                       class="form-control @error('rajaongkir_origin_city') is-invalid @enderror" 
                                       name="rajaongkir_origin_city" 
                                       value="{{ old('rajaongkir_origin_city', $settings['rajaongkir_origin_city'] ?? '151') }}"
                                       placeholder="Contoh: 151 (Bandung)">
                                @error('rajaongkir_origin_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">
                                    Bandung: 151, Jakarta: 152, Yogyakarta: 419.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Informasi
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Pengaturan ini akan mempengaruhi tampilan website publik.</p>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Nama website di header & footer</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Logo di navigasi & favicon</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Email & telepon di kontak</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">WhatsApp, Instagram, Facebook & Twitter</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Jam operasional di kontak & footer</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Tagline di halaman utama</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-lightbulb me-2"></i>Tips
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                        <i class="fas fa-image text-warning"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-1">Format Logo</strong>
                        <small class="text-muted">Gunakan PNG dengan background transparan untuk hasil terbaik.</small>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                        <i class="fas fa-expand-arrows-alt text-info"></i>
                    </div>
                    <div>
                        <strong class="d-block mb-1">Ukuran Logo</strong>
                        <small class="text-muted">Rekomendasi minimal 200x200 pixel untuk kualitas favicon.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logo preview functionality
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');
    const removeLogo = document.getElementById('removeLogo');
    
    logoInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove('d-none');
                if (logoPlaceholder) {
                    logoPlaceholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(file);
            
            // Uncheck remove logo if selecting new file
            if (removeLogo) {
                removeLogo.checked = false;
            }
        }
    });
    
    // Disable file input when remove is checked
    removeLogo?.addEventListener('change', function() {
        if (this.checked) {
            logoInput.value = '';
            logoInput.disabled = true;
        } else {
            logoInput.disabled = false;
        }
    });
</script>
@endpush
