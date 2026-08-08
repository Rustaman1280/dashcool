<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Simple mock authentication for testing UI
        if ($request->email === 'admin@datasekolah.sch.id' && $request->password === 'password') {
            session(['user' => ['name' => 'Administrator Utama', 'email' => $request->email, 'role' => 'Super Admin']]);
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, Administrator!');
        }

        // Allow any login in demo environment for convenience
        session(['user' => ['name' => 'Admin Sekolah', 'email' => $request->email, 'role' => 'Panitia SPMB']]);
        return redirect()->route('dashboard')->with('success', 'Berhasil masuk ke Sistem DataSekolah.');
    }

    public function logout(Request $request)
    {
        session()->forget('user');
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
