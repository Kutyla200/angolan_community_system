@extends('layouts.app')


@section('title', __('Welcome to Angolan Community Portal'))

@push('styles')
<style>
    /* Hero Section Styles */
    .hero-section {
        background: linear-gradient(135deg, 
            rgba(0, 135, 81, 0.05) 0%, 
            rgba(204, 9, 47, 0.03) 50%, 
            rgba(255, 209, 0, 0.02) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background-image: 
            radial-gradient(circle at 20% 80%, rgba(0, 135, 81, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(204, 9, 47, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 209, 0, 0.05) 0%, transparent 50%);
        animation: blob 20s infinite alternate;
        z-index: 0;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    /* Feature Card Hover Effects */
    .feature-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #00875122, #CC092F11, #FFD10011) border-box;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border-color: transparent;
    }
    
    /* Stats Counter Animation */
    .stat-number {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Step Animation */
    .step-icon {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .step-icon::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120%;
        height: 120%;
        border-radius: 50%;
        background: rgba(0, 135, 81, 0.1);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .step:hover .step-icon::after {
        opacity: 1;
    }
    
    /* CTA Button Animation */
    .cta-button {
        position: relative;
        overflow: hidden;
    }
    
    .cta-button::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }
    
    .cta-button:hover::after {
        animation: ripple 1s ease-out;
    }
    
    /* Wave Animation */
    .wave {
        animation: wave 2s ease-in-out infinite;
    }
    
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(50px, -30px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(50, 50);
            opacity: 0;
        }
    }
    
    @keyframes wave {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-15px);
        }
    }
    
    /* Mobile Optimizations */
    @media (max-width: 640px) {
        .hero-section {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        
        .feature-card {
            margin-bottom: 20px;
        }
        
        .step {
            margin-bottom: 30px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
        }
    }
    
    /* Tablet Optimizations */
    @media (max-width: 1024px) {
        .hero-section::before {
            animation: blob 30s infinite alternate;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section py-16 md:py-24">
    <div class="container-wide px-4 sm:px-6 lg:px-8">
        <div class="hero-content text-center max-w-5xl mx-auto">
            <!-- Logo and Title -->
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <div class="w-28 h-28 gradient-primary rounded-full flex items-center justify-center wave">
                        <i class="bi bi-people-fill text-white text-5xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-12 h-12 bg-secondary rounded-full flex items-center justify-center animate-pulse">
                        <i class="bi bi-flag-fill text-white text-xl"></i>
                    </div>
                    <div class="absolute -bottom-2 -left-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center animate-pulse-slow">
                        <i class="bi bi-heart-fill text-dark text-lg"></i>
                    </div>
                </div>
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <span class="text-gradient-primary">{{ __('Angolan Community') }}</span>
                <span class="block text-gray-800">{{ __('in South Africa') }}</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                {{ __('Connecting Angolans through support, skills sharing, and opportunities. Building a stronger community together.') }}
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 animate-fade-in">
                <a href="{{ route('registration') }}" 
                   class="cta-button btn-primary px-8 py-4 text-lg flex items-center justify-center group">
                    <i class="bi bi-person-plus mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                    {{ __('Register Now') }}
                    <i class="bi bi-arrow-right ml-3 text-xl group-hover:translate-x-2 transition-transform"></i>
                </a>
                
                <a href="#features" 
                   class="btn-outline px-8 py-4 text-lg flex items-center justify-center group">
                    <i class="bi bi-play-circle mr-3 text-xl"></i>
                    {{ __('How It Works') }}
                </a>
            </div>
            
            <!-- Stats Preview -->
            <div class="stats-grid grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mb-8">
                <div class="card p-6 text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="stat-number text-3xl font-bold mb-2">500+</div>
                    <div class="text-gray-600 font-medium">{{ __('Community Members') }}</div>
                </div>
                <div class="card p-6 text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="stat-number text-3xl font-bold mb-2">9</div>
                    <div class="text-gray-600 font-medium">{{ __('Provinces Covered') }}</div>
                </div>
                <div class="card p-6 text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="stat-number text-3xl font-bold mb-2">50+</div>
                    <div class="text-gray-600 font-medium">{{ __('Skills Categories') }}</div>
                </div>
                <div class="card p-6 text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="stat-number text-3xl font-bold mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">{{ __('Community Support') }}</div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <i class="bi bi-shield-check text-primary mr-2"></i>
                    {{ __('Secure & Private') }}
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="bi bi-lock text-primary mr-2"></i>
                    {{ __('POPIA Compliant') }}
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="bi bi-translate text-primary mr-2"></i>
                    {{ __('English & Portuguese') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section bg-white">
    <div class="container-wide px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="badge-primary mb-4">{{ __('Why Join Us?') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Empowering Our Community') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('Join a trusted network of Angolans supporting each other in South Africa.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="feature-card p-8 card-hover">
                <div class="w-16 h-16 gradient-primary rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <i class="bi bi-shield-check text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">{{ __('Secure & Private') }}</h3>
                <p class="text-gray-600 text-center">
                    {{ __('Your data is protected with enterprise-grade security, encryption, and strict privacy controls. We are POPIA compliant.') }}
                </p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-primary mr-2"></i>
                        {{ __('End-to-end encryption') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-primary mr-2"></i>
                        {{ __('Regular security audits') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-primary mr-2"></i>
                        {{ __('Data ownership retained') }}
                    </li>
                </ul>
            </div>
            
            <!-- Feature 2 -->
            <div class="feature-card p-8 card-hover">
                <div class="w-16 h-16 bg-secondary-600 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <i class="bi bi-people-fill text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">{{ __('Community Network') }}</h3>
                <p class="text-gray-600 text-center">
                    {{ __('Connect with fellow Angolans for support, opportunities, and meaningful collaborations across South Africa.') }}
                </p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-secondary mr-2"></i>
                        {{ __('Verified community members') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-secondary mr-2"></i>
                        {{ __('Local meetups & events') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-secondary mr-2"></i>
                        {{ __('Cultural exchange programs') }}
                    </li>
                </ul>
            </div>
            
            <!-- Feature 3 -->
            <div class="feature-card p-8 card-hover">
                <div class="w-16 h-16 bg-accent-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <i class="bi bi-briefcase-fill text-dark-900 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-center mb-4">{{ __('Opportunities') }}</h3>
                <p class="text-gray-600 text-center">
                    {{ __('Access exclusive job referrals, business opportunities, professional networking, and skill development programs.') }}
                </p>
                <ul class="mt-4 space-y-2">
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-accent mr-2"></i>
                        {{ __('Job matching system') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-accent mr-2"></i>
                        {{ __('Business partnerships') }}
                    </li>
                    <li class="flex items-center text-sm text-gray-600">
                        <i class="bi bi-check-circle text-accent mr-2"></i>
                        {{ __('Skill certification') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section bg-gradient-to-b from-gray-50 to-white">
    <div class="container-wide px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="badge-secondary mb-4">{{ __('Simple Process') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('How It Works') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('Join our community network in four simple steps.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="step text-center">
                <div class="relative mb-6">
                    <div class="step-icon w-24 h-24 gradient-primary rounded-full flex items-center justify-center mx-auto text-white text-3xl font-bold shadow-lg">
                        1
                    </div>
                    <div class="absolute -top-2 -right-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center animate-pulse">
                        <i class="bi bi-pencil-fill text-dark text-sm"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-3">{{ __('Register') }}</h3>
                <p class="text-gray-600">
                    {{ __('Fill out our secure registration form with your basic information. Takes less than 5 minutes.') }}
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="step text-center">
                <div class="relative mb-6">
                    <div class="step-icon w-24 h-24 gradient-primary rounded-full flex items-center justify-center mx-auto text-white text-3xl font-bold shadow-lg">
                        2
                    </div>
                    <div class="absolute -top-2 -right-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center animate-pulse">
                        <i class="bi bi-tools text-dark text-sm"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-3">{{ __('Share Skills') }}</h3>
                <p class="text-gray-600">
                    {{ __('Tell us about your skills, experience, and how you can help other community members.') }}
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="step text-center">
                <div class="relative mb-6">
                    <div class="step-icon w-24 h-24 gradient-primary rounded-full flex items-center justify-center mx-auto text-white text-3xl font-bold shadow-lg">
                        3
                    </div>
                    <div class="absolute -top-2 -right-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center animate-pulse">
                        <i class="bi bi-shield-check text-dark text-sm"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-3">{{ __('Get Verified') }}</h3>
                <p class="text-gray-600">
                    {{ __('Our community team verifies your registration to ensure safety and authenticity.') }}
                </p>
            </div>
            
            <!-- Step 4 -->
            <div class="step text-center">
                <div class="relative mb-6">
                    <div class="step-icon w-24 h-24 gradient-primary rounded-full flex items-center justify-center mx-auto text-white text-3xl font-bold shadow-lg">
                        4
                    </div>
                    <div class="absolute -top-2 -right-2 w-10 h-10 bg-accent rounded-full flex items-center justify-center animate-pulse">
                        <i class="bi bi-people-fill text-dark text-sm"></i>
                    </div>
                </div>
                <h3 class="text-lg font-bold mb-3">{{ __('Connect') }}</h3>
                <p class="text-gray-600">
                    {{ __('Start connecting with community members, opportunities, and support networks immediately.') }}
                </p>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div class="max-w-4xl mx-auto mt-12">
            <div class="relative">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%"></div>
                </div>
                <div class="absolute inset-0 flex justify-between items-center">
                    <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                    <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                    <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                    <div class="w-4 h-4 bg-primary-600 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section bg-white">
    <div class="container-narrow px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="badge-accent mb-4">{{ __('Community Voices') }}</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('What Members Say') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="card p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-person-circle text-primary-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold">{{ __('Maria Silva') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Johannesburg') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "{{ __('This platform helped me find housing when I first arrived in South Africa. The community support was incredible.') }}"
                </p>
                <div class="flex text-accent-500 mt-3">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="card p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-secondary-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-person-circle text-secondary-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold">{{ __('José Fernandes') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Cape Town') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "{{ __('Through the community network, I found a job in my field within two weeks. The job referral system works!') }}"
                </p>
                <div class="flex text-accent-500 mt-3">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="card p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-accent-100 rounded-full flex items-center justify-center">
                        <i class="bi bi-person-circle text-accent-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold">{{ __('Ana Costa') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Durban') }}</p>
                    </div>
                </div>
                <p class="text-gray-700 italic">
                    "{{ __('The legal guidance I received through this platform saved me months of stress. Thank you to our community lawyers!') }}"
                </p>
                <div class="flex text-accent-500 mt-3">
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

<!-- CTA Section -->
<section class="section gradient-primary text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 right-0 bottom-0 pattern-angular"></div>
    </div>
    
    <div class="container-wide px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-people text-white text-3xl"></i>
            </div>
            
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                {{ __('Ready to Join Our Community?') }}
            </h2>
            
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                {{ __('Register today and become part of the growing Angolan community network in South Africa. Connect, share, and grow together.') }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('registration') }}" 
                   class="cta-button bg-white text-primary-600 hover:bg-gray-100 px-10 py-4 rounded-lg text-lg font-bold flex items-center justify-center group">
                    <i class="bi bi-person-plus mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                    {{ __('Register Now - It\'s Free!') }}
                    <i class="bi bi-arrow-right ml-3 text-xl group-hover:translate-x-2 transition-transform"></i>
                </a>
                
                <a href="#features" 
                   class="px-10 py-4 border-2 border-white/30 hover:border-white text-white rounded-lg text-lg font-bold flex items-center justify-center group backdrop-blur-sm">
                    <i class="bi bi-play-circle mr-3 text-xl"></i>
                    {{ __('Watch Tutorial') }}
                </a>
            </div>
            
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-2xl font-bold">✓</div>
                    <p class="text-sm text-white/80">{{ __('No Fees') }}</p>
                </div>
                <div>
                    <div class="text-2xl font-bold">✓</div>
                    <p class="text-sm text-white/80">{{ __('Secure Data') }}</p>
                </div>
                <div>
                    <div class="text-2xl font-bold">✓</div>
                    <p class="text-sm text-white/80">{{ __('24/7 Support') }}</p>
                </div>
                <div>
                    <div class="text-2xl font-bold">✓</div>
                    <p class="text-sm text-white/80">{{ __('Bilingual') }}</p>
                </div>
            </div>
            
            <p class="mt-10 text-white/70 text-sm flex items-center justify-center">
                <i class="bi bi-shield-lock mr-2"></i>
                {{ __('Your information is secure, private, and never shared without consent.') }}
            </p>
        </div>
    </div>
</section>

<!-- Footer Stats -->
<section class="py-8 bg-dark-900 text-white">
    <div class="container-wide px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl font-bold text-primary-300">🇦🇴</div>
                <p class="text-sm text-gray-300">{{ __('Proudly Angolan') }}</p>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary-300">🤝</div>
                <p class="text-sm text-gray-300">{{ __('Community First') }}</p>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary-300">🔒</div>
                <p class="text-sm text-gray-300">{{ __('Privacy Focused') }}</p>
            </div>
            <div>
                <div class="text-2xl font-bold text-primary-300">🌍</div>
                <p class="text-sm text-gray-300">{{ __('South Africa Wide') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Animate stats counter
    document.addEventListener('DOMContentLoaded', function() {
        // Stats counter animation
        const statElements = document.querySelectorAll('.stat-number');
        
        statElements.forEach(stat => {
            const target = parseInt(stat.textContent);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(current) + (stat.textContent.includes('+') ? '+' : '');
            }, 50);
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);
        
        // Observe all feature cards and steps
        document.querySelectorAll('.feature-card, .step').forEach(el => {
            observer.observe(el);
        });
        
        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });
    });
</script>
@endsection