@extends('layouts.admin')

@section('title', $member->first_name . ' ' . $member->last_name)
@section('header', __('Member Details'))

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color), #006b42);
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center / cover no-repeat;
    }
    
    .info-card {
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .timeline-item {
        position: relative;
        padding-left: 40px;
        padding-bottom: 30px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 30px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item:last-child::before {
        display: none;
    }
    
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 8px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .skill-progress {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .skill-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), #006b42);
        transition: width 0.3s ease;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .profile-header {
            background: var(--primary-color) !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('admin.members.index') }}" class="flex items-center text-gray-600 hover:text-primary">
            <i class="bi bi-arrow-left mr-2"></i>
            {{ __('Back to Members') }}
        </a>
        
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                <i class="bi bi-printer mr-2"></i>
                {{ __('Print') }}
            </button>
            
            <button onclick="exportMember()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                <i class="bi bi-download mr-2"></i>
                {{ __('Export') }}
            </button>
            
            <button onclick="editMember()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                <i class="bi bi-pencil mr-2"></i>
                {{ __('Edit') }}
            </button>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="profile-header text-white rounded-lg p-8 relative">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-4xl font-bold border-4 border-white/30">
                    {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                </div>
                
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $member->first_name }} {{ $member->last_name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/90">
                        <span class="flex items-center">
                            <i class="bi bi-card-text mr-2"></i>
                            {{ $member->registration_number }}
                        </span>
                        <span class="flex items-center">
                            <i class="bi bi-calendar mr-2"></i>
                            {{ __('Joined') }} {{ $member->created_at->format('M d, Y') }}
                        </span>
                        @if($member->willing_to_help)
                        <span class="flex items-center bg-white/20 px-3 py-1 rounded-full">
                            <i class="bi bi-heart-fill mr-2"></i>
                            {{ __('Community Volunteer') }}
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-3 no-print">
                    <button onclick="sendMessage()" class="px-6 py-3 bg-white text-primary rounded-lg hover:bg-gray-100 flex items-center font-medium">
                        <i class="bi bi-envelope mr-2"></i>
                        {{ __('Send Message') }}
                    </button>
                    
                    <button onclick="callMember()" class="px-6 py-3 bg-white/10 backdrop-blur-sm border-2 border-white/30 rounded-lg hover:bg-white/20 flex items-center">
                        <i class="bi bi-telephone mr-2"></i>
                        {{ __('Call') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="info-card card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('Skills') }}</p>
                    <h3 class="text-2xl font-bold text-primary">{{ $member->skills->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-primary-50 text-primary rounded-lg flex items-center justify-center">
                    <i class="bi bi-tools text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="info-card card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('Support Areas') }}</p>
                    <h3 class="text-2xl font-bold text-secondary">{{ $member->supportAreas->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-secondary-50 text-secondary rounded-lg flex items-center justify-center">
                    <i class="bi bi-heart text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="info-card card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('Age') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $member->age ?? __('N/A') }}
                        @if($member->age)
                        <span class="text-sm text-gray-500">{{ __('years') }}</span>
                        @endif
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar-event text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="info-card card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">{{ __('Profile Completeness') }}</p>
                    <h3 class="text-2xl font-bold text-green-600">95%</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-person-circle text-primary mr-3"></i>
                    {{ __('Personal Information') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-600">{{ __('First Name') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->first_name }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Last Name') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->last_name }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Gender') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ ucfirst($member->gender) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Date of Birth') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            @if($member->date_of_birth)
                                {{ $member->date_of_birth->format('d M Y') }}
                                <span class="text-sm text-gray-500">({{ $member->age }} {{ __('years') }})</span>
                            @else
                                <span class="text-gray-400">{{ __('Not provided') }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Nationality') }}</label>
                        <p class="font-medium text-gray-900 mt-1 flex items-center">
                            <span class="mr-2">🇦🇴</span>
                            {{ $member->nationality }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Citizenship Status') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ ucfirst(str_replace('_', ' ', $member->citizenship_status)) }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Language Preference') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ strtoupper($member->language_preference) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-geo-alt text-primary mr-3"></i>
                    {{ __('Location Information') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Province') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->province }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm text-gray-600">{{ __('City') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->city }}</p>
                    </div>
                    
                    @if($member->area)
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">{{ __('Area / Suburb') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->area }}</p>
                    </div>
                    @endif
                </div>
                
                <!-- Map placeholder -->
                <div class="mt-6 bg-gray-100 rounded-lg h-48 flex items-center justify-center no-print">
                    <div class="text-center">
                        <i class="bi bi-map text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">{{ __('Map View') }}</p>
                        <p class="text-sm text-gray-500">{{ $member->city }}, {{ $member->province }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-telephone text-primary mr-3"></i>
                    {{ __('Contact Information') }}
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-50 text-primary rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Mobile Number') }}</p>
                                <p class="font-medium text-gray-900">{{ $member->mobile_number }}</p>
                            </div>
                        </div>
                        <button onclick="callMember()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark no-print">
                            <i class="bi bi-telephone"></i>
                        </button>
                    </div>
                    
                    @if($member->email)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Email Address') }}</p>
                                <p class="font-medium text-gray-900">{{ $member->email }}</p>
                            </div>
                        </div>
                        <a href="mailto:{{ $member->email }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 no-print">
                            <i class="bi bi-envelope"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if($member->whatsapp_number)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('WhatsApp Number') }}</p>
                                <p class="font-medium text-gray-900">{{ $member->whatsapp_number }}</p>
                            </div>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->whatsapp_number) }}" 
                           target="_blank"
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 no-print">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                    @endif
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-900">{{ __('Preferred Contact Method') }}</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    {{ ucfirst($member->preferred_contact_method) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-briefcase text-primary mr-3"></i>
                    {{ __('Professional Information') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Employment Status') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            <span class="badge badge-primary">
                                {{ ucfirst(str_replace('_', ' ', $member->employment_status)) }}
                            </span>
                        </p>
                    </div>
                    
                    @if($member->profession)
                    <div>
                        <label class="text-sm text-gray-600">{{ __('Profession') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->profession }}</p>
                    </div>
                    @endif
                    
                    @if($member->field_of_study)
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">{{ __('Field of Study') }}</label>
                        <p class="font-medium text-gray-900 mt-1">{{ $member->field_of_study }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Skills -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-tools text-primary mr-3"></i>
                    {{ __('Skills & Expertise') }}
                </h2>
                
                @if($member->skills->count() > 0)
                <div class="space-y-6">
                    @foreach($member->skills->groupBy('category') as $category => $skills)
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3">{{ __($category) }}</h3>
                        <div class="space-y-4">
                            @foreach($skills as $skill)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-gray-900">{{ $skill->name }}</span>
                                    <div class="flex items-center gap-3">
                                        @if($skill->pivot->years_experience)
                                        <span class="text-sm text-gray-600">
                                            {{ $skill->pivot->years_experience }} {{ __('years') }}
                                        </span>
                                        @endif
                                        <span class="badge badge-{{ $skill->pivot->experience_level == 'expert' ? 'success' : ($skill->pivot->experience_level == 'intermediate' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($skill->pivot->experience_level) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="skill-progress">
                                    <div class="skill-progress-fill" 
                                         style="width: {{ $skill->pivot->experience_level == 'expert' ? '100%' : ($skill->pivot->experience_level == 'intermediate' ? '66%' : '33%') }}">
                                    </div>
                                </div>
                                @if($skill->pivot->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $skill->pivot->description }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="bi bi-tools text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-600">{{ __('No skills added yet') }}</p>
                </div>
                @endif
            </div>

            <!-- Community Support -->
            <div class="card p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="bi bi-heart text-primary mr-3"></i>
                    {{ __('Community Support') }}
                </h2>
                
                @if($member->supportAreas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($member->supportAreas as $area)
                    <div class="p-4 border border-gray-200 rounded-lg hover:border-primary transition-colors">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-primary-50 text-primary rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="bi {{ $area->icon ?? 'bi-check-circle' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $area->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $area->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="bi bi-heart text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-600">{{ __('No support areas selected') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card p-6 no-print">
                <h3 class="font-bold text-gray-900 mb-4">{{ __('Quick Actions') }}</h3>
                <div class="space-y-3">
                    <button onclick="sendMessage()" class="w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center justify-center">
                        <i class="bi bi-envelope mr-2"></i>
                        {{ __('Send Message') }}
                    </button>
                    
                    <button onclick="addToGroup()" class="w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                        <i class="bi bi-people mr-2"></i>
                        {{ __('Add to Group') }}
                    </button>
                    
                    <button onclick="scheduleAppointment()" class="w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                        <i class="bi bi-calendar-event mr-2"></i>
                        {{ __('Schedule Meeting') }}
                    </button>
                    
                    <button onclick="addNote()" class="w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                        <i class="bi bi-journal-plus mr-2"></i>
                        {{ __('Add Note') }}
                    </button>
                </div>
            </div>

            <!-- Consent Information -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <i class="bi bi-shield-check text-green-600 mr-2"></i>
                    {{ __('Data Consent') }}
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="bi bi-check-circle-fill text-green-600 mr-2 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('Consent Given') }}</p>
                            <p class="text-xs text-gray-600">
                                {{ $member->consent_given_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-xs text-green-800">{{ $member->consent_text }}</p>
                    </div>
                </div>
            </div>

            <!-- Registration Info -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">{{ __('Registration Details') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Registration Number') }}</label>
                        <p class="font-mono font-medium text-gray-900 mt-1">{{ $member->registration_number }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Registered At') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $member->registered_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    
                    @if($member->last_updated_at)
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Last Updated') }}</label>
                        <p class="font-medium text-gray-900 mt-1">
                            {{ $member->last_updated_at->diffForHumans() }}
                        </p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-xs text-gray-600">{{ __('Registration IP') }}</label>
                        <p class="font-mono text-sm text-gray-900 mt-1">{{ $member->ip_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">{{ __('Recent Activity') }}</h3>
                
                <div class="space-y-4">
                    <div class="timeline-item">
                        <div class="timeline-dot bg-green-100 text-green-600">
                            <i class="bi bi-person-plus text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-gray-900">{{ __('Registered') }}</p>
                            <p class="text-xs text-gray-600">{{ $member->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @if($member->updated_at != $member->created_at)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-blue-100 text-blue-600">
                            <i class="bi bi-pencil text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-gray-900">{{ __('Profile Updated') }}</p>
                            <p class="text-xs text-gray-600">{{ $member->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Placeholder for future activities -->
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">{{ __('End of timeline') }}</p>
                    </div>
                </div>
            </div>

            <!-- Tags & Categories -->
            <div class="card p-6 no-print">
                <h3 class="font-bold text-gray-900 mb-4">{{ __('Tags & Labels') }}</h3>
                
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($member->willing_to_help)
                    <span class="badge badge-success">{{ __('Volunteer') }}</span>
                    @endif
                    
                    <span class="badge badge-primary">{{ $member->province }}</span>
                    
                    @if($member->skills->count() > 5)
                    <span class="badge badge-warning">{{ __('Highly Skilled') }}</span>
                    @endif
                    
                    @if($member->created_at->gt(now()->subDays(7)))
                    <span class="badge badge-accent">{{ __('New Member') }}</span>
                    @endif
                </div>
                
                <button onclick="manageTags()" class="text-sm text-primary hover:underline flex items-center">
                    <i class="bi bi-plus-circle mr-1"></i>
                    {{ __('Add Tag') }}
                </button>
            </div>

            <!-- Danger Zone -->
            <div class="card p-6 border-red-200 no-print">
                <h3 class="font-bold text-red-600 mb-4 flex items-center">
                    <i class="bi bi-exclamation-triangle mr-2"></i>
                    {{ __('Danger Zone') }}
                </h3>
                
                <div class="space-y-3">
                    <button onclick="suspendMember()" class="w-full px-4 py-2 border border-yellow-300 text-yellow-700 rounded-lg hover:bg-yellow-50 text-sm flex items-center justify-center">
                        <i class="bi bi-pause-circle mr-2"></i>
                        {{ __('Suspend Account') }}
                    </button>
                    
                    <button onclick="deleteMember()" class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 text-sm flex items-center justify-center">
                        <i class="bi bi-trash mr-2"></i>
                        {{ __('Delete Member') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sendMessage() {
    Swal.fire({
        title: '{{ __("Send Message to") }} {{ $member->first_name }}',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __("Message Type") }}</label>
                    <select id="message-type" class="w-full px-3 py-2 border rounded-lg">
                        <option value="email">{{ __("Email") }}</option>
                        <option value="sms">{{ __("SMS") }}</option>
                        <option value="whatsapp">{{ __("WhatsApp") }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __("Message") }}</label>
                    <textarea id="message-text" rows="5" class="w-full px-3 py-2 border rounded-lg" placeholder="{{ __('Type your message here...') }}"></textarea>
                </div>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '{{ __("Send") }}',
        confirmButtonColor: '#008751',
        preConfirm: () => {
            return {
                type: document.getElementById('message-type').value,
                message: document.getElementById('message-text').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.message) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Message Sent") }}',
                confirmButtonColor: '#008751'
            });
        }
    });
}

function callMember() {
    window.location.href = 'tel:{{ $member->mobile_number }}';
}

function editMember() {
    window.location.href = '{{ route("admin.members.show", $member->id) }}/edit';
}

function addToGroup() {
    Swal.fire({
        title: '{{ __("Add to Group") }}',
        input: 'select',
        inputOptions: {
            'volunteers': '{{ __("Volunteers") }}',
            'professionals': '{{ __("Professionals") }}',
            'gauteng': '{{ __("Gauteng Members") }}',
            'cape_town': '{{ __("Cape Town Members") }}'
        },
        showCancelButton: true,
        confirmButtonText: '{{ __("Add") }}',
        confirmButtonColor: '#008751'
    });
}

function scheduleAppointment() {
    Swal.fire({
        title: '{{ __("Schedule Meeting") }}',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __("Meeting Type") }}</label>
                    <select class="w-full px-3 py-2 border rounded-lg">
                        <option>{{ __("Phone Call") }}</option>
                        <option>{{ __("Video Call") }}</option>
                        <option>{{ __("In-Person Meeting") }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __("Date & Time") }}</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __("Notes") }}</label>
                    <textarea rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '{{ __("Schedule") }}',
        confirmButtonColor: '#008751'
    });
}

function addNote() {
    Swal.fire({
        title: '{{ __("Add Note") }}',
        input: 'textarea',
        inputPlaceholder: '{{ __("Type your note here...") }}',
        showCancelButton: true,
        confirmButtonText: '{{ __("Save") }}',
        confirmButtonColor: '#008751'
    });
}

function manageTags() {
    Swal.fire({
        title: '{{ __("Manage Tags") }}',
        html: `
            <div class="text-left">
                <input type="text" id="new-tag" class="w-full px-3 py-2 border rounded-lg mb-3" placeholder="{{ __('Enter tag name') }}">
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-primary cursor-pointer">{{ __("Active") }}</span>
                    <span class="badge badge-success cursor-pointer">{{ __("Verified") }}</span>
                    <span class="badge badge-warning cursor-pointer">{{ __("Follow-up") }}</span>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __("Add Tag") }}',
        confirmButtonColor: '#008751'
    });
}

function exportMember() {
    Swal.fire({
        title: '{{ __("Export Member Data") }}',
        text: '{{ __("Choose export format") }}',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'PDF',
        denyButtonText: 'Excel',
        cancelButtonText: 'JSON'
    });
}

function suspendMember() {
    Swal.fire({
        title: '{{ __("Suspend Member Account") }}',
        text: '{{ __("This will temporarily disable the member\'s access") }}',
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: '{{ __("Reason for suspension") }}',
        showCancelButton: true,
        confirmButtonText: '{{ __("Suspend") }}',
        confirmButtonColor: '#f59e0b'
    });
}

function deleteMember() {
    Swal.fire({
        title: '{{ __("Delete Member") }}',
        text: '{{ __("This action cannot be undone. All member data will be permanently deleted.") }}',
        icon: 'error',
        input: 'checkbox',
        inputPlaceholder: '{{ __("I understand this action is permanent") }}',
        showCancelButton: true,
        confirmButtonText: '{{ __("Delete Permanently") }}',
        confirmButtonColor: '#dc2626',
        preConfirm: (checked) => {
            if (!checked) {
                Swal.showValidationMessage('{{ __("You must confirm to proceed") }}');
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Delete member
            window.location.href = '{{ route("admin.members.destroy", $member->id) }}';
        }
    });
}
</script>
@endpush