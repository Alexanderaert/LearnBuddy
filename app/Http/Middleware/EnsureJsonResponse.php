<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EnsureJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }
        return $response;
    }
}
