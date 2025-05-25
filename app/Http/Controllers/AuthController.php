<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function loginGet()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        $title = "Login";
        return view("/auth/login", compact("title"));
    }

    public function loginPost(Request $request)
    {
        try {
            $credentials = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                
                // Redirect ke dashboard admin jika user adalah admin
                if (auth()->user()->role_id === 1) {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->intended("/home");
            }

            return back()
                ->withInput()
                ->with("loginError", "Email atau password salah!");
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with("loginError", "Terjadi kesalahan saat login. Silakan coba lagi.");
        }
    }

    public function registrationGet()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        $title = "Registration";
        return view("/auth/register", compact("title"));
    }

    public function registrationPost(Request $request)
    {
        try {
            // Validate input
            $validatedData = $request->validate([
                "fullname" => "required|string|max:255",
                "username" => "required|string|min:3|max:255|unique:users,username",
                "email" => "required|email|unique:users,email",
                "password" => "required|string|min:5|max:255|confirmed",
                "password_confirmation" => "required",
                "address" => "required|string|max:255",
                "phone" => "required|string|max:20",
                "gender" => "required|in:M,F"
            ]);

            // Check if roles exist
            if (!Role::find(2)) {
                throw new \Exception('Sistem belum siap untuk registrasi. Silakan hubungi administrator.');
            }

            // Remove password_confirmation from validated data
            unset($validatedData['password_confirmation']);

            // Hash password
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Set default values
            $validatedData['image'] = env("IMAGE_PROFILE", "default.jpg");
            $validatedData['role_id'] = 2; // Customer role
            $validatedData['coupon'] = 0;
            $validatedData['point'] = 0;
            $validatedData['remember_token'] = Str::random(30);

            // Create user
            $user = User::create($validatedData);

            if (!$user) {
                throw new \Exception('Gagal membuat user baru.');
            }

            return redirect('/auth/login')
                ->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Registration validation error: ' . $e->getMessage());
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    public function logoutPost()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/auth')
            ->with('success', 'Anda berhasil logout.');
    }
}
