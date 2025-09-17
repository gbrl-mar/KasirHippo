<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtCookieAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('access_token');

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->toUser();
            $request->user = $user;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token invalid'], 401);
        }

        return $next($request);
    }
}
