<?php

namespace App\Services;

use App\Exceptions\PostNotFoundException;
use App\Models\Post;
use App\Utils\Pagination;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function __construct() {
    }

    public function list(Pagination $pagination): LengthAwarePaginator {
        Log::info("Retrieving list of Posts in PostService");

        return Post::with('user')
            ->paginate($pagination->getPerPage(), ['*'], 'page', $pagination->getCurrentPage());
    }

    public function create($data): Post {
        Log::info("Creating Post in PostService");

        return Post::create([
            "topic" => $data["topic"],
            "content" => $data["content"],
            "user_id" => $data["user_id"]
        ]);
    }

    public function findByid($id): Post {
        Log::info("Retrieving Post with id {$id} in PostService");

        try {
            return Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Post with id $id Not Found.");

            throw new PostNotFoundException($id);
        }
    }

    public function update($data, $id): Post {
        Log::info("Updating Post with id {$id} in PostService");

        try {
            $post = Post::findOrFail($id);
            $post->update($data);

            return $post;
        } catch (ModelNotFoundException $e) {
            Log::error("Post with id $id Not Found.");

            throw new PostNotFoundException($id);
        }
    }

    public function delete($id): void {
        Log::info("Deleting Post with id $id in PostService");

        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Post with id $id Not Found.");

            throw new PostNotFoundException($id);
        }

        $post->delete();
    }

}
