@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $roomType->name }}</h1>
        <div>
            <a href="{{ route('room-types.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Room Types
            </a>
            <a href="{{ route('room-types.edit', $roomType->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Room Type
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
        <!-- Room Type Information Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Room Type Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Room Type Actions:</div>
                            <a class="dropdown-item" href="{{ route('room-types.edit', $roomType->id) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit Room Type
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteRoomTypeModal">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Delete Room Type
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($roomType->image)
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $roomType->image) }}" alt="{{ $roomType->name }}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Name</span>
                        <span class="h5">{{ $roomType->name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Price Per Night</span>
                        <span class="h5 text-success">${{ number_format($roomType->base_price, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Capacity</span>
                        <span>{{ $roomType->capacity }} {{ Str::plural('Person', $roomType->capacity) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Status</span>
                        @if($roomType->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted">Description</span>
                        <p>{{ $roomType->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amenities Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Amenities</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_air_conditioning)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>Air Conditioning</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_tv)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>TV</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_refrigerator)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>Refrigerator</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_safe)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>Safe</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_wifi)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>WiFi</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_minibar)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>Minibar</span>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                @if($roomType->has_bathtub)
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @endif
                                <span>Bathtub</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($roomType->amenities && is_array($roomType->amenities) && count($roomType->amenities) > 0)
                    <div class="mt-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted mb-2">Additional Amenities</span>
                        <ul class="list-group">
                            @foreach($roomType->amenities as $amenity)
                                <li class="list-group-item">{{ $amenity }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @elseif($roomType->amenities && is_string($roomType->amenities) && !empty($roomType->amenities))
                    <div class="mt-3">
                        <span class="d-block text-xs font-weight-bold text-uppercase text-muted mb-2">Additional Amenities</span>
                        <ul class="list-group">
                            <li class="list-group-item">{{ $roomType->amenities }}</li>
                        </ul>
                    </div>
                    @endif
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
                    <a href="{{ route('rooms.create', ['room_type_id' => $roomType->id]) }}" class="btn btn-success btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Add Room with this Type</span>
                    </a>
                    
                    <a href="{{ route('room-types.edit', $roomType->id) }}" class="btn btn-primary btn-icon-split btn-lg mb-3 w-100">
                        <span class="icon text-white-50">
                            <i class="fas fa-edit"></i>
                        </span>
                        <span class="text">Edit Room Type</span>
                    </a>
                    
                    <!-- Image Upload Form -->
                    <button type="button" class="btn btn-info btn-icon-split btn-lg mb-3 w-100" data-bs-toggle="modal" data-bs-target="#updateImageModal">
                        <span class="icon text-white-50">
                            <i class="fas fa-image"></i>
                        </span>
                        <span class="text">Update Image</span>
                    </button>
                    
                    <form action="{{ route('room-types.update', $roomType->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $roomType->name }}">
                        <input type="hidden" name="description" value="{{ $roomType->description }}">
                        <input type="hidden" name="base_price" value="{{ $roomType->base_price }}">
                        <input type="hidden" name="capacity" value="{{ $roomType->capacity }}">
                        <input type="hidden" name="has_air_conditioning" value="{{ $roomType->has_air_conditioning ? '1' : '0' }}">
                        <input type="hidden" name="has_tv" value="{{ $roomType->has_tv ? '1' : '0' }}">
                        <input type="hidden" name="has_refrigerator" value="{{ $roomType->has_refrigerator ? '1' : '0' }}">
                        <input type="hidden" name="has_safe" value="{{ $roomType->has_safe ? '1' : '0' }}">
                        <input type="hidden" name="has_wifi" value="{{ $roomType->has_wifi ? '1' : '0' }}">
                        <input type="hidden" name="has_minibar" value="{{ $roomType->has_minibar ? '1' : '0' }}">
                        <input type="hidden" name="has_bathtub" value="{{ $roomType->has_bathtub ? '1' : '0' }}">
                        
                        @if($roomType->is_active)
                            <input type="hidden" name="is_active" value="0">
                            <button type="submit" class="btn btn-warning btn-icon-split btn-lg w-100">
                                <span class="icon text-white-50">
                                    <i class="fas fa-ban"></i>
                                </span>
                                <span class="text">Mark as Inactive</span>
                            </button>
                        @else
                            <input type="hidden" name="is_active" value="1">
                            <button type="submit" class="btn btn-success btn-icon-split btn-lg w-100">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Mark as Active</span>
                            </button>
                        @endif
                    </form>
                    
                    <button type="button" class="btn btn-danger btn-icon-split btn-lg w-100" data-bs-toggle="modal" data-bs-target="#deleteRoomTypeModal" {{ $roomType->rooms->count() > 0 ? 'disabled' : '' }}>
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text">Delete Room Type</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Rooms of this Type</h6>
            <a href="{{ route('rooms.create', ['room_type_id' => $roomType->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus fa-sm"></i> Add Room
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Floor</th>
                            <th>Status</th>
                            <th>Features</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomType->rooms as $room)
                        <tr>
                            <td>
                                <a href="{{ route('rooms.show', $room->id) }}">
                                    {{ $room->room_number }}
                                </a>
                            </td>
                            <td>{{ $room->floor }}</td>
                            <td>
                                @if($room->status == 'available')
                                    <span class="badge bg-success">Available</span>
                                @elseif($room->status == 'occupied')
                                    <span class="badge bg-danger">Occupied</span>
                                @elseif($room->status == 'maintenance')
                                    <span class="badge bg-warning text-dark">Maintenance</span>
                                @elseif($room->status == 'cleaning')
                                    <span class="badge bg-info text-dark">Cleaning</span>
                                @endif
                            </td>
                            <td>
                                @if($room->is_smoking)
                                    <span class="badge bg-secondary me-1"><i class="fas fa-smoking"></i> Smoking</span>
                                @endif
                                @if($room->is_accessible)
                                    <span class="badge bg-primary me-1"><i class="fas fa-wheelchair"></i> Accessible</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No rooms found for this type</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Rooms</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $roomType->rooms->count() }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Available Rooms</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $availableRoomsCount }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Occupancy Rate</div>
                                @php
                                    $totalRooms = $roomType->rooms->count();
                                    $occupancyRate = $totalRooms > 0 ? (($totalRooms - $availableRoomsCount) / $totalRooms) * 100 : 0;
                                @endphp
                                <div class="h5 mb-0 font-weight-bold">{{ number_format($occupancyRate, 1) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Room Type Modal -->
<div class="modal fade" id="deleteRoomTypeModal" tabindex="-1" aria-labelledby="deleteRoomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoomTypeModalLabel">Delete Room Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete {{ $roomType->name }}?</p>
                <p class="text-danger">This action cannot be undone and will remove all associated data.</p>
                @if($roomType->rooms->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Warning: This room type has {{ $roomType->rooms->count() }} room(s) associated with it. You cannot delete it until all rooms are reassigned or deleted.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('room-types.destroy', $roomType->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" {{ $roomType->rooms->count() > 0 ? 'disabled' : '' }}>Delete Room Type</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Image Modal -->
<div class="modal fade" id="updateImageModal" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateImageModalLabel">Update Room Type Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('room-types.update', $roomType->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if($roomType->image)
                    <div class="text-center mb-3">
                        <p>Current Image:</p>
                        <img src="{{ asset('storage/' . $roomType->image) }}" alt="{{ $roomType->name }}" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">New Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <small class="text-muted">Select a new image to replace the current one.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Image</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 