@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-cart-plus me-2 text-accent"></i> Create New Order
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul class="mt-2 mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-warning border-start border-4 border-warning shadow-sm">
        <i class="bi bi-shield-exclamation me-2"></i>
        {{ session('error') }}
    </div>
@endif

                        <!-- Supplier & Service Type -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Supplier <span class="text-danger">*</span>
                                </label>
                                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->business_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Service Type <span class="text-danger">*</span>
                                </label>
                                <select name="service_type_id" class="form-select @error('service_type_id') is-invalid @enderror" required>
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('service_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Order Type & Payment Method -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Order Type <span class="text-danger">*</span>
                                </label>
                                <select name="order_type" id="order_type" class="form-select @error('order_type') is-invalid @enderror" required>
                                    <option value="instant" {{ old('order_type') == 'instant' ? 'selected' : '' }}>Instant</option>
                                    <option value="scheduled" {{ old('order_type') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="catering" {{ old('order_type') == 'catering' ? 'selected' : '' }}>Catering</option>
                                    <option value="subscription" {{ old('order_type') == 'subscription' ? 'selected' : '' }}>Subscription</option>
                                </select>
                                @error('order_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="wallet" {{ old('payment_method') == 'wallet' ? 'selected' : '' }}>Wallet</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Scheduled Date (shown when order type is scheduled) -->
                        <div class="mb-4" id="scheduled_at_field" style="display: none;">
                            <label class="form-label fw-semibold">Scheduled Date & Time</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
                            @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Delivery Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-geo-alt me-1"></i> Delivery Information
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        Delivery Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="delivery_address_text" rows="2" class="form-control @error('delivery_address_text') is-invalid @enderror" placeholder="Enter full delivery address" required>{{ old('delivery_address_text') }}</textarea>
                                    @error('delivery_address_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Latitude</label>
                                    <input type="text" name="delivery_latitude" class="form-control @error('delivery_latitude') is-invalid @enderror" placeholder="-6.7924" value="{{ old('delivery_latitude') }}">
                                    @error('delivery_latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Longitude</label>
                                    <input type="text" name="delivery_longitude" class="form-control @error('delivery_longitude') is-invalid @enderror" placeholder="39.2083" value="{{ old('delivery_longitude') }}">
                                    @error('delivery_longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Delivery Phone <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="delivery_phone" class="form-control @error('delivery_phone') is-invalid @enderror" placeholder="+255 XXX XXX XXX" value="{{ old('delivery_phone') }}" required>
                                    @error('delivery_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Name</label>
                                    <input type="text" name="delivery_contact_name" class="form-control @error('delivery_contact_name') is-invalid @enderror" placeholder="John Doe" value="{{ old('delivery_contact_name') }}">
                                    @error('delivery_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold"><i class="bi bi-basket me-1"></i> Order Items</h6>
        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
            <i class="bi bi-plus-lg"></i> Add Item
        </button>
    </div>

    <div id="orderItems"></div> 
    
    @error('items')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
</div>
                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Special Instructions</label>
                            <textarea name="special_instructions" rows="3" class="form-control @error('special_instructions') is-invalid @enderror" placeholder="Any special instructions for this order">{{ old('special_instructions') }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-accent">
                                <i class="bi bi-check-lg me-1"></i> Create Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-accent text-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-receipt me-1"></i> Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div id="orderSummary">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                            <small>No items added yet</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Item Template -->
<template id="itemTemplate">
    <div class="card mb-3 order-item">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="mb-0 fw-bold">Item <span class="item-number"></span></h6>
                <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Menu Item <span class="text-danger">*</span></label>
                    <select name="items[INDEX][menu_item_id]" class="form-select menu-item-select" required>
                        <option value="">Select Item</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="items[INDEX][quantity]" class="form-control item-quantity" min="1" value="1" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-semibold">Special Instructions</label>
                    <input type="text" name="items[INDEX][special_instructions]" class="form-control" placeholder="e.g., Extra spicy">
                </div>
            </div>
        </div>
    </div>
</template>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCounter = 0;
    const orderItemsContainer = document.getElementById('orderItems');
    const addItemBtn = document.getElementById('addItemBtn');
    const template = document.getElementById('itemTemplate');

    function addRow() {
        // 1. Get template content
        const clone = template.content.cloneNode(true);
        
        // 2. Select the row div
        const row = clone.querySelector('.order-item');
        
        // 3. Forcefully update the names of all inputs in this row
        const inputs = row.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => {
            // Replaces items[INDEX][menu_item_id] with items[0][menu_item_id]
            input.name = input.name.replace('INDEX', itemCounter);
        });

        // 4. Set the visual number
        row.querySelector('.item-number').textContent = itemCounter + 1;

        // 5. Setup removal logic
        row.querySelector('.remove-item').onclick = function() {
            row.remove();
            calculateTotals();
        };

        // 6. Setup calculation triggers
        row.querySelector('.menu-item-select').onchange = calculateTotals;
        row.querySelector('.item-quantity').oninput = calculateTotals;

        // 7. Append to the form
        orderItemsContainer.appendChild(row);
        
        itemCounter++;
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('.order-item');
        
        rows.forEach(row => {
            const select = row.querySelector('.menu-item-select');
            const qtyInput = row.querySelector('.item-quantity');
            
            if (select.value) {
                const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                subtotal += (price * qty);
            }
        });

        const tax = subtotal * 0.18;
        const total = subtotal + tax;

        document.getElementById('orderSummary').innerHTML = `
            <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong>${subtotal.toLocaleString()} TZS</strong></div>
            <div class="d-flex justify-content-between mb-2"><span>VAT (18%)</span><span>${tax.toLocaleString()} TZS</span></div>
            <hr>
            <div class="d-flex justify-content-between"><h5 class="fw-bold">Total</h5><h5 class="text-primary fw-bold">${total.toLocaleString()} TZS</h5></div>
        `;
    }

    // Button Listener
    addItemBtn.addEventListener('click', (e) => {
        e.preventDefault();
        addRow();
    });

    // Add first row by default
    addRow();
});
</script>
@endpush