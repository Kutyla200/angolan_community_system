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
            --secondary-color: #CC092F; /* Angolan flag red */
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
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #006b42;
            border-color: #006b42;
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
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-primary-gradient rounded-full flex items-center justify-center">
                                <i class="bi bi-people-fill text-white text-xl"></i>
                            </div>
                            <span class="text-xl font-bold text-gray-900">
                                {{ config('app.name') }}
                            </span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth('admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary px-3 py-2">
                                <i class="bi bi-speedometer2 mr-1"></i>
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('admin.members') }}" class="text-gray-700 hover:text-primary px-3 py-2">
                                <i class="bi bi-people mr-1"></i>
                                {{ __('Members') }}
                            </a>
                            <a href="{{ route('admin.analytics') }}" class="text-gray-700 hover:text-primary px-3 py-2">
                                <i class="bi bi-bar-chart mr-1"></i>
                                {{ __('Analytics') }}
                            </a>
                        @else
                            <a href="{{ route('registration') }}" class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                                <i class="bi bi-person-plus mr-2"></i>
                                {{ __('Register Now') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark-color text-white py-8 mt-12">
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
                        <p class="text-gray-300">
                            <i class="bi bi-envelope mr-2"></i>
                            {{ __('For support, email: support@angolancommunity.org') }}
                        </p>
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