<?php

namespace App\AI\Tools;

use App\Models\GeneratedPost;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetPostHistoryTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Fetch generated post history by post ID. Returns the hook propose, generated text, status, and creation date.';
    }

    public function handle(Request $request): Stringable|string
    {
        $post = GeneratedPost::findOrFail($request['postId']);

        return json_encode([
            'hook_propose' => $post->hook_propose,
            'generated_text' => $post->generated_text,
            'status' => $post->status,
            'created_at' => $post->created_at,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'postId' => $schema->integer()
                ->description('The ID of the generated post to fetch history for.')
                ->min(1)
                ->required(),
        ];
    }
}
