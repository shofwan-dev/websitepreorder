@extends('layouts.app')

@section('title', 'Production Manager - PO Kaligrafi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold" style="color: #8b6b2d;">
                <i class="fas fa-industry me-2"></i> Production Manager
            </h1>
            <p class="text-muted mb-0">Dashboard untuk mengelola seluruh proses produksi PO Kaligrafi</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Tambah Batch
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-download me-2"></i> Export Laporan
            </button>
        </div>
    </div>
    
    <!-- Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 bg-primary bg-opacity-10">
                            <i class="fas fa-box-open text-primary fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Total Batch</p>
                            <h3 class="fw-bold mb-0">12</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 bg-warning bg-opacity-10">
                            <i class="fas fa-spinner text-warning fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Dalam Produksi</p>
                            <h3 class="fw-bold mb-0">5</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 bg-success bg-opacity-10">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Selesai</p>
                            <h3 class="fw-bold mb-0">7</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3 bg-danger bg-opacity-10">
                            <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Terlambat</p>
                            <h3 class="fw-bold mb-0">2</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row g-4">
        <!-- Production Timeline -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-stream me-2"></i> Timeline Produksi Aktif
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Table of active productions -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Batch</th>
                                    <th>Produk</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Deadline</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>#{{ $i + 10 }}</td>
                                    <td>Kaligrafi Lampu Allah</td>
                                    <td>
                                        <span class="badge bg-warning">Produksi</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 100px;">
                                            <div class="progress-bar" style="width: {{ rand(30, 90) }}%"></div>
                                        </div>
                                    </td>
                                    <td>{{ now()->addDays(rand(1, 7))->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-bolt me-2"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary text-start">
                            <i class="fas fa-bullhorn me-2"></i> Kirim Update ke Customer
                        </button>
                        <button class="btn btn-outline-success text-start">
                            <i class="fas fa-sync-alt me-2"></i> Update Status Produksi
                        </button>
                        <button class="btn btn-outline-warning text-start">
                            <i class="fas fa-truck me-2"></i> Kelola Pengiriman
                        </button>
                        <button class="btn btn-outline-info text-start">
                            <i class="fas fa-chart-line me-2"></i> Generate Laporan
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Updates -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-history me-2"></i> Update Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex">
                                <div class="rounded-circle p-2 me-3 bg-primary bg-opacity-10">
                                    <i class="fas fa-user-cog text-primary"></i>
                                </div>
                                <div>
                                    <p class="small mb-1">Admin mengupdate status batch #{{ $i + 10 }}</p>
                                    <p class="text-muted smaller">30 menit yang lalu</p>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .progress-bar {
        background: linear-gradient(90deg, #d4a017 0%, #f4c542 100%);
    }
</style>
@endsection