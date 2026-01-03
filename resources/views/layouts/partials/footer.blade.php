<!-- resources/views/layouts/partials/footer.blade.php -->
<footer class="bg-wood-brown-900 text-white pt-12 pb-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Company Info -->
            <div>
                <div class="flex items-center mb-4">
                    @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                        <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" alt="Logo" class="h-10 w-auto mr-3 rounded">
                    @else
                        <div class="w-10 h-10 bg-warm-gold-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-mosque text-white"></i>
                        </div>
                    @endif
                    <span class="text-xl font-serif-islamic">{{ $site_settings['site_name'] ?? 'PO Kaligrafi' }}</span>
                </div>
                <p class="text-gray-300 mb-4">
                    {{ $site_settings['tagline'] ?? 'Menghadirkan cahaya ketenangan melalui karya kaligrafi islami berkualitas.' }}
                </p>
                <div class="flex space-x-4">
                    <a href="https://wa.me/{{ str_replace(['-', ' ', '+'], '', $site_settings['whatsapp'] ?? '6281234567890') }}" target="_blank" class="text-gray-300 hover:text-warm-gold-300">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                    @if(!empty($site_settings['instagram']))
                    <a href="https://instagram.com/{{ str_replace('@', '', $site_settings['instagram']) }}" target="_blank" class="text-gray-300 hover:text-warm-gold-300">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    @endif
                    @if(!empty($site_settings['facebook']))
                    <a href="{{ str_starts_with($site_settings['facebook'], 'http') ? $site_settings['facebook'] : 'https://facebook.com/' . $site_settings['facebook'] }}" target="_blank" class="text-gray-300 hover:text-warm-gold-300">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-warm-gold-300">Beranda</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-warm-gold-300">Tentang Kami</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="text-gray-300 hover:text-warm-gold-300">Cara Kerja PO</a></li>
                    <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-warm-gold-300">FAQ</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-warm-gold-300">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Products -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Produk</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-300 hover:text-warm-gold-300">Kaligrafi Allah</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-warm-gold-300">Kaligrafi Muhammad</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-warm-gold-300">Kaligrafi Ayat Kursi</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-warm-gold-300">Kaligrafi Custom</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt text-warm-gold-300 mt-1 mr-3"></i>
                        <span class="text-gray-300">{{ $site_settings['address'] ?? 'Jl. Pengrajin No. 123, Yogyakarta' }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone text-warm-gold-300 mt-1 mr-3"></i>
                        <span class="text-gray-300">{{ $site_settings['phone'] ?? '+62 812-3456-7890' }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope text-warm-gold-300 mt-1 mr-3"></i>
                        <span class="text-gray-300">{{ $site_settings['email'] ?? 'admin@pokaligrafi.com' }}</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-clock text-warm-gold-300 mt-1 mr-3"></i>
                        <span class="text-gray-300">Senin - Jumat: 09:00 - 17:00 WIB</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 pt-8 text-center">
            <p class="text-gray-400">
                &copy; {{ date('Y') }} {{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu Islami' }}. Hak cipta dilindungi.
            </p>
            <p class="text-gray-500 text-sm mt-2">
                Dibuat dengan <i class="fas fa-heart text-red-400"></i> untuk ummat.
            </p>
        </div>
    </div>
</footer>