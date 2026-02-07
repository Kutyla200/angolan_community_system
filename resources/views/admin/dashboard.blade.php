@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }

    .recent-activity-item {
        border-left: 3px solid var(--primary-color);
        padding-left: 16px;
        margin-bottom: 16px;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background-color: rgba(0, 135, 81, 0.1);
        color: var(--primary-color);
    }

    .badge-info {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Total Members') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['total_members']) }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="bi bi-arrow-up mr-1"></i>
                        {{ __('+') . $stats['today'] }} {{ __('today') }}
                    </p>
                </div>
                <div class="stat-icon bg-blue-50 text-blue-600">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('This Week') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ number_format($stats['this_week']) }}</h3>

                    <p class="text-sm text-gray-600 mt-2">{{ __('new registrations') }}</p>
                </div>
                <div class="stat-icon bg-green-50 text-green-600">
                    <i class="bi bi-person-plus"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Willing to Help') }}</p>
                    <h3 class="text-3xl font-bold mt-2">
                        {{ number_format($stats['willing_to_help']) }}
                    </h3>

                    <p class="text-sm text-gray-600 mt-2">{{ __('community volunteers') }}</p>
                </div>
                <div class="stat-icon bg-yellow-50 text-yellow-600">
                    <i class="bi bi-heart-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">{{ __('Active Provinces') }}</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $provinceStats->count() }}
</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ __('across South Africa') }}</p>
                </div>
                <div class="stat-icon bg-purple-50 text-purple-600">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Province Distribution -->
        <div class="chart-container">
            <h3 class="text-lg font-bold mb-6">{{ __('Members by Province') }}</h3>
            <canvas id="provinceChart" height="250"></canvas>
        </div>

        <!-- Skill Distribution -->
        <div class="chart-container">
            <h3 class="text-lg font-bold mb-6">{{ __('Top Skills') }}</h3>
            <canvas id="skillChart" height="250"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="chart-container lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold">{{ __('Recent Registrations') }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-primary hover:underline">
                    {{ __('View All') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 text-left">{{ __('Name') }}</th>
                            <th class="py-3 text-left">{{ __('Location') }}</th>
                            <th class="py-3 text-left">{{ __('Registered') }}</th>
                            <th class="py-3 text-left">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentMembers as $member)

                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">
                                <a href="{{ route('admin.members.show', $member->id) }}"
                                    class="text-primary hover:underline">
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </a>
                            </td>
                            <td class="py-3">
                                {{ $member->city }}, {{ $member->province }}
                            </td>
                            <td class="py-3">
                                {{ $member->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3">
                                <span class="badge badge-success">
                                    {{ __('Active') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="chart-container">
            <h3 class="text-lg font-bold mb-6">{{ __('Quick Actions') }}</h3>
            <div class="space-y-4">
                <a href="{{ route('admin.members.index') }}"
                    class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ __('Search Members') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Find members by skills or location') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.analytics') }}"
                    class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ __('View Analytics') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Detailed community insights') }}</p>
                    </div>
                </a>

                <a href="#" onclick="exportData()"
                    class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="bi bi-download"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ __('Export Data') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Download reports in CSV/PDF') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ===================== PROVINCE CHART =====================
        const provinceCtx = document.getElementById('provinceChart').getContext('2d');

        const provinceChart = new Chart(provinceCtx, {
            type: 'bar',
            data: {
                labels: @json($provinceStats->pluck('province')),
                datasets: [{
                    label: '{{ __("Members") }}',
                    data: @json($provinceStats->pluck('count')),
                    backgroundColor: 'rgba(0, 135, 81, 0.8)',
                    borderColor: 'rgba(0, 135, 81, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });


        // ===================== SKILL CHART =====================
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
                    legend: { position: 'right' }
                }
            }
        });

    });


    // ===================== EXPORT FUNCTION =====================
    function exportData() {
        Swal.fire({
            title: '{{ __("Export Data") }}',
            text: '{{ __("Select export format") }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'CSV',
            cancelButtonText: 'PDF',
            showDenyButton: true,
            denyButtonText: '{{ __("Cancel") }}',
            confirmButtonColor: '#008751',
            cancelButtonColor: '#CC092F'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("admin.export.csv") }}';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = '{{ route("admin.export.pdf") }}';
            }
        });
    }
</script>

@endsection