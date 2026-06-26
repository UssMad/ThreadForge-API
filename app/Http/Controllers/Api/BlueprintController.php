<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlueprintRequest;
use App\Http\Requests\UpdateBlueprintRequest;
use App\Http\Resources\BlueprintResource;
use App\Models\Blueprint;
use Illuminate\Http\Request;

class BlueprintController extends Controller
{
    /**
     * List all blueprints for the authenticated user.
     *
     * @authenticated
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Technical Twitter Thread",
     *       "audience_target": "Developers",
     *       "tone": "Professional",
     *       "max_hashtags": 3,
     *       "max_characters": 280,
     *       "additional_rules": ["Use code snippets"],
     *       "generated_posts_count": 5,
     *       "created_at": "2026-06-25T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $blueprints = $request->user()
            ->blueprints()
            ->withCount('generatedPosts')
            ->latest()
            ->get();

        return BlueprintResource::collection($blueprints);
    }

    /**
     * Create a new blueprint.
     *
     * @authenticated
     *
     * @bodyParam name string required Blueprint name. Example: Technical Twitter Thread
     * @bodyParam audience_target string required Target audience. Example: Developers
     * @bodyParam tone string required Writing tone. Example: Professional
     * @bodyParam max_hashtags integer required Maximum hashtags. Example: 3
     * @bodyParam max_characters integer required Maximum characters per post. Example: 280
     * @bodyParam additional_rules array Optional additional rules. Example: ["Use code snippets"]
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "Technical Twitter Thread",
     *   "audience_target": "Developers",
     *   "tone": "Professional",
     *   "max_hashtags": 3,
     *   "max_characters": 280,
     *   "additional_rules": ["Use code snippets"],
     *   "generated_posts_count": 0,
     *   "created_at": "2026-06-25T00:00:00.000000Z"
     * }
     */
    public function store(StoreBlueprintRequest $request)
    {
        $blueprint = $request->user()->blueprints()->create(
            $request->validated()
        );

        return new BlueprintResource($blueprint);
    }

    /**
     * Show a specific blueprint.
     *
     * @authenticated
     *
     * @urlParam blueprint integer required The blueprint ID. Example: 1
     *
     * @response {
     *   "id": 1,
     *   "name": "Technical Twitter Thread",
     *   "audience_target": "Developers",
     *   "tone": "Professional",
     *   "max_hashtags": 3,
     *   "max_characters": 280,
     *   "additional_rules": ["Use code snippets"],
     *   "generated_posts_count": 5,
     *   "created_at": "2026-06-25T00:00:00.000000Z"
     * }
     */
    public function show(Blueprint $blueprint)
    {
        $this->authorize('view', $blueprint);

        $blueprint->loadCount('generatedPosts');

        return new BlueprintResource($blueprint);
    }

    /**
     * Update a blueprint.
     *
     * @authenticated
     *
     * @urlParam blueprint integer required The blueprint ID. Example: 1
     *
     * @bodyParam name string Blueprint name. Example: Technical Twitter Thread
     * @bodyParam audience_target string Target audience. Example: Developers
     * @bodyParam tone string Writing tone. Example: Professional
     * @bodyParam max_hashtags integer Maximum hashtags. Example: 3
     * @bodyParam max_characters integer Maximum characters per post. Example: 280
     * @bodyParam additional_rules array Additional rules. Example: ["Use code snippets"]
     *
     * @response {
     *   "id": 1,
     *   "name": "Technical Twitter Thread",
     *   "audience_target": "Developers",
     *   "tone": "Professional",
     *   "max_hashtags": 3,
     *   "max_characters": 280,
     *   "additional_rules": ["Use code snippets"],
     *   "generated_posts_count": 5,
     *   "created_at": "2026-06-25T00:00:00.000000Z"
     * }
     */
    public function update(UpdateBlueprintRequest $request, Blueprint $blueprint)
    {
        $this->authorize('update', $blueprint);

        $blueprint->update($request->validated());

        $blueprint->loadCount('generatedPosts');

        return new BlueprintResource($blueprint);
    }

    /**
     * Delete a blueprint.
     *
     * @authenticated
     *
     * @urlParam blueprint integer required The blueprint ID. Example: 1
     *
     * @response 204
     */
    public function destroy(Blueprint $blueprint)
    {
        $this->authorize('delete', $blueprint);

        $blueprint->delete();

        return response()->noContent();
    }
}
