@extends('layouts.guest-layout')

@section('title', 'TapEats - Restaurant menu')

@section('content')
<style>
    .restaurant-hero {
        position: relative;
        min-height: 320px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 0 0 24px 24px;
        overflow: hidden;
    }

    /* Dark gradient overlay for readability */
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.35),
            rgba(0, 0, 0, 0.75)
        );
        z-index: 1;
    }

    /* Content above overlay */
    .hero-content {
        position: relative;
        z-index: 2;
        padding: 4rem 1rem;
        color: #fff;
    }

    /* Title */
    .hero-title {
        font-size: clamp(1.8rem, 4vw, 3rem);
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    /* Description */
    .hero-desc {
        max-width: 600px;
        font-size: 1rem;
        opacity: 0.95;
        margin-bottom: 0.75rem;
    }

    /* Meta (rating) */
    .hero-meta {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Mobile optimization */
    @media (max-width: 768px) {
        .restaurant-hero {
            min-height: 240px;
        }

        .hero-content {
            padding: 3rem 1rem;
        }
    }

    /* Cart Sidebar Styles */
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

    @keyframes cartBadgePulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .cart-badge-pulse {
        animation: cartBadgePulse 0.3s ease;
    }

    /* Floating Cart Button */
    .floating-cart {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1030;
    }

    .checkout-btn {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 1rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
    }

    .checkout-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .cart-sidebar {
            width: 100%;
        }
    }
</style>

<div class="restaurant-hero"
     style="background-image: url('{{ 
        $supplier->cover_image 
            ? asset('storage/'.$supplier->cover_image) 
            : asset('images/default-cover.jpg') 
     }}');">

    <div class="hero-overlay"></div>

    <div class="container hero-content">
        <h1 class="hero-title">{{ $supplier->business_name }}</h1>
        <p class="hero-desc">{{ $supplier->description }}</p>

        <div class="hero-meta">
            ⭐ {{ number_format($supplier->average_rating, 1) }}
            <span class="mx-1">•</span>
            {{ $supplier->total_reviews }} reviews
        </div>
    </div>
</div>

<!-- Floating Cart Button -->
<button onclick="toggleCart()" class="floating-cart btn btn-primary btn-lg rounded-circle shadow-lg" style="width: 60px; height: 60px; position: relative;">
    <i class="bi bi-cart3"></i>
    <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
        0
    </span>
</button>
<div class="container py-5">
    {{-- Using the grouped variable from the controller --}}
    @forelse($menuByCategory as $categoryName => $items)
        <div class="mb-5">
            <h3 class="mb-3 border-bottom pb-2">{{ $categoryName }}</h3>

            <div class="row g-4">
                @foreach($items as $item)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="{{ $item->image_url ? asset('storage/'.$item->image_url) : asset('images/default-food.jpg') }}"
                                         class="img-fluid rounded-start"
                                         style="width:100%; height:100%; min-height:120px; object-fit:cover;"
                                         alt="{{ $item->name }}">
                                </div>

                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="mb-1">{{ $item->name }}</h5>
                                        <p class="small text-muted mb-2">
                                            {{ Str::limit($item->description, 80) }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-primary">
                                                {{ number_format($item->discounted_price ?? $item->price, 2) }} Tsh
                                            </span>

                                            <button 
                                                class="btn btn-sm btn-primary add-to-cart-btn"
                                                onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->discounted_price ?? $item->price }}, '{{ $item->image_url ? asset('storage/'.$item->image_url) : asset('images/default-food.jpg') }}', {{ $item->supplier_id }})">
                                                <i class="bi bi-cart-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <p class="text-muted">No menu items available for this restaurant yet.</p>
        </div>
    @endforelse
</div>


<!-- Cart Sidebar -->
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
                <span id="cartTotal" class="fw-bold fs-5 text-primary">0.00 Tsh</span>
            </div>
            
            <button onclick="proceedToCheckout()" id="checkoutBtn" class="btn checkout-btn w-100 py-3" disabled>
                <i class="bi bi-check-circle me-2"></i>
                Proceed to Checkout
            </button>

            <div class="text-center mt-2">
                <small class="text-muted">Minimum order: 10000.00 Tsh</small>
            </div>
        </div>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>

<script>
    // Store supplier ID for checkout
    const SUPPLIER_ID = {{ $supplier->id }};
    const MIN_ORDER_AMOUNT = 10.00;

    // Initialize cart from localStorage
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
        
        // Store supplier ID in session for checkout
        sessionStorage.setItem('cart_supplier_id', SUPPLIER_ID);
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
            cartBadge.classList.add('cart-badge-pulse');
            setTimeout(() => cartBadge.classList.remove('cart-badge-pulse'), 300);
        } else {
            cartBadge.style.display = 'none';
        }
        
        // Update total
        cartTotal.textContent = `${total.toFixed(2)} Tsh`;
        
        // Enable/disable checkout button
        if (total >= MIN_ORDER_AMOUNT) {
            checkoutBtn.disabled = false;
        } else {
            checkoutBtn.disabled = true;
        }
        
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
                                <h6 class="mb-1">${item.supplier_id}</h6>
                                <p class="mb-0 text-primary fw-bold">${item.price.toFixed(2)} Tsh</p>
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
                            <span class="fw-bold">${(item.price * item.quantity).toFixed(2)} Tsh</span>
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

    async function proceedToCheckout() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    if (total < MIN_ORDER_AMOUNT) {
        alert(`Minimum order amount is $${MIN_ORDER_AMOUNT.toFixed(2)}`);
        return;
    }

    try {
        // Sync cart to server session
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
            // Redirect to checkout page
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

@endsection
