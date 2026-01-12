
@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Location
                    </h5>
                    <a href="{{ route('supplier.locations.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('supplier.locations.update', $location->id) }}" method="POST" id="locationForm">
                        @csrf
                        @method('PUT')

                        <!-- Google Places Autocomplete -->
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Update your location:</strong> Search for a new address or manually edit the fields below
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Search New Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="autocomplete" 
                                           placeholder="Start typing to search a new address...">
                                </div>
                                <small class="text-muted">Or edit the fields below manually</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Location Name -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="location_name" class="form-label fw-bold">
                                    Location Name <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="text" class="form-control @error('location_name') is-invalid @enderror" 
                                       id="location_name" name="location_name" 
                                       placeholder="e.g., Main Branch, Downtown Office"
                                       value="{{ old('location_name', $location->location_name) }}">
                                @error('location_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">
                                    Phone <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" 
                                       placeholder="+255 xxx xxx xxx"
                                       value="{{ old('phone', $location->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Line 1 -->
                        <div class="mb-3">
                            <label for="address_line1" class="form-label fw-bold">
                                Address Line 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" 
                                   id="address_line1" name="address_line1" required
                                   placeholder="Street address, P.O. box, company name"
                                   value="{{ old('address_line1', $location->address_line1) }}">
                            @error('address_line1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Line 2 -->
                        <div class="mb-3">
                            <label for="address_line2" class="form-label fw-bold">
                                Address Line 2 <small class="text-muted">(Optional)</small>
                            </label>
                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" 
                                   id="address_line2" name="address_line2"
                                   placeholder="Apartment, suite, unit, building, floor, etc."
                                   value="{{ old('address_line2', $location->address_line2) }}">
                            @error('address_line2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City, State, Postal Code -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-bold">
                                    City <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" required
                                       placeholder="e.g., Dar es Salaam"
                                       value="{{ old('city', $location->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="state" class="form-label fw-bold">
                                    State/Region <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state"
                                       placeholder="e.g., Dar es Salaam"
                                       value="{{ old('state', $location->state) }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="postal_code" class="form-label fw-bold">
                                    Postal Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" required
                                       placeholder="e.g., 11101"
                                       value="{{ old('postal_code', $location->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="country" class="form-label fw-bold">
                                    Country <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" required
                                       value="{{ old('country', $location->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="landmark" class="form-label fw-bold">
                                    Nearby Landmark <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                       id="landmark" name="landmark"
                                       placeholder="e.g., Near City Mall"
                                       value="{{ old('landmark', $location->landmark) }}">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coordinates (Hidden - Auto-filled) -->
                        <input type="hidden" id="latitude" name="latitude" 
                               value="{{ old('latitude', $location->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" 
                               value="{{ old('longitude', $location->longitude) }}">

                        <!-- Map Preview -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Location Preview</label>
                            <div id="map" style="height: 400px; width: 100%;" class="border rounded"></div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Drag the marker to adjust location or search for a new address
                            </small>
                        </div>

                        <!-- Settings -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_primary" 
                                           name="is_primary" value="1" 
                                           {{ old('is_primary', $location->is_primary) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_primary">
                                        Set as Primary Location
                                    </label>
                                    <div class="form-text">This will be your default location for deliveries</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" 
                                           {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">
                                        Active Location
                                    </label>
                                    <div class="form-text">Inactive locations won't be used for deliveries</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('supplier.locations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-accent shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Update Location
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
<script>
    let map;
    let marker;
    let autocomplete;

    function initMap() {
        // Use existing location coordinates
        const currentLocation = { 
            lat: parseFloat('{{ $location->latitude }}'), 
            lng: parseFloat('{{ $location->longitude }}') 
        };
        
        map = new google.maps.Map(document.getElementById('map'), {
            center: currentLocation,
            zoom: 17
        });

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: currentLocation
        });

        // Update coordinates when marker is dragged
        marker.addListener('dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });

        // Initialize autocomplete
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'),
            { 
                componentRestrictions: { country: 'tz' },
                fields: ['address_components', 'geometry', 'formatted_address']
            }
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
            alert('No details available for input: ' + place.name);
            return;
        }

        // Update map
        map.setCenter(place.geometry.location);
        map.setZoom(17);
        marker.setPosition(place.geometry.location);

        // Set coordinates
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();

        // Parse address components
        let streetNumber = '';
        let route = '';
        
        for (const component of place.address_components) {
            const type = component.types[0];
            
            switch (type) {
                case 'street_number':
                    streetNumber = component.long_name;
                    break;
                case 'route':
                    route = component.long_name;
                    break;
                case 'locality':
                    document.getElementById('city').value = component.long_name;
                    break;
                case 'administrative_area_level_1':
                    document.getElementById('state').value = component.long_name;
                    break;
                case 'postal_code':
                    document.getElementById('postal_code').value = component.long_name;
                    break;
                case 'country':
                    document.getElementById('country').value = component.long_name;
                    break;
            }
        }

        // Combine street number and route for address line 1
        const address1 = [streetNumber, route].filter(Boolean).join(' ');
        if (address1) {
            document.getElementById('address_line1').value = address1;
        }
    }

    // Initialize map when page loads
    window.onload = initMap;

    // Form validation
    document.getElementById('locationForm').addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!lat || !lng) {
            e.preventDefault();
            alert('Location coordinates are required. Please ensure a valid location is selected.');
            return false;
        }
    });
</script>
@endpush
@endsection
