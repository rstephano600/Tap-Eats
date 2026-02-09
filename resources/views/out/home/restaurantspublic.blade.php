@extends('layouts.guest-layout')

@section('title', 'TapEats - Restaurants')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Available Restaurants</h2>

    <div class="row g-4">
        @forelse($suppliers as $supplier)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
<img
    src="{{ $supplier->cover_image 
        ? asset('storage/' . $supplier->cover_image) 
        : asset('images/default-restaurant.jpg') }}"
    class="card-img-top"
    alt="{{ $supplier->business_name }}"
>

                    <div class="card-body">
                        <h5>{{ $supplier->business_name }}</h5>

                        <p class="text-muted small">
                            ⭐ {{ number_format($supplier->average_rating, 1) }}
                            • {{ $supplier->total_reviews }} reviews
                        </p>

                        <p class="small">
                            {{ Str::limit($supplier->description, 80) }}
                        </p>

                        <a href="{{ route('restaurantsshow', [encrypt($supplier->id)]) }}"
                           class="btn btn-primary btn-sm w-100">
                            View Menu
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No restaurants available at the moment.</p>
        @endforelse
    </div>


</div>

@endsection
