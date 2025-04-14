<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostFormRequest;
use App\Http\Requests\UpdatePostFormRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PostRestController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Obtiene un listado de posts",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron posts"
     *     )
     * )
     */
    public function index(): JsonResponse {
        $posts = Post::with('user')->paginate(10);

        return response()->json(PostResource::collection($posts));
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Crear un post",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Creación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function create(CreatePostFormRequest $request): JsonResponse {
        Log::info("Create Post method in PostRestController");
        $data = $request->validated();

        return response()->json(new PostResource($this->postService->create($data)), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Obtiene un post por id",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           description="ID del post que se quiere recuperar",
     *           required=true,
     *           @OA\Schema(
     *               type="integer",
     *               example=1
     *           )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El post no existe"
     *     )
     * )
     */
    public function find($id): JsonResponse {
        Log::info("Find/Read Post with id: $id in PostRestController");

        try {
            $post = $this->postService->findById($id);
            return response()->json(new PostResource($post));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(UpdatePostFormRequest $request, $id): JsonResponse {
        Log::info('Update Post method in PostRestController');
        $post = $this->postService->update($request, $id);

        return response()->json(new PostResource($post));
    }

    public function delete($id): JsonResponse {
        Log::info('Delete Post method in PostRestController');
        $this->postService->delete($id);

        return response()->json(['message' => 'Se ha eliminado exitosamente']);
    }
}
