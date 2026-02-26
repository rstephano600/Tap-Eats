@extends('layouts.app')

@section('title', 'Orders Summary')

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

{{-- Statistics Cards --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(0,31,63,0.08);">
                <i class="bi bi-bag" style="color:#001f3f;"></i>
            </div>
            <div>
                <div class="stat-value">{{ $statistics['total_orders'] ?? 0 }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-trend text-muted ms-auto">
                <i class="bi bi-bar-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(255,167,38,0.12);">
                <i class="bi bi-clock-history text-warning"></i>
            </div>
            <div>
                <div class="stat-value text-warning">{{ $statistics['pending_orders'] ?? 0 }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-trend text-warning ms-auto">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(25,135,84,0.1);">
                <i class="bi bi-check-circle text-success"></i>
            </div>
            <div>
                <div class="stat-value text-success">{{ $statistics['completed_orders'] ?? 0 }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-trend text-success ms-auto">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(25,135,84,0.1);">
                <i class="bi bi-cash-coin text-success"></i>
            </div>
            <div>
                <div class="stat-value text-success" style="font-size:1rem;">
                    TZS {{ number_format($statistics['total_revenue'] ?? 0, 2) }}
                </div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-trend text-success ms-auto">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>

</div>


                <div class="card-body">
                    <form action="{{ route('storeordersinformations') }}" method="POST" id="orderForm">
                        @csrf

                                                <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Supplier <span class="text-danger">*</span>
                                </label>
                                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier->supplier_id ? 'selected' : '' }}>
                                            {{ $supplier->supplier->business_name }} - {{ $supplier->supplier->supplier_id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        <div>

                        <button type="submit" class="btn btn-accent">
                                <i class="bi bi-check-lg me-1"></i> Create Order
                            </button>
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