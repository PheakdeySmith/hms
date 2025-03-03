@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Check-in: Booking #{{ $booking->booking_reference }}</h1>
        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Booking
        </a>
    </div>

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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Status</span>
                        <span class="badge bg-warning text-dark">Confirmed</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Guest</span>
                        <span class="h5 font-weight-bold">{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Room</span>
                        <span>Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Check-in Date</span>
                        <span>{{ $booking->check_in_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Check-out Date</span>
                        <span>{{ $booking->check_out_date->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Number of Nights</span>
                        <span>{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Number of Guests</span>
                        <span>{{ $booking->number_of_guests }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Total Amount</span>
                        <span class="h5 font-weight-bold text-success">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Amount Paid</span>
                        <span class="h5 font-weight-bold">${{ number_format($booking->payments->where('status', 'completed')->sum('amount'), 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Balance Due</span>
                        <span class="h5 font-weight-bold {{ $booking->total_price - $booking->payments->where('status', 'completed')->sum('amount') > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($booking->total_price - $booking->payments->where('status', 'completed')->sum('amount'), 2) }}
                        </span>
                    </div>
                    
                    @if($booking->total_price - $booking->payments->where('status', 'completed')->sum('amount') > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> There is an outstanding balance on this booking.
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Process Payment
                        </a>
                    </div>
                    @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> This booking is fully paid.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Check-in Form Card -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check-in Form</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.process-check-in', $booking->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="actual_check_in" class="form-label">Check-in Date & Time</label>
                            <input type="datetime-local" class="form-control" id="actual_check_in" name="actual_check_in" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="id_verified" class="form-label">ID Verification</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="id_verified" name="id_verified" value="1" required>
                                <label class="form-check-label" for="id_verified">
                                    I confirm that I have verified the guest's ID
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_verified" class="form-label">Payment Verification</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="payment_verified" name="payment_verified" value="1" required>
                                <label class="form-check-label" for="payment_verified">
                                    I confirm that payment has been verified
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="key_issued" class="form-label">Room Key</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="key_issued" name="key_issued" value="1" required>
                                <label class="form-check-label" for="key_issued">
                                    I confirm that the room key has been issued
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Check-in Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Complete Check-in
                            </button>
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Room Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Room Number</span>
                        <span class="h5 font-weight-bold">{{ $booking->room->room_number }}</span>
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
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Amenities</span>
                        <p>{{ $booking->room->roomType->amenities }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Special Features</span>
                        <ul class="list-unstyled">
                            @if($booking->room->is_smoking)
                                <li><i class="fas fa-smoking"></i> Smoking Allowed</li>
                            @else
                                <li><i class="fas fa-smoking-ban"></i> Non-Smoking</li>
                            @endif
                            
                            @if($booking->room->is_accessible)
                                <li><i class="fas fa-wheelchair"></i> Accessible</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-in Checklist -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Check-in Checklist</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-id-card text-primary"></i> Verify ID</td>
                            <td>Check guest's ID matches the booking information</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-credit-card text-primary"></i> Verify Payment</td>
                            <td>Confirm payment method and collect any outstanding balance</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-key text-primary"></i> Issue Room Key</td>
                            <td>Program and issue room key card to guest</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-wifi text-primary"></i> Provide Wi-Fi Information</td>
                            <td>Share Wi-Fi network name and password</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-utensils text-primary"></i> Explain Amenities</td>
                            <td>Inform about breakfast times, gym hours, etc.</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-phone text-primary"></i> Emergency Contacts</td>
                            <td>Provide information about emergency services and hotel contacts</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 