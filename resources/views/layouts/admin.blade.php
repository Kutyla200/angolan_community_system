<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ in_array(app()->getLocale(), ['ar', 'fa', 'he']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', __('Admin Dashboard')) - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --primary-color: #008751;
            --secondary-color: #CC092F;
            --accent-color: #FFD100;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }
        
        .sidebar {
            width: 250px;
            transition: all 0.3s ease;
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                position: fixed;
                z-index: 50;
            }
            
            .sidebar.open {
                width: 250px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            
            .overlay.open {
                display: block;
            }
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #4b5563;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }
        
        .nav-link.active {
            background-color: rgba(0, 135, 81, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-primary {
            background-color: rgba(0, 135, 81, 0.1);
            color: var(--primary-color);
        }
        
        .badge-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: rgb(34, 197, 94);
        }
        
        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: rgb(245, 158, 11);
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar bg-white h-screen fixed left-0 top-0 shadow-lg overflow-y-auto" id="sidebar">
        <!-- Logo -->
        <div class="p-6 border-b">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-gradient rounded-full flex items-center justify-center">
                    <i class="bi bi-shield-lock text-white"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-gray-900">
                        {{ config('app.name') }}
                    </span>
                    <p class="text-sm text-gray-600">{{ __('Admin Portal') }}</p>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <div class="p-4 border-b">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary-gradient rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">
                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 truncate">{{ auth('admin')->user()->name }}</p>
                    <p class="text-sm text-gray-600 capitalize">{{ auth('admin')->user()->role }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 mr-3"></i>
                {{ __('Dashboard') }}
            </a>
            
            <a href="{{ route('admin.members') }}" 
               class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                <i class="bi bi-people mr-3"></i>
                {{ __('Members') }}
                <span class="ml-auto bg-primary text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">
                    {{ \App\Models\Member::count() }}
                </span>
            </a>
            
            <a href="{{ route('admin.analytics') }}" 
               class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart mr-3"></i>
                {{ __('Analytics') }}
            </a>
            
            <div class="pt-4 mt-4 border-t">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('System') }}
                </p>
                
                <a href="#" class="nav-link">
                    <i class="bi bi-gear mr-3"></i>
                    {{ __('Settings') }}
                </a>
                
                <a href="#" class="nav-link">
                    <i class="bi bi-shield-check mr-3"></i>
                    {{ __('Audit Logs') }}
                </a>
                
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-full text-left">
                        <i class="bi bi-box-arrow-right mr-3"></i>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </nav>
        
        <!-- Footer -->
        <div class="p-4 border-t mt-auto">
            <div class="text-center text-xs text-gray-500">
                <p>{{ __('Version') }} 1.0.0</p>
                <p class="mt-1">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm border-b">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <button id="sidebar-toggle" 
                            class="p-2 rounded-lg hover:bg-gray-100 md:hidden"
                            onclick="toggleSidebar()">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    
                    <h1 class="text-xl font-bold text-gray-900 ml-4">
                        @yield('header', __('Dashboard'))
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="hidden md:flex items-center space-x-2">
                        <span class="text-sm text-gray-600">
                            <i class="bi bi-translate mr-1"></i>
                        </span>
                        <a href="{{ route('language.switch', 'en') }}" 
                           class="text-sm px-3 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-primary text-white' : 'text-gray-600 hover:text-primary' }}">
                            EN
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('language.switch', 'pt') }}" 
                           class="text-sm px-3 py-1 rounded {{ app()->getLocale() === 'pt' ? 'bg-primary text-white' : 'text-gray-600 hover:text-primary' }}">
                            PT
                        </a>
                    </div>
                    
                    <!-- Notifications -->
                    <button class="p-2 rounded-lg hover:bg-gray-100 relative">
                        <i class="bi bi-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-check-circle text-green-600 mr-3"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-circle text-red-600 mr-3"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            overlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking a link on mobile
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        toggleSidebar();
                    }
                });
            });
        });
        
        // Confirm delete function
        function confirmDelete(action, type = 'item') {
            Swal.fire({
                title: '{{ __("Are you sure?") }}',
                text: `{{ __("You are about to delete this") }} ${type}. {{ __("This action cannot be undone.") }}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("Yes, delete") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = action;
                }
            });
        }
        
        // Export function
        function exportData(format) {
            let url = format === 'csv' 
                ? '{{ route("admin.export.csv") }}' 
                : '{{ route("admin.export.pdf") }}';
            
            Swal.fire({
                title: '{{ __("Exporting Data") }}',
                text: '{{ __("Please wait while we prepare your file...") }}',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false
            });
            
            window.location.href = url;
        }
    </script>
    
    @stack('scripts')
</body>
</html>