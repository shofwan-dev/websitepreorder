@extends('layouts.app')

@section('title', 'Daftar - ' . ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu'))

@section('content')
<div class="min-h-screen bg-gradient-to-b from-warm-gold-50 to-white">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto">
            <!-- Logo Header -->
            <div class="text-center mb-8">
                @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                    <div class="w-20 h-20 mx-auto flex items-center justify-center mb-4">
                        <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" alt="Logo" class="w-20 h-20 rounded-full object-cover shadow-lg">
                    </div>
                @else
                    <div class="w-20 h-20 mx-auto bg-warm-gold-500 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-mosque text-white text-3xl"></i>
                    </div>
                @endif
                <h1 class="text-3xl font-serif-islamic text-warm-gold-700 mb-2">
                    Bergabung Bersama Kami
                </h1>
                <p class="text-gray-600">
                    Daftar untuk pengalaman PO {{ $site_settings['site_name'] ?? 'Kaligrafi' }} yang lebih personal
                </p>
            </div>
            
            <!-- Register Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-warm-gold-500 mr-2"></i> Nama Lengkap
                        </label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name" 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-gold-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                            placeholder="Nama Anda"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-warm-gold-500 mr-2"></i> Alamat Email
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-gold-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                            placeholder="email@contoh.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-warm-gold-500 mr-2"></i> Kata Sandi
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-gold-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-warm-gold-500 mr-2"></i> Konfirmasi Kata Sandi
                        </label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warm-gold-500 focus:border-transparent transition"
                            placeholder="Ketik ulang kata sandi"
                        >
                    </div>
                    
                    <!-- Terms Checkbox -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <input 
                                id="terms" 
                                name="terms" 
                                type="checkbox"
                                required
                                class="h-4 w-4 text-warm-gold-600 focus:ring-warm-gold-500 border-gray-300 rounded mt-1"
                            >
                            <label for="terms" class="ml-3 text-sm text-gray-700">
                                Saya menyetujui 
                                <a href="#" class="text-warm-gold-600 hover:text-warm-gold-700">Syarat & Ketentuan</a> 
                                dan 
                                <a href="#" class="text-warm-gold-600 hover:text-warm-gold-700">Kebijakan Privasi</a>
                                PO Kaligrafi
                            </label>
                        </div>
                        @error('terms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-warm-gold-500 to-warm-gold-600 hover:from-warm-gold-600 hover:to-warm-gold-700 text-white py-3 px-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Sudah punya akun?</span>
                        </div>
                    </div>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" 
                       class="inline-block w-full border-2 border-warm-gold-500 text-warm-gold-600 hover:bg-warm-gold-50 py-3 px-4 rounded-lg font-semibold transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Akun
                    </a>
                </div>
                
                <!-- Benefits -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-warm-gold-700 mb-3">
                        Keuntungan Bergabung:
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Pantau status PO secara real-time</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Riwayat semua transaksi Anda</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Notifikasi perkembangan produksi</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Akses ke PO batch eksklusif</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection