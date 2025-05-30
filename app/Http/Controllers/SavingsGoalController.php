<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use App\Models\Userpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SavingsGoalController extends Controller
{
    const POINTS_FOR_GOAL_COMPLETION = 50;

    public function index()
    {
        $savingsGoals = SavingsGoal::where('user_id', Auth::id())
                            ->orderBy('target_date', 'asc')
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('savingsGoals.index', compact('savingsGoals'));
    }

    public function create()
    {
        return redirect()->route('savings-goals.index')->with('info', 'Gunakan tombol "Tambah Tujuan Baru" untuk membuat tujuan.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0.01',
            'target_date' => 'required|date|after_or_equal:today',
        ]);

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

        $this->awardPointsIfGoalCompleted($savingsGoal);

        return redirect()->route('savings-goals.index')
                        ->with('success', 'Tujuan tabungan berhasil ditambahkan!');
    }

    public function show(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return redirect()->route('savings-goals.index');
    }

    public function edit(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return redirect()->route('savings-goals.index')->with('info', 'Gunakan tombol "Edit" pada daftar untuk mengubah tujuan.');
    }

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

        $this->awardPointsIfGoalCompleted($savingsGoal);

        return redirect()->route('savings-goals.index')
                        ->with('success', 'Tujuan tabungan berhasil diperbarui!');
    }

    public function destroy(SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $savingsGoal->delete();

        return redirect()->route('savings-goals.index')
                        ->with('success', 'Tujuan tabungan berhasil dihapus!');
    }
    
    public function addFunds(Request $request, SavingsGoal $savingsGoal)
    {
        if ($savingsGoal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'amount_to_add' => 'required|numeric|min:0.01',
            'date' => 'required|date', 
        ]);

        if ($validator->fails()) {
            return redirect()->route('savings-goals.index')
                        ->withErrors($validator, 'addFundsErrorBag_goal_'.$savingsGoal->id) // Error bag unik per goal
                        ->withInput()
                        ->with('open_modal_on_error', 'addFundsModal') 
                        ->with('error_modal_goal_id', $savingsGoal->id); 
        }

        $amountToAdd = $request->input('amount_to_add');

        // Update current_amount di savings goal
        $savingsGoal->current_amount += $amountToAdd;
        $savingsGoal->save();

        $this->awardPointsIfGoalCompleted($savingsGoal);

        // Bagian untuk mencatat transaksi otomatis telah dihapus.

        return redirect()->route('savings-goals.index')
                        ->with('success', 'Dana berhasil ditambahkan ke tujuan "' . $savingsGoal->goal_name . '"!');
    }

    protected function awardPointsIfGoalCompleted(SavingsGoal $savingsGoal)
    {
        // Cek apakah tujuan sudah tercapai
        if ($savingsGoal->current_amount >= $savingsGoal->target_amount) {
            $existingPoints = UserPoint::where('user_id', $savingsGoal->user_id)
                                    ->where('savings_goal_id', $savingsGoal->id)
                                    ->where('achievement_type', 'goal_completed')
                                    ->exists();

            if (!$existingPoints) {
                UserPoint::create([
                    'user_id' => $savingsGoal->user_id,
                    'savings_goal_id' => $savingsGoal->id,
                    'points_earned' => self::POINTS_FOR_GOAL_COMPLETION,
                    'description' => 'Selamat! Menyelesaikan tujuan tabungan: ' . $savingsGoal->goal_name,
                    'achievement_date' => Carbon::now(), 
                    'achievement_type' => 'goal_completed',
                ]);
            }
        }
    }
}
