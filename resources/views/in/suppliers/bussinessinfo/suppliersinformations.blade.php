@extends('layouts.app')

@section('title', 'suppliers')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-key me-2 text-accent"></i> System suppliers
            </h5>
            <a href="{{ route('createsuppliersinformations') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add supplier
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
            <table class="table datatable w-100">
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
                                        $badgeClass = match($supplier->Status) {
                                            'Active' => 'success',
                                            'Inactive' => 'secondary',
                                            'Deleted' => 'warning',
                                            default => 'info'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $badgeClass }} px-3">
                                        {{ ucfirst($supplier->Status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('showsuppliersinformations', encrypt($supplier->id)) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('editsuppliersinformations', encrypt($supplier->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('destroysuppliersinformations', encrypt($supplier->id)) }}" method="POST" class="d-inline">
                                         @csrf @method('DELETE')
                                         <button type="button"
                                                 class="btn btn-sm btn-outline-danger"
                                                 title="Delete Supplier"
                                                 data-confirm
                                                 data-confirm-type="danger"
                                                 data-confirm-title="Delete Supplier?"
                                                 data-confirm-message="This will permanently delete {{ $supplier->business_name }}. This action cannot be undone."
                                                 data-confirm-ok="Yes, Delete"
                                                 data-confirm-cancel="Keep it">
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