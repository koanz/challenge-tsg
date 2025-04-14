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
    // Registro de usuarios
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
//        } catch (QueryException $exception) {
//            // Verificar si el error es por violación de clave única
//            if ($exception->errorInfo[1] == '23505') { // Código MySQL para claves únicas duplicadas
//                return response()->json([
//                    'error' => 'El correo electrónico ya está registrado.',
//                ], 409); // Código HTTP 409: Conflicto
//            }
//
//            // Manejar otros tipos de excepciones
//            return response()->json([
//                'error' => 'Ocurrió un error al registrar el usuario.',
//            ], 500);
//        }
    }

    // Inicio de sesión
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
        ]);
    }
}
