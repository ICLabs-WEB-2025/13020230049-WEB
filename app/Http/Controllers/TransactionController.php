<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil semua transaksi yang dimiliki oleh pengguna yang sedang login
        $transactions = Transaction::where('user_id', auth()->id())
                                    ->with('category') // Mengambil relasi kategori
                                    ->latest()
                                    ->get();
        $categories = ExpenseCategory::all();
        // Mengembalikan data transaksi ke view
        // return view('transaction.index', compact('transactions'));
        return view('transaction.index', compact('transactions', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        // dd($request->all());
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        // dd('Validasi lolos', $request->all());

        // Menyimpan transaksi baru
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

        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',  // pastikan ini ada
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Memperbarui transaksi
        $transaction->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,  // pastikan ini diterima
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
