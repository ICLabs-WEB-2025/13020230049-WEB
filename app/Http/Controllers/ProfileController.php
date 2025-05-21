<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Untuk verifikasi password
use App\Models\User; // Model User
use App\Models\UserPoint;
use App\Models\SavingsGoal;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Handle account deletion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        // Validasi password saat ini untuk konfirmasi penghapusan
        $request->validateWithBag('deleteAccount', [
            'password_delete' => ['required', 'string'],
        ]);

        if (!Hash::check($request->password_delete, $user->password)) {
            return back()->withErrors([
                'password_delete' => 'Password yang Anda masukkan salah.',
            ], 'deleteAccount')->with('open_modal_on_error', 'deleteAccountModal'); // Agar modal tetap terbuka jika error
        }

        // Lakukan logout sebelum menghapus user untuk menghindari masalah sesi
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus pengguna. Data terkait (transactions, savings_goals, user_points)
        // akan terhapus otomatis jika onDelete('cascade') sudah di-set di migration.
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }

    // Method untuk edit profil dasar (username/email) bisa ditambahkan di sini
    // public function edit() { ... }
    // public function update(Request $request) { ... }
}
