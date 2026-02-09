@extends('layouts.guest-layout')

@section('title', 'TapEats - Food Delivery in Dar es Salaam')

@section('content')
<div class="container mt-5">
    <h3>Search Results ({{ $results->count() }})</h3>
    
    @forelse($results as $location)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $location->supplier->business_name }}</h5>
                <p class="card-text">
                    <strong>Branch:</strong> {{ $location->location_name }} <br>
                    <strong>Address:</strong> {{ $location->address_line1 }}, {{ $location->city }}
                </p>
                <a href="#" class="btn btn-primary">View Details</a>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            No locations found matching your search criteria.
        </div>
    @endforelse
</div>
@endsection