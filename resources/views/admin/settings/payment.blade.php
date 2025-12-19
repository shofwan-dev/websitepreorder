@extends('layouts.admin')

@section('title', 'Pengaturan Pembayaran')
@section('page-title', 'Pengaturan Pembayaran')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
    </nav>
    <h1 class="page-title">Pengaturan Pembayaran</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-university me-2"></i>Rekening Bank
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Bank</th>
                                <th>No. Rekening</th>
                                <th>Atas Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>BCA</strong></td>
                                <td>1234567890</td>
                                <td>PO Kaligrafi</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" disabled>Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mandiri</strong></td>
                                <td>0987654321</td>
                                <td>PO Kaligrafi</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" disabled>Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-primary" disabled>
                    <i class="fas fa-plus me-2"></i>Tambah Rekening
                </button>
                <span class="text-muted ms-2">(Fitur segera hadir)</span>
            </div>
        </div>
        
        <!-- iPaymu Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-credit-card me-2"></i>iPaymu Payment Gateway
            </div>
            <div class="card-body">
                @if(session('payment_success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('payment_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('payment_error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('payment_error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <form method="POST" action="{{ route('admin.settings.payment.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="ipaymu_va" class="form-label">
                            iPaymu VA <span class="text-danger">*</span>
                            <small class="text-muted">(Virtual Account Number)</small>
                        </label>
                        <input type="text" 
                               class="form-control @error('ipaymu_va') is-invalid @enderror" 
                               id="ipaymu_va" 
                               name="ipaymu_va" 
                               value="{{ old('ipaymu_va', config('services.ipaymu.va', '')) }}"
                               placeholder="Contoh: 1179000899"
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            VA number dari dashboard iPaymu
                        </div>
                        @error('ipaymu_va')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ipaymu_api_key" class="form-label">
                            iPaymu API Key <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('ipaymu_api_key') is-invalid @enderror" 
                               id="ipaymu_api_key" 
                               name="ipaymu_api_key" 
                               value="{{ old('ipaymu_api_key', config('services.ipaymu.api_key', '')) }}"
                               placeholder="Masukkan API Key dari dashboard iPaymu"
                               required>
                        <div class="form-text">
                            <i class="fas fa-lock me-1"></i>
                            API Key akan disimpan dengan aman
                        </div>
                        @error('ipaymu_api_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ipaymu_environment" class="form-label">
                            Environment
                        </label>
                        <select class="form-select @error('ipaymu_environment') is-invalid @enderror" 
                                id="ipaymu_environment" 
                                name="ipaymu_environment">
                            <option value="sandbox" {{ old('ipaymu_environment', config('services.ipaymu.environment', 'sandbox')) == 'sandbox' ? 'selected' : '' }}>
                                Sandbox (Testing)
                            </option>
                            <option value="production" {{ old('ipaymu_environment', config('services.ipaymu.environment')) == 'production' ? 'selected' : '' }}>
                                Production (Live)
                            </option>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-server me-1"></i>
                            Gunakan Sandbox untuk testing, Production untuk live
                        </div>
                        @error('ipaymu_environment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                        <a href="{{ route('admin.settings.payment.test') }}" 
                           class="btn btn-outline-secondary"
                           onclick="return confirm('Test koneksi ke iPaymu?')">
                            <i class="fas fa-plug me-2"></i>Test Koneksi
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-cog me-2"></i>Pengaturan Lainnya
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Minimal DP (%)</label>
                        <input type="number" class="form-control" value="50" disabled>
                        <div class="form-text">Persentase minimal pembayaran DP</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Batas Waktu Pembayaran (Hari)</label>
                        <input type="number" class="form-control" value="3" disabled>
                        <div class="form-text">Batas waktu pembayaran setelah order dikonfirmasi</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-info-circle me-2"></i>Informasi
            </div>
            <div class="card-body">
                <p class="text-muted">Rekening bank akan ditampilkan kepada customer saat melakukan pembayaran.</p>
                <p class="text-muted small">Pastikan nomor rekening dan nama penerima sudah benar untuk menghindari kesalahan transfer.</p>
            </div>
        </div>
    </div>
</div>
@endsection
