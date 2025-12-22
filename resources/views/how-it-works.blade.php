@extends('layouts.app')

@section('title', 'Cara Kerja - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-vh-100" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <!-- Hero Section -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3" style="color: #8b6b2d;">
                <i class="fas fa-info-circle me-3"></i>Cara Kerja Pre-Order
            </h1>
            <p class="lead text-muted mb-0">
                Proses mudah dan transparan untuk mendapatkan kaligrafi berkualitas
            </p>
        </div>

        <!-- Steps -->
        <div class="row g-4 mb-5">
            @foreach($steps as $step)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden" 
                     style="transition: all 0.3s ease;">
                    <!-- Step Number Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                             style="width: 48px; height: 48px; background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                            {{ $step['number'] }}
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; background-color: rgba(212, 160, 23, 0.1);">
                                <i class="{{ $step['icon'] }} fa-2x" style="color: #d4a017;"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold mb-3" style="color: #8b6b2d;">
                            {{ $step['title'] }}
                        </h5>
                        <p class="card-text text-muted">
                            {{ $step['description'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Timeline Visualization -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4 p-md-5">
                <h3 class="text-center fw-bold mb-5" style="color: #8b6b2d;">
                    <i class="fas fa-stream me-2"></i>Timeline Proses
                </h3>
                
                <div class="position-relative">
                    <!-- Timeline Line -->
                    <div class="d-none d-md-block position-absolute top-50 start-0 w-100 border-top border-3"
                         style="border-color: rgba(212, 160, 23, 0.2) !important; z-index: 0;"></div>
                    
                    <div class="row g-4 position-relative" style="z-index: 1;">
                        @foreach($steps as $index => $step)
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto shadow"
                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                    <i class="{{ $step['icon'] }} text-white"></i>
                                </div>
                            </div>
                            <h6 class="fw-bold small mb-1" style="color: #8b6b2d;">
                                {{ $step['title'] }}
                            </h6>
                            <p class="small text-muted mb-0" style="font-size: 0.75rem;">
                                Step {{ $step['number'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <h3 class="text-center fw-bold mb-4" style="color: #8b6b2d;">
                    <i class="fas fa-star me-2"></i>Keuntungan Pre-Order
                </h3>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Harga Spesial</h5>
                    <p class="text-muted small mb-0">Dapatkan harga lebih murah dibanding harga normal</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="fas fa-award fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Kualitas Terjamin</h5>
                    <p class="text-muted small mb-0">Diproduksi dengan standar quality control ketat</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="fas fa-bell fa-2x text-danger"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Update Real-time</h5>
                    <p class="text-muted small mb-0">Notifikasi setiap tahap produksi via WhatsApp</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 70px; height: 70px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="fas fa-shield-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Garansi 1 Tahun</h5>
                    <p class="text-muted small mb-0">Perlindungan untuk produk berkualitas</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="card border-0 shadow-sm text-center p-5"
             style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
            <div class="text-white">
                <h3 class="fw-bold mb-3">Siap Ikut Pre-Order?</h3>
                <p class="mb-4">Dapatkan kaligrafi lampu berkualitas dengan proses yang mudah dan transparan</p>
                <a href="{{ route('home') }}" 
                   class="btn btn-light btn-lg px-5 fw-semibold"
                   style="color: #8b6b2d;">
                    <i class="fas fa-shopping-cart me-2"></i>Lihat Produk
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212, 160, 23, 0.2) !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem;
        }
    }
</style>
@endpush
@endsection
