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

        // Mengembalikan data transaksi ke view
        return view('transaction.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

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
        // Menemukan transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($id);

        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category_id' => 'required|exists:expense_categories,id',
            'transaction_type' => 'required|in:income,expense',
            'date' => 'required|date',  // Validasi format tanggal yang benar
            'description' => 'nullable|string',
        ]);

        // Memperbarui transaksi dengan data yang diterima
        $transaction->update([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'description' => $request->description,
            'date' => $request->date,  // Pastikan data `date` diterima dan disimpan
        ]);

        // Mengembalikan respons sukses
        return response()->json(['success' => 'Transaksi berhasil diperbarui!']);
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
