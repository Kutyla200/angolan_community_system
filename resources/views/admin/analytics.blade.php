@extends('layouts.admin')

@section('title', __('Analytics Dashboard'))
@section('header', __('Analytics & Insights'))

@section('content')
<div class="space-y-6">
    <!-- Analytics Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Community Insights') }}</h3>
                <p class="text-gray-600">{{ __('Detailed analytics and community statistics') }}</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <select id="time-range" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    <option value="7">{{ __('Last 7 Days') }}</option>
                    <option value="30" selected>{{ __('Last 30 Days') }}</option>
                    <option value="90">{{ __('Last 90 Days') }}</option>
                    <option value="365">{{ __('Last Year') }}</option>
                </select>
                
                <button onclick="exportData('csv')" 
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                    <i class="bi bi-download mr-2"></i>
                    {{ __('Export CSV') }}
                </button>
                
                <button onclick="exportData('pdf')" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                    <i class="bi bi-file-earmark-pdf mr-2"></i>
                    {{ __('Export Report') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Members') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_members']) }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="bi bi-arrow-up mr-1"></i>
                        +{{ $stats['today'] }} {{ __('today') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Active This Week') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['thisWeek']) }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ __('new registrations') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up-arrow text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Willing to Help') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['willingToHelp']) }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ __('community volunteers') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-heart-fill text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Avg. Completion') }}</p>
                    <h3 class="text-3xl font-bold mt-2">98%</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ __('registration rate') }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Registration Trends -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Registration Trends') }}</h3>
            <canvas id="registrationChart" height="250"></canvas>
        </div>
        
        <!-- Province Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Members by Province') }}</h3>
            <canvas id="provinceChart" height="250"></canvas>
        </div>
        
        <!-- Skills Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Top Skills') }}</h3>
            <canvas id="skillChart" height="250"></canvas>
        </div>
        
        <!-- Age Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Age Distribution') }}</h3>
            <canvas id="ageChart" height="250"></canvas>
        </div>
    </div>
    
    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Employment Status -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Employment Status') }}</h3>
            <div class="space-y-4">
                @foreach($analyticsData['employment_stats'] as $stat)
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ __(ucfirst(str_replace('_', ' ', $stat->status))) }}</span>
                    <div class="flex items-center">
                        <span class="font-semibold mr-3">{{ $stat->count }}</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" 
                                 style="width: {{ ($stat->count / $stats['total'] * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Gender Distribution -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Gender Distribution') }}</h3>
            <canvas id="genderChart" height="200"></canvas>
        </div>
        
        <!-- Top Cities -->
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold mb-6">{{ __('Top Cities') }}</h3>
            <div class="space-y-3">
                @foreach($analyticsData['top_cities'] as $city)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="bi bi-geo-alt text-primary mr-3"></i>
                        <div>
                            <p class="font-medium">{{ $city->city }}</p>
                            <p class="text-sm text-gray-600">{{ $city->province }}</p>
                        </div>
                    </div>
                    <span class="font-bold">{{ $city->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Export Reports -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Generate Reports') }}</h3>
                <p class="text-gray-600">{{ __('Create comprehensive community reports') }}</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <button onclick="generateReport('member-list')" 
                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                    <i class="bi bi-people mr-2"></i>
                    {{ __('Member Directory') }}
                </button>
                
                <button onclick="generateReport('skills-report')" 
                        class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center">
                    <i class="bi bi-tools mr-2"></i>
                    {{ __('Skills Inventory') }}
                </button>
                
                <button onclick="generateReport('community-report')" 
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark flex items-center">
                    <i class="bi bi-file-text mr-2"></i>
                    {{ __('Full Community Report') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registration Trends Chart
    const regCtx = document.getElementById('registrationChart').getContext('2d');
    const regChart = new Chart(regCtx, {
        type: 'line',
        data: {
            labels: @json($analyticsData['registration_trends']->pluck('date')),
            datasets: [{
                label: '{{ __("Registrations") }}',
                data: @json($analyticsData['registration_trends']->pluck('count')),
                borderColor: '#008751',
                backgroundColor: 'rgba(0, 135, 81, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Province Distribution Chart
    const provinceCtx = document.getElementById('provinceChart').getContext('2d');
    const provinceChart = new Chart(provinceCtx, {
        type: 'bar',
        data: {
            labels: @json($provinceDistribution->pluck('province')),
            datasets: [{
                label: '{{ __("Members") }}',
                data: @json($provinceDistribution->pluck('count')),
                backgroundColor: [
                    '#008751', '#CC092F', '#FFD100', '#1a1a2e',
                    '#4361ee', '#7209b7', '#f72585', '#4cc9f0', '#4895ef'
                ],
                borderColor: '#ffffff',
                borderWidth: 1
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Skill Distribution Chart
    const skillCtx = document.getElementById('skillChart').getContext('2d');
    const skillChart = new Chart(skillCtx, {
        type: 'doughnut',
        data: {
            labels: @json($skillDistribution->pluck('name_' . app()->getLocale())),
            datasets: [{
                data: @json($skillDistribution->pluck('count')),
                backgroundColor: [
                    '#008751', '#CC092F', '#FFD100', '#1a1a2e',
                    '#4361ee', '#7209b7', '#f72585', '#4cc9f0',
                    '#4895ef', '#560bad'
                ]
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
    
    // Age Distribution Chart
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    const ageChart = new Chart(ageCtx, {
        type: 'pie',
        data: {
            labels: @json($analyticsData['age_distribution']->pluck('age_group')),
            datasets: [{
                data: @json($analyticsData['age_distribution']->pluck('count')),
                backgroundColor: [
                    '#008751', '#CC092F', '#FFD100', '#1a1a2e',
                    '#4361ee', '#7209b7'
                ]
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
    
    // Gender Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderData = {
        labels: ['Male', 'Female', 'Other', 'Prefer not to say'],
        datasets: [{
            data: [
                {{ $analyticsData['gender_stats']['male'] ?? 0 }},
                {{ $analyticsData['gender_stats']['female'] ?? 0 }},
                {{ $analyticsData['gender_stats']['other'] ?? 0 }},
                {{ $analyticsData['gender_stats']['prefer_not_to_say'] ?? 0 }}
            ],
            backgroundColor: ['#008751', '#CC092F', '#FFD100', '#1a1a2e']
        }]
    };
    
    new Chart(genderCtx, {
        type: 'doughnut',
        data: genderData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Time range filter
    document.getElementById('time-range').addEventListener('change', function() {
        const days = this.value;
        // Here you would typically make an AJAX request to update the charts
        // For now, we'll just reload the page with the new filter
        window.location.href = '{{ route("admin.analytics") }}?days=' + days;
    });
});

function generateReport(type) {
    Swal.fire({
        title: '{{ __("Generating Report") }}',
        text: '{{ __("Please wait while we generate your report...") }}',
        icon: 'info',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            // Simulate API call
            setTimeout(() => {
                Swal.fire({
                    title: '{{ __("Report Ready") }}',
                    text: '{{ __("Your report has been generated successfully.") }}',
                    icon: 'success',
                    confirmButtonText: '{{ __("Download") }}',
                    confirmButtonColor: '#008751'
                }).then(() => {
                    // Trigger download
                    window.location.href = `/admin/reports/${type}/download`;
                });
            }, 2000);
        }
    });
}
</script>
@endsection