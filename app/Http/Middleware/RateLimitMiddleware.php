<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

class RateLimitMiddleware
{
    private const RATE_LIMIT = 60; 
    private const WINDOW = 60; // seconds

    public function handle(Request $request, Closure $next)
    {
        $key = 'rate_limit:' . $request->ip();
        $attempts = Cache::get($key, 0);

        if ($attempts >= self::RATE_LIMIT) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        Cache::put($key, $attempts + 1, self::WINDOW);

        return $next($request);
    }
}