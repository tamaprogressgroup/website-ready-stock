<?php

namespace App\Http\Controllers\Back\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Admin::orderByDesc('id')->paginate(20);
        return view('back.admin-user.index', compact('users'));
    }

    public function create()
    {
        return view('back.admin-user.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:m_admin_users,email',
            'password'              => 'required|string|min:8|confirmed',
            'role'                  => 'required|in:superadmin,admin,staff',
            'is_active'             => 'nullable|boolean',
        ]);

        Admin::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'is_active'  => $request->boolean('is_active', true) ? 1 : 0,
            'created_at' => now(),
        ]);

        return redirect()->route('admin-user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return view('back.admin-user.form', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => ['required', 'email', Rule::unique('m_admin_users', 'email')->ignore($id)],
            'password'  => 'nullable|string|min:8|confirmed',
            'role'      => 'required|in:superadmin,admin,staff',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->boolean('is_active', true) ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin-user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $currentAdmin = Auth::guard('admin')->user();

        if ((int) $currentAdmin->id === (int) $id) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang login.');
        }

        Admin::findOrFail($id)->delete();

        return redirect()->route('admin-user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
