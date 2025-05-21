<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SavingsGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $savingsGoals = SavingsGoal::where('user_id', Auth::id())
                            ->orderBy('target_date', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->get();
        // Menggunakan path view 'savingsGoals.index' (camelCase) sesuai preferensi folder
        return view('savingsGoals.index', compact('savingsGoals'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index')->with('info', 'Gunakan tombol "Tambah Tujuan Baru" untuk membuat tujuan.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'required|date|after_or_equal:today',
        ]);

        // Redirect ke route 'savings-goals.index' (kebab-case)
        if ($validator->fails()) {
            return redirect()->route('savings-goals.index')
                        ->withErrors($validator, 'storeSavingsGoal')
                        ->withInput()
                        ->with('open_modal_on_error', 'addSavingsGoalModal');
        }

        $savingsGoal = new SavingsGoal();
        $savingsGoal->user_id = Auth::id();
        $savingsGoal->goal_name = $request->input('goal_name');
        $savingsGoal->target_amount = $request->input('target_amount');
        $savingsGoal->target_date = $request->input('target_date');
        $savingsGoal->current_amount = 0;
        $savingsGoal->save();

        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index')
                         ->with('success', 'Tujuan tabungan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SavingsGoal  $savingsGoal
     * @return \Illuminate\Http\Response
     */
    public function show(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // Menggunakan path view 'savingsGoals.show' (camelCase) jika ada
        // return view('savingsGoals.show', compact('savingsGoal'));
        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SavingsGoal  $savingsGoal
     * @return \Illuminate\Http\Response
     */
    public function edit(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index')->with('info', 'Gunakan tombol "Edit" pada daftar untuk mengubah tujuan.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SavingsGoal  $savingsGoal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'required|numeric|min:0|max:'.$request->input('target_amount', $savingsGoal->target_amount),
            'target_date' => 'required|date|after_or_equal:today',
        ]);

        // Redirect ke route 'savings-goals.index' (kebab-case)
        if ($validator->fails()) {
            return redirect()->route('savings-goals.index')
                        ->withErrors($validator, 'updateSavingsGoal')
                        ->withInput()
                        ->with('open_modal_on_error', 'editSavingsGoalModal-'.$savingsGoal->id);
        }

        $savingsGoal->goal_name = $request->input('goal_name');
        $savingsGoal->target_amount = $request->input('target_amount');
        $savingsGoal->current_amount = $request->input('current_amount');
        $savingsGoal->target_date = $request->input('target_date');
        $savingsGoal->save();

        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index')
                         ->with('success', 'Tujuan tabungan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SavingsGoal  $savingsGoal
     * @return \Illuminate\Http\Response
     */
    public function destroy(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $savingsGoal->delete();

        // Redirect ke route 'savings-goals.index' (kebab-case)
        return redirect()->route('savings-goals.index')
                        ->with('success', 'Tujuan tabungan berhasil dihapus!');
    }

    
    public function addFunds(Request $request, SavingsGoal $savingsGoal)
    {
        // Otorisasi: Pastikan pengguna hanya bisa menambah dana ke miliknya
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input dari form modal tambah dana
        // Input 'date' dari form 'addFundsModal' juga divalidasi
        $validator = Validator::make($request->all(), [
            'amount_to_add' => 'required|numeric|min:0.01',
            'date' => 'required|date', // Validasi untuk tanggal penambahan dana
        ]);

        if ($validator->fails()) {
            // Menggunakan error bag yang lebih spesifik untuk form ini
            // agar tidak bentrok jika ada error lain di halaman yang sama.
            // Kita juga perlu cara untuk memberitahu JavaScript modal mana yang harus dibuka kembali
            // dan untuk goal ID mana.
            return redirect()->route('savings-goals.index')
                        ->withErrors($validator, 'addFundsErrorBag_goal_'.$savingsGoal->id) // Error bag unik per goal
                        ->withInput()
                        // Flag untuk membuka modal tambah dana yang spesifik jika ada error
                        ->with('open_modal_on_error', 'addFundsModal') // ID Modal umum
                        ->with('error_modal_goal_id', $savingsGoal->id); // ID Goal spesifik untuk JS
        }

        $amountToAdd = $request->input('amount_to_add');

        // Update current_amount di savings goal
        $savingsGoal->current_amount += $amountToAdd;
        // Pertimbangkan untuk tidak membiarkan current_amount melebihi target_amount
        // if ($savingsGoal->current_amount > $savingsGoal->target_amount) {
        //     $savingsGoal->current_amount = $savingsGoal->target_amount;
        // }
        $savingsGoal->save();

        // Bagian untuk mencatat transaksi otomatis telah dihapus.

        return redirect()->route('savings-goals.index')
                         ->with('success', 'Dana berhasil ditambahkan ke tujuan "' . $savingsGoal->goal_name . '"!');
    }
}
