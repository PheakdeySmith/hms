@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Guest Details</h1>
        <div>
            <a href="{{ route('guests.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Guests
            </a>
            <a href="{{ route('guests.edit', $guest->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Guest
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Guest Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Guest Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Guest Actions:</div>
                            <a class="dropdown-item" href="{{ route('guests.edit', $guest->id) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit Guest
                            </a>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Delete Guest
                            </a>
                            <form id="delete-form" action="{{ route('guests.destroy', $guest->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode($guest->first_name . ' ' . $guest->last_name) }}&background=4e73df&color=ffffff&size=128" width="100" height="100">
                        <h5 class="mt-3 mb-0">{{ $guest->first_name }} {{ $guest->last_name }}</h5>
                        <p class="text-muted">{{ $guest->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Phone</span>
                        <span>{{ $guest->phone }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Address</span>
                        <p class="mb-0">
                            {{ $guest->address }}<br>
                            {{ $guest->city }}, {{ $guest->state }} {{ $guest->postal_code }}<br>
                            {{ $guest->country }}
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Date of Birth</span>
                        <span>{{ $guest->date_of_birth ? date('M d, Y', strtotime($guest->date_of_birth)) : 'Not provided' }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Identification</span>
                        <span>{{ $guest->identification_type }}: {{ $guest->identification_number }}</span>
                    </div>
                    
                    @if($guest->special_requests)
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Special Requests/Notes</span>
                        <p class="mb-0">{{ $guest->special_requests }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Guest Stats Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guest Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-4">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $guest->bookings->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    
                    <div class="row no-gutters align-items-center mb-4">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Spent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($guest->bookings->sum('total_price'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    
                    <div class="row no-gutters align-items-center mb-4">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Nights Stayed</div>
                            @php
                                $totalNights = 0;
                                foreach($guest->bookings as $booking) {
                                    if($booking->check_in_date && $booking->check_out_date) {
                                        $totalNights += $booking->check_in_date->diffInDays($booking->check_out_date);
                                    }
                                }
                            @endphp
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalNights }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Last Stay</div>
                            @php
                                $lastBooking = $guest->bookings->sortByDesc('check_in_date')->first();
                            @endphp
                            @if($lastBooking)
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lastBooking->check_in_date->format('M d, Y') }}</div>
                            @else
                                <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('bookings.create', ['guest_id' => $guest->id]) }}" class="btn btn-success btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Create New Booking</span>
                    </a>
                    
                    <a href="mailto:{{ $guest->email }}" class="btn btn-info btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <span class="text">Send Email</span>
                    </a>
                    
                    <a href="tel:{{ $guest->phone }}" class="btn btn-primary btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-phone"></i>
                        </span>
                        <span class="text">Call Guest</span>
                    </a>
                    
                    <button type="button" class="btn btn-danger btn-icon-split btn-lg w-100" data-bs-toggle="modal" data-bs-target="#deleteGuestModal">
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Delete Guest</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking History Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Booking History</h6>
            <a href="{{ route('bookings.create', ['guest_id' => $guest->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus fa-sm"></i> Add Booking
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Nights</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guest->bookings->sortByDesc('check_in_date') as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}">
                                    {{ $booking->booking_reference }}
                                </a>
                            </td>
                            <td>Room {{ $booking->room->room_number }}</td>
                            <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                            <td>{{ $booking->check_out_date->format('M d, Y') }}</td>
                            <td>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
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
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found for this guest</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Guest Modal -->
<div class="modal fade" id="deleteGuestModal" tabindex="-1" aria-labelledby="deleteGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGuestModalLabel">Delete Guest</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete {{ $guest->first_name }} {{ $guest->last_name }}?</p>
                <p class="text-danger">This action cannot be undone and will remove all associated data.</p>
                @if($guest->bookings->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Warning: This guest has {{ $guest->bookings->count() }} booking(s). Deleting this guest will affect these bookings.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('guests.destroy', $guest->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Guest</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection