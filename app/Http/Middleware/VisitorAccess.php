<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('visitor.login')->with('error', __('auth.authentication_required'));
        }

        $user = Auth::user();

        // Check if account is active
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('visitor.login')->with('error', __('auth.account_deactivated'));
        }

        // Check if user type is visitor or compatible
        if (!in_array($user->user_type, ['visitor', 'user', null])) {
            // If admin/staff trying to access visitor panel, redirect to admin dashboard
            if ($user->hasAnyRole(['admin', 'super-admin', 'manager'])) {
                return redirect()->route('admin.dashboard.index');
            }

            Auth::logout();
            return redirect()->route('visitor.login')->with('error', __('auth.unauthorized_access'));
        }

        // Assign visitor role if user doesn't have any role
        if (!$user->hasAnyRole(['visitor', 'admin', 'super-admin', 'manager'])) {
            $user->assignRole('visitor');
        }

        // Update user_type if needed
        if (is_null($user->user_type) || $user->user_type === 'user') {
            $user->update(['user_type' => 'visitor']);
        }

        return $next($request);
    }
}
