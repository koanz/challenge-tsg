<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostFormRequest;
use App\Http\Requests\UpdatePostFormRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PostRestController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(): JsonResponse {
        $posts = Post::with('user')->paginate(10);

        return response()->json(PostResource::collection($posts));
    }

    public function create(CreatePostFormRequest $request): JsonResponse {
        Log::info("Store method in PostRestController");
        $data = $request->validated();

        return response()->json(new PostResource($this->postService->create($data)), 201);
    }

    public function find($id): JsonResponse {
        Log::info("Find/Read Post with id: $id in PostRestController");

        try {
            $post = $this->postService->findById($id);
            return response()->json(new PostResource($post));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(UpdatePostFormRequest $request, $id): JsonResponse {
        Log::info('Update Post method in PostRestController');
        $post = $this->postService->update($request, $id);

        return response()->json(new PostResource($post));
    }

    public function delete($id): JsonResponse {
        Log::info('Delete Post method in PostRestController');
        $this->postService->delete($id);
        $message['message'] = "Se ha eliminado exitosamente";

        return response()->json($message);
    }
}
