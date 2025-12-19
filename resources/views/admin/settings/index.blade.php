@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pengaturan</h1>
    <p class="text-muted">Konfigurasi sistem PO Kaligrafi</p>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.settings.website') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-globe fa-2x text-primary"></i>
                    </div>
                    <h5 class="card-title">Website</h5>
                    <p class="card-text text-muted">Pengaturan umum website, nama, logo, dll.</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.settings.payment') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-credit-card fa-2x text-success"></i>
                    </div>
                    <h5 class="card-title">Pembayaran</h5>
                    <p class="card-text text-muted">Konfigurasi metode pembayaran dan rekening.</p>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admin.settings.whatsapp') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fab fa-whatsapp fa-2x text-success"></i>
                    </div>
                    <h5 class="card-title">WhatsApp</h5>
                    <p class="card-text text-muted">Konfigurasi API WhatsApp untuk notifikasi.</p>
                </div>
            </div>
        </a>
    </div>
</div>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush
@endsection
