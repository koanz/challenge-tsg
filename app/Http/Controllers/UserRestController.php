<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFountException;
use App\Http\Requests\UpdateUserFormRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserRestController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obtiene un listado de usuarios",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron usuarios"
     *     )
     * )
     */
    public function index(): JsonResponse {
        $users = User::paginate(10);

        return response()->json(UserResource::collection($users));
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Obtiene un post por id",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Creación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function create(UserRegisterRequest $request): JsonResponse {
        Log::info("Create User method in UserRestController");
        $data = $request->validated();

        return response()->json(new UserResource($this->userService->create($data)), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtiene un usuario por id",
     *     tags={"Usuarios"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID del usuario que se quiere recuperar",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="El usuario no existe"
     *     )
     * )
     */
    public function find($id): JsonResponse {
        Log::info("Find/Read User with id: $id in UserRestController");

        try {
            $user = $this->userService->findById($id);
            return response()->json(new UserResource($user));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateUserFormRequest $request, $id): JsonResponse {
        Log::info("Update User method in UserRestController");
        try {
            $user = $this->userService->update($request, $id);
        } catch (ModelNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            Log::error("Error inesperado: " .$e->getMessage());
            return response()->json([
                'error' => 'Error interno del servidor'
            ], 500);
        }

        return response()->json(new UserResource($user));
    }

    public function delete($id): JsonResponse {
        Log::info("Delete Post method in PostRestController");

        try {
            $this->userService->delete($id);
        } catch (UserNotFountException $e) {
            Log::error("Error al eliminar usuario: " .$e->getMessage(), ['id' => $id]);
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            Log::error("Error inesperado: ".$e->getMessage());
            return response()->json([
                'error' => 'Error interno del servidor'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Se ha eliminado exitosamente.',
            'id' => $id
        ]);
    }
}
