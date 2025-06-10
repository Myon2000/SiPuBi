<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\FertilizerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuotaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Petani\DashboardController as PetaniDashboardController;
use App\Http\Controllers\Petani\QuotaController as PetaniQuotaController;

// Landing page with role-based redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('fertilizers', FertilizerController::class);

    Route::resource('quotas', QuotaController::class);
    Route::get('quotas/{userId}', [QuotaController::class, 'show'])->name('quotas.show');
});

// Petani Routes
Route::middleware(['auth', 'role:petani'])->group(function () {
    Route::get('/dashboard', function () {
        return view('petani.dashboard');
    })->name('dashboard');

    Route::get('/quotas', [PetaniQuotaController::class, 'index'])->name('quotas.index');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

require __DIR__.'/auth.php';