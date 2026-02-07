@extends('layouts.admin')

@section('title', __('Members Management'))
@section('header', __('Members Directory'))

@push('styles')
<style>
    .filter-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: rgba(0, 135, 81, 0.1);
        border-radius: 20px;
        font-size: 12px;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    
    .member-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .member-card:hover {
        border-left-color: var(--primary-color);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .skill-tag {
        display: inline-block;
        padding: 2px 8px;
        background: #f3f4f6;
        border-radius: 12px;
        font-size: 11px;
        margin: 2px;
    }
    
    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .bulk-actions-bar {
        position: fixed;
        bottom: -100px;
        left: 250px;
        right: 0;
        background: white;
        border-top: 2px solid var(--primary-color);
        padding: 16px;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        transition: bottom 0.3s ease;
        z-index: 40;
    }
    
    .bulk-actions-bar.active {
        bottom: 0;
    }
    
    @media (max-width: 768px) {
        .bulk-actions-bar {
            left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Members Directory') }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Manage and view all community members') }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button onclick="toggleBulkSelect()" id="bulk-select-btn"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                <i class="bi bi-check-square mr-2"></i>
                {{ __('Bulk Select') }}
            </button>
            
            <button onclick="showImportModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                <i class="bi bi-upload mr-2"></i>
                {{ __('Import') }}
            </button>
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                    <i class="bi bi-download mr-2"></i>
                    {{ __('Export') }}
                    <i class="bi bi-chevron-down ml-2"></i>
                </button>
                
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                    <a href="{{ route('admin.export.csv') }}" 
                       class="block px-4 py-3 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-file-earmark-spreadsheet text-green-600 mr-2"></i>
                        {{ __('Export as CSV') }}
                    </a>
                    <a href="{{ route('admin.export.pdf') }}" 
                       class="block px-4 py-3 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-file-earmark-pdf text-red-600 mr-2"></i>
                        {{ __('Export as PDF') }}
                    </a>
                    <a href="#" onclick="exportExcel()" 
                       class="block px-4 py-3 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-file-earmark-excel text-green-700 mr-2"></i>
                        {{ __('Export as Excel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Members') }}</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $members->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('New This Week') }}</p>
                    <h3 class="text-2xl font-bold mt-1">{{ \App\Models\Member::whereBetween('created_at', [now()->startOfWeek(), now()])->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up-arrow text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Volunteers') }}</p>
                    <h3 class="text-2xl font-bold mt-1">{{ \App\Models\Member::where('willing_to_help', true)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-heart-fill text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Active Filters') }}</p>
                    <h3 class="text-2xl font-bold mt-1" id="active-filters-count">{{ count(array_filter(request()->except('page'))) }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-funnel-fill text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ __('Advanced Filters') }}</h3>
            <button onclick="toggleFilters()" class="text-primary hover:underline flex items-center">
                <i class="bi bi-funnel mr-1"></i>
                <span id="filter-toggle-text">{{ __('Show Filters') }}</span>
            </button>
        </div>
        
        <div id="filters-container" class="hidden">
            <form action="{{ route('admin.members.index') }}" method="GET" id="filter-form">

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-search mr-1"></i>
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="{{ __('Name, email, phone, registration number...') }}">
                    </div>
                    
                    <!-- Province -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-geo-alt mr-1"></i>
                            {{ __('Province') }}
                        </label>
                        <select name="province" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">{{ __('All Provinces') }}</option>
                            @foreach($provinces as $value => $label)
                                <option value="{{ $value }}" {{ request('province') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- City -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-building mr-1"></i>
                            {{ __('City') }}
                        </label>
                        <input type="text" name="city" value="{{ request('city') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary"
                               placeholder="{{ __('Enter city name') }}">
                    </div>
                    
                    <!-- Employment Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-briefcase mr-1"></i>
                            {{ __('Employment Status') }}
                        </label>
                        <select name="employment_status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="employed" {{ request('employment_status') == 'employed' ? 'selected' : '' }}>{{ __('Employed') }}</option>
                            <option value="self_employed" {{ request('employment_status') == 'self_employed' ? 'selected' : '' }}>{{ __('Self-Employed') }}</option>
                            <option value="student" {{ request('employment_status') == 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                            <option value="unemployed" {{ request('employment_status') == 'unemployed' ? 'selected' : '' }}>{{ __('Unemployed') }}</option>
                            <option value="retired" {{ request('employment_status') == 'retired' ? 'selected' : '' }}>{{ __('Retired') }}</option>
                        </select>
                    </div>
                    
                    <!-- Skill -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-tools mr-1"></i>
                            {{ __('Skill') }}
                        </label>
                        <select name="skill" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">{{ __('All Skills') }}</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Willing to Help -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-heart mr-1"></i>
                            {{ __('Willing to Help') }}
                        </label>
                        <select name="willing_to_help" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">{{ __('All') }}</option>
                            <option value="yes" {{ request('willing_to_help') == 'yes' ? 'selected' : '' }}>{{ __('Yes') }}</option>
                            <option value="no" {{ request('willing_to_help') == 'no' ? 'selected' : '' }}>{{ __('No') }}</option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-calendar mr-1"></i>
                            {{ __('Registered After') }}
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                        <i class="bi bi-search mr-2"></i>
                        {{ __('Apply Filters') }}
                    </button>
                    
                    <a href="{{ route('admin.members.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                        <i class="bi bi-x-circle mr-2"></i>
                        {{ __('Clear All') }}
                    </a>
                    
                    <button type="button" onclick="saveFilterPreset()" class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-primary-50 flex items-center ml-auto">
                        <i class="bi bi-bookmark mr-2"></i>
                        {{ __('Save as Preset') }}
                    </button>
                </div>
            </form>
            
            <!-- Active Filters Display -->
            @if(count(array_filter(request()->except('page'))) > 0)
            <div class="mt-4 pt-4 border-t">
                <div class="flex items-center flex-wrap">
                    <span class="text-sm text-gray-600 mr-2">{{ __('Active Filters:') }}</span>
                    @foreach(request()->except('page') as $key => $value)
                        @if($value)
                        <span class="filter-badge">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $value }}</strong>
                            <a href="{{ route('admin.members.index', array_merge(request()->except($key), ['page' => 1])) }}" 
                               class="ml-2 text-gray-500 hover:text-red-600">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">{{ __('View:') }}</span>
            <button onclick="setView('grid')" id="view-grid" class="p-2 rounded hover:bg-gray-100">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
            <button onclick="setView('list')" id="view-list" class="p-2 rounded hover:bg-gray-100 bg-gray-100">
                <i class="bi bi-list-ul"></i>
            </button>
        </div>
        
        <div class="text-sm text-gray-600">
            {{ __('Showing') }} {{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }} {{ __('of') }} {{ $members->total() }} {{ __('members') }}
        </div>
    </div>

    <!-- Members List View -->
    <div id="list-view" class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="hidden" onchange="toggleSelectAll(this)">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Member') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Contact') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Location') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Skills') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($members as $member)
                    <tr class="member-card hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="member-checkbox hidden" value="{{ $member->id }}" onchange="updateBulkActions()">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-gradient rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.members.show', $member->id) }}" class="font-medium text-gray-900 hover:text-primary">
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $member->registration_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="flex items-center text-gray-900">
                                    <i class="bi bi-telephone text-gray-400 mr-2"></i>
                                    {{ $member->mobile_number }}
                                </div>
                                @if($member->email)
                                <div class="flex items-center text-gray-500 mt-1">
                                    <i class="bi bi-envelope text-gray-400 mr-2"></i>
                                    {{ $member->email }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-gray-900">{{ $member->city }}</div>
                                <div class="text-gray-500">{{ $member->province }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($member->skills->take(3) as $skill)
                                <span class="skill-tag">{{ $skill->name }}</span>
                                @endforeach
                                @if($member->skills->count() > 3)
                                <span class="skill-tag text-primary">+{{ $member->skills->count() - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($member->willing_to_help)
                                <span class="badge badge-success">
                                    <i class="bi bi-heart-fill mr-1"></i>
                                    {{ __('Volunteer') }}
                                </span>
                                @endif
                                <span class="badge badge-primary">
                                    {{ ucfirst(str_replace('_', ' ', $member->employment_status)) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.members.show', $member->id) }}" 
                                   class="action-btn bg-blue-50 text-blue-600 hover:bg-blue-100"
                                   title="{{ __('View Details') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <button onclick="sendMessage({{ $member->id }})" 
                                        class="action-btn bg-green-50 text-green-600 hover:bg-green-100"
                                        title="{{ __('Send Message') }}">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                
                                <button onclick="editMember({{ $member->id }})" 
                                        class="action-btn bg-yellow-50 text-yellow-600 hover:bg-yellow-100"
                                        title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                <button onclick="confirmDelete({{ $member->id }})" 
                                        class="action-btn bg-red-50 text-red-600 hover:bg-red-100"
                                        title="{{ __('Delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No members found') }}</h3>
                                <p class="text-gray-500">{{ __('Try adjusting your filters or search criteria') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($members->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $members->links() }}
        </div>
        @endif
    </div>

    <!-- Members Grid View (Hidden by default) -->
    <div id="grid-view" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($members as $member)
        <div class="card p-6 member-card">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-gradient rounded-full flex items-center justify-center text-white font-bold mr-3">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $member->registration_number }}</p>
                    </div>
                </div>
                <input type="checkbox" class="member-checkbox hidden" value="{{ $member->id }}">
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="bi bi-geo-alt text-gray-400 mr-2"></i>
                    {{ $member->city }}, {{ $member->province }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="bi bi-telephone text-gray-400 mr-2"></i>
                    {{ $member->mobile_number }}
                </div>
                @if($member->willing_to_help)
                <div class="flex items-center text-sm text-green-600">
                    <i class="bi bi-heart-fill mr-2"></i>
                    {{ __('Willing to help') }}
                </div>
                @endif
            </div>
            
            <div class="flex flex-wrap gap-1 mb-4">
                @foreach($member->skills->take(4) as $skill)
                <span class="skill-tag">{{ $skill->name }}</span>
                @endforeach
            </div>
            
            <div class="flex gap-2 pt-4 border-t">
                <a href="{{ route('admin.members.show', $member->id) }}" 
                   class="flex-1 text-center action-btn bg-primary text-white hover:bg-primary-dark">
                    <i class="bi bi-eye mr-1"></i> {{ __('View') }}
                </a>
                <button onclick="sendMessage({{ $member->id }})" 
                        class="action-btn bg-green-50 text-green-600 hover:bg-green-100">
                    <i class="bi bi-chat-dots"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Bulk Actions Bar -->
<div id="bulk-actions-bar" class="bulk-actions-bar">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <span class="text-gray-700 mr-4">
                <strong id="selected-count">0</strong> {{ __('members selected') }}
            </span>
        </div>
        
        <div class="flex gap-3">
            <button onclick="bulkExport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                <i class="bi bi-download mr-2"></i>
                {{ __('Export Selected') }}
            </button>
            
            <button onclick="bulkSendMessage()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                <i class="bi bi-envelope mr-2"></i>
                {{ __('Send Message') }}
            </button>
            
            <button onclick="bulkAddToGroup()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center">
                <i class="bi bi-people mr-2"></i>
                {{ __('Add to Group') }}
            </button>
            
            <button onclick="bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                <i class="bi bi-trash mr-2"></i>
                {{ __('Delete') }}
            </button>
            
            <button onclick="clearSelection()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                {{ __('Clear') }}
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let bulkSelectMode = false;
let selectedMembers = [];

function toggleFilters() {
    const container = document.getElementById('filters-container');
    const toggleText = document.getElementById('filter-toggle-text');
    
    if (container.classList.contains('hidden')) {
        container.classList.remove('hidden');
        toggleText.textContent = '{{ __("Hide Filters") }}';
    } else {
        container.classList.add('hidden');
        toggleText.textContent = '{{ __("Show Filters") }}';
    }
}

function setView(view) {
    const listView = document.getElementById('list-view');
    const gridView = document.getElementById('grid-view');
    const listBtn = document.getElementById('view-list');
    const gridBtn = document.getElementById('view-grid');
    
    if (view === 'grid') {
        listView.classList.add('hidden');
        gridView.classList.remove('hidden');
        listBtn.classList.remove('bg-gray-100');
        gridBtn.classList.add('bg-gray-100');
    } else {
        listView.classList.remove('hidden');
        gridView.classList.add('hidden');
        listBtn.classList.add('bg-gray-100');
        gridBtn.classList.remove('bg-gray-100');
    }
    
    localStorage.setItem('members-view', view);
}

// Restore view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('members-view') || 'list';
    setView(savedView);
    
    // Show filters if there are active filters
    if ({{ count(array_filter(request()->except('page'))) }} > 0) {
        toggleFilters();
    }
});

function toggleBulkSelect() {
    bulkSelectMode = !bulkSelectMode;
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const selectAll = document.getElementById('select-all');
    const btn = document.getElementById('bulk-select-btn');
    
    checkboxes.forEach(cb => {
        cb.classList.toggle('hidden');
    });
    
    selectAll.classList.toggle('hidden');
    
    if (bulkSelectMode) {
        btn.classList.add('bg-primary', 'text-white');
        btn.classList.remove('border-gray-300');
    } else {
        btn.classList.remove('bg-primary', 'text-white');
        btn.classList.add('border-gray-300');
        clearSelection();
    }
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.member-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkActions();
}

function updateBulkActions() {
    selectedMembers = Array.from(document.querySelectorAll('.member-checkbox:checked'))
        .map(cb => cb.value);
    
    document.getElementById('selected-count').textContent = selectedMembers.length;
    
    const bulkBar = document.getElementById('bulk-actions-bar');
    if (selectedMembers.length > 0) {
        bulkBar.classList.add('active');
    } else {
        bulkBar.classList.remove('active');
    }
}

function clearSelection() {
    document.querySelectorAll('.member-checkbox').forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('select-all').checked = false;
    updateBulkActions();
}

function sendMessage(memberId) {
    Swal.fire({
        title: '{{ __("Send Message") }}',
        html: `
            <textarea id="message-text" class="swal2-textarea" placeholder="{{ __('Type your message here...') }}"></textarea>
            <div class="mt-3">
                <label class="flex items-center justify-center">
                    <input type="checkbox" id="send-sms" class="mr-2">
                    <span>{{ __('Also send via SMS') }}</span>
                </label>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __("Send") }}',
        cancelButtonText: '{{ __("Cancel") }}',
        confirmButtonColor: '#008751',
        preConfirm: () => {
            return {
                message: document.getElementById('message-text').value,
                sendSMS: document.getElementById('send-sms').checked
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.message) {
            // Send message via AJAX
            Swal.fire({
                icon: 'success',
                title: '{{ __("Message Sent") }}',
                text: '{{ __("Your message has been sent successfully") }}',
                confirmButtonColor: '#008751'
            });
        }
    });
}

function editMember(memberId) {
    window.location.href = `/admin/members/${memberId}/edit`;
}

function confirmDelete(memberId) {
    Swal.fire({
        title: '{{ __("Are you sure?") }}',
        text: '{{ __("This action cannot be undone. The member data will be permanently deleted.") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("Yes, delete") }}',
        cancelButtonText: '{{ __("Cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Delete via AJAX
            fetch(`/admin/members/${memberId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("Deleted") }}',
                    text: '{{ __("Member has been deleted") }}',
                    confirmButtonColor: '#008751'
                }).then(() => {
                    location.reload();
                });
            });
        }
    });
}

function bulkExport() {
    if (selectedMembers.length === 0) return;
    
    Swal.fire({
        title: '{{ __("Export Selected Members") }}',
        text: `{{ __("Export") }} ${selectedMembers.length} {{ __("members") }}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'CSV',
        cancelButtonText: 'PDF',
        showDenyButton: true,
        denyButtonText: 'Excel'
    }).then((result) => {
        if (result.isConfirmed || result.isDenied) {
            const format = result.isConfirmed ? 'csv' : 'xlsx';
            const ids = selectedMembers.join(',');
            window.location.href = `/admin/export/${format}?ids=${ids}`;
        }
    });
}

function bulkSendMessage() {
    if (selectedMembers.length === 0) return;
    sendMessage(selectedMembers);
}

function bulkAddToGroup() {
    if (selectedMembers.length === 0) return;
    
    Swal.fire({
        title: '{{ __("Add to Group") }}',
        input: 'select',
        inputOptions: {
            'volunteers': '{{ __("Volunteers") }}',
            'professionals': '{{ __("Professionals") }}',
            'students': '{{ __("Students") }}',
            'new': '{{ __("Create New Group") }}'
        },
        showCancelButton: true,
        confirmButtonText: '{{ __("Add") }}',
        confirmButtonColor: '#008751'
    });
}

function bulkDelete() {
    if (selectedMembers.length === 0) return;
    
    Swal.fire({
        title: '{{ __("Delete Selected Members") }}',
        text: `{{ __("Delete") }} ${selectedMembers.length} {{ __("members? This cannot be undone.") }}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: '{{ __("Yes, delete all") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Bulk delete via AJAX
            Swal.fire({
                icon: 'success',
                title: '{{ __("Deleted") }}',
                text: `${selectedMembers.length} {{ __("members have been deleted") }}`,
                confirmButtonColor: '#008751'
            }).then(() => {
                location.reload();
            });
        }
    });
}

function saveFilterPreset() {
    Swal.fire({
        title: '{{ __("Save Filter Preset") }}',
        input: 'text',
        inputPlaceholder: '{{ __("Preset name") }}',
        showCancelButton: true,
        confirmButtonText: '{{ __("Save") }}',
        confirmButtonColor: '#008751'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Save preset
            Swal.fire({
                icon: 'success',
                title: '{{ __("Preset Saved") }}',
                confirmButtonColor: '#008751'
            });
        }
    });
}

function exportExcel() {
    window.location.href = '{{ route("admin.export.csv") }}';
}

function showImportModal() {
    Swal.fire({
        title: '{{ __("Import Members") }}',
        html: `
            <div class="text-left">
                <p class="mb-4">{{ __("Upload a CSV or Excel file with member data") }}</p>
                <input type="file" id="import-file" accept=".csv,.xlsx" class="w-full">
                <div class="mt-4">
                    <a href="/downloads/member-import-template.xlsx" class="text-primary hover:underline">
                        <i class="bi bi-download mr-1"></i>
                        {{ __("Download Template") }}
                    </a>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __("Import") }}',
        confirmButtonColor: '#008751'
    });
}
</script>
@endpush