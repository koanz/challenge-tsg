<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de usuarios en la aplicación",
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
     *         response=200,
     *         description="Registro exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registro exitoso"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'token' => $token,
            ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Inicia sesión en la aplicación",
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
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Inicio de sesión exitoso"),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciales inválidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Credenciales inválidas'
            ], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
        ]);
    }
}
