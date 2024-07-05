<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$this->hasRequiredRole($user->role, $role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }

    private function hasRequiredRole($userRole, $requiredRoles)
    {
        // Convert the string to an array if necessary
        if (is_string($requiredRoles)) {
            $requiredRoles = explode('|', $requiredRoles);
        }

        return in_array($userRole, $requiredRoles);
    }
}
