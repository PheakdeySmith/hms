@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Today's Check-Ins and Check-Outs</h1>
        <div>
            <a href="{{ route('check-in-out.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to All Check-Ins/Outs
            </a>
        </div>
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

    <!-- Overview Cards -->
    <div class="row">
        <!-- Today's Date Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Date</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $today->format('M d, Y') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Check-Ins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Check-Ins</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCheckIns }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Check-Outs Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Check-Outs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCheckOuts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-end fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Actions Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Actions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedCheckIns + $completedCheckOuts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Check-Ins Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Today's Check-Ins</h6>
            <span class="badge bg-primary">{{ $checkIns->count() }} Total</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check-In Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkIns as $booking)
                        <tr class="{{ $booking->status == 'confirmed' ? 'table-warning' : 'table-success' }}">
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}">
                                    #{{ $booking->id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('guests.show', $booking->guest->id) }}">
                                    {{ $booking->guest->full_name }}
                                </a>
                            </td>
                            <td>
                                @if($booking->room)
                                <a href="{{ route('rooms.show', $booking->room->id) }}">
                                    {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})
                                </a>
                                @else
                                <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->actual_check_in)
                                    {{ $booking->actual_check_in->format('h:i A') }}
                                @else
                                    <span class="text-muted">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-warning text-dark">Pending Check-In</span>
                                @elseif($booking->status == 'checked_in')
                                    <span class="badge bg-success">Checked In</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($booking->status == 'confirmed')
                                    <a href="{{ route('bookings.check-in', $booking->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-sign-in-alt"></i> Check In
                                    </a>
                                    @endif
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No check-ins scheduled for today</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Today's Check-Outs Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Today's Check-Outs</h6>
            <span class="badge bg-primary">{{ $checkOuts->count() }} Total</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check-Out Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkOuts as $booking)
                        <tr class="{{ $booking->status == 'checked_in' ? 'table-info' : 'table-success' }}">
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}">
                                    #{{ $booking->id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('guests.show', $booking->guest->id) }}">
                                    {{ $booking->guest->full_name }}
                                </a>
                            </td>
                            <td>
                                @if($booking->room)
                                <a href="{{ route('rooms.show', $booking->room->id) }}">
                                    {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})
                                </a>
                                @else
                                <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->actual_check_out)
                                    {{ $booking->actual_check_out->format('h:i A') }}
                                @else
                                    <span class="text-muted">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'checked_in')
                                    <span class="badge bg-info">Pending Check-Out</span>
                                @elseif($booking->status == 'checked_out')
                                    <span class="badge bg-success">Checked Out</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($booking->status == 'checked_in')
                                    <a href="{{ route('bookings.check-out', $booking->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-sign-out-alt"></i> Check Out
                                    </a>
                                    @endif
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No check-outs scheduled for today</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
