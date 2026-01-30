<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, fallback to app default
        $locale = session('locale', config('app.locale'));

        // Validate locale
        $supportedLocales = ['en', 'bn']; // English and Bengali

        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        } else {
            // Fallback to default locale
            App::setLocale(config('app.locale'));
            session(['locale' => config('app.locale')]);
        }

        return $next($request);
    }
}
