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
        // DETERMINE ACCESS LEVEL
        // null  = super_admin  (sees everything)
        // false = no supplier  (sees nothing / own profile only)
        // Supplier = scoped    (sees only their supplier data)
        // =============================================

        $isSuperAdmin   = $supplier === null;
        $hasNoSupplier  = $supplier === false;
        $isSupplierUser = $supplier instanceof Supplier;

        // =============================================
        // BUILD SCOPED QUERIES
        // =============================================
        $ordersQuery = Order::query();
        $menuQuery   = MenuItem::query();

        if ($isSuperAdmin) {
            // No filter — sees everything
        } elseif ($hasNoSupplier) {
            // Regular user with no supplier — sees nothing
            $ordersQuery->whereRaw('0 = 1');
            $menuQuery->whereRaw('0 = 1');
        } else {
            // Supplier owner or member — scoped to their supplier
            $ordersQuery->where('supplier_id', $supplier->id);
            $menuQuery->where('supplier_id', $supplier->id);
        }

        // =============================================
        // STATS
        // =============================================
        $stats = [
            'total_orders'     => (clone $ordersQuery)->count(),
            'pending_orders'   => (clone $ordersQuery)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $ordersQuery)->where('order_status', 'completed')->count(),
            'total_revenue'    => (clone $ordersQuery)->where('order_status', 'completed')->sum('total_amount'),
            'total_menu_items' => (clone $menuQuery)->count(),
            'today_orders'     => (clone $ordersQuery)->whereDate('created_at', today())->count(),

            // Admin-only stats
            'total_suppliers'  => $isSuperAdmin ? Supplier::count() : ($isSupplierUser ? 1 : 0),
            'total_users'      => $isSuperAdmin ? User::count() : 0,
        ];

        // =============================================
        // RECENT ORDERS
        // =============================================
        $recentOrders = (clone $ordersQuery)
            ->with(['customer', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // =============================================
        // MONTHLY REVENUE CHART (last 6 months)
        // =============================================
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

        // =============================================
        // TOP SUPPLIERS — admin only
        // =============================================
        $topSuppliers = collect(); // empty by default

        if ($isSuperAdmin) {
            $topSuppliers = Supplier::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->limit(5)
                ->get();
        }

        return view('in.dashboard.dashboard', compact(
            'user', 'supplier', 'stats',
            'recentOrders', 'monthlyRevenue', 'monthlyLabels',
            'topSuppliers', 'isSuperAdmin', 'hasNoSupplier', 'isSupplierUser'
        ));
    }
}