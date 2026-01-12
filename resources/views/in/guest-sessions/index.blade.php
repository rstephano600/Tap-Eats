@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h4>Guest Sessions</h4>
        <a href="{{ route('guest-sessions.create') }}" class="btn btn-primary">
            Create Session
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Session Token</th>
                <th>IP Address</th>
                <th>Location</th>
                <th>Last Activity</th>
                <th>Expires At</th>
                <th>Status</th>
                <th width="220">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guestSessions as $session)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <small class="font-monospace">
                        {{ Str::limit($session->session_token, 16) }}
                    </small>
                </td>
                <td>{{ $session->ip_address }}</td>
                <td>
                    @if($session->city || $session->country)
                        {{ $session->city ? $session->city . ', ' : '' }}{{ $session->country }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ $session->last_activity_at->diffForHumans() }}</td>
                <td>
                    <small class="{{ $session->isExpired() ? 'text-danger' : 'text-success' }}">
                        {{ $session->expires_at->format('Y-m-d H:i') }}
                    </small>
                </td>
                <td>
                    <span class="badge bg-{{ $session->status == 'active' ? 'success' : ($session->status == 'locked' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('guest-sessions.show', $session->id) }}" class="btn btn-sm btn-info">
                        View
                    </a>
                    <a href="{{ route('guest-sessions.edit', $session->id) }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('guest-sessions.destroy', $session->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this session?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No guest sessions found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $guestSessions->links() }}
</div>
@endsection