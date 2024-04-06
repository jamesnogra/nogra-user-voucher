<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserToken;

class TokenValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validate the incoming request data
        $validatedData = $request->validate(['token' => 'required']);

        // Validate the token
        $userToken = UserToken::validateToken($request->token);
        if (!$userToken) {
            return response()->json([
                'error' => 'Unauthorized, incorrect token'
            ], Response::HTTP_UNAUTHORIZED);
        }
        // Put the token data to $request
        $request['user_id'] = $userToken->user_id;

        return $next($request);
    }
}
