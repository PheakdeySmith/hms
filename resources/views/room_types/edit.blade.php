@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Room Type: {{ $roomType->name }}</h1>
        <div>
            <a href="{{ route('room-types.show', $roomType) }}" class="btn btn-sm btn-info shadow-sm mr-2">
                <i class="fas fa-eye fa-sm text-white-50"></i> View Details
            </a>
            <a href="{{ route('room-types.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Room Types
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
            <h6 class="m-0 font-weight-bold text-primary">Room Type Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('room-types.update', $roomType) }}" method="POST" enctype="multipart/form-data">
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
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $roomType->name) }}" required>
                                    <small class="text-muted">E.g., Standard, Deluxe, Suite, etc.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $roomType->description) }}</textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="base_price" class="form-label">Base Price Per Night</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="base_price" name="base_price" value="{{ old('base_price', $roomType->base_price) }}" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="capacity" class="form-label">Capacity</label>
                                        <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $roomType->capacity) }}" min="1" required>
                                        <small class="text-muted">Maximum number of guests</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Room Image</label>
                                    @if($roomType->image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $roomType->image_path) }}" alt="{{ $roomType->name }}" class="img-thumbnail" style="max-height: 150px;">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image">
                                            <label class="form-check-label text-danger" for="delete_image">
                                                Delete current image
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Upload a new image to replace the current one (optional)</small>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $roomType->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                        <small class="d-block text-muted">Inactive room types won't be available for new bookings</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amenities -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Amenities</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_air_conditioning" name="has_air_conditioning" {{ old('has_air_conditioning', $roomType->has_air_conditioning) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_air_conditioning">Air Conditioning</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_tv" name="has_tv" {{ old('has_tv', $roomType->has_tv) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_tv">TV</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_refrigerator" name="has_refrigerator" {{ old('has_refrigerator', $roomType->has_refrigerator) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_refrigerator">Refrigerator</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_safe" name="has_safe" {{ old('has_safe', $roomType->has_safe) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_safe">Safe</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_wifi" name="has_wifi" {{ old('has_wifi', $roomType->has_wifi) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_wifi">WiFi</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_minibar" name="has_minibar" {{ old('has_minibar', $roomType->has_minibar) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_minibar">Minibar</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_bathtub" name="has_bathtub" {{ old('has_bathtub', $roomType->has_bathtub) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_bathtub">Bathtub</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3 mt-4">
                                    <label for="amenities" class="form-label">Additional Amenities</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" id="amenity_input" placeholder="Add amenity">
                                        <button class="btn btn-outline-secondary" type="button" id="add_amenity">Add</button>
                                    </div>
                                    <div id="amenities_container" class="d-flex flex-wrap gap-2 mt-2">
                                        <!-- Amenities will be added here -->
                                    </div>
                                    <div id="amenities_input_container">
                                        <!-- Hidden inputs will be added here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Room Type
                    </button>
                    <a href="{{ route('room-types.show', $roomType) }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amenityInput = document.getElementById('amenity_input');
        const addAmenityBtn = document.getElementById('add_amenity');
        const amenitiesContainer = document.getElementById('amenities_container');
        const amenitiesInputContainer = document.getElementById('amenities_input_container');
        let amenityCount = 0;
        
        // Function to add amenity
        function addAmenity(text = null) {
            const amenityText = text || amenityInput.value.trim();
            if (amenityText) {
                // Create badge with delete button
                const badge = document.createElement('div');
                badge.className = 'badge bg-primary p-2 d-flex align-items-center';
                badge.innerHTML = `
                    <span>${amenityText}</span>
                    <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove"></button>
                `;
                amenitiesContainer.appendChild(badge);
                
                // Create hidden input
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `amenities[${amenityCount}]`;
                hiddenInput.value = amenityText;
                amenitiesInputContainer.appendChild(hiddenInput);
                
                // Add event listener to delete button
                const deleteBtn = badge.querySelector('.btn-close');
                deleteBtn.addEventListener('click', function() {
                    badge.remove();
                    hiddenInput.remove();
                });
                
                // Clear input and increment counter
                if (!text) amenityInput.value = '';
                amenityCount++;
            }
        }
        
        // Add amenity when button is clicked
        addAmenityBtn.addEventListener('click', function() {
            addAmenity();
        });
        
        // Add amenity when Enter key is pressed
        amenityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addAmenity();
            }
        });
        
        // Add existing amenities if any (from old input or model)
        @if(old('amenities'))
            @foreach(old('amenities') as $amenity)
                addAmenity("{{ $amenity }}");
            @endforeach
        @elseif(isset($roomType->amenities) && is_array($roomType->amenities))
            @foreach($roomType->amenities as $amenity)
                addAmenity("{{ $amenity }}");
            @endforeach
        @elseif(isset($roomType->amenities) && is_string($roomType->amenities) && !empty($roomType->amenities))
            addAmenity("{{ $roomType->amenities }}");
        @endif
    });
</script>
@endpush
@endsection 