<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;

class AuthAdminController extends Controller
{
    public function showLoginForm()
    {
        if (auth('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember    = (bool) $request->boolean('remember');

        $admin = \App\Models\Admin::where('email', $credentials['email'])->first();
        if (!$admin) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'البريد الإلكتروني غير مسجل.'], 404);
            }
            return back()->withErrors(['email' => 'البريد الإلكتروني غير مسجل.'])->withInput();
        }

        if (! auth('admin')->attempt($credentials, $remember)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'كلمة المرور غير صحيحة.'], 401);
            }
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة.'])->withInput();
        }

        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ],
            ], 200);
        }

        return redirect()->route('admin.dashboard');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        if (auth('admin')->check()) {
            auth('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Logged out successfully'], 200);
            }
            return redirect()->route('admin.login')->with('status','تم تسجيل الخروج');
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
