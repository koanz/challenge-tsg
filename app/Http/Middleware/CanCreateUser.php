<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanCreateUser
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

        // Valida si el usuario posee rol user
        if($authenticatedUser->hasRole('user')) {
            return response()->json([
                "status_code" => Response::HTTP_FORBIDDEN,
                "message" => "No tienes permiso para realizar esta acción."
            ], Response::HTTP_FORBIDDEN);

        }

        return $next($request);
    }
}
