<?php

namespace App\Http\Controllers\Back\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('back.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email dulu
        $admin = Admin::where('email', $request->email)->first();

        // Email tidak ditemukan
        if (!$admin) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar.',
            ])->onlyInput('email');
        }

        // Password salah
        if (!Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'email' => 'Password yang kamu masukkan salah.',
            ])->onlyInput('email');
        }

        // Akun nonaktif — perlu persetujuan admin
        if (!$admin->is_active) {
            return back()->withErrors([
                'email' => 'Akun kamu belum diaktifkan. Hubungi admin untuk aktivasi.',
            ])->onlyInput('email');
        }

        // Semua valid — login
        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('customer.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('back.login');
    }
}
