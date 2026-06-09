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
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/servicios', [PageController::class, 'services'])->name('services');
Route::get('/nosotros', [PageController::class, 'about'])->name('about');
Route::get('/contacto', [PageController::class, 'contact'])->name('contact');
Route::post('/contacto', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');

// Auth routes
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

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/verify-email', App\Http\Controllers\Auth\EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', 'App\Http\Controllers\Auth\VerifyEmailController@__invoke')->name('verification.verify');
    Route::post('/email/verification-notification', 'App\Http\Controllers\Auth\EmailVerificationNotificationController@store')->name('verification.send');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', 'App\Http\Controllers\Auth\AuthenticatedSessionController@destroy')->name('logout');

    // Root dashboard: redirect by role
    Route::get('/dashboard', [DashboardController::class, 'redirectByRole'])->name('dashboard');

    // Role-specific dashboards (render actual page)
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/tecnico/dashboard', [DashboardController::class, 'technicianDashboard'])->name('technician.dashboard');
    Route::get('/mi-cuenta/dashboard', [DashboardController::class, 'clientDashboard'])->name('client.dashboard');

    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notificaciones/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notificaciones/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notificaciones/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Client routes
    Route::middleware('role:client')->prefix('mi-cuenta')->name('client.')->group(function () {
        Route::get('/vehiculos', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehiculos/crear', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehiculos', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
        Route::get('/vehiculos/{vehicle}/editar', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehiculos/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::get('/ordenes', [ServiceOrderController::class, 'clientOrders'])->name('orders.index');
        Route::get('/ordenes/{service_order}', [ServiceOrderController::class, 'show'])->name('orders.show');
        Route::get('/cotizaciones', [QuotationController::class, 'clientQuotations'])->name('quotations.index');
        Route::get('/cotizaciones/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
        Route::post('/cotizaciones/{quotation}/aprobar', [QuotationController::class, 'clientApprove'])->name('quotations.approve');
        Route::post('/cotizaciones/{quotation}/rechazar', [QuotationController::class, 'clientReject'])->name('quotations.reject');
        Route::get('/ventas', [SaleController::class, 'clientSales'])->name('sales.index');
    });

    // Technician routes
    Route::middleware('role:technician')->prefix('tecnico')->name('technician.')->group(function () {
        Route::get('/ordenes', [ServiceOrderController::class, 'index'])->name('orders.index');
        Route::get('/ordenes/{service_order}', [ServiceOrderController::class, 'show'])->name('orders.show');
        Route::patch('/ordenes/{service_order}/status', [ServiceOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/ordenes/{service_order}/reports', [ServiceReportController::class, 'store'])->name('orders.reports.store');
        Route::get('/catalogo', [ProductController::class, 'catalog'])->name('products.catalog');
        Route::get('/reportes', [ServiceReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes/{service_report}', [ServiceReportController::class, 'show'])->name('reports.show');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios', 'App\Http\Controllers\UserController@index')->name('usuarios.index');
        Route::post('/usuarios', 'App\Http\Controllers\UserController@store')->name('usuarios.store');
        Route::get('/usuarios/{user}/editar', 'App\Http\Controllers\UserController@edit')->name('usuarios.edit');
        Route::put('/usuarios/{user}', 'App\Http\Controllers\UserController@update')->name('usuarios.update');
        Route::put('/usuarios/{user}/toggle-active', 'App\Http\Controllers\UserController@toggleActive')->name('usuarios.toggle-active');
        Route::delete('/usuarios/{user}', 'App\Http\Controllers\UserController@destroy')->name('usuarios.destroy');

        // AJAX lookup endpoints for order/quotation creation forms
        Route::get('/clientes/buscar', 'App\Http\Controllers\UserController@searchClients')->name('clientes.buscar');
        Route::get('/clientes/{clientId}/vehiculos', [VehicleController::class, 'getClientVehicles'])->name('clientes.vehiculos');
        Route::get('/clientes/{clientId}/ordenes', [ServiceOrderController::class, 'getClientOrders'])->name('clientes.ordenes');

        Route::resource('vehiculos', VehicleController::class)
            ->except(['show'])
            ->parameters(['vehiculos' => 'vehicle']);
        Route::get('/vehiculos/{vehicle}', [VehicleController::class, 'show'])->name('vehiculos.show');
        Route::post('/vehiculos/cliente/{client}', [VehicleController::class, 'addVehicleForClient'])->name('vehiculos.add-for-client');
        Route::resource('ordenes', ServiceOrderController::class)->parameters(['ordenes' => 'service_order']);
        Route::patch('/ordenes/{service_order}/status', [ServiceOrderController::class, 'updateStatus'])->name('ordenes.update-status');
        Route::post('/ordenes/{service_order}/reports', [ServiceReportController::class, 'store'])->name('ordenes.reports.store');
        Route::resource('productos', ProductController::class)->parameters(['productos' => 'product']);
        Route::resource('cotizaciones', QuotationController::class)->parameters(['cotizaciones' => 'quotation']);
        Route::patch('/cotizaciones/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('cotizaciones.update-status');
        Route::get('/cotizaciones/{quotation}/pdf', [QuotationController::class, 'generatePdf'])->name('cotizaciones.pdf');
        Route::post('/cotizaciones/{quotation}/convertir-venta', [QuotationController::class, 'convertToSale'])->name('cotizaciones.convert-to-sale');
        Route::resource('ventas', SaleController::class)->parameters(['ventas' => 'sale']);
        Route::post('/ventas/{sale}/pago', [SaleController::class, 'registerPayment'])->name('ventas.register-payment');
        Route::post('/ventas/{sale}/cancelar', [SaleController::class, 'cancel'])->name('ventas.cancel');
        Route::get('/reportes-servicio/crear', [ServiceReportController::class, 'create'])->name('reports.create');
        Route::get('/reportes-servicio', [ServiceReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes-servicio/{service_report}', [ServiceReportController::class, 'show'])->name('reports.show');
        Route::delete('/reportes-servicio/{service_report}', [ServiceReportController::class, 'destroy'])->name('reports.destroy');
    });
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

});
