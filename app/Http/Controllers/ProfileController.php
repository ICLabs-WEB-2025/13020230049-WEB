<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPoint; // Pastikan model UserPoint sudah ada
use App\Models\SavingsGoal; // Tambahkan ini untuk model SavingsGoal

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login

        // Menghitung total poin pengguna
        $totalPoints = UserPoint::where('user_id', $user->id)->sum('points_earned');

        // Mengambil beberapa riwayat poin/pencapaian terbaru (misalnya 5 terbaru)
        $recentPointActivities = UserPoint::where('user_id', $user->id)
                                    ->orderBy('achievement_date', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5) // Ambil 5 entri terbaru
                                    ->get();

        // Mengambil ringkasan tujuan tabungan aktif (misalnya 3 teratas yang belum selesai)
        $activeSavingsGoals = SavingsGoal::where('user_id', $user->id)
                                    // Hanya tampilkan yang belum tercapai
                                    ->whereRaw('current_amount < target_amount')
                                    ->orderBy('target_date', 'asc') // Urutkan berdasarkan tanggal target terdekat
                                    ->take(3) // Ambil 3 tujuan teratas
                                    ->get();

        return view('profile.show', compact(
            'user',
            'totalPoints',
            'recentPointActivities',
            'activeSavingsGoals' // Kirim data tujuan tabungan aktif ke view
        ));
    }

    // Kamu bisa menambahkan method lain di sini nanti, misalnya untuk update profil, ganti password, dll.
    // public function edit() { ... }
    // public function update(Request $request) { ... }
    // public function updatePassword(Request $request) { ... }
}