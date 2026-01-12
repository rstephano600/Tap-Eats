@extends('layouts.app')

@section('title', 'suppliers')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-key me-2 text-accent"></i> System suppliers
            </h5>
            <a href="{{ route('suppliers.create') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add supplier
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="50">#</th>
                            <th>Business Name</th>
                            <th>Business Type</th>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                            <th>Status</th>
                            <th class="text-end pe-3" width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-bold text-darkblue">{{ $supplier->business_name }}</td>
                                <td class="fw-bold text-darkblue">{{ $supplier->BusinessType->name }}</td>

                                <td class="text-muted small">{{ $supplier->contact_email }}</td>
                                <td class="text-muted small">{{ $supplier->contact_phone }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($supplier->status) {
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'locked' => 'warning',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $badgeClass }} px-3">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Delete this supplier?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No suppliers found in the system.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection