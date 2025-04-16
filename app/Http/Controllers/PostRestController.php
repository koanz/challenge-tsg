<?php

namespace App\Http\Controllers;

use App\Exceptions\PostNotFoundException;
use App\Http\Requests\CreatePostFormRequest;
use App\Http\Requests\UpdatePostFormRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use App\Utils\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints del crud de posts"
 * )
 */
class PostRestController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Obtener un listado de posts",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Cantidad de registros por página. Por defecto es 10.",
     *          required=false,
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Número de página. Por defecto es 1.",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse {
        Log::info("Retrieving list of Post in PostRestController");
        $pagination = new Pagination($request->query('per_page', 10), $request->query('page', 1));

        $posts = $this->postService->list($pagination);

        return response()->json([
            'data' => PostResource::collection($posts),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Crear un post",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="Atributos para crear el post",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="topic",
     *                  type="string",
     *                  description="Tema del post",
     *                  example="Lorem Ipsum"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  type="string",
     *                  description="Contenido del post",
     *                  example="Lorem Ipsum is simply dummy text of the printing and typesetting industry."
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  type="integer",
     *                  description="Id del usuario",
     *                  example="1"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserResource")
     *          )
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function create(CreatePostFormRequest $request): JsonResponse {
        Log::info("Creating Post in PostRestController");

        $data = $request->validated();

        return response()->json(new PostResource($this->postService->create($data)), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Obtener un post por id",
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
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function find($id): JsonResponse {
        Log::info("Retrieving Post with id $id in PostRestController");

        try {
            $post = $this->postService->findById($id);
            return response()->json(new PostResource($post));
        } catch (PostNotFoundException $e) {
            Log::error("Post with id $id Not Found.");

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/posts/{id}",
     *     summary="Actualizar un post por id",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del post a actualizar",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *              example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Atributos para actualizar el post",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="topic",
     *                 type="string",
     *                 description="Tema del post",
     *                 example="Lorem Ipsum"
     *             ),
     *             @OA\Property(
     *                 property="content",
     *                 type="string",
     *                 description="Contenido del post",
     *                example="Lorem Ipsum is simply dummy text of the printing and typesetting industry."
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 description="Id del usuario",
     *                 example="1"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function update(UpdatePostFormRequest $request, $id): JsonResponse {
        Log::info('Updating Post in PostRestController');

        try {
            $post = $this->postService->update($request->validated(), $id);
        } catch (PostNotFoundException $e) {
            Log::error("Post with id $id Not Found.");

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error("Error inesperado: " .$e->getMessage());
            return response()->json([
                "status_code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Error interno del servidor"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Eliminar un post por su id",
     *     tags={"Posts"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del post a eliminar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function delete($id): JsonResponse {
        Log::info("Deleting Post with id $id in PostRestController");

        try {
            $this->postService->delete($id);
        } catch (PostNotFoundException $e) {
            Log::error("Error trying to delete post: " .$e->getMessage(), ['id' => $id]);

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error("Unexpected error: ".$e->getMessage());

            return response()->json([
                "status_code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Error interno del servidor"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Se ha eliminado exitosamente.'
        ]);
    }
}
