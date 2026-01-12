@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Edit Guest Session</h4>
        <a href="{{ route('guest-sessions.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('guest-sessions.update', $guestSession->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Session Token</label>
                    <input type="text" class="form-control" value="{{ $guestSession->session_token }}" disabled>
                    <small class="form-text text-muted">Auto-generated, cannot be modified</small>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ip_address') is-invalid @enderror"
                               id="ip_address" name="ip_address" 
                               value="{{ old('ip_address', $guestSession->ip_address) }}" required>
                        @error('ip_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="device_id" class="form-label">Device ID</label>
                        <input type="text" class="form-control @error('device_id') is-invalid @enderror"
                               id="device_id" name="device_id" 
                               value="{{ old('device_id', $guestSession->device_id) }}">
                        @error('device_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="user_agent" class="form-label">User Agent</label>
                    <input type="text" class="form-control @error('user_agent') is-invalid @enderror"
                           id="user_agent" name="user_agent" 
                           value="{{ old('user_agent', $guestSession->user_agent) }}">
                    @error('user_agent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                               id="latitude" name="latitude" 
                               value="{{ old('latitude', $guestSession->latitude) }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                               id="longitude" name="longitude" 
                               value="{{ old('longitude', $guestSession->longitude) }}">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="location_address" class="form-label">Location Address</label>
                    <input type="text" class="form-control @error('location_address') is-invalid @enderror"
                           id="location_address" name="location_address" 
                           value="{{ old('location_address', $guestSession->location_address) }}">
                    @error('location_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="city" name="city" 
                               value="{{ old('city', $guestSession->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                               id="country" name="country" 
                               value="{{ old('country', $guestSession->country) }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="preferences" class="form-label">Preferences (JSON)</label>
                    <textarea class="form-control @error('preferences') is-invalid @enderror"
                              id="preferences" name="preferences" rows="3">{{ old('preferences', json_encode($guestSession->preferences ?? [], JSON_PRETTY_PRINT)) }}</textarea>
                    <small class="form-text text-muted">Enter valid JSON format</small>
                    @error('preferences')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="last_activity_at" class="form-label">Last Activity At <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('last_activity_at') is-invalid @enderror"
                               id="last_activity_at" name="last_activity_at" 
                               value="{{ old('last_activity_at', $guestSession->last_activity_at->format('Y-m-d\TH:i')) }}" required>
                        @error('last_activity_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="expires_at" class="form-label">Expires At <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                               id="expires_at" name="expires_at" 
                               value="{{ old('expires_at', $guestSession->expires_at->format('Y-m-d\TH:i')) }}" required>
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="active" {{ old('status', $guestSession->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $guestSession->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="locked" {{ old('status', $guestSession->status) == 'locked' ? 'selected' : '' }}>Locked</option>
                        <option value="deleted" {{ old('status', $guestSession->status) == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Session</button>
                    <a href="{{ route('guest-sessions.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection