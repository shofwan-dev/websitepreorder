@extends('layouts.app')

@section('title', 'Dashboard - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi'))

@section('content')
<div class="min-vh-100 py-4" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="mb-4 mb-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h1 class="h2 fw-bold mb-2" style="color: #8b6b2d; font-family: 'Georgia', serif;">
                        <i class="fas fa-hand-peace me-2"></i> Assalamu'alaikum, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-muted mb-0">Selamat datang di dashboard PO Kaligrafi Anda</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('order.create') }}" class="btn btn-lg fw-semibold text-white border-0"
                       style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                        <i class="fas fa-plus-circle me-2"></i> Ikut PO Baru
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-3 g-md-4 mb-4 mb-md-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #d4a017;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(212, 160, 23, 0.1);">
                                <i class="fas fa-shopping-cart fs-4" style="color: #d4a017;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">PO Aktif</p>
                                <h3 class="fw-bold mb-0" style="color: #8b6b2d;">2</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0d6efd;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                <i class="fas fa-history fs-4" style="color: #0d6efd;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total PO</p>
                                <h3 class="fw-bold mb-0" style="color: #0d6efd;">5</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                <i class="fas fa-star fs-4" style="color: #198754;"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Poin Loyalty</p>
                                <h3 class="fw-bold mb-0" style="color: #198754;">125</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row g-4">
            <!-- Active PO Section -->
            <div class="col-lg-8">
                <!-- PO Aktif Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                                <i class="fas fa-bolt me-2"></i> PO Aktif Anda
                            </h5>
                            <span class="badge rounded-pill" style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                2 Active
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- PO 1 -->
                        <div class="card border mb-3 border-hover" style="border-color: #f0e6d2;">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                    <div class="mb-2 mb-md-0">
                                        <h6 class="fw-bold mb-1">Kaligrafi Lampu Allah</h6>
                                        <p class="text-muted small mb-0">Batch #12 • PO Berjalan</p>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="fas fa-hammer me-1"></i> Produksi
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Progress Produksi</span>
                                        <span>60%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: 60%; background: linear-gradient(90deg, #d4a017 0%, #f4c542 100%);">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                    <div class="mb-2 mb-md-0">
                                        <span class="text-muted small">
                                            <i class="far fa-clock me-1"></i> Estimasi: 7-10 hari
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline" style="border-color: #d4a017; color: #d4a017;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        <a href="#" class="btn btn-sm text-white" 
                                           style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                            <i class="fas fa-share-alt me-1"></i> Bagikan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PO 2 -->
                        <div class="card border mb-0 border-hover" style="border-color: #f0e6d2;">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                    <div class="mb-2 mb-md-0">
                                        <h6 class="fw-bold mb-1">Kaligrafi Muhammad</h6>
                                        <p class="text-muted small mb-0">Batch #8 • Menunggu Kuota</p>
                                    </div>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                        <i class="fas fa-users me-1"></i> Menunggu
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Progress Kuota</span>
                                        <span>7/10 pemesan</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: 70%; background: linear-gradient(90deg, #d4a017 0%, #f4c542 100%);">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                    <div class="mb-2 mb-md-0">
                                        <span class="text-muted small">
                                            <i class="fas fa-users me-1"></i> 3 slot tersisa
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline" style="border-color: #d4a017; color: #d4a017;">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        <a href="#" class="btn btn-sm text-white" 
                                           style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                                            <i class="fas fa-share-alt me-1"></i> Bagikan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold" style="color: #8b6b2d;">
                            <i class="fas fa-history me-2"></i> Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="d-flex align-items-start py-3 border-bottom border-light last:border-0">
                            <div class="rounded-circle p-2 me-3" style="background-color: rgba(212, 160, 23, 0.1);">
                                <i class="fas fa-bell" style="color: #d4a017;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="fw-medium mb-1">Update produksi batch #12</p>
                                <p class="text-muted small mb-1">Kaligrafi sedang dalam tahap quality control</p>
                                <p class="text-muted smaller">2 jam yang lalu</p>
                            </div>
                        </div>
                        @endfor
                        
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline" style="border-color: #d4a017; color: #d4a017;">
                                <i class="fas fa-list me-1"></i> Lihat Semua Aktivitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white fs-3 fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                        <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>
                        
                        <div class="list-group list-group-flush">
                            <a href="{{ route('profile.edit') }}" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(212, 160, 23, 0.1);">
                                    <i class="fas fa-user-edit" style="color: #d4a017;"></i>
                                </div>
                                <span>Edit Profil</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                            <a href="#" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="fas fa-shopping-bag" style="color: #0d6efd;"></i>
                                </div>
                                <span>Riwayat PO</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                            <a href="#" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(220, 53, 69, 0.1);">
                                    <i class="fas fa-heart" style="color: #dc3545;"></i>
                                </div>
                                <span>Wishlist</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                            <a href="#" 
                               class="list-group-item list-group-item-action d-flex align-items-center border-0 py-3 px-0">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(108, 117, 125, 0.1);">
                                    <i class="fas fa-cog" style="color: #6c757d;"></i>
                                </div>
                                <span>Pengaturan</span>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="card border-0 text-white overflow-hidden" 
                     style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4">Statistik Anda</h5>
                        
                        <div class="mb-4">
                            <p class="small opacity-90 mb-1">Total Pengeluaran</p>
                            <p class="h4 fw-bold mb-0">Rp 1.750.000</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="small opacity-90 mb-1">Member Sejak</p>
                            <p class="h5 fw-semibold mb-0">Januari 2024</p>
                        </div>
                        
                        <div>
                            <p class="small opacity-90 mb-2">Status</p>
                            <span class="badge bg-white text-dark px-3 py-2">
                                <i class="fas fa-crown me-2" style="color: #d4a017;"></i> Member Gold
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom hover effects */
    .card.border-hover:hover {
        border-color: #d4a017 !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .list-group-item-action:hover {
        background-color: rgba(212, 160, 23, 0.05) !important;
        color: #8b6b2d !important;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    /* Progress bar styling */
    .progress {
        background-color: rgba(212, 160, 23, 0.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem !important;
            font-size: 0.9rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    }
    
    /* Smaller text utility */
    .smaller {
        font-size: 0.75rem;
    }
</style>

<script>
    // Add hover effects dynamically
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover class to all border-hover cards
        const cards = document.querySelectorAll('.border-hover');
        cards.forEach(card => {
            card.style.transition = 'all 0.3s ease';
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 15px rgba(212, 160, 23, 0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Add animation to stats cards
        const statCards = document.querySelectorAll('.card.shadow-sm');
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection