@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - ' . ($site_settings['site_name'] ?? 'Pre-Order'))

@section('content')
<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3" style="color: #8b6b2d;">
                <i class="fas fa-file-contract me-3"></i>Syarat & Ketentuan
            </h1>
            <p class="lead text-muted">
                Ketentuan penggunaan layanan {{ $site_settings['site_name'] ?? 'Pre-Order' }}
            </p>
        </div>

        <!-- Content Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="content-area">
                            @if($content)
                                {!! $content !!}
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Konten syarat dan ketentuan belum diatur. Silakan hubungi administrator.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-area {
        line-height: 1.8;
        font-size: 1rem;
    }
    
    .content-area h4, .content-area h5 {
        margin-top: 2rem;
    }
    
    .content-area ul, .content-area ol {
        padding-left: 1.5rem;
    }
    
    .content-area li {
        margin-bottom: 0.5rem;
    }
    
    .content-area a {
        color: #0066cc;
        text-decoration: underline;
    }
    
    .content-area a:hover {
        color: #004499;
    }
</style>
@endsection
