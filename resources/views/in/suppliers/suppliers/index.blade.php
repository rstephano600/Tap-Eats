@extends('layouts.app')

@section('title', 'My Businesses')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-shop me-2 text-accent"></i> My Businesses
            </h5>
            <a href="{{ route('supplier.suppliers.create') }}" class="btn btn-accent shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Business
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
                            <th class="ps-3">#</th>
                            <th>Business Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                         <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                         <td class="fw-bold text-darkblue">{{ $supplier->business_name }}</td>
                         <td class="text-muted">
                             {{ $supplier->businessType?->name ?? '-' }}
                         </td>
                         <td>
                             <span class="badge rounded-pill bg-{{ $supplier->is_active ? 'success' : 'secondary' }}">
                                 {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                             </span>
                         </td>
                            <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="{{ route('supplier.suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('supplier.suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('supplier.suppliers.destroy', $supplier) }}">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this business?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                         </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-shop fs-1 mb-2 d-block"></i>
                                    No businesses registered yet.
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
