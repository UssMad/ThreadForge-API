<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RepurposeContentRequest;
use App\Http\Resources\RawContentResource;
use App\Models\Blueprint;
use App\Jobs\ProcessContentGeneration;

class RawContentController extends Controller
{
    public function store(RepurposeContentRequest $request)
    {
        $blueprint = Blueprint::findOrFail($request->blueprint_id);

        if ($blueprint->user_id !== $request->user()->id) {
            abort(403);
        }

        $rawContent = $request->user()->rawContents()->create(
            $request->validated()
        );

        ProcessContentGeneration::dispatch($rawContent);

        return response()->json([
            'message' => 'Content queued successfully',
            'raw_content' => new RawContentResource($rawContent),
        ], 202);
    }
}
