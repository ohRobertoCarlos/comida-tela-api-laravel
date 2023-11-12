<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsEstablishment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userIsEstablishment = $request->user()->establishment_id === $request->route('establishment_id');
        if (
            !$userIsEstablishment &&
            !$request->user()->is_admin
        ) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }

        return $next($request);
    }
}
