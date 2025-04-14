<?php

namespace App\Services;

use App\Http\Requests\UpdatePostFormRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function __construct() {
    }

    public function create($data): Post {
        Log::info('Creating Post in PostService');

        return Post::create([
            'topic' => $data['topic'],
            'content' => $data['content'],
            'user_id' => $data['user_id']
        ]);
    }

    public function findByid($id): Post {
        Log::info("Find Post with id: $id in PostService");

        try {
            return Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Post con ID {$id} no encontrado.");

            throw new \Exception("El Post con ID {$id} no se ha encontrado.");
        }
    }

    public function update(UpdatePostFormRequest $data, $id): Post {
        Log::info("Update Post with id: $id in PostService");

        $post = Post::findOrFail($id);
        $post->update($data->all());

        return $post;
    }

    public function delete($id): void {
        Log::info("Delete Post with id: $id in PostService");
        $post = Post::findOrFail($id);

        $post->delete();
    }

}
