@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Staff Details</h1>
        <div>
            <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Staff
            </a>
            <a href="{{ route('staff.index') }}" class="btn btn-secondary btn-sm shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Staff
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Uncomment this to debug the staff data --}}
    {{--
    <div class="card mb-4">
        <div class="card-header">Debug Information</div>
        <div class="card-body">
            <pre>{{ print_r($staff->toArray(), true) }}</pre>
        </div>
    </div>
    --}}

    <div class="row">
        <!-- Staff Information -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Staff Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Name</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Email</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-primary text-uppercase mb-1">Phone</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->phone ?: 'Not provided' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Information -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Employment Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-success text-uppercase mb-1">Position</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->position }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-success text-uppercase mb-1">Department</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->department }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-success text-uppercase mb-1">Hire Date</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->hire_date ? date('F d, Y', strtotime($staff->hire_date)) : 'Not provided' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">Role & Permissions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-info text-uppercase mb-1">Role</label>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if($staff->roles->count() > 0)
                                {{ $staff->roles->first()->name }}
                            @else
                                No role assigned
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-info text-uppercase mb-1">Permissions</label>
                        <div class="mb-0 text-gray-800">
                            @if($staff->getAllPermissions()->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($staff->getAllPermissions() as $permission)
                                        <li class="list-group-item py-1 px-0 border-0">
                                            <i class="fas fa-check-circle text-success mr-2"></i> {{ $permission->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="font-weight-bold">No permissions assigned</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activity History</h6>
        </div>
        <div class="card-body">
            <p class="mb-0">Staff activity tracking is not implemented yet.</p>
        </div>
    </div>

    <!-- Delete Staff Member -->
    <div class="card shadow mb-4 border-left-danger">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-danger">Delete Staff Member</h5>
                    <p class="mb-0">This action cannot be undone and will permanently delete all data associated with this staff member.</p>
                </div>
                <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStaffModal">
                        <i class="fas fa-trash fa-sm"></i> Delete Staff
                    </button>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteStaffModal" tabindex="-1" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteStaffModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this staff member? This action cannot be undone.</p>
                                    <p class="font-weight-bold">{{ $staff->name }} ({{ $staff->email }})</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Staff</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
