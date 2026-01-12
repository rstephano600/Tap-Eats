
@extends('layouts.app')

@section('title', 'Location Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-geo-alt-fill me-2 text-accent"></i> Location Details
                    </h5>
                    <div>
                        <a href="{{ route('supplier.locations.edit', $location->id) }}" 
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('supplier.locations.index') }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Badges -->
                    <div class="mb-4 d-flex gap-2">
                        @if($location->is_primary)
                            <span class="badge bg-primary px-3 py-2">
                                <i class="bi bi-star-fill me-1"></i>Primary Location
                            </span>
                        @endif
                        
                        @if($location->is_active)
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Active
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i>Inactive
                            </span>
                        @endif

                        <span class="badge bg-info px-3 py-2">
                            {{ ucfirst($location->status) }}
                        </span>
                    </div>

                    <!-- Location Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                        <i class="bi bi-info-circle me-2"></i>Basic Information
                                    </h6>
                                    
                                    @if($location->location_name)
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Location Name</label>
                                            <div class="fw-bold text-darkblue">{{ $location->location_name }}</div>
                                        </div>
                                    @endif

                                    @if($location->phone)
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Phone</label>
                                            <div class="fw-bold text-darkblue">
                                                <i class="bi bi-telephone me-2"></i>{{ $location->phone }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($location->landmark)
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Landmark</label>
                                            <div class="fw-bold text-darkblue">
                                                <i class="bi bi-signpost me-2"></i>{{ $location->landmark }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                        <i class="bi bi-geo me-2"></i>Address Details
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Address</label>
                                        <div class="fw-bold text-darkblue">
                                            {{ $location->address_line1 }}
                                            @if($location->address_line2)
                                                <br>{{ $location->address_line2 }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">City & Postal Code</label>
                                        <div class="fw-bold text-darkblue">
                                            {{ $location->city }}, {{ $location->postal_code }}
                                        </div>
                                    </div>

                                    @if($location->state)
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">State/Region</label>
                                            <div class="fw-bold text-darkblue">{{ $location->state }}</div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label text-muted small mb-1">Country</label>
                                        <div class="fw-bold text-darkblue">{{ $location->country }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coordinates -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <i class="bi bi-crosshair me-2"></i>GPS Coordinates
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Latitude</label>
                                    <div class="fw-bold text-darkblue">{{ $location->latitude }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small mb-1">Longitude</label>
                                    <div class="fw-bold text-darkblue">{{ $location->longitude }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Display -->
                    <div class="card border-0 mb-4">
                        <div class="card-body p-0">
                            <div id="map" style="height: 450px; width: 100%;" class="rounded"></div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <i class="bi bi-clock-history me-2"></i>Record Information
                            </h6>
                            <div class="row small">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="text-muted">Created:</span>
                                        <span class="fw-bold text-darkblue">
                                            {{ $location->created_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    @if($location->created_by)
                                        <div class="mb-2">
                                            <span class="text-muted">Created By:</span>
                                            <span class="fw-bold text-darkblue">
                                                {{ $location->creator->name ?? 'System' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <span class="text-muted">Last Updated:</span>
                                        <span class="fw-bold text-darkblue">
                                            {{ $location->updated_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    @if($location->updated_by)
                                        <div class="mb-2">
                                            <span class="text-muted">Updated By:</span>
                                            <span class="fw-bold text-darkblue">
                                                {{ $location->updater->name ?? 'System' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        @if(!$location->is_primary)
                            <form action="{{ route('supplier.locations.set-primary', $location->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary"
                                        onclick="return confirm('Set this as your primary location?')">
                                    <i class="bi bi-star me-1"></i> Set as Primary
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('supplier.locations.toggle-active', $location->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="bi bi-toggle-{{ $location->is_active ? 'on' : 'off' }} me-1"></i>
                                {{ $location->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('supplier.locations.destroy', $location->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this location?')">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}"></script>
<script>
    function initMap() {
        const location = { 
            lat: parseFloat('{{ $location->latitude }}'), 
            lng: parseFloat('{{ $location->longitude }}') 
        };
        
        const map = new google.maps.Map(document.getElementById('map'), {
            center: location,
            zoom: 17,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
        });

        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: '{{ $location->location_name ?? "Location" }}',
            animation: google.maps.Animation.DROP
        });

        // Add info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px;">
                    <h6 class="fw-bold mb-2">{{ $location->location_name ?? "Location" }}</h6>
                    <p class="mb-1 small">{{ $location->address_line1 }}</p>
                    <p class="mb-0 small text-muted">{{ $location->city }}, {{ $location->country }}</p>
                </div>
            `
        });

        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });

        // Open info window by default
        infoWindow.open(map, marker);
    }

    window.onload = initMap;
</script>
@endpush
@endsection