@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Guest Session Details</h4>
        <div>
            <a href="{{ route('guest-sessions.edit', $guestSession->id) }}" class="btn btn-warning">
                Edit
            </a>
            <a href="{{ route('guest-sessions.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Session Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Session Token:</strong>
                </div>
                <div class="col-md-9">
                    <code>{{ $guestSession->session_token }}</code>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>IP Address:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->ip_address }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Device ID:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->device_id ?? 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>User Agent:</strong>
                </div>
                <div class="col-md-9">
                    <small>{{ $guestSession->user_agent ?? 'N/A' }}</small>
                </div>
            </div>

            <hr>

            <h6 class="mb-3">Location Information</h6>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Coordinates:</strong>
                </div>
                <div class="col-md-9">
                    @if($guestSession->latitude && $guestSession->longitude)
                        {{ $guestSession->latitude }}, {{ $guestSession->longitude }}
                        <a href="https://www.google.com/maps?q={{ $guestSession->latitude }},{{ $guestSession->longitude }}" 
                           target="_blank" class="btn btn-sm btn-link">
                            View on Map
                        </a>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Address:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->location_address ?? 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>City:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->city ?? 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Country:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->country ?? 'N/A' }}
                </div>
            </div>

            <hr>

            <h6 class="mb-3">Session Details</h6>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Status:</strong>
                </div>
                <div class="col-md-9">
                    <span class="badge bg-{{ $guestSession->status == 'active' ? 'success' : ($guestSession->status == 'locked' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($guestSession->status) }}
                    </span>
                    @if($guestSession->isExpired())
                        <span class="badge bg-warning text-dark ms-2">Expired</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Last Activity:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->last_activity_at->format('Y-m-d H:i:s') }}
                    <small class="text-muted">({{ $guestSession->last_activity_at->diffForHumans() }})</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Expires At:</strong>
                </div>
                <div class="col-md-9">
                    <span class="{{ $guestSession->isExpired() ? 'text-danger' : 'text-success' }}">
                        {{ $guestSession->expires_at->format('Y-m-d H:i:s') }}
                        <small>({{ $guestSession->expires_at->diffForHumans() }})</small>
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Preferences:</strong>
                </div>
                <div class="col-md-9">
                    @if($guestSession->preferences)
                        <pre class="bg-light p-2 rounded"><code>{{ json_encode($guestSession->preferences, JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <span class="text-muted">No preferences set</span>
                    @endif
                </div>
            </div>

            <hr>

            <h6 class="mb-3">Audit Information</h6>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Created By:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->creator->name ?? 'System' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Created At:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->created_at->format('Y-m-d H:i:s') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Updated By:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->updater->name ?? 'N/A' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Updated At:</strong>
                </div>
                <div class="col-md-9">
                    {{ $guestSession->updated_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <form action="{{ route('guest-sessions.destroy', $guestSession->id) }}" 
              method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this session?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Session</button>
        </form>
    </div>
</div>
@endsection