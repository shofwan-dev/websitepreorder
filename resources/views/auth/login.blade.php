@extends('layouts.app')

@section('title', 'Masuk - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <!-- Logo Header -->
                <div class="text-center mb-5">
                    @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                        <div class="mx-auto mb-4" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" alt="Logo" class="img-fluid" style="max-width: 100px; max-height: 100px; border-radius: 50%; box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);">
                        </div>
                    @else
                        <div class="mx-auto mb-4" style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);">
                            <i class="fas fa-mosque text-white fs-1"></i>
                        </div>
                    @endif
                    <h1 class="h2 fw-bold mb-2" style="color: #8b6b2d; font-family: 'Georgia', serif;">
                        Selamat Datang
                    </h1>
                    <p class="text-muted">
                        Masuk ke akun {{ $site_settings['site_name'] ?? 'PO Kaligrafi' }} Anda
                    </p>
                </div>
                
                <!-- Login Form Card -->
                <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold" style="color: #5a4a2a;">
                                    <i class="fas fa-envelope me-2" style="color: #d4a017;"></i> Alamat Email
                                </label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autocomplete="email" 
                                    autofocus
                                    class="form-control form-control-lg py-3 @error('email') is-invalid @enderror"
                                    placeholder="email@contoh.com"
                                    style="border-color: #e6d7b8;"
                                >
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold" style="color: #5a4a2a;">
                                    <i class="fas fa-lock me-2" style="color: #d4a017;"></i> Kata Sandi
                                </label>
                                <div class="input-group">
                                    <input 
                                        id="password" 
                                        type="password" 
                                        name="password" 
                                        required 
                                        autocomplete="current-password"
                                        class="form-control form-control-lg py-3 @error('password') is-invalid @enderror"
                                        placeholder="••••••••"
                                        style="border-color: #e6d7b8;"
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-color: #e6d7b8;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4">
                                <div class="form-check mb-2 mb-sm-0">
                                    <input 
                                        id="remember" 
                                        name="remember" 
                                        type="checkbox"
                                        class="form-check-input"
                                        style="border-color: #d4a017;"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    <label for="remember" class="form-check-label text-muted">
                                        Ingat saya
                                    </label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #d4a017;">
                                        <small><i class="fas fa-question-circle me-1"></i> Lupa kata sandi?</small>
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100 py-3 mb-4 fw-bold text-white border-0" 
                                    style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%); font-size: 1.1rem; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(212, 160, 23, 0.4);">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Akun
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="position-relative my-4">
                            <hr class="my-4">
                            <div class="position-absolute top-50 start-50 translate-middle px-3 bg-white">
                                <span class="text-muted small">Atau</span>
                            </div>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-muted mb-3">
                                Belum punya akun?
                            </p>
                            <a href="{{ route('register') }}" 
                               class="btn btn-outline w-100 py-3 fw-semibold border-2"
                               style="border-color: #d4a017; color: #d4a017; background-color: transparent;">
                                <i class="fas fa-user-plus me-2"></i> Daftar Akun Baru
                            </a>
                        </div>
                        
                        <!-- Back to Home -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <a href="{{ route('home') }}" class="text-decoration-none" style="color: #8b6b2d;">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Security Notice -->
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        <i class="fas fa-shield-alt me-1"></i> Data Anda aman bersama kami
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Form validation styles
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('is-invalid');
            });
            
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    });
</script>

<style>
    /* Custom styles for better appearance */
    .form-control:focus {
        border-color: #d4a017;
        box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #d4a017;
        border-color: #d4a017;
    }
    
    .form-check-input:focus {
        border-color: #d4a017;
        box-shadow: 0 0 0 0.25rem rgba(212, 160, 23, 0.25);
    }
    
    .btn:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .input-group .btn {
            padding: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .d-flex.flex-sm-row {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        
        .d-flex.flex-sm-row a {
            margin-top: 0.5rem;
        }
    }
</style>
@endsection