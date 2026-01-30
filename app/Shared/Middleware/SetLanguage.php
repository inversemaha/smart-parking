<?php

namespace App\Shared\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from various sources in priority order
        $locale = $this->determineLocale($request);

        // Validate the locale
        if ($this->isValidLocale($locale)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // Fallback to default locale
            $locale = config('app.locale', 'en');
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Determine the locale from various sources.
     */
    protected function determineLocale(Request $request): string
    {
        // 1. Check URL parameter
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }

        // 2. Check header for API requests
        if ($request->expectsJson()) {
            $locale = $request->header('Accept-Language');
            if ($locale && $this->isValidLocale($locale)) {
                return $locale;
            }
        }

        // 3. Check session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
        }

        // 4. Check user preference if authenticated
        if ($user = auth()->user()) {
            if (isset($user->preferred_language) && $this->isValidLocale($user->preferred_language)) {
                return $user->preferred_language;
            }
        }

        // 5. Check browser Accept-Language header
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale && $this->isValidLocale($browserLocale)) {
            return $browserLocale;
        }

        // 6. Default locale
        return config('app.locale', 'en');
    }

    /**
     * Check if the locale is valid.
     */
    protected function isValidLocale(string $locale): bool
    {
        $supportedLocales = config('app.supported_locales', ['en', 'bn']);
        return in_array($locale, $supportedLocales);
    }

    /**
     * Get the preferred locale from browser Accept-Language header.
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = [];
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLanguage, $matches);

        if (count($matches[1])) {
            $languages = array_combine($matches[1], $matches[2]);

            foreach ($languages as $lang => $q) {
                // Extract the primary language (e.g., 'en' from 'en-US')
                $primaryLang = substr($lang, 0, 2);

                if ($this->isValidLocale($primaryLang)) {
                    return $primaryLang;
                }

                // Also check the full locale
                if ($this->isValidLocale($lang)) {
                    return $lang;
                }
            }
        }

        return null;
    }
}
