<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

Route::get('/get-token', function () {
    return csrf_token();
});

Route::get('/', [UserController::class, 'loginForm'])->name('login');
Route::get('/register', [UserController::class, 'registerForm'])->name('register');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


//transaksis
Route::middleware('auth')->get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::middleware('auth')->post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::middleware('auth')->delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');


// api kategori
// Jika menggunakan route API
Route::get('/categories', function () {
    return response()->json(App\Models\ExpenseCategory::all());
});



// Dashboard
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');