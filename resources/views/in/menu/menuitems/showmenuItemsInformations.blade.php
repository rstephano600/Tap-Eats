@extends('layouts.app')

@section('title', 'Menu Item Details')

@section('content')
<style>
    .menu-image-wrapper {
    position: relative;
    height: 260px;
    border-radius: 14px;
    overflow: hidden;
    background: #f3f3f3;
}

.menu-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-eye me-2 text-accent"></i> Menu Item Details
            </h5>
        </div>

        <div class="card-body">

            {{-- Top section --}}
            <div class="row g-4 mb-4">

                {{-- Image --}}
                <div class="col-md-4">
                    <div class="menu-image-wrapper">
                        <img
                            src="{{ $menuItem->gallery_images && count($menuItem->gallery_images)
                                ? asset('storage/'.$menuItem->gallery_images[0])
                                : asset('images/food-placeholder.jpg') }}"
                            class="menu-image"
                            alt="{{ $menuItem->name }}"
                        >

                        @if($menuItem->is_featured)
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                ⭐ Featured
                            </span>
                        @endif

                        @if($menuItem->is_popular)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                🔥 Popular
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Core info --}}
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1">{{ $menuItem->name }}</h3>
                    <p class="text-muted mb-2">
                        {{ $menuItem->category?->category_name ?? 'Uncategorized' }}
                    </p>

                    <p class="mb-3">{{ $menuItem->description ?? 'No description provided.' }}</p>

                    {{-- Price --}}
                    <div class="mb-3">
                        @if($menuItem->has_discount)
                            <span class="text-muted text-decoration-line-through me-2">
                                {{ number_format($menuItem->price, 2) }}
                            </span>
                            <span class="fs-4 fw-bold text-danger">
                                {{ number_format($menuItem->discounted_price, 2) }}
                            </span>
                        @else
                            <span class="fs-4 fw-bold text-dark">
                                {{ number_format($menuItem->price, 2) }}
                            </span>
                        @endif
                    </div>

                    {{-- Dietary --}}
                    <div class="mb-3">
                        @foreach(['vegetarian','vegan','gluten_free','halal','spicy'] as $tag)
                            @php $field = 'is_'.$tag; @endphp
                            @if($menuItem->$field)
                                <span class="badge bg-info text-dark me-1">
                                    {{ ucfirst(str_replace('_',' ', $tag)) }}
                                </span>
                            @endif
                        @endforeach
                    </div>

                    {{-- Availability --}}
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        <span>
                            <i class="bi bi-clock me-1"></i>
                            {{ $menuItem->preparation_time ? $menuItem->preparation_time.' mins' : '—' }}
                        </span>
                        <span>
                            <i class="bi bi-people me-1"></i>
                            Serves: {{ $menuItem->serves ?? '—' }}
                        </span>
                        <span>
                            <i class="bi bi-box-seam me-1"></i>
                            {{ $menuItem->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Extra details --}}
            <div class="row g-4">

                {{-- Nutrition --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold mb-3">Nutrition</h6>
                        <ul class="list-unstyled small mb-0">
                            <li>🍽 Portion: {{ $menuItem->portion_size ?? '—' }}</li>
                            <li>🔥 Calories: {{ $menuItem->calories ?? '—' }}</li>
                        </ul>
                    </div>
                </div>

                {{-- Ingredients & Allergens --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold mb-3">Ingredients</h6>
                        @if($menuItem->ingredients)
                            @foreach($menuItem->ingredients as $ing)
                                <span class="badge bg-light text-dark border me-1 mb-1">
                                    {{ $ing }}
                                </span>
                            @endforeach
                        @else
                            <small class="text-muted">—</small>
                        @endif

                        <hr>

                        <h6 class="fw-bold mb-2">Allergens</h6>
                        @if($menuItem->allergens)
                            @foreach($menuItem->allergens as $alg)
                                <span class="badge bg-warning text-dark me-1 mb-1">
                                    {{ $alg }}
                                </span>
                            @endforeach
                        @else
                            <small class="text-muted">None</small>
                        @endif
                    </div>
                </div>

                {{-- Metrics --}}
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold mb-3">Metrics</h6>
                        <ul class="list-unstyled small mb-0">
                            <li>👁 Views: {{ $menuItem->view_count }}</li>
                            <li>🛒 Orders: {{ $menuItem->order_count }}</li>
                            <li>⭐ Rating: {{ $menuItem->average_rating }}</li>
                            <li>📦 Stock: {{ $menuItem->stock_quantity ?? 'Unlimited' }}</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
