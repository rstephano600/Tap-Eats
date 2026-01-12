<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
    // ========== USER MANAGEMENT ==========
    // User Management
    'create-user',
    'edit-user',
    'delete-user',
    'view-users',
    'view-user-details',
    'activate-user',
    'deactivate-user',
    'impersonate-user',
    'reset-user-password',
    
    // ========== ROLE & PERMISSION MANAGEMENT ==========
    // Role Management
    'create-role',
    'edit-role',
    'delete-role',
    'view-roles',
    'assign-role',
    'remove-role',
    
    // Permission Management
    'assign-permission',
    'revoke-permission',
    'view-permissions',
    'manage-roles',
    
    // ========== RESTAURANT MANAGEMENT ==========
    // Restaurant Profile
    'create-restaurant',
    'edit-restaurant',
    'delete-restaurant',
    'view-restaurants',
    'approve-restaurant',
    'reject-restaurant',
    'suspend-restaurant',
    'activate-restaurant',
    'manage-restaurant-profile',
    
    // Restaurant Menu Management
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
    
    // ========== ORDER MANAGEMENT ==========
    // Customer Order Permissions
    'place-order',
    'cancel-order',
    'reorder',
    'rate-order',
    'view-order-history',
    'track-order',
    'apply-promo-code',
    
    // Restaurant Order Permissions
    'view-restaurant-orders',
    'accept-order',
    'reject-order',
    'update-order-status',
    'mark-order-ready',
    'view-order-details',
    'print-order-invoice',
    
    // Admin/Platform Order Permissions
    'view-all-orders',
    'edit-order',
    'refund-order',
    'assign-driver-to-order',
    'view-order-analytics',
    'manage-order-disputes',
    
    // ========== DELIVERY MANAGEMENT ==========
    // Driver Permissions
    'view-delivery-assignments',
    'accept-delivery',
    'reject-delivery',
    'start-delivery',
    'complete-delivery',
    'update-delivery-location',
    'view-delivery-history',
    'report-delivery-issue',
    
    // Delivery Management (Admin/Restaurant)
    'assign-delivery',
    'reassign-delivery',
    'track-delivery',
    'view-delivery-status',
    'manage-delivery-partners',
    'rate-delivery-partner',
    
    // ========== PAYMENT & FINANCE ==========
    // Payment Processing
    'process-payment',
    'view-payment-history',
    'issue-refund',
    'view-transactions',
    'export-transactions',
    
    // Financial Management
    'view-earnings',
    'view-revenue-reports',
    'manage-payouts',
    'approve-payout',
    'view-financial-reports',
    
    // ========== INVENTORY & SUPPLY CHAIN ==========
    'manage-supplier-menu',
    'manage-supplier-orders',
    'delivery-food',
    'manage-inventory',
    'update-stock',
    'view-inventory-reports',
    'manage-suppliers',
    'create-purchase-order',
    'receive-stock',
    
    // ========== CUSTOMER MANAGEMENT ==========
    'view-customer-profiles',
    'edit-customer-profile',
    'view-customer-orders',
    'manage-customer-addresses',
    'view-customer-preferences',
    'manage-customer-wallet',
    'view-customer-loyalty-points',
    
    // ========== REVIEWS & RATINGS ==========
    'create-review',
    'edit-review',
    'delete-review',
    'view-reviews',
    'reply-to-review',
    'report-review',
    'manage-review-ratings',
    'feature-review',
    
    // ========== PROMOTIONS & MARKETING ==========
    'create-promotion',
    'edit-promotion',
    'delete-promotion',
    'view-promotions',
    'apply-promotion',
    'manage-coupons',
    'create-discount',
    'view-marketing-campaigns',
    'send-promotional-emails',
    'manage-newsletter',
    
    // ========== CONTENT MANAGEMENT ==========
    // Pages & Content
    'manage-pages',
    'create-page',
    'edit-page',
    'delete-page',
    
    // Blog & Articles
    'create-blog-post',
    'edit-blog-post',
    'delete-blog-post',
    'publish-blog-post',
    
    // FAQs & Support
    'manage-faqs',
    'create-faq',
    'edit-faq',
    'delete-faq',
    
    // ========== LOCATION & GEOGRAPHY ==========
    'manage-delivery-zones',
    'create-delivery-zone',
    'edit-delivery-zone',
    'delete-delivery-zone',
    'view-coverage-areas',
    'set-delivery-charges',
    'manage-service-areas',
    
    // ========== NOTIFICATIONS & COMMUNICATION ==========
    'send-notification',
    'view-notifications',
    'manage-notification-templates',
    'send-bulk-sms',
    'send-bulk-email',
    'manage-email-templates',
    'view-communication-logs',
    
    // ========== REPORTS & ANALYTICS ==========
    'view-reports',
    'generate-reports',
    'export-reports',
    'view-sales-reports',
    'view-user-reports',
    'view-restaurant-reports',
    'view-delivery-reports',
    'view-financial-reports',
    'view-performance-analytics',
    'view-dashboard-analytics',
    
    // ========== SYSTEM SETTINGS ==========
    'manage-system-settings',
    'update-site-settings',
    'manage-tax-rates',
    'manage-commission-rates',
    'manage-currencies',
    'manage-payment-methods',
    'view-system-logs',
    'manage-backup',
    'view-activity-logs',
    
    // ========== SUPPORT & TICKETING ==========
    'create-support-ticket',
    'view-support-tickets',
    'respond-to-ticket',
    'close-ticket',
    'escalate-ticket',
    'view-customer-support',
    
    // ========== LOYALTY & REWARDS ==========
    'manage-loyalty-program',
    'create-reward',
    'edit-reward',
    'delete-reward',
    'view-loyalty-points',
    'redeem-rewards',
    'manage-referral-program',
    
    // ========== CART & WISHLIST ==========
    'add-to-cart',
    'update-cart',
    'clear-cart',
    'view-cart',
    'add-to-favorites',
    'remove-from-favorites',
    'view-favorites',
    'create-wishlist',
    'manage-wishlist',
    
    // ========== ADDRESS MANAGEMENT ==========
    'add-address',
    'edit-address',
    'delete-address',
    'set-default-address',
    'view-addresses',
    
    // ========== REAL-TIME FEATURES ==========
    'view-live-orders',
    'track-live-delivery',
    'send-instant-notification',
    'view-online-users',
    
    // ========== API & INTEGRATION ==========
    'manage-api-keys',
    'create-api-key',
    'revoke-api-key',
    'view-api-usage',
    'manage-webhooks',
    'manage-third-party-integrations',
    
    // ========== MULTI-LANGUAGE & LOCALIZATION ==========
    'manage-languages',
    'translate-content',
    'switch-language',
    
    // ========== IMAGE & MEDIA MANAGEMENT ==========
    'upload-images',
    'delete-images',
    'manage-gallery',
    'crop-images',
    'optimize-images',
    
    // ========== SUBSCRIPTION & PLANS ==========
    'manage-subscription-plans',
    'upgrade-plan',
    'downgrade-plan',
    'cancel-subscription',
    'view-billing-history',
    'manage-invoices',
    
    // ========== IMPORT/EXPORT ==========
    'import-data',
    'export-data',
    'bulk-upload',
    'download-templates',
    
    // ========== SECURITY & COMPLIANCE ==========
    'two-factor-authentication',
    'view-security-logs',
    'manage-ip-restrictions',
    'gdpr-compliance',
    'data-export-request',
    'account-deletion-request',
    
    // ========== DASHBOARD & OVERVIEW ==========
    'view-admin-dashboard',
    'view-restaurant-dashboard',
    'view-driver-dashboard',
    'view-customer-dashboard',
    'view-sales-overview',
    'view-performance-metrics',
];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['descriptions' => ucfirst(str_replace('-', ' ', $permission))]
            );
        }
    }
}
