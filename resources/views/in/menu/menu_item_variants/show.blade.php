@extends('layouts.app')

@section('title', 'Variant Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Variant Details
            </h5>
        </div>

        <div class="card-body">
            <dl class="row">

                <dt class="col-md-3">Menu Item</dt>
                <dd class="col-md-9">{{ $menuItemVariant->menuItem->name }}</dd>

                <dt class="col-md-3">Variant Name</dt>
                <dd class="col-md-9">{{ $menuItemVariant->variant_name }}</dd>

                <dt class="col-md-3">Price Adjustment</dt>
                <dd class="col-md-9">
                    {{ $menuItemVariant->price_adjustment >= 0 ? '+' : '' }}
                    {{ number_format($menuItemVariant->price_adjustment, 2) }}
                </dd>

                <dt class="col-md-3">Available</dt>
                <dd class="col-md-9">
                    {{ $menuItemVariant->is_available ? 'Yes' : 'No' }}
                </dd>

                <dt class="col-md-3">Display Order</dt>
                <dd class="col-md-9">{{ $menuItemVariant->display_order }}</dd>

                <dt class="col-md-3">Status</dt>
                <dd class="col-md-9">{{ ucfirst($menuItemVariant->status) }}</dd>

            </dl>
        </div>
    </div>
</div>
@endsection
