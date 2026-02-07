@extends('layouts.app')

@section('title', __('Registration Form'))

@push('styles')
<style>
    /* Enhanced Registration Page Styles */
    .registration-hero {
        background: linear-gradient(135deg, rgba(0, 135, 81, 0.05) 0%, rgba(204, 9, 47, 0.03) 50%, rgba(255, 209, 0, 0.02) 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
    }
    
    .step {
        display: none;
    }
    
    .step.active {
        display: block;
        animation: fadeInSlide 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes fadeInSlide {
        from { 
            opacity: 0; 
            transform: translateY(20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }
    
    .progress-bar {
        height: 10px;
        background: linear-gradient(90deg, #e5e7eb, #f3f4f6);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), #006b42, var(--secondary-color));
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 135, 81, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .step-indicator {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #e5e7eb;
        color: #6b7280;
        border: 3px solid transparent;
        position: relative;
    }
    
    .step-indicator.active {
        background: linear-gradient(135deg, var(--primary-color), #006b42);
        color: white;
        box-shadow: 0 8px 20px rgba(0, 135, 81, 0.3);
        transform: scale(1.1);
        border-color: var(--accent-color);
    }
    
    .step-indicator.completed {
        background: linear-gradient(135deg, var(--accent-color), #ffd700);
        color: var(--dark-color);
        box-shadow: 0 4px 12px rgba(255, 209, 0, 0.3);
    }
    
    .step-indicator.completed::after {
        content: '✓';
        position: absolute;
        font-size: 24px;
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .form-card:hover {
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 12px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }
    
    .skill-chip {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        margin: 6px;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-radius: 25px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        border: 2px solid transparent;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .skill-chip::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }
    
    .skill-chip:hover::before {
        left: 100%;
    }
    
    .skill-chip.selected {
        background: linear-gradient(135deg, rgba(0, 135, 81, 0.1), rgba(0, 135, 81, 0.15));
        border-color: var(--primary-color);
        color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 135, 81, 0.2);
        transform: translateY(-2px);
    }
    
    .skill-chip:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    /* Enhanced Input Styles */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    input[type="number"],
    select {
        transition: all 0.3s ease;
    }
    
    input:focus,
    select:focus {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 135, 81, 0.15) !important;
    }
    
    /* Button Enhancements */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #006b42);
        box-shadow: 0 6px 20px rgba(0, 135, 81, 0.3);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #006b42, var(--primary-color));
        box-shadow: 0 10px 30px rgba(0, 135, 81, 0.4);
        transform: translateY(-2px) scale(1.02);
    }
    
    .btn-primary:active {
        transform: translateY(0) scale(0.98);
    }
    
    @media (max-width: 640px) {
        .form-card {
            margin: 0 -16px;
            border-radius: 16px 16px 0 0;
        }
        
        .step-indicator {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .registration-hero {
            padding: 2rem 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Registration Hero -->
<div class="registration-hero">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center shadow-xl">
                <i class="bi bi-person-plus-fill text-white text-3xl"></i>
            </div>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
            {{ __('Join UMOJA Angola') }}
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            {{ __('Become part of our growing community. Registration takes less than 5 minutes.') }}
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            @foreach(['personal', 'location', 'contact', 'skills', 'support', 'consent'] as $index => $step)
            <div class="flex flex-col items-center flex-1">
                <div class="step-indicator {{ $index == 0 ? 'active' : '' }}" 
                     data-step="{{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>
                <span class="text-xs mt-2 text-gray-600 hidden sm:block">
                    @switch($step)
                        @case('personal') {{ __('Personal') }} @break
                        @case('location') {{ __('Location') }} @break
                        @case('contact') {{ __('Contact') }} @break
                        @case('skills') {{ __('Skills') }} @break
                        @case('support') {{ __('Support') }} @break
                        @case('consent') {{ __('Consent') }} @break
                    @endswitch
                </span>
            </div>
            @if($index < 5)
            <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
            @endif
            @endforeach
        </div>
        
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 16.66%"></div>
        </div>
    </div>

    <!-- Registration Form -->
    <form id="registration-form" action="{{ route('registration.store') }}" method="POST" class="form-card p-6">
        @csrf
        
        <!-- Step 1: Personal Information -->
        <div class="step active" id="step-1">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-person-circle text-primary mr-2"></i>
                    {{ __('Personal Information') }}
                </h2>
                <p class="text-gray-600">{{ __('Tell us about yourself') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('First Name') }} *
                    </label>
                    <input type="text" name="first_name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('Enter your first name') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Last Name') }} *
                    </label>
                    <input type="text" name="last_name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('Enter your last name') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Gender') }} *
                    </label>
                    <select name="gender" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">{{ __('Select gender') }}</option>
                        @foreach($genders as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Date of Birth') }}
                    </label>
                    <input type="date" name="date_of_birth"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                    <p class="text-sm text-gray-500 mt-1">{{ __('Optional - used for community statistics') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Nationality') }} *
                    </label>
                    <input type="text" name="nationality" value="Angolan" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Citizenship Status') }} *
                    </label>
                    <select name="citizenship_status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                            id="citizenship-status">
                        <option value="">{{ __('Select status') }}</option>
                        <option value="angolan">{{ __('Angolan') }}</option>
                        <option value="south_african">{{ __('South African') }}</option>
                        <option value="dual_citizenship">{{ __('Dual Citizenship') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                
                <div class="hidden" id="other-citizenship-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Other Citizenship') }} *
                    </label>
                    <input type="text" name="other_citizenship"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('Specify citizenship') }}">
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <div></div>
                <button type="button" onclick="nextStep(2)"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    {{ __('Next') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 2: Location Information -->
        <div class="step" id="step-2">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-geo-alt text-primary mr-2"></i>
                    {{ __('Location Information') }}
                </h2>
                <p class="text-gray-600">{{ __('Where are you located in South Africa?') }}</p>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Province') }} *
                    </label>
                    <select name="province" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">{{ __('Select province') }}</option>
                        @foreach($provinces as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('City / Town') }} *
                    </label>
                    <input type="text" name="city" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('Enter your city or town') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Area / Suburb') }}
                    </label>
                    <input type="text" name="area"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('Optional - enter your area or suburb') }}">
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(1)"
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(3)"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    {{ __('Next') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 3: Contact Information -->
        <div class="step" id="step-3">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-telephone text-primary mr-2"></i>
                    {{ __('Contact Information') }}
                </h2>
                <p class="text-gray-600">{{ __('How can we reach you?') }}</p>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Mobile Number') }} *
                    </label>
                    <input type="tel" name="mobile_number" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="+27 12 345 6789">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Email Address') }}
                    </label>
                    <input type="email" name="email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('your.email@example.com') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Preferred Contact Method') }} *
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach(['phone' => __('Phone Call'), 'whatsapp' => __('WhatsApp'), 'email' => __('Email')] as $value => $label)
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="preferred_contact_method" value="{{ $value }}"
                                   class="mr-3 text-primary focus:ring-primary">
                            <div>
                                <span class="font-medium">{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="hidden" id="whatsapp-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('WhatsApp Number') }} *
                    </label>
                    <input type="tel" name="whatsapp_number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="+27 12 345 6789">
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(2)"
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(4)"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    {{ __('Next') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 4: Skills & Professional Information -->
        <div class="step" id="step-4">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-briefcase text-primary mr-2"></i>
                    {{ __('Skills & Professional Information') }}
                </h2>
                <p class="text-gray-600">{{ __('Tell us about your skills and experience') }}</p>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Employment Status') }} *
                    </label>
                    <select name="employment_status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">{{ __('Select status') }}</option>
                        <option value="employed">{{ __('Employed') }}</option>
                        <option value="self_employed">{{ __('Self-Employed') }}</option>
                        <option value="student">{{ __('Student') }}</option>
                        <option value="unemployed">{{ __('Unemployed') }}</option>
                        <option value="retired">{{ __('Retired') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Profession / Field of Study') }}
                    </label>
                    <input type="text" name="profession"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="{{ __('e.g., Teacher, Engineer, IT Specialist') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        {{ __('Skills') }}
                    </label>
                    <div class="space-y-4">
                        @foreach($skills->groupBy('category') as $category => $categorySkills)
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">{{ __($category) }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($categorySkills as $skill)
                                <div class="skill-chip" data-skill-id="{{ $skill->id }}">
                                    <input type="hidden" name="skills[{{ $skill->id }}][level]" value="intermediate">
                                    <input type="hidden" name="skills[{{ $skill->id }}][years]" value="">
                                    <span>{{ $skill->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Skill Details Modal -->
                    <div id="skill-details-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold" id="skill-name"></h3>
                                <button type="button" onclick="closeSkillModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" id="selected-skill-id">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Experience Level') }}
                                    </label>
                                    <select id="experience-level" class="w-full px-3 py-2 border rounded-lg">
                                        <option value="beginner">{{ __('Beginner') }}</option>
                                        <option value="intermediate">{{ __('Intermediate') }}</option>
                                        <option value="expert">{{ __('Expert') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ __('Years of Experience') }}
                                    </label>
                                    <input type="number" id="years-experience" min="0" max="50"
                                           class="w-full px-3 py-2 border rounded-lg"
                                           placeholder="{{ __('e.g., 5') }}">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" onclick="closeSkillModal()"
                                        class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="button" onclick="saveSkillDetails()"
                                        class="btn-primary text-white px-4 py-2 rounded-lg">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="willing_to_help" id="willing_to_help" value="1"
                           class="h-5 w-5 text-primary rounded focus:ring-primary">
                    <label for="willing_to_help" class="ml-3 text-gray-700">
                        {{ __('I am willing to offer my skills and services to help the community') }}
                    </label>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(3)"
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(5)"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    {{ __('Next') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 5: Community Support -->
        <div class="step" id="step-5">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-heart text-primary mr-2"></i>
                    {{ __('Community Support') }}
                </h2>
                <p class="text-gray-600">{{ __('How would you like to help the community?') }}</p>
            </div>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        {{ __('Areas where I can help (Optional)') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($supportAreas as $area)
                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="support_areas[]" value="{{ $area->id }}"
                                   class="mt-1 mr-3 text-primary focus:ring-primary">
                            <div>
                                <span class="font-medium">{{ $area->name }}</span>
                                <p class="text-sm text-gray-600 mt-1">{{ $area->description }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(4)"
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(6)"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    {{ __('Next') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 6: Consent & Declaration -->
        <div class="step" id="step-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="bi bi-shield-check text-primary mr-2"></i>
                    {{ __('Consent & Declaration') }}
                </h2>
                <p class="text-gray-600">{{ __('Please review and agree to continue') }}</p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-bold text-gray-800 mb-4">{{ __('Data Usage Agreement') }}</h3>
                    <div class="space-y-4 text-gray-700">
                        <p>{{ __('By registering, I agree that:') }}</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>{{ __('My data will be used strictly for community organization purposes') }}</li>
                            <li>{{ __('My information will help connect community members for support and opportunities') }}</li>
                            <li>{{ __('My contact details will only be shared with my explicit consent') }}</li>
                            <li>{{ __('I can request to update or delete my information at any time') }}</li>
                            <li>{{ __('The community leadership is committed to protecting my privacy') }}</li>
                        </ul>
                        <p class="text-sm text-gray-600 mt-4">
                            <i class="bi bi-info-circle mr-1"></i>
                            {{ __('This platform complies with POPIA (Protection of Personal Information Act) regulations.') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" name="consent" id="consent" value="1" required
                           class="mt-1 h-5 w-5 text-primary rounded focus:ring-primary">
                    <label for="consent" class="ml-3 text-gray-700">
                        {{ __('I have read and agree to the data usage terms above. I understand that my information will be stored securely and used only for community organization purposes.') }}
                        <span class="text-red-500">*</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(5)"
                        class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="bi bi-arrow-left mr-2"></i>
                    {{ __('Back') }}
                </button>
                <button type="submit"
                        class="btn-primary text-white px-8 py-3 rounded-lg hover:shadow-lg transition flex items-center">
                    <i class="bi bi-check-circle mr-2"></i>
                    {{ __('Complete Registration') }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 6;
    
    function nextStep(step) {
        if (validateStep(currentStep)) {
            showStep(step);
            updateProgress(step);
        }
    }
    
    function prevStep(step) {
        showStep(step);
        updateProgress(step);
    }
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        
        // Show current step
        document.getElementById(`step-${step}`).classList.add('active');
        
        // Update indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNum = index + 1;
            indicator.classList.remove('active', 'completed');
            if (stepNum === step) {
                indicator.classList.add('active');
            } else if (stepNum < step) {
                indicator.classList.add('completed');
            }
        });
        
        currentStep = step;
    }
    
    function updateProgress(step) {
        const progress = (step / totalSteps) * 100;
        document.getElementById('progress-fill').style.width = `${progress}%`;
    }
    
    function validateStep(step) {
        const currentStepEl = document.getElementById(`step-${step}`);
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                field.focus();
                
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("Required Field") }}',
                    text: field.labels?.[0]?.textContent || '{{ __("Please fill in all required fields") }}',
                    confirmButtonColor: '#008751'
                });
                
                return false;
            }
            field.classList.remove('border-red-500');
        }
        
        // Special validations
        if (step === 1) {
            const citizenshipStatus = document.getElementById('citizenship-status').value;
            if (citizenshipStatus === 'other') {
                const otherInput = document.querySelector('[name="other_citizenship"]');
                if (!otherInput.value.trim()) {
                    otherInput.classList.add('border-red-500');
                    otherInput.focus();
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __("Required Field") }}',
                        text: '{{ __("Please specify your citizenship") }}',
                        confirmButtonColor: '#008751'
                    });
                    return false;
                }
            }
        }
        
        if (step === 3) {
            const contactMethod = document.querySelector('input[name="preferred_contact_method"]:checked');
            if (contactMethod && contactMethod.value === 'whatsapp') {
                const whatsappInput = document.querySelector('[name="whatsapp_number"]');
                if (!whatsappInput.value.trim()) {
                    whatsappInput.classList.add('border-red-500');
                    whatsappInput.focus();
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __("Required Field") }}',
                        text: '{{ __("WhatsApp number is required when selecting WhatsApp as preferred contact method") }}',
                        confirmButtonColor: '#008751'
                    });
                    return false;
                }
            }
        }
        
        return true;
    }
    
    // Dynamic form elements
    document.addEventListener('DOMContentLoaded', function() {
        // Citizenship status change
        document.getElementById('citizenship-status').addEventListener('change', function() {
            const otherContainer = document.getElementById('other-citizenship-container');
            if (this.value === 'other') {
                otherContainer.classList.remove('hidden');
                otherContainer.querySelector('input').required = true;
            } else {
                otherContainer.classList.add('hidden');
                otherContainer.querySelector('input').required = false;
                otherContainer.querySelector('input').value = '';
            }
        });
        
        // Preferred contact method change
        document.querySelectorAll('input[name="preferred_contact_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const whatsappContainer = document.getElementById('whatsapp-container');
                if (this.value === 'whatsapp') {
                    whatsappContainer.classList.remove('hidden');
                    whatsappContainer.querySelector('input').required = true;
                } else {
                    whatsappContainer.classList.add('hidden');
                    whatsappContainer.querySelector('input').required = false;
                    whatsappContainer.querySelector('input').value = '';
                }
            });
        });
        
        // Skill selection
        document.querySelectorAll('.skill-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                const skillId = this.dataset.skillId;
                const skillName = this.textContent.trim();
                
                if (this.classList.contains('selected')) {
                    // Remove skill
                    this.classList.remove('selected');
                    const hiddenInputs = this.querySelectorAll('input[type="hidden"]');
                    hiddenInputs.forEach(input => input.value = '');
                } else {
                    // Open modal to enter skill details
                    document.getElementById('skill-name').textContent = skillName;
                    document.getElementById('selected-skill-id').value = skillId;
                    document.getElementById('skill-details-modal').classList.remove('hidden');
                }
            });
        });
    });
    
    function saveSkillDetails() {
        const skillId = document.getElementById('selected-skill-id').value;
        const experienceLevel = document.getElementById('experience-level').value;
        const yearsExperience = document.getElementById('years-experience').value;
        
        // Find the skill chip
        const skillChip = document.querySelector(`.skill-chip[data-skill-id="${skillId}"]`);
        
        // Update hidden inputs
        const levelInput = skillChip.querySelector('input[name^="skills["][name$="[level]"]');
        const yearsInput = skillChip.querySelector('input[name^="skills["][name$="[years]"]');
        
        levelInput.value = experienceLevel;
        yearsInput.value = yearsExperience;
        
        // Mark as selected
        skillChip.classList.add('selected');
        
        // Add indicator
        const indicator = document.createElement('span');
        indicator.className = 'ml-2 text-xs bg-primary text-white px-2 py-1 rounded-full';
        indicator.textContent = experienceLevel.charAt(0).toUpperCase() + experienceLevel.slice(1);
        skillChip.appendChild(indicator);
        
        closeSkillModal();
    }
    
    function closeSkillModal() {
        document.getElementById('skill-details-modal').classList.add('hidden');
    }
</script>
@endsection