@extends('layouts.app')

@section('title', 'Masuk - ' . ($site_settings['site_name'] ?? 'Pre-Order'))

@section('content')
<div class="auth-container">
    <!-- Animated Background -->
    <div class="auth-background">
        <div class="gradient-bg"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
    </div>

    <div class="container">
        <div class="row min-vh-100 align-items-center py-5">
            <div class="col-lg-10 col-xl-8 mx-auto">
                <div class="row g-0 auth-card shadow-2xl animate-fade-in-up">
                    <!-- Left Side - Branding & Features -->
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="auth-branding h-100">
                            <div class="branding-content">
                                <!-- Logo & Title -->
                                <div class="text-center mb-5 animate-fade-in">
                                    @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                                        <div class="logo-wrapper mb-4">
                                            <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" 
                                                 alt="Logo" 
                                                 class="logo-image">
                                        </div>
                                    @else
                                        <div class="logo-placeholder mb-4">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    @endif
                                    <h2 class="brand-title">{{ $site_settings['site_name'] ?? 'Pre-Order' }}</h2>
                                    <p class="brand-subtitle">Selamat Datang Kembali!</p>
                                </div>

                                <!-- Features List -->
                                <div class="benefits-list">
                                    <div class="benefit-item animate-slide-in-left" style="animation-delay: 0.2s">
                                        <div class="benefit-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h5>Akses Cepat</h5>
                                            <p>Langsung ke dashboard Anda</p>
                                        </div>
                                    </div>

                                    <div class="benefit-item animate-slide-in-left" style="animation-delay: 0.4s">
                                        <div class="benefit-icon">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h5>Pesanan Tersimpan</h5>
                                            <p>Lihat semua riwayat PO Anda</p>
                                        </div>
                                    </div>

                                    <div class="benefit-item animate-slide-in-left" style="animation-delay: 0.6s">
                                        <div class="benefit-icon">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h5>Profil Personal</h5>
                                            <p>Checkout lebih cepat & mudah</p>
                                        </div>
                                    </div>

                                    <div class="benefit-item animate-slide-in-left" style="animation-delay: 0.8s">
                                        <div class="benefit-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="benefit-text">
                                            <h5>Penawaran Eksklusif</h5>
                                            <p>Dapatkan promo khusus member</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Trust Badge -->
                                <div class="trust-badge mt-5 text-center">
                                    <div class="badge-container">
                                        <i class="fas fa-shield-alt mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0 small">Data Anda Aman & Terenkripsi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-lg-7">
                        <div class="auth-form-wrapper">
                            <!-- Welcome Text -->
                            <div class="auth-header animate-fade-in-down mb-4">
                                <h1 class="auth-title">Masuk ke Akun</h1>
                                <p class="auth-description">Lanjutkan perjalanan belanja Pre-Order Anda</p>
                            </div>

                            <!-- Session Status (if any) -->
                            @if (session('status'))
                                <div class="alert alert-success mb-4 animate-fade-in">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
                                @csrf

                                <!-- Email -->
                                <div class="form-group floating-label-group animate-slide-in-right" style="animation-delay: 0.1s">
                                    <div class="input-group-custom">
                                        <span class="input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input 
                                            id="email" 
                                            type="email" 
                                            name="email" 
                                            value="{{ old('email') }}" 
                                            required 
                                            autocomplete="email"
                                            autofocus
                                            class="form-control form-control-modern @error('email') is-invalid @enderror"
                                            placeholder=" "
                                        >
                                        <label for="email" class="floating-label">Alamat Email</label>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group floating-label-group animate-slide-in-right" style="animation-delay: 0.2s">
                                    <div class="input-group-custom">
                                        <span class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input 
                                            id="password" 
                                            type="password" 
                                            name="password" 
                                            required 
                                            autocomplete="current-password"
                                            class="form-control form-control-modern @error('password') is-invalid @enderror"
                                            placeholder=" "
                                        >
                                        <label for="password" class="floating-label">Kata Sandi</label>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="d-flex justify-content-between align-items-center mb-4 animate-slide-in-right" style="animation-delay: 0.3s">
                                    <div class="custom-checkbox-wrapper">
                                        <input 
                                            type="checkbox" 
                                            id="remember" 
                                            name="remember" 
                                            class="custom-checkbox"
                                            {{ old('remember') ? 'checked' : '' }}
                                        >
                                        <label for="remember" class="custom-checkbox-label">
                                            Ingat saya
                                        </label>
                                    </div>
                                    
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="link-primary">
                                            <small><i class="fas fa-key me-1"></i>Lupa Password?</small>
                                        </a>
                                    @endif
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary-custom btn-lg w-100 animate-slide-in-right" style="animation-delay: 0.4s">
                                    <span class="btn-content">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <span>Masuk ke Akun</span>
                                    </span>
                                    <div class="btn-ripple"></div>
                                </button>
                            </form>

                            <!-- Divider -->
                            <div class="divider my-4 animate-fade-in" style="animation-delay: 0.5s">
                                <span>Belum punya akun?</span>
                            </div>

                            <!-- Register Link -->
                            <a href="{{ route('register') }}" class="btn btn-outline-custom btn-lg w-100 animate-slide-in-right" style="animation-delay: 0.6s">
                                <i class="fas fa-user-plus me-2"></i>
                                <span>Daftar Akun Baru</span>
                            </a>

                            <!-- Back to Home -->
                            <div class="text-center mt-4 animate-fade-in" style="animation-delay: 0.7s">
                                <a href="{{ route('home') }}" class="link-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    <span>Kembali ke Beranda</span>
                                </a>
                            </div>

                            <!-- Mobile Features (visible on small screens) -->
                            <div class="mobile-benefits d-lg-none mt-4 animate-fade-in" style="animation-delay: 0.8s">
                                <div class="benefits-grid">
                                    <div class="benefit-mini">
                                        <i class="fas fa-clock"></i>
                                        <span>Cepat</span>
                                    </div>
                                    <div class="benefit-mini">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Riwayat</span>
                                    </div>
                                    <div class="benefit-mini">
                                        <i class="fas fa-user-circle"></i>
                                        <span>Personal</span>
                                    </div>
                                    <div class="benefit-mini">
                                        <i class="fas fa-star"></i>
                                        <span>Promo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   AUTH CONTAINER & BACKGROUND
   (Reuse same styles from register page)
============================================ */
.auth-container {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
}

.auth-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.gradient-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.1;
}

.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    border-radius: 50%;
    animation: float 20s infinite ease-in-out;
}

.shape-1 {
    width: 300px;
    height: 300px;
    top: -100px;
    left: -100px;
    animation-delay: 0s;
}

.shape-2 {
    width: 200px;
    height: 200px;
    top: 50%;
    right: -50px;
    animation-delay: 3s;
}

.shape-3 {
    width: 250px;
    height: 250px;
    bottom: -80px;
    left: 30%;
    animation-delay: 6s;
}

.shape-4 {
    width: 150px;
    height: 150px;
    top: 20%;
    right: 20%;
    animation-delay: 9s;
}

.shape-5 {
    width: 180px;
    height: 180px;
    bottom: 30%;
    left: 10%;
    animation-delay: 12s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(30px, -30px) rotate(90deg); }
    50% { transform: translate(-20px, 20px) rotate(180deg); }
    75% { transform: translate(20px, 30px) rotate(270deg); }
}

/* ============================================
   AUTH CARD
============================================ */
.auth-card {
    position: relative;
    z-index: 1;
    border-radius: 24px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}

/* ============================================
   BRANDING SIDE
============================================ */
.auth-branding {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 3rem 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.auth-branding::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.branding-content {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.logo-wrapper img,
.logo-placeholder {
    width: 100px;
    height: 100px;
    margin: 0 auto;
}

.logo-image {
    border-radius: 50%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: 4px solid rgba(255, 255, 255, 0.3);
}

.logo-placeholder {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    border: 4px solid rgba(255, 255, 255, 0.3);
}

.brand-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.brand-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Benefits List */
.benefits-list {
    margin-top: auto;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    opacity: 0;
}

.benefit-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    font-size: 1.5rem;
}

.benefit-text h5 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.benefit-text p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
}

/* Trust Badge */
.trust-badge {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.badge-container {
    opacity: 0.9;
}

/* ============================================
   FORM SIDE (Reuse from register)
============================================ */
.auth-form-wrapper {
    padding: 3rem 2.5rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.auth-description {
    color: #718096;
    font-size: 1rem;
    margin: 0;
}

/* ============================================
   FLOATING LABEL INPUTS (Reuse from register)
============================================ */
.floating-label-group {
    position: relative;
    margin-bottom: 1.75rem;
}

.input-group-custom {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 1.1rem;
    z-index: 2;
    transition: all 0.3s ease;
}

.form-control-modern {
    height: 56px;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-control-modern:focus ~ .floating-label,
.form-control-modern:not(:placeholder-shown) ~ .floating-label {
    top: -10px;
    left: 2.5rem;
    font-size: 0.75rem;
    color: #667eea;
    background: #fff;
    padding: 0 0.5rem;
}

.form-control-modern:focus ~ .input-icon {
    color: #667eea;
}

.floating-label {
    position: absolute;
    left: 3rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
    color: #a0aec0;
    pointer-events: none;
    transition: all 0.3s ease;
    background: transparent;
}

.form-control-modern.is-invalid {
    border-color: #f56565;
}

.form-control-modern.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
}

/* Password Toggle */
.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 0.5rem;
    z-index: 2;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #667eea;
}

/* ============================================
   CUSTOM CHECKBOX (Reuse from register)
============================================ */
.custom-checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.custom-checkbox {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e0;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    margin-top: 0.125rem;
    transition: all 0.3s ease;
}

.custom-checkbox:checked {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.custom-checkbox:checked::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 0.75rem;
}

.custom-checkbox-label {
    font-size: 0.9rem;
    color: #4a5568;
    cursor: pointer;
    line-height: 1.5;
}

.link-primary {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.link-primary:hover {
    color: #764ba2;
    text-decoration: underline;
}

.link-secondary {
    color: #718096;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.link-secondary:hover {
    color: #4a5568;
}

/* ============================================
   BUTTONS (Reuse from register)
============================================ */
.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.btn-primary-custom:active {
    transform: translateY(0);
}

.btn-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-outline-custom {
    background: transparent;
    border: 2px solid #667eea;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    color: #667eea;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: #667eea;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
}

/* Ripple Effect */
.btn-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    width: 0;
    height: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-primary-custom:active .btn-ripple {
    width: 300px;
    height: 300px;
}

/* ============================================
   DIVIDER (Reuse from register)
============================================ */
.divider {
    display: flex;
    align-items: center;
    text-align: center;
    color: #a0aec0;
    font-size: 0.875rem;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
}

.divider span {
    padding: 0 1rem;
}

/* ============================================
   MOBILE BENEFITS
============================================ */
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    text-align: center;
}

.benefit-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.benefit-mini:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.benefit-mini i {
    font-size: 1.5rem;
    color: #667eea;
}

.benefit-mini span {
    font-size: 0.75rem;
    color: #4a5568;
    font-weight: 500;
}

/* ============================================
   ANIMATIONS
============================================ */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-fade-in-down {
    animation: fadeInDown 0.6s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.6s ease-out;
    animation-fill-mode: both;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
    animation-fill-mode: both;
}

/* ============================================
   RESPONSIVE
============================================ */
@media (max-width: 991.98px) {
    .auth-form-wrapper {
        padding: 2rem 1.5rem;
    }
    
    .auth-title {
        font-size: 1.75rem;
    }
    
    .benefits-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 575.98px) {
    .auth-form-wrapper {
        padding: 1.5rem 1rem;
    }
    
    .auth-title {
        font-size: 1.5rem;
    }
    
    .form-control-modern {
        height: 50px;
        padding: 0.875rem 0.875rem 0.875rem 2.75rem;
    }
    
    .input-icon {
        left: 0.875rem;
    }
    
    .floating-label {
        left: 2.75rem;
    }
    
    .btn-primary-custom,
    .btn-outline-custom {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.75rem;
    }
}
</style>

<script>
// Password Toggle Function
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Add floating label animation on page load for fields with values
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-control-modern').forEach(input => {
        if (input.value) {
            input.classList.add('has-value');
        }
    });
});
</script>
@endsection