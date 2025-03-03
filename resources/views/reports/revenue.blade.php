@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Revenue Report</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
            </a>
            <a href="{{ route('reports.export', 'revenue') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Report
            </a>
        </div>
    </div>

    <!-- Date Range Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter by Date Range</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.revenue') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revenue Overview Cards -->
    <div class="row">
        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Card Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Credit Card Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($revenueByPaymentMethod['credit_card'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cash Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($revenueByPaymentMethod['cash'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Other Payment Methods</div>
                            @php
                                $otherRevenue = ($revenueByPaymentMethod['bank_transfer'] ?? 0) + ($revenueByPaymentMethod['paypal'] ?? 0);
                            @endphp
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($otherRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Revenue Trend</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="revenueChart" style="min-height: 300px;"></canvas>
            </div>
            <hr>
            <div class="text-center small mt-3">
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> Total Revenue
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-success"></i> Credit Card
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-info"></i> Cash
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-warning"></i> Bank Transfer
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-danger"></i> PayPal
                </span>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Method Distribution</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-pie pt-4">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $methods = [
                                        'credit_card' => 'Credit Card',
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                        'paypal' => 'PayPal'
                                    ];
                                @endphp
                                
                                @foreach($methods as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>${{ number_format($revenueByPaymentMethod[$key] ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $percentage = $totalRevenue > 0 
                                                ? (($revenueByPaymentMethod[$key] ?? 0) / $totalRevenue) * 100 
                                                : 0;
                                        @endphp
                                        {{ number_format($percentage, 1) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart data
    const dates = @json($dates);
    const revenueByMethod = @json($revenueByMethod);
    
    // Calculate total revenue per day
    const totalRevenue = dates.map((date, i) => {
        return Object.values(revenueByMethod).reduce((sum, methodData) => sum + methodData[i], 0);
    });
    
    // Create line chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Total Revenue',
                        data: totalRevenue,
                        backgroundColor: 'transparent',
                        borderColor: '#4e73df',
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4e73df',
                        borderWidth: 3
                    },
                    {
                        label: 'Credit Card',
                        data: revenueByMethod.credit_card,
                        backgroundColor: 'transparent',
                        borderColor: '#1cc88a',
                        pointBackgroundColor: '#1cc88a',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#1cc88a',
                        borderWidth: 2
                    },
                    {
                        label: 'Cash',
                        data: revenueByMethod.cash,
                        backgroundColor: 'transparent',
                        borderColor: '#36b9cc',
                        pointBackgroundColor: '#36b9cc',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#36b9cc',
                        borderWidth: 2
                    },
                    {
                        label: 'Bank Transfer',
                        data: revenueByMethod.bank_transfer,
                        backgroundColor: 'transparent',
                        borderColor: '#f6c23e',
                        pointBackgroundColor: '#f6c23e',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#f6c23e',
                        borderWidth: 2
                    },
                    {
                        label: 'PayPal',
                        data: revenueByMethod.paypal,
                        backgroundColor: 'transparent',
                        borderColor: '#e74a3b',
                        pointBackgroundColor: '#e74a3b',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#e74a3b',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Revenue ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.raw.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
        
        // Create pie chart for payment method distribution
        const paymentMethodData = @json($revenueByPaymentMethod);
        const pieCtx = document.getElementById('paymentMethodChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Credit Card', 'Cash', 'Bank Transfer', 'PayPal'],
                datasets: [{
                    data: [
                        paymentMethodData.credit_card || 0,
                        paymentMethodData.cash || 0,
                        paymentMethodData.bank_transfer || 0,
                        paymentMethodData.paypal || 0
                    ],
                    backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return label + ': $' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 