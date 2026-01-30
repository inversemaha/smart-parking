<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLanguage
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
        // Get language from various sources in order of priority
        $locale = $this->getLocale($request);

        // Set application locale
        App::setLocale($locale);

        // Store in session for persistence
        session(['locale' => $locale]);

        return $next($request);
    }

    /**
     * Get locale from various sources
     */
    protected function getLocale(Request $request): string
    {
        // 1. Check if user is authenticated and has a preferred language
        if (auth()->check() && auth()->user()->preferred_language) {
            return auth()->user()->preferred_language;
        }

        // 2. Check session
        if (session()->has('locale')) {
            return session('locale');
        }

        // 3. Check Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');
        if ($acceptLanguage) {
            $preferredLanguages = $this->parseAcceptLanguage($acceptLanguage);
            foreach ($preferredLanguages as $lang) {
                if (in_array($lang, ['en', 'bn'])) {
                    return $lang;
                }
            }
        }

        // 4. Check for Bengali keywords in User-Agent (simple heuristic)
        $userAgent = $request->header('User-Agent', '');
        if (strpos($userAgent, 'bn') !== false || strpos($userAgent, 'BD') !== false) {
            return 'bn';
        }

        // 5. Default to English
        return 'en';
    }

    /**
     * Parse Accept-Language header
     */
    protected function parseAcceptLanguage(string $acceptLanguage): array
    {
        $languages = [];

        foreach (explode(',', $acceptLanguage) as $part) {
            $part = trim($part);
            if (strpos($part, ';q=') !== false) {
                [$lang, $priority] = explode(';q=', $part, 2);
                $languages[floatval($priority)] = substr(trim($lang), 0, 2);
            } else {
                $languages[1.0] = substr(trim($part), 0, 2);
            }
        }

        krsort($languages);

        return array_values($languages);
    }
}
