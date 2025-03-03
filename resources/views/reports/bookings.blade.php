@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bookings Report</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
            </a>
            <a href="{{ route('reports.export', 'bookings') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-sm btn-primary shadow-sm">
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
            <form method="GET" action="{{ route('reports.bookings') }}" class="row g-3 align-items-end">
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

    <!-- Booking Overview Cards -->
    <div class="row">
        <!-- Total Bookings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBookings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmed Bookings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Confirmed Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookingsByStatusTotal['confirmed'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checked In Bookings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Checked In</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookingsByStatusTotal['checked_in'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled Bookings Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Cancelled Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookingsByStatusTotal['cancelled'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Chart Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Booking Trends</h6>
        </div>
        <div class="card-body">
            <div class="chart-area">
                <canvas id="bookingsChart" style="min-height: 300px;"></canvas>
            </div>
            <hr>
            <div class="text-center small mt-3">
                <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i> Total Bookings
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-success"></i> Confirmed
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-info"></i> Checked In
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-warning"></i> Checked Out
                </span>
                <span class="mr-2">
                    <i class="fas fa-circle text-danger"></i> Cancelled
                </span>
            </div>
        </div>
    </div>

    <!-- Booking Status Distribution Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Booking Status Distribution</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-pie pt-4">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Booking Status</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $statuses = [
                                        'confirmed' => 'Confirmed',
                                        'checked_in' => 'Checked In',
                                        'checked_out' => 'Checked Out',
                                        'cancelled' => 'Cancelled'
                                    ];
                                @endphp
                                
                                @foreach($statuses as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $bookingsByStatusTotal[$key] ?? 0 }}</td>
                                    <td>
                                        @php
                                            $percentage = $totalBookings > 0 
                                                ? (($bookingsByStatusTotal[$key] ?? 0) / $totalBookings) * 100 
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
    const bookingsByStatus = @json($bookingsByStatus);
    
    // Calculate total bookings per day
    const totalBookings = dates.map((date, i) => {
        return Object.values(bookingsByStatus).reduce((sum, statusData) => sum + statusData[i], 0);
    });
    
    // Create line chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bookingsChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Total Bookings',
                        data: totalBookings,
                        backgroundColor: 'transparent',
                        borderColor: '#4e73df',
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4e73df',
                        borderWidth: 3
                    },
                    {
                        label: 'Confirmed',
                        data: bookingsByStatus.confirmed,
                        backgroundColor: 'transparent',
                        borderColor: '#1cc88a',
                        pointBackgroundColor: '#1cc88a',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#1cc88a',
                        borderWidth: 2
                    },
                    {
                        label: 'Checked In',
                        data: bookingsByStatus.checked_in,
                        backgroundColor: 'transparent',
                        borderColor: '#36b9cc',
                        pointBackgroundColor: '#36b9cc',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#36b9cc',
                        borderWidth: 2
                    },
                    {
                        label: 'Checked Out',
                        data: bookingsByStatus.checked_out,
                        backgroundColor: 'transparent',
                        borderColor: '#f6c23e',
                        pointBackgroundColor: '#f6c23e',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#f6c23e',
                        borderWidth: 2
                    },
                    {
                        label: 'Cancelled',
                        data: bookingsByStatus.cancelled,
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
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
        
        // Create pie chart for booking status distribution
        const bookingStatusData = @json($bookingsByStatusTotal);
        const pieCtx = document.getElementById('bookingStatusChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Checked In', 'Checked Out', 'Cancelled'],
                datasets: [{
                    data: [
                        bookingStatusData.confirmed || 0,
                        bookingStatusData.checked_in || 0,
                        bookingStatusData.checked_out || 0,
                        bookingStatusData.cancelled || 0
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
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 