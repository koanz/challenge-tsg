<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanUpdateUser
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recupera el usuario autenticado
        $authenticatedUser = Auth::user();
        // Obtiene el ID del usuario desde la ruta.
        $userId = $request->route('id');

        // Validación de permisos
        if ($authenticatedUser->hasRole('user') && $authenticatedUser->id != $userId) {
            return response()->json([
                "status_code" => Response::HTTP_FORBIDDEN,
                "message" => "No tienes permiso para realizar esta acción."
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
