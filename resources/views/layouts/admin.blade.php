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
    @vite(['resources/css/app.css'])
    
    <style>
        :root {
            --primary-color: #008751;
            --secondary-color: #CC092F;
            --accent-color: #FFD100;
            --dark-color: #1a1a2e;
            --sidebar-width: 260px;
        }
        
        /* ============================
           SIDEBAR
        ============================ */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #006b42 0%, #004d2f 100%);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        
        /* ============================
           MOBILE OVERLAY
        ============================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0,0,0,0.3);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
        }
        
        /* ============================
           NAV LINKS
        ============================ */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(3px);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            font-weight: 600;
            box-shadow: inset 3px 0 0 var(--accent-color);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .nav-link button {
            background: none;
            border: none;
            color: inherit;
            font: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0;
        }
        
        .nav-section-title {
            padding: 16px 16px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.4);
        }
        
        .nav-count {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9);
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .nav-count.new {
            background: var(--secondary-color);
            color: white;
        }
        
        /* ============================
           SIDEBAR HEADER
        ============================ */
        .sidebar-header {
            padding: 20px 16px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-brand-icon img {
            width: 34px;
            height: 34px;
            object-fit: cover;
            border-radius: 7px;
        }

        .sidebar-brand-text {
            line-height: 1.2;
        }

        .sidebar-brand-name {
            font-size: 15px;
            font-weight: 700;
            color: white;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
        }
        
        /* ============================
           USER PROFILE IN SIDEBAR
        ============================ */
        .sidebar-user {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: rgba(255,255,255,0.55);
        }
        
        /* ============================
           LANGUAGE SWITCHER (SIDEBAR)
        ============================ */
        .sidebar-lang {
            padding: 10px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .sidebar-lang span {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            margin-right: 4px;
        }

        .lang-btn {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            color: rgba(255,255,255,0.7);
        }

        .lang-btn:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .lang-btn.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .lang-divider {
            color: rgba(255,255,255,0.2);
            font-size: 11px;
        }

        /* ============================
           STAT CARDS
        ============================ */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            transition: all 0.25s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        /* ============================
           CARD
        ============================ */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }
        
        /* ============================
           BADGES
        ============================ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-primary {
            background-color: rgba(0, 135, 81, 0.1);
            color: #006b42;
        }
        
        .badge-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #15803d;
        }
        
        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #b45309;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
        
        /* ============================
           TOP HEADER
        ============================ */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 1px solid #f0f0f0;
        }

        /* ============================
           USER DROPDOWN
        ============================ */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
            min-width: 220px;
            display: none;
            z-index: 200;
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }
        
        .user-dropdown-menu.show {
            display: block;
            animation: dropIn 0.2s ease;
        }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-dropdown-header {
            padding: 14px 16px;
            border-bottom: 1px solid #f5f5f5;
            background: #fafafa;
        }
        
        .user-dropdown-item {
            padding: 11px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        
        .user-dropdown-item:hover {
            background: #f9fafb;
        }

        .user-dropdown-item.danger {
            color: #dc2626;
        }

        .user-dropdown-item.danger:hover {
            background: #fef2f2;
        }
        
        /* ============================
           HEADER ICON BTN
        ============================ */
        .header-icon {
            position: relative;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f5f6fa;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        
        .header-icon:hover {
            background: #008751;
            color: white;
            border-color: transparent;
        }
        
        .header-icon .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--secondary-color);
            border-radius: 50%;
            border: 2px solid white;
        }

        /* ============================
           SIDEBAR FOOTER
        ============================ */
        .sidebar-footer {
            margin-top: auto;
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }

        .sidebar-footer p {
            text-align: center;
            font-size: 11px;
            color: rgba(255,255,255,0.35);
        }

        /* ============================
           MAIN CONTENT AREA
        ============================ */
        .page-main {
            flex: 1;
            padding: 24px;
        }

        @media (max-width: 640px) {
            .page-main {
                padding: 16px;
            }
        }

        /* ============================
           ALERTS
        ============================ */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #22c55e;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================
           HAMBURGER BUTTON
        ============================ */
        .hamburger {
            display: none;
            padding: 8px;
            border-radius: 8px;
            background: #f5f6fa;
            border: none;
            cursor: pointer;
            color: #374151;
            transition: background 0.2s;
        }

        .hamburger:hover { background: #e5e7eb; }

        @media (max-width: 768px) {
            .hamburger { display: flex; align-items: center; justify-content: center; }
        }

        /* ============================
           SEARCH BAR
        ============================ */
        .search-bar {
            position: relative;
        }

        .search-bar input {
            padding: 8px 12px 8px 36px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            background: #f9fafb;
            color: #374151;
            outline: none;
            transition: all 0.2s;
            width: 220px;
        }

        .search-bar input:focus {
            background: white;
            border-color: #008751;
            box-shadow: 0 0 0 3px rgba(0,135,81,0.1);
            width: 260px;
        }

        .search-bar i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }

        @media (max-width: 640px) {
            .search-bar { display: none; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    
    <!-- Mobile Overlay - clicking this closes sidebar -->
    <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Main navigation">
        
        <!-- Brand -->
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('umoja.jpeg') }}" alt="UMOJA Logo">
                </div>
                <div class="sidebar-brand-text">
                    <div class="sidebar-brand-name">UMOJA Angola</div>
                    <div class="sidebar-brand-sub">Admin Portal</div>
                </div>
            </div>
        </div>
        
    

        <!-- Language Switcher -->
        <div class="sidebar-lang">
            <span><i class="bi bi-translate"></i> Lang:</span>
            <a href="{{ route('language.switch', 'en') }}" 
               class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            <span class="lang-divider">|</span>
            <a href="{{ route('language.switch', 'pt') }}" 
               class="lang-btn {{ app()->getLocale() === 'pt' ? 'active' : '' }}">PT</a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4" role="menu">
            <div class="nav-section-title">{{ __('Main') }}</div>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               role="menuitem">
                <i class="bi bi-speedometer2"></i>
                {{ __('Dashboard') }}
                <span class="nav-count">{{ \App\Models\Member::whereDate('created_at', today())->count() }} today</span>
            </a>
            
            <a href="{{ route('admin.members.index') }}" 
               class="nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}"
               role="menuitem">
                <i class="bi bi-people"></i>
                {{ __('Members') }}
                <span class="nav-count">{{ \App\Models\Member::count() }}</span>
            </a>
            
            <a href="{{ route('admin.analytics') }}" 
               class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}"
               role="menuitem">
                <i class="bi bi-bar-chart-line"></i>
                {{ __('Analytics') }}
            </a>
            
            <div class="nav-section-title mt-4">{{ __('Data') }}</div>
            
            <a href="{{ route('admin.export.csv') }}" class="nav-link" role="menuitem">
                <i class="bi bi-download"></i>
                {{ __('Export CSV') }}
            </a>

            <a href="{{ route('admin.export.pdf') }}" class="nav-link" role="menuitem">
                <i class="bi bi-file-earmark-pdf"></i>
                {{ __('Export PDF') }}
            </a>
            
            <a href="{{ route('admin.logs.index') }}" 
               class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}"
               role="menuitem">
                <i class="bi bi-clock-history"></i>
                {{ __('Audit Logs') }}
            </a>
            
            <div class="nav-section-title mt-4">{{ __('System') }}</div>
            
            @if(auth('admin')->user()->canManageSettings())
            <a href="{{ route('admin.settings.index') }}" 
               class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"
               role="menuitem">
                <i class="bi bi-gear"></i>
                {{ __('Settings') }}
            </a>
            @endif

            <a href="{{ url('/') }}" class="nav-link" role="menuitem" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>
                {{ __('View Site') }}
            </a>
            
            <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                @csrf
                <a href="#" class="nav-link" role="menuitem"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    {{ __('Logout') }}
                </a>
            </form>
        </nav>
        
        <div class="sidebar-footer">
            <p>v1.0.0 &copy; {{ date('Y') }} UMOJA Angola</p>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        
        <!-- Top Header -->
        <header class="top-header" role="banner">
            <div class="flex items-center justify-between px-5 py-3 gap-3">
                
                <!-- Left: Hamburger + Breadcrumb -->
                <div class="flex items-center gap-3">
                    <button class="hamburger" id="hamburger-btn" 
                            aria-label="Toggle navigation" 
                            aria-expanded="false"
                            aria-controls="sidebar">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    
                    <nav aria-label="Breadcrumb" class="hidden sm:block">
                        <ol class="flex items-center gap-2 text-sm text-gray-500">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-green-700 flex items-center gap-1">
                                    <i class="bi bi-house-door text-xs"></i>
                                    <span class="hidden md:inline">{{ __('Home') }}</span>
                                </a>
                            </li>
                            @hasSection('breadcrumb')
                                <li class="text-gray-300">/</li>
                                @yield('breadcrumb')
                            @endif
                        </ol>
                    </nav>
                </div>
                
                <!-- Right: Search + Actions + User -->
                <div class="flex items-center gap-3">
                    
                    <!-- Search -->
                    <div class="search-bar">
                        <i class="bi bi-search"></i>
                        <input type="text" 
                               placeholder="{{ __('Search members...') }}"
                               id="globalSearch"
                               aria-label="Search members"
                               onkeypress="if(event.key==='Enter') window.location='/admin/members?search='+encodeURIComponent(this.value)">
                    </div>
                    
                    <!-- Notifications -->
                    <button class="header-icon" aria-label="Notifications" title="Notifications">
                        <i class="bi bi-bell text-base"></i>
                        <span class="notif-dot" aria-hidden="true"></span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-2 rounded-10 hover:bg-gray-50 transition border border-transparent hover:border-gray-200"
                                aria-haspopup="true"
                                :aria-expanded="open">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ auth('admin')->user()->name }}</p>
                                <p class="text-xs text-gray-500 leading-tight">{{ ucfirst(auth('admin')->user()->role) }}</p>
                            </div>
                            <i class="bi bi-chevron-down text-xs text-gray-400 hidden md:block" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div class="user-dropdown-menu" :class="{ 'show': open }" @click.away="open = false" role="menu">
                            <div class="user-dropdown-header">
                                <p class="font-semibold text-gray-900 text-sm">{{ auth('admin')->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ auth('admin')->user()->email }}</p>
                                <span class="inline-block mt-2 px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full font-medium">
                                    {{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}
                                </span>
                            </div>
                            
                            <div class="py-1">
                                @if(auth('admin')->user()->canManageSettings())
                                <a href="{{ route('admin.settings.index') }}" class="user-dropdown-item" role="menuitem">
                                    <i class="bi bi-gear text-gray-400"></i>
                                    {{ __('Settings') }}
                                </a>
                                @endif
                                
                                <!-- Language in header dropdown too -->
                                <div class="px-4 py-2 border-t border-gray-50">
                                    <p class="text-xs text-gray-400 mb-2 font-medium uppercase tracking-wide">{{ __('Language') }}</p>
                                    <div class="flex gap-2">
                                        <a href="{{ route('language.switch', 'en') }}" 
                                           class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition {{ app()->getLocale() === 'en' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            🇬🇧 EN
                                        </a>
                                        <a href="{{ route('language.switch', 'pt') }}" 
                                           class="flex-1 text-center py-1.5 rounded-lg text-xs font-semibold transition {{ app()->getLocale() === 'pt' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            🇦🇴 PT
                                        </a>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 mt-1">
                                    <button onclick="document.getElementById('logout-form').submit()" 
                                            class="user-dropdown-item danger w-full"
                                            role="menuitem">
                                        <i class="bi bi-box-arrow-right text-red-400"></i>
                                        {{ __('Logout') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="page-main" id="page-content">
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert-success" role="alert" id="alert-success">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-green-600 text-lg flex-shrink-0"></i>
                    <span class="text-green-800 text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 flex-shrink-0" aria-label="Dismiss">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert-error" role="alert" id="alert-error">
                <div class="flex items-start gap-3">
                    <i class="bi bi-exclamation-circle-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                    <span class="text-red-800 text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 flex-shrink-0 ml-3" aria-label="Dismiss">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert-error" role="alert">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg"></i>
                        <span class="text-red-800 text-sm font-semibold">{{ __('Please fix the following errors:') }}</span>
                    </div>
                    <ul class="text-red-700 text-sm list-disc pl-8 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            <!-- Page Header -->
            @hasSection('page-header')
                <div class="mb-6">@yield('page-header')</div>
            @else
                @hasSection('header')
                <h1 class="text-2xl font-bold text-gray-900 mb-6">@yield('header')</h1>
                @endif
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    // ============================
    // SIDEBAR TOGGLE — FIXED
    // ============================
    (function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburger = document.getElementById('hamburger-btn');
        
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            hamburger.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden'; // prevent scroll behind overlay
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }
        
        function toggleSidebar() {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }
        
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
        
        // Overlay click closes sidebar
        overlay.addEventListener('click', closeSidebar);
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
        
        // Close when nav link clicked on mobile
        sidebar.querySelectorAll('.nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });
    })();
    
    // ============================
    // AUTO-DISMISS ALERTS
    // ============================
    setTimeout(function() {
        ['alert-success', 'alert-error'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 400);
            }
        });
    }, 5000);
    
    // ============================
    // EXPORT HELPER
    // ============================
    function exportData(format) {
        const url = format === 'csv' 
            ? '{{ route("admin.export.csv") }}' 
            : '{{ route("admin.export.pdf") }}';
        
        Swal.fire({
            title: '{{ __("Exporting Data") }}',
            text: '{{ __("Please wait while we prepare your file...") }}',
            icon: 'info',
            showConfirmButton: false,
            timer: 1500,
            allowOutsideClick: false
        }).then(function() {
            window.location.href = url;
        });
    }

    // ============================
    // CONFIRM DELETE HELPER
    // ============================
    function confirmDelete(action, type) {
        type = type || 'item';
        Swal.fire({
            title: '{{ __("Are you sure?") }}',
            text: 'You are about to delete this ' + type + '. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, delete") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = action;
            }
        });
    }
    </script>
    
    @stack('scripts')
</body>
</html>