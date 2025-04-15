<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints: registro, inicio y cierre de sesión"
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de usuarios",
     *     tags={"Autenticación"},
     *     description="Registro de usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="name", type="string", format="name", example="Nestor", description="Nombre del usuario."),
     *             @OA\Property(property="email", type="string", format="email", example="nestor@example.com", description="Correo electrónico del usuario."),
     *             @OA\Property(property="password", type="string", format="password", example="123456", description="Contraseña del usuario."),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456", description="Confirmación de la contraseña del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registro exitoso"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ])->assignRole("user");

            $token = JWTAuth::fromUser($user);

            return response()->json([
                "message" => "Usuario registrado exitosamente",
                "token" => $token,
            ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Inicio de sesión",
     *     tags={"Autenticación"},
     *     description="Inicio de sesión del usuario con retorno de token JWT necesario para poder acceder a las rutas de Usuarios y Posts.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Correo electrónico del usuario."),
     *             @OA\Property(property="password", type="string", format="password", example="123456", description="Contraseña del usuario.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inicio de sesión exitoso"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorize",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciales inválidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function login(LoginFormRequest $request): JsonResponse
    {
        $data = $request->validated();
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($data)) {
            return response()->json([
                "status_code" => Response::HTTP_FORBIDDEN,
                "message" => "Credenciales inválidas"
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            "message" => "Inicio de sesión exitoso",
            "token" => $token,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cierre de sesión",
     *     security={{"BearerAuth":{}}},
     *     tags={"Autenticación"},
     *     description="Cierre de sesión del usuario",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            // Invalidar el token
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                "message" => "Cierre de sesión exitoso",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Error al intentar cerrar la sesión",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
