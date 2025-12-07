<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogHttpRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Log the incoming request
        Log::channel('user_activity')->info('HTTP Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'route' => $request->route()?->getName(),
        ]);

        $response = $next($request);

        $endTime = microtime(true);
        $executionTime = round(($endTime - $startTime) * 1000, 2); // in milliseconds

        // Log the response
        Log::channel('user_activity')->info('HTTP Response', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->status(),
            'execution_time' => "{$executionTime}ms",
            'user_id' => auth()->id(),
        ]);

        return $response;
    }
}

