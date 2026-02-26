@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#001f3f;">
            <i class="bi bi-bag-check me-2" style="color:#FFA726;"></i> Orders Management
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-1" style="font-size:0.78rem;">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active text-muted">Orders</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('createordersinformations') }}" class="btn btn-accent btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> New Order
    </a>
</div>
{{-- Orders Table Card --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-2 d-flex align-items-center justify-content-center"
                 style="width:32px;height:32px;background:rgba(0,31,63,0.08);">
                <i class="bi bi-table" style="color:#001f3f;font-size:0.85rem;"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-semibold" style="color:#001f3f;">All Orders</h6>
                <small class="text-muted" style="font-size:0.7rem;">
                    {{ isset($orders) ? $orders->count() : 0 }} total records
                </small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge rounded-pill px-3 py-2"
                  style="background:#001f3f;font-size:0.72rem;">
                {{ isset($orders) ? $orders->count() : 0 }} Orders
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        <div class="table-responsive px-3 pt-3">
            <table class="table datatable w-100"
                   id="ordersTable"
                   data-title="Orders Report - {{ date('d M Y') }}">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Supplier</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Order Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th class="no-export text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        {{-- # --}}
                        <td class="text-muted" style="font-size:0.8rem;">{{ $loop->iteration }}</td>

                        {{-- Order Number --}}
                        <td>
                            <span class="fw-semibold" style="color:#001f3f;">
                                {{ $order->order_number }}
                            </span>
                            @if($order->order_type == 'scheduled')
                                <span class="badge bg-info-subtle text-info border border-info-subtle ms-1"
                                      style="font-size:0.65rem;">
                                    <i class="bi bi-clock me-1"></i>Scheduled
                                </span>
                            @endif
                        </td>

                        {{-- Customer --}}
                        <td>
                            @if($order->customer)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold"
                                         style="width:30px;height:30px;background:#001f3f;font-size:0.72rem;flex-shrink:0;">
                                        {{ substr($order->customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.83rem;">
                                            {{ $order->customer->name }}
                                        </div>
                                        <div class="text-muted" style="font-size:0.72rem;">
                                            <i class="bi bi-telephone me-1"></i>{{ $order->customer->phone ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted fst-italic" style="font-size:0.82rem;">
                                    <i class="bi bi-person-slash me-1"></i>Guest
                                </span>
                            @endif
                        </td>

                        {{-- Supplier --}}
                        <td style="font-size:0.83rem;">
                            @if($order->supplier)
                                <i class="bi bi-shop me-1 text-muted"></i>
                                {{ $order->supplier->business_name }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Items --}}
                        <td>
                            <span class="badge rounded-pill px-2"
                                  style="background:rgba(0,31,63,0.08);color:#001f3f;font-size:0.75rem;">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $order->orderItems->count() }}
                            </span>
                        </td>

                        {{-- Amount --}}
                        <td>
                            <span class="fw-bold text-success" style="font-size:0.88rem;">
                                {{ number_format($order->total_amount, 2) }} TZS
                            </span>
                        </td>

                        {{-- Order Status --}}
                        <td>
                            @php
                                $statusMap = [
                                    'pending'    => ['color' => 'warning',  'icon' => 'clock-history'],
                                    'accepted'   => ['color' => 'info',     'icon' => 'check'],
                                    'preparing'  => ['color' => 'primary',  'icon' => 'fire'],
                                    'ready'      => ['color' => 'success',  'icon' => 'bag-check'],
                                    'dispatched' => ['color' => 'primary',  'icon' => 'truck'],
                                    'delivered'  => ['color' => 'success',  'icon' => 'check-circle'],
                                    'cancelled'  => ['color' => 'danger',   'icon' => 'x-circle'],
                                    'rejected'   => ['color' => 'danger',   'icon' => 'slash-circle'],
                                    'failed'     => ['color' => 'danger',   'icon' => 'exclamation-circle'],
                                ];
                                $s = $statusMap[$order->order_status] ?? ['color' => 'secondary', 'icon' => 'question'];
                            @endphp
                            <span class="badge bg-{{ $s['color'] }}-subtle text-{{ $s['color'] }} border border-{{ $s['color'] }}-subtle rounded-pill px-2">
                                <i class="bi bi-{{ $s['icon'] }} me-1"></i>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>

                        {{-- Payment --}}
                        <td>
                            @php
                                $payMap = [
                                    'pending'  => 'warning',
                                    'paid'     => 'success',
                                    'failed'   => 'danger',
                                    'refunded' => 'info',
                                ];
                                $pc = $payMap[$order->payment_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $pc }}-subtle text-{{ $pc }} border border-{{ $pc }}-subtle rounded-pill px-2 d-block mb-1"
                                  style="width:fit-content;">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            <small class="text-muted" style="font-size:0.7rem;">
                                <i class="bi bi-credit-card me-1"></i>
                                {{ ucfirst($order->payment_method ?? '—') }}
                            </small>
                        </td>

                        {{-- Date --}}
                        <td>
                            <div style="font-size:0.82rem;">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $order->created_at->format('d M Y') }}
                            </div>
                            <div class="text-muted" style="font-size:0.72rem;">
                                <i class="bi bi-clock me-1"></i>
                                {{ $order->created_at->format('h:i A') }}
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="no-export text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a href="{{ route('showordersinformations', $order->id) }}"
                                   class="action-btn action-btn-view"
                                   title="View Order">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if($order->is_editable)
                                    <a href="{{ route('editordersinformations', $order->id) }}"
                                       class="action-btn action-btn-edit"
                                       title="Edit Order">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                @if($order->is_cancellable)
                                    <form action="{{ route('destroyordersinformations', $order->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                class="action-btn action-btn-delete"
                                                title="Cancel Order"
                                                onclick="confirmDelete(this)">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                            <p class="text-muted mb-0">No orders found.</p>
                            <a href="{{ route('createordersinformations') }}" class="btn btn-accent btn-sm mt-3">
                                <i class="bi bi-plus-lg me-1"></i> Create First Order
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    @php $runningTotal = 0; @endphp

@foreach($orders as $order)
    @php $runningTotal += $order->total_amount; @endphp
    @endforeach
                   <tr>
    <td>Total:</td>
    <td>
        <span class="fw-bold text-primary">
            TZS {{ number_format($runningTotal, 2) }}
        </span>
    </td>
</tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="font-size:3rem;">🗑️</div>
                <h5 class="fw-bold mb-1">Cancel this order?</h5>
                <p class="text-muted mb-4" style="font-size:0.88rem;">
                    This action cannot be undone. The order will be permanently cancelled.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">No, Keep it</button>
                    <button class="btn btn-danger px-4" id="confirmDeleteBtn">Yes, Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.09);
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #001f3f;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.72rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }
    .stat-trend {
        font-size: 1.1rem;
        opacity: 0.4;
    }

    /* Action Buttons */
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        border: 1px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        background: transparent;
    }
    .action-btn-view  { color: #0dcaf0; border-color: #0dcaf0; }
    .action-btn-view:hover  { background: #0dcaf0; color: #fff; }
    .action-btn-edit  { color: #0d6efd; border-color: #0d6efd; }
    .action-btn-edit:hover  { background: #0d6efd; color: #fff; }
    .action-btn-delete { color: #dc3545; border-color: #dc3545; }
    .action-btn-delete:hover { background: #dc3545; color: #fff; }
</style>
@endpush

@push('scripts')
<script>
    // Custom delete confirmation using modal instead of browser confirm()
    let deleteForm = null;

    function confirmDelete(btn) {
        deleteForm = btn.closest('form');
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteForm) deleteForm.submit();
    });
</script>
@endpush