<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Hanya import yang diperlukan
use PHPUnit\Framework\MockObject\NoMoreReturnValuesConfiguredException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        Auth::logout();

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if(Auth::user()->role !== 'siswa') {
                Auth::logout();
                return back()->with('error', 'akun ini bukan akun untuk siswa');
            }
            return redirect()->route('siswa.dashboard');
        }
        
        return back()->with('error', 'email atau password salah');
    }

    public function loginAdmin(Request $request) 
    {
        Auth::logout();
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)) {
            if(Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'akun ini tidak ada hak akses admin');
            }
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'email atau password salah');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}