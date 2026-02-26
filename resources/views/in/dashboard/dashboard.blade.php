@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Welcome Header --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color:#001f3f;">
            👋 Welcome back, {{ auth()->user()->name }}!
        </h4>
        <p class="text-muted mb-0" style="font-size:0.85rem;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->format('l, d F Y') }} &nbsp;·&nbsp;
            <span class="badge rounded-pill px-2 py-1"
                  style="background:rgba(40,167,69,0.1);color:#1a6b30;font-size:0.72rem;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;
                             background:#28a745;margin-right:4px;"></span>
                Online
            </span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ordersinformations') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-bag-check me-1"></i> View Orders
        </a>
        <a href="{{ route('createmenuItemsInformations') }}" class="btn btn-sm btn-accent rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Menu Item
        </a>
    </div>
</div>

{{-- =====================
     STAT CARDS
===================== --}}
<div class="row g-3 mb-4">

    {{-- Total Orders --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#001f3f;--accent-light:rgba(0,31,63,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value">{{ number_format($stats['total_orders']) }}</div>
                <div class="dash-stat-label">Total Orders</div>
            </div>
            <div class="dash-stat-arrow">
                <i class="bi bi-arrow-up-right text-success" style="font-size:0.8rem;"></i>
            </div>
        </div>
    </div>

    {{-- Pending --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#FFA726;--accent-light:rgba(255,167,38,0.1);">
            <div class="dash-stat-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#FFA726;">
                    {{ number_format($stats['pending_orders']) }}
                </div>
                <div class="dash-stat-label">Pending</div>
            </div>
            @if($stats['pending_orders'] > 0)
            <div class="dash-stat-arrow">
                <span class="badge rounded-pill"
                      style="background:#FFA726;color:#fff;font-size:0.6rem;animation:pulse 1.5s infinite;">
                    NEW
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Completed --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#28a745;--accent-light:rgba(40,167,69,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#28a745;">
                    {{ number_format($stats['completed_orders']) }}
                </div>
                <div class="dash-stat-label">Completed</div>
            </div>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#0dcaf0;--accent-light:rgba(13,202,240,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#0aa2c0;font-size:1rem;">
                    TZS {{ number_format($stats['total_revenue'], 0) }}
                </div>
                <div class="dash-stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    {{-- Today Orders --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#6f42c1;--accent-light:rgba(111,66,193,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-calendar-day"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#6f42c1;">
                    {{ number_format($stats['today_orders']) }}
                </div>
                <div class="dash-stat-label">Today's Orders</div>
            </div>
        </div>
    </div>

    {{-- Menu Items --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#fd7e14;--accent-light:rgba(253,126,20,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-egg-fried"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#fd7e14;">
                    {{ number_format($stats['total_menu_items']) }}
                </div>
                <div class="dash-stat-label">Menu Items</div>
            </div>
        </div>
    </div>

    {{-- Suppliers (admin only) --}}
    @if(auth()->user()->hasRole('super_admin'))
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#20c997;--accent-light:rgba(32,201,151,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-shop"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#20c997;">
                    {{ number_format($stats['total_suppliers']) }}
                </div>
                <div class="dash-stat-label">Suppliers</div>
            </div>
        </div>
    </div>

    {{-- Total Users (admin only) --}}
    <div class="col-6 col-md-3">
        <div class="dash-stat-card" style="--accent-color:#dc3545;--accent-light:rgba(220,53,69,0.08);">
            <div class="dash-stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="dash-stat-content">
                <div class="dash-stat-value" style="color:#dc3545;">
                    {{ number_format($stats['total_users']) }}
                </div>
                <div class="dash-stat-label">Total Users</div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- =====================
     CHART + TOP SUPPLIERS
===================== --}}
<div class="row g-3 mb-4">

    {{-- Revenue Chart --}}
    <div class="{{ auth()->user()->hasRole('super_admin') ? 'col-md-8' : 'col-12' }}">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#001f3f;">
                            <i class="bi bi-graph-up-arrow me-2 text-success"></i>Revenue Overview
                        </h6>
                        <small class="text-muted">Last 6 months performance</small>
                    </div>
                    <span class="badge rounded-pill px-3 py-2"
                          style="background:rgba(0,31,63,0.06);color:#001f3f;font-size:0.72rem;">
                        TZS {{ number_format($stats['total_revenue'], 0) }} Total
                    </span>
                </div>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Suppliers (admin only) --}}
    @if(auth()->user()->hasRole('super_admin'))
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#001f3f;">
                    <i class="bi bi-trophy me-2 text-warning"></i>Top Suppliers
                </h6>
                @forelse($topSuppliers as $index => $ts)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="fw-bold rounded-circle d-flex align-items-center justify-content-center"
                         style="width:28px;height:28px;font-size:0.72rem;flex-shrink:0;
                                background:{{ $index === 0 ? '#FFA726' : ($index === 1 ? '#adb5bd' : 'rgba(0,31,63,0.08)') }};
                                color:{{ $index < 2 ? '#fff' : '#001f3f' }};">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold text-truncate" style="font-size:0.83rem;color:#001f3f;">
                            {{ $ts->business_name }}
                        </div>
                        <div class="text-muted" style="font-size:0.72rem;">
                            {{ $ts->orders_count }} orders
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="dash-mini-bar">
                            <div class="dash-mini-bar-fill"
                                 style="width:{{ $topSuppliers->first()->orders_count > 0 ? ($ts->orders_count / $topSuppliers->first()->orders_count) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center" style="font-size:0.82rem;">No data yet</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif

</div>

{{-- =====================
     ORDER STATUS BREAKDOWN + RECENT ORDERS
===================== --}}
<div class="row g-3">

    {{-- Order Status Doughnut --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1" style="color:#001f3f;">
                    <i class="bi bi-pie-chart me-2" style="color:#FFA726;"></i>Order Breakdown
                </h6>
                <small class="text-muted d-block mb-3">Status distribution</small>
                <canvas id="statusChart" height="180"></canvas>

                {{-- Legend --}}
                <div class="mt-3 d-flex flex-column gap-2">
                    @php
                        $statusData = [
                            ['label' => 'Pending',   'color' => '#FFA726', 'count' => $stats['pending_orders']],
                            ['label' => 'Completed', 'color' => '#28a745', 'count' => $stats['completed_orders']],
                            ['label' => 'Others',    'color' => '#adb5bd',
                             'count' => $stats['total_orders'] - $stats['pending_orders'] - $stats['completed_orders']],
                        ];
                    @endphp
                    @foreach($statusData as $sd)
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:10px;height:10px;border-radius:50%;
                                         background:{{ $sd['color'] }};display:inline-block;"></span>
                            <span style="font-size:0.78rem;color:#6c757d;">{{ $sd['label'] }}</span>
                        </div>
                        <span class="fw-semibold" style="font-size:0.78rem;color:#001f3f;">
                            {{ $sd['count'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-0">
                <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#001f3f;">
                            <i class="bi bi-clock-history me-2" style="color:#FFA726;"></i>Recent Orders
                        </h6>
                        <small class="text-muted">Latest 6 orders</small>
                    </div>
                    <a href="{{ route('ordersinformations') }}"
                       class="btn btn-sm rounded-pill px-3"
                       style="background:rgba(0,31,63,0.06);color:#001f3f;font-size:0.78rem;">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0" style="font-size:0.82rem;">
                        <thead>
                            <tr style="background:#fafafa;">
                                <th class="px-4 py-3 fw-semibold text-muted border-0"
                                    style="font-size:0.72rem;letter-spacing:0.5px;">ORDER</th>
                                <th class="py-3 fw-semibold text-muted border-0"
                                    style="font-size:0.72rem;letter-spacing:0.5px;">CUSTOMER</th>
                                <th class="py-3 fw-semibold text-muted border-0"
                                    style="font-size:0.72rem;letter-spacing:0.5px;">AMOUNT</th>
                                <th class="py-3 fw-semibold text-muted border-0"
                                    style="font-size:0.72rem;letter-spacing:0.5px;">STATUS</th>
                                <th class="py-3 fw-semibold text-muted border-0"
                                    style="font-size:0.72rem;letter-spacing:0.5px;">DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="recent-order-row">
                                <td class="px-4 py-3 border-0">
                                    <span class="fw-semibold" style="color:#001f3f;">
                                        #{{ $order->order_number ?? $order->id }}
                                    </span>
                                </td>
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center"
                                             style="width:26px;height:26px;background:#001f3f;font-size:0.65rem;flex-shrink:0;">
                                            {{ substr($order->customer->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span class="text-truncate" style="max-width:100px;">
                                            {{ $order->customer->name ?? 'Guest' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 border-0 fw-semibold text-success">
                                    TZS {{ number_format($order->total_amount, 0) }}
                                </td>
                                <td class="py-3 border-0">
                                    @php
                                        $sc = [
                                            'pending'   => ['warning', 'clock'],
                                            'completed' => ['success', 'check-circle'],
                                            'cancelled' => ['danger',  'x-circle'],
                                            'preparing' => ['primary', 'fire'],
                                        ][$order->order_status] ?? ['secondary', 'question'];
                                    @endphp
                                    <span class="badge bg-{{ $sc[0] }}-subtle text-{{ $sc[0] }}
                                                 border border-{{ $sc[0] }}-subtle rounded-pill px-2">
                                        <i class="bi bi-{{ $sc[1] }} me-1"></i>
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td class="py-3 border-0 text-muted">
                                    {{ $order->created_at?->diffForHumans() ?? 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No orders yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    /* Stat Cards */
    .dash-stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .dash-stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        border-radius: 14px 0 0 14px;
    }
    .dash-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.09);
    }
    .dash-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--accent-light);
        color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .dash-stat-content { flex: 1; min-width: 0; }
    .dash-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #001f3f;
        line-height: 1.1;
    }
    .dash-stat-label {
        font-size: 0.7rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    /* Mini progress bar */
    .dash-mini-bar {
        width: 60px;
        height: 5px;
        background: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
    }
    .dash-mini-bar-fill {
        height: 100%;
        background: #FFA726;
        border-radius: 5px;
        transition: width 1s ease;
    }

    /* Recent orders row hover */
    .recent-order-row {
        transition: background 0.15s;
    }
    .recent-order-row:hover {
        background: #fafbff;
    }

    /* Pulse animation for pending badge */
    @keyframes pulse {
        0%   { box-shadow: 0 0 0 0 rgba(255,167,38,0.5); }
        70%  { box-shadow: 0 0 0 6px rgba(255,167,38,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,167,38,0); }
    }

    /* Card entrance animation */
    .card {
        animation: cardFadeUp 0.4s ease forwards;
    }
    @keyframes cardFadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =============================================
    // REVENUE LINE CHART
    // =============================================
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 31, 63, 0.15)');
    gradient.addColorStop(1, 'rgba(0, 31, 63, 0.0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Revenue (TZS)',
                data: @json($monthlyRevenue),
                borderColor: '#001f3f',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#FFA726',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#001f3f',
                    titleColor: '#FFA726',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ' TZS ' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#adb5bd' }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        font: { size: 11 },
                        color: '#adb5bd',
                        callback: val => 'TZS ' + (val / 1000).toFixed(0) + 'K'
                    }
                }
            }
        }
    });

    // =============================================
    // STATUS DOUGHNUT CHART
    // =============================================
    const statusCtx = document.getElementById('statusChart').getContext('2d');

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Completed', 'Others'],
            datasets: [{
                data: [
                    {{ $stats['pending_orders'] }},
                    {{ $stats['completed_orders'] }},
                    {{ $stats['total_orders'] - $stats['pending_orders'] - $stats['completed_orders'] }}
                ],
                backgroundColor: ['#FFA726', '#28a745', '#dee2e6'],
                borderColor: ['#fff', '#fff', '#fff'],
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#001f3f',
                    titleColor: '#FFA726',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 8,
                }
            }
        }
    });

});
</script>
@endpush