<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log the login action
            \App\Models\AuditLog::create([
                'admin_user_id' => Auth::guard('admin')->id(),
                'action' => 'login',
                'description' => 'Admin logged in',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        // Log the logout action
        \App\Models\AuditLog::create([
            'admin_user_id' => Auth::guard('admin')->id(),
            'action' => 'logout',
            'description' => 'Admin logged out',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}