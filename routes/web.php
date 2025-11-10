<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/', function () {
    return view('welcome');
});

// Protected Routes (only logged-in users)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/generate-report/pdf', [ReportController::class, 'generatePdf'])
     ->name('reports.generate.pdf');

     //notifs
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    
    // Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
    // ->name('notifications.readAll');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
// Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
// Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    //new notifs
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
Route::post('/notifications/{notification}/control', [NotificationController::class, 'markAsControlled'])->name('notifications.control');
Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markAllRead');
Route::post('/notifications/{reportId}/mark-as-controlled', [NotificationController::class, 'markAsControlled'])
    ->name('notifications.markAsControlled');




//reports

    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    Route::get('/reports/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/reports/{report}/approve', [ReportController::class, 'approveStore'])->name('reports.approve.store');

    Route::post('/reports/{report}/accept', [ReportController::class, 'accept'])->name('reports.accept');
    Route::post('/reports/{id}/accept', [ReportController::class, 'accept'])->name('reports.accept');
    Route::put('/reports/{report}/under-control', [ReportController::class, 'markUnderControl'])->name('reports.underControl');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/control', [NotificationController::class, 'markAsControlled'])
    ->name('notifications.control');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
    ->name('notifications.destroy');    
    Route::post('/notifications/{id}/mark-as-controlled', [NotificationController::class, 'markAsControlled'])->name('notifications.markAsControlled');
    
});

// Public Routes (login/register)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
