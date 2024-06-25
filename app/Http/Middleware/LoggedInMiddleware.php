<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoggedInMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = auth('sanctum')->user();

        if (!$user) {
            return response(["message" => "Unauthorized"], 403);
        }
        
        // if (! $user->hasRole('Student')) {
        //     $response = [
        //         'message' => 'Forbidden',
        //         'data' => null,
        //         'errors' => null,
        //     ];

        //     return response($response, 401);
        // }

        $request->merge(['user' => $user]);

        return $next($request);
    }
}
