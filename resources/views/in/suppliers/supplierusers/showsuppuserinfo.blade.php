@extends('layouts.app')

@section('title', 'View Supplier User')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Assignment Details
            </h5>
        </div>

        <div class="card-body">
            <dl class="row">

                <dt class="col-md-3">Supplier</dt>
                <dd class="col-md-9">{{ $supplierUser->supplier->business_name }}</dd>

                <dt class="col-md-3">User</dt>
                <dd class="col-md-9">{{ $supplierUser->user->name }}</dd>

                <dt class="col-md-3">Role</dt>
                <dd class="col-md-9">{{ $supplierUser->role->name }}</dd>

                <dt class="col-md-3">Primary</dt>
                <dd class="col-md-9">
                    {{ $supplierUser->is_primary ? 'Yes' : 'No' }}
                </dd>

                <dt class="col-md-3">Status</dt>
                <dd class="col-md-9">{{ $supplierUser->Status }}</dd>

                <dt class="col-md-3">Joined At</dt>
                <dd class="col-md-9">{{ $supplierUser->joined_at }}</dd>

            </dl>

            <div class="text-end">
                <a href="{{ route('supplieruserinfo') }}" class="btn btn-light">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
