@extends('layouts.guest-layout')

@section('title', 'Daily Meals - TapEats')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0 3rem;
        margin-bottom: 2rem;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        position: sticky;
        top: 20px;
    }

    .meal-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s;
        height: 100%;
        overflow: hidden;
    }

    .meal-card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transform: translateY(-4px);
    }

    .meal-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .badge-custom {
        padding: 0.35rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .dietary-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .price-tag {
        background: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 1.25rem;
        font-weight: bold;
    }

    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444;
        color: white;
        padding: 0.5rem;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .meal-type-filter {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .meal-type-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 20px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
    }

    .meal-type-btn:hover,
    .meal-type-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .category-section {
        margin-bottom: 3rem;
    }

    .category-header {
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .add-to-cart-btn {
        transition: all 0.3s;
    }

    .add-to-cart-btn:hover {
        transform: scale(1.05);
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Daily Meal Plans</h1>
                <p class="lead mb-4">
                    Fresh, delicious meals delivered daily. Choose from breakfast, lunch, or dinner options.
                </p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <div class="h3 fw-bold">{{ $totalItems }}</div>
                        <small>Available Meals</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">{{ count($menuByCategory) }}</div>
                        <small>Categories</small>
                    </div>
                    <div class="text-center">
                        <div class="h3 fw-bold">{{ $featuredItems->count() }}</div>
                        <small>Featured</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filter-card">
                <h5 class="mb-3">
                    <i class="bi bi-funnel"></i> Filters
                </h5>

                <form action="{{ route('dailymenuitems') }}" method="GET">
                    <!-- Search -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Search Meals</label>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name..." 
                                   value="{{ $search }}">
                        </div>
                    </div>

                    <!-- Meal Type -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Meal Type</label>
                        <select name="meal_type" class="form-select">
                            <option value="">All Meals</option>
                            <option value="breakfast" {{ $mealType == 'breakfast' ? 'selected' : '' }}>
                                Breakfast ({{ $mealTypeCounts['breakfast'] }})
                            </option>
                            <option value="lunch" {{ $mealType == 'lunch' ? 'selected' : '' }}>
                                Lunch ({{ $mealTypeCounts['lunch'] }})
                            </option>
                            <option value="dinner" {{ $mealType == 'dinner' ? 'selected' : '' }}>
                                Dinner ({{ $mealTypeCounts['dinner'] }})
                            </option>
                        </select>
                    </div>

                    <!-- Dietary Preferences -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Dietary Preferences</label>
                        <select name="dietary" class="form-select">
                            <option value="">All Options</option>
                            <option value="vegetarian" {{ $dietaryFilter == 'vegetarian' ? 'selected' : '' }}>
                                <i class="bi bi-leaf"></i> Vegetarian
                            </option>
                            <option value="vegan" {{ $dietaryFilter == 'vegan' ? 'selected' : '' }}>
                                🌱 Vegan
                            </option>
                            <option value="gluten_free" {{ $dietaryFilter == 'gluten_free' ? 'selected' : '' }}>
                                Gluten Free
                            </option>
                            <option value="halal" {{ $dietaryFilter == 'halal' ? 'selected' : '' }}>
                                ☪️ Halal
                            </option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Price Range</label>
                        <select name="price_range" class="form-select">
                            <option value="">All Prices</option>
                            <option value="under_10" {{ $priceRange == 'under_10' ? 'selected' : '' }}>Under $10</option>
                            <option value="10_20" {{ $priceRange == '10_20' ? 'selected' : '' }}>$10 - $20</option>
                            <option value="20_30" {{ $priceRange == '20_30' ? 'selected' : '' }}>$20 - $30</option>
                            <option value="above_30" {{ $priceRange == 'above_30' ? 'selected' : '' }}>Above $30</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    
                    @if($search || $mealType || $dietaryFilter || $priceRange)
                        <a href="{{ route('dailymenuitems') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i> Clear Filters
                        </a>
                    @endif
                </form>

                <!-- Quick Stats -->
                <div class="mt-4 pt-4 border-top">
                    <h6 class="mb-3">Quick Info</h6>
                    <div class="small text-muted">
                        <p class="mb-2">
                            <i class="bi bi-clock text-primary"></i> 
                            Fresh meals daily
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-truck text-success"></i> 
                            Free delivery over $25
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-shield-check text-info"></i> 
                            Quality guaranteed
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meals Grid -->
        <div class="col-lg-9">
            @if($menuByCategory->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No meals found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                    <a href="{{ route('dailymenuitems') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Reset Filters
                    </a>
                </div>
            @else
                @foreach($menuByCategory as $categoryName => $items)
                    <div class="category-section">
                        <div class="category-header">
                            <h3 class="mb-0">
                                <i class="bi bi-grid"></i> {{ $categoryName }}
                                <span class="badge bg-white text-dark ms-2">{{ $items->count() }} items</span>
                            </h3>
                        </div>

                        <div class="row g-4">
                            @foreach($items as $item)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card meal-card">
                                        <!-- Image -->
                                        <div class="position-relative">
                                            <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/default-food.jpg') }}" 
                                                 class="meal-image" 
                                                 alt="{{ $item->name }}">
                                            
                                            @if($item->has_discount)
                                                <div class="discount-badge">
                                                    {{ $item->discount_percentage }}% OFF
                                                </div>
                                            @endif

                                            @if($item->is_featured)
                                                <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                                                    <i class="bi bi-star-fill"></i> Featured
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="card-body">
                                            <!-- Supplier -->
                                            <p class="text-muted small mb-1">
                                                <i class="bi bi-shop"></i> {{ $item->supplier->business_name ?? 'N/A' }}
                                            </p>

                                            <!-- Name -->
                                            <h5 class="card-title mb-2">{{ $item->name }}</h5>

                                            <!-- Description -->
                                            <p class="text-muted small mb-2">
                                                {{ Str::limit($item->description, 80) }}
                                            </p>

                                            <!-- Dietary Badges -->
                                            <div class="mb-2">
                                                @if($item->is_vegetarian)
                                                    <span class="dietary-badge bg-success bg-opacity-10 text-success">
                                                        <i class="bi bi-leaf"></i> Vegetarian
                                                    </span>
                                                @endif
                                                @if($item->is_vegan)
                                                    <span class="dietary-badge bg-success bg-opacity-10 text-success">
                                                        🌱 Vegan
                                                    </span>
                                                @endif
                                                @if($item->is_gluten_free)
                                                    <span class="dietary-badge bg-info bg-opacity-10 text-info">
                                                        Gluten Free
                                                    </span>
                                                @endif
                                                @if($item->is_halal)
                                                    <span class="dietary-badge bg-primary bg-opacity-10 text-primary">
                                                        ☪️ Halal
                                                    </span>
                                                @endif
                                                @if($item->is_spicy)
                                                    <span class="dietary-badge bg-danger bg-opacity-10 text-danger">
                                                        🌶️ Spicy
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Additional Info -->
                                            <div class="small text-muted mb-3">
                                                @if($item->preparation_time)
                                                    <span class="me-2">
                                                        <i class="bi bi-clock"></i> {{ $item->preparation_time }} min
                                                    </span>
                                                @endif
                                                @if($item->calories)
                                                    <span class="me-2">
                                                        <i class="bi bi-fire"></i> {{ $item->calories }} cal
                                                    </span>
                                                @endif
                                                @if($item->serves)
                                                    <span>
                                                        <i class="bi bi-people"></i> Serves {{ $item->serves }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Price & Add to Cart -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    @if($item->has_discount)
                                                        <div>
                                                            <span class="text-decoration-line-through text-muted small">
                                                                ${{ number_format($item->price, 2) }}
                                                            </span>
                                                            <div class="price-tag d-inline-block">
                                                                ${{ number_format($item->discounted_price, 2) }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="price-tag">
                                                            ${{ number_format($item->price, 2) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <button class="btn btn-primary add-to-cart-btn" 
                                                        onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->current_price }}, '{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/default-food.jpg') }}', {{ $item->supplier_id }})">
                                                    <i class="bi bi-cart-plus"></i> Add
                                                </button>
                                            </div>

                                            <!-- Stock Status -->
                                            @if($item->stock_quantity !== null)
                                                <div class="mt-2">
                                                    @if($item->stock_quantity > 10)
                                                        <small class="text-success">
                                                            <i class="bi bi-check-circle"></i> In Stock
                                                        </small>
                                                    @elseif($item->stock_quantity > 0)
                                                        <small class="text-warning">
                                                            <i class="bi bi-exclamation-triangle"></i> Only {{ $item->stock_quantity }} left
                                                        </small>
                                                    @else
                                                        <small class="text-danger">
                                                            <i class="bi bi-x-circle"></i> Out of Stock
                                                        </small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Refresh Notice -->
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i> 
                <strong>Tip:</strong> Refresh the page to see items in a different random order!
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar (reuse from menu.blade.php) -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="d-flex flex-column h-100">
        <!-- Cart Header -->
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h4 class="mb-0">Your Cart</h4>
            <button onclick="toggleCart()" class="btn-close"></button>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" class="flex-fill overflow-auto p-3">
            <p class="text-muted text-center py-5">Your cart is empty</p>
        </div>

        <!-- Cart Footer -->
        <div class="border-top p-3">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-semibold">Total:</span>
                <span id="cartTotal" class="fw-bold fs-5 text-primary">$0.00</span>
            </div>
            
            <button onclick="proceedToCheckout()" id="checkoutBtn" class="btn btn-primary w-100 py-3">
                <i class="bi bi-check-circle me-2"></i>
                Proceed to Checkout
            </button>
        </div>
    </div>
</div>

<div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>

<!-- Floating Cart Button -->
<button onclick="toggleCart()" class="floating-cart btn btn-primary btn-lg rounded-circle shadow-lg" style="width: 60px; height: 60px; position: fixed; bottom: 20px; right: 20px; z-index: 1030;">
    <i class="bi bi-cart3"></i>
    <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
        0
    </span>
</button>

<script>
    const MIN_ORDER_AMOUNT = 10.00;
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Add item to cart
    function addToCart(id, name, price, image, supplier_id) {
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                id: id,
                name: name,
                price: parseFloat(price),
                image: image,
                quantity: 1,
                supplier_id: supplier_id
            });
        }
        
        saveCart();
        updateCart();
        showCartNotification(name);
    }

    // Update cart quantity
    function updateQuantity(id, change) {
        const item = cart.find(i => i.id === id);
        
        if (item) {
            item.quantity += change;
            
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
            
            saveCart();
            updateCart();
        }
    }

    // Remove item from cart
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        updateCart();
    }

    // Save cart to localStorage
    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Update cart display
    function updateCart() {
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const cartBadge = document.getElementById('cartBadge');
        const checkoutBtn = document.getElementById('checkoutBtn');
        
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        // Update badge
        if (totalItems > 0) {
            cartBadge.style.display = 'inline-block';
            cartBadge.textContent = totalItems;
        } else {
            cartBadge.style.display = 'none';
        }
        
        // Update total
        cartTotal.textContent = `$${total.toFixed(2)}`;
        
        // Enable/disable checkout button
        checkoutBtn.disabled = total < MIN_ORDER_AMOUNT;
        
        // Update cart items
        if (cart.length === 0) {
            cartItems.innerHTML = '<p class="text-muted text-center py-5">Your cart is empty</p>';
        } else {
            cartItems.innerHTML = cart.map(item => `
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="${item.image}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="${item.name}">
                            <div class="flex-fill ms-3">
                                <h6 class="mb-1">${item.name}</h6>
                                <p class="mb-0 text-primary fw-bold">$${item.price.toFixed(2)}</p>
                            </div>
                            <button onclick="removeFromCart(${item.id})" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div class="btn-group" role="group">
                                <button onclick="updateQuantity(${item.id}, -1)" class="btn btn-sm btn-outline-secondary">-</button>
                                <button class="btn btn-sm btn-outline-secondary" disabled>${item.quantity}</button>
                                <button onclick="updateQuantity(${item.id}, 1)" class="btn btn-sm btn-outline-secondary">+</button>
                            </div>
                            <span class="fw-bold">$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }

    // Toggle cart sidebar
    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('cartOverlay');
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Show notification when item added
    function showCartNotification(itemName) {
        const btn = event.target.closest('.add-to-cart-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 1000);
    }

    // Proceed to checkout
    async function proceedToCheckout() {
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        try {
            const response = await fetch('{{ route("cart.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cart_data: JSON.stringify(cart)
                })
            });

            if (response.ok) {
                window.location.href = '{{ route("checkoutindex") }}';
            } else {
                throw new Error('Failed to sync cart');
            }
        } catch (error) {
            console.error('Error syncing cart:', error);
            alert('Failed to proceed to checkout. Please try again.');
        }
    }

    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCart();
    });

    // Close cart with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');
            
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        }
    });
</script>

<style>
    .cart-sidebar {
        position: fixed;
        right: 0;
        top: 0;
        height: 100vh;
        width: 400px;
        background: white;
        box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 1050;
    }

    .cart-sidebar.active {
        transform: translateX(0);
    }

    .cart-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        z-index: 1040;
    }

    .cart-overlay.active {
        display: block;
    }

    @media (max-width: 768px) {
        .cart-sidebar {
            width: 100%;
        }
    }
</style>
@endsection