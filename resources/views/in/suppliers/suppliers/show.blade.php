@extends('layouts.app')

@section('title', 'Business Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-shop-window me-2 text-accent"></i> {{ $supplier->business_name }}
            </h5>
            <a href="{{ route('supplier.suppliers.edit', $supplier) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-md-3 text-muted">Business Type</dt>
                <dd class="col-md-9">    {{ $supplier->businessType?->name ?? 'Not specified' }}</dd>

                <dt class="col-md-3 text-muted">Description</dt>
                <dd class="col-md-9">{{ $supplier->description ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Contact Email</dt>
                <dd class="col-md-9">{{ $supplier->contact_email }}</dd>

                <dt class="col-md-3 text-muted">Contact Phone</dt>
                <dd class="col-md-9">{{ $supplier->contact_phone }}</dd>

                <dt class="col-md-3 text-muted">Status</dt>
                <dd class="col-md-9">
                    <span class="badge bg-{{ $supplier->is_active ? 'success' : 'secondary' }}">
                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </dl>

            <div class="mt-4">
                <a href="{{ route('supplier.suppliers.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
