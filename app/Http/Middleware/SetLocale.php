<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale');

        if (! $locale) {
            // Try to get from user preferences if authenticated
            if ($request->user() && method_exists($request->user(), 'locale')) {
                $locale = $request->user()->locale;
            }

            // Fall back to browser language detection
            if (! $locale) {
                $locale = $request->getPreferredLanguage(config('app.supported_locales', ['en']));
            }

            // Fall back to default locale
            if (! $locale || ! in_array($locale, config('app.supported_locales', ['en']))) {
                $locale = config('app.locale', 'en');
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
