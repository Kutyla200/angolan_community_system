@extends('layouts.admin')

@section('title', __('Audit Logs'))
@section('header', __('System Audit Logs'))

@push('styles')
<style>
    .log-entry {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .log-entry:hover {
        border-left-color: var(--primary-color);
        transform: translateX(4px);
        background-color: #f9fafb;
    }
    
    .log-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-center;
        font-size: 18px;
    }
    
    .log-create { background: #dff0d8; color: #3c763d; }
    .log-update { background: #d9edf7; color: #31708f; }
    .log-delete { background: #f2dede; color: #a94442; }
    .log-login { background: #fcf8e3; color: #8a6d3b; }
    .log-export { background: #e7e7ff; color: #5a5aa0; }
    
    .filter-chip {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .filter-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .timeline-line {
        position: absolute;
        left: 19px;
        top: 50px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Logs') }}</p>
                    <h3 class="text-2xl font-bold mt-1">1,248</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-journal-text text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Today') }}</p>
                    <h3 class="text-2xl font-bold mt-1">47</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-calendar-check text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Failed Logins') }}</p>
                    <h3 class="text-2xl font-bold mt-1 text-red-600">3</h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-shield-exclamation text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Active Users') }}</p>
                    <h3 class="text-2xl font-bold mt-1">5</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card p-6">
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" id="search-logs" placeholder="{{ __('Search logs...') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            
            <!-- Date Range -->
            <div class="flex gap-2">
                <input type="date" id="date-from" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                <input type="date" id="date-to" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
            </div>
            
            <!-- Export -->
            <button onclick="exportLogs()" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                <i class="bi bi-download mr-2"></i>
                {{ __('Export') }}
            </button>
        </div>
        
        <!-- Quick Filters -->
        <div class="flex flex-wrap gap-2">
            <span class="text-sm text-gray-600 mr-2">{{ __('Quick Filters:') }}</span>
            <button onclick="filterLogs('all')" class="filter-chip bg-gray-100 hover:bg-gray-200 text-gray-700">
                {{ __('All') }}
            </button>
            <button onclick="filterLogs('login')" class="filter-chip bg-yellow-100 hover:bg-yellow-200 text-yellow-700">
                <i class="bi bi-box-arrow-in-right mr-1"></i>
                {{ __('Logins') }}
            </button>
            <button onclick="filterLogs('create')" class="filter-chip bg-green-100 hover:bg-green-200 text-green-700">
                <i class="bi bi-plus-circle mr-1"></i>
                {{ __('Created') }}
            </button>
            <button onclick="filterLogs('update')" class="filter-chip bg-blue-100 hover:bg-blue-200 text-blue-700">
                <i class="bi bi-pencil mr-1"></i>
                {{ __('Updated') }}
            </button>
            <button onclick="filterLogs('delete')" class="filter-chip bg-red-100 hover:bg-red-200 text-red-700">
                <i class="bi bi-trash mr-1"></i>
                {{ __('Deleted') }}
            </button>
            <button onclick="filterLogs('export')" class="filter-chip bg-purple-100 hover:bg-purple-200 text-purple-700">
                <i class="bi bi-download mr-1"></i>
                {{ __('Exports') }}
            </button>
        </div>
    </div>

    <!-- Logs Timeline -->
    <div class="card p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="bi bi-clock-history text-primary mr-3"></i>
            {{ __('Activity Timeline') }}
        </h2>
        
        <div class="space-y-4 relative">
            <!-- Sample Log Entries -->
            @php
                $sampleLogs = [
                    [
                        'action' => 'export',
                        'user' => 'Admin User',
                        'description' => 'Exported members data to CSV',
                        'time' => '2 minutes ago',
                        'ip' => '197.189.45.22',
                        'details' => ['format' => 'CSV', 'records' => 523]
                    ],
                    [
                        'action' => 'update',
                        'user' => 'João Silva',
                        'description' => 'Updated member profile',
                        'time' => '15 minutes ago',
                        'ip' => '41.191.234.156',
                        'details' => ['member' => 'Maria Santos', 'fields' => ['email', 'phone']]
                    ],
                    [
                        'action' => 'create',
                        'user' => 'Admin User',
                        'description' => 'Created new admin account',
                        'time' => '1 hour ago',
                        'ip' => '197.189.45.22',
                        'details' => ['role' => 'Coordinator', 'province' => 'Gauteng']
                    ],
                    [
                        'action' => 'login',
                        'user' => 'Admin User',
                        'description' => 'Admin logged in',
                        'time' => '2 hours ago',
                        'ip' => '197.189.45.22',
                        'details' => ['device' => 'Chrome on Windows']
                    ],
                    [
                        'action' => 'delete',
                        'user' => 'João Silva',
                        'description' => 'Deleted member record',
                        'time' => '3 hours ago',
                        'ip' => '41.191.234.156',
                        'details' => ['member' => 'Pedro Costa', 'reason' => 'Duplicate entry']
                    ],
                    [
                        'action' => 'update',
                        'user' => 'Admin User',
                        'description' => 'Updated system settings',
                        'time' => '5 hours ago',
                        'ip' => '197.189.45.22',
                        'details' => ['setting' => 'Email notifications', 'value' => 'Enabled']
                    ],
                    [
                        'action' => 'export',
                        'user' => 'Maria Fernandes',
                        'description' => 'Generated analytics report',
                        'time' => '6 hours ago',
                        'ip' => '102.165.89.41',
                        'details' => ['format' => 'PDF', 'type' => 'Monthly Report']
                    ],
                    [
                        'action' => 'create',
                        'user' => 'Admin User',
                        'description' => 'Added new skill category',
                        'time' => '1 day ago',
                        'ip' => '197.189.45.22',
                        'details' => ['category' => 'Healthcare', 'skills' => 5]
                    ]
                ];
            @endphp
            
            <div class="timeline-line hidden md:block"></div>
            
            @foreach($sampleLogs as $index => $log)
            <div class="log-entry card p-4 relative">
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="log-icon log-{{ $log['action'] }} flex-shrink-0">
                        @switch($log['action'])
                            @case('create')
                                <i class="bi bi-plus-circle"></i>
                                @break
                            @case('update')
                                <i class="bi bi-pencil-square"></i>
                                @break
                            @case('delete')
                                <i class="bi bi-trash"></i>
                                @break
                            @case('login')
                                <i class="bi bi-box-arrow-in-right"></i>
                                @break
                            @case('export')
                                <i class="bi bi-download"></i>
                                @break
                        @endswitch
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $log['description'] }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <i class="bi bi-person mr-1"></i>
                                        {{ $log['user'] }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="bi bi-clock mr-1"></i>
                                        {{ $log['time'] }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="bi bi-geo-alt mr-1"></i>
                                        {{ $log['ip'] }}
                                    </span>
                                </div>
                            </div>
                            
                            <button onclick="viewLogDetails({{ $index }})" class="px-3 py-1 text-sm text-primary hover:bg-primary-50 rounded">
                                {{ __('Details') }}
                            </button>
                        </div>
                        
                        <!-- Additional Details (Collapsed by default) -->
                        <div id="log-details-{{ $index }}" class="hidden mt-3 p-3 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Additional Information') }}</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                @foreach($log['details'] as $key => $value)
                                <div>
                                    <span class="text-gray-600">{{ ucfirst($key) }}:</span>
                                    <span class="font-medium text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-gray-600">{{ __('Showing 1-8 of 1,248 entries') }}</p>
            <div class="flex gap-2">
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-50">{{ __('Previous') }}</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-50">1</button>
                <button class="px-4 py-2 bg-primary text-white rounded-lg">2</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-50">3</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-50">{{ __('Next') }}</button>
            </div>
        </div>
    </div>

    <!-- Log Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Action Distribution -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Action Distribution') }}</h3>
            <canvas id="actionChart" height="200"></canvas>
        </div>
        
        <!-- Activity Timeline Chart -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Activity Over Time') }}</h3>
            <canvas id="timelineChart" height="200"></canvas>
        </div>
    </div>

    <!-- Top Administrators -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Most Active Administrators') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Admin') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Role') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Last Active') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-gradient rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    AU
                                </div>
                                <span class="font-medium">Admin User</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge badge-primary">Super Admin</span>
                        </td>
                        <td class="px-4 py-3 font-medium">348</td>
                        <td class="px-4 py-3 text-gray-600">2 minutes ago</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-secondary-gradient rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    JS
                                </div>
                                <span class="font-medium">João Silva</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge badge-success">Coordinator</span>
                        </td>
                        <td class="px-4 py-3 font-medium">187</td>
                        <td class="px-4 py-3 text-gray-600">15 minutes ago</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center text-dark font-bold mr-3">
                                    MF
                                </div>
                                <span class="font-medium">Maria Fernandes</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge badge-warning">Coordinator</span>
                        </td>
                        <td class="px-4 py-3 font-medium">145</td>
                        <td class="px-4 py-3 text-gray-600">6 hours ago</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Alerts -->
    <div class="card p-6 border-yellow-200">
        <h3 class="text-lg font-bold text-yellow-900 mb-4 flex items-center">
            <i class="bi bi-exclamation-triangle text-yellow-600 mr-2"></i>
            {{ __('Security Alerts') }}
        </h3>
        
        <div class="space-y-3">
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <i class="bi bi-shield-exclamation text-yellow-600 mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-medium text-yellow-900">{{ __('Failed Login Attempts') }}</h4>
                            <p class="text-sm text-yellow-700 mt-1">3 {{ __('failed attempts from IP') }} 41.85.123.45</p>
                            <p class="text-xs text-yellow-600 mt-1">{{ __('Last attempt: 30 minutes ago') }}</p>
                        </div>
                    </div>
                    <button class="px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        {{ __('Block IP') }}
                    </button>
                </div>
            </div>
            
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="bi bi-info-circle text-blue-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">{{ __('Large Data Export') }}</h4>
                        <p class="text-sm text-blue-700 mt-1">{{ __('Admin exported 1,000+ member records') }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ __('Exported by: Admin User • 2 hours ago') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function viewLogDetails(index) {
    const detailsDiv = document.getElementById(`log-details-${index}`);
    detailsDiv.classList.toggle('hidden');
}

function filterLogs(type) {
    console.log('Filtering logs by:', type);
    // Implement actual filtering logic
    Swal.fire({
        icon: 'info',
        title: `{{ __("Filtering by") }} ${type}`,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });
}

function exportLogs() {
    Swal.fire({
        title: '{{ __("Export Audit Logs") }}',
        text: '{{ __("Choose export format") }}',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'CSV',
        denyButtonText: 'PDF',
        cancelButtonText: 'JSON',
        confirmButtonColor: '#008751'
    }).then((result) => {
        if (result.isConfirmed || result.isDenied || result.dismiss === Swal.DismissReason.cancel) {
            const format = result.isConfirmed ? 'CSV' : (result.isDenied ? 'PDF' : 'JSON');
            Swal.fire({
                icon: 'success',
                title: `{{ __("Exporting as") }} ${format}`,
                text: '{{ __("Your file will download shortly") }}',
                confirmButtonColor: '#008751'
            });
        }
    });
}

// Charts
document.addEventListener('DOMContentLoaded', function() {
    // Action Distribution Chart
    const actionCtx = document.getElementById('actionChart').getContext('2d');
    new Chart(actionCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Created") }}', '{{ __("Updated") }}', '{{ __("Deleted") }}', '{{ __("Logins") }}', '{{ __("Exports") }}'],
            datasets: [{
                data: [245, 432, 87, 356, 128],
                backgroundColor: ['#22c55e', '#3b82f6', '#ef4444', '#f59e0b', '#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: '{{ __("Actions") }}',
                data: [45, 52, 48, 73, 65, 38, 42],
                borderColor: '#008751',
                backgroundColor: 'rgba(0, 135, 81, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Real-time search
    document.getElementById('search-logs').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.log-entry').forEach(entry => {
            const text = entry.textContent.toLowerCase();
            entry.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
});
</script>
@endpush