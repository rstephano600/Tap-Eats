@extends('layouts.app')

@section('title', 'Customer Profiles')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-people me-2 text-accent"></i> Customer Profiles
            </h5>
            <a href="{{ route('customer-profiles.create') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Profile
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Gender</th>
                            <th>Loyalty Points</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold">
                                    {{ $profile->first_name }} {{ $profile->last_name }}
                                    <div class="small text-muted">{{ $profile->user->email ?? '' }}</div>
                                </td>
                                <td>{{ ucfirst($profile->gender ?? '-') }}</td>
                                <td>{{ $profile->loyalty_points }}</td>
                                <td>
                                    <span class="badge bg-{{ $profile->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($profile->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('customer-profiles.show', $profile) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('customer-profiles.edit', $profile) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('customer-profiles.destroy', $profile) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this profile?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No customer profiles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $profiles->links() }}
        </div>
    </div>
</div>
@endsection
