<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCollectionController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDriverController;

// Driver Controllers
use App\Http\Controllers\Driver\DriverDashboardController;
use App\Http\Controllers\Driver\RouteController as DriverRouteController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
})->name('home');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Public Service Pages
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{serviceType: slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/pricing', [ServiceController::class, 'pricing'])->name('pricing');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:customer'])->group(function () {
        // Collections
        Route::resource('collections', CollectionController::class);
        Route::post('/collections/{collection}/cancel', [CollectionController::class, 'cancel'])->name('collections.cancel');
        Route::post('/collections/{collection}/reschedule', [CollectionController:: class, 'reschedule'])->name('collections.reschedule');

        // Subscriptions
        Route::resource('subscriptions', SubscriptionController::class);
        Route::post('/subscriptions/{subscription}/pause', [SubscriptionController::class, 'pause'])->name('subscriptions.pause');
        Route::post('/subscriptions/{subscription}/resume', [SubscriptionController:: class, 'resume'])->name('subscriptions.resume');
        Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

        // Payments
        Route::get('/checkout/{collection}', [PaymentController:: class, 'checkout'])->name('payments.checkout');
        Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');
        Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');

        // Billing
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/invoice/{invoice}/download', [BillingController:: class, 'downloadInvoice'])->name('billing.invoice.download');

        // Ratings
        Route::post('/collections/{collection}/rate', [RatingController::class, 'store'])->name('ratings.store');

        // Complaints
        Route:: resource('complaints', ComplaintController::class)->only(['index', 'create', 'store', 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Driver Routes
    |--------------------------------------------------------------------------
    */
    Route:: middleware(['role:driver'])->prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');
        
        // Routes
        Route::get('/routes', [DriverRouteController::class, 'index'])->name('routes');
        Route::get('/routes/{route}', [DriverRouteController:: class, 'show'])->name('routes.show');
        Route::post('/routes/{route}/start', [DriverRouteController:: class, 'start'])->name('routes.start');
        Route::post('/routes/{route}/complete', [DriverRouteController:: class, 'complete'])->name('routes.complete');
        
        // Route Stops
        Route::post('/stops/{routeStop}/arrive', [DriverRouteController:: class, 'arriveAtStop'])->name('stops.arrive');
        Route::post('/stops/{routeStop}/complete', [DriverRouteController:: class, 'completeStop'])->name('stops.complete');
        Route::post('/stops/{routeStop}/skip', [DriverRouteController:: class, 'skipStop'])->name('stops.skip');
        
        // Location Update API
        Route::post('/location', [DriverRouteController::class, 'updateLocation'])->name('location.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,provider'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController:: class, 'index'])->name('dashboard');

        // Collections Management
        Route::get('/collections', [AdminCollectionController::class, 'index'])->name('collections');
        Route::get('/collections/{collection}', [AdminCollectionController::class, 'show'])->name('collections.show');
        Route::post('/collections/{collection}/assign', [AdminCollectionController::class, 'assignDriver'])->name('collections.assign');
        Route::patch('/collections/{collection}/status', [AdminCollectionController::class, 'updateStatus'])->name('collections.status');

        // Customers Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
        Route::get('/customers/{user}', [AdminCustomerController:: class, 'show'])->name('customers.show');
        Route::patch('/customers/{user}/status', [AdminCustomerController:: class, 'updateStatus'])->name('customers.status');

        // Drivers Management
        Route:: get('/drivers', [AdminDriverController::class, 'index'])->name('drivers.index');
        Route::get('/drivers/create', [AdminDriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers', [AdminDriverController::class, 'store'])->name('drivers.store');
        Route::get('/drivers/{driver}', [AdminDriverController::class, 'show'])->name('drivers.show');
        Route::get('/drivers/{driver}/edit', [AdminDriverController::class, 'edit'])->name('drivers.edit');
        Route::put('/drivers/{driver}', [AdminDriverController::class, 'update'])->name('drivers.update');
        Route::patch('/drivers/{driver}/status', [AdminDriverController::class, 'updateStatus'])->name('drivers.status');
        Route::delete('/drivers/{driver}', [AdminDriverController::class, 'destroy'])->name('drivers.destroy');

            // Settings (placeholder)
        Route::get('/settings', [App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';