<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FertilizerController as AdminFertilizerController;
use App\Http\Controllers\Admin\QuotaController as AdminQuotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Petani\DashboardController as PetaniDashboardController;
use App\Http\Controllers\Petani\QuotaController as PetaniQuotaController;
use App\Http\Controllers\Petani\FertilizerController as PetaniFertilizerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\PurchaseRequestController as AdminPurchaseRequestController;
use App\Http\Controllers\Petani\PurchaseRequestController as PetaniPurchaseRequestController;

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

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('fertilizers', AdminFertilizerController::class);

    Route::resource('quotas', AdminQuotaController::class);

    Route::get('quotas/{userId}', [AdminQuotaController::class, 'show'])->name('quotas.show');

    Route::get('purchase-requests', [AdminPurchaseRequestController::class, 'index'])->name('purchase-requests.index');

    Route::get('purchase-requests/{purchaseRequest}', [AdminPurchaseRequestController::class, 'show'])->name('purchase-requests.show');

    Route::post('purchase-requests/{purchaseRequest}/approve', [AdminPurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');

    Route::post('purchase-requests/{purchaseRequest}/reject', [AdminPurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');

    Route::post('purchase-requests/{purchaseRequest}', [AdminPurchaseRequestController::class, 'update'])->name('purchase-requests.update');
});

// Petani Routes
Route::middleware(['auth', 'role:petani'])->group(function () {
    Route::get('/dashboard', function () {
        return view('petani.dashboard');
    })->name('dashboard');

    Route::get('/quotas', [PetaniQuotaController::class, 'index'])->name('quotas.index');
    
    Route::get('/fertilizers', [PetaniFertilizerController::class, 'index'])->name('petani.fertilizers.index');

    Route::resource('purchase-requests', PetaniPurchaseRequestController::class)
        ->except(['edit', 'update', 'destroy'])
        ->names('petani.purchase-requests');

    Route::post('purchase-requests/{purchaseRequest}/cancel', [PetaniPurchaseRequestController::class, 'cancel'])
        ->name('petani.purchase-requests.cancel');
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