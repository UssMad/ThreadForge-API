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
    public function index(Request $request)
    {
        $blueprints = $request->user()
            ->blueprints()
            ->withCount('generatedPosts')
            ->latest()
            ->get();

        return BlueprintResource::collection($blueprints);
    }

    public function store(StoreBlueprintRequest $request)
    {
        $blueprint = $request->user()->blueprints()->create(
            $request->validated()
        );

        return new BlueprintResource($blueprint);
    }

    public function show(Request $request, Blueprint $blueprint)
    {
        if ($blueprint->user_id !== $request->user()->id) {
            abort(403);
        }

        $blueprint->loadCount('generatedPosts');

        return new BlueprintResource($blueprint);
    }

    public function update(UpdateBlueprintRequest $request, Blueprint $blueprint)
    {
        if ($blueprint->user_id !== $request->user()->id) {
            abort(403);
        }

        $blueprint->update($request->validated());

        $blueprint->loadCount('generatedPosts');

        return new BlueprintResource($blueprint);
    }

    public function destroy(Request $request, Blueprint $blueprint)
    {
        if ($blueprint->user_id !== $request->user()->id) {
            abort(403);
        }

        $blueprint->delete();

        return response()->noContent();
    }
}
