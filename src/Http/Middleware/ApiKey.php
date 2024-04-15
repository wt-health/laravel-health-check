<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->query('key');
        if ($key == null || $key !== config('health-check.key')) {
            return response()->json(['data' => 'forbidden'], 403);
        }

        return $next($request);
    }
}
