@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Booking</h1>
        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Bookings
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Please check the form for errors.
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Booking Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Guest Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Guest Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="guest_id" class="form-label">Select Guest</label>
                                    <select class="form-select" id="guest_id" name="guest_id" required>
                                        <option value="">-- Select Guest --</option>
                                        @foreach($guests as $guest)
                                            <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                                {{ $guest->first_name }} {{ $guest->last_name }} ({{ $guest->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('guests.create') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-plus"></i> Create New Guest
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Booking Source</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="booking_source" class="form-label">Booking Source</label>
                                    <select class="form-select" id="booking_source" name="booking_source" required>
                                        <option value="direct" {{ old('booking_source') == 'direct' ? 'selected' : '' }}>Direct</option>
                                        <option value="website" {{ old('booking_source') == 'website' ? 'selected' : '' }}>Website</option>
                                        <option value="booking_com" {{ old('booking_source') == 'booking_com' ? 'selected' : '' }}>Booking.com</option>
                                        <option value="expedia" {{ old('booking_source') == 'expedia' ? 'selected' : '' }}>Expedia</option>
                                        <option value="airbnb" {{ old('booking_source') == 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                                        <option value="travel_agent" {{ old('booking_source') == 'travel_agent' ? 'selected' : '' }}>Travel Agent</option>
                                        <option value="phone" {{ old('booking_source') == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="walk_in" {{ old('booking_source') == 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                                        <option value="other" {{ old('booking_source') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3">{{ old('special_requests') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room and Date Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Room Selection</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Select Room</label>
                                    <select class="form-select" id="room_id" name="room_id" required>
                                        <option value="">-- Select Room --</option>
                                        @foreach($availableRooms as $room)
                                            <option value="{{ $room->id }}"
                                                data-price="{{ $room->roomType->price_per_night }}"
                                                data-capacity="{{ $room->roomType->capacity }}"
                                                {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                Room {{ $room->room_number }} - {{ $room->roomType->name }} (${{ $room->roomType->price_per_night }}/night)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="check_in_date" class="form-label">Check-in Date</label>
                                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="{{ old('check_in_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required onchange="calculateNights()">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="check_out_date" class="form-label">Check-out Date</label>
                                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="{{ old('check_out_date', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required onchange="calculateNights()">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="number_of_guests" class="form-label">Number of Guests</label>
                                    <input type="number" class="form-control" id="number_of_guests" name="number_of_guests" value="{{ old('number_of_guests', 1) }}" min="1" required>
                                    <div class="form-text text-danger" id="capacity_warning" style="display: none;">
                                        Warning: Number of guests exceeds room capacity.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nights" class="form-label">Number of Nights</label>
                                        <input type="number" class="form-control" id="nights" name="nights" value="1" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="room_price" class="form-label">Room Price per Night</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="room_price" name="room_price" value="0.00" readonly>
                                        </div>
                                        <small class="text-muted">Price is based on the selected room type</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="total_price" class="form-label">Total Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control" id="total_price" name="total_price" value="{{ old('total_price', '0.00') }}" readonly>
                                    </div>
                                    <small class="text-muted">Total price = Room price × Number of nights</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Booking
                    </button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug price calculation
        console.log('DOM loaded - setting up price calculation');

        // Get form elements
        const roomSelect = document.getElementById('room_id');
        const checkInDate = document.getElementById('check_in_date');
        const checkOutDate = document.getElementById('check_out_date');

        // Debug available rooms
        console.log('Available rooms in dropdown:', roomSelect.options.length - 1); // Subtract 1 for the placeholder
        for (let i = 0; i < roomSelect.options.length; i++) {
            const option = roomSelect.options[i];
            if (option.value) {
                console.log(`Room option ${i}:`, {
                    value: option.value,
                    text: option.text,
                    price: option.dataset.price,
                    capacity: option.dataset.capacity
                });
            }
        }

        // Add event listeners with debug logs
        roomSelect.addEventListener('change', function() {
            console.log('Room selected:', roomSelect.value);
            if (roomSelect.selectedIndex > 0) {
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                console.log('Selected option:', selectedOption);
                console.log('All data attributes:', selectedOption.dataset);
                console.log('Price data attribute:', selectedOption.dataset.price);

                // Look for price in text if dataset is not working
                const optionText = selectedOption.text;
                console.log('Option text:', optionText);
                const priceMatch = optionText.match(/\$(\d+(\.\d+)?)/);
                console.log('Price from text match:', priceMatch ? priceMatch[1] : 'Not found');
            } else {
                console.log('No room selected');
            }
            updatePrice();
        });

        checkInDate.addEventListener('change', function() {
            console.log('Check-in date changed:', checkInDate.value);
            updatePrice();
        });

        checkOutDate.addEventListener('change', function() {
            console.log('Check-out date changed:', checkOutDate.value);
            updatePrice();
        });

        // Function to update price calculation with debugging
        function updatePrice() {
            console.log('Updating price...');

            // Default values
            let roomPrice = 0;
            let totalPrice = 0;

            try {
                // Get values
                const checkIn = checkInDate.value ? new Date(checkInDate.value) : null;
                const checkOut = checkOutDate.value ? new Date(checkOutDate.value) : null;
                console.log('Check-in date:', checkIn);
                console.log('Check-out date:', checkOut);

                // Calculate nights only if both dates are valid
                let nights = 0;
                if (checkIn && checkOut && !isNaN(checkIn.getTime()) && !isNaN(checkOut.getTime())) {
                    nights = Math.max(1, Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24)));
                    console.log('Number of nights:', nights);
                } else {
                    console.log('Invalid dates, cannot calculate nights');
                }

                // Calculate price if room is selected and nights is valid
                if (roomSelect.selectedIndex > 0) {
                    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                    let priceString = selectedOption.dataset.price;

                    // If dataset.price is not working, try to get price from option text
                    if (!priceString || priceString === "undefined" || priceString === "null") {
                        const priceMatch = selectedOption.text.match(/\$(\d+(\.\d+)?)/);
                        if (priceMatch) {
                            priceString = priceMatch[1];
                            console.log('Used price from option text:', priceString);
                        }
                    }

                    console.log('Raw price value:', priceString);

                    // Clean the price string - remove any non-numeric characters except decimal point
                    const cleanPriceString = priceString ? priceString.replace(/[^\d.]/g, '') : "0";
                    console.log('Cleaned price string:', cleanPriceString);

                    roomPrice = parseFloat(cleanPriceString) || 0;
                    console.log('Parsed room price:', roomPrice);

                    if (!isNaN(roomPrice) && roomPrice > 0) {
                        // Set room price even if nights is invalid
                        document.getElementById('room_price').value = roomPrice.toFixed(2);

                        // Calculate total only if nights is valid
                        if (nights > 0) {
                            totalPrice = roomPrice * nights;
                            console.log('Total price calculated:', totalPrice);
                        } else {
                            console.log('Valid room price but invalid nights');
                        }
                    } else {
                        console.log('Room price is invalid (0, negative, or NaN)');
                    }
                } else {
                    console.log('No room selected, cannot calculate price');
                }
            } catch (error) {
                console.error('Error calculating price:', error);
            }

            // Always set values, even in error case
            document.getElementById('room_price').value = isNaN(roomPrice) ? '0.00' : roomPrice.toFixed(2);
            document.getElementById('total_price').value = isNaN(totalPrice) ? '0.00' : totalPrice.toFixed(2);
        }

        // Initial price update
        console.log('Initial price update');
        updatePrice();

        // Check if there's an initial room selection
        if (roomSelect.selectedIndex > 0) {
            console.log('Initial room selected:', roomSelect.value);
            updatePrice();
        }
    });
</script>
@endsection

@endsection
