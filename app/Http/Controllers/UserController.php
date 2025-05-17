<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'username' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            session()->flash('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->route('login');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat melakukan registrasi. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }

    }   

    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request) 
    { 
        $credentials = $request->only('email', 'password'); 

        if (Auth::attempt($credentials)) { 
            $user = Auth::user(); 
            return redirect()->route('dashboard');
            // return response()->json($user, 200); 
        } 
        return redirect()->route('login');
        // return response()->json(['error' => 'Unauthorized'], 401); 
    }

}