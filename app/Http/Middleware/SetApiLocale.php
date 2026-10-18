<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetApiLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $language = strtolower(trim((string) $request->header('Accept-Language', '')));
        $locale = preg_match('/^(ar|arabic)(?:[-_,;]|$)/', $language) === 1 ? 'ar' : 'en';

        App::setLocale($locale);

        return $next($request);
    }
}
