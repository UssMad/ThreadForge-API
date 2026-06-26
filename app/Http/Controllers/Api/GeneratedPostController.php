<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGeneratedPostStatusRequest;
use App\Http\Resources\GeneratedPostResource;
use App\Models\GeneratedPost;

class GeneratedPostController extends Controller
{
    /**
     * List generated posts for the authenticated user.
     *
     * @authenticated
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "hook_propose": "Laravel queues changed how I build APIs.",
     *       "body_points": ["Avoid blocking requests", "Improve response times"],
     *       "technical_readability_score": 90,
     *       "suggested_hashtags": ["Laravel", "PHP"],
     *       "tone_compliance_justification": "Matches professional technical tone.",
     *       "generated_text": "Laravel queues changed how I build APIs. Avoid blocking requests Improve response times",
     *       "status": "draft",
     *       "created_at": "2026-06-25T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $posts = GeneratedPost::whereHas('rawContent', function ($query) {
            $query->where('user_id', request()->user()->id);
        })->with('rawContent', 'blueprint')->latest()->get();

        return GeneratedPostResource::collection($posts);
    }

    /**
     * Show a specific generated post.
     *
     * @authenticated
     *
     * @urlParam generatedPost integer required The post ID. Example: 1
     *
     * @response {
     *   "id": 1,
     *   "hook_propose": "Laravel queues changed how I build APIs.",
     *   "body_points": ["Avoid blocking requests", "Improve response times"],
     *   "technical_readability_score": 90,
     *   "suggested_hashtags": ["Laravel", "PHP"],
     *   "tone_compliance_justification": "Matches professional technical tone.",
     *   "generated_text": "Laravel queues changed how I build APIs. Avoid blocking requests Improve response times",
     *   "status": "draft",
     *   "created_at": "2026-06-25T00:00:00.000000Z"
     * }
     */
    public function show(GeneratedPost $generatedPost)
    {
        $this->authorize('view', $generatedPost);

        return new GeneratedPostResource($generatedPost);
    }

    /**
     * Update the status of a generated post.
     *
     * @authenticated
     *
     * @urlParam generatedPost integer required The post ID. Example: 1
     *
     * @bodyParam status string required New status. Example: posted
     *
     * @response {
     *   "id": 1,
     *   "hook_propose": "Laravel queues changed how I build APIs.",
     *   "body_points": ["Avoid blocking requests", "Improve response times"],
     *   "technical_readability_score": 90,
     *   "suggested_hashtags": ["Laravel", "PHP"],
     *   "tone_compliance_justification": "Matches professional technical tone.",
     *   "generated_text": "Laravel queues changed how I build APIs. Avoid blocking requests Improve response times",
     *   "status": "posted",
     *   "created_at": "2026-06-25T00:00:00.000000Z"
     * }
     */
    public function updateStatus(UpdateGeneratedPostStatusRequest $request, GeneratedPost $generatedPost)
    {
        $this->authorize('update', $generatedPost);

        $generatedPost->update([
            'status' => $request->validated()['status'],
        ]);

        return new GeneratedPostResource($generatedPost);
    }
}
