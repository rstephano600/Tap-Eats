
@extends('layouts.app')

@section('title', 'Supplier Locations')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-geo-alt me-2 text-accent"></i> Supplier Locations
            </h5>
            <a href="{{ route('supplier.locations.create') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Location
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group" role="group">
                        <a href="{{ route('supplier.locations.index') }}" class="btn btn-sm btn-outline-primary active">
                            <i class="bi bi-list-ul me-1"></i> All
                        </a>
                        <a href="{{ route('supplier.locations.index', ['status' => 'active']) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-check-circle me-1"></i> Active
                        </a>
                        <a href="{{ route('supplier.locations.index', ['status' => 'inactive']) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Inactive
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="50">#</th>
                            <th>Location Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Primary</th>
                            <th>Status</th>
                            <th class="text-end pe-3" width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $location)
                            <tr>
                                <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-darkblue">
                                        {{ $location->location_name ?? 'Location ' . $loop->iteration }}
                                    </div>
                                    @if($location->landmark)
                                        <small class="text-muted">
                                            <i class="bi bi-signpost me-1"></i>{{ $location->landmark }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $location->address_line1 }}</div>
                                    @if($location->address_line2)
                                        <div class="small text-muted">{{ $location->address_line2 }}</div>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $location->city }}
                                    @if($location->postal_code)
                                        <br><small>{{ $location->postal_code }}</small>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $location->phone ?? '-' }}</td>
                                <td>
                                    @if($location->is_primary)
                                        <span class="badge bg-primary px-3">
                                            <i class="bi bi-star-fill me-1"></i>Primary
                                        </span>
                                    @else
                                        <form action="{{ route('supplier.locations.set-primary', $location->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                                    title="Set as Primary">
                                                <i class="bi bi-star"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('supplier.locations.toggle-active', $location->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @if($location->is_active)
                                            <span class="badge rounded-pill bg-success px-3">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3">
                                                Inactive
                                            </span>
                                        @endif
                                    </form>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('supplier.locations.show', $location->id) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('supplier.locations.edit', $location->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('supplier.locations.toggle-active', $location->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                    title="{{ $location->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $location->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('supplier.locations.destroy', $location->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Delete this location?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No locations found. Add your first location to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

