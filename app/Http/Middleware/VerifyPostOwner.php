<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response {
        $postId = $request->route('id'); // Obtener el ID del post desde la ruta.
        $post = Post::find($postId); // Buscar el post.

        // Validar que el post existe.
        if (!$post) {
            return response()->json([
                'error' => 'El post no existe.'
            ], 404);
        }

        // Validar que el usuario autenticado sea el propietario del post.
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'No tienes permiso para realizar esta acción.'
            ], 403);
        }

        return $next($request); // Continuar con la solicitud si la validación pasa.
    }
}
