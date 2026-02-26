@extends('layouts.guest-layout')

@section('title', 'TapEats - Food Delivery in Tanzania')

@push('styles')
<style>
    /* ============================================
       HERO SECTION
       ============================================ */
    .hero-section {
        background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);
        padding: 5rem 0 4rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .hero-section h1 {
        color: white;
        font-family: 'Playfair Display', serif;
        font-weight: 900;
        line-height: 1.2;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .hero-section .lead {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.2rem;
    }

    .search-box {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .search-box input {
        border: none;
        padding: 0.75rem;
    }

    .search-box input:focus {
        outline: none;
        box-shadow: none;
    }

    .search-box button {
        background: var(--dark-color);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .search-box button:hover {
        background: #000;
        transform: scale(1.05);
    }

    .stat-box {
        text-align: center;
        padding: 1.5rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        font-family: 'Playfair Display', serif;
    }

    /* ============================================
       SERVICE CARDS
       ============================================ */
    .service-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
    }

    .service-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
        transform: rotate(10deg) scale(1.1);
    }

    .service-card h4 {
        font-weight: 700;
        margin: 1rem 0 0.75rem;
    }

    .service-card .btn {
        border-radius: 10px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* ============================================
       CATEGORY CARDS
       ============================================ */
    .bg-light-custom {
        background: #F8F9FA;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 2rem;
    }

    .category-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        height: 220px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .category-card:hover img {
        transform: scale(1.1);
    }

    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        padding: 1.5rem;
        color: white;
    }

    /* ============================================
       RESTAURANT/SUPPLIER CARDS
       ============================================ */
    .supplier-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .supplier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .supplier-card img {
        height: 200px;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .supplier-card:hover img {
        transform: scale(1.05);
    }

    .badge-featured {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--secondary-color);
        color: var(--dark-color);
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.7rem;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .rating-stars {
        color: var(--secondary-color);
    }

    /* ============================================
       HOW IT WORKS SECTION
       ============================================ */
    .how-it-works-step {
        text-align: center;
        padding: 2rem 1rem;
    }

    .step-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        transition: all 0.3s ease;
    }

    .how-it-works-step:hover .step-icon {
        transform: translateY(-10px) rotate(5deg);
        box-shadow: 0 15px 40px rgba(255, 107, 53, 0.4);
    }

    .how-it-works-step h4 {
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* ============================================
       MENU ITEM CARDS
       ============================================ */
    .menu-item-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .menu-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .menu-item-card img {
        height: 180px;
        object-fit: cover;
    }

    .menu-item-card .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .menu-item-card .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    /* ============================================
       PROMOTIONAL BANNER
       ============================================ */
    .promo-banner {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 25px;
        padding: 4rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .promo-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .promo-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .promo-banner .btn-light {
        padding: 0.875rem 2.5rem;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .promo-banner .btn-light:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    /* ============================================
       TESTIMONIALS
       ============================================ */
    .testimonial-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
    }

    /* ============================================
       APP DOWNLOAD SECTION
       ============================================ */
    .app-download-section {
        background: linear-gradient(135deg, #1A1A1A 0%, #2D2D2D 100%);
        padding: 5rem 0;
        color: white;
        margin-top: 5rem;
    }

    .app-badge {
        height: 50px;
        margin-right: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .app-badge:hover {
        transform: scale(1.05);
    }

    /* ============================================
       RESPONSIVE DESIGN
       ============================================ */
    @media (max-width: 767.98px) {
        .hero-section {
            padding: 3rem 0 2rem;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.75rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .category-card {
            height: 180px;
        }

        .promo-banner {
            padding: 2rem 1rem;
        }
    }

    .category-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    height: 200px;
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
}
</style>
<style>
/* Animated Background */
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.search-container {
    transition: transform 0.3s ease;
}

/* On mobile, stack inputs nicely */
@media (max-width: 768px) {
    .display-3 {
        font-size: 2.5rem;
    }
    .search-container {
        margin: 0 10px;
    }
}

/* Hover effects for stat boxes */
.stat-card {
    transition: all 0.3s ease;
}
.stat-card:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-5px);
}

.btn-warning {
    background-color: #ffc107;
    border: none;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.btn-warning:hover {
    background-color: #e0a800;
    transform: scale(1.02);
}
</style>
<style>
.promo-banner {
    /* Vibrant Gradient Mesh */
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.promo-overlay {
    background-image: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                      radial-gradient(circle at 80% 70%, rgba(0, 0, 0, 0.1) 0%, transparent 50%);
    opacity: 0.6;
}

.promo-code-box {
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.promo-code-box:hover {
    transform: scale(1.05);
}

.fw-black { font-weight: 900; }
.fw-mono { font-family: 'Courier New', Courier, monospace; }

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.animate-bounce {
    animation: bounce 2s infinite ease-in-out;
}

.btn-warning {
    background: #ffc107;
    color: #000;
    border: none;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: #fff;
    color: #ff416c;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.btn-light:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden py-5 d-flex align-items-center min-vh-100" style="background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); background-size: 400% 400%; animation: gradientBG 15s ease infinite;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-10 mx-auto text-center">
                <span class="badge bg-white text-dark rounded-pill px-3 py-2 mb-4 shadow-sm animate-fade-in-up">
                    <i class="bi bi-star-fill text-warning me-1"></i> #1 Food Delivery in Tanzania
                </span>

                <h1 class="display-3 fw-bolder text-white mb-4 animate-fade-in-up" style="letter-spacing: -1px;">
                    Delicious Food <span class="text-warning">Delivered</span> to Your Door
                </h1>
                
                <p class="lead text-white-50 mb-5 mx-auto animate-fade-in-up" style="max-width: 700px; animation-delay: 0.2s;">
                    Discover top-rated restaurants, subscribe to daily meal plans, or book professional catering services with ease.
                </p>
<!--                 
                <div class="search-container p-2 p-md-3 bg-white rounded-4 shadow-lg mx-auto animate-fade-in-up" style="max-width: 900px; animation-delay: 0.4s;">
                    <form action="{{ route('searchsupplierlocation') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-12 col-md-5">
                                <div class="input-group h-100">
                                    <span class="input-group-text bg-light border-0 py-3">
                                        <i class="bi bi-geo-alt-fill text-danger"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-light py-3" name="location" placeholder="Your delivery address...">
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="input-group h-100">
                                    <span class="input-group-text bg-light border-0 py-3">
                                        <i class="bi bi-search text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-light py-3" name="query" placeholder="Restaurants or dishes...">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-warning w-100 h-100 py-3 fw-bold text-uppercase">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div> -->

                <div class="row mt-5 pt-4 g-4 justify-content-center">
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="stat-card p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-25 animate-fade-in-up" style="animation-delay: 0.6s; backdrop-filter: blur(5px);">
                            <h3 class="fw-bold text-white mb-0">{{ $supplierCount }}+</h3>
                            <small class="text-white-50">Restaurants</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="stat-card p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-25 animate-fade-in-up" style="animation-delay: 0.7s; backdrop-filter: blur(5px);">
                            <h3 class="fw-bold text-white mb-0">{{ $customerProfile }}+</h3>
                            <small class="text-white-50">Happy Customers</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="stat-card p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-25 animate-fade-in-up" style="animation-delay: 0.8s; backdrop-filter: blur(5px);">
                            <h3 class="fw-bold text-white mb-0">15K+</h3>
                            <small class="text-white-50">Orders Delivered</small>
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
                        <a href="{{ route('restaurantspublic') }}" class="btn btn-outline-primary">Order Now</a>
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
                        <a href="{{ route('dailymenuitems') }}" class="btn btn-outline-primary">View Plans</a>
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
                        <a href="{{ route('catering') }}" class="btn btn-outline-primary">Get Quote</a>
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
            <a href="{{ route('dailymenuitems') }}" class="text-decoration-none text-primary-custom fw-semibold">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-6 col-md-3">
                <a href="{{ route('restaurantspublic', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="category-card">
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/default-cuisine.jpg') }}" 
                             alt="{{ $category->category_name }}">
                        <div class="category-overlay">
                            <h5 class="mb-0">{{ $category->category_name }}</h5>
                            <small>{{ $category->restaurants_count }} Restaurants</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Restaurants -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Featured Restaurants</h2>
            <a href="{{ route('restaurantspublic') }}" class="text-decoration-none text-primary-custom fw-semibold">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($featuredSuppliers as $supplier)
                <div class="col-md-3">
                    <div class="card supplier-card shadow-sm h-100 position-relative border-0" style="cursor: pointer;" 
                         onclick="window.location.href='{{ route('restaurantsshow', [encrypt($supplier->id)]) }}'">
                        
                        @if($supplier->is_featured)
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 shadow-sm" style="z-index: 10;">FEATURED</span>
                        @endif

                        <img src="{{ $supplier->cover_image ? asset('storage/' . $supplier->cover_image) : asset('images/default-restaurant.jpg') }}" class="card-img-top" alt="{{ $supplier->business_name }}" >

                        <div class="card-body">
                            <h5 class="card-title mb-1 text-truncate">{{ $supplier->business_name }}</h5>
                            
                            <p class="text-muted small mb-2 text-truncate">
                                {{ $supplier->categories->pluck('category_name')->implode(', ') }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="text-warning small">
                                        {{-- Generating stars based on rating --}}
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $supplier->average_rating ? '-fill' : ($i - 0.5 <= $supplier->average_rating ? '-half' : '') }}"></i>
                                        @endfor
                                    </span>
                                    <span class="text-muted small ms-1">{{ number_format($supplier->average_rating, 1) }} ({{ $supplier->total_reviews }})</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between text-muted small border-top pt-2">
                                <span><i class="bi bi-clock me-1"></i> {{ $supplier->preparation_time ?? '30-45' }} min</span>
                                <span><i class="bi bi-bicycle me-1"></i> ${{ number_format($supplier->delivery_fee, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No featured restaurants found.</p>
                </div>
            @endforelse
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

<!-- Promotional Banner -->
<section class="container my-5">
    <div class="promo-banner position-relative overflow-hidden rounded-5 shadow-lg py-5 px-4 text-white text-center">
        <div class="position-absolute top-0 start-0 w-100 h-100 promo-overlay"></div>
        
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-md-9 mx-auto">
                <div class="badge bg-white text-danger fw-bold px-3 py-2 mb-3 rounded-pill shadow-sm animate-bounce">
                    LIMITED TIME ONLY
                </div>
                
                <h2 class="display-5 fw-black mb-2">
                    <i class="bi bi-gift-fill me-2"></i>Special Weekend Offer!
                </h2>
                
                <h4 class="fw-light mb-4 opacity-90">Get <span class="fw-bold text-warning">30% OFF</span> on your first order</h4>
                
                <div class="promo-code-box d-inline-block bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-4 mb-4">
                    <p class="mb-1 small text-uppercase fw-bold opacity-75">Use code at checkout</p>
                    <div class="fs-2 fw-mono fw-bold tracking-widest text-warning">WELCOME30</div>
                </div>

                <div class="d-block mt-2">
                    @auth
                        <a href="{{ route('dailymenuitems') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold rounded-pill shadow">
                            Explore Today's Menu <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('showRegisterForm') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-pill shadow text-danger">
                            Join Now & Save <i class="bi bi-person-plus-fill ms-2"></i>
                        </a>
                    @endauth
                </div>

                <p class="mt-4 small opacity-75 italic">
                    *Valid for new customers only. Minimum order 20,000 TZS
                </p>
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
                <h2 class="display-5 fw-bold mb-4">Get the TapEats App</h2>
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
                <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&h=600&fit=crop" alt="Mobile App" class="img-fluid rounded" style="max-height: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            </div>
        </div>
    </div>
</section>
@endsection
