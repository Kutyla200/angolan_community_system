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
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --primary-color: #008751;
            --secondary-color: #CC092F;
            --accent-color: #FFD100;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --sidebar-width: 250px;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            background: linear-gradient(180deg, var(--primary-color) 0%, #006d42 100%);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                position: fixed;
                z-index: 1050;
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                width: 250px;
                transform: translateX(0);
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
                z-index: 1040;
            }
            
            .overlay.open {
                display: block;
            }
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(204, 9, 47, 0.3);
        }
        
        .nav-section-title {
            padding: 15px 20px 10px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .nav-badge {
            margin-left: auto;
            padding: 2px 8px;
            background: var(--secondary-color);
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: white;
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
        
        .user-menu {
            position: relative;
        }
        
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            display: none;
            z-index: 1000;
            margin-top: 5px;
        }
        
        .user-dropdown.show {
            display: block;
        }
        
        .user-dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .user-dropdown-item {
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .user-dropdown-item:hover {
            background: #f5f6fa;
        }
        
        .header-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f5f6fa;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .header-icon:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .header-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-color);
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 10px;
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
        <div class="sidebar-header p-6 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-red-500 rounded-full flex items-center justify-center">
                    <i class="bi bi-flag text-white"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-white">
                        {{ config('app.name') }}
                    </span>
                    <p class="text-sm text-white/80">{{ __('Admin Portal') }}</p>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <div class="p-4 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">
                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-white truncate">{{ auth('admin')->user()->name }}</p>
                    <p class="text-sm text-white/80 capitalize">{{ auth('admin')->user()->role }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-nav p-4 space-y-1">
            <div class="nav-section-title">{{ __('Main Menu') }}</div>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 mr-3"></i>
                {{ __('Dashboard') }}
            </a>
            
            <a href="{{ route('admin.members.index') }}" 
               class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                <i class="bi bi-people mr-3"></i>
                {{ __('Members') }}
                @if(isset($newMembersCount) && $newMembersCount > 0)
                    <span class="nav-badge">{{ $newMembersCount }}</span>
                @else
                    <span class="ml-auto bg-white/20 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">
                        {{ \App\Models\Member::count() }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('admin.analytics') }}" 
               class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart mr-3"></i>
                {{ __('Analytics') }}
            </a>
            
            <div class="nav-section-title mt-6">{{ __('Management') }}</div>
            
            <a href="{{ route('admin.export.csv') }}" class="nav-link">
                <i class="bi bi-download mr-3"></i>
                {{ __('Export Data') }}
            </a>
            
            <a href="{{ route('admin.logs.index') }}" 
               class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                <i class="bi bi-clock-history mr-3"></i>
                {{ __('Audit Logs') }}
            </a>
            
            <div class="nav-section-title mt-6">{{ __('System') }}</div>
            
            @if(auth('admin')->user()->canManageSettings())
            <a href="{{ route('admin.settings.index') }}" 
               class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear mr-3"></i>
                {{ __('Settings') }}
            </a>
            @endif
            
            <div class="nav-section-title mt-6">{{ __('Account') }}</div>
            
            <a href="#" class="nav-link">
                <i class="bi bi-person mr-3"></i>
                {{ __('My Profile') }}
            </a>
            
            <form method="POST" action="{{ route('admin.logout') }}" id="logout-form" class="inline">
                @csrf
                <button type="submit" class="nav-link w-full text-left">
                    <i class="bi bi-box-arrow-right mr-3"></i>
                    {{ __('Logout') }}
                </button>
            </form>
        </nav>
        
        <!-- Footer -->
        <div class="p-4 border-t border-white/10 mt-auto">
            <div class="text-center text-xs text-white/60">
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
                    
                    <nav class="ml-4" aria-label="breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm">
                            <li class="text-gray-600">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">
                                    <i class="bi bi-house-door mr-1"></i>
                                    {{ __('Home') }}
                                </a>
                            </li>
                            @hasSection('breadcrumb')
                                <li class="text-gray-400">/</li>
                                @yield('breadcrumb')
                            @endif
                        </ol>
                    </nav>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="hidden md:block relative">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               placeholder="{{ __('Search members...') }}" 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               id="globalSearch"
                               onkeypress="handleSearch(event)">
                    </div>
                    
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
                    <button class="header-icon relative" onclick="toggleNotifications()">
                        <i class="bi bi-bell text-lg"></i>
                        <span class="badge">3</span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="user-menu" x-data="{ open: false }">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center cursor-pointer"
                             @click="open = !open">
                            <span class="text-white font-bold">
                                {{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}
                            </span>
                        </div>
                        
                        <div class="user-dropdown" :class="{ 'show': open }" @click.away="open = false">
                            <div class="user-dropdown-header">
                                <h6 class="font-semibold">{{ auth('admin')->user()->name }}</h6>
                                <p class="text-sm text-gray-600 mt-1">{{ auth('admin')->user()->email }}</p>
                                <span class="inline-block mt-2 px-2 py-1 text-xs bg-primary/10 text-primary rounded">
                                    {{ auth('admin')->user()->role_name ?? auth('admin')->user()->role }}
                                </span>
                            </div>
                            <a href="#" class="user-dropdown-item">
                                <i class="bi bi-person"></i>
                                {{ __('My Profile') }}
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="user-dropdown-item">
                                <i class="bi bi-gear"></i>
                                {{ __('Settings') }}
                            </a>
                            <a href="#" class="user-dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <i class="bi bi-check-circle text-green-600 mr-3"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-circle text-red-600 mr-3"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="bi bi-exclamation-triangle text-red-600 mr-3"></i>
                        <span class="text-red-800 font-semibold">{{ __('Please fix the following errors:') }}</span>
                    </div>
                    <ul class="text-red-700 text-sm list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Breadcrumb content section -->
            @hasSection('page-header')
                <div class="page-header mb-6">
                    @yield('page-header')
                </div>
            @else
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    @yield('header', __('Dashboard'))
                </h1>
                @hasSection('page-subtitle')
                    <p class="text-gray-600 mb-6">@yield('page-subtitle')</p>
                @endif
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }
        
        function handleSearch(event) {
            if (event.key === 'Enter') {
                const searchTerm = event.target.value;
                window.location.href = '/admin/members?search=' + encodeURIComponent(searchTerm);
            }
        }
        
        function toggleNotifications() {
            // Implement notification dropdown logic here
            console.log('Toggle notifications');
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
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(alert => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                });
            }, 5000);
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