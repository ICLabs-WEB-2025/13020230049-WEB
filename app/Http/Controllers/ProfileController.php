<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 
use App\Models\UserPoint;
use App\Models\SavingsGoal;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $totalPoints = UserPoint::where('user_id', $user->id)->sum('points_earned');

        $recentPointActivities = UserPoint::where('user_id', $user->id)
                                    ->orderBy('achievement_date', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        $activeSavingsGoals = SavingsGoal::where('user_id', $user->id)
                                    ->whereRaw('current_amount < target_amount')
                                    ->orderBy('target_date', 'asc')
                                    ->take(3)
                                    ->get();

        return view('profile.show', compact(
            'user',
            'totalPoints',
            'recentPointActivities',
            'activeSavingsGoals'
        ));
    }

    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        $request->validateWithBag('deleteAccount', [
            'password_delete' => ['required', 'string'],
        ]);

        if (!Hash::check($request->password_delete, $user->password)) {
            return back()->withErrors([
                'password_delete' => 'Password yang Anda masukkan salah.',
            ], 'deleteAccount')->with('open_modal_on_error', 'deleteAccountModal');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }

}
