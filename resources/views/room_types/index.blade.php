@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Room Types</h1>
        <a href="{{ route('room-types.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Room Type
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
            <form action="{{ route('room-types.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name" name="search" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="is_active">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="reset" class="btn btn-secondary w-100" onclick="window.location='{{ route('room-types.index') }}'">
                        <i class="fas fa-sync-alt fa-sm"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Room Types Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Room Types</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('room-types.index', ['export' => 'csv']) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i> Export CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('room-types.index', ['export' => 'pdf']) }}">
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
                            <th>Name</th>
                            <th>Price</th>
                            <th>Capacity</th>
                            <th>Rooms</th>
                            <th>Amenities</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypes as $roomType)
                        <tr>
                            <td>
                                <a href="{{ route('room-types.show', $roomType->id) }}">
                                    {{ $roomType->name }}
                                </a>
                            </td>
                            <td>${{ number_format($roomType->base_price, 2) }}</td>
                            <td>{{ $roomType->capacity }} {{ Str::plural('Person', $roomType->capacity) }}</td>
                            <td>{{ $roomType->rooms->count() }}</td>
                            <td>
                                <div class="d-flex flex-wrap">
                                    @if($roomType->has_air_conditioning)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-snowflake"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_tv)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-tv"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_refrigerator)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-cube"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_safe)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-lock"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_wifi)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-wifi"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_minibar)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-glass-martini-alt"></i></span>
                                    @endif
                                    
                                    @if($roomType->has_bathtub)
                                        <span class="badge bg-info me-1 mb-1"><i class="fas fa-bath"></i></span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($roomType->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('room-types.show', $roomType->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('room-types.edit', $roomType->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $roomType->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $roomType->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $roomType->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $roomType->id }}">Delete Room Type</h5>
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
                                                    <button type="submit" class="btn btn-danger" {{ $roomType->rooms->count() > 0 ? 'disabled' : '' }}>Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No room types found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $roomTypes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 