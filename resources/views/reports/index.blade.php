@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reports</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Occupancy Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Occupancy Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Room Occupancy Analysis</div>
                            <p class="mt-2 mb-0">Track room occupancy rates over time by room type.</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reports.occupancy') }}" class="btn btn-primary btn-sm">View Report</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Revenue Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Financial Performance</div>
                            <p class="mt-2 mb-0">Analyze revenue trends and payment methods over time.</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reports.revenue') }}" class="btn btn-success btn-sm">View Report</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Bookings Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Booking Trends</div>
                            <p class="mt-2 mb-0">Track booking patterns and status distribution over time.</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reports.bookings') }}" class="btn btn-info btn-sm">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Description Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">About Reports</h6>
        </div>
        <div class="card-body">
            <p>The reporting system provides valuable insights into your hotel's performance across various metrics:</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-bed mr-1"></i> Occupancy Reports
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Track room occupancy rates over time</li>
                                <li>Compare occupancy across different room types</li>
                                <li>Identify peak and low seasons</li>
                                <li>Plan maintenance during low occupancy periods</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-dollar-sign mr-1"></i> Revenue Reports
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Analyze revenue trends over time</li>
                                <li>Track payment method distribution</li>
                                <li>Identify most profitable periods</li>
                                <li>Support financial planning and forecasting</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-calendar-check mr-1"></i> Booking Reports
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Track booking patterns over time</li>
                                <li>Analyze booking status distribution</li>
                                <li>Monitor cancellation rates</li>
                                <li>Identify booking sources and trends</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle mr-1"></i> All reports can be filtered by date range and exported for further analysis.
            </div>
        </div>
    </div>
</div>
@endsection 