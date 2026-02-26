@extends('layouts.guest-layout')

@section('title', 'Order Confirmation - TapEats')

@section('content')
<style>
    .confirmation-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .order-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #f97316;
        font-family: monospace;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .order-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e5e7eb;
    }

    .timeline-item.active::before {
        background: #f97316;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        left: -1.44rem;
        top: 1.2rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #e5e7eb;
    }

    .timeline-item:last-child::after {
        display: none;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        flex: 1;
        min-width: 200px;
    }

    @media (max-width: 768px) {
        .action-buttons .btn {
            min-width: 100%;
        }
    }
</style>

<div class="confirmation-container">
    <!-- Success Icon -->
    <div class="text-center">
        <div class="success-icon">
            <i class="bi bi-check-lg text-white" style="font-size: 3rem;"></i>
        </div>
        <h1 class="mb-2">Order Placed Successfully!</h1>
        <p class="text-muted mb-4">Thank you for your order. We'll notify you once the restaurant accepts it.</p>
    </div>

    <!-- Order Details Card -->
    <div class="order-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Order Number</h4>
                <div class="order-number">{{ $order->order_number }}</div>
            </div>
            <span class="status-badge status-{{ $order->order_status }}">
                {{ ucfirst($order->order_status) }}
            </span>
        </div>

        <!-- Order Info -->
        <div class="mb-4">
            <h5 class="mb-3">Order Information</h5>
            <div class="info-row">
                <span class="text-muted">Restaurant:</span>
                <span class="fw-semibold">{{ $order->supplier->business_name }}</span>
            </div>
            <div class="info-row">
                <span class="text-muted">Service Type:</span>
                <span class="fw-semibold">{{ $order->serviceType->name }}</span>
            </div>
            <div class="info-row">
                <span class="text-muted">Order Type:</span>
                <span class="fw-semibold">{{ ucfirst($order->order_type) }}</span>
            </div>
            @if($order->scheduled_at)
            <div class="info-row">
                <span class="text-muted">Scheduled For:</span>
                <span class="fw-semibold">{{ $order->scheduled_at->format('M d, Y - h:i A') }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="text-muted">Payment Method:</span>
                <span class="fw-semibold">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-row">
                <span class="text-muted">Order Date:</span>
                <span class="fw-semibold">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
            </div>
        </div>

        <!-- Delivery Address -->
        @if($order->delivery_address_text)
        <div class="mb-4">
            <h5 class="mb-3">Delivery Address</h5>
            <div class="d-flex align-items-start">
                <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                <div>
                    <p class="mb-1">{{ $order->delivery_contact_name }}</p>
                    <p class="text-muted mb-1">{{ $order->delivery_address_text }}</p>
                    <p class="text-muted mb-0">{{ $order->delivery_phone }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Order Items -->
        <div class="mb-4">
            <h5 class="mb-3">Order Items ({{ $order->orderItems->count() }})</h5>
            @foreach($order->orderItems as $item)
            <div class="order-item">
                <img src="{{ $item->menuItem->main_image_url ?? asset('images/placeholder.jpg') }}" 
                     class="item-image" alt="{{ $item->item_name }}">
                <div class="flex-fill">
                    <h6 class="mb-1">{{ $item->item_name }}</h6>
                    <p class="text-muted small mb-1">{{ Str::limit($item->item_description, 60) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Qty: {{ $item->quantity }}</span>
                        <span class="fw-bold text-primary">{{ number_format($item->subtotal, 2) }} Tsh</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Price Breakdown -->
        <div class="border-top pt-3">
            <div class="info-row">
                <span>Subtotal:</span>
                <span>{{ number_format($order->subtotal, 2) }} Tsh</span>
            </div>
            <div class="info-row">
                <span>Delivery Fee:</span>
                <span>{{ number_format($order->delivery_fee, 2) }} Tsh</span>
            </div>
            <div class="info-row">
                <span>Service Fee:</span>
                <span>{{ number_format($order->service_fee, 2) }} Tsh</span>
            </div>
            <div class="info-row">
                <span>Tax:</span>
                <span>{{ number_format($order->tax_amount, 2) }} Tsh</span>
            </div>
            <div class="info-row fw-bold fs-5 text-primary">
                <span>Total:</span>
                <span>{{ number_format($order->total_amount, 2) }} Tsh</span>
            </div>
        </div>

        @if($order->special_instructions)
        <div class="mt-3 p-3 bg-light rounded">
            <strong>Special Instructions:</strong>
            <p class="mb-0 mt-1">{{ $order->special_instructions }}</p>
        </div>
        @endif
    </div>

    <!-- Order Timeline -->
    <div class="order-card">
        <h5 class="mb-4">Order Status</h5>
        <div class="timeline">
            <div class="timeline-item active">
                <strong>Order Placed</strong>
                <p class="text-muted small mb-0">{{ $order->created_at->format('M d, h:i A') }}</p>
            </div>
            <div class="timeline-item {{ $order->accepted_at ? 'active' : '' }}">
                <strong>Order Accepted</strong>
                @if($order->accepted_at)
                <p class="text-muted small mb-0">{{ $order->accepted_at->format('M d, h:i A') }}</p>
                @else
                <p class="text-muted small mb-0">Waiting for restaurant confirmation</p>
                @endif
            </div>
            <div class="timeline-item {{ $order->prepared_at ? 'active' : '' }}">
                <strong>Order Preparing</strong>
                @if($order->prepared_at)
                <p class="text-muted small mb-0">{{ $order->prepared_at->format('M d, h:i A') }}</p>
                @else
                <p class="text-muted small mb-0">Pending</p>
                @endif
            </div>
            <div class="timeline-item {{ $order->dispatched_at ? 'active' : '' }}">
                <strong>Out for Delivery</strong>
                @if($order->dispatched_at)
                <p class="text-muted small mb-0">{{ $order->dispatched_at->format('M d, h:i A') }}</p>
                @else
                <p class="text-muted small mb-0">Pending</p>
                @endif
            </div>
            <div class="timeline-item {{ $order->delivered_at ? 'active' : '' }}">
                <strong>Delivered</strong>
                @if($order->delivered_at)
                <p class="text-muted small mb-0">{{ $order->delivered_at->format('M d, h:i A') }}</p>
                @else
                <p class="text-muted small mb-0">Pending</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        @auth
        <a href="{{ route('orderstrack', $order->order_number) }}" class="btn btn-primary btn-lg">
            <i class="bi bi-geo-alt"></i> Track Order
        </a>
        <a href="{{ route('ordersindex') }}" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-list-ul"></i> View All Orders
        </a>
        @else
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-house"></i> Back to Home
        </a>
        <a href="{{ route('showRegisterForm') }}" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-person-plus"></i> Create Account
        </a>
        @endauth
    </div>

    <!-- Help Section -->
    <div class="text-center mt-4">
        <p class="text-muted">Need help with your order?</p>
        <a href="{{ route('customersupport') }}" class="btn btn-link">Contact Support</a>
    </div>
</div>

<script>
    // Clear cart from localStorage after successful order
    localStorage.removeItem('cart');
    
    // Show success message
    @if(session('success'))
    // You can use a toast notification library here
    console.log('{{ session("success") }}');
    @endif
</script>

@endsection