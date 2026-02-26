@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-darkblue text-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shop fs-4 me-2 text-accent"></i>
                        <h5 class="mb-0 fw-bold">Supplier Profile: {{ $supplier->business_name }}</h5>
                    </div>
                    <div>
                        @role('super_admin')
                        <form action="{{ route('supplier.toggle-status', encrypt($supplier->id)) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $supplier->is_active ? 'btn-success' : 'btn-danger' }}">
                                <i class="bi {{ $supplier->is_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                        @endrole
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 border-end">
                            <div class="p-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Business Information</h6>
                                <div class="mb-3">
                                    <label class="text-muted small d-block">Business Name</label>
                                    <span class="fw-bold fs-5 text-dark">{{ $supplier->business_name }}</span>
                                    <span class="badge {{ $supplier->verification_status == 'verified' ? 'bg-info' : 'bg-secondary' }} ms-2">
                                        {{ ucfirst($supplier->verification_status) }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small d-block">Description</label>
                                    <p class="text-secondary small">{{ $supplier->description ?: 'No description provided.' }}</p>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="text-muted small d-block">License Number</label>
                                        <span class="fw-bold">{{ $supplier->license_number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="text-muted small d-block">Tax ID (TIN)</label>
                                        <span class="fw-bold">{{ $supplier->tax_id ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <hr class="my-4 opacity-25">
                                
                                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Contact Details</h6>
                                <div class="mb-2">
                                    <i class="bi bi-envelope me-2 text-muted"></i>{{ $supplier->contact_email }}
                                </div>
                                <div class="mb-2">
                                    <i class="bi bi-telephone me-2 text-muted"></i>{{ $supplier->contact_phone }}
                                </div>
                                <div class="mb-2">
                                    <i class="bi bi-globe me-2 text-muted"></i>{{ $supplier->website ?? 'No website' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 bg-light-subtle">
                            <div class="p-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Operational Settings</h6>
                                <div class="row bg-white p-3 rounded border mb-4">
                                    <div class="col-6 mb-2">
                                        <label class="text-muted d-block small">Prep Time</label>
                                        <strong>{{ $supplier->preparation_time }} mins</strong>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="text-muted d-block small">Delivery Radius</label>
                                        <strong>{{ $supplier->delivery_radius }} km</strong>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted d-block small">Min. Order</label>
                                        <strong class="text-success">TZS {{ number_format($supplier->min_order_amount, 2) }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-muted d-block small">Delivery Fee</label>
                                        <strong>TZS {{ number_format($supplier->delivery_fee, 2) }}</strong>
                                    </div>
                                </div>

                                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Financial Information</h6>
                                <div class="list-group list-group-flush border rounded">
                                    <div class="list-group-item d-flex justify-content-between small">
                                        <span class="text-muted">Bank Name:</span>
                                        <span class="fw-bold">{{ $supplier->bank_name }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between small">
                                        <span class="text-muted">Account No:</span>
                                        <span class="fw-bold text-primary">{{ $supplier->bank_account_number }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between small">
                                        <span class="text-muted">Mobile Money:</span>
                                        <span class="fw-bold">{{ $supplier->mobile_money_provider }} ({{ $supplier->mobile_money_number }})</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between small">
                                        <span class="text-muted">Commission Rate:</span>
                                        <span class="badge bg-dark">{{ $supplier->commission_rate }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-light border-top py-3">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">Created: {{ $supplier->created_at->format('d M Y') }}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Verified: {{ $supplier->verified_at ? $supplier->verified_at->format('d M Y') : 'Pending' }}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Current Status: <span class="fw-bold">{{ $supplier->Status }}</span></small>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white py-3 d-flex justify-content-between">
                    <a href="{{ route('suppliersinformations') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <a href="{{ route('editsuppliersinformations', encrypt($supplier->id)) }}" class="btn btn-darkblue px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Supplier Profile
                    </a>
                </div>
            </div>
        
    </div>
</div>
@endsection