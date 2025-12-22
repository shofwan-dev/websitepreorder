@extends('layouts.app')

@section('title', 'Tentang Kami - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Tentang {{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}</h1>
    
    <div class="row">
        <div class="col-md-8">
            <p class="lead">Kami adalah pengrajin kaligrafi lampu yang berdedikasi menciptakan karya seni islami dengan kualitas terbaik.</p>
            
            <h4 class="mt-4">Visi Kami</h4>
            <p>Menjadi pelopor dalam menyebarkan keindahan kaligrafi islami melalui karya seni yang fungsional dan bermakna.</p>
            
            <h4 class="mt-4">Misi Kami</h4>
            <ul>
                <li>Menciptakan kaligrafi lampu dengan desain elegan dan bermakna</li>
                <li>Menggunakan bahan-bahan berkualitas tinggi untuk hasil yang optimal</li>
                <li>Memberikan pengalaman pre-order yang transparan dan terpercaya</li>
                <li>Menyebarkan cahaya keberkahan melalui karya seni islami</li>
            </ul>
            
            <h4 class="mt-4">Kenapa Memilih Kami?</h4>
            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fas fa-award text-warning me-2"></i>Kualitas Terjamin</h5>
                            <p>Setiap produk melalui quality control ketat sebelum dikirim.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><i class="fas fa-shipping-fast text-success me-2"></i>Pengiriman Aman</h5>
                            <p>Packing khusus untuk melindungi kaligrafi selama pengiriman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Kontak</h5>
                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>Jl. Pengrajin No. 123, Yogyakarta</p>
                    <p><i class="fas fa-phone me-2 text-success"></i>0812-3456-7890</p>
                    <p><i class="fas fa-envelope me-2 text-info"></i>admin@pokaligrafi.com</p>
                    <p><i class="fas fa-clock me-2 text-warning"></i>Senin - Jumat: 09:00 - 17:00 WIB</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection