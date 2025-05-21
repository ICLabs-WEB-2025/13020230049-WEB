<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\ProfileController;

Route::get('/get-token', function () {
    return csrf_token();
});

Route::get('/', [UserController::class, 'loginForm'])->name('login');
Route::get('/register', [UserController::class, 'registerForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// autentikasi
Route::middleware(['auth'])->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //transaksis
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::resource('savings-goals', SavingsGoalController::class);
    
    // TAMBAHKAN ROUTE INI UNTUK FITUR TAMBAH DANA:
    Route::get('/savings-goals', [SavingsGoalController::class, 'index'])->name('savings-goals.index');
    Route::post('/savings-goals/{savings_goal}/add-funds', [SavingsGoalController::class, 'addFunds'])->name('savings-goals.add-funds');
    Route::post('/savings-goals', [SavingsGoalController::class, 'store'])->name('savings-goals.store');
    Route::get('/savings-goals/{savings_goal}', [SavingsGoalController::class, 'show'])->name('savings-goals.show'); 
    Route::get('/savings-goals/{savings_goal}/edit', [SavingsGoalController::class, 'edit'])->name('savings-goals.edit');
    Route::put('/savings-goals/{savings_goal}', [SavingsGoalController::class, 'update'])->name('savings-goals.update');
    Route::delete('/savings-goals/{savings_goal}', [SavingsGoalController::class, 'destroy'])->name('savings-goals.destroy');
    
    // show profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::delete('/profile', [ProfileController::class, 'destroyAccount'])->name('profile.destroyAccount');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
