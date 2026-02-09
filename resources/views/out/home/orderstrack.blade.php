@extends('layouts.guest-layout')

@section('title', 'Track Order - TapEats')

@section('content')
<style>
    .timeline {
        position: relative;
        padding-left: 50px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -50px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        z-index: 2;
    }

    .timeline-marker.completed {
        background: #10b981;
        color: white;
    }

    .timeline-marker.pending {
        background: #e5e7eb;
        color: #9ca3af;
    }

    .timeline-line {
        position: absolute;
        left: -30px;
        top: 40px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item:last-child .timeline-line {
        display: none;
    }

    .timeline-line.completed {
        background: #10b981;
    }

    .order-details-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Track Order</h2>
                    <p class="text-muted mb-0">Order #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('ordersindex') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> All Orders
                </a>
            </div>

            <div class="row">
                <!-- Left: Order Timeline -->
                <div class="col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="mb-4">
                                <i class="bi bi-clock-history"></i> Order Status
                            </h5>

                            <div class="timeline">
                                @foreach($statusTimeline as $index => $step)
                                    <div class="timeline-item">
                                        <div class="timeline-marker {{ $step['completed'] ? 'completed' : 'pending' }}">
                                            <i class="bi {{ $step['icon'] }}"></i>
                                        </div>
                                        
                                        @if($index < count($statusTimeline) - 1)
                                            <div class="timeline-line {{ $step['completed'] ? 'completed' : '' }}"></div>
                                        @endif

                                        <div>
                                            <h6 class="mb-1 {{ $step['completed'] ? 'text-success' : 'text-muted' }}">
                                                {{ $step['label'] }}
                                            </h6>
                                            @if($step['time'])
                                                <p class="text-muted small mb-0">
                                                    {{ $step['time']->format('M d, Y h:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($order->order_status === 'cancelled')
                                <div class="alert alert-danger mt-4">
                                    <i class="bi bi-x-circle"></i> This order has been cancelled
                                    @if($order->cancellation_reason)
                                        <br><small>Reason: {{ $order->cancellation_reason }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Delivery Info (if delivery) -->
                    @if($order->serviceType && stripos($order->serviceType->name, 'delivery') !== false)
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="bi bi-geo-alt"></i> Delivery Information
                                </h5>

                                <p class="mb-2">
                                    <strong>Address:</strong><br>
                                    {{ $order->delivery_address_text }}<br>
                                    {{ $order->delivery_city }}, {{ $order->delivery_postal_code }}
                                </p>

                                <p class="mb-2">
                                    <strong>Contact:</strong> {{ $order->delivery_phone }}
                                </p>

                                @if($order->special_instructions)
                                    <p class="mb-0">
                                        <strong>Instructions:</strong> {{ $order->special_instructions }}
                                    </p>
                                @endif

                                @if($order->driver)
                                    <div class="alert alert-info mt-3 mb-0">
                                        <strong>Driver:</strong> {{ $order->driver->name }}<br>
                                        <strong>Phone:</strong> {{ $order->driver->phone }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right: Order Details -->
                <div class="col-lg-5">
                    <!-- Restaurant Info -->
                    <div class="order-details-card">
                        <h5 class="mb-3">
                            <i class="bi bi-shop"></i> Restaurant
                        </h5>
                        <h6>{{ $order->supplier->business_name ?? 'N/A' }}</h6>
                        <p class="text-muted small mb-0">
                            {{ $order->supplier->address ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Order Items -->
                    <div class="order-details-card">
                        <h5 class="mb-3">
                            <i class="bi bi-cart"></i> Order Items
                        </h5>

                        @foreach($order->orderItems as $item)
                            <div class="d-flex align-items-center mb-3">
                                @if($item->menuItem && $item->menuItem->image_url)
                                    <img src="{{ asset('storage/' . $item->menuItem->image_url) }}" 
                                         class="item-image me-3" alt="{{ $item->item_name }}">
                                @else
                                    <div class="item-image bg-light me-3 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif

                                <div class="flex-fill">
                                    <h6 class="mb-0">{{ $item->item_name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} Tsh</small>
                                </div>

                                <div class="text-end">
                                    <strong>{{ number_format($item->subtotal, 2) }} Tsh</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="order-details-card">
                        <h5 class="mb-3">
                            <i class="bi bi-receipt"></i> Order Summary
                        </h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>{{ number_format($order->subtotal, 2) }} Tsh</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>{{ number_format($order->delivery_fee, 2) }} Tsh</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Fee:</span>
                            <span>{{ number_format($order->service_fee, 2) }} Tsh</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax:</span>
                            <span>{{ number_format($order->tax_amount, 2) }} Tsh</span>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <strong>Total:</strong>
                            <strong class="text-primary fs-5">{{ number_format($order->total_amount, 2) }} Tsh</strong>
                        </div>

                        <div class="mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="bi bi-credit-card"></i> 
                                Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                ({{ ucfirst($order->payment_status) }})
                            </small>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(in_array($order->order_status, ['pending', 'confirmed']))
                        <button class="btn btn-outline-danger w-100 mb-2" onclick="confirmCancelOrder()">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    @endif

                    @if($order->order_status === 'delivered' || $order->order_status === 'completed')
                        <a href="{{ route('dailymenuitems', $order->supplier_id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Order Again
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCancelOrder() {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Implement cancel order functionality
        alert('Cancel order feature coming soon!');
    }
}
</script>
@endsection