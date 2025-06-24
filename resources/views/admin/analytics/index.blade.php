@extends('layouts.admin')

@section('active-analytics', 'menu-item-active')
@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6 bg-gray-900 min-h-screen">
    <!-- Header -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
                <p class="text-gray-400 mt-1">Comprehensive overview of platform metrics and user behavior</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-sm text-gray-500">
                    Last updated: {{ now()->format('M d, Y H:i') }}
                </div>
                <button onclick="location.reload()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <!-- Users Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Users</p>
                    <p class="text-2xl font-bold">{{ number_format($usersData['total']) }}</p>
                    <p class="text-blue-100 text-xs mt-1">{{ number_format($usersData['verified']) }} verified</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-200"></i>
            </div>
        </div>

        <!-- Properties Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Properties</p>
                    <p class="text-2xl font-bold">{{ number_format($propertiesData['total']) }}</p>
                    <p class="text-green-100 text-xs mt-1">{{ number_format($propertiesData['approved']) }} approved</p>
                </div>
                <i class="fas fa-building text-3xl text-green-200"></i>
            </div>
        </div>

        <!-- Payments Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Payments</p>
                    <p class="text-2xl font-bold">{{ number_format($paymentsData['total']) }}</p>
                    <p class="text-purple-100 text-xs mt-1">${{ number_format($paymentsData['totalRevenue'], 2) }}</p>
                </div>
                <i class="fas fa-credit-card text-3xl text-purple-200"></i>
            </div>
        </div>

        <!-- Review Invitations Card -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Review Invites</p>
                    <p class="text-2xl font-bold">{{ number_format($reviewInvitationsData['total']) }}</p>
                    <p class="text-orange-100 text-xs mt-1">{{ number_format($reviewInvitationsData['sent']) }} sent</p>
                </div>
                <i class="fas fa-envelope text-3xl text-orange-200"></i>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm">Products</p>
                    <p class="text-2xl font-bold">{{ number_format($productsData['total']) }}</p>
                    <p class="text-indigo-100 text-xs mt-1">Active products</p>
                </div>
                <i class="fas fa-box text-3xl text-indigo-200"></i>
            </div>
        </div>

        <!-- Ads Card -->
        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-sm">Advertisements</p>
                    <p class="text-2xl font-bold">{{ number_format($adsData['total']) }}</p>
                    <p class="text-pink-100 text-xs mt-1">{{ number_format($adsData['active']) }} active</p>
                </div>
                <i class="fas fa-ad text-3xl text-pink-200"></i>
            </div>
        </div>
    </div>    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Users Registration Chart -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Daily User Registrations (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Properties Chart -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Daily Property Additions (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="propertiesChart"></canvas>
            </div>
        </div>

        <!-- Payments Revenue Chart -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Monthly Revenue (Last 12 Months)</h3>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Review Invitations Chart -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Daily Review Invitations (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="reviewInvitationsChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Status Distribution Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- User Status Distribution -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">User Status Distribution</h3>
            <div class="h-64">
                <canvas id="userStatusChart"></canvas>
            </div>
        </div>

        <!-- Property Status Distribution -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Property Status Distribution</h3>
            <div class="h-64">
                <canvas id="propertyStatusChart"></canvas>
            </div>
        </div>

        <!-- Payment Status Distribution -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Payment Status Distribution</h3>
            <div class="h-64">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Additional Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Products Daily Additions -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Daily Product Additions (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="productsChart"></canvas>
            </div>
        </div>

        <!-- Ads Daily Creations -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Daily Ad Creations (Last 30 Days)</h3>
            <div class="h-80">
                <canvas id="adsChart"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                }
            },
            x: {
                grid: {
                    color: '#f1f5f9'
                }
            }
        }
    };

    // Users Registration Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($usersData['dailyRegistrations']->pluck('date')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($usersData['dailyRegistrations']->pluck('count')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: commonOptions
    });

    // Properties Chart
    const propertiesCtx = document.getElementById('propertiesChart').getContext('2d');
    new Chart(propertiesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($propertiesData['dailyAdditions']->pluck('date')) !!},
            datasets: [{
                label: 'New Properties',
                data: {!! json_encode($propertiesData['dailyAdditions']->pluck('count')) !!},
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: commonOptions
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($paymentsData['monthlyRevenue']->pluck('month')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($paymentsData['monthlyRevenue']->pluck('amount')) !!},
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Review Invitations Chart
    const reviewInvitationsCtx = document.getElementById('reviewInvitationsChart').getContext('2d');
    new Chart(reviewInvitationsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($reviewInvitationsData['dailyInvitations']->pluck('date')) !!},
            datasets: [{
                label: 'Invitations Sent',
                data: {!! json_encode($reviewInvitationsData['dailyInvitations']->pluck('count')) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: commonOptions
    });

    // User Status Pie Chart
    const userStatusCtx = document.getElementById('userStatusChart').getContext('2d');
    new Chart(userStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($usersData['statusDistribution']->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($usersData['statusDistribution']->pluck('count')) !!},
                backgroundColor: ['#3b82f6', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Property Status Pie Chart
    const propertyStatusCtx = document.getElementById('propertyStatusChart').getContext('2d');
    new Chart(propertyStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($propertiesData['statusDistribution']->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($propertiesData['statusDistribution']->pluck('count')) !!},
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Payment Status Pie Chart
    const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    new Chart(paymentStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentsData['statusDistribution']->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($paymentsData['statusDistribution']->pluck('count')) !!},
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($productsData['dailyAdditions']->pluck('date')) !!},
            datasets: [{
                label: 'New Products',
                data: {!! json_encode($productsData['dailyAdditions']->pluck('count')) !!},
                backgroundColor: '#6366f1',
                borderColor: '#4f46e5',
                borderWidth: 1
            }]
        },
        options: commonOptions
    });

    // Ads Chart
    const adsCtx = document.getElementById('adsChart').getContext('2d');
    new Chart(adsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($adsData['dailyCreated']->pluck('date')) !!},
            datasets: [{
                label: 'New Ads',
                data: {!! json_encode($adsData['dailyCreated']->pluck('count')) !!},
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: commonOptions
    });
});
</script>
@endpush

@endsection
