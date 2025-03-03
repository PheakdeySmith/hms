@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rooms</h1>
        <a href="{{ route('rooms.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Room
        </a>
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

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Room Number</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by room number">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="room_type_id" class="form-label">Room Type</label>
                    <select class="form-select" id="room_type_id" name="room_type_id">
                        <option value="">All Room Types</option>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ request('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="floor" class="form-label">Floor</label>
                    <select class="form-select" id="floor" name="floor">
                        <option value="">All Floors</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                                {{ $floor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i> Filter
                    </button>
                    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt fa-sm"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Rooms Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Rooms</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('rooms.index', ['export' => 'csv']) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i> Export CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('rooms.index', ['export' => 'pdf']) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Room #</th>
                            <th>Type</th>
                            <th>Floor</th>
                            <th>Status</th>
                            <th>Features</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td>
                                <a href="{{ route('rooms.show', $room->id) }}">
                                    {{ $room->room_number }}
                                </a>
                            </td>
                            <td>{{ $room->roomType->name }}</td>
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
                                    <span class="badge bg-secondary"><i class="fas fa-smoking"></i> Smoking</span>
                                @endif
                                @if($room->is_accessible)
                                    <span class="badge bg-primary"><i class="fas fa-wheelchair"></i> Accessible</span>
                                @endif
                            </td>
                            <td>${{ number_format($room->roomType->base_price, 2) }}</td>
                            <td>
                                <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $room->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $room->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $room->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $room->id }}">Delete Room</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete Room {{ $room->room_number }}?</p>
                                                <p class="text-danger">This action cannot be undone and will remove all associated data.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No rooms found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 