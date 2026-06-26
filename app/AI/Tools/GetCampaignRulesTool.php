<?php

namespace App\AI\Tools;

use App\Models\Blueprint;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCampaignRulesTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Fetch campaign rules by campaign ID. Returns the blueprint configuration including audience target, tone, hashtag limits, character limits, and additional rules.';
    }

    public function handle(Request $request): Stringable|string
    {
        $blueprint = Blueprint::findOrFail($request['campaignId']);

        return json_encode([
            'name' => $blueprint->name,
            'audience_target' => $blueprint->audience_target,
            'tone' => $blueprint->tone,
            'max_hashtags' => $blueprint->max_hashtags,
            'max_characters' => $blueprint->max_characters,
            'additional_rules' => $blueprint->additional_rules,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'campaignId' => $schema->integer()
                ->description('The ID of the campaign to fetch rules for.')
                ->min(1)
                ->required(),
        ];
    }
}
