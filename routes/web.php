<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\ServiceReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Health check endpoint for Render
Route::get('/up', function () {
    return response('OK', 200)->header('Content-Type', 'text/plain');
});


// ---------------------------------------------------------------------------
// Public pages
// ---------------------------------------------------------------------------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/servicios', [PageController::class, 'services'])->name('services');
Route::get('/nosotros', [PageController::class, 'about'])->name('about');
Route::get('/contacto', [PageController::class, 'contact'])->name('contact');
Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');

// ---------------------------------------------------------------------------
// Auth routes (Breeze-style, manual for Inertia)
// ---------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', 'App\Http\Controllers\Auth\AuthenticatedSessionController@create')->name('login');
    Route::post('/login', 'App\Http\Controllers\Auth\AuthenticatedSessionController@store');
    Route::get('/register', 'App\Http\Controllers\Auth\RegisteredUserController@create')->name('register');
    Route::post('/register', 'App\Http\Controllers\Auth\RegisteredUserController@store');
    Route::get('/forgot-password', 'App\Http\Controllers\Auth\PasswordResetLinkController@create')->name('password.request');
    Route::post('/forgot-password', 'App\Http\Controllers\Auth\PasswordResetLinkController@store')->name('password.email');
    Route::get('/reset-password/{token}', 'App\Http\Controllers\Auth\NewPasswordController@create')->name('password.reset');
    Route::post('/reset-password', 'App\Http\Controllers\Auth\NewPasswordController@store')->name('password.update');
});

// ---------------------------------------------------------------------------
// Authenticated routes
// ---------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Auth\AuthenticatedSessionController@destroy')->name('logout');

    // Dashboard routes
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/admin/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::get('/tecnico/dashboard', DashboardController::class)->name('technician.dashboard');
    Route::get('/mi-cuenta/dashboard', DashboardController::class)->name('client.dashboard');

    // Notifications
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notificaciones/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notificaciones/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notificaciones/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // -----------------------------------------------------------------------
    // Client routes
    // -----------------------------------------------------------------------
    Route::middleware('role:client')->prefix('mi-cuenta')->name('client.')->group(function () {
        // Vehicles
        Route::get('/vehiculos', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehiculos/crear', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehiculos', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
        Route::get('/vehiculos/{vehicle}/editar', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehiculos/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');

        // Service orders (client view)
        Route::get('/ordenes', [ServiceOrderController::class, 'clientOrders'])->name('orders.index');
        Route::get('/ordenes/{order}', [ServiceOrderController::class, 'show'])->name('orders.show');

        // Quotations (client view)
        Route::get('/cotizaciones', [QuotationController::class, 'clientQuotations'])->name('quotations.index');
        Route::get('/cotizaciones/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');

        // Sales (client view)
        Route::get('/ventas', [SaleController::class, 'clientSales'])->name('sales.index');
    });

    // -----------------------------------------------------------------------
    // Technician routes
    // -----------------------------------------------------------------------
    Route::middleware('role:technician')->prefix('tecnico')->name('technician.')->group(function () {
        // Service orders
        Route::get('/ordenes', [ServiceOrderController::class, 'index'])->name('orders.index');
        Route::get('/ordenes/{order}', [ServiceOrderController::class, 'show'])->name('orders.show');
        Route::patch('/ordenes/{order}/status', [ServiceOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/ordenes/{order}/reports', [ServiceReportController::class, 'store'])->name('orders.reports.store');

        // Product catalog
        Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');

        // Service reports
        Route::get('/reportes', [ServiceReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes/{report}', [ServiceReportController::class, 'show'])->name('reports.show');
    });

    // -----------------------------------------------------------------------
    // Admin routes
    // -----------------------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Users management
        Route::get('/usuarios', 'App\Http\Controllers\UserController@index')->name('users.index');
        Route::post('/usuarios', 'App\Http\Controllers\UserController@store');
        Route::put('/usuarios/{user}', 'App\Http\Controllers\UserController@update');
        Route::delete('/usuarios/{user}', 'App\Http\Controllers\UserController@destroy');

        // Vehicles
        Route::resource('vehiculos', VehicleController::class)->except(['show']);
        Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehiculos.show');
        Route::post('/vehiculos/cliente/{client}', [VehicleController::class, 'addVehicleForClient'])->name('vehiculos.add-for-client');

        // Service Orders
        Route::resource('ordenes', ServiceOrderController::class);
        Route::patch('/ordenes/{order}/status', [ServiceOrderController::class, 'updateStatus'])->name('ordenes.update-status');
        Route::post('/ordenes/{order}/reports', [ServiceReportController::class, 'store'])->name('ordenes.reports.store');

        // Products
        Route::resource('productos', ProductController::class);

        // Quotations
        Route::resource('cotizaciones', QuotationController::class);
        Route::patch('/cotizaciones/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('cotizaciones.update-status');
        Route::get('/cotizaciones/{quotation}/pdf', [QuotationController::class, 'generatePdf'])->name('cotizaciones.pdf');
        Route::post('/cotizaciones/{quotation}/convertir-venta', [QuotationController::class, 'convertToSale'])->name('cotizaciones.convert-to-sale');

        // Sales
        Route::resource('ventas', SaleController::class);
        Route::post('/ventas/{sale}/pago', [SaleController::class, 'registerPayment'])->name('ventas.register-payment');
        Route::post('/ventas/{sale}/cancelar', [SaleController::class, 'cancel'])->name('ventas.cancel');

        // Reports
        Route::get('/reportes-servicio', [ServiceReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes-servicio/{report}', [ServiceReportController::class, 'show'])->name('reports.show');
        Route::delete('/reportes-servicio/{report}', [ServiceReportController::class, 'destroy'])->name('reports.destroy');
    });
});
