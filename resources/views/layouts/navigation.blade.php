<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                        <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" alt="Logo" class="h-10 w-auto mr-3 rounded">
                    @else
                        <div class="w-10 h-10 bg-warm-gold-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-mosque text-white"></i>
                        </div>
                    @endif
                    <div>
                        <span class="text-xl font-serif-islamic text-warm-gold-700">{{ $site_settings['site_name'] ?? 'PO Kaligrafi' }}</span>
                        <span class="block text-xs text-gray-500">{{ isset($site_settings['tagline']) && !empty($site_settings['tagline']) ? \Illuminate\Support\Str::limit($site_settings['tagline'], 30) : 'Lampu Islami' }}</span>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium {{ request()->is('/') ? 'text-warm-gold-600' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium {{ request()->is('tentang-kami') ? 'text-warm-gold-600' : '' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('how-it-works') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium {{ request()->is('cara-kerja') ? 'text-warm-gold-600' : '' }}">
                    Cara Kerja
                </a>
                <a href="{{ route('faq') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium {{ request()->is('faq') ? 'text-warm-gold-600' : '' }}">
                    FAQ
                </a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium {{ request()->is('kontak') ? 'text-warm-gold-600' : '' }}">
                    Kontak
                </a>
                
                <!-- Auth Menu -->
                @auth
                    <!-- Dropdown User -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-warm-gold-600 focus:outline-none">
                            <div class="w-8 h-8 bg-warm-gold-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-warm-gold-600"></i>
                            </div>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-warm-gold-50 hover:text-warm-gold-700">
                                <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-warm-gold-50 hover:text-warm-gold-700">
                                <i class="fas fa-user-edit mr-3"></i> Profil Saya
                            </a>
                            <div class="border-t my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-3"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login & Register Buttons -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-warm-gold-600 hover:text-warm-gold-700 font-medium px-4 py-2 rounded-lg hover:bg-warm-gold-50 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-warm-gold-500 hover:bg-warm-gold-600 text-white font-medium px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-user-plus mr-2"></i> Daftar
                        </a>
                    </div>
                @endauth
                
                <!-- CTA PO Button -->
                <a href="{{ route('order.create') }}" 
                   class="bg-gradient-to-r from-warm-gold-500 to-warm-gold-600 hover:from-warm-gold-600 hover:to-warm-gold-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-cart-plus mr-2"></i> Ikut PO
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-gray-700">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden py-4 border-t">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium py-2 {{ request()->is('/') ? 'text-warm-gold-600' : '' }}">
                    <i class="fas fa-home mr-3"></i> Beranda
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium py-2 {{ request()->is('tentang-kami') ? 'text-warm-gold-600' : '' }}">
                    <i class="fas fa-info-circle mr-3"></i> Tentang Kami
                </a>
                <a href="{{ route('how-it-works') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium py-2 {{ request()->is('cara-kerja') ? 'text-warm-gold-600' : '' }}">
                    <i class="fas fa-cogs mr-3"></i> Cara Kerja
                </a>
                <a href="{{ route('faq') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium py-2 {{ request()->is('faq') ? 'text-warm-gold-600' : '' }}">
                    <i class="fas fa-question-circle mr-3"></i> FAQ
                </a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-warm-gold-600 font-medium py-2 {{ request()->is('kontak') ? 'text-warm-gold-600' : '' }}">
                    <i class="fas fa-phone-alt mr-3"></i> Kontak
                </a>
                
                <!-- Mobile Auth Menu -->
                @auth
                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Akun Anda</p>
                        <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-warm-gold-600 py-2">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-warm-gold-600 py-2">
                            <i class="fas fa-user-edit mr-3"></i> Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 py-2">
                                <i class="fas fa-sign-out-alt mr-3"></i> Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-4 border-t">
                        <a href="{{ route('login') }}" class="block text-warm-gold-600 hover:text-warm-gold-700 py-2 font-medium">
                            <i class="fas fa-sign-in-alt mr-3"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="block text-white bg-warm-gold-500 hover:bg-warm-gold-600 py-3 px-4 rounded-lg text-center mt-2">
                            <i class="fas fa-user-plus mr-2"></i> Daftar Akun Baru
                        </a>
                    </div>
                @endauth
                
                <a href="{{ route('order.create') }}" 
                   class="bg-gradient-to-r from-warm-gold-500 to-warm-gold-600 hover:from-warm-gold-600 hover:to-warm-gold-700 text-white px-6 py-3 rounded-lg font-semibold text-center mt-4 shadow-lg">
                    <i class="fas fa-cart-plus mr-2"></i> Ikut Pre-Order
                </a>
            </div>
        </div>
    </div>
</nav>