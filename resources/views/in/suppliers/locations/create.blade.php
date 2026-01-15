
@extends('layouts.app')

@section('title', 'Add Location')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-geo-alt-fill me-2 text-accent"></i> Add New Location
                    </h5>
                    <a href="{{ route('supplier.locations.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('supplier.locations.store') }}" method="POST" id="locationForm">
                        @csrf

                        <!-- Google Places Autocomplete -->
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Search your location:</strong> Start typing your address below to search using Google Maps
                        </div>
<input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
<input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Search Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="autocomplete" 
                                           placeholder="Start typing your address...">
                                </div>
                                <small class="text-muted">Select from suggestions to auto-fill the form below</small>
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
                                       value="{{ old('location_name') }}">
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
                                       value="{{ old('phone') }}">
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
                                   value="{{ old('address_line1') }}">
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
                                   value="{{ old('address_line2') }}">
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
                                       value="{{ old('city') }}">
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
                                       value="{{ old('state') }}">
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
                                       value="{{ old('postal_code') }}">
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
                                       value="{{ old('country', 'Tanzania') }}">
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
                                       value="{{ old('landmark') }}">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coordinates (Hidden - Auto-filled) -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        <!-- Map Preview -->
                        <!-- <div class="mb-3">
                            <label class="form-label fw-bold">Location Preview</label>
                            <div id="map" style="height: 400px; width: 100%;" class="border rounded"></div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Map will update when you select a location from search
                            </small>
                        </div> -->

                        <div class="mb-3">
    <label class="form-label fw-semibold">Select Location on Map</label>
    <div id="map" style="height: 350px; width: 100%;" class="border rounded"></div>
</div>


                        <!-- Primary Location Checkbox -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_primary" 
                                       name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_primary">
                                    Set as Primary Location
                                </label>
                                <div class="form-text">This will be your default/main location for deliveries</div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('supplier.locations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-accent shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Save Location
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
        // Default to Dar es Salaam
        const defaultLocation = { lat: -6.7924, lng: 39.2083 };
        
        map = new google.maps.Map(document.getElementById('map'), {
            center: defaultLocation,
            zoom: 13
        });

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: defaultLocation
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
            alert('Please select a location from the search suggestions to set coordinates.');
            return false;
        }
    });

    
let map, marker;

function initMap() {
    const defaultPosition = { lat: -6.7924, lng: 39.2083 }; // Dar es Salaam

    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultPosition,
        zoom: 13,
    });

    marker = new google.maps.Marker({
        position: defaultPosition,
        map: map,
        draggable: true
    });

    // Set initial values
    document.getElementById('latitude').value = defaultPosition.lat;
    document.getElementById('longitude').value = defaultPosition.lng;

    // When marker is dragged
    marker.addListener('dragend', function () {
        const position = marker.getPosition();
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();
    });

    // When clicking on map
    map.addListener('click', function (event) {
        marker.setPosition(event.latLng);
        document.getElementById('latitude').value = event.latLng.lat();
        document.getElementById('longitude').value = event.latLng.lng();
    });
}

window.onload = initMap;


</script>
@endpush
@endsection