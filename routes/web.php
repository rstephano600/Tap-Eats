<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthenticationController;
Route::get('/', function () {
    return view('in.dashboard.home');
})->name('home');;


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
    // Supplier Locations Routes
    Route::prefix('supplier/locations')->name('supplier.locations.')->group(function () {
        Route::get('/', [SupplierLocationController::class, 'index'])->name('index');
        Route::get('/create', [SupplierLocationController::class, 'create'])->name('create');
        Route::post('/', [SupplierLocationController::class, 'store'])->name('store');
        Route::get('/{id}', [SupplierLocationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SupplierLocationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupplierLocationController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierLocationController::class, 'destroy'])->name('destroy');
        
        // Additional actions
        Route::post('/{id}/set-primary', [SupplierLocationController::class, 'setPrimary'])->name('set-primary');
        Route::post('/{id}/toggle-active', [SupplierLocationController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/primary/get', [SupplierLocationController::class, 'getPrimary'])->name('get-primary');
        Route::post('/validate-address', [SupplierLocationController::class, 'validateAddress'])->name('validate-address');
    });
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
