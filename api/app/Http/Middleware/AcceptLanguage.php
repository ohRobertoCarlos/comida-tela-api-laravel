<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AcceptLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLanguages = config('app.available_locales');
        $locale = $request->getPreferredLanguage($availableLanguages) ?? env('APP_LOCALE', App::getFallbackLocale());

        App::setLocale($locale);

        $response = $next($request);
        $response->header(
            'Content-Language',
            $request->hasHeader('Accept-Language') ? $locale : implode(', ', $availableLanguages)
        );

        return $response;
    }

}
