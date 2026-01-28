@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-pencil-square me-2 text-accent"></i> Edit Order #{{ $order->order_number }}
                    </h5>
                </div>

                <div class="card-body">
                    @if(!$order->is_editable)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This order is in <strong>{{ $order->order_status }}</strong> status and cannot be fully edited. 
                            You can only update delivery information.
                        </div>
                    @endif

                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Order Information (Read-only) -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-info-circle me-1"></i> Order Information
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Order Number</label>
                                    <input type="text" class="form-control" value="{{ $order->order_number }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Order Status</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($order->order_status) }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Supplier</label>
                                    <input type="text" class="form-control" value="{{ $order->supplier->business_name ?? '—' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Payment Method</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}" disabled>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Delivery Information (Editable) -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-geo-alt me-1"></i> Delivery Information
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        Delivery Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="delivery_address_text" 
                                              rows="2" 
                                              class="form-control @error('delivery_address_text') is-invalid @enderror" 
                                              placeholder="Enter full delivery address" 
                                              required>{{ old('delivery_address_text', $order->delivery_address_text) }}</textarea>
                                    @error('delivery_address_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Latitude</label>
                                    <input type="text" 
                                           name="delivery_latitude" 
                                           class="form-control @error('delivery_latitude') is-invalid @enderror" 
                                           placeholder="-6.7924" 
                                           value="{{ old('delivery_latitude', $order->delivery_latitude) }}">
                                    @error('delivery_latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Longitude</label>
                                    <input type="text" 
                                           name="delivery_longitude" 
                                           class="form-control @error('delivery_longitude') is-invalid @enderror" 
                                           placeholder="39.2083" 
                                           value="{{ old('delivery_longitude', $order->delivery_longitude) }}">
                                    @error('delivery_longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Delivery Phone <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="delivery_phone" 
                                           class="form-control @error('delivery_phone') is-invalid @enderror" 
                                           placeholder="+255 XXX XXX XXX" 
                                           value="{{ old('delivery_phone', $order->delivery_phone) }}" 
                                           required>
                                    @error('delivery_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Name</label>
                                    <input type="text" 
                                           name="delivery_contact_name" 
                                           class="form-control @error('delivery_contact_name') is-invalid @enderror" 
                                           placeholder="John Doe" 
                                           value="{{ old('delivery_contact_name', $order->delivery_contact_name) }}">
                                    @error('delivery_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Scheduled Date (if applicable) -->
                        @if($order->order_type == 'scheduled')
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Scheduled Date & Time</label>
                                <input type="datetime-local" 
                                       name="scheduled_at" 
                                       class="form-control @error('scheduled_at') is-invalid @enderror" 
                                       value="{{ old('scheduled_at', $order->scheduled_at ? $order->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text me-1"></i> Special Instructions
                            </label>
                            <textarea name="special_instructions" 
                                      rows="3" 
                                      class="form-control @error('special_instructions') is-invalid @enderror" 
                                      placeholder="Any special instructions for this order">{{ old('special_instructions', $order->special_instructions) }}</textarea>
                            @error('special_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Order Items (Read-only display) -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-darkblue mb-3">
                                <i class="bi bi-basket me-1"></i> Order Items
                                <span class="badge bg-secondary ms-2">{{ $order->orderItems->count() }} items</span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $item->item_name }}</strong>
                                                    @if($item->variant_name)
                                                        <br><small class="text-muted">{{ $item->variant_name }}</small>
                                                    @endif
                                                    @if($item->special_instructions)
                                                        <br><small class="text-info">
                                                            <i class="bi bi-chat-left-text"></i> {{ $item->special_instructions }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                                </td>
                                                <td class="text-end">
                                                    TZS {{ number_format($item->subtotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                <small>Order items cannot be modified after order is placed. Please contact support if you need to modify items.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-accent">
                                <i class="bi bi-check-lg me-1"></i> Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-md-4">
            <!-- Current Order Summary -->
            <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-accent text-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-calculator me-1"></i> Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-3">
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

                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Note:</strong> Pricing cannot be modified after order is placed.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Order Status Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-darkblue">
                        <i class="bi bi-info-circle me-1"></i> Order Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Current Status</label>
                        <div>
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
                            <h5>
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </h5>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Payment Status</label>
                        <div>
                            @php
                                $paymentColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'refunded' => 'info'
                                ];
                                $payColor = $paymentColors[$order->payment_status] ?? 'secondary';
                            @endphp
                            <h5>
                                <span class="badge bg-{{ $payColor }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </h5>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Order Date</label>
                        <p class="mb-0 fw-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    @if($order->estimated_delivery_time)
                        <div class="mb-0">
                            <label class="form-label small text-muted">Estimated Delivery</label>
                            <p class="mb-0 fw-semibold">{{ $order->estimated_delivery_time }} minutes</p>
                        </div>
                    @endif

                    @if($order->delivery_otp)
                        <div class="mt-3 p-3 bg-light rounded text-center">
                            <small class="text-muted d-block mb-1">Delivery OTP</small>
                            <code class="fs-4 fw-bold">{{ $order->delivery_otp }}</code>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting
    const phoneInput = document.querySelector('input[name="delivery_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0 && value[0] !== '+') {
                value = '+' + value;
            }
            e.target.value = value;
        });
    }

    // Coordinate validation
    const latInput = document.querySelector('input[name="delivery_latitude"]');
    const lonInput = document.querySelector('input[name="delivery_longitude"]');
    
    if (latInput) {
        latInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (value < -90 || value > 90) {
                this.classList.add('is-invalid');
                this.parentElement.querySelector('.invalid-feedback')?.remove();
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block';
                feedback.textContent = 'Latitude must be between -90 and 90';
                this.parentElement.appendChild(feedback);
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }

    if (lonInput) {
        lonInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (value < -180 || value > 180) {
                this.classList.add('is-invalid');
                this.parentElement.querySelector('.invalid-feedback')?.remove();
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block';
                feedback.textContent = 'Longitude must be between -180 and 180';
                this.parentElement.appendChild(feedback);
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endpush