@extends('layouts.app')

@section('title', 'Hubungi Kami - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-vh-100 py-5" style="background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3" style="color: #8b6b2d;">
                <i class="fas fa-phone-alt me-3"></i>Hubungi Kami
            </h1>
            <p class="lead text-muted">
                Kami siap membantu dan menjawab pertanyaan Anda
            </p>
        </div>

        <div class="row g-4">
            <!-- Contact Information -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-4" style="color: #8b6b2d;">
                            <i class="fas fa-info-circle me-2"></i>Informasi Kontak
                        </h4>

                        <!-- WhatsApp -->
                        <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                 style="width: 60px; height: 60px; background-color: rgba(37, 211, 102, 0.1);">
                                <i class="fab fa-whatsapp fa-2x text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">WhatsApp</h5>
                                <p class="text-muted mb-2">{{ $site_settings['phone'] ?? $phone ?? '0812-3456-7890' }}</p>
                                <a href="https://wa.me/{{ str_replace(['-', ' ', '+'], '', $whatsapp ?? '6281234567890') }}" 
                                   target="_blank"
                                   class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp me-2"></i>Chat Sekarang
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                 style="width: 60px; height: 60px; background-color: rgba(13, 110, 253, 0.1);">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Email</h5>
                                <p class="text-muted mb-2">{{ $site_settings['email'] ?? $email ?? 'admin@pokaligrafi.com' }}</p>
                                <a href="mailto:{{ $site_settings['email'] ?? $email ?? 'admin@pokaligrafi.com' }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-envelope me-2"></i>Kirim Email
                                </a>
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); opacity: 0.1;">
                                <i class="fab fa-instagram fa-2x" style="background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Instagram</h5>
                                <p class="text-muted mb-2">{{ $instagram ?? '@pokaligrafi' }}</p>
                                <a href="https://instagram.com/{{ ltrim($instagram ?? '@pokaligrafi', '@') }}" 
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white;">
                                    <i class="fab fa-instagram me-2"></i>Follow Kami
                                </a>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                 style="width: 60px; height: 60px; background-color: rgba(220, 53, 69, 0.1);">
                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Alamat</h5>
                                <p class="text-muted mb-0">{{ $site_settings['address'] ?? $address ?? 'Jl. Pengrajin No. 123, Yogyakarta' }}</p>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="d-flex align-items-start">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                 style="width: 60px; height: 60px; background-color: rgba(255, 193, 7, 0.1);">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Jam Operasional</h5>
                                <p class="text-muted mb-0">{{ $business_hours ?? 'Senin - Jumat: 09:00 - 17:00 WIB' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Contact Card -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4"
                     style="background: linear-gradient(135deg, #d4a017 0%, #f4c542 100%);">
                    <div class="card-body p-4 p-md-5 text-white">
                        <h4 class="fw-bold mb-4">
                            <i class="fas fa-headset me-2"></i>Butuh Bantuan Segera?
                        </h4>
                        <p class="mb-4">
                            Tim customer service kami siap membantu Anda melalui WhatsApp. 
                            Dapatkan respons cepat untuk pertanyaan seputar produk, pemesanan, atau pengiriman.
                        </p>
                        <a href="https://wa.me/{{ str_replace(['-', ' ', '+'], '', $whatsapp ?? '6281234567890') }}" 
                           target="_blank"
                           class="btn btn-light btn-lg w-100 fw-semibold"
                           style="color: #8b6b2d;">
                            <i class="fab fa-whatsapp me-2"></i>Chat via WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Why Contact Us -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #8b6b2d;">
                            <i class="fas fa-comments me-2"></i>Apa yang Bisa Kami Bantu?
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Konsultasi produk dan desain</span>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Informasi harga dan promo</span>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Cara pemesanan dan pembayaran</span>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Tracking pesanan</span>
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Klaim garansi</span>
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check-circle me-2" style="color: #d4a017;"></i>
                                <span class="text-muted">Pertanyaan umum lainnya</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Link -->
        <div class="text-center mt-5">
            <p class="text-muted mb-3">
                Atau cek halaman <a href="{{ route('faq') }}" class="fw-bold" style="color: #d4a017;">FAQ</a> untuk jawaban cepat pertanyaan umum
            </p>
        </div>
    </div>
</div>
@endsection
