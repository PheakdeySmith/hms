@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Room {{ $room->room_number }}</h1>
        <div>
            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rooms
            </a>
            <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Room
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
        <!-- Room Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Room Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Room Actions:</div>
                            <a class="dropdown-item" href="{{ route('rooms.edit', $room->id) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit Room
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteRoomModal">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Delete Room
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Room Number</span>
                        <span class="h5">{{ $room->room_number }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Room Type</span>
                        <span>{{ $room->roomType->name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Floor</span>
                        <span>{{ $room->floor }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Status</span>
                        @if($room->status == 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif($room->status == 'occupied')
                            <span class="badge bg-danger">Occupied</span>
                        @elseif($room->status == 'maintenance')
                            <span class="badge bg-warning text-dark">Maintenance</span>
                        @elseif($room->status == 'cleaning')
                            <span class="badge bg-info text-dark">Cleaning</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Features</span>
                        <div>
                            @if($room->is_smoking)
                                <span class="badge bg-secondary mb-1"><i class="fas fa-smoking"></i> Smoking Allowed</span>
                            @else
                                <span class="badge bg-success mb-1"><i class="fas fa-smoking-ban"></i> Non-Smoking</span>
                            @endif
                            
                            @if($room->is_accessible)
                                <span class="badge bg-primary mb-1"><i class="fas fa-wheelchair"></i> Accessible</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($room->notes)
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Notes</span>
                        <p class="mb-0">{{ $room->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Room Type Details Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Room Type Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Price Per Night</span>
                        <span class="h5 text-success">${{ number_format($room->roomType->base_price, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Capacity</span>
                        <span>{{ $room->roomType->capacity }} {{ Str::plural('Person', $room->roomType->capacity) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Description</span>
                        <p>{{ $room->roomType->description }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Amenities</span>
                        <div class="row mt-2">
                            @if($room->roomType->has_air_conditioning)
                                <div class="col-6 mb-2"><i class="fas fa-snowflake text-info"></i> Air Conditioning</div>
                            @endif
                            
                            @if($room->roomType->has_tv)
                                <div class="col-6 mb-2"><i class="fas fa-tv text-info"></i> TV</div>
                            @endif
                            
                            @if($room->roomType->has_refrigerator)
                                <div class="col-6 mb-2"><i class="fas fa-cube text-info"></i> Refrigerator</div>
                            @endif
                            
                            @if($room->roomType->has_safe)
                                <div class="col-6 mb-2"><i class="fas fa-lock text-info"></i> Safe</div>
                            @endif
                            
                            @if($room->roomType->has_wifi)
                                <div class="col-6 mb-2"><i class="fas fa-wifi text-info"></i> WiFi</div>
                            @endif
                            
                            @if($room->roomType->has_minibar)
                                <div class="col-6 mb-2"><i class="fas fa-glass-martini-alt text-info"></i> Minibar</div>
                            @endif
                            
                            @if($room->roomType->has_bathtub)
                                <div class="col-6 mb-2"><i class="fas fa-bath text-info"></i> Bathtub</div>
                            @endif
                            
                            @if($room->roomType->amenities)
                                @if(is_array($room->roomType->amenities))
                                    @foreach($room->roomType->amenities as $amenity)
                                        <div class="col-6 mb-2"><i class="fas fa-check text-info"></i> {{ $amenity }}</div>
                                    @endforeach
                                @else
                                    <div class="col-6 mb-2"><i class="fas fa-check text-info"></i> {{ $room->roomType->amenities }}</div>
                                @endif
                            @endif
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
                    <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="btn btn-success btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-calendar-plus"></i>
                        </span>
                        <span class="text">Create New Booking</span>
                    </a>
                    
                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text">Edit Room</span>
                    </a>
                    
                    <form action="{{ route('rooms.update', $room->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="room_number" value="{{ $room->room_number }}">
                        <input type="hidden" name="room_type_id" value="{{ $room->room_type_id }}">
                        <input type="hidden" name="floor" value="{{ $room->floor }}">
                        <input type="hidden" name="is_smoking" value="{{ $room->is_smoking ? '1' : '0' }}">
                        <input type="hidden" name="is_accessible" value="{{ $room->is_accessible ? '1' : '0' }}">
                        <input type="hidden" name="notes" value="{{ $room->notes }}">
                        
                        @if($room->status == 'available')
                            <input type="hidden" name="status" value="maintenance">
                            <button type="submit" class="btn btn-warning btn-icon-split btn-lg w-100">
                                <span class="icon text-white-50">
                                    <i class="fas fa-tools"></i>
                                </span>
                                <span class="text">Mark as Maintenance</span>
                            </button>
                        @elseif($room->status == 'maintenance')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" class="btn btn-success btn-icon-split btn-lg w-100">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Mark as Available</span>
                            </button>
                        @elseif($room->status == 'cleaning')
                            <input type="hidden" name="status" value="available">
                            <button type="submit" class="btn btn-success btn-icon-split btn-lg w-100">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Mark as Available</span>
                            </button>
                        @endif
                    </form>
                    
                    <button type="button" class="btn btn-danger btn-icon-split btn-lg w-100" data-bs-toggle="modal" data-bs-target="#deleteRoomModal">
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Delete Room</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Upcoming Bookings</h6>
            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus fa-sm"></i> Add Booking
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Nights</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingBookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}">
                                    {{ $booking->booking_reference }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('guests.show', $booking->guest->id) }}">
                                    {{ $booking->guest->first_name }} {{ $booking->guest->last_name }}
                                </a>
                            </td>
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
                            <td colspan="8" class="text-center">No upcoming bookings for this room</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Nights</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('bookings.show', $booking->id) }}">
                                    {{ $booking->booking_reference }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('guests.show', $booking->guest->id) }}">
                                    {{ $booking->guest->first_name }} {{ $booking->guest->last_name }}
                                </a>
                            </td>
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
                            <td colspan="8" class="text-center">No recent bookings for this room</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoomModalLabel">Delete Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete Room {{ $room->room_number }}?</p>
                <p class="text-danger">This action cannot be undone and will remove all associated data.</p>
                @if($room->bookings->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Warning: This room has {{ $room->bookings->count() }} booking(s). Deleting this room will affect these bookings.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Room</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 