<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUUID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Verifikasi UUID pengguna
            $uuid = $request->header('X-UUID');
            if ($uuid && $user->uuid !== $uuid) {
                Auth::logout();
                return redirect()->route('login')->withErrors('UUID tidak valid')->withInput();
            }
        }

        return $next($request);
    }
}
