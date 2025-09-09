<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AppointmentPaymentController;
use App\Http\Controllers\AppointmentController as PublicAppointmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\ScheduleExceptionController as AdminScheduleExceptionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Booking flow
Route::get('/turnos', [BookingController::class, 'index'])->name('appointments.select');
Route::get('/turnos/slots', [BookingController::class, 'slots'])->middleware('throttle:60,1')->name('appointments.slots');
Route::post('/turnos', [BookingController::class, 'store'])->name('appointments.store');
Route::post('/turnos/{appointment}/pago', [AppointmentPaymentController::class, 'createPreference'])
    ->name('appointments.pay');
Route::get('/turnos/{appointment}/confirmacion', [BookingController::class, 'confirm'])
    ->name('appointments.confirmation');
Route::get('/turnos/{appointment}/ics', [PublicAppointmentController::class, 'ics'])
    ->name('appointments.ics');

// Shop
Route::get('/tienda', [ProductController::class, 'index'])->name('shop.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('shop.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/confirmacion', [CheckoutController::class, 'confirm'])->name('checkout.confirmation');

// Webhook
Route::post('/webhook/mercadopago', [WebhookController::class, 'mercadopago'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('webhook.mercadopago');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin
    Route::middleware('can:admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'index'])->name('admin.index');
        Route::resource('/admin/services', AdminServiceController::class);
        Route::resource('/admin/staff', AdminStaffController::class);
        Route::resource('/admin/schedules', AdminScheduleController::class);
        Route::resource('/admin/exceptions', AdminScheduleExceptionController::class);
        Route::resource('/admin/products', AdminProductController::class);
        Route::get('/admin/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::patch('/admin/appointments/{appointment}/confirm', [AdminAppointmentController::class, 'confirm'])->name('admin.appointments.confirm');
        Route::patch('/admin/appointments/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('admin.appointments.cancel');
        Route::patch('/admin/appointments/{appointment}/done', [AdminAppointmentController::class, 'done'])->name('admin.appointments.done');

        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::patch('/admin/orders/{order}/deliver', [AdminOrderController::class, 'deliver'])->name('admin.orders.deliver');

        Route::get('/admin/settings', [AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::post('/admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    });
});

require __DIR__.'/auth.php';
