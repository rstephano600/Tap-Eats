@extends('layouts.app')

@section('title', 'FoodHub - Home')

@section('page-title', 'Welcome to FoodHub')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Home</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-body">
                <h5 class="card-title">Popular Restaurants</h5>
                <p class="card-text">Discover delicious food from top-rated restaurants near you.</p>
                
                <!-- Restaurant cards will go here -->
                <div class="row mt-4">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Restaurant">
                            <div class="card-body">
                                <h5 class="card-title">Restaurant Name</h5>
                                <p class="card-text">Italian • $$ • ★★★★☆</p>
                                <a href="#" class="btn btn-primary">View Menu</a>
                            </div>
                        </div>
                    </div>
                    <!-- Add more restaurant cards -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Page specific JavaScript
    console.log('Home page loaded');
</script>
@endsection