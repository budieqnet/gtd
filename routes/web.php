<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login & Register pages
require __DIR__.'/auth.php';

// Employee routes (login with NIP)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

    Route::controller(TaskController::class)->prefix('tasks')->group(function () {
        Route::get('/', 'index')->name('tasks.index');
        Route::get('/create', 'create')->name('tasks.create');
        Route::post('/', 'store')->name('tasks.store');
        Route::patch('/{task}/status', 'updateStatus')->name('tasks.update-status');
        Route::put('/{task}', 'updateTask')->name('tasks.update');
        Route::delete('/{task}', 'destroy')->name('tasks.destroy');
        Route::get('/manager/dashboard', 'dashboard')->name('tasks.manager-dashboard');
        Route::get('/history', 'history')->name('tasks.history');
        Route::get('/{task}', 'show')->name('tasks.show');
    });

    // Profile routes (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification routes
    Route::get('/notifications/data', function () {
        $notifications = Auth::user()->notifications()->latest()->take(20)->get();
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => Auth::user()->unreadNotifications->count(),
        ]);
    })->name('notifications.data');

    Route::post('/notifications/read/{id}', function (string $id) {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read-all');

    // Messenger routes
    Route::get('/messenger', [MessengerController::class, 'index'])->name('messenger');
    Route::get('/messenger/conversations', [MessengerController::class, 'conversations'])->name('messenger.conversations');
    Route::get('/messenger/conversations/{conversation}/messages', [MessengerController::class, 'messages'])->name('messenger.messages');
    Route::post('/messenger/conversations/{conversation}/send', [MessengerController::class, 'send'])->name('messenger.send');
    Route::post('/messenger/start', [MessengerController::class, 'start'])->name('messenger.start');
    Route::get('/messenger/unread', [MessengerController::class, 'unreadCount'])->name('messenger.unread');
    Route::get('/messenger/employees', [MessengerController::class, 'employees'])->name('messenger.employees');
});

// Admin Routes (login with username)
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/employees/check-nip', [AdminController::class, 'checkNip'])->name('employees.check-nip');
    Route::put('/employees/{employee}', [AdminController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{employee}', [AdminController::class, 'destroyEmployee'])->name('employees.destroy');

    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroyAdmin'])->name('admins.destroy');
    Route::get('/admins/check-username', [AdminController::class, 'checkUsername'])->name('admins.check-username');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/performance', [AdminController::class, 'performance'])->name('performance');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/reports/performance', [AdminController::class, 'reportPerformance'])->name('reports.performance');

    Route::get('/positions', [AdminController::class, 'positions'])->name('positions');
    Route::post('/positions', [AdminController::class, 'storePosition'])->name('positions.store');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])->name('positions.destroy');
    Route::post('/positions/sort', [AdminController::class, 'sortPositions'])->name('positions.sort');

    Route::post('/logout', [\App\Http\Controllers\Admin\Auth\LogoutController::class, 'destroy'])->name('logout');
});
