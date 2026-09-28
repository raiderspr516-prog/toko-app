<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (guard: admin, prefix: /admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    require __DIR__.'/admin-auth.php';

    Route::middleware(['auth:admin', 'admin.active'])->name('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class);
        Route::resource('coupons', CouponController::class)->except(['show']);

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('payment-verification', [PaymentVerificationController::class, 'index'])->name('payment-verification.index');
        Route::post('payment-verification/{proof}/approve', [PaymentVerificationController::class, 'approve'])->name('payment-verification.approve');
        Route::post('payment-verification/{proof}/reject', [PaymentVerificationController::class, 'reject'])->name('payment-verification.reject');
        Route::get('payment-proofs/{proof}/view', [PaymentVerificationController::class, 'viewProof'])->name('payment-proofs.view');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::post('orders/{order}/shipment', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::post('orders/{order}/shipment/deliver', [ShipmentController::class, 'markDelivered'])->name('shipments.deliver');

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
        Route::get('reports/customers', [ReportController::class, 'customers'])->name('reports.customers');

        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::post('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    });
});
