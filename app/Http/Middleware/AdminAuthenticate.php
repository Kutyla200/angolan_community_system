<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin area.');
        }
        
        $admin = Auth::guard('admin')->user();
        
        // Check if account is active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }
        
        // Check role if specified
        if ($role && !$this->hasRole($admin, $role)) {
            abort(403, 'You do not have permission to access this resource.');
        }
        
        // Log the admin activity
        $request->attributes->add(['admin_user' => $admin]);
        
        return $next($request);
    }
    
    /**
     * Check if admin has required role
     */
    private function hasRole($admin, $role)
    {
        $roles = explode('|', $role);
        return in_array($admin->role, $roles);
    }
}