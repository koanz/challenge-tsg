<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFountException;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Utils\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Endpoints del crud de usuarios"
 * )
 */
class UserRestController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obtener un listado de usuarios",
     *     tags={"Usuarios"},
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
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
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
        $pagination = new Pagination($request->query('per_page', 10), $request->query('page', 1));
        $users = $this->userService->list($pagination);

        return response()->json([
            'data' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Crear un usuario",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          description="Atributos para crear el usuario",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Nombre del usuario",
     *                  example="Juan"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email del usuario",
     *                  example="juan@email.com"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  description="Contraseña del usuario",
     *                  example="123456"
     *              ),
     *              @OA\Property(
     *                  property="password_confirmation",
     *                  type="string",
     *                  description="Confirmación de la contraseña",
     *                  example="123456"
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function create(UserRegisterRequest $request): JsonResponse {
        Log::info("Create method in UserRestController");
        $data = $request->validated();

        return response()->json(new UserResource($this->userService->create($data)), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtener un usuario por id",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del usuario a obtener",
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
        Log::info("Find method with id $id in UserRestController");

        try {
            $user = $this->userService->findById($id);
            return response()->json(new UserResource($user));
        } catch (UserNotFountException $e) {
            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/users/{id}",
     *     summary="Actualizar un usuario por su id",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del usuario a actualizar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Atributos para actualizar el usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Nombre del usuario",
     *                 example="Juan"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 description="Email del usuario",
     *                 example="juan@email.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="Contraseña del usuario",
     *                 example="123456"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 description="Confirmación de la contraseña",
     *                 example="123456"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Detalles de los errores de validación"
     *             )
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
     *
     *     @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *     )
     * )
     */
    public function update(UpdateUserFormRequest $request, $id): JsonResponse {
        Log::info("Updating user with id $id in UserRestController");

        try {
            $user = $this->userService->update($request->validated(), $id);
        } catch (UserNotFountException $e) {
            Log::error("User with id $id Not Found.");

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error("Unexpected error: " .$e->getMessage());

            return response()->json([
                "status_code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Error interno del servidor"
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Eliminar un usuario por su id",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id del usuario a eliminar",
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
        Log::info("Delete Post method in PostRestController");

        try {
            $this->userService->delete($id);
        } catch (UserNotFountException $e) {
            Log::error("Error trying to delete a user: " .$e->getMessage(), ['id' => $id]);

            return response()->json([
                "status_code" => Response::HTTP_NOT_FOUND,
                "message" => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error("Unexpected Error: ".$e->getMessage());

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
