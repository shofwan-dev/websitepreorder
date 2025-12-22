@extends('layouts.app')

@section('title', 'Kebijakan Refund - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3" style="color: #8b6b2d;">
                <i class="fas fa-undo-alt me-3"></i>Kebijakan Refund
            </h1>
            <p class="lead text-muted">
                Ketentuan pengembalian dana dan pembatalan pesanan
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
                                <!-- Fallback jika tidak ada konten (seharusnya tidak terjadi karena ada default) -->
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Konten kebijakan refund belum diatur. Silakan hubungi administrator.
                                </div>
                            @endif
                            
                            <!-- Contact Info (Always shown) -->
                            <h4 class="fw-bold mb-4 mt-5" style="color: #8b6b2d;">Hubungi Kami</h4>
                            <p>Jika ada pertanyaan terkait kebijakan refund, silakan hubungi:</p>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <p class="mb-2"><i class="fab fa-whatsapp me-2 text-success"></i> <strong>WhatsApp:</strong> {{ $site_settings['whatsapp'] ?? '6281234567890' }}</p>
                                    <p class="mb-0"><i class="fas fa-envelope me-2 text-primary"></i> <strong>Email:</strong> {{ $site_settings['email'] ?? 'admin@example.com' }}</p>
                                </div>
                            </div>
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
</style>
@endsection
