<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlueprintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'audience_target' => $this->audience_target,
            'tone' => $this->tone,
            'max_hashtags' => $this->max_hashtags,
            'max_characters' => $this->max_characters,
            'additional_rules' => $this->additional_rules,
            'generated_posts_count' => $this->whenCounted('generatedPosts'),
            'created_at' => $this->created_at,
        ];
    }
}
