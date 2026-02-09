@extends('layouts.guest-layout')

@section('title', 'My Orders - TapEats')

@section('content')
<style>
    .order-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s;
        margin-bottom: 1rem;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #dbeafe; color: #1e40af; }
    .status-preparing { background: #fed7aa; color: #9a3412; }
    .status-ready { background: #d1fae5; color: #065f46; }
    .status-dispatched { background: #e0e7ff; color: #3730a3; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-receipt"></i> My Orders
                </h2>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="bi bi-house"></i> Back to Home
                </a>
            </div>

            @if($orders->isEmpty())
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Orders Yet</h4>
                        <p class="text-muted">Start ordering from your favorite restaurants!</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-shop"></i> Browse Restaurants
                        </a>
                    </div>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="order-card card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Order Info -->
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="bi bi-shop text-primary"></i>
                                                {{ $order->supplier->business_name ?? 'Restaurant' }}
                                            </h5>
                                            <p class="text-muted small mb-1">
                                                Order #{{ $order->order_number }}
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar"></i>
                                                {{ $order->created_at->format('M d, Y h:i A') }}
                                            </p>
                                        </div>
                                        <span class="status-badge status-{{ $order->order_status }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </div>

                                    <!-- Order Items Preview -->
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            {{ $order->orderItems->count() }} item(s) • 
                                            {{ $order->serviceType->name ?? 'N/A' }}
                                            @if($order->order_type === 'scheduled')
                                                • Scheduled for {{ \Carbon\Carbon::parse($order->scheduled_at)->format('M d, h:i A') }}
                                            @endif
                                        </small>
                                    </div>

                                    <!-- Total Amount -->
                                    <div class="mt-2">
                                        <h5 class="text-primary mb-0">
                                            {{ number_format($order->total_amount, 2) }} Tsh
                                        </h5>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <a href="{{ route('orderstrack', $order->order_number) }}" 
                                       class="btn btn-primary btn-sm mb-2 w-100">
                                        <i class="bi bi-geo-alt"></i> Track Order
                                    </a>
                                    
                                    @if(in_array($order->order_status, ['delivered', 'completed']))
                                        <a href="{{ route('orderstrack', $order->order_number) }}" 
                                           class="btn btn-outline-secondary btn-sm w-100">
                                            <i class="bi bi-arrow-clockwise"></i> Order Again
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

<div class="d-flex justify-content-center mt-5 mb-4 pagination-sm">
    {{ $orders->links() }}
</div>
            @endif
        </div>
    </div>
</div>
@endsection