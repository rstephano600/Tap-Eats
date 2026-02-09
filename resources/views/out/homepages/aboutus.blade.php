@extends('layouts.guest-layout')

@section('title', 'About Us - TapEats')

@section('content')
<div class="bg-primary text-white py-5 mb-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Connecting You to Local Flavors</h1>
        <p class="lead">We bridge the gap between premium food suppliers and your doorstep.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-4">Our Mission</h2>
            <p class="text-muted">
                Our platform is designed to empower local businesses while providing customers with seamless access to diverse cuisines. 
                Whether you are looking for a quick lunch or a catering proposal for a large event, we handle the logistics so you can focus on the flavor.
            </p>
        </div>
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=600&h=400&fit=crop" class="img-fluid rounded shadow" alt="Teamwork">
        </div>
    </div>

    <h2 class="text-center fw-bold mb-5">What We Offer</h2>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="mb-3">
                    <i class="bi bi-shop fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold">Verified Suppliers</h4>
                <p class="text-muted small">
                    Every restaurant on our platform goes through a strict verification process to ensure quality and safety.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="mb-3">
                    <i class="bi bi-truck fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold">Smart Delivery</h4>
                <p class="text-muted small">
                    Using precise radius-based logic, we ensure your food arrives within the optimal preparation time.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4">
                <div class="mb-3">
                    <i class="bi bi-calendar-event fs-1 text-primary"></i>
                </div>
                <h4 class="fw-bold">Catering Solutions</h4>
                <p class="text-muted small">
                    Our suppliers can provide custom catering proposals for your specific events and gatherings.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <h2 class="fw-bold text-primary">{{ $stats['restaurants'] }}+</h2>
                <p class="text-muted">Partner Restaurants</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold text-primary">{{ $stats['cities'] }}+</h2>
                <p class="text-muted">Cities Covered</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold text-primary">{{ $stats['users'] }}+</h2>
                <p class="text-muted">Happy Customers</p>
            </div>
        </div>
    </div>
</div>
@endsection