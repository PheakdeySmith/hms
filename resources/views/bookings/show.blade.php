@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
        <div>
            <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Bookings
            </a>
            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Booking
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

    <div class="row">
        <!-- Booking Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Booking Actions:</div>
                            @if($booking->status == 'confirmed')
                            <a class="dropdown-item" href="{{ route('bookings.check-in', $booking->id) }}">
                                <i class="fas fa-sign-in-alt fa-sm fa-fw mr-2 text-gray-400"></i> Check-in
                            </a>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('cancel-form').submit();">
                                <i class="fas fa-ban fa-sm fa-fw mr-2 text-gray-400"></i> Cancel Booking
                            </a>
                            <form id="cancel-form" action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            @elseif($booking->status == 'checked_in')
                            <a class="dropdown-item" href="{{ route('bookings.check-out', $booking->id) }}">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Check-out
                            </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this booking?')) document.getElementById('delete-form').submit();">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Delete Booking
                            </a>
                            <form id="delete-form" action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Status</span>
                        @if($booking->status == 'confirmed')
                            <span class="badge bg-warning text-dark">Confirmed</span>
                        @elseif($booking->status == 'checked_in')
                            <span class="badge bg-success">Checked In</span>
                        @elseif($booking->status == 'checked_out')
                            <span class="badge bg-secondary">Checked Out</span>
                        @elseif($booking->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Booking Reference</span>
                        <span class="h5 font-weight-bold">{{ $booking->booking_reference }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Check-in Date</span>
                        <span>{{ $booking->check_in_date->format('M d, Y') }}</span>
                        @if($booking->actual_check_in)
                            <span class="text-success d-block small">
                                <i class="fas fa-check-circle"></i> Checked in on {{ $booking->actual_check_in->format('M d, Y g:i A') }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Check-out Date</span>
                        <span>{{ $booking->check_out_date->format('M d, Y') }}</span>
                        @if($booking->actual_check_out)
                            <span class="text-success d-block small">
                                <i class="fas fa-check-circle"></i> Checked out on {{ $booking->actual_check_out->format('M d, Y g:i A') }}
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Number of Nights</span>
                        <span>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Number of Guests</span>
                        <span>{{ $booking->number_of_guests }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Total Price</span>
                        <span class="h5 font-weight-bold text-success">${{ number_format($booking->total_price, 2) }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Booking Source</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $booking->booking_source)) }}</span>
                    </div>

                    @if($booking->special_requests)
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Special Requests</span>
                        <p class="mb-0">{{ $booking->special_requests }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Guest Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guest Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode($booking->guest->first_name . ' ' . $booking->guest->last_name) }}&background=4e73df&color=ffffff&size=128" width="100" height="100">
                        <h5 class="mt-3 mb-0">{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</h5>
                        <p class="text-muted">{{ $booking->guest->email }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Phone</span>
                        <span>{{ $booking->guest->phone }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Address</span>
                        <p class="mb-0">
                            {{ $booking->guest->address }}<br>
                            {{ $booking->guest->city }}, {{ $booking->guest->state }} {{ $booking->guest->zip_code }}<br>
                            {{ $booking->guest->country }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">ID Type</span>
                        <span>{{ $booking->guest->identification_type }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">ID Number</span>
                        <span>{{ $booking->guest->identification_number }}</span>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('guests.show', $booking->guest->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user"></i> View Guest Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Room Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light p-3 rounded-circle d-inline-block">
                            <i class="fas fa-door-open fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-0">Room {{ $booking->room->room_number }}</h5>
                        <p class="text-muted">{{ $booking->room->roomType->name }}</p>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Room Type</span>
                        <span>{{ $booking->room->roomType->name }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Floor</span>
                        <span>{{ $booking->room->floor }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Capacity</span>
                        <span>{{ $booking->room->roomType->capacity }} Persons</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Price Per Night</span>
                        <span>${{ number_format($booking->room->roomType->price_per_night, 2) }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Amenities</span>
                        <p class="mb-0">{{ $booking->room->roomType->amenities }}</p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('rooms.show', $booking->room->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-door-open"></i> View Room Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
            <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus fa-sm"></i> Add Payment
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_reference }}</td>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                @if($payment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($payment->status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @elseif($payment->status == 'refunded')
                                    <span class="badge bg-info">Refunded</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.receipt', ['booking' => $booking->id, 'payment' => $payment->id]) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No payments recorded</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Total Paid:</th>
                            <th>${{ number_format($booking->payments->where('status', 'completed')->sum('amount'), 2) }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-end">Balance:</th>
                            <th>${{ number_format($booking->total_price - $booking->payments->where('status', 'completed')->sum('amount'), 2) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        @if($booking->status == 'confirmed')
                        <a href="{{ route('bookings.check-in', $booking->id) }}" class="btn btn-success me-2">
                            <i class="fas fa-sign-in-alt"></i> Check-in Guest
                        </a>
                        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-ban"></i> Cancel Booking
                        </button>
                        @elseif($booking->status == 'checked_in')
                        <a href="{{ route('bookings.check-out', $booking->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-sign-out-alt"></i> Check-out Guest
                        </a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit Booking
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this booking?</p>
                <p class="text-danger">This action cannot be undone and will remove all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
