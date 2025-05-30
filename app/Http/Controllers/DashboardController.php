<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Data Ringkasan Umum
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

        $expenseCategoriesGlobal = ExpenseCategory::all();

        // --- PERSIAPAN DATA UNTUK CHART 
        $monthlyChartLabels = [];
        $monthlyChartIncomeData = [];
        $monthlyChartExpenseData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyChartLabels[] = $month->format('M Y'); 

            $incomeForMonth = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
            $monthlyChartIncomeData[] = (float) $incomeForMonth;

            $expenseForMonth = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
            $monthlyChartExpenseData[] = (float) $expenseForMonth; 
        }

        $currentMonthExpenses = Transaction::where('transactions.user_id', $userId)
            ->where('transactions.transaction_type', 'expense')
            ->whereYear('transactions.date', Carbon::now()->year)
            ->whereMonth('transactions.date', Carbon::now()->month)
            ->join('expense_categories', 'transactions.category_id', '=', 'expense_categories.id')
            ->select('expense_categories.category_name as category_display_name', DB::raw('SUM(transactions.amount) as total_amount'))
            ->groupBy('expense_categories.id', 'expense_categories.category_name')
            ->orderBy('total_amount', 'desc')
            ->get();

        $pieChartLabels = $currentMonthExpenses->pluck('category_display_name')->toArray();
        $pieChartData = $currentMonthExpenses->pluck('total_amount')->map(function($value) {
            return (float) $value; 
        })->toArray();


        return view('main.dashboard', compact(
            'totalIncome',
            'totalExpense',
            'transactions',
            'expenseCategoriesGlobal',
            'monthlyChartLabels',
            'monthlyChartIncomeData',
            'monthlyChartExpenseData',
            'pieChartLabels',
            'pieChartData'
        ));
    }
}


