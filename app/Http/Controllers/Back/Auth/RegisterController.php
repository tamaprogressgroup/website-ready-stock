<?php

namespace App\Http\Controllers\Back\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('customer.dashboard');
        }

        return view('back.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:m_admin_users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'staff',
            'is_active'  => 0, // perlu diaktifkan oleh superadmin
            'created_at' => now(),
        ]);

        return redirect()->route('back.login')
            ->with('success', 'Pendaftaran berhasil! Akun kamu sedang menunggu persetujuan admin sebelum bisa login.');
    }
}
