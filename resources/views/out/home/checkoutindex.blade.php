@extends('layouts.guest-layout')

@section('title', 'Checkout - TapEats')

@section('content')
<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .step-indicator {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .step.active .step-circle {
        background: #f97316;
        color: white;
    }

    .step.completed .step-circle {
        background: #10b981;
        color: white;
    }

    .step-line {
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #e5e7eb;
        z-index: -1;
    }

    .step:last-child .step-line {
        display: none;
    }

    .order-summary-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-summary-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .delivery-option {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }

    .delivery-option:hover {
        border-color: #f97316;
    }

    .delivery-option.active {
        border-color: #f97316;
        background: #fff7ed;
    }

    .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-method:hover {
        border-color: #f97316;
    }

    .payment-method.active {
        border-color: #f97316;
        background: #fff7ed;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .total-row {
        font-size: 1.25rem;
        font-weight: 700;
        padding-top: 1rem;
        border-top: 2px solid #e5e7eb;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 1rem;
        }
    }
</style>

<div class="checkout-container">
    <!-- Progress Steps -->
    <div class="step-indicator">
        <div class="step active">
            <div class="step-circle">1</div>
            <div class="step-label">Cart</div>
            <div class="step-line"></div>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Details</div>
            <div class="step-line"></div>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Payment</div>
        </div>
    </div>

    <form id="checkoutForm" action="{{ route('checkoutprocess') }}" method="POST">
        @csrf

        <div class="container mt-3">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Order Failed!</strong><br>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex">
                <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
        <div class="row">
            <!-- Left Column - Customer Info & Delivery -->
            <div class="col-lg-7">
                
                <!-- Customer Information -->
                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-person-circle"></i>
                        Customer Information
                    </h3>

                    @guest
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i>
                        Already have an account? <a href="{{ route('showLoginForm') }}" class="alert-link">Login here</a>
                    </div>
                    @endguest

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" 
                                   value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}" 
                                   {{ auth()->check() ? 'readonly' : '' }} required>
                            @error('customer_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" 
                                   value="{{ auth()->check() ? auth()->user()->phone : old('customer_phone') }}" 
                                   {{ auth()->check() ? 'readonly' : '' }} required>
                            @error('customer_phone')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control" 
                                   value="{{ auth()->check() ? auth()->user()->email : old('customer_email') }}" 
                                   {{ auth()->check() ? 'readonly' : '' }} required>
                            @error('customer_email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Delivery Type -->
                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-truck"></i>
                        Service Type
                    </h3>

                    <div class="row g-3">
                        @foreach($serviceTypes as $serviceType)
                        <div class="col-md-6">
                            <div class="delivery-option" onclick="selectServiceType({{ $serviceType->id }}, '{{ $serviceType->name }}')">
                                <input type="radio" name="service_type_id" value="{{ $serviceType->id }}" 
                                       id="service_{{ $serviceType->id }}" class="d-none" required>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $serviceType->icon ?? 'box' }} fs-3 text-primary me-3"></i>
                                    <div>
                                        <h5 class="mb-1">{{ $serviceType->name }}</h5>
                                        <p class="text-muted small mb-0">{{ $serviceType->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery Address (shown only for delivery) -->
                <div class="section-card" id="deliveryAddressSection" style="display: none;">
                    <h3 class="section-title">
                        <i class="bi bi-geo-alt"></i>
                        Delivery Address
                    </h3>

                    @auth
                    @if($savedAddresses->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Saved Addresses</label>
                        <select class="form-select" id="savedAddressSelect" onchange="fillAddressFromSaved()">
                            <option value="">-- Select a saved address --</option>
                            @foreach($savedAddresses as $address)
                            <option value="{{ $address->id }}" 
                                    data-address="{{ $address->address_line1 }}"
                                    data-address2="{{ $address->address_line2 }}"
                                    data-city="{{ $address->city }}"
                                    data-state="{{ $address->state }}"
                                    data-postal="{{ $address->postal_code }}"
                                    data-lat="{{ $address->latitude }}"
                                    data-lng="{{ $address->longitude }}">
                                {{ $address->label }} - {{ $address->address_line1 }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center mb-3">
                        <span class="text-muted">OR</span>
                    </div>
                    @endif
                    @endauth

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="delivery_address" id="delivery_address" 
                                   class="form-control" placeholder="House/Apt No., Street Name"
                                   value="{{ old('delivery_address') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="delivery_city" id="delivery_city" 
                                   class="form-control" value="{{ old('delivery_city') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="delivery_postal_code" id="delivery_postal_code" 
                                   class="form-control" value="{{ old('delivery_postal_code') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Delivery Instructions (Optional)</label>
                            <textarea name="special_instructions" class="form-control" rows="2" 
                                      placeholder="E.g., Ring the doorbell, Leave at door">{{ old('special_instructions') }}</textarea>
                        </div>

                        <input type="hidden" name="delivery_latitude" id="delivery_latitude">
                        <input type="hidden" name="delivery_longitude" id="delivery_longitude">
                    </div>
                </div>

                <!-- Order Type -->
                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-clock"></i>
                        Order Type
                    </h3>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="delivery-option active" onclick="selectOrderType('instant')">
                                <input type="radio" name="order_type" value="instant" id="order_immediate" 
                                       class="d-none" checked required>
                                <div>
                                    <h5 class="mb-1">🚀 Order Now</h5>
                                    <p class="text-muted small mb-0">Get your order ASAP</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="delivery-option" onclick="selectOrderType('scheduled')">
                                <input type="radio" name="order_type" value="scheduled" id="order_scheduled" 
                                       class="d-none" required>
                                <div>
                                    <h5 class="mb-1">📅 Schedule Order</h5>
                                    <p class="text-muted small mb-0">Choose delivery time</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="scheduledTimeSection" class="mt-3" style="display: none;">
                        <label class="form-label">Scheduled Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" 
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-credit-card"></i>
                        Payment Method
                    </h3>

                    <div class="payment-method active" onclick="selectPayment('cash')">
                        <input type="radio" name="payment_method" value="cash" id="payment_cash" 
                               class="d-none" checked required>
                        <i class="bi bi-cash-coin fs-3 text-success"></i>
                        <div>
                            <h5 class="mb-0">Cash on Delivery</h5>
                            <p class="text-muted small mb-0">Pay when you receive your order</p>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPayment('card')">
                        <input type="radio" name="payment_method" value="card" id="payment_card" 
                               class="d-none" required>
                        <i class="bi bi-credit-card fs-3 text-primary"></i>
                        <div>
                            <h5 class="mb-0">Credit/Debit Card</h5>
                            <p class="text-muted small mb-0">Pay online securely</p>
                        </div>
                    </div>

                    <div class="payment-method" onclick="selectPayment('mobile_money')">
                        <input type="radio" name="payment_method" value="mobile_money" id="payment_mobile" 
                               class="d-none" required>
                        <i class="bi bi-phone fs-3 text-info"></i>
                        <div>
                            <h5 class="mb-0">Mobile Money</h5>
                            <p class="text-muted small mb-0">M-Pesa, Airtel Money, etc.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-5">
                <div class="section-card sticky-top" style="top: 20px;">
                    <h3 class="section-title">
                        <i class="bi bi-receipt"></i>
                        Order Summary
                    </h3>
<!-- Order Summary Section -->
@if($isSingleSupplier)
    <!-- Single Supplier View -->
    <div class="mb-3 pb-3 border-bottom">
        <h5 class="mb-1">{{ $supplier->business_name }}</h5>
        <p class="text-muted small mb-0">
            <i class="bi bi-geo-alt"></i> {{ $supplier->address }}
        </p>
    </div>

    <!-- Cart Items - Loaded from JavaScript -->
    <div id="orderSummaryItems">
        <!-- Items will be loaded from localStorage -->
    </div>
@else
    <!-- Multi-Supplier View - Server-Side Rendered -->
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle"></i> Your order contains items from {{ count($suppliers) }} restaurants
    </div>

    <div id="orderSummaryItems">
        @foreach($orderSummary as $supplierId => $summary)
            <div class="mb-4 pb-3 border-bottom supplier-group" data-supplier-id="{{ $supplierId }}">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-shop"></i> {{ $summary['supplier']->business_name }}
                </h6>
                
                <div class="supplier-items-{{ $supplierId }}">
                    <!-- Items will be populated by JavaScript for this supplier -->
                </div>
                
                <div class="mt-2 pt-2 border-top">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Subtotal:</span>
                        <span class="fw-semibold supplier-subtotal-{{ $supplierId }}">$0.00</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif



                    <!-- Pricing Breakdown -->
                    <div class="mt-3">
                        <div class="price-row">
                            <span>Subtotal:</span>
                            <span id="summarySubtotal">$0.00</span>
                        </div>
                        <div class="price-row">
                            <span>Delivery Fee:</span>
                            <span id="summaryDeliveryFee">$0.00</span>
                        </div>
                        <div class="price-row">
                            <span>Service Fee:</span>
                            <span id="summaryServiceFee">$0.00</span>
                        </div>
                        <div class="price-row">
                            <span>Tax ({{ $taxRate ?? 0 }}%):</span>
                            <span id="summaryTax">$0.00</span>
                        </div>
                        
                        <div class="price-row total-row text-primary">
                            <span>Total:</span>
                            <span id="summaryTotal">$0.00</span>
                        </div>
                    </div>

                    <!-- Hidden inputs for amounts -->
                    <input type="hidden" name="subtotal" id="hiddenSubtotal">
                    <input type="hidden" name="delivery_fee" id="hiddenDeliveryFee">
                    <input type="hidden" name="service_fee" id="hiddenServiceFee">
                    <input type="hidden" name="tax_amount" id="hiddenTaxAmount">
                    <input type="hidden" name="total_amount" id="hiddenTotalAmount">
                    <input type="hidden" name="cart_items" id="hiddenCartItems">


                    <!-- Place Order Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" id="placeOrderBtn">
                        <i class="bi bi-check-circle"></i> Place Order
                    </button>

                    <p class="text-muted small text-center mt-2 mb-0">
                        By placing your order, you agree to our Terms & Conditions
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Configuration
    const DELIVERY_FEE = {{ $deliveryFee ?? 5.00 }};
    const SERVICE_FEE = {{ $serviceFee ?? 2.50 }};
    const TAX_RATE = {{ $taxRate ?? 0.10 }}; // 10%

    // Load cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (cart.length === 0) {
            window.location.href = '{{ route("home") }}';
            return;
        }
        
        renderOrderSummary();
        calculateTotals();
    });

    // Render order summary
// Render order summary
function renderOrderSummary() {
    const isSingleSupplier = {{ $isSingleSupplier ? 'true' : 'false' }};
    
    if (isSingleSupplier) {
        // Single supplier - render all items in one list
        const container = document.getElementById('orderSummaryItems');
        container.innerHTML = cart.map(item => `
            <div class="order-summary-item mb-2">
                <div class="d-flex align-items-start">
                    <img src="${item.image}" class="rounded me-2" 
                         style="width: 50px; height: 50px; object-fit: cover;" alt="${item.name}">
                    <div class="flex-fill">
                        <h6 class="mb-1 small">${item.name}</h6>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Qty: ${item.quantity}</p>
                    </div>
                    <div class="text-primary fw-bold ms-2">$${(item.price * item.quantity).toFixed(2)}</div>
                </div>
            </div>
        `).join('');
    } else {
        // Multi-supplier - populate each supplier's section
        const groupedBySupplier = cart.reduce((acc, item) => {
            if (!acc[item.supplier_id]) {
                acc[item.supplier_id] = [];
            }
            acc[item.supplier_id].push(item);
            return acc;
        }, {});

        // For each supplier group, populate its items
        Object.entries(groupedBySupplier).forEach(([supplierId, items]) => {
            const container = document.querySelector(`.supplier-items-${supplierId}`);
            if (container) {
                container.innerHTML = items.map(item => `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex flex-fill">
                            <img src="${item.image}" class="rounded me-2" 
                                 style="width: 40px; height: 40px; object-fit: cover;" alt="${item.name}">
                            <div class="flex-fill">
                                <div class="small fw-semibold">${item.name}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Qty: ${item.quantity} × $${item.price.toFixed(2)}</div>
                            </div>
                        </div>
                        <div class="text-primary fw-bold ms-2">$${(item.price * item.quantity).toFixed(2)}</div>
                    </div>
                `).join('');

                // Update supplier subtotal
                const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const subtotalElement = document.querySelector(`.supplier-subtotal-${supplierId}`);
                if (subtotalElement) {
                    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
                }
            }
        });
    }

    // Store cart items as JSON for backend
    document.getElementById('hiddenCartItems').value = JSON.stringify(cart);
}

function calculateTotals() {
    const isSingleSupplier = {{ $isSingleSupplier ? 'true' : 'false' }};
    const subtotal = cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
    
    // Check if the selected service type is 'Delivery'
    const selectedService = document.querySelector('input[name="service_type_id"]:checked');
    const serviceTypeElement = selectedService ? document.querySelector(`label[for="${selectedService.id}"]`) : null;
    const isDelivery = serviceTypeElement && serviceTypeElement.textContent.toLowerCase().includes('delivery');

    // For multi-supplier, you might want to charge delivery per supplier
    const uniqueSuppliers = [...new Set(cart.map(item => item.supplier_id))].length;
    const deliveryFee = isDelivery ? (isSingleSupplier ? DELIVERY_FEE : DELIVERY_FEE * uniqueSuppliers) : 0;
    
    const serviceFee = SERVICE_FEE;
    const tax = (subtotal + deliveryFee + serviceFee) * TAX_RATE;
    const total = subtotal + deliveryFee + serviceFee + tax;

    // Update the UI display
    document.getElementById('summarySubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summaryDeliveryFee').textContent = `$${deliveryFee.toFixed(2)}`;
    
    if (!isSingleSupplier && isDelivery) {
        // Show delivery fee breakdown
        document.getElementById('summaryDeliveryFee').innerHTML = `$${deliveryFee.toFixed(2)} <small class="text-muted">(${uniqueSuppliers} restaurants)</small>`;
    }
    
    document.getElementById('summaryServiceFee').textContent = `$${serviceFee.toFixed(2)}`;
    document.getElementById('summaryTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;

    // Update Hidden Inputs
    document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
    document.getElementById('hiddenDeliveryFee').value = deliveryFee.toFixed(2);
    document.getElementById('hiddenServiceFee').value = serviceFee.toFixed(2);
    document.getElementById('hiddenTaxAmount').value = tax.toFixed(2);
    document.getElementById('hiddenTotalAmount').value = total.toFixed(2);
}

    // Select service type
    function selectServiceType(id, name) {
        document.querySelectorAll('.delivery-option').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('service_' + id).checked = true;

        // Show/hide delivery address section
        const deliverySection = document.getElementById('deliveryAddressSection');
        if (name.toLowerCase().includes('delivery')) {
            deliverySection.style.display = 'block';
            makeDeliveryFieldsRequired(true);
        } else {
            deliverySection.style.display = 'none';
            makeDeliveryFieldsRequired(false);
        }

        calculateTotals();
    }

    // Make delivery fields required/optional
    function makeDeliveryFieldsRequired(required) {
        const fields = ['delivery_address', 'delivery_city'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (required) {
                    field.setAttribute('required', 'required');
                } else {
                    field.removeAttribute('required');
                }
            }
        });
    }

    // Select order type
    function selectOrderType(type) {
        document.querySelectorAll('.delivery-option').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('order_' + type).checked = true;

        const scheduledSection = document.getElementById('scheduledTimeSection');
        if (type === 'scheduled') {
            scheduledSection.style.display = 'block';
            document.querySelector('input[name="scheduled_at"]').setAttribute('required', 'required');
        } else {
            scheduledSection.style.display = 'none';
            document.querySelector('input[name="scheduled_at"]').removeAttribute('required');
        }
    }

    // Select payment method
    function selectPayment(method) {
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('payment_' + method).checked = true;
    }

    // Fill address from saved
    function fillAddressFromSaved() {
        const select = document.getElementById('savedAddressSelect');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            document.getElementById('delivery_address').value = option.dataset.address + ' ' + (option.dataset.address2 || '');
            document.getElementById('delivery_city').value = option.dataset.city;
            document.getElementById('delivery_postal_code').value = option.dataset.postal;
            document.getElementById('delivery_latitude').value = option.dataset.lat;
            document.getElementById('delivery_longitude').value = option.dataset.lng;
        }
    }

    // Form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('placeOrderBtn');
    
    // Simple Client-side check before submission
    if (cart.length === 0) {
        e.preventDefault();
        alert("Your cart is empty!");
        return;
    }

    // Visual feedback
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Order...';
    
    // Let the form submit normally (which allows Laravel's back()->withErrors() to work)
    return true; 
});
</script>

@endsection