<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     type="object",
     *     description="Esquema del usuario asociado a un post",
     *     @OA\Property(property="id", type="integer", description="ID del usuario"),
     *     @OA\Property(property="name", type="string", description="Nombre del usuario"),
     *     @OA\Property(property="email", type="string", description="Correo electrónico del usuario")
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
