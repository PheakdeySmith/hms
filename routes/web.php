<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckInOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rooms
Route::resource('rooms', RoomController::class);
Route::get('/rooms/{room}/maintenance', [RoomController::class, 'toggleMaintenance'])->name('rooms.maintenance');

// Room Types
Route::resource('room-types', RoomTypeController::class);

// Guests
Route::resource('guests', GuestController::class);

// Bookings
Route::resource('bookings', BookingController::class);
Route::get('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
Route::post('/bookings/{booking}/check-in', [BookingController::class, 'processCheckIn'])->name('bookings.process-check-in');
Route::get('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.check-out');
Route::post('/bookings/{booking}/check-out', [BookingController::class, 'processCheckOut'])->name('bookings.process-check-out');
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('/bookings-export', [BookingController::class, 'export'])->name('bookings.export');

// Check In/Out
Route::get('/check-in-out', [App\Http\Controllers\CheckInOutController::class, 'index'])->name('check-in-out.index');
Route::get('/check-in-out/today', [App\Http\Controllers\CheckInOutController::class, 'today'])->name('check-in-out.today');

// Payments
Route::resource('payments', PaymentController::class);
Route::post('/payments/{booking}/add', [PaymentController::class, 'addPayment'])->name('payments.add');
Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
Route::get('/payments-export', [PaymentController::class, 'export'])->name('payments.export');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

// Roles & Permissions
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

// Settings
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

// Authentication
Auth::routes(['register' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
