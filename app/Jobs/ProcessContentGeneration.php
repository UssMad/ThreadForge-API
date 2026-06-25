<?php

namespace App\Jobs;

use App\Models\GeneratedPost;
use App\Models\RawContent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;

class ProcessContentGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public RawContent $rawContent;

    public function __construct(RawContent $rawContent)
    {
        $this->rawContent = $rawContent;
    }

    public function handle(): void
    {
        $rawContent = $this->rawContent;

        $response = [
            'hook_propose' => 'Laravel queues changed how I build APIs.',
            'body_points' => [
                'Avoid blocking requests',
                'Improve response times',
                'Scale heavy workloads',
            ],
            'technical_readability_score' => 90,
            'suggested_hashtags' => [
                'Laravel',
                'PHP',
            ],
            'tone_compliance_justification' => 'Matches professional technical tone.',
        ];

        $validated = Validator::make($response, [
            'hook_propose' => 'required|string|max:280',
            'body_points' => 'required|array',
            'technical_readability_score' => 'required|integer|min:0|max:100',
            'suggested_hashtags' => 'required|array',
            'tone_compliance_justification' => 'required|string',
        ])->validate();

        GeneratedPost::create([
            'raw_content_id' => $rawContent->id,
            'blueprint_id' => $rawContent->blueprint_id,
            'hook_propose' => $validated['hook_propose'],
            'body_points' => $validated['body_points'],
            'technical_readability_score' => $validated['technical_readability_score'],
            'suggested_hashtags' => $validated['suggested_hashtags'],
            'tone_compliance_justification' => $validated['tone_compliance_justification'],
            'generated_text' => $validated['hook_propose'].' '.implode(' ', $validated['body_points']),
            'status' => 'draft',
        ]);
    }
}
