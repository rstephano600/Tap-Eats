@extends('layouts.app')

@section('title', 'Add-on Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Add-on Details
            </h5>
        </div>

        <div class="card-body">
            <dl class="row">

                <dt class="col-md-3">Menu Item</dt>
                <dd class="col-md-9">{{ $menuItemAddon->menuItem->name }}</dd>

                <dt class="col-md-3">Add-on Name</dt>
                <dd class="col-md-9">{{ $menuItemAddon->addon_name }}</dd>

                <dt class="col-md-3">Price</dt>
                <dd class="col-md-9">{{ number_format($menuItemAddon->price, 2) }}</dd>

                <dt class="col-md-3">Max Quantity</dt>
                <dd class="col-md-9">{{ $menuItemAddon->max_quantity }}</dd>

                <dt class="col-md-3">Available</dt>
                <dd class="col-md-9">{{ $menuItemAddon->is_available ? 'Yes' : 'No' }}</dd>

                <dt class="col-md-3">Status</dt>
                <dd class="col-md-9">{{ ucfirst($menuItemAddon->status) }}</dd>

            </dl>
        </div>
    </div>
</div>
@endsection
