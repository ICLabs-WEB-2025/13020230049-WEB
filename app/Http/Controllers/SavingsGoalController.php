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
}
