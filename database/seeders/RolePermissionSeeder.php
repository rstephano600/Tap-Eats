<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin      = Role::where('slug', 'admin')->first();
        $supplier   = Role::where('slug', 'supplier')->first();
        $customer   = Role::where('slug', 'customer')->first();

        // Super Admin → ALL permissions
        $superAdmin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        // Admin permissions
        $admin->permissions()->sync(
            Permission::whereIn('name', [
    'create-user',
    'edit-user',
    'delete-user',
    'view-users',
    'view-user-details',
    'activate-user',
    'deactivate-user',
    
    // Restaurant Management
    'create-restaurant',
    'edit-restaurant',
    'delete-restaurant',
    'view-restaurants',
    'approve-restaurant',
    'reject-restaurant',
    'suspend-restaurant',
    'activate-restaurant',
    
    // Order Management
    'view-all-orders',
    'edit-order',
    'refund-order',
    'assign-driver-to-order',
    'view-order-analytics',
    'manage-order-disputes',
    
    // System Management
    'manage-system-settings',
    'view-system-logs',
    'manage-roles',
    'assign-permission',
    'view-reports',
    'generate-reports',
    'export-reports',
    
    // Financial Management
    'view-financial-reports',
    'manage-payouts',
    'approve-payout',
    
    // And many more from the full list...

            ])->pluck('id')->toArray()
        );

        // Supplier permissions
        $supplier->permissions()->sync(
            Permission::whereIn('name', [

    'create-menu',
    'edit-menu',
    'delete-menu',
    'view-menu',
    'manage-menu-categories',
    'add-menu-item',
    'edit-menu-item',
    'delete-menu-item',
    'view-menu-items',
    'toggle-menu-availability',
    'manage-menu-pricing',
    'view-restaurant-orders',
    'accept-order',
    'reject-order',
    'update-order-status',
    'mark-order-ready',
    'view-order-details',
    'print-order-invoice',
    'create-promotion',
    'edit-promotion',
    'view-promotions',
    'manage-inventory',
    'update-stock',
    'view-inventory-reports',
    'view-earnings',
    'reply-to-review',
    'view-restaurant-dashboard',
    'view-sales-overview',
            ])->pluck('id')->toArray()
        );

        // Customer permissions
        $customer->permissions()->sync(
            Permission::whereIn('name', [
    'place-order',
    'cancel-order',
    'reorder',
    'rate-order',
    'view-order-history',
    'track-order',
    'apply-promo-code',
    'create-review',
    'edit-review',
    'delete-review',
    'view-reviews',
    'add-to-cart',
    'update-cart',
    'clear-cart',
    'view-cart',
    'add-to-favorites',
    'remove-from-favorites',
    'view-favorites',
    'add-address',
    'edit-address',
    'delete-address',
    'set-default-address',
    'view-addresses',
    'create-support-ticket',
    'view-support-tickets',
    'two-factor-authentication',
    'view-customer-dashboard',
    'redeem-rewards',
    'view-loyalty-points',
            ])->pluck('id')->toArray()
        );
        // Customer permissions
        $delivery->permissions()->sync(
            Permission::whereIn('name', [
    'view-delivery-assignments',
    'accept-delivery',
    'reject-delivery',
    'start-delivery',
    'complete-delivery',
    'update-delivery-location',
    'view-delivery-history',
    'report-delivery-issue',
    'track-delivery',
    'view-delivery-status',
    'view-earnings',
    'view-delivery-reports',
    'view-driver-dashboard',
    'two-factor-authentication',
            ])->pluck('id')->toArray()
        );
    }
}

