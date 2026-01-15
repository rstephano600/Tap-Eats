@extends('layouts.app')

@section('title', 'Menu Item Details')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Menu Item Details
            </h5>
        </div>

        <div class="card-body">
            <dl class="row">

                <dt class="col-md-3">Name</dt>
                <dd class="col-md-9">{{ $menuItem->name }}</dd>

                <dt class="col-md-3">Category</dt>
                <dd class="col-md-9">{{ $menuItem->category?->category_name ?? '—' }}</dd>

                <dt class="col-md-3">Price</dt>
                <dd class="col-md-9">{{ number_format($menuItem->price, 2) }}</dd>

                <dt class="col-md-3">Discounted Price</dt>
                <dd class="col-md-9">{{ $menuItem->discounted_price ?? '—' }}</dd>

                <dt class="col-md-3">Description</dt>
                <dd class="col-md-9">{{ $menuItem->description ?? '—' }}</dd>

                <dt class="col-md-3">Dietary</dt>
                <dd class="col-md-9">
                    @foreach(['vegetarian','vegan','gluten_free','halal','spicy'] as $tag)
                        @php $field = 'is_'.$tag; @endphp
                        @if($menuItem->$field)
                            <span class="badge bg-info me-1">{{ ucfirst(str_replace('_',' ', $tag)) }}</span>
                        @endif
                    @endforeach
                </dd>

                <dt class="col-md-3">Metrics</dt>
                <dd class="col-md-9">
                    Views: {{ $menuItem->view_count }},
                    Orders: {{ $menuItem->order_count }},
                    Rating: {{ $menuItem->average_rating }}
                </dd>

            </dl>
        </div>
    </div>
</div>
@endsection
