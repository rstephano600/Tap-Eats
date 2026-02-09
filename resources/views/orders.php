<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TapEats - Order Your Favorite Food</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.5s ease-out; }
        .cart-badge { animation: pulse 0.3s ease-out; }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold">
                        TE
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">TapEats</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search food..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 w-64"
                               id="searchInput">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <button class="relative bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 transition" onclick="toggleCart()">
                        <span class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Cart</span>
                            <span class="cart-badge bg-white text-orange-500 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold" id="cartCount">0</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Categories -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex space-x-4 overflow-x-auto">
                <button class="category-btn px-6 py-2 bg-orange-500 text-white rounded-full whitespace-nowrap hover:bg-orange-600 transition" data-category="all">All</button>
                @foreach($categories as $category)
                <button class="category-btn px-6 py-2 bg-gray-100 text-gray-700 rounded-full whitespace-nowrap hover:bg-gray-200 transition" data-category="{{ $category }}">
                    {{ ucfirst($category) }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Suppliers Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Popular Suppliers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="suppliersGrid">
                @foreach($suppliers as $supplier)
                <div class="fade-in bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition cursor-pointer" onclick="filterBySupplier({{ $supplier->id }})">
                    @if($supplier->logo)
                    <img src="{{ asset('storage/' . $supplier->logo) }}" alt="{{ $supplier->business_name }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold">{{ substr($supplier->business_name, 0, 2) }}</span>
                    </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $supplier->business_name }}</h3>
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>📍 {{ $supplier->region ?? 'Location' }}</span>
                            <span class="px-2 py-1 rounded-full text-xs {{ $supplier->is_verified ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $supplier->is_verified ? '✓ Verified' : 'Pending' }}
                            </span>
                        </div>
                        @if($supplier->description)
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $supplier->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Menu Items Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Menu</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="menuGrid">
                @forelse($foods as $food)
                <div class="fade-in bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition food-item" 
                     data-category="{{ $food->category }}" 
                     data-supplier="{{ $food->supplier_id }}"
                     data-available="{{ $food->available ? 'true' : 'false' }}">
                    @if($food->image)
                    <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->food_name }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <span class="text-gray-400 text-5xl">🍽️</span>
                    </div>
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-lg">{{ $food->food_name }}</h3>
                            @if(!$food->available)
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Unavailable</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mb-1">{{ $food->supplier->business_name }}</p>
                        @if($food->description)
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $food->description }}</p>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-orange-500">TSh {{ number_format($food->price, 0) }}</span>
                            @if($food->available)
                            <button onclick="addToCart({{ $food->id }}, '{{ $food->food_name }}', {{ $food->price }}, '{{ $food->image ? asset('storage/' . $food->image) : '' }}', {{ $food->supplier_id }}, {{ $food->business_id }})" 
                                    class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                                Add +
                            </button>
                            @else
                            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">
                                Unavailable
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No menu items available yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Cart Sidebar -->
    <div id="cartSidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 overflow-hidden">
        <div class="flex flex-col h-full">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-xl font-bold" id="cartTitle">Your Cart</h3>
                <button onclick="closeCheckout()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Cart View -->
            <div id="cartView" class="flex-1 flex flex-col">
                <div id="cartItems" class="flex-1 overflow-y-auto p-6">
                    <p class="text-gray-500 text-center py-8">Your cart is empty</p>
                </div>
                
                <div class="border-t p-6">
                    <div class="flex justify-between mb-4">
                        <span class="font-semibold">Total:</span>
                        <span class="font-bold text-xl text-orange-500" id="cartTotal">TSh 0</span>
                    </div>
                    <button class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold" onclick="proceedToCheckout()">
                        Proceed to Checkout
                    </button>
                </div>
            </div>

            <!-- Checkout Form View -->
            <div id="checkoutView" class="flex-1 flex-col hidden">
                <div class="flex-1 overflow-y-auto p-6">
                    <form id="checkoutForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="full_name" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Enter your full name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="+255 XXX XXX XXX">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Address *</label>
                            <textarea name="address" required rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                      placeholder="Enter your delivery address"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Region/City *</label>
                            <select name="region" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="">Select region</option>
                                <option value="Dodoma">Dodoma</option>
                                <option value="Dar es Salaam">Dar es Salaam</option>
                                <option value="Arusha">Arusha</option>
                                <option value="Mwanza">Mwanza</option>
                                <option value="Mbeya">Mbeya</option>
                                <option value="Morogoro">Morogoro</option>
                                <option value="Tanga">Tanga</option>
                                <option value="Zanzibar">Zanzibar</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method *</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" checked class="mr-3">
                                    <span>💵 Cash on Delivery</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="mobile_money" class="mr-3">
                                    <span>📱 Mobile Money (M-Pesa/Tigo Pesa)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Special Instructions (Optional)</label>
                            <textarea name="notes" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                      placeholder="Any special requests or delivery instructions?"></textarea>
                        </div>

                        <!-- Order Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-3">Order Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span id="checkoutSubtotal">TSh 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Delivery Fee:</span>
                                    <span id="deliveryFee">TSh 0</span>
                                </div>
                                <div class="border-t pt-2 mt-2"></div>
                                <div class="flex justify-between font-bold text-base">
                                    <span>Total:</span>
                                    <span class="text-orange-500" id="checkoutTotal">TSh 0</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-t p-6 space-y-3">
                    <button onclick="confirmOrder()" 
                            class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                        Place Order
                    </button>
                    <button onclick="backToCart()" 
                            class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition font-semibold">
                        Back to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCart()"></div>

    <script>
        let cart = [];
        let selectedSupplier = null;

        // Add to cart
        function addToCart(foodId, foodName, price, image, supplierId, businessId) {
            const existingItem = cart.find(i => i.id === foodId);
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: foodId,
                    name: foodName,
                    price: parseFloat(price),
                    image: image,
                    quantity: 1,
                    supplier_id: supplierId,
                    business_id: businessId
                });
            }
            
            updateCart();
        }

        // Update cart display
        function updateCart() {
            const cartCount = document.getElementById('cartCount');
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            cartCount.textContent = totalItems;
            cartTotal.textContent = `TSh ${Math.round(total).toLocaleString()}`;
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-center py-8">Your cart is empty</p>';
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="flex items-center space-x-4 mb-4 pb-4 border-b">
                        ${item.image ? `<img src="${item.image}" class="w-16 h-16 object-cover rounded-lg">` : '<div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">🍽️</div>'}
                        <div class="flex-1">
                            <h4 class="font-semibold">${item.name}</h4>
                            <p class="text-orange-500 font-bold">TSh ${Math.round(item.price).toLocaleString()}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 bg-gray-200 rounded-full hover:bg-gray-300">-</button>
                            <span class="font-semibold">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, 1)" class="w-8 h-8 bg-orange-500 text-white rounded-full hover:bg-orange-600">+</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Update quantity
        function updateQuantity(foodId, change) {
            const item = cart.find(i => i.id === foodId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    cart = cart.filter(i => i.id !== foodId);
                }
                updateCart();
            }
        }

        // Toggle cart
        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');
            
            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Proceed to checkout form
        function proceedToCheckout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            // Check if all items are from the same supplier
            const supplierIds = [...new Set(cart.map(item => item.supplier_id))];
            if (supplierIds.length > 1) {
                alert('Please order from one supplier at a time!');
                return;
            }

            // Switch to checkout view
            document.getElementById('cartView').classList.add('hidden');
            document.getElementById('checkoutView').classList.remove('hidden');
            document.getElementById('checkoutView').classList.add('flex');
            document.getElementById('cartTitle').textContent = 'Checkout';

            // Update checkout summary
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('checkoutSubtotal').textContent = `TSh ${Math.round(total).toLocaleString()}`;
            document.getElementById('deliveryFee').textContent = 'TSh 0';
            document.getElementById('checkoutTotal').textContent = `TSh ${Math.round(total).toLocaleString()}`;
        }

        // Back to cart
        function backToCart() {
            document.getElementById('checkoutView').classList.add('hidden');
            document.getElementById('checkoutView').classList.remove('flex');
            document.getElementById('cartView').classList.remove('hidden');
            document.getElementById('cartTitle').textContent = 'Your Cart';
        }

        // Close checkout/cart
        function closeCheckout() {
            backToCart();
            toggleCart();
        }

        // Confirm and place order
        function confirmOrder() {
            const form = document.getElementById('checkoutForm');
            
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get form data
            const formData = new FormData(form);
            const customerData = {
                full_name: formData.get('full_name'),
                phone: formData.get('phone'),
                address: formData.get('address'),
                region: formData.get('region'),
                payment_method: formData.get('payment_method'),
                notes: formData.get('notes')
            };

            // Show loading state
            const submitBtn = event.target;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Placing Order...';
            submitBtn.disabled = true;

            // Send order to backend
            fetch('{{ route("orders.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    cart: cart,
                    supplier_id: cart[0].supplier_id,
                    business_id: cart[0].business_id,
                    customer_details: customerData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear cart
                    cart = [];
                    updateCart();
                    
                    // Show success message
                    alert('Order placed successfully! Order #' + data.order_id);
                    
                    // Reset form
                    form.reset();
                    backToCart();
                    toggleCart();
                    
                    // Redirect to order details
                    window.location.href = '{{ route("orders.show", ":id") }}'.replace(':id', data.order_id);
                } else {
                    alert('Error placing order: ' + data.message);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while placing your order. Please try again.');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        // Filter by supplier
        function filterBySupplier(supplierId) {
            selectedSupplier = supplierId;
            filterMenu();
        }

        // Filter menu
        function filterMenu() {
            const items = document.querySelectorAll('.food-item');
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const activeCategory = document.querySelector('.category-btn.bg-orange-500').getAttribute('data-category');
            
            items.forEach(item => {
                const category = item.getAttribute('data-category');
                const supplier = parseInt(item.getAttribute('data-supplier'));
                const foodName = item.querySelector('h3').textContent.toLowerCase();
                
                let showItem = true;
                
                // Category filter
                if (activeCategory !== 'all' && category !== activeCategory) {
                    showItem = false;
                }
                
                // Supplier filter
                if (selectedSupplier && supplier !== selectedSupplier) {
                    showItem = false;
                }
                
                // Search filter
                if (searchTerm && !foodName.includes(searchTerm)) {
                    showItem = false;
                }
                
                item.style.display = showItem ? 'block' : 'none';
            });
        }

        // Category filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('bg-orange-500', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-700');
                });
                this.classList.add('bg-orange-500', 'text-white');
                this.classList.remove('bg-gray-100', 'text-gray-700');
                
                selectedSupplier = null; // Reset supplier filter when changing category
                filterMenu();
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', filterMenu);
    </script>
</body>
</html>