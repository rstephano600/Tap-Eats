<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use App\Models\MenuItem;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    public function dashboard()
    {
        $user     = auth()->user();
        $supplier = $this->getAuthSupplier();

        // =============================================
        // STATS — scoped to supplier or global for admin
        // =============================================
        $ordersQuery    = Order::query();
        $suppliersQuery = Supplier::query();
        $menuQuery      = MenuItem::query();
        $usersQuery     = User::query();

        if ($supplier) {
            $ordersQuery->where('supplier_id', $supplier->id);
            $menuQuery->where('supplier_id', $supplier->id);
        }

        $stats = [
            'total_orders'     => $ordersQuery->count(),
            'pending_orders'   => (clone $ordersQuery)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $ordersQuery)->where('order_status', 'completed')->count(),
            'total_revenue'    => (clone $ordersQuery)->where('order_status', 'completed')->sum('total_amount'),
            'total_suppliers'  => $suppliersQuery->count(),
            'total_menu_items' => $menuQuery->count(),
            'total_users'      => $usersQuery->count(),
            'today_orders'     => (clone $ordersQuery)->whereDate('created_at', today())->count(),
        ];

        // Recent orders
        $recentOrders = (clone $ordersQuery)
            ->with(['customer', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        $monthlyLabels  = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[]  = $month->format('M Y');
            $monthlyRevenue[] = (clone $ordersQuery)
                ->where('order_status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
        }

        // Top suppliers by orders (admin only)
        $topSuppliers = Supplier::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        return view('in.dashboard.dashboard', compact(
            'user', 'supplier', 'stats',
            'recentOrders', 'monthlyRevenue',
            'monthlyLabels', 'topSuppliers'
        ));
    }
}