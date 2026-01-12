@extends('layouts.app')

@section('title', 'Customer Profile')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-person me-2"></i>
            {{ $customerProfile->first_name }} {{ $customerProfile->last_name }}
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $customerProfile->user->email }}</dd>

                <dt class="col-sm-3">Gender</dt>
                <dd class="col-sm-9">{{ ucfirst($customerProfile->gender ?? '-') }}</dd>

                <dt class="col-sm-3">Loyalty Points</dt>
                <dd class="col-sm-9">{{ $customerProfile->loyalty_points }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($customerProfile->status) }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
