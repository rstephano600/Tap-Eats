@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Order Details -->
        <div class="col-lg-8">
            <!-- Order Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-darkblue">
                                <i class="bi bi-receipt me-2 text-accent"></i> Order #{{ $order->order_number }}
                            </h5>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i> {{ $order->created_at->format('M d, Y h:i A') }}
                            </small>
                        </div>
                        <div>
                            @if($order->is_editable)
                                <a href="{{ route('editordersinformations', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                            @endif
                            <a href="{{ route('ordersinformations') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Order Status -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-info-circle me-1"></i> Order Information
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Order Type:</td>
                                    <td class="fw-semibold">
                                        <span class="badge bg-info">{{ ucfirst($order->order_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Order Status:</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'accepted' => 'info',
                                                'preparing' => 'primary',
                                                'ready' => 'success',
                                                'dispatched' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                'rejected' => 'danger',
                                                'failed' => 'danger'
                                            ];
                                            $color = $statusColors[$order->order_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment Status:</td>
                                    <td>
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'info'
                                            ];
                                            $payColor = $paymentColors[$order->payment_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $payColor }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment Method:</td>
                                    <td class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                </tr>
                                @if($order->payment_reference)
                                    <tr>
                                        <td class="text-muted">Payment Ref:</td>
                                        <td class="fw-semibold">{{ $order->payment_reference }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-person me-1"></i> Customer Details
                            </h6>
                            @if($order->customer)
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-muted" width="40%">Name:</td>
                                        <td class="fw-semibold">{{ $order->customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email:</td>
                                        <td>{{ $order->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Phone:</td>
                                        <td>{{ $order->customer->phone }}</td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted">Guest Order</p>
                            @endif
                        </div>

                        <!-- Supplier Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-shop me-1"></i> Supplier Details
                            </h6>
                            @if($order->supplier)
                                <table class="table table-sm">
                                    <tr>
                                        <td class="text-muted" width="40%">Business:</td>
                                        <td class="fw-semibold">{{ $order->supplier->business_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Contact:</td>
                                        <td>{{ $order->supplier->user->phone ?? '—' }}</td>
                                    </tr>
                                </table>
                            @endif
                        </div>

                        <!-- Delivery Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-geo-alt me-1"></i> Delivery Details
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Address:</td>
                                    <td>{{ $order->delivery_address_text }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Contact:</td>
                                    <td class="fw-semibold">{{ $order->delivery_contact_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td class="fw-semibold">{{ $order->delivery_phone }}</td>
                                </tr>
                                @if($order->estimated_delivery_time)
                                    <tr>
                                        <td class="text-muted">Est. Time:</td>
                                        <td>{{ $order->estimated_delivery_time }} minutes</td>
                                    </tr>
                                @endif
                                @if($order->delivery_otp)
                                    <tr>
                                        <td class="text-muted">Delivery OTP:</td>
                                        <td><code class="fs-5 fw-bold">{{ $order->delivery_otp }}</code></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($order->special_instructions)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong><i class="bi bi-info-circle me-1"></i> Special Instructions:</strong><br>
                            {{ $order->special_instructions }}
                        </div>
                    @endif

                    @if($order->cancellation_reason)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong><i class="bi bi-x-circle me-1"></i> Cancellation Reason:</strong><br>
                            {{ $order->cancellation_reason }}
                        </div>
                    @endif

                    @if($order->rejection_reason)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong><i class="bi bi-x-circle me-1"></i> Rejection Reason:</strong><br>
                            {{ $order->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-basket me-1"></i> Order Items
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Addons</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong class="text-darkblue">{{ $item->item_name }}</strong>
                                            @if($item->variant_name)
                                                <br><small class="text-muted">{{ $item->variant_name }}</small>
                                            @endif
                                            @if($item->special_instructions)
                                                <br><small class="text-info">
                                                    <i class="bi bi-chat-left-text"></i> {{ $item->special_instructions }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>TZS {{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td>
                                            @if($item->selected_addons)
                                                <small>
                                                    @foreach($item->selected_addons as $addon)
                                                        <span class="badge bg-light text-dark">
                                                            {{ $addon['name'] }} ({{ $addon['quantity'] }})
                                                        </span>
                                                    @endforeach
                                                </small>
                                                <br><small class="text-success">+TZS {{ number_format($item->addons_total, 2) }}</small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">
                                            TZS {{ number_format($item->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Actions -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-accent text-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-calculator me-1"></i> Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Subtotal:</td>
                            <td class="text-end fw-semibold">TZS {{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Delivery Fee:</td>
                            <td class="text-end">TZS {{ number_format($order->delivery_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Service Fee:</td>
                            <td class="text-end">TZS {{ number_format($order->service_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tax:</td>
                            <td class="text-end">TZS {{ number_format($order->tax_amount, 2) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td class="text-success">
                                    Discount
                                    @if($order->coupon_code)
                                        <br><small>({{ $order->coupon_code }})</small>
                                    @endif
                                </td>
                                <td class="text-end text-success">-TZS {{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold fs-5">Total:</td>
                            <td class="text-end fw-bold fs-5 text-success">
                                TZS {{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-clock-history me-1"></i> Order Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0 fw-semibold">Order Placed</p>
                            </div>
                        </div>

                        @if($order->accepted_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $order->accepted_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 fw-semibold">Order Accepted</p>
                                </div>
                            </div>
                        @endif

                        @if($order->prepared_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $order->prepared_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 fw-semibold">Order Prepared</p>
                                </div>
                            </div>
                        @endif

                        @if($order->dispatched_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $order->dispatched_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 fw-semibold">Order Dispatched</p>
                                </div>
                            </div>
                        @endif

                        @if($order->delivered_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $order->delivered_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 fw-semibold">Order Delivered</p>
                                </div>
                            </div>
                        @endif

                        @if($order->cancelled_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">{{ $order->cancelled_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 fw-semibold">Order Cancelled</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-lightning me-1"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($order->order_status == 'pending')
                            <form action="{{ route('updateStatusConfirm', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="order_status" value="accepted">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-1"></i> Accept Order
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Reject Order
                            </button>
                        @endif

                        @if($order->order_status == 'accepted')
                            <form action="{{ route('updateStatusPrepare', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="order_status" value="preparing">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-lg me-1"></i> Mark as Preparing
                                </button>
                            </form>
                        @endif

                        @if($order->order_status == 'preparing')
                            <form action="{{ route('updateStatusReady', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="order_status" value="dispatched">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-truck me-1"></i> Mark as Dispatched
                                </button>
                            </form>
                        @endif

                        @if($order->order_status == 'dispatched')
                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="order_status" value="delivered">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle-fill me-1"></i> Mark as Delivered
                                </button>
                            </form>
                        @endif
                        @if(($order->order_status !== 'cancelled') && ($order->payment_status == 'pending'))
                            <form action="{{ route('markOrderAsPaid', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="payment_status" value="paid">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle-fill me-1"></i> Mark as Paid
                                </button>
                            </form>
                        @endif
                        @if($order->is_cancellable)
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="bi bi-x-circle me-1"></i> Cancel Order
                            </button>
                        @endif

                        <a href="{{ route('ordersinformations') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="order_status" value="cancelled">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Cancellation Reason</label>
                    <textarea name="cancellation_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="order_status" value="rejected">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Rejection Reason</label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Reject Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 8px;
    bottom: -12px;
    width: 2px;
    background: #e0e0e0;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}
.timeline-content {
    padding-left: 10px;
}
</style>
@endpush