<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        $user = Auth::user();

        if ($user && $user->status !== $status) {
            Auth::logout(); // Logout pengguna
            return redirect()->route('login')->withErrors('Akun Anda tidak aktif. Harap hubungi administrator.');
        }

        return $next($request);
    }
}
