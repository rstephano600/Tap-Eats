<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomesupportController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SearchController;


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


Route::get('/index/restaurants', [AuthenticationController::class, 'public'])->name('restaurants.public');
Route::get('/catering', [AuthenticationController::class, 'index'])->name('catering');
Route::get('/about', [AuthenticationController::class, 'about'])->name('about');
Route::get('/contact', [AuthenticationController::class, 'index'])->name('contact');
Route::get('/search', [AuthenticationController::class, 'index'])->name('search');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
});



Route::get('/dashboard', function () {
    return view('in.dashboard.home');
})->name('dashboard');;




// Authentication
Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

// Registration
Route::get('register', [AuthenticationController::class, 'showRegisterForm'])->name('showRegisterForm');
Route::post('register', [AuthenticationController::class, 'register']);

use App\Http\Controllers\Role\RoleController;
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class)->middleware('permission:manage-roles');
});

use App\Http\Controllers\Role\PermissionController;
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
    Route::resource('service-types', \App\Http\Controllers\Admin\ServiceTypeController::class);
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

use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierFinancialInfoController;


Route::middleware(['auth'])
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });


    Route::middleware(['auth'])
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function () {
        Route::resource('financial', SupplierFinancialInfoController::class);
        Route::post('financial/{id}/primary', [SupplierFinancialInfoController::class, 'setPrimary']);
        Route::post('financial/{id}/toggle', [SupplierFinancialInfoController::class, 'toggleActive']);
    });


use App\Http\Controllers\Menu\MenuCategoryController;
use App\Http\Controllers\Menu\MenuItemController;
use App\Http\Controllers\Menu\MenuItemVariantController;
use App\Http\Controllers\Menu\MenuItemAddonController;
Route::resource('menu-categories', MenuCategoryController::class);
Route::post('menu-categories/{id}/restore', [MenuCategoryController::class, 'restore'])->name('menu-categories.restore');
Route::delete('menu-categories/{id}/force-delete', [MenuCategoryController::class, 'forceDelete'])->name('menu-categories.force-delete');

Route::resource('menu-items', MenuItemController::class);
Route::post('menu-items/{id}/restore', [MenuItemController::class, 'restore'])->name('menu-items.restore');
Route::delete('menu-items/{id}/force-delete', [MenuItemController::class, 'force-delete'])->name('menu-items.force-delete');

Route::resource('menu-item-variants', MenuItemVariantController::class);
Route::post('menu-item-variants/{id}/restore', [MenuItemVariantController::class, 'restore'])->name('menu-item-variants.restore');
Route::delete('menu-item-variants/{id}/force-delete', [MenuItemVariantController::class, 'forceDelete'])->name('menu-item-variants.force-delete');

Route::resource('menu-item-addons', MenuItemAddonController::class);
Route::post('menu-item-addons/{id}/restore', [MenuItemAddonController::class, 'restore'])->name('menu-item-addons.restore');
Route::delete('menu-item-addons/{id}/force-delete', [MenuItemAddonController::class, 'force-delete'])->name('menu-item-addons.force-delete');


use App\Http\Controllers\Order\OrderController;
Route::middleware(['auth'])->group(function () {
    // Order Management Routes
    Route::resource('orders', OrderController::class);
    
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