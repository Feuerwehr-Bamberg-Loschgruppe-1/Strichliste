<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_admin) {
            // Wenn der Benutzer nicht mehr Admin ist, logge ihn aus
            Auth::logout();

            // Session invalidieren
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Weiterleitung zur Login-Seite mit einer Nachricht
            return redirect('/login')->withErrors('Sie wurden abgemeldet, da Ihr Admin-Status entfernt wurde.');
        }

        return $next($request);
    }
}
