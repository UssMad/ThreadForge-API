<?php

namespace Database\Factories;

use App\Models\Blueprint;
use App\Models\GeneratedPost;
use App\Models\RawContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneratedPostFactory extends Factory
{
    protected $model = GeneratedPost::class;

    public function definition(): array
    {
        return [
            'raw_content_id' => RawContent::factory(),
            'blueprint_id' => Blueprint::factory(),
            'hook_propose' => fake()->sentence(),
            'body_points' => [fake()->sentence(), fake()->sentence()],
            'technical_readability_score' => fake()->numberBetween(50, 100),
            'suggested_hashtags' => [fake()->word(), fake()->word()],
            'tone_compliance_justification' => fake()->sentence(),
            'generated_text' => fake()->paragraph(),
            'status' => 'draft',
        ];
    }
}
