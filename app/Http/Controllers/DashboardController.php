<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $userId = auth()->id(); // Pastikan pengguna sudah login

        $totalIncome = Transaction::where('user_id', $userId)
                                ->where('transaction_type', 'income')
                                ->sum('amount');

        $totalExpense = Transaction::where('user_id', $userId)
                                    ->where('transaction_type', 'expense')
                                    ->sum('amount');

        $transactions = Transaction::where('user_id', $userId)
                                    ->with('category')
                                    ->latest()
                                    ->take(5)
                                    ->get();

    $expenseCategories = ExpenseCategory::all(); // Kategori mungkin bersifat global atau per pengguna, tergantung desain Anda

    return view('main.dashboard', compact('totalIncome', 'totalExpense', 'transactions', 'expenseCategories'));
}
}
