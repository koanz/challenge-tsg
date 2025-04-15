<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CanCreatePost
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recupera el Usuario autenticado
        $authenticatedUser = Auth::user();
        // Recupera el id del Usuario del Post
        $postUserId = $request->input('user_id');

        // Valida que si son distintos usuarios no puedan realizar la operación
        if($authenticatedUser->id != $postUserId) {
            return response()->json([
                "status_code" => Response::HTTP_FORBIDDEN,
                "message" => "Operación inválida."
            ], Response::HTTP_FORBIDDEN);
        }


        return $next($request);
    }
}
