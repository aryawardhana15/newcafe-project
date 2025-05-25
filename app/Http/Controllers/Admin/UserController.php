<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|min:3|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'address' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|in:M,F'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['point'] = 0;
        $validatedData['coupon'] = 0;

        User::create($validatedData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => ['required', 'string', 'min:3', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'address' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|in:M,F'
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Prevent deleting the last admin
        if ($user->role_id === 1 && User::where('role_id', 1)->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    public function updatePoint(Request $request, User $user)
    {
        $request->validate([
            'point' => 'required|integer|min:0'
        ]);

        $user->point = $request->point;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Point pengguna berhasil diperbarui');
    }

    public function updateCoupon(Request $request, User $user)
    {
        $request->validate([
            'coupon' => 'required|integer|min:0'
        ]);

        $user->coupon = $request->coupon;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Kupon pengguna berhasil diperbarui');
    }
} 