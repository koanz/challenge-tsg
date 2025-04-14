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
                'error' => 'Token no encontrado. Acceso denegado.',
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Si usas un paquete como `tymon/jwt-auth`, puedes validar el token aquí.
        try {
            // Ejemplo con JWT-Auth (si está configurado):
            auth('api')->setToken($token)->authenticate();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token inválido o expirado.',
                'status_code' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
