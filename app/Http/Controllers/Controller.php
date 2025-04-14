<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *     title="API Challenge TSG",
 *     version="1.0",
 *     description="Documentación para los endpoints del Challenge TSG",
 *     @OA\Contact(
 *         email="cristiananzawa@gmail.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Introduce tu token JWT para autenticar las solicitudes"
 * )
 */
abstract class Controller
{
    //
}
