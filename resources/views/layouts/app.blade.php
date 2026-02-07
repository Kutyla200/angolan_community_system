<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ in_array(app()->getLocale(), ['ar', 'fa', 'he']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --primary-color: #008751; /* Angolan flag green */
            --primary-600: #006b42;
            --primary-800: #004d2f;
            --secondary-color: #CC092F; /* Angolan flag red */
            --secondary-600: #a00724;
            --accent-color: #FFD100; /* Angolan flag yellow */
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }
        
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), #006b42);
        }
        
        .border-primary {
            border-color: var(--primary-color);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .bg-accent {
            background-color: var(--accent-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-600));
            border: none;
            box-shadow: 0 4px 15px rgba(0, 135, 81, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-800));
            box-shadow: 0 6px 20px rgba(0, 135, 81, 0.4);
            transform: translateY(-2px);
        }
        
        .nav-link-item {
            padding: 0.5rem 1rem;
            color: #374151;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
            display: inline-block;
        }
        
        .nav-link-item:hover {
            color: var(--primary-color);
        }
        
        .nav-link-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: transform 0.3s ease;
        }
        
        .nav-link-item:hover::after {
            transform: translateX(-50%) scaleX(1);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-600)) !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(0, 135, 81, 0.3) !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            cursor: pointer !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-800)) !important;
            box-shadow: 0 6px 20px rgba(0, 135, 81, 0.4) !important;
            transform: translateY(-2px) !important;
        }
        
        /* Footer Styles */
        .bg-dark-color {
            background-color: #1a1a2e !important;
        }
        
        footer {
            background-color: #1a1a2e !important;
            color: white !important;
        }
        
        footer a {
            color: #d1d5db !important;
            transition: color 0.2s ease !important;
        }
        
        footer a:hover {
            color: white !important;
        }
        
        footer .text-gray-300 {
            color: #d1d5db !important;
        }
        
        footer .text-gray-400 {
            color: #9ca3af !important;
        }
        
        footer .text-accent {
            color: #FFD100 !important;
        }
        
        footer .text-green-400 {
            color: #4ade80 !important;
        }
        
        footer .border-gray-700 {
            border-color: #374151 !important;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Section spacing */
        section {
            scroll-margin-top: 100px;
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        
        /* Badge Styles */
        .badge-primary {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: rgba(0, 135, 81, 0.1);
            color: #006b42;
        }
        
        .badge-secondary {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: rgba(204, 9, 47, 0.1);
            color: #a00724;
        }
        
        .badge-accent {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: rgba(255, 209, 0, 0.2);
            color: #1a1a2e;
        }
        
        /* Section Styles */
        .section {
            padding: 3rem 0;
        }
        
        @media (min-width: 768px) {
            .section {
                padding: 5rem 0;
            }
        }
        
        /* Container Styles */
        .container-wide {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container-wide {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .container-wide {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        .container-narrow {
            max-width: 1024px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Text Gradient */
        .text-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-600));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Gradient Background */
        .gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-600));
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Language Switcher -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end py-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">
                            <i class="bi bi-translate mr-1"></i>
                            {{ __('Language') }}:
                        </span>
                        <a href="{{ route('language.switch', 'en') }}" 
                           class="text-sm px-3 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-primary-gradient text-white' : 'text-gray-600 hover:text-primary' }}">
                            <i class="bi bi-flag-fill mr-1"></i> English
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('language.switch', 'pt') }}" 
                           class="text-sm px-3 py-1 rounded {{ app()->getLocale() === 'pt' ? 'bg-primary-gradient text-white' : 'text-gray-600 hover:text-primary' }}">
                            <i class="bi bi-flag-fill mr-1"></i> Português
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-gray-100" id="main-nav">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <img src="{{ asset('umoja.jpeg') }}" alt="UMOJA Logo" class="w-10 h-10 rounded-lg object-cover">
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-900 block leading-tight">
                                    UMOJA Angola
                                </span>
                                <span class="text-xs text-gray-500 hidden sm:block">
                                    {{ __('Angolan Community') }}
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="{{ route('home') }}#about" class="nav-link-item">
                            {{ __('About') }}
                        </a>
                        <a href="{{ route('home') }}#leadership" class="nav-link-item">
                            {{ __('Leadership') }}
                        </a>
                        <a href="{{ route('home') }}#features" class="nav-link-item">
                            {{ __('Features') }}
                        </a>
                        @auth('admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link-item">
                                <i class="bi bi-speedometer2 mr-1"></i>
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('registration') }}" class="ml-4 btn-primary text-white px-6 py-3 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold">
                                <i class="bi bi-person-plus mr-2"></i>
                                {{ __('Register Now') }}
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-btn" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            <i class="bi bi-list text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white">
                <div class="px-4 py-4 space-y-2">
                    <a href="{{ route('home') }}#about" class="block px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        {{ __('About') }}
                    </a>
                    <a href="{{ route('home') }}#leadership" class="block px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        {{ __('Leadership') }}
                    </a>
                    <a href="{{ route('home') }}#features" class="block px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        {{ __('Features') }}
                    </a>
                    <a href="{{ route('home') }}#contact" class="block px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                        {{ __('Contact') }}
                    </a>
                    @auth('admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                            <i class="bi bi-speedometer2 mr-2"></i>
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('registration') }}" class="block btn-primary text-white px-4 py-3 rounded-lg text-center font-semibold">
                            <i class="bi bi-person-plus mr-2"></i>
                            {{ __('Register Now') }}
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark-color text-white py-12 mt-12" style="background-color: #1a1a2e;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="bi bi-flag-fill text-accent mr-2"></i>
                            {{ config('app.name') }}
                        </h3>
                        <p class="text-gray-300">
                            {{ __('Connecting Angolans in South Africa through community support and collaboration.') }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold mb-4">{{ __('Quick Links') }}</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">{{ __('Home') }}</a></li>
                            <li><a href="{{ route('registration') }}" class="text-gray-300 hover:text-white">{{ __('Registration') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">{{ __('Privacy Policy') }}</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold mb-4">{{ __('Contact') }}</h4>
                        <div class="space-y-3">
                            <p class="text-gray-300 flex items-center">
                                <i class="bi bi-envelope mr-2 text-accent"></i>
                                <a href="mailto:info@umojaangola.org" class="hover:text-white transition">info@umojaangola.org</a>
                            </p>
                            <p class="text-gray-300 flex items-center">
                                <i class="bi bi-telephone mr-2 text-accent"></i>
                                <a href="tel:+27123456789" class="hover:text-white transition">+27 12 345 6789</a>
                            </p>
                            <p class="text-gray-300 flex items-center">
                                <i class="bi bi-whatsapp mr-2 text-green-400"></i>
                                <a href="https://wa.me/27123456789" target="_blank" class="hover:text-white transition">{{ __('WhatsApp') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    const icon = mobileMenuBtn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-list');
                        icon.classList.toggle('bi-x-lg');
                    }
                });
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mobileMenu && !mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    const icon = mobileMenuBtn?.querySelector('i');
                    if (icon) {
                        icon.classList.add('bi-list');
                        icon.classList.remove('bi-x-lg');
                    }
                }
            });
            
            // Navbar scroll effect
            const navbar = document.getElementById('main-nav');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('shadow-xl');
                        navbar.classList.remove('bg-white/95');
                        navbar.classList.add('bg-white');
                    } else {
                        navbar.classList.remove('shadow-xl');
                        navbar.classList.add('bg-white/95');
                        navbar.classList.remove('bg-white');
                    }
                });
            }
        });
        
        // Language persistence
        document.addEventListener('DOMContentLoaded', function() {
            // Store language preference
            const currentLang = '{{ app()->getLocale() }}';
            localStorage.setItem('preferred_language', currentLang);
            
            // Form validation and submission
            const registrationForm = document.getElementById('registration-form');
            if (registrationForm) {
                registrationForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ __('Processing...') }}
                    `;
                    submitBtn.disabled = true;
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("Success!") }}',
                                text: data.message,
                                confirmButtonText: '{{ __("Continue") }}',
                                confirmButtonColor: '#008751'
                            }).then(() => {
                                window.location.href = '{{ route("home") }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("Error") }}',
                                text: Object.values(data.errors || {}).flat().join('\n') || data.message,
                                confirmButtonColor: '#CC092F'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("Error") }}',
                            text: '{{ __("An error occurred. Please try again.") }}',
                            confirmButtonColor: '#CC092F'
                        });
                    } finally {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>