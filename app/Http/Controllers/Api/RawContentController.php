<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RepurposeContentRequest;
use App\Http\Resources\RawContentResource;
use App\Jobs\ProcessContentGeneration;
use App\Models\Blueprint;

class RawContentController extends Controller
{
    /**
     * Submit raw content for AI processing.
     *
     * @authenticated
     *
     * @bodyParam blueprint_id integer required The blueprint ID to use. Example: 1
     * @bodyParam title string required Content title. Example: Laravel Queue Best Practices
     * @bodyParam content string required Raw content to repurpose. Example: Laravel queues provide a unified API across a variety of different queue backends...
     *
     * @response 202 {
     *   "message": "Content queued successfully",
     *   "raw_content": {
     *     "id": 1,
     *     "blueprint_id": 1,
     *     "title": "Laravel Queue Best Practices",
     *     "content": "Laravel queues provide a unified API...",
     *     "created_at": "2026-06-25T00:00:00.000000Z"
     *   }
     * }
     */
    public function store(RepurposeContentRequest $request)
    {
        $blueprint = Blueprint::findOrFail($request->blueprint_id);

        $this->authorize('view', $blueprint);

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
