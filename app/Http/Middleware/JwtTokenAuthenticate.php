<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtTokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                "status_code" => Response::HTTP_UNAUTHORIZED,
                "message" => "Token no encontrado. Acceso denegado.",
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            auth('api')->setToken($token)->authenticate();
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => Response::HTTP_UNAUTHORIZED,
                "message" => "Token inválido o expirado.",
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
