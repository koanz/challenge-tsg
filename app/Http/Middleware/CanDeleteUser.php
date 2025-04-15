<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanDeleteUser
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authenticatedUser = Auth::user();
        $userId = $request->route('id');

        // Usuarios con role user no tienen permisos para eliminar usuarios
        if($authenticatedUser->hasRole('user')) {
            return response()->json([
                "status_code" => Response::HTTP_FORBIDDEN,
                "message" => "No tienes permiso para realizar esta acción."
            ], Response::HTTP_FORBIDDEN);
        }

        if($authenticatedUser->hasRole('admin')) {
            // Validar que el usuario admin autenticado no pueda eliminar su propio usuario
            if ($authenticatedUser->id == $userId) {
                return response()->json([
                    "status_code" => Response::HTTP_FORBIDDEN,
                    "message" => "No es posible realizar está acción."
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
