<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGeneratedPostStatusRequest;
use App\Http\Resources\GeneratedPostResource;
use App\Models\GeneratedPost;

class GeneratedPostController extends Controller
{
    public function index()
    {
        $posts = GeneratedPost::whereHas('rawContent', function ($query) {
            $query->where('user_id', request()->user()->id);
        })->with('rawContent', 'blueprint')->latest()->get();

        return GeneratedPostResource::collection($posts);
    }

    public function show(GeneratedPost $generatedPost)
    {
        if ($generatedPost->rawContent->user_id !== request()->user()->id) {
            abort(403);
        }

        return new GeneratedPostResource($generatedPost);
    }

    public function updateStatus(UpdateGeneratedPostStatusRequest $request, GeneratedPost $generatedPost)
    {
        if ($generatedPost->rawContent->user_id !== $request->user()->id) {
            abort(403);
        }

        $generatedPost->update([
            'status' => $request->validated()['status'],
        ]);

        return new GeneratedPostResource($generatedPost);
    }
}
