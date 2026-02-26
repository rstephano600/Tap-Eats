<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomesupportController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Supplier\SupplierUserController;

// The new, cleaner route definition
Route::get('/', [HomeController::class, 'homepage'])->name('home');
Route::get('/searchsupplierlocation', [HomeController::class, 'searchsupplierlocation'])->name('searchsupplierlocation');
Route::get('/restaurantspublic', [HomeController::class, 'restaurantspublic'])->name('restaurantspublic');
Route::get('/restaurantsshow{id}', [HomeController::class, 'restaurantsshow'])->name('restaurantsshow');
Route::get('/cartadditem', [HomeController::class, 'cartadditem'])->name('cartadditem');

// Checkout Routes
Route::post('/cart/sync', [HomeController::class, 'syncCart'])->name('cart.sync');
Route::get('/checkout', [HomeController::class, 'checkoutindex'])->name('checkoutindex');
Route::post('/checkoutprocess', [HomeController::class, 'checkoutprocess'])->name('checkoutprocess');
Route::get('/order-confirmation/{orderNumber}', [HomeController::class, 'orderconfirmation'])->name('orderconfirmation');

// Order routes
Route::get('/ordersindex', [HomeController::class, 'ordersindex'])->name('ordersindex');
Route::get('/orders/track/{orderNumber}', [HomeController::class, 'orderstrack'])->name('orderstrack');

// Customer Support routes
Route::get('/customersupport', [HomeController::class, 'customersupport'])->name('customersupport');
Route::post('/customersupport/ticket', [HomeController::class, 'submitsupportticket'])->name('submitsupportticket');
Route::get('/customersupport/faq', [HomeController::class, 'faq'])->name('supportfaq');

// Daily Meals routes
Route::get('/daily-meals', [HomeController::class, 'dailymenuitems'])->name('dailymenuitems');
Route::post('/daily-meals/add-to-cart', [HomeController::class, 'addDailyMealToCart'])->name('dailymeals.addtocart');

// HOME PAGES ROUTES
Route::get('/aboutus', [HomesupportController::class, 'aboutus'])->name('aboutus');
Route::get('/contactus', [HomesupportController::class, 'contactus'])->name('contactus');

Route::get('/index/restaurants', [AuthenticationController::class, 'public'])->name('restaurants.public');
Route::get('/catering', [CateringController::class, 'index'])->name('catering');

Route::get('/contact', [AuthenticationController::class, 'index'])->name('contact');
Route::get('/search', [AuthenticationController::class, 'index'])->name('search');



// Auth Routes
Route::get('/login',    [AuthenticationController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login',   [AuthenticationController::class, 'login'])->name('login');  // <-- POST route
Route::post('/logout',  [AuthenticationController::class, 'logout'])->name('logout');

Route::get('/register',  [AuthenticationController::class, 'showRegisterForm'])->name('showRegisterForm');
Route::post('/register', [AuthenticationController::class, 'register'])->name('register');

// SERVICE TYPES
Route::get('/Servicetypes', [ServiceTypeController::class, 'Servicetypes'])->name('Servicetypes');
Route::get('/createServicetypes', [ServiceTypeController::class, 'createServicetypes'])->name('createServicetypes');
Route::post('/storeServicetypes', [ServiceTypeController::class, 'storeServicetypes'])->name('storeServicetypes');
Route::get('/editServicetypes/{serviceType}', [ServiceTypeController::class, 'editServicetypes'])->name('editServicetypes');
Route::get('/updateServicetypes', [ServiceTypeController::class, 'updateServicetypes'])->name('updateServicetypes');
Route::get('showtServicetypes', [ServiceTypeController::class, 'showServicetypes'])->name('showServicetypes');
Route::get('destroytServicetypes/{serviceType}', [ServiceTypeController::class, 'destroyServicetypes'])->name('destroyServicetypes');


// SUPPLIER INFORMATIONS
Route::middleware(['auth'])->group(function () {
    Route::get('/suppliersinformations', [SupplierController::class, 'suppliersinformations'])->name('suppliersinformations');
    Route::get('/createsuppliersinformations', [SupplierController::class, 'createsuppliersinformations'])->name('createsuppliersinformations');
    Route::post('/storesuppliersinformations', [SupplierController::class, 'storesuppliersinformations'])->name('storesuppliersinformations');
    Route::get('/showsuppliersinformations/{id}', [SupplierController::class, 'showsuppliersinformations'])->name('showsuppliersinformations');
    Route::post('/suppliers/{id}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('supplier.toggle-status');
    Route::get('/editsuppliersinformations/{id}', [SupplierController::class, 'editsuppliersinformations'])->name('editsuppliersinformations');
    Route::post('/updatesuppliersinformations/{id}', [SupplierController::class, 'updatesuppliersinformations'])->name('updatesuppliersinformations');
    Route::delete('/destroysuppliersinformations/{id}', [SupplierController::class, 'destroysuppliersinformations'])->name('destroysuppliersinformations');
    
    Route::resource('supplier-users', SupplierController::class);
    Route::get('/supplieruserinfo', [SupplierController::class, 'supplieruserinfo'])->name('supplieruserinfo');
    Route::get('/createsuppuserinfo', [SupplierController::class, 'createsuppuserinfo'])->name('createsuppuserinfo');
    Route::post('/storesuppuserinfo', [SupplierController::class, 'storesuppuserinfo'])->name('storesuppuserinfo');
    Route::get('/showsuppuserinfo/{id}', [SupplierController::class, 'showsuppuserinfo'])->name('showsuppuserinfo');
    Route::get('/editsuppuserinfo/{id}', [SupplierController::class, 'editsuppuserinfo'])->name('editsuppuserinfo');
    Route::post('/updatesuppuserinfo', [SupplierController::class, 'updatesuppuserinfo'])->name('updatesuppuserinfo');
    Route::get('/destroysuppuserinfo/{id}', [SupplierController::class, 'destroysuppuserinfo'])->name('destroysuppuserinfo');



    Route::resource('roles', RoleController::class)->middleware('permission:manage-roles');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // SUPER ADMIN
    Route::get('/manageSuppliers', [SuperAdminController::class, 'manageSuppliers'])->name('manageSuppliers');

});


use App\Http\Controllers\Admin\UserManagementController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Roles Management
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        
        // Permissions Management
        Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store', 'destroy']);
    });
    
    // User Management (accessible by super_admin and admin)
    Route::middleware(['role_or_permission:super_admin|manage_permissions'])->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::post('users/{user}/roles', [UserManagementController::class, 'updateRoles'])->name('users.roles.update');
        Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    });
});
// Admin routes with supplier access middleware
Route::middleware(['auth', 'supplier.access'])->prefix('admin')->name('admin.')->group(function () {
    
    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index')
        ->middleware('permission:view_staff');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create')
        ->middleware('permission:create_staff');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store')
        ->middleware('permission:create_staff');
    Route::put('/staff/{user}/role', [StaffController::class, 'updateRole'])->name('staff.update-role')
        ->middleware('permission:assign_roles');
    Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy')
        ->middleware('permission:delete_staff');
    Route::post('/staff/{user}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status')
        ->middleware('permission:edit_staff');
});


use App\Http\Controllers\Role\RoleUserController;
use App\Http\Controllers\Role\PermissionRoleController;

Route::middleware(['auth'])->group(function () {
    Route::resource('permissions', PermissionController::class);
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('role-users', RoleUserController::class);
    Route::resource('permission-roles', PermissionRoleController::class);
});

use App\Http\Controllers\Users\UserController;

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class)->middleware('permission:manage-roles');
});


use App\Http\Controllers\Role\PermissionUserController;
use App\Http\Controllers\Role\UserPermissionController;


// Permission-User Assignment Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::apiResource('permission-users', PermissionUserController::class);
    
    // Additional routes
    Route::post('/permission-users/bulk-assign', [PermissionUserController::class, 'bulkAssign']);
    Route::post('/permission-users/{id}/restore', [PermissionUserController::class, 'restore']);
    Route::get('/users/{id}/permissions', [PermissionUserController::class, 'getUserPermissions']);
    Route::get('/permissions/{id}/users', [PermissionUserController::class, 'getPermissionUsers']);
});

use App\Http\Controllers\Users\GuestSessionController;
use App\Http\Controllers\Users\CustomerProfileController;
use App\Http\Controllers\Users\UserCustomerProfileController;
use App\Http\Controllers\Users\CustomerAddressController;
use App\Http\Controllers\Supplier\SupplierLocationController;
Route::resource('guest-sessions', GuestSessionController::class);
Route::post('/guest-session/update', [GuestSessionController::class, 'update']);
Route::resource('customer-profiles', CustomerProfileController::class);


Route::middleware(['auth'])->group(function () {
    Route::resource('user-permissions', UserPermissionController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserCustomerProfileController::class, 'show'])
        ->name('customer.profile');

    Route::post('/profile', [UserCustomerProfileController::class, 'store'])
        ->name('customer.profile.store');

    Route::put('/profile', [UserCustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});

Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('addresses', [CustomerAddressController::class, 'index'])
        ->name('customer.addresses.index');

    Route::get('addresses/create', [CustomerAddressController::class, 'create'])
        ->name('customer.addresses.create');

    Route::post('addresses', [CustomerAddressController::class, 'store'])
        ->name('customer.addresses.store');

    Route::get('addresses/{address}/edit', [CustomerAddressController::class, 'edit'])
        ->name('customer.addresses.edit');

    Route::put('addresses/{address}', [CustomerAddressController::class, 'update'])
        ->name('customer.addresses.update');

    Route::delete('addresses/{address}', [CustomerAddressController::class, 'destroy'])
        ->name('customer.addresses.destroy');
});




Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('busines-types', \App\Http\Controllers\Admin\BusinessTypeController::class);
});
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('suppliers', \App\Http\Controllers\Supplier\SupplierInformationController::class);
});

Route::middleware(['auth'])->group(function () {
        Route::get('/supplierlocations', [SupplierLocationController::class, 'supplierlocations'])->name('supplierlocations');
        Route::get('/createsupplierlocations', [SupplierLocationController::class, 'createsupplierlocations'])->name('createsupplierlocations');
        Route::post('/storesupplierlocations', [SupplierLocationController::class, 'storesupplierlocations'])->name('storesupplierlocations');
        Route::get('/showsupplierlocations/{id}', [SupplierLocationController::class, 'showsupplierlocations'])->name('showsupplierlocations');
        Route::get('/supplierlocations{id}/edit', [SupplierLocationController::class, 'editsupplierlocations'])->name('editsupplierlocations');
        Route::put('/updatesupplierlocations{id}', [SupplierLocationController::class, 'updatesupplierlocations'])->name('updatesupplierlocations');
        Route::delete('/destroysupplierlocations{id}', [SupplierLocationController::class, 'destroysupplierlocations'])->name('destroysupplierlocations');
        
        // Additional actions
        Route::post('/{id}/set-primary', [SupplierLocationController::class, 'setPrimary'])->name('setprimarysupplierlocations');
        Route::post('/{id}/toggle-active', [SupplierLocationController::class, 'toggleActive'])->name('supplier.locations.toggle-active');
        Route::get('/primary/get', [SupplierLocationController::class, 'getPrimary'])->name('get-primary');
        Route::post('/validate-address', [SupplierLocationController::class, 'validateAddress'])->name('validate-address');

});


use App\Http\Controllers\Supplier\SupplierFinancialInfoController;





    Route::middleware(['auth'])
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function () {
        Route::resource('financial', SupplierFinancialInfoController::class);
        Route::post('financial/{id}/primary', [SupplierFinancialInfoController::class, 'setPrimary']);
        Route::post('financial/{id}/toggle', [SupplierFinancialInfoController::class, 'toggleActive']);
    });


use App\Http\Controllers\Menu\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Menu\MenuItemVariantController;
use App\Http\Controllers\Menu\MenuItemAddonController;
Route::resource('menu-categories', MenuCategoryController::class);
Route::post('menu-categories/{id}/restore', [MenuCategoryController::class, 'restore'])->name('menu-categories.restore');
Route::delete('menu-categories/{id}/force-delete', [MenuCategoryController::class, 'forceDelete'])->name('menu-categories.force-delete');

Route::resource('menu-items', MenuItemController::class);
Route::get('/menuItemsInformations', [MenuController::class, 'menuItemsInformations'])->name('menuItemsInformations');
Route::get('/createmenuItemsInformations', [MenuController::class, 'createmenuItemsInformations'])->name('createmenuItemsInformations');
Route::post('/storemenuItemsInformations', [MenuController::class, 'storemenuItemsInformations'])->name('storemenuItemsInformations');
Route::get('/editmenuItemsInformations{id}', [MenuController::class, 'editmenuItemsInformations'])->name('editmenuItemsInformations');
Route::post('/updatemenuItemsInformations{id}', [MenuController::class, 'updatemenuItemsInformations'])->name('updatemenuItemsInformations');
Route::get('/showmenuItemsInformations{id}', [MenuController::class, 'showmenuItemsInformations'])->name('showmenuItemsInformations');
Route::get('/destroymenuItemsInformations{id}', [MenuController::class, 'destroymenuItemsInformations'])->name('destroymenuItemsInformations');

Route::post('menu-items/{id}/restore', [MenuItemController::class, 'restore'])->name('menu-items.restore');
Route::delete('menu-items/{id}/force-delete', [MenuItemController::class, 'force-delete'])->name('menu-items.force-delete');

Route::resource('menu-item-variants', MenuItemVariantController::class);
Route::post('menu-item-variants/{id}/restore', [MenuItemVariantController::class, 'restore'])->name('menu-item-variants.restore');
Route::delete('menu-item-variants/{id}/force-delete', [MenuItemVariantController::class, 'forceDelete'])->name('menu-item-variants.force-delete');

Route::resource('menu-item-addons', MenuItemAddonController::class);
Route::post('menu-item-addons/{id}/restore', [MenuItemAddonController::class, 'restore'])->name('menu-item-addons.restore');
Route::delete('menu-item-addons/{id}/force-delete', [MenuItemAddonController::class, 'force-delete'])->name('menu-item-addons.force-delete');


use App\Http\Controllers\OrderController;
Route::middleware(['auth'])->group(function () {
    // Order Management Routes

    Route::get('/ordersinformations', [OrderController::class, 'ordersinformations'])->name('ordersinformations');
    Route::get('/createordersinformations', [OrderController::class, 'createordersinformations'])->name('createordersinformations');
    Route::post('/storeordersinformations', [OrderController::class, 'storeordersinformations'])->name('storeordersinformations');
    Route::get('/showordersinformations/{id}', [OrderController::class, 'showordersinformations'])->name('showordersinformations');
    Route::get('/editordersinformations/{id}', [OrderController::class, 'editordersinformations'])->name('editordersinformations');
    Route::post('/updateordersinformations', [OrderController::class, 'updateordersinformations'])->name('updateordersinformations');
    Route::get('/destroyordersinformations/{id}', [OrderController::class, 'destroyordersinformations'])->name('destroyordersinformations');

    // CHANGE STATUS
    Route::patch('/updateStatusConfirm/{id}', [OrderController::class, 'updateStatusConfirm'])->name('updateStatusConfirm');
    Route::patch('/updateStatusPrepare/{id}', [OrderController::class, 'updateStatusPrepare'])->name('updateStatusPrepare');
    Route::patch('/updateStatusReady/{id}', [OrderController::class, 'updateStatusReady'])->name('updateStatusReady');
    Route::patch('/updateStatusDelivered/{id}', [OrderController::class, 'updateStatusDelivered'])->name('updateStatusDelivered');
    Route::patch('/updateStatusComplete/{id}', [OrderController::class, 'updateStatusComplete'])->name('updateStatusComplete');
    Route::patch('/markOrderAsPaid/{id}', [OrderController::class, 'markOrderAsPaid'])->name('markOrderAsPaid');

    Route::get('/completedOrders', [OrderController::class, 'completedOrders'])->name('completedOrders');
    Route::get('/cancelledOrders', [OrderController::class, 'cancelledOrders'])->name('cancelledOrders');
    Route::get('/ordersSummary', [OrderController::class, 'ordersSummary'])->name('ordersSummary');
    
    // ORDERS

    // Additional Order Actions
    Route::prefix('orders')->name('orders.')->group(function () {
        // Status Management
        Route::patch('{order}/update-status', [OrderController::class, 'updateStatus'])
            ->name('update-status');
        
        // Payment Status
        Route::patch('{order}/update-payment', [OrderController::class, 'updatePaymentStatus'])
            ->name('update-payment');
        
        // Order Statistics
        Route::get('statistics/dashboard', [OrderController::class, 'statistics'])
            ->name('statistics');
    });
});