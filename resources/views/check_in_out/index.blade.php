@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Check-In / Check-Out Management</h1>
        <div>
            <a href="{{ route('check-in-out.today') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar-day fa-sm text-white-50"></i> Today's Check-Ins/Outs
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
        <!-- Today's Check-Ins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Today's Check-Ins</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $checkInsToday }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Check-Outs Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Check-Outs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $checkOutsToday }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Check-Ins/Outs</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('check-in-out.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Booking Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Check-Ins/Outs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Check-Ins and Check-Outs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check-In Date</th>
                            <th>Check-Out Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
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
                            <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                            <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                    <span class="badge bg-warning text-dark">Confirmed</span>
                                @elseif($booking->status == 'checked_in')
                                    <span class="badge bg-success">Checked In</span>
                                @elseif($booking->status == 'checked_out')
                                    <span class="badge bg-info">Checked Out</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($booking->status == 'confirmed')
                                    <a href="{{ route('bookings.check-in', $booking->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-sign-in-alt"></i> Check In
                                    </a>
                                    @elseif($booking->status == 'checked_in')
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
                            <td colspan="7" class="text-center">No check-ins or check-outs found for the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
