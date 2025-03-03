@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Guest</h1>
        <div>
            <a href="{{ route('guests.show', $guest->id) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Guest
            </a>
            <a href="{{ route('guests.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i> All Guests
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
            <h6 class="m-0 font-weight-bold text-primary">Guest Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('guests.update', $guest->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $guest->first_name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $guest->last_name) }}" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $guest->email) }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $guest->phone) }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $guest->date_of_birth ? $guest->date_of_birth->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Address Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $guest->address) }}">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $guest->city) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="state" class="form-label">State/Province</label>
                                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $guest->state) }}">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="postal_code" class="form-label">Postal/Zip Code</label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $guest->postal_code) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $guest->country) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Identification Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Identification</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="identification_type" class="form-label">ID Type</label>
                                    <select class="form-select" id="identification_type" name="identification_type">
                                        <option value="">-- Select ID Type --</option>
                                        <option value="Passport" {{ old('identification_type', $guest->identification_type) == 'Passport' ? 'selected' : '' }}>Passport</option>
                                        <option value="Driver's License" {{ old('identification_type', $guest->identification_type) == "Driver's License" ? 'selected' : '' }}>Driver's License</option>
                                        <option value="National ID" {{ old('identification_type', $guest->identification_type) == 'National ID' ? 'selected' : '' }}>National ID</option>
                                        <option value="Other" {{ old('identification_type', $guest->identification_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="identification_number" class="form-label">ID Number</label>
                                    <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{ old('identification_number', $guest->identification_number) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Special Requests/Notes</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="5">{{ old('special_requests', $guest->special_requests) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Guest
                    </button>
                    <a href="{{ route('guests.show', $guest->id) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 