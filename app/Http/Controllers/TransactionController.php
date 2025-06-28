<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
                                ->with('category')
                                ->latest()
                                ->get();
        
        $incomeCategories = ExpenseCategory::where('type', 'income')->get();
        $expenseCategories = ExpenseCategory::where('type', 'expense')->get();
        
        $totalIncome = Transaction::where('user_id', auth()->id())
            ->where('transaction_type', 'income')
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', auth()->id())
            ->where('transaction_type', 'expense')
            ->sum('amount');

        return view('transaction.index', compact(
            'transactions',
            'incomeCategories', 
            'expenseCategories', 
            'totalIncome',
            'totalExpense'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        $transaction = new Transaction([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        $transaction->save();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',  
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $transaction->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,  
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== auth()->id()) {
            return redirect()->route('transactions.index')->with('error', 'Akses ditolak.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }

}
