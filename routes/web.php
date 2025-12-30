<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/list', [AdminController::class, 'list'])->name('admin.list');
    Route::get('/admin/pending', [AdminController::class, 'pending'])->name('admin.pending');
    Route::post('/admin/approve/{user}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::get('/admin/create-user', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store-user', [AdminController::class, 'store'])->name('admin.store');
    Route::post('/admin/revoke/{user}', [AdminController::class, 'deleteUser'])->name('admin.revoke');
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

    Route::post('/subscribe', function (Request $request) {
        Log::info('subscriber user:' , [$request->user()]);
        $request->user()->updatePushSubscription($request->get('endpoint'), $request->get('keys')['p256dh'], $request->get('keys')['auth'], 'aesgcm');
        return response()->json(['success' => true]);
    });



//reports

    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    Route::get('/reports/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
    Route::get('/reports/{report}/decline', [ReportController::class, 'decline'])->name('reports.decline');
    Route::post('/reports/{report}/approve', [ReportController::class, 'approveStore'])->name('reports.approve.store');
    Route::post('/reports/{report}/decline', [ReportController::class, 'declineStore'])->name('reports.decline.store');
    
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
    

    // profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');
});

// Public Routes (login/register)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Broadcast::routes();