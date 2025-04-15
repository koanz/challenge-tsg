<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response {
        Log::info("Verifying Post Owner middleware");

        // Obtiene el ID del post desde la ruta.
        $postId = $request->route('id');

        // Valida la existencia del post.
        try {
            $post = Post::findOrFail($postId);
        } catch (ModelNotFoundException $e) {
            Log::error("Post con ID {$postId} no encontrado.");

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => "El Post con id {$postId} no se ha encontrado."
            ], Response::HTTP_NOT_FOUND);
        }

        $authenticatedUser = Auth::user();

        if($authenticatedUser->hasRole('user')) {
            // Valida que el usuario autenticado sea el propietario del post.
            if ($authenticatedUser->id !== $post->user_id) {
                return response()->json([
                    "status_code" => Response::HTTP_FORBIDDEN,
                    "message" => "No tienes permiso para realizar esta acción."
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
