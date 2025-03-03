@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Room {{ $room->room_number }}</h1>
        <div>
            <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Room
            </a>
            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i> All Rooms
            </a>
        </div>
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
            <h6 class="m-0 font-weight-bold text-primary">Room Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="room_number" class="form-label">Room Number</label>
                                        <input type="text" class="form-control" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                                        <small class="text-muted">Must be unique</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="floor" class="form-label">Floor</label>
                                        <input type="text" class="form-control" id="floor" name="floor" value="{{ old('floor', $room->floor) }}" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="room_type_id" class="form-label">Room Type</label>
                                    <select class="form-select" id="room_type_id" name="room_type_id" required>
                                        <option value="">-- Select Room Type --</option>
                                        @foreach($roomTypes as $roomType)
                                            <option value="{{ $roomType->id }}" {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                                {{ $roomType->name }} - ${{ number_format($roomType->base_price, 2) }} per night
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="cleaning" {{ old('status', $room->status) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features and Notes -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Features and Notes</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_smoking" name="is_smoking" {{ old('is_smoking', $room->is_smoking) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_smoking">Smoking Allowed</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_accessible" name="is_accessible" {{ old('is_accessible', $room->is_accessible) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_accessible">Accessible Room</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="5">{{ old('notes', $room->notes) }}</textarea>
                                    <small class="text-muted">Add any special notes or information about this room</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Room
                    </button>
                    <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 