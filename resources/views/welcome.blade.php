<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodHub - Order Food Delivery & Catering Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body>
    <!-- Header/Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shop"></i> FoodHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Restaurants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Daily Meals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Catering</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-cart3"></i> Cart <span class="badge bg-danger">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="#" style="background: var(--primary-color); border: none;">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Delicious Food Delivered to Your Door</h1>
                    <p class="lead mb-5">Order from top-rated restaurants, subscribe to daily meals, or book catering services in Dar es Salaam</p>
                    
                    <!-- Search Box -->
                    <div class="search-box">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="bi bi-geo-alt-fill text-primary-custom"></i>
                                    </span>
                                    <input type="text" class="form-control border-0" placeholder="Enter your delivery address" value="Dar es Salaam, TZ">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-0" placeholder="Search for restaurants or dishes">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-dark w-100" style="background: var(--dark-color);">Search</button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number text-white">250+</div>
                                <div>Restaurants</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number text-white">15K+</div>
                                <div>Orders Delivered</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-box">
                                <div class="stat-number text-white">8K+</div>
                                <div>Happy Customers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Type Cards -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Choose Your Service</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <h4>Instant Delivery</h4>
                            <p class="text-muted">Order food from your favorite restaurants and get it delivered in 30-45 minutes</p>
                            <a href="#" class="btn btn-outline-primary">Order Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h4>Daily Meals</h4>
                            <p class="text-muted">Subscribe to daily meal plans for breakfast, lunch, or dinner delivered on schedule</p>
                            <a href="#" class="btn btn-outline-primary">View Plans</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h4>Catering Services</h4>
                            <p class="text-muted">Book catering for events, parties, and corporate functions with custom menus</p>
                            <a href="#" class="btn btn-outline-primary">Get Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="py-5 bg-light-custom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Explore by Cuisine</h2>
                <a href="#" class="text-decoration-none text-primary-custom fw-semibold">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=400&fit=crop" alt="Pizza">
                        <div class="category-overlay">
                            <h5 class="mb-0">Pizza</h5>
                            <small>45 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop" alt="Salads">
                        <div class="category-overlay">
                            <h5 class="mb-0">Healthy</h5>
                            <small>32 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=400&fit=crop" alt="Asian">
                        <div class="category-overlay">
                            <h5 class="mb-0">Asian</h5>
                            <small>28 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=400&fit=crop" alt="Burgers">
                        <div class="category-overlay">
                            <h5 class="mb-0">Burgers</h5>
                            <small>38 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop" alt="Local">
                        <div class="category-overlay">
                            <h5 class="mb-0">Local Cuisine</h5>
                            <small>52 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1551782450-a2132b4ba21d?w=400&h=400&fit=crop" alt="Fast Food">
                        <div class="category-overlay">
                            <h5 class="mb-0">Fast Food</h5>
                            <small>41 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400&h=400&fit=crop" alt="Desserts">
                        <div class="category-overlay">
                            <h5 class="mb-0">Desserts</h5>
                            <small>24 Restaurants</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="category-card">
                        <img src="https://images.unsplash.com/photo-1600850056064-a8b380df8395?w=400&h=400&fit=crop" alt="Seafood">
                        <div class="category-overlay">
                            <h5 class="mb-0">Seafood</h5>
                            <small>19 Restaurants</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Suppliers -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Featured Restaurants</h2>
                <a href="#" class="text-decoration-none text-primary-custom fw-semibold">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card supplier-card shadow-sm position-relative">
                        <span class="badge badge-featured">FEATURED</span>
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Ocean Grill Restaurant</h5>
                            <p class="text-muted small mb-2">Seafood, International</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="rating-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </span>
                                    <span class="text-muted small">4.5 (234)</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="bi bi-clock"></i> 30-45 min</span>
                                <span><i class="bi bi-geo-alt"></i> 2.3 km</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card supplier-card shadow-sm position-relative">
                        <span class="badge badge-featured">FEATURED</span>
                        <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Spice Paradise</h5>
                            <p class="text-muted small mb-2">Indian, Asian Fusion</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="rating-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                    <span class="text-muted small">5.0 (189)</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="bi bi-clock"></i> 25-40 min</span>
                                <span><i class="bi bi-geo-alt"></i> 1.8 km</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card supplier-card shadow-sm">
                        <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Mama's Kitchen</h5>
                            <p class="text-muted small mb-2">Local Cuisine, Home Style</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="rating-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </span>
                                    <span class="text-muted small">4.2 (312)</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="bi bi-clock"></i> 35-50 min</span>
                                <span><i class="bi bi-geo-alt"></i> 3.1 km</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card supplier-card shadow-sm">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Pizza Corner</h5>
                            <p class="text-muted small mb-2">Italian, Pizza</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="rating-stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </span>
                                    <span class="text-muted small">4.7 (456)</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="bi bi-clock"></i> 20-35 min</span>
                                <span><i class="bi bi-geo-alt"></i> 1.2 km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5 bg-light-custom">
        <div class="container">
            <h2 class="text-center section-title">How It Works</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="how-it-works-step">
                        <div class="step-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4>1. Browse & Select</h4>
                        <p class="text-muted">Choose from hundreds of restaurants and food suppliers in your area</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="how-it-works-step">
                        <div class="step-icon">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h4>2. Place Order</h4>
                        <p class="text-muted">Add items to cart, customize, and checkout securely with multiple payment options</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="how-it-works-step">
                        <div class="step-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4>3. Fast Delivery</h4>
                        <p class="text-muted">Track your order in real-time and enjoy your delicious meal at your doorstep</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Dishes -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center section-title">Trending This Week</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="card menu-item-card shadow-sm">
                        <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=300&fit=crop" class="card-img-top" alt="Dish">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Grilled Salmon</h6>
                            <p class="text-muted small mb-2">Ocean Grill Restaurant</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary-custom">TSh 25,000</span>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card menu-item-card shadow-sm">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop" class="card-img-top" alt="Dish">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Margherita Pizza</h6>
                            <p class="text-muted small mb-2">Pizza Corner</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary-custom">TSh 18,000</span>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card menu-item-card shadow-sm">
                        <img src="https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&h=300&fit=crop" class="card-img-top" alt="Dish">
                        <div class="card-body">
                            <h6 class="card-title mb-1">Chicken Biryani</h6>
                            <p class="text-muted small mb-2">Spice Paradise</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary-custom">TSh 15,000</span>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6



                <!-- Promotional Banner -->
<section class="container my-5">
    <div class="promo-banner text-center">
        <div class="row align-items-center">
            <div class="col-md-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3">
                    <i class="bi bi-gift-fill"></i> Special Weekend Offer!
                </h2>
                <h4 class="mb-4">Get 30% OFF on your first order</h4>
                <p class="lead mb-4">Use code <strong class="fs-3">WELCOME30</strong> at checkout</p>
                <a href="#" class="btn btn-light btn-lg">Order Now</a>
                <p class="mt-3 small">*Valid for new customers only. Minimum order TSh 20,000</p>
            </div>
        </div>
    </div>
</section>

<!-- Nearby Suppliers -->
<section class="py-5 bg-light-custom">
    <div class="container">
        <h2 class="text-center section-title">Near You</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card supplier-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Burger Heaven</h5>
                        <p class="text-muted small mb-2">Fast Food, Burgers</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </span>
                                <span class="text-muted small">4.3 (178)</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-clock"></i> 20-30 min</span>
                            <span><i class="bi bi-geo-alt-fill text-danger"></i> 0.8 km</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card supplier-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Sushi Express</h5>
                        <p class="text-muted small mb-2">Japanese, Sushi</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </span>
                                <span class="text-muted small">4.6 (203)</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-clock"></i> 30-45 min</span>
                            <span><i class="bi bi-geo-alt-fill text-danger"></i> 1.5 km</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card supplier-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1555992336-fb0d29498b13?w=400&h=300&fit=crop" class="card-img-top" alt="Restaurant">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Green Leaf Cafe</h5>
                        <p class="text-muted small mb-2">Healthy, Vegetarian</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="rating-stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </span>
                                <span class="text-muted small">4.9 (145)</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-clock"></i> 25-35 min</span>
                            <span><i class="bi bi-geo-alt-fill text-danger"></i> 1.1 km</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">What Our Customers Say</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://i.pravatar.cc/60?img=1" alt="Customer" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0">Amina Hassan</h6>
                            <div class="rating-stars small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"The food quality is amazing! I love the daily meal subscription service. It saves me so much time and the food is always fresh and delicious."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://i.pravatar.cc/60?img=12" alt="Customer" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0">John Mwangi</h6>
                            <div class="rating-stars small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"Fast delivery and great customer service! The tracking feature is really helpful. I can see exactly when my food will arrive."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://i.pravatar.cc/60?img=5" alt="Customer" class="testimonial-avatar me-3">
                        <div>
                            <h6 class="mb-0">Sarah Juma</h6>
                            <div class="rating-stars small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">"I used their catering service for my company event and it was perfect! Professional service and the guests loved the food variety."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- App Download Section -->
<section class="app-download-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="display-5 fw-bold mb-4">Get the FoodHub App</h2>
                <p class="lead mb-4">Download our mobile app for a better experience and exclusive offers. Available on iOS and Android.</p>
                <div class="d-flex flex-wrap">
                    <a href="#" class="text-decoration-none">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="app-badge">
                    </a>
                    <a href="#" class="text-decoration-none">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="app-badge">
                    </a>
                </div>
                <div class="mt-4">
                    <h5>Features:</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i> Real-time order tracking</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i> Exclusive app-only deals</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i> Save your favorite restaurants</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i> Quick reorder from history</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&h=600&fit=crop" alt="Mobile App" class="img-fluid rounded" style="max-height: 500px;">
            </div>
        </div>
    </div>
</section>
