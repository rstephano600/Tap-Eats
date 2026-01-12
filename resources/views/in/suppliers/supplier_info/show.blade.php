@extends('layouts.app')

@section('title', 'supplier Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-darkblue text-white py-3 d-flex align-items-center">
                    <i class="bi bi-shield-check fs-4 me-2 text-accent"></i>
                    <h5 class="mb-0 fw-bold">supplier Details</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-4">
                            <div class="row">
                                <div class="col-sm-4 text-muted fw-bold text-uppercase" style="font-size: 0.8rem;">supplier Name</div>
                                <div class="col-sm-8 fw-bold text-dark fs-5">{{ $supplier->name }}</div>
                            </div>
                        </li>
                        <li class="list-group-item p-4">
                            <div class="row">
                                <div class="col-sm-4 text-muted fw-bold text-uppercase" style="font-size: 0.8rem;">Status</div>
                                <div class="col-sm-8">
                                    <span class="badge rounded-pill bg-{{ $supplier->status == 'active' ? 'success' : 'warning' }} px-3">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item p-4">
                            <div class="row">
                                <div class="col-sm-4 text-muted fw-bold text-uppercase" style="font-size: 0.8rem;">Description</div>
                                <div class="col-sm-8 text-secondary">{{ $supplier->descriptions ?: 'No description provided.' }}</div>
                            </div>
                        </li>
                        <li class="list-group-item p-4 bg-light">
                            <div class="row">
                                <div class="col-sm-4 text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">System Info</div>
                                <div class="col-sm-8 small text-muted">
                                    <div class="mb-1"><i class="bi bi-calendar-plus me-2"></i>Created: {{ $supplier->created_at->format('M d, Y H:i') }}</div>
                                    <div><i class="bi bi-clock-history me-2"></i>Updated: {{ $supplier->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white py-3 d-flex justify-content-between">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-accent px-4 shadow-sm">
                        <i class="bi bi-pencil me-1"></i> Edit supplier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection