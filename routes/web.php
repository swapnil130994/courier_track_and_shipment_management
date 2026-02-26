<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShipmentController;


Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function(){

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'webLogin'])
        ->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
// Show forgot password form
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
     ->name('password.request');

// Send reset link email
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
     ->name('password.email');

// Show reset form (after clicking email link)
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
     ->name('password.reset');

// Handle password reset submission
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
     ->name('password.update');

Route::middleware('auth')->group(function(){

    Route::post('/logout', [AuthController::class, 'webLogout'])
        ->name('logout');

    Route::get('/dashboard',[DashboardController::class,'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Shipments (Admin + Staff)
    |--------------------------------------------------------------------------
    */
    Route::resource('shipments',ShipmentController::class);
    Route::get('/add-shipment', function () {
        return view('shipments/create');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('/staff/create',[StaffController::class,'create'])->name('staff.create');
    Route::post('/staff/store',[StaffController::class,'store'])->name('staff.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCSV'])->name('reports.export');
    Route::get('/reports/invoice', [ReportController::class, 'exportInvoicePDF'])->name('reports.invoice');

});

// Route::get('/track', [ShipmentController::class, 'trackForm'])->name('track.form');
Route::get('/track', function () {
    return view('track');
});
Route::get('/track/result', [ShipmentController::class, 'trackResult'])->name('track.result');
