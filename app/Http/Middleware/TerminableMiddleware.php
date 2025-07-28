<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;
use Symfony\Component\HttpFoundation\Response;

class TerminableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $log = [
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'time' => now(),
        ];

        FacadesLog::info('Завершённый запрос:', $log);
    }
}
