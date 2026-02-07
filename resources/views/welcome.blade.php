@extends('layouts.app')

@section('title', __('Welcome to UMOJA Angola'))

@push('styles')
<style>
    /* Modern Hero Section */
    .hero-section {
        min-height: 90vh;
        background: linear-gradient(135deg, #008751 0%, #006b42 50%, #004d2f 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 209, 0, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(204, 9, 47, 0.15) 0%, transparent 50%);
        animation: pulse-glow 8s ease-in-out infinite;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 100px,
            rgba(255, 255, 255, 0.03) 100px,
            rgba(255, 255, 255, 0.03) 200px
        );
        animation: slide 20s linear infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }
    
    @keyframes slide {
        0% { transform: translate(0, 0); }
        100% { transform: translate(100px, 100px); }
    }
    
    .hero-content {
        position: relative;
        z-index: 10;
    }
    
    .hero-logo {
        animation: float 6s ease-in-out infinite;
        filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3));
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    .hero-title {
        animation: fadeInUp 1s ease-out;
    }
    
    .hero-subtitle {
        animation: fadeInUp 1.2s ease-out;
    }
    
    .hero-cta {
        animation: fadeInUp 1.4s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Modern Stats Cards */
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: fadeInScale 0.8s ease-out;
        animation-fill-mode: both;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        background: rgba(255, 255, 255, 1);
    }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Section Animations */
    .section-fade-in {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .section-fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Modern Feature Cards */
    .feature-card {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #008751, #CC092F, #FFD100);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .feature-card:hover::before {
        transform: scaleX(1);
    }
    
    .feature-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.4s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    /* Leadership Cards */
    .leader-card {
        perspective: 1000px;
    }
    
    .leader-card-inner {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }
    
    .leader-card:hover .leader-card-inner {
        transform: translateY(-15px) rotateX(5deg);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
    }
    
    .leader-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        position: relative;
        overflow: hidden;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.4s ease;
    }
    
    .leader-card:hover .leader-avatar {
        transform: scale(1.1);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }
    
    .leader-avatar::after {
        content: '';
        position: absolute;
        inset: -50%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
    }
    
    @keyframes shine {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    
    /* Modern CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #008751 0%, #006b42 100%);
        position: relative;
        overflow: hidden;
    }
    
    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 209, 0, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Modern Button */
    .btn-modern {
        background: white;
        color: #008751;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(0, 135, 81, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-modern:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }
    
    .btn-modern span {
        position: relative;
        z-index: 1;
    }
    
    /* Testimonial Cards */
    .testimonial-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
        border-left: 4px solid #008751;
    }
    
    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* Step Cards */
    .step-card {
        text-align: center;
        position: relative;
    }
    
    .step-number {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #008751, #006b42);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 135, 81, 0.3);
        transition: all 0.4s ease;
        position: relative;
    }
    
    .step-card:hover .step-number {
        transform: scale(1.15) rotate(360deg);
        box-shadow: 0 15px 40px rgba(0, 135, 81, 0.4);
    }
    
    .step-number::after {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        border: 3px solid #FFD100;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .step-card:hover .step-number::after {
        opacity: 1;
        animation: pulse-ring 2s infinite;
    }
    
    @keyframes pulse-ring {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.5; }
    }
    
    /* About Section */
    .about-section {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa, #ffffff);
        position: relative;
    }
    
    .about-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
    }
    
    .about-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* Contact Section */
    .contact-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.4s ease;
        text-align: center;
    }
    
    .contact-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .contact-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        transition: all 0.4s ease;
    }
    
    .contact-card:hover .contact-icon {
        transform: scale(1.15) rotate(360deg);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 80vh;
            padding: 1rem 0;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
    }
    
    /* Scroll Animations */
    .animate-on-scroll {
        opacity: 1 !important;
        transform: translateY(0) !important;
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .animate-on-scroll.animated {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    
    /* Ensure sections are visible */
    section {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Section backgrounds */
    .about-section {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa, #ffffff) !important;
    }
    
    #features {
        background: white !important;
    }
    
    #leadership {
        background: white !important;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container-wide px-4 sm:px-6 lg:px-8">
        <div class="hero-content text-center max-w-6xl mx-auto">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="hero-logo relative">
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-full bg-white flex items-center justify-center shadow-2xl border-8 border-white/20">
                        <img src="{{ asset('umoja.jpeg') }}" alt="UMOJA Angola" class="w-full h-full object-cover rounded-full p-2">
                    </div>
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center shadow-xl animate-pulse">
                        <i class="bi bi-flag-fill text-white text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-xl animate-pulse" style="animation-delay: 0.5s;">
                        <i class="bi bi-heart-fill text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Title -->
            <div class="hero-title mb-6">
                <h1 class="text-5xl md:text-7xl font-black text-white mb-4 leading-tight">
                    <span class="block">UMOJA</span>
                    <span class="block text-yellow-300">Angola</span>
                </h1>
                <p class="text-xl md:text-2xl text-white/90 font-light">
                    {{ __('Angolan Community Organization in South Africa') }}
                </p>
            </div>
            
            <!-- Tagline -->
            <div class="hero-subtitle mb-10">
                <p class="text-2xl md:text-3xl text-white/95 font-semibold mb-2">
                    {{ __('United in Unity') }}
                </p>
                <p class="text-xl md:text-2xl text-yellow-200 font-medium">
                    {{ __('Stronger Together') }}
                </p>
            </div>
            
            <!-- Description -->
            <div class="mb-12 max-w-3xl mx-auto">
                <p class="text-lg md:text-xl text-white/90 leading-relaxed">
                    {{ __('UMOJA Angola connects Angolans across South Africa through mutual support, skills sharing, and community empowerment. Together, we build a stronger, more united Angolan community.') }}
                </p>
            </div>
            
            <!-- CTA Buttons -->
            <div class="hero-cta flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('registration') }}" class="btn-modern inline-flex items-center justify-center">
                    <span>
                        <i class="bi bi-person-plus-fill mr-2"></i>
                        {{ __('Register Now') }}
                    </span>
                </a>
                <a href="#about" class="btn-modern inline-flex items-center justify-center bg-white/10 backdrop-blur-md text-white border-2 border-white/30 hover:bg-white/20">
                    <span>
                        <i class="bi bi-info-circle mr-2"></i>
                        {{ __('Learn More') }}
                    </span>
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-green-800 mb-2">500+</div>
                    <div class="text-sm font-semibold text-gray-700">{{ __('Members') }}</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-800 mb-2">9</div>
                    <div class="text-sm font-semibold text-gray-700">{{ __('Provinces') }}</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-yellow-800 mb-2">50+</div>
                    <div class="text-sm font-semibold text-gray-700">{{ __('Skills') }}</div>
                </div>
                <div class="stat-card rounded-2xl p-6 text-center">
                    <div class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-800 mb-2">24/7</div>
                    <div class="text-sm font-semibold text-gray-700">{{ __('Support') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section about-section" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: linear-gradient(to bottom, #ffffff, #f8f9fa, #ffffff);">
    <div class="container-wide px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="badge-primary mb-4 inline-block">{{ __('About UMOJA') }}</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ __('Who We Are') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ __('UMOJA Angola is a community organization dedicated to uniting and empowering Angolans living in South Africa. We believe in the power of unity, mutual support, and collective growth.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <div class="about-card animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-2xl flex items-center justify-center mr-4">
                        <i class="bi bi-bullseye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">{{ __('Our Mission') }}</h3>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    {{ __('To create a strong, united, and supportive network of Angolans in South Africa, fostering community cohesion, cultural preservation, and mutual assistance for the betterment of all members.') }}
                </p>
            </div>
            
            <div class="about-card animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-2xl flex items-center justify-center mr-4">
                        <i class="bi bi-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">{{ __('Our Vision') }}</h3>
                </div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    {{ __('A thriving, self-sufficient Angolan community in South Africa where every member has access to support, opportunities, and resources needed to succeed and contribute to both the Angolan and South African societies.') }}
                </p>
            </div>
        </div>
        
        <!-- Objectives -->
        <div class="about-card animate-on-scroll">
            <div class="flex items-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center mr-4">
                    <i class="bi bi-list-check text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900">{{ __('Our Objectives') }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Promote unity and solidarity among Angolans in South Africa') }}</span>
                </div>
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Provide support and assistance to community members in need') }}</span>
                </div>
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Facilitate skills sharing, job opportunities, and professional networking') }}</span>
                </div>
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Preserve and promote Angolan culture and heritage') }}</span>
                </div>
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Advocate for the rights and interests of Angolan community members') }}</span>
                </div>
                <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-1"></i>
                    <span class="text-gray-700">{{ __('Build bridges between the Angolan and South African communities') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section bg-white" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: white;">
    <div class="container-wide px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="badge-secondary mb-4 inline-block">{{ __('Why Join UMOJA?') }}</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ __('Empowering Our Community') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon bg-gradient-to-br from-green-500 to-green-700 text-white">
                    <i class="bi bi-shield-check text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-center mb-4 text-gray-900">{{ __('Secure & Private') }}</h3>
                <p class="text-gray-600 text-center mb-6">
                    {{ __('Your data is protected with enterprise-grade security, encryption, and strict privacy controls. We are POPIA compliant.') }}
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-green-600 mr-3"></i>
                        <span>{{ __('End-to-end encryption') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-green-600 mr-3"></i>
                        <span>{{ __('Regular security audits') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-green-600 mr-3"></i>
                        <span>{{ __('Data ownership retained') }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon bg-gradient-to-br from-red-500 to-red-700 text-white">
                    <i class="bi bi-people-fill text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-center mb-4 text-gray-900">{{ __('Community Network') }}</h3>
                <p class="text-gray-600 text-center mb-6">
                    {{ __('Connect with fellow Angolans for support, opportunities, and meaningful collaborations across South Africa.') }}
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-red-600 mr-3"></i>
                        <span>{{ __('Verified community members') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-red-600 mr-3"></i>
                        <span>{{ __('Local meetups & events') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-red-600 mr-3"></i>
                        <span>{{ __('Cultural exchange programs') }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="feature-card animate-on-scroll">
                <div class="feature-icon bg-gradient-to-br from-yellow-400 to-yellow-600 text-white">
                    <i class="bi bi-briefcase-fill text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-center mb-4 text-gray-900">{{ __('Opportunities') }}</h3>
                <p class="text-gray-600 text-center mb-6">
                    {{ __('Access exclusive job referrals, business opportunities, professional networking, and skill development programs.') }}
                </p>
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-yellow-600 mr-3"></i>
                        <span>{{ __('Job matching system') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-yellow-600 mr-3"></i>
                        <span>{{ __('Business partnerships') }}</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="bi bi-check-circle-fill text-yellow-600 mr-3"></i>
                        <span>{{ __('Skill certification') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section bg-gradient-to-b from-gray-50 to-white" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: linear-gradient(to bottom, #f9fafb, #ffffff);">
    <div class="container-wide px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="badge-accent mb-4 inline-block">{{ __('Simple Process') }}</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ __('How It Works') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="step-card animate-on-scroll">
                <div class="step-number">1</div>
                <h3 class="text-xl font-black mb-3 text-gray-900">{{ __('Register') }}</h3>
                <p class="text-gray-600">
                    {{ __('Fill out our secure registration form with your basic information. Takes less than 5 minutes.') }}
                </p>
            </div>
            
            <div class="step-card animate-on-scroll">
                <div class="step-number">2</div>
                <h3 class="text-xl font-black mb-3 text-gray-900">{{ __('Share Skills') }}</h3>
                <p class="text-gray-600">
                    {{ __('Tell us about your skills, experience, and how you can help other community members.') }}
                </p>
            </div>
            
            <div class="step-card animate-on-scroll">
                <div class="step-number">3</div>
                <h3 class="text-xl font-black mb-3 text-gray-900">{{ __('Get Verified') }}</h3>
                <p class="text-gray-600">
                    {{ __('Our community team verifies your registration to ensure safety and authenticity.') }}
                </p>
            </div>
            
            <div class="step-card animate-on-scroll">
                <div class="step-number">4</div>
                <h3 class="text-xl font-black mb-3 text-gray-900">{{ __('Connect') }}</h3>
                <p class="text-gray-600">
                    {{ __('Start connecting with community members, opportunities, and support networks immediately.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Section -->
<section id="leadership" class="section bg-white" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: white;">
    <div class="container-wide px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="badge-primary mb-4 inline-block">{{ __('Our Leadership') }}</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ __('Meet Our Leadership Team') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('Dedicated leaders working tirelessly to serve and strengthen the Angolan community in South Africa.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Leader 1 -->
            <div class="leader-card animate-on-scroll">
                <div class="leader-card-inner text-center">
                    <div class="leader-avatar bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white text-5xl font-bold">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">{{ __('President') }}</h3>
                    <p class="text-green-600 font-bold mb-4 text-lg">{{ __('Name Surname') }}</p>
                    <p class="text-gray-600 mb-6 min-h-[60px]">
                        {{ __('Leading the organization with vision and dedication to unite the Angolan community.') }}
                    </p>
                    <div class="space-y-3">
                        <a href="tel:+27123456789" class="flex items-center justify-center text-gray-700 hover:text-green-600 transition font-medium">
                            <i class="bi bi-telephone-fill mr-2"></i>
                            <span>+27 12 345 6789</span>
                        </a>
                        <a href="mailto:president@umojaangola.org" class="flex items-center justify-center text-gray-700 hover:text-green-600 transition font-medium">
                            <i class="bi bi-envelope-fill mr-2"></i>
                            <span class="text-sm">president@umojaangola.org</span>
                        </a>
                        <a href="https://wa.me/27123456789" target="_blank" class="flex items-center justify-center text-gray-700 hover:text-green-600 transition font-medium">
                            <i class="bi bi-whatsapp mr-2"></i>
                            <span>{{ __('WhatsApp') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Leader 2 -->
            <div class="leader-card animate-on-scroll">
                <div class="leader-card-inner text-center">
                    <div class="leader-avatar bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-5xl font-bold">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">{{ __('Vice President') }}</h3>
                    <p class="text-red-600 font-bold mb-4 text-lg">{{ __('Name Surname') }}</p>
                    <p class="text-gray-600 mb-6 min-h-[60px]">
                        {{ __('Supporting community initiatives and coordinating member engagement activities.') }}
                    </p>
                    <div class="space-y-3">
                        <a href="tel:+27123456790" class="flex items-center justify-center text-gray-700 hover:text-red-600 transition font-medium">
                            <i class="bi bi-telephone-fill mr-2"></i>
                            <span>+27 12 345 6790</span>
                        </a>
                        <a href="mailto:vicepresident@umojaangola.org" class="flex items-center justify-center text-gray-700 hover:text-red-600 transition font-medium">
                            <i class="bi bi-envelope-fill mr-2"></i>
                            <span class="text-sm">vicepresident@umojaangola.org</span>
                        </a>
                        <a href="https://wa.me/27123456790" target="_blank" class="flex items-center justify-center text-gray-700 hover:text-red-600 transition font-medium">
                            <i class="bi bi-whatsapp mr-2"></i>
                            <span>{{ __('WhatsApp') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Leader 3 -->
            <div class="leader-card animate-on-scroll">
                <div class="leader-card-inner text-center">
                    <div class="leader-avatar bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white text-5xl font-bold">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-2">{{ __('Secretary General') }}</h3>
                    <p class="text-yellow-600 font-bold mb-4 text-lg">{{ __('Name Surname') }}</p>
                    <p class="text-gray-600 mb-6 min-h-[60px]">
                        {{ __('Managing communications, documentation, and administrative operations.') }}
                    </p>
                    <div class="space-y-3">
                        <a href="tel:+27123456791" class="flex items-center justify-center text-gray-700 hover:text-yellow-600 transition font-medium">
                            <i class="bi bi-telephone-fill mr-2"></i>
                            <span>+27 12 345 6791</span>
                        </a>
                        <a href="mailto:secretary@umojaangola.org" class="flex items-center justify-center text-gray-700 hover:text-yellow-600 transition font-medium">
                            <i class="bi bi-envelope-fill mr-2"></i>
                            <span class="text-sm">secretary@umojaangola.org</span>
                        </a>
                        <a href="https://wa.me/27123456791" target="_blank" class="flex items-center justify-center text-gray-700 hover:text-yellow-600 transition font-medium">
                            <i class="bi bi-whatsapp mr-2"></i>
                            <span>{{ __('WhatsApp') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- General Contact -->
        <div class="mt-16 max-w-4xl mx-auto animate-on-scroll">
            <div class="bg-gradient-to-br from-green-50 to-red-50 rounded-3xl p-8 border-2 border-green-200">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 mb-3">{{ __('General Contact Information') }}</h3>
                    <p class="text-gray-600 text-lg">{{ __('Reach out to us for any inquiries or support') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="contact-card">
                        <div class="contact-icon bg-gradient-to-br from-green-500 to-green-700 text-white">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <h4 class="font-black text-gray-900 mb-2">{{ __('Email') }}</h4>
                        <a href="mailto:info@umojaangola.org" class="text-green-600 hover:text-green-800 transition font-semibold">
                            info@umojaangola.org
                        </a>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon bg-gradient-to-br from-red-500 to-red-700 text-white">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <h4 class="font-black text-gray-900 mb-2">{{ __('Phone') }}</h4>
                        <a href="tel:+27123456789" class="text-red-600 hover:text-red-800 transition font-semibold">
                            +27 12 345 6789
                        </a>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon bg-gradient-to-br from-green-500 to-green-600 text-white">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <h4 class="font-black text-gray-900 mb-2">{{ __('WhatsApp') }}</h4>
                        <a href="https://wa.me/27123456789" target="_blank" class="text-green-600 hover:text-green-800 transition font-semibold">
                            {{ __('Send Message') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section bg-gradient-to-b from-white to-gray-50" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: linear-gradient(to bottom, #ffffff, #f9fafb);">
    <div class="container-wide px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="badge-accent mb-4 inline-block">{{ __('Community Voices') }}</span>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ __('What Members Say') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="testimonial-card animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center text-white text-2xl mr-4">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-gray-900">{{ __('Maria Silva') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Johannesburg') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic mb-4">
                    "{{ __('This platform helped me find housing when I first arrived in South Africa. The community support was incredible.') }}"
                </p>
                <div class="flex text-yellow-400">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
            
            <div class="testimonial-card animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white text-2xl mr-4">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-gray-900">{{ __('José Fernandes') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Cape Town') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic mb-4">
                    "{{ __('Through the community network, I found a job in my field within two weeks. The job referral system works!') }}"
                </p>
                <div class="flex text-yellow-400">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </div>
            </div>
            
            <div class="testimonial-card animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white text-2xl mr-4">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-gray-900">{{ __('Ana Costa') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Durban') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic mb-4">
                    "{{ __('The legal guidance I received through this platform saved me months of stress. Thank you to our community lawyers!') }}"
                </p>
                <div class="flex text-yellow-400">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="cta-section section relative" style="display: block; visibility: visible; opacity: 1; padding: 4rem 0; background: linear-gradient(135deg, #008751 0%, #006b42 100%); position: relative;">
    <div class="container-wide px-4 sm:px-6 lg:px-8 relative z-10" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem; position: relative; z-index: 10;">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6 animate-on-scroll">
                {{ __('Ready to Join UMOJA Angola?') }}
            </h2>
            <p class="text-xl md:text-2xl text-white/95 mb-10 max-w-2xl mx-auto animate-on-scroll">
                {{ __('Join UMOJA Angola today and become part of a growing, united Angolan community network in South Africa. Connect, share, and grow together.') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-on-scroll">
                <a href="{{ route('registration') }}" class="btn-modern inline-flex items-center justify-center">
                    <span>
                        <i class="bi bi-person-plus-fill mr-2"></i>
                        {{ __('Register Now - It\'s Free!') }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
        
        // Stats counter animation
        const statNumbers = document.querySelectorAll('.stat-card .text-4xl');
        statNumbers.forEach(stat => {
            const text = stat.textContent;
            const number = parseInt(text.replace(/\D/g, ''));
            if (number) {
                let current = 0;
                const increment = number / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= number) {
                        stat.textContent = text;
                        clearInterval(timer);
                    } else {
                        const suffix = text.includes('+') ? '+' : '';
                        stat.textContent = Math.floor(current) + suffix;
                    }
                }, 30);
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const headerOffset = 100;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endsection
