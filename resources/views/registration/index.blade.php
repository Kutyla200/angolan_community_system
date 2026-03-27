@extends('layouts.app')

@section('title', __('Registration Form'))

@push('styles')
<style>
    .registration-hero {
        background: linear-gradient(135deg, rgba(0, 135, 81, 0.06) 0%, rgba(204, 9, 47, 0.03) 100%);
        padding: 2.5rem 0;
        margin-bottom: 2rem;
    }
    
    .step { display: none; }
    .step.active {
        display: block;
        animation: fadeInSlide 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .progress-bar {
        height: 8px;
        background: #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #008751, #CC092F);
        border-radius: 8px;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .step-indicator {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #e5e7eb;
        color: #6b7280;
        border: 2px solid transparent;
        flex-shrink: 0;
    }
    
    .step-indicator.active {
        background: #008751;
        color: white;
        box-shadow: 0 4px 14px rgba(0, 135, 81, 0.35);
        transform: scale(1.1);
    }
    
    .step-indicator.completed {
        background: #FFD100;
        color: #1a1a2e;
        content: '✓';
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #008751, #CC092F, #FFD100);
    }
    
    /* Skill chips */
    .skill-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        margin: 4px;
        background: #f9fafb;
        border-radius: 24px;
        cursor: pointer;
        border: 2px solid #e5e7eb;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        user-select: none;
    }
    
    .skill-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border-color: #008751;
        color: #006b42;
    }
    
    .skill-chip.selected {
        background: rgba(0, 135, 81, 0.08);
        border-color: #008751;
        color: #006b42;
        box-shadow: 0 2px 8px rgba(0, 135, 81, 0.15);
    }

    .skill-chip .skill-badge {
        font-size: 10px;
        background: #008751;
        color: white;
        border-radius: 10px;
        padding: 1px 7px;
        font-weight: 600;
        display: none;
    }

    .skill-chip.selected .skill-badge {
        display: inline;
    }

    /* Modal */
    .skill-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .skill-modal-overlay.open {
        display: flex;
        animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .skill-modal {
        background: white;
        border-radius: 20px;
        padding: 28px;
        max-width: 420px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: scaleIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Form inputs */
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #374151;
        background: #fafafa;
        transition: all 0.2s ease;
        outline: none;
    }

    .form-input:focus {
        border-color: #008751;
        background: white;
        box-shadow: 0 0 0 3px rgba(0,135,81,0.1);
    }

    .form-input.error {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    /* Buttons */
    .btn-primary-reg {
        background: linear-gradient(135deg, #008751, #006b42);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-primary-reg:hover {
        background: linear-gradient(135deg, #006b42, #004d2f);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,135,81,0.3);
    }

    .btn-secondary-reg {
        background: white;
        color: #374151;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border: 1.5px solid #e5e7eb;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-secondary-reg:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    /* Contact method cards */
    .contact-option {
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .contact-option:hover {
        border-color: #008751;
        background: rgba(0,135,81,0.02);
    }

    .contact-option input[type="radio"]:checked ~ * {
        color: #006b42;
    }

    .contact-option:has(input:checked) {
        border-color: #008751;
        background: rgba(0,135,81,0.05);
    }

    /* Support area checkboxes */
    .support-option {
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .support-option:hover {
        border-color: #008751;
    }

    .support-option:has(input:checked) {
        border-color: #008751;
        background: rgba(0,135,81,0.04);
    }

    @media (max-width: 640px) {
        .form-card { border-radius: 16px; }
        .step-indicator { width: 36px; height: 36px; font-size: 14px; }
    }
</style>
@endpush

@section('content')
<!-- Hero -->
<div class="registration-hero">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="bi bi-person-plus-fill text-white text-2xl"></i>
            </div>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
            {{ __('Join UMOJA Angola') }}
        </h1>
        <p class="text-base text-gray-600 max-w-xl mx-auto">
            {{ __('Become part of our growing community. Registration takes less than 5 minutes.') }}
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    
    <!-- Step Indicators -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            @foreach(['personal' => __('Personal'), 'location' => __('Location'), 'contact' => __('Contact'), 'skills' => __('Skills'), 'support' => __('Support'), 'consent' => __('Consent')] as $key => $label)
            <div class="flex flex-col items-center flex-1">
                <div class="step-indicator" data-step="{{ $loop->index + 1 }}" id="indicator-{{ $loop->index + 1 }}">
                    <span class="step-num">{{ $loop->index + 1 }}</span>
                    <span class="check-icon hidden">✓</span>
                </div>
                <span class="text-xs mt-1.5 text-gray-500 hidden sm:block font-medium">{{ $label }}</span>
            </div>
            @if(!$loop->last)
            <div class="h-px bg-gray-200 flex-1 mx-1 mb-5"></div>
            @endif
            @endforeach
        </div>
        
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 16.66%"></div>
        </div>
    </div>

    <!-- Form -->
    <form id="registration-form" action="{{ route('registration.store') }}" method="POST" class="form-card p-6 sm:p-8">
        @csrf
        
        <!-- STEP 1: Personal -->
        <div class="step active" id="step-1">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-person-circle text-green-600"></i>
                    {{ __('Personal Information') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Tell us about yourself') }}</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">{{ __('First Name') }} <span class="required">*</span></label>
                    <input type="text" name="first_name" required class="form-input"
                           placeholder="{{ __('Enter your first name') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Last Name') }} <span class="required">*</span></label>
                    <input type="text" name="last_name" required class="form-input"
                           placeholder="{{ __('Enter your last name') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Gender') }} <span class="required">*</span></label>
                    <select name="gender" required class="form-input">
                        <option value="">{{ __('Select gender') }}</option>
                        @foreach($genders as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('Date of Birth') }}</label>
                    <input type="date" name="date_of_birth" class="form-input">
                    <p class="text-xs text-gray-400 mt-1">{{ __('Optional - used for community statistics') }}</p>
                </div>
                <div>
                    <label class="form-label">{{ __('Nationality') }} <span class="required">*</span></label>
                    <input type="text" name="nationality" value="Angolan" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('Citizenship Status') }} <span class="required">*</span></label>
                    <select name="citizenship_status" required class="form-input" id="citizenship-status">
                        <option value="">{{ __('Select status') }}</option>
                        <option value="angolan">{{ __('Angolan') }}</option>
                        <option value="south_african">{{ __('South African') }}</option>
                        <option value="dual_citizenship">{{ __('Dual Citizenship') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div class="sm:col-span-2 hidden" id="other-citizenship-container">
                    <label class="form-label">{{ __('Other Citizenship') }} <span class="required">*</span></label>
                    <input type="text" name="other_citizenship" class="form-input"
                           placeholder="{{ __('Specify citizenship') }}">
                </div>
            </div>
            
            <div class="flex justify-end mt-8">
                <button type="button" onclick="nextStep(2)" class="btn-primary-reg">
                    {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- STEP 2: Location -->
        <div class="step" id="step-2">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-green-600"></i>
                    {{ __('Location Information') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Where are you located in South Africa?') }}</p>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="form-label">{{ __('Province') }} <span class="required">*</span></label>
                    <select name="province" required class="form-input">
                        <option value="">{{ __('Select province') }}</option>
                        @foreach($provinces as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('City / Town') }} <span class="required">*</span></label>
                    <input type="text" name="city" required class="form-input"
                           placeholder="{{ __('Enter your city or town') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Area / Suburb') }}</label>
                    <input type="text" name="area" class="form-input"
                           placeholder="{{ __('Optional - enter your area or suburb') }}">
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(1)" class="btn-secondary-reg">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(3)" class="btn-primary-reg">
                    {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- STEP 3: Contact -->
        <div class="step" id="step-3">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-telephone text-green-600"></i>
                    {{ __('Contact Information') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('How can we reach you?') }}</p>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="form-label">{{ __('Mobile Number') }} <span class="required">*</span></label>
                    <input type="tel" name="mobile_number" required class="form-input"
                           placeholder="+27 12 345 6789">
                </div>
                <div>
                    <label class="form-label">{{ __('Email Address') }}</label>
                    <input type="email" name="email" class="form-input"
                           placeholder="{{ __('your.email@example.com') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Preferred Contact Method') }} <span class="required">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach(['phone' => ['icon' => 'bi-telephone', 'label' => __('Phone Call')], 'whatsapp' => ['icon' => 'bi-whatsapp', 'label' => __('WhatsApp')], 'email' => ['icon' => 'bi-envelope', 'label' => __('Email')]] as $value => $item)
                        <label class="contact-option">
                            <input type="radio" name="preferred_contact_method" value="{{ $value }}"
                                   class="text-green-600 focus:ring-green-500 flex-shrink-0">
                            <div>
                                <i class="bi {{ $item['icon'] }} text-green-600 text-lg block mb-0.5"></i>
                                <span class="text-sm font-medium text-gray-800">{{ $item['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="hidden" id="whatsapp-container">
                    <label class="form-label">{{ __('WhatsApp Number') }} <span class="required">*</span></label>
                    <input type="tel" name="whatsapp_number" class="form-input"
                           placeholder="+27 12 345 6789">
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(2)" class="btn-secondary-reg">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(4)" class="btn-primary-reg">
                    {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- STEP 4: Skills -->
        <div class="step" id="step-4">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-briefcase text-green-600"></i>
                    {{ __('Skills & Professional Information') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Tell us about your skills and experience') }}</p>
            </div>
            
            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">{{ __('Employment Status') }} <span class="required">*</span></label>
                        <select name="employment_status" required class="form-input">
                            <option value="">{{ __('Select status') }}</option>
                            <option value="employed">{{ __('Employed') }}</option>
                            <option value="self_employed">{{ __('Self-Employed') }}</option>
                            <option value="student">{{ __('Student') }}</option>
                            <option value="unemployed">{{ __('Unemployed') }}</option>
                            <option value="retired">{{ __('Retired') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('Profession / Field of Study') }}</label>
                        <input type="text" name="profession" class="form-input"
                               placeholder="{{ __('e.g., Teacher, Engineer, IT Specialist') }}">
                    </div>
                </div>
                
                <div>
                    <label class="form-label">{{ __('Skills') }}</label>
                    <p class="text-xs text-gray-500 mb-3">{{ __('Click to select, click again to set experience details') }}</p>
                    
                    <!-- Hidden skill data stored here -->
                    <div id="skills-data-container"></div>
                    
                    @foreach($skills->groupBy('category') as $category => $categorySkills)
                    <div class="mb-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __($category) }}</h4>
                        <div class="flex flex-wrap">
                            @foreach($categorySkills as $skill)
                            <div class="skill-chip" 
                                 data-skill-id="{{ $skill->id }}" 
                                 data-skill-name="{{ $skill->name }}"
                                 onclick="toggleSkill(this)">
                                <i class="bi {{ $skill->icon ?? 'bi-star' }} text-xs opacity-60"></i>
                                <span>{{ $skill->name }}</span>
                                <span class="skill-badge" id="badge-{{ $skill->id }}">intermediate</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="flex items-start gap-3 p-4 bg-green-50 rounded-12 border border-green-100">
                    <input type="checkbox" name="willing_to_help" id="willing_to_help" value="1"
                           class="mt-0.5 h-4 w-4 text-green-600 rounded focus:ring-green-500 flex-shrink-0">
                    <label for="willing_to_help" class="text-sm text-gray-700 cursor-pointer">
                        <span class="font-semibold text-green-800">{{ __('Willing to Help') }}</span><br>
                        {{ __('I am willing to offer my skills and services to help the community') }}
                    </label>
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(3)" class="btn-secondary-reg">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(5)" class="btn-primary-reg">
                    {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- STEP 5: Support -->
        <div class="step" id="step-5">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-heart text-green-600"></i>
                    {{ __('Community Support') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('How would you like to help the community?') }}</p>
            </div>
            
            <div>
                <label class="form-label">{{ __('Areas where I can help') }} <span class="text-gray-400 font-normal">({{ __('Optional') }})</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                    @foreach($supportAreas as $area)
                    <label class="support-option">
                        <input type="checkbox" name="support_areas[]" value="{{ $area->id }}"
                               class="h-4 w-4 text-green-600 rounded focus:ring-green-500 flex-shrink-0 mt-0.5">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                                <i class="bi {{ $area->icon ?? 'bi-check-circle' }} text-green-600 mr-1"></i>
                                {{ $area->name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $area->description }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(4)" class="btn-secondary-reg">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </button>
                <button type="button" onclick="nextStep(6)" class="btn-primary-reg">
                    {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- STEP 6: Consent -->
        <div class="step" id="step-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-shield-check text-green-600"></i>
                    {{ __('Consent & Declaration') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('Please review and agree to continue') }}</p>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-16 p-5 mb-5">
                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    {{ __('Data Usage Agreement') }}
                </h3>
                <p class="text-sm text-gray-700 mb-3">{{ __('By registering, I agree that:') }}</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex gap-2"><i class="bi bi-check-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i><span>{{ __('My data will be used strictly for community organization purposes') }}</span></li>
                    <li class="flex gap-2"><i class="bi bi-check-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i><span>{{ __('My information will help connect community members for support and opportunities') }}</span></li>
                    <li class="flex gap-2"><i class="bi bi-check-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i><span>{{ __('My contact details will only be shared with my explicit consent') }}</span></li>
                    <li class="flex gap-2"><i class="bi bi-check-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i><span>{{ __('I can request to update or delete my information at any time') }}</span></li>
                    <li class="flex gap-2"><i class="bi bi-check-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i><span>{{ __('The community leadership is committed to protecting my privacy') }}</span></li>
                </ul>
                <p class="text-xs text-gray-500 mt-4 flex items-center gap-1.5">
                    <i class="bi bi-shield-check text-blue-500"></i>
                    {{ __('This platform complies with POPIA (Protection of Personal Information Act) regulations.') }}
                </p>
            </div>
            
            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-12 cursor-pointer hover:border-green-500 transition-colors" id="consent-label">
                <input type="checkbox" name="consent" id="consent" value="1" required
                       class="mt-0.5 h-5 w-5 text-green-600 rounded focus:ring-green-500 flex-shrink-0"
                       onchange="document.getElementById('consent-label').classList.toggle('border-green-500', this.checked); document.getElementById('consent-label').classList.toggle('bg-green-50', this.checked);">
                <span class="text-sm text-gray-700">
                    {{ __('I have read and agree to the data usage terms above. I understand that my information will be stored securely and used only for community organization purposes.') }}
                    <span class="text-red-500 ml-0.5">*</span>
                </span>
            </label>
            
            <div class="flex justify-between mt-8">
                <button type="button" onclick="prevStep(5)" class="btn-secondary-reg">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </button>
                <button type="submit" class="btn-primary-reg">
                    <i class="bi bi-check-circle"></i>
                    {{ __('Complete Registration') }}
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Skill Details Modal - FIXED -->
<div class="skill-modal-overlay" id="skill-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="skill-modal-title">
    <div class="skill-modal" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900" id="skill-modal-title"></h3>
            <button type="button" onclick="closeSkillModal(false)" 
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('Experience Level') }}</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['beginner' => __('Beginner'), 'intermediate' => __('Intermediate'), 'expert' => __('Expert')] as $val => $lbl)
                    <label class="flex flex-col items-center p-3 border-2 border-gray-200 rounded-10 cursor-pointer transition hover:border-green-500"
                           id="level-label-{{ $val }}"
                           onclick="selectLevel('{{ $val }}')">
                        <input type="radio" name="modal_level" value="{{ $val }}" 
                               id="level-{{ $val }}"
                               class="hidden">
                        <span class="text-sm font-semibold text-gray-700">{{ $lbl }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="form-label" for="modal-years">{{ __('Years of Experience') }}</label>
                <input type="number" id="modal-years" min="0" max="50" placeholder="e.g. 5"
                       class="form-input">
                <p class="text-xs text-gray-400 mt-1">{{ __('Optional') }}</p>
            </div>
        </div>
        
        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeSkillModal(false)" class="btn-secondary-reg flex-1 justify-center">
                {{ __('Cancel') }}
            </button>
            <button type="button" onclick="closeSkillModal(true)" class="btn-primary-reg flex-1 justify-center">
                <i class="bi bi-check"></i>
                {{ __('Save') }}
            </button>
        </div>
    </div>
</div>

<script>
// ============================
// MULTI-STEP FORM
// ============================
let currentStep = 1;
const totalSteps = 6;

function showStep(step) {
    document.querySelectorAll('.step').forEach(function(s) { s.classList.remove('active'); });
    document.getElementById('step-' + step).classList.add('active');
    
    document.querySelectorAll('.step-indicator').forEach(function(ind, idx) {
        const stepNum = idx + 1;
        ind.classList.remove('active', 'completed');
        ind.querySelector('.step-num').classList.remove('hidden');
        ind.querySelector('.check-icon').classList.add('hidden');
        
        if (stepNum === step) {
            ind.classList.add('active');
        } else if (stepNum < step) {
            ind.classList.add('completed');
            ind.querySelector('.step-num').classList.add('hidden');
            ind.querySelector('.check-icon').classList.remove('hidden');
        }
    });
    
    currentStep = step;
    document.getElementById('progress-fill').style.width = (step / totalSteps * 100) + '%';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextStep(step) {
    if (validateStep(currentStep)) showStep(step);
}

function prevStep(step) {
    showStep(step);
}

function validateStep(step) {
    const stepEl = document.getElementById('step-' + step);
    const required = stepEl.querySelectorAll('[required]');
    let valid = true;
    let firstError = null;
    
    required.forEach(function(field) {
        field.classList.remove('error');
        const val = field.type === 'checkbox' ? field.checked : field.value.trim();
        if (!val) {
            field.classList.add('error');
            if (!firstError) firstError = field;
            valid = false;
        }
    });
    
    if (!valid) {
        if (firstError) firstError.focus();
        Swal.fire({
            icon: 'warning',
            title: '{{ __("Required Field") }}',
            text: '{{ __("Please fill in all required fields") }}',
            confirmButtonColor: '#008751',
            timer: 3000,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
        return false;
    }
    
    // Special: citizenship other
    if (step === 1) {
        const cit = document.getElementById('citizenship-status').value;
        if (cit === 'other') {
            const otherInput = document.querySelector('[name="other_citizenship"]');
            if (!otherInput.value.trim()) {
                otherInput.classList.add('error');
                otherInput.focus();
                Swal.fire({ icon: 'warning', title: '{{ __("Required Field") }}', text: '{{ __("Please specify your citizenship") }}', confirmButtonColor: '#008751', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                return false;
            }
        }
    }
    
    // Special: whatsapp
    if (step === 3) {
        const contactMethod = document.querySelector('input[name="preferred_contact_method"]:checked');
        if (!contactMethod) {
            Swal.fire({ icon: 'warning', title: '{{ __("Required") }}', text: 'Please select a preferred contact method.', confirmButtonColor: '#008751', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
            return false;
        }
        if (contactMethod.value === 'whatsapp') {
            const wa = document.querySelector('[name="whatsapp_number"]');
            if (!wa.value.trim()) {
                wa.classList.add('error');
                wa.focus();
                Swal.fire({ icon: 'warning', title: '{{ __("Required Field") }}', text: '{{ __("WhatsApp number is required when selecting WhatsApp as preferred contact method") }}', confirmButtonColor: '#008751', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                return false;
            }
        }
    }
    
    return true;
}

// ============================
// DYNAMIC FORM LOGIC
// ============================
document.addEventListener('DOMContentLoaded', function() {
    // Citizenship
    document.getElementById('citizenship-status').addEventListener('change', function() {
        const container = document.getElementById('other-citizenship-container');
        const input = container.querySelector('input');
        if (this.value === 'other') {
            container.classList.remove('hidden');
            input.required = true;
        } else {
            container.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    });
    
    // Contact method
    document.querySelectorAll('input[name="preferred_contact_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const container = document.getElementById('whatsapp-container');
            const input = container.querySelector('input');
            if (this.value === 'whatsapp') {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        });
    });

    // Close modal when clicking overlay background
    document.getElementById('skill-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeSkillModal(false);
    });
});

// ============================
// SKILL SELECTION - FIXED
// ============================
let currentSkillId = null;
let currentSkillEl = null;
let selectedLevel = 'intermediate';

// Stores skill data: { skillId: { level, years } }
const skillsData = {};

function toggleSkill(chipEl) {
    const skillId = chipEl.dataset.skillId;
    const skillName = chipEl.dataset.skillName;
    
    if (chipEl.classList.contains('selected')) {
        // Already selected — open modal to edit
        openSkillModal(chipEl, skillId, skillName);
    } else {
        // First select — open modal immediately
        openSkillModal(chipEl, skillId, skillName);
    }
}

function openSkillModal(chipEl, skillId, skillName) {
    currentSkillId = skillId;
    currentSkillEl = chipEl;
    
    // Pre-fill with existing data if any
    const existing = skillsData[skillId];
    selectedLevel = existing ? existing.level : 'intermediate';
    
    document.getElementById('skill-modal-title').textContent = skillName;
    document.getElementById('modal-years').value = existing ? (existing.years || '') : '';
    
    // Set level radio + visual
    selectLevel(selectedLevel, false); // false = don't save yet
    
    document.getElementById('skill-modal-overlay').classList.add('open');
    document.getElementById('modal-years').focus();
}

function selectLevel(level, save) {
    selectedLevel = level;
    
    // Update visual state of level buttons
    ['beginner', 'intermediate', 'expert'].forEach(function(l) {
        const label = document.getElementById('level-label-' + l);
        if (l === level) {
            label.classList.add('border-green-500', 'bg-green-50');
            label.classList.remove('border-gray-200');
        } else {
            label.classList.remove('border-green-500', 'bg-green-50');
            label.classList.add('border-gray-200');
        }
        const radio = document.getElementById('level-' + l);
        if (radio) radio.checked = (l === level);
    });
}

function closeSkillModal(save) {
    if (save && currentSkillId && currentSkillEl) {
        // Read values
        const level = selectedLevel;
        const years = document.getElementById('modal-years').value;
        
        // Store data
        skillsData[currentSkillId] = { level: level, years: years };
        
        // Mark chip as selected
        currentSkillEl.classList.add('selected');
        
        // Update badge text
        const badge = document.getElementById('badge-' + currentSkillId);
        if (badge) {
            badge.textContent = level.charAt(0).toUpperCase() + level.slice(1);
        }
        
        // Update/create hidden inputs
        updateSkillInputs(currentSkillId, level, years);
    }
    
    document.getElementById('skill-modal-overlay').classList.remove('open');
    currentSkillId = null;
    currentSkillEl = null;
}

function updateSkillInputs(skillId, level, years) {
    const container = document.getElementById('skills-data-container');
    
    // Remove old inputs for this skill
    const old = container.querySelectorAll('[data-for-skill="' + skillId + '"]');
    old.forEach(function(el) { el.remove(); });
    
    // Create new inputs
    const levelInput = document.createElement('input');
    levelInput.type = 'hidden';
    levelInput.name = 'skills[' + skillId + '][level]';
    levelInput.value = level;
    levelInput.dataset.forSkill = skillId;
    
    const yearsInput = document.createElement('input');
    yearsInput.type = 'hidden';
    yearsInput.name = 'skills[' + skillId + '][years]';
    yearsInput.value = years || '';
    yearsInput.dataset.forSkill = skillId;
    
    container.appendChild(levelInput);
    container.appendChild(yearsInput);
}

// ============================
// FORM SUBMISSION
// ============================
document.getElementById('registration-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalHTML = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> {{ __("Processing...") }}';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Success!") }}',
                text: data.message,
                confirmButtonText: '{{ __("Continue") }}',
                confirmButtonColor: '#008751'
            }).then(function() {
                window.location.href = '{{ route("home") }}';
            });
        } else {
            const errors = Object.values(data.errors || {}).flat().join('\n') || data.message;
            Swal.fire({
                icon: 'error',
                title: '{{ __("Error") }}',
                text: errors,
                confirmButtonColor: '#CC092F'
            });
        }
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: '{{ __("Error") }}',
            text: '{{ __("An error occurred. Please try again.") }}',
            confirmButtonColor: '#CC092F'
        });
    } finally {
        submitBtn.innerHTML = originalHTML;
        submitBtn.disabled = false;
    }
});
</script>
@endsection