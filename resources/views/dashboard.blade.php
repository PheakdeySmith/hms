@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="{{ route('reports.export', ['type' => 'all']) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Available Rooms Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Available Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Occupied Rooms Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Occupied Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $occupiedRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Check-ins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Check-ins
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCheckIns }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Today's Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($todayRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Room Status Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Room Occupancy</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="roomStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="revenueSourcesChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Direct
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Online
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Travel Agents
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Ref</th>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('bookings.show', $booking->id) }}">
                                            {{ $booking->booking_reference }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</td>
                                    <td>Room {{ $booking->room->room_number }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($booking->status == 'confirmed')
                                            <span class="badge bg-warning text-dark">Confirmed</span>
                                        @elseif($booking->status == 'checked_in')
                                            <span class="badge bg-success">Checked In</span>
                                        @elseif($booking->status == 'checked_out')
                                            <span class="badge bg-secondary">Checked Out</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-primary">View All Bookings</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Check-ins -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Check-ins</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Ref</th>
                                    <th>Guest</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingCheckIns as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('bookings.show', $booking->id) }}">
                                            {{ $booking->booking_reference }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</td>
                                    <td>Room {{ $booking->room->room_number }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('bookings.check-in', $booking->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-sign-in-alt"></i> Check-in
                                        </a>
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
    // Room Status Chart
    var roomStatusCtx = document.getElementById("roomStatusChart");
    var roomStatusChart = new Chart(roomStatusCtx, {
        type: 'bar',
        data: {
            labels: ["Available", "Occupied", "Maintenance", "Reserved"],
            datasets: [{
                label: "Rooms",
                backgroundColor: ["#4e73df", "#1cc88a", "#e74a3b", "#f6c23e"],
                data: [{{ $availableRooms }}, {{ $occupiedRooms }}, {{ $maintenanceRooms }}, {{ $reservedRooms }}],
            }],
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Monthly Revenue Chart
    var revenueCtx = document.getElementById("revenueSourcesChart");
    var revenueChart = new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ["Direct", "Online", "Travel Agents"],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%',
        },
    });
</script>
@endsection