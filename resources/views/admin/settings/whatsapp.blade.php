@extends('layouts.admin')

@section('title', 'Pengaturan WhatsApp')
@section('page-title', 'Pengaturan WhatsApp')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item active">WhatsApp</li>
        </ol>
    </nav>
    <h1 class="page-title">Pengaturan WhatsApp API</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fab fa-whatsapp me-2"></i>Konfigurasi API
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.whatsapp.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('api_key') is-invalid @enderror" 
                               id="api_key"
                               name="api_key"
                               value="{{ old('api_key', $settings['whatsapp_api_key'] ?? '') }}"
                               placeholder="Masukkan API Key">
                        <div class="form-text">API Key dari provider WhatsApp Gateway</div>
                        @error('api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sender" class="form-label">Nomor Pengirim <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('sender') is-invalid @enderror" 
                               id="sender"
                               name="sender"
                               value="{{ old('sender', $settings['whatsapp_sender'] ?? '') }}"
                               placeholder="Contoh: 628123456789">
                        <div class="form-text">Nomor WhatsApp yang terdaftar di gateway (format: 628xxxxxxxxxx)</div>
                        @error('sender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="endpoint" class="form-label">Endpoint <span class="text-danger">*</span></label>
                        <input type="url" 
                               class="form-control @error('endpoint') is-invalid @enderror" 
                               id="endpoint"
                               name="endpoint"
                               value="{{ old('endpoint', $settings['whatsapp_endpoint'] ?? 'https://wa.mutekar.com/send-message') }}"
                               placeholder="https://wa.mutekar.com/send-message">
                        <div class="form-text">URL endpoint API WhatsApp Gateway</div>
                        @error('endpoint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-vial me-2"></i>Test Pengiriman
            </div>
            <div class="card-body">
                <p class="text-muted">Gunakan halaman test untuk menguji pengiriman pesan WhatsApp.</p>
                <a href="{{ route('admin.whatsapp-test') }}" class="btn btn-success">
                    <i class="fab fa-whatsapp me-2"></i>Buka Halaman Test
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card bg-success text-white">
            <div class="card-header">
                <i class="fas fa-check-circle me-2"></i>Status Koneksi
            </div>
            <div class="card-body">
                @if(!empty($settings['whatsapp_api_key']))
                    <div class="d-flex align-items-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status"></div>
                        <span>API Key Terkonfigurasi</span>
                    </div>
                @else
                    <div class="text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        API Key belum dikonfigurasi
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-book me-2"></i>Panduan
            </div>
            <div class="card-body">
                <ol class="small text-muted ps-3">
                    <li class="mb-2">Daftar akun di <a href="https://fonnte.com" target="_blank">Fonnte.com</a> atau provider lainnya</li>
                    <li class="mb-2">Dapatkan API Key dari dashboard provider</li>
                    <li class="mb-2">Hubungkan nomor WhatsApp Anda</li>
                    <li class="mb-2">Masukkan API Key, Nomor Pengirim, dan Endpoint di form ini</li>
                    <li>Test pengiriman pesan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
