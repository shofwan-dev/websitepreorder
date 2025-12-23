<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    
    {{-- Primary Meta Tags --}}
    <title>@yield('title', ($seo_settings['seo_title'] ?? ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu') . ' - ' . ($site_settings['tagline'] ?? 'Pre-Order Kaligrafi Lampu Islami')))</title>
    <meta name="title" content="@yield('meta_title', ($seo_settings['seo_title'] ?? ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu') . ' - ' . ($site_settings['tagline'] ?? 'Pre-Order Kaligrafi Lampu Islami')))">
    <meta name="description" content="@yield('meta_description', ($seo_settings['seo_description'] ?? $site_settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim. Pre-order kaligrafi lampu dengan harga terjangkau dan kualitas terbaik.'))">
    <meta name="keywords" content="@yield('meta_keywords', ($seo_settings['seo_keywords'] ?? 'kaligrafi lampu, pre order kaligrafi, lampu islami, dekorasi islami, kaligrafi murah, lampu kaligrafi, dekorasi muslim, kaligrafi arab, islamic decor'))">
    <meta name="author" content="{{ $seo_settings['seo_author'] ?? $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}">
    <meta name="robots" content="{{ !empty($seo_settings['seo_noindex']) && $seo_settings['seo_noindex'] == '1' ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' }}">
    <meta name="googlebot" content="{{ !empty($seo_settings['seo_noindex']) && $seo_settings['seo_noindex'] == '1' ? 'noindex, nofollow' : 'index, follow' }}">
    
    {{-- Google Search Console Verification --}}
    @if(!empty($seo_settings['google_search_console'] ?? ''))
    <meta name="google-site-verification" content="{{ $seo_settings['google_search_console'] }}">
    @endif
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    {{-- Favicon --}}
    @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
    <link rel="icon" href="{{ asset('storage/' . $site_settings['site_logo']) }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/' . $site_settings['site_logo']) }}">
    @endif
    
    {{-- Open Graph Meta Tags (Facebook) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}">
    <meta property="og:title" content="@yield('og_title', ($seo_settings['og_title'] ?? ($seo_settings['seo_title'] ?? ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu') . ' - ' . ($site_settings['tagline'] ?? 'Pre-Order Kaligrafi Lampu Islami'))))">
    <meta property="og:description" content="@yield('og_description', ($seo_settings['og_description'] ?? ($seo_settings['seo_description'] ?? $site_settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim. Pre-order kaligrafi lampu dengan harga terjangkau dan kualitas terbaik.')))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('storage/' . ($seo_settings['og_image'] ?? $site_settings['site_logo'] ?? 'logo.png')))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="id_ID">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', ($seo_settings['twitter_title'] ?? ($seo_settings['seo_title'] ?? ($site_settings['site_name'] ?? 'PO Kaligrafi Lampu') . ' - ' . ($site_settings['tagline'] ?? 'Pre-Order Kaligrafi Lampu Islami'))))">
    <meta name="twitter:description" content="@yield('twitter_description', ($seo_settings['twitter_description'] ?? ($seo_settings['seo_description'] ?? $site_settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim. Pre-order kaligrafi lampu dengan harga terjangkau dan kualitas terbaik.')))">
    <meta name="twitter:image" content="@yield('twitter_image', asset('storage/' . ($seo_settings['og_image'] ?? $site_settings['site_logo'] ?? 'logo.png')))">
    @if(!empty($site_settings['twitter'] ?? ''))
    <meta name="twitter:site" content="@{{ ltrim($site_settings['twitter'], '@') }}">
    <meta name="twitter:creator" content="@{{ ltrim($site_settings['twitter'], '@') }}">
    @endif
    
    {{-- Additional SEO Tags --}}
    <meta name="theme-color" content="#d4a017">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    {{-- Geo Tags --}}
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="{{ $site_settings['address'] ?? 'Indonesia' }}">
    
    {{-- Schema.org JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('storage/' . ($site_settings['site_logo'] ?? 'logo.png')) }}",
        "description": "{{ $site_settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim' }}",
        "telephone": "{{ $site_settings['phone'] ?? '' }}",
        "email": "{{ $site_settings['email'] ?? '' }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $site_settings['address'] ?? 'Indonesia' }}",
            "addressCountry": "ID"
        },
        "sameAs": [
            @if(!empty($site_settings['instagram'] ?? ''))
            "https://instagram.com/{{ ltrim($site_settings['instagram'], '@') }}",
            @endif
            @if(!empty($site_settings['facebook'] ?? ''))
            "{{ str_contains($site_settings['facebook'], 'http') ? $site_settings['facebook'] : 'https://facebook.com/' . ltrim($site_settings['facebook'], '@') }}",
            @endif
            @if(!empty($site_settings['twitter'] ?? ''))
            "https://twitter.com/{{ ltrim($site_settings['twitter'], '@') }}"
            @endif
        ]
    }
    </script>

    {{-- Local Business Schema --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}",
        "image": "{{ asset('storage/' . ($site_settings['site_logo'] ?? 'logo.png')) }}",
        "url": "{{ url('/') }}",
        "telephone": "{{ $site_settings['phone'] ?? '' }}",
        "priceRange": "$$",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $site_settings['address'] ?? 'Indonesia' }}",
            "addressCountry": "ID"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "09:00",
            "closes": "17:00"
        }
    }
    </script>
    
    {{-- Page-specific Schema --}}
    @stack('schema')
    
    {{-- Google Analytics --}}
    @if(!empty($seo_settings['google_analytics'] ?? ''))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seo_settings['google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $seo_settings['google_analytics'] }}');
    </script>
    @endif
    
    {{-- CSS Libraries --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gold: #d4a017;
            --secondary-gold: #f4c542;
            --dark-brown: #2c3e50;
            --light-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
            background: rgba(255,255,255,0.95);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--dark-brown) !important;
            font-size: 1.25rem;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(360deg);
        }

        .nav-link {
            position: relative;
            color: #555 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-gold), var(--secondary-gold));
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 80%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-gold) !important;
        }

        /* Mobile Menu Toggle */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            transition: transform 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler:active {
            transform: scale(0.9);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23d4a017' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0.5rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            color: white !important;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
        }

        /* Buttons */
        .btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(212, 160, 23, 0.5);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-gold);
            color: var(--primary-gold);
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            border-color: transparent;
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(40, 167, 69, 0.5);
        }

        /* Badge */
        .badge {
            padding: 0.35em 0.65em;
            border-radius: 50px;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-gold), var(--secondary-gold), var(--primary-gold));
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 200% 50%;
            }
        }

        .footer h5 {
            color: var(--secondary-gold);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), transparent);
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer a:hover {
            color: var(--secondary-gold);
            transform: translateX(5px);
        }

        .footer .fab,
        .footer .fas {
            transition: all 0.3s ease;
        }

        .footer .fab:hover,
        .footer .fas:hover {
            transform: scale(1.2) rotate(360deg);
            color: var(--secondary-gold);
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 12px;
                margin-top: 1rem;
                box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                animation: slideDown 0.3s ease;
            }

            .navbar-nav {
                gap: 0.5rem;
            }

            .nav-link {
                padding: 0.75rem 1rem !important;
                border-radius: 8px;
            }

            .nav-link:hover {
                background: rgba(212, 160, 23, 0.1);
            }

            .auth-links {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #eee;
            }

            .auth-links .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }

            .auth-links .btn {
                width: 100%;
                justify-content: center;
            }

            .ms-3 {
                margin-left: 0 !important;
                margin-top: 0.5rem;
            }

            .ms-3 .btn {
                width: 100%;
            }

            /* Footer Mobile */
            .footer {
                padding: 2rem 0 1rem;
                text-align: center;
            }

            .footer .col-md-4 {
                margin-bottom: 2rem;
            }

            .footer h5::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer .fab,
            .footer .fas {
                font-size: 1.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-brand img {
                height: 30px !important;
            }

            .btn {
                padding: 0.4rem 1rem;
                font-size: 0.875rem;
            }

            .footer {
                font-size: 0.9rem;
            }

            .footer p {
                margin-bottom: 0.75rem;
            }
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-gold), var(--secondary-gold));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.4);
            z-index: 1000;
        }

        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(212, 160, 23, 0.6);
        }

        /* Loading Animation */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .page-loader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-gold);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                @if(isset($site_settings['site_logo']) && !empty($site_settings['site_logo']))
                    <img src="{{ asset('storage/' . $site_settings['site_logo']) }}" alt="Logo" height="40">
                @else
                    <i class="fas fa-mosque"></i>
                @endif
                <span>{{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('how-it-works') ? 'active' : '' }}" href="{{ route('how-it-works') }}">
                            <i class="fas fa-cogs me-1"></i>Cara Kerja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">
                            <i class="fas fa-question-circle me-1"></i>FAQ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i>Kontak
                        </a>
                    </li>
                </ul>
                
                <!-- Auth Links -->
                <div class="navbar-nav ms-3 auth-links">
                    @auth
                        <!-- Menu untuk user yang sudah login -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <span>{{ Auth::user()->name ?? 'User' }}</span>
                                @if(Auth::user()->role === 'admin')
                                    <span class="badge bg-danger ms-1">Admin</span>
                                @elseif(Auth::user()->role === 'manager')
                                    <span class="badge bg-warning ms-1">Manager</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                                    <!-- Menu untuk Admin/Manager -->
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Admin Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                        <i class="fas fa-shopping-cart me-2 text-success"></i>Kelola Pesanan
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                        <i class="fas fa-box me-2 text-info"></i>Kelola Produk
                                    </a></li>
                                    @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users me-2 text-warning"></i>Kelola User
                                    </a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <!-- Menu untuk semua user yang login -->
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-home me-2"></i>My Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders.index') }}">
                                    <i class="fas fa-list me-2"></i>Pesanan Saya
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Menu untuk user yang belum login -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i>Daftar
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
                
                <!-- PO Button (Visible untuk semua user) -->
                <div class="ms-3">
                    <a href="{{ route('user.orders.create') }}" class="btn btn-success">
                        <i class="fas fa-shopping-cart me-1"></i> Ikut PO
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>{{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}</h5>
                    <p>{{ $site_settings['tagline'] ?? 'Menghadirkan keindahan kaligrafi islami dalam setiap rumah Muslim.' }}</p>
                    <div class="mt-3">
                        @auth
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm me-2 mb-2">
                                    <i class="fas fa-cogs"></i> Admin Panel
                                </a>
                            @endif
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light btn-sm mb-2">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2 mb-2">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Daftar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <p><i class="fas fa-phone me-2"></i> {{ $site_settings['phone'] ?? '0812-3456-7890' }}</p>
                    <p><i class="fas fa-envelope me-2"></i> {{ $site_settings['email'] ?? 'admin@pokaligrafi.com' }}</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> {{ $site_settings['address'] ?? 'Jl. Pengrajin No. 123, Yogyakarta' }}</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Follow Kami</h5>
                    <div class="mb-3">
                        @if(!empty($site_settings['instagram'] ?? ''))
                        <a href="https://instagram.com/{{ ltrim($site_settings['instagram'], '@') }}" target="_blank" class="text-white me-3" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a>
                        @endif
                        @if(!empty($site_settings['whatsapp'] ?? ''))
                        <a href="https://wa.me/{{ str_replace(['-', ' ', '+'], '', $site_settings['whatsapp']) }}" target="_blank" class="text-white me-3" title="WhatsApp"><i class="fab fa-whatsapp fa-2x"></i></a>
                        @endif
                        @if(!empty($site_settings['facebook'] ?? ''))
                        <a href="{{ str_contains($site_settings['facebook'], 'http') ? $site_settings['facebook'] : 'https://facebook.com/' . ltrim($site_settings['facebook'], '@') }}" target="_blank" class="text-white me-3" title="Facebook"><i class="fab fa-facebook fa-2x"></i></a>
                        @endif
                        @if(!empty($site_settings['twitter'] ?? ''))
                        <a href="https://twitter.com/{{ ltrim($site_settings['twitter'], '@') }}" target="_blank" class="text-white me-3" title="Twitter/X"><i class="fab fa-x-twitter fa-2x"></i></a>
                        @endif
                    </div>
                    <h5>Jam Operasional</h5>
                    <p><i class="fas fa-clock me-2"></i> {{ $site_settings['business_hours'] ?? 'Senin - Jumat: 09:00 - 17:00 WIB' }}</p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ $site_settings['site_name'] ?? 'PO Kaligrafi Lampu' }}. All rights reserved.</p>
                <p class="small">
                    <a href="{{ route('home') }}" class="text-white me-3">Beranda</a> |
                    <a href="{{ route('about') }}" class="text-white me-3">Tentang Kami</a> |
                    <a href="{{ route('how-it-works') }}" class="text-white me-3">Cara Kerja</a> |
                    <a href="{{ route('faq') }}" class="text-white me-3">FAQ</a> |
                    <a href="{{ route('contact') }}" class="text-white">Kontak</a> |
                    <a href="{{ route('refund-policy') }}" class="text-white me-3">Refund Policy</a> |
                    <a href="{{ route('terms-conditions') }}" class="text-white">Syarat & Ketentuan</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Page Loader
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('pageLoader').classList.add('hide');
            }, 500);
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Scroll to Top Button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Active menu highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    link.setAttribute('aria-current', 'page');
                }
            });

            // Close mobile menu on link click
            const navbarCollapse = document.getElementById('navbarNav');
            const navLinks2 = document.querySelectorAll('.nav-link');
            
            navLinks2.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                            toggle: false
                        });
                        bsCollapse.hide();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>