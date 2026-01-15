@extends('layouts.app')

@section('title', 'Financial Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-credit-card me-2 text-accent"></i> Financial Details
            </h5>
            <a href="{{ route('supplier.financial.edit', $financial->id) }}"
               class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-md-4 text-muted">Commission Rate</dt>
                <dd class="col-md-8">{{ $financial->commission_rate }}%</dd>

                <dt class="col-md-4 text-muted">Bank</dt>
                <dd class="col-md-8">
                    {{ $financial->bank_name ?? '—' }}
                </dd>

                <dt class="col-md-4 text-muted">Account</dt>
                <dd class="col-md-8">
                    {{ $financial->bank_account_name ?? '—' }}
                </dd>

                <dt class="col-md-4 text-muted">Mobile Money</dt>
                <dd class="col-md-8">
                    {{ $financial->mobile_money_provider ?? '—' }}
                </dd>

                <dt class="col-md-4 text-muted">Primary</dt>
                <dd class="col-md-8">
                    {{ $financial->is_primary ? 'Yes' : 'No' }}
                </dd>
            </dl>

            <a href="{{ route('supplier.financial.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
