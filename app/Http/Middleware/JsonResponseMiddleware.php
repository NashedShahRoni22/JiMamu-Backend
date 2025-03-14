<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Str::contains($request->fullUrl(), '/api')) {
            $request->headers->set('Accept', 'application/json');
            return $next($request);
        }
        return $next($request);
    }
}
