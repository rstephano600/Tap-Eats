@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Orders</p>
                            <h4 class="mb-0 fw-bold text-darkblue">{{ $statistics['total_orders'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart-check fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pending</p>
                            <h4 class="mb-0 fw-bold text-warning">{{ $statistics['pending_orders'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock-history fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Completed</p>
                            <h4 class="mb-0 fw-bold text-success">{{ $statistics['completed_orders'] ?? 0 }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Revenue</p>
                            <h4 class="mb-0 fw-bold text-success">TZS {{ number_format($statistics['total_revenue'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-darkblue">
                <i class="bi bi-cart-check me-2 text-accent"></i> Orders Management
            </h5>
            <a href="{{ route('orders.create') }}" class="btn btn-accent btn-sm">
                <i class="bi bi-plus-lg me-1"></i> New Order
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Order Status</label>
                        <select name="order_status" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('order_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="preparing" {{ request('order_status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ request('order_status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="dispatched" {{ request('order_status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                            <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">All Payment Status</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">To Date</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold text-darkblue">
                                    {{ $order->order_number }}
                                    @if($order->order_type == 'scheduled')
                                        <span class="badge bg-info ms-1">
                                            <i class="bi bi-clock"></i>
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($order->customer)
                                        <div>
                                            <strong>{{ $order->customer->name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer->phone }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Guest Order</span>
                                    @endif
                                </td>

                                <td>
                                    @if($order->supplier)
                                        {{ $order->supplier->business_name }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $order->orderItems->count() }} items
                                    </span>
                                </td>

                                <td>
                                    <strong class="text-success">
                                        TZS {{ number_format($order->total_amount, 2) }}
                                    </strong>
                                </td>

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
                                    <br>
                                    <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                </td>

                                <td>
                                    <small>{{ $order->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="btn btn-outline-info"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($order->is_editable)
                                            <a href="{{ route('orders.edit', $order->id) }}"
                                               class="btn btn-outline-primary"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        @if($order->is_cancellable)
                                            <form action="{{ route('orders.destroy', $order->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger"
                                                        onclick="return confirm('Cancel this order?')"
                                                        title="Cancel">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection