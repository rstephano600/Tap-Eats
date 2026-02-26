<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view_dashboard',
            'view_analytics',
            'view_reports',
            
            // Orders Management
            'view_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',
            'cancel_orders',
            'refund_orders',
            'assign_drivers',
            'update_order_status',
            'view_all_orders',
            
            // Menu Management
            'view_menu_items',
            'create_menu_items',
            'edit_menu_items',
            'delete_menu_items',
            'manage_menu_categories',
            'manage_menu_variants',
            'manage_menu_addons',
            'import_menu_items',
            'export_menu_items',
            
            // Supplier/Restaurant Management
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'delete_suppliers',
            'manage_supplier_settings',
            'manage_supplier_hours',
            'manage_supplier_delivery_zones',
            'view_all_suppliers',
            
            // Customer Management
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            'view_customer_orders',
            'manage_customer_addresses',
            'ban_customers',
            
            // Driver Management
            'view_drivers',
            'create_drivers',
            'edit_drivers',
            'delete_drivers',
            'assign_orders_to_drivers',
            'track_drivers',
            'manage_driver_payouts',
            
            // Staff Management
            'view_staff',
            'create_staff',
            'edit_staff',
            'delete_staff',
            'assign_roles',
            'manage_permissions',
            
            // POS Management
            'access_pos',
            'create_pos_orders',
            'process_payments',
            'manage_cash_drawer',
            'print_receipts',
            'void_transactions',
            'apply_discounts',
            
            // Catering Management
            'view_catering_orders',
            'create_catering_orders',
            'edit_catering_orders',
            'approve_catering_orders',
            'assign_catering_staff',
            'manage_catering_packages',
            
            // Inventory Management
            'view_inventory',
            'manage_inventory',
            'create_purchase_orders',
            'receive_inventory',
            'adjust_stock',
            'view_stock_reports',
            'manage_suppliers_inventory',
            
            // Financial Management
            'view_financial_reports',
            'view_sales_reports',
            'view_revenue_reports',
            'manage_pricing',
            'manage_taxes',
            'manage_service_fees',
            'process_payouts',
            'manage_commissions',
            'export_financial_data',
            'view_all_financials',
            
            // Marketing & Promotions
            'view_promotions',
            'create_promotions',
            'edit_promotions',
            'delete_promotions',
            'manage_coupons',
            'send_notifications',
            'manage_campaigns',
            
            // Reviews & Ratings
            'view_reviews',
            'respond_to_reviews',
            'moderate_reviews',
            'delete_reviews',
            
            // Support & Tickets
            'view_support_tickets',
            'create_support_tickets',
            'respond_to_tickets',
            'assign_tickets',
            'close_tickets',
            'view_all_tickets',
            
            // Settings
            'manage_general_settings',
            'manage_payment_settings',
            'manage_notification_settings',
            'manage_email_templates',
            'manage_sms_settings',
            'manage_integrations',
            'manage_api_keys',
            'view_system_logs',
            
            // Reports
            'view_order_reports',
            'view_inventory_reports',
            'view_driver_reports',
            'view_customer_reports',
            'view_performance_reports',
            'export_reports',
            
            // System Administration
            'manage_system_settings',
            'manage_roles_permissions',
            'view_audit_logs',
            'manage_backups',
            'clear_cache',
            'access_developer_tools',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Super Admin Role
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Create Admin Role
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = [
            'view_dashboard', 'view_analytics', 'view_reports',
            'view_orders', 'create_orders', 'edit_orders', 'cancel_orders', 'refund_orders', 
            'assign_drivers', 'update_order_status',
            'view_menu_items', 'create_menu_items', 'edit_menu_items', 'delete_menu_items',
            'manage_menu_categories', 'manage_menu_variants', 'manage_menu_addons',
            'import_menu_items', 'export_menu_items',
            'edit_suppliers', 'manage_supplier_settings', 'manage_supplier_hours',
            'manage_supplier_delivery_zones',
            'view_customers', 'edit_customers', 'view_customer_orders', 'manage_customer_addresses',
            'view_drivers', 'create_drivers', 'edit_drivers', 'assign_orders_to_drivers', 'track_drivers',
            'view_staff', 'create_staff', 'edit_staff', 'assign_roles',
            'access_pos', 'create_pos_orders', 'process_payments', 'manage_cash_drawer',
            'print_receipts', 'apply_discounts',
            'view_catering_orders', 'create_catering_orders', 'edit_catering_orders',
            'approve_catering_orders', 'manage_catering_packages',
            'view_inventory', 'manage_inventory', 'create_purchase_orders',
            'receive_inventory', 'adjust_stock', 'view_stock_reports',
            'view_financial_reports', 'view_sales_reports', 'view_revenue_reports',
            'manage_pricing', 'manage_taxes', 'manage_service_fees',
            'view_promotions', 'create_promotions', 'edit_promotions', 'delete_promotions',
            'manage_coupons', 'send_notifications',
            'view_reviews', 'respond_to_reviews', 'moderate_reviews',
            'view_support_tickets', 'respond_to_tickets', 'assign_tickets', 'close_tickets',
            'view_order_reports', 'view_inventory_reports', 'view_driver_reports',
            'view_customer_reports', 'export_reports',
        ];
        $admin->givePermissionTo($adminPermissions);

        // Create Restaurant Manager Role
        $manager = Role::create(['name' => 'restaurant_manager', 'guard_name' => 'web']);
        $managerPermissions = [
            'view_dashboard', 'view_analytics',
            'view_orders', 'create_orders', 'edit_orders', 'update_order_status', 'assign_drivers',
            'view_menu_items', 'create_menu_items', 'edit_menu_items', 'manage_menu_categories',
            'view_staff', 'create_staff', 'edit_staff',
            'access_pos', 'create_pos_orders', 'process_payments',
            'view_inventory', 'manage_inventory', 'adjust_stock',
            'view_order_reports', 'view_sales_reports',
            'view_reviews', 'respond_to_reviews',
        ];
        $manager->givePermissionTo($managerPermissions);

        // Create Restaurant Staff Role
        $staff = Role::create(['name' => 'restaurant_staff', 'guard_name' => 'web']);
        $staff->givePermissionTo(['view_dashboard', 'view_orders', 'update_order_status', 
            'view_menu_items', 'access_pos', 'create_pos_orders', 'view_inventory']);

        // Create Cashier Role
        $cashier = Role::create(['name' => 'cashier', 'guard_name' => 'web']);
        $cashier->givePermissionTo(['access_pos', 'create_pos_orders', 'process_payments',
            'manage_cash_drawer', 'print_receipts', 'apply_discounts', 'view_orders']);

        // Create Driver Role
        $driver = Role::create(['name' => 'driver', 'guard_name' => 'web']);
        $driver->givePermissionTo(['view_dashboard', 'view_orders', 'update_order_status']);

        // Create Customer Service Role
        $cs = Role::create(['name' => 'customer_service', 'guard_name' => 'web']);
        $csPermissions = [
            'view_dashboard', 'view_orders', 'edit_orders', 'cancel_orders', 'refund_orders',
            'view_customers', 'edit_customers',
            'view_support_tickets', 'create_support_tickets', 'respond_to_tickets', 'close_tickets',
            'view_reviews', 'respond_to_reviews',
        ];
        $cs->givePermissionTo($csPermissions);

        // Create Accountant Role
        $accountant = Role::create(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->givePermissionTo(['view_dashboard', 'view_financial_reports', 'view_sales_reports',
            'view_revenue_reports', 'process_payouts', 'manage_commissions', 'export_financial_data',
            'view_orders', 'export_reports']);

        // Create Inventory Manager Role
        $inventoryManager = Role::create(['name' => 'inventory_manager', 'guard_name' => 'web']);
        $inventoryManager->givePermissionTo(['view_dashboard', 'view_inventory', 'manage_inventory',
            'create_purchase_orders', 'receive_inventory', 'adjust_stock', 'view_stock_reports',
            'manage_suppliers_inventory', 'view_menu_items', 'edit_menu_items']);

        // Create Catering Manager Role
        $cateringManager = Role::create(['name' => 'catering_manager', 'guard_name' => 'web']);
        $cateringManager->givePermissionTo(['view_dashboard', 'view_catering_orders',
            'create_catering_orders', 'edit_catering_orders', 'approve_catering_orders',
            'assign_catering_staff', 'manage_catering_packages', 'view_menu_items',
            'view_customers', 'view_financial_reports']);
    }

    public function down()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Role::query()->delete();
        Permission::query()->delete();
    }
};