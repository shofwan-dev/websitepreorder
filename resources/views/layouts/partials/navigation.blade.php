<!-- resources/views/layouts/partials/navigation.blade.php -->
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
            <div class="hidden md:flex items-center space-x-8">
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
                <a href="{{ route('order.create') }}" 
                   class="bg-warm-gold-500 hover:bg-warm-gold-600 text-white px-6 py-2 rounded-lg font-semibold transition">
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
                <a href="{{ route('order.create') }}" 
                   class="bg-warm-gold-500 hover:bg-warm-gold-600 text-white px-6 py-3 rounded-lg font-semibold text-center mt-4">
                    <i class="fas fa-cart-plus mr-2"></i> Ikut Pre-Order
                </a>
            </div>
        </div>
    </div>
</nav>