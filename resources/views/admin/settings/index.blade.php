@extends('layouts.admin')

@section('title', __('System Settings'))
@section('header', __('Settings & Configuration'))

@push('styles')
<style>
    .settings-nav {
        position: sticky;
        top: 100px;
    }
    
    .settings-section {
        scroll-margin-top: 100px;
    }
    
    .setting-card {
        transition: all 0.3s ease;
    }
    
    .setting-card:hover {
        transform: translateX(4px);
        border-left-color: var(--primary-color);
    }
    
    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 30px;
        transition: 0.4s;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
        transition: 0.4s;
    }
    
    input:checked + .toggle-slider {
        background-color: var(--primary-color);
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }
</style>
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Settings Navigation -->
    <div class="lg:col-span-1">
        <div class="settings-nav card p-4">
            <h3 class="font-bold text-gray-900 mb-4">{{ __('Settings') }}</h3>
            <nav class="space-y-1">
                <a href="#general" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-gear mr-2"></i>
                    {{ __('General') }}
                </a>
                <a href="#notifications" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-bell mr-2"></i>
                    {{ __('Notifications') }}
                </a>
                <a href="#email" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-envelope mr-2"></i>
                    {{ __('Email Settings') }}
                </a>
                <a href="#security" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-shield-lock mr-2"></i>
                    {{ __('Security') }}
                </a>
                <a href="#data" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-database mr-2"></i>
                    {{ __('Data & Privacy') }}
                </a>
                <a href="#integrations" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-plug mr-2"></i>
                    {{ __('Integrations') }}
                </a>
                <a href="#backup" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-cloud-download mr-2"></i>
                    {{ __('Backup & Restore') }}
                </a>
                <a href="#system" class="block px-4 py-2 rounded-lg hover:bg-gray-50 text-gray-700 hover:text-primary">
                    <i class="bi bi-cpu mr-2"></i>
                    {{ __('System Info') }}
                </a>
            </nav>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="lg:col-span-3 space-y-6">
        <!-- General Settings -->
        <div id="general" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-gear text-primary mr-3"></i>
                {{ __('General Settings') }}
            </h2>

            <div class="space-y-6">
                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Site Name') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('The name of your community platform') }}</p>
                        </div>
                        <input type="text" value="Angolan Community" 
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Default Language') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Primary language for the platform') }}</p>
                        </div>
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                            <option value="en">English</option>
                            <option value="pt">Português</option>
                        </select>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Timezone') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Server timezone for dates and times') }}</p>
                        </div>
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                            <option value="Africa/Johannesburg">Africa/Johannesburg (SAST)</option>
                            <option value="Africa/Luanda">Africa/Luanda (WAT)</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('New Member Registration') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Allow new members to register') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Maintenance Mode') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Put the site in maintenance mode') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        {{ __('Reset') }}
                    </button>
                    <button class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div id="notifications" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-bell text-primary mr-3"></i>
                {{ __('Notification Settings') }}
            </h2>

            <div class="space-y-6">
                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('New Member Notifications') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Get notified when new members register') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Daily Summary') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Receive daily activity summary') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Weekly Report') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Receive weekly community statistics') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Email Notifications') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Send notifications via email') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('SMS Notifications') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Send critical notifications via SMS') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div id="email" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-envelope text-primary mr-3"></i>
                {{ __('Email Configuration') }}
            </h2>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('SMTP Host') }}</label>
                        <input type="text" placeholder="smtp.gmail.com"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('SMTP Port') }}</label>
                        <input type="text" placeholder="587"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('SMTP Username') }}</label>
                        <input type="text" placeholder="your-email@example.com"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('SMTP Password') }}</label>
                        <input type="password" placeholder="••••••••"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('From Email') }}</label>
                        <input type="email" placeholder="noreply@community.org"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('From Name') }}</label>
                        <input type="text" placeholder="Angolan Community"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="testEmail()" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="bi bi-send mr-2"></i>
                        {{ __('Send Test Email') }}
                    </button>
                    <button class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark ml-auto">
                        {{ __('Save Email Settings') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div id="security" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-shield-lock text-primary mr-3"></i>
                {{ __('Security Settings') }}
            </h2>

            <div class="space-y-6">
                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Two-Factor Authentication') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Require 2FA for admin accounts') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Session Timeout') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Auto-logout after inactivity (minutes)') }}</p>
                        </div>
                        <input type="number" value="60" min="5" max="240"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-32">
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Failed Login Attempts') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Max attempts before lockout') }}</p>
                        </div>
                        <input type="number" value="5" min="3" max="10"
                               class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-32">
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Password Policy') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Minimum password requirements') }}</p>
                        </div>
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                            <option>{{ __('Strong (12+ chars, mixed case, numbers, symbols)') }}</option>
                            <option>{{ __('Medium (8+ chars, mixed case, numbers)') }}</option>
                            <option>{{ __('Basic (6+ characters)') }}</option>
                        </select>
                    </div>
                </div>

                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="bi bi-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-yellow-900">{{ __('Security Audit') }}</h4>
                            <p class="text-sm text-yellow-700 mt-1">{{ __('Last security scan: 2 days ago') }}</p>
                            <button class="mt-3 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                                {{ __('Run Security Scan') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data & Privacy -->
        <div id="data" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-database text-primary mr-3"></i>
                {{ __('Data & Privacy Settings') }}
            </h2>

            <div class="space-y-6">
                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('POPIA Compliance') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Enforce POPIA data protection standards') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked disabled>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Data Retention Period') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('How long to keep deleted member data') }}</p>
                        </div>
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                            <option value="30">30 days</option>
                            <option value="90" selected>90 days</option>
                            <option value="180">180 days</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Data Export Requests') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Allow members to request their data') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Audit Logging') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Track all admin actions') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle text-blue-600 mr-3 mt-1"></i>
                        <div class="flex-1">
                            <h4 class="font-medium text-blue-900">{{ __('Privacy Policy') }}</h4>
                            <p class="text-sm text-blue-700 mt-1">{{ __('Review and update your privacy policy') }}</p>
                            <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                {{ __('Edit Privacy Policy') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrations -->
        <div id="integrations" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-plug text-primary mr-3"></i>
                {{ __('Integrations') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- WhatsApp Integration -->
                <div class="border rounded-lg p-6 hover:border-primary transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-whatsapp text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">WhatsApp Business</h3>
                            <p class="text-sm text-gray-600">{{ __('Send messages via WhatsApp') }}</p>
                        </div>
                    </div>
                    <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        {{ __('Configure') }}
                    </button>
                </div>

                <!-- SMS Gateway -->
                <div class="border rounded-lg p-6 hover:border-primary transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-chat-text text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">SMS Gateway</h3>
                            <p class="text-sm text-gray-600">{{ __('Bulk SMS notifications') }}</p>
                        </div>
                    </div>
                    <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ __('Configure') }}
                    </button>
                </div>

                <!-- Google Analytics -->
                <div class="border rounded-lg p-6 hover:border-primary transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-graph-up text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Google Analytics</h3>
                            <p class="text-sm text-gray-600">{{ __('Track website analytics') }}</p>
                        </div>
                    </div>
                    <button class="w-full px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        {{ __('Configure') }}
                    </button>
                </div>

                <!-- Calendar Integration -->
                <div class="border rounded-lg p-6 hover:border-primary transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-calendar text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Google Calendar</h3>
                            <p class="text-sm text-gray-600">{{ __('Sync events and meetings') }}</p>
                        </div>
                    </div>
                    <button class="w-full px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        {{ __('Configure') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup & Restore -->
        <div id="backup" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-cloud-download text-primary mr-3"></i>
                {{ __('Backup & Restore') }}
            </h2>

            <div class="space-y-6">
                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Automatic Backups') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Enable scheduled database backups') }}</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="setting-card border-l-4 border-transparent p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ __('Backup Frequency') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('How often to create backups') }}</p>
                        </div>
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary w-64">
                            <option value="hourly">{{ __('Hourly') }}</option>
                            <option value="daily" selected>{{ __('Daily') }}</option>
                            <option value="weekly">{{ __('Weekly') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button onclick="createBackup()" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center justify-center">
                        <i class="bi bi-download mr-2"></i>
                        {{ __('Create Backup Now') }}
                    </button>
                    
                    <button onclick="restoreBackup()" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                        <i class="bi bi-upload mr-2"></i>
                        {{ __('Restore from Backup') }}
                    </button>
                </div>

                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">{{ __('Recent Backups') }}</h4>
                    <div class="space-y-2">
                        @for($i = 0; $i < 5; $i++)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="bi bi-file-earmark-zip text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">backup-{{ date('Y-m-d', strtotime("-$i days")) }}.zip</p>
                                    <p class="text-xs text-gray-600">{{ number_format(rand(500, 2000)) }} MB • {{ date('M d, Y H:i', strtotime("-$i days")) }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 text-sm text-primary hover:bg-primary-50 rounded">
                                    {{ __('Download') }}
                                </button>
                                <button class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div id="system" class="settings-section card p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="bi bi-cpu text-primary mr-3"></i>
                {{ __('System Information') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-3">{{ __('Application') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Version') }}</span>
                            <span class="font-medium">v1.0.0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Laravel') }}</span>
                            <span class="font-medium">{{ app()->version() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('PHP') }}</span>
                            <span class="font-medium">{{ PHP_VERSION }}</span>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-3">{{ __('Database') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Type') }}</span>
                            <span class="font-medium">MySQL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Size') }}</span>
                            <span class="font-medium">{{ number_format(rand(100, 500)) }} MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Tables') }}</span>
                            <span class="font-medium">15</span>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-3">{{ __('Server') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('OS') }}</span>
                            <span class="font-medium">{{ PHP_OS }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Memory Limit') }}</span>
                            <span class="font-medium">{{ ini_get('memory_limit') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Upload Max') }}</span>
                            <span class="font-medium">{{ ini_get('upload_max_filesize') }}</span>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-700 mb-3">{{ __('Storage') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Disk Usage') }}</span>
                            <span class="font-medium">2.3 GB / 50 GB</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 4.6%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-600 text-2xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-green-900">{{ __('System Status: Healthy') }}</h4>
                        <p class="text-sm text-green-700 mt-1">{{ __('All systems operational. Last check: 5 minutes ago') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testEmail() {
    Swal.fire({
        title: '{{ __("Send Test Email") }}',
        input: 'email',
        inputPlaceholder: '{{ __("Enter recipient email") }}',
        showCancelButton: true,
        confirmButtonText: '{{ __("Send") }}',
        confirmButtonColor: '#008751'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Test Email Sent") }}',
                text: '{{ __("Check your inbox") }}',
                confirmButtonColor: '#008751'
            });
        }
    });
}

function createBackup() {
    Swal.fire({
        title: '{{ __("Creating Backup") }}',
        text: '{{ __("This may take a few minutes...") }}',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("Backup Created") }}',
                    text: '{{ __("Your backup has been created successfully") }}',
                    confirmButtonColor: '#008751'
                });
            }, 3000);
        }
    });
}

function restoreBackup() {
    Swal.fire({
        title: '{{ __("Restore from Backup") }}',
        text: '{{ __("This will replace all current data. Are you sure?") }}',
        icon: 'warning',
        input: 'file',
        showCancelButton: true,
        confirmButtonText: '{{ __("Restore") }}',
        confirmButtonColor: '#d33'
    });
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush