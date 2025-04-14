<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    /**
     * @OA\Schema(
     *     schema="PostResource",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", description="ID del post"),
     *         @OA\Property(property="title", type="string", description="Título del post"),
     *         @OA\Property(property="content", type="string", description="Contenido del post"),
     *         @OA\Property(property="user", ref="#/components/schemas/UserResource", description="Usuario asociado al post")
     *     }
     * )
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'topic' => $this->topic,
            'content' => $this->content,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]
        ];
    }
}
