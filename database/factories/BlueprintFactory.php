<?php

namespace Database\Factories;

use App\Models\Blueprint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlueprintFactory extends Factory
{
    protected $model = Blueprint::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(3),
            'audience_target' => fake()->word(),
            'tone' => fake()->word(),
            'max_hashtags' => fake()->numberBetween(1, 5),
            'max_characters' => fake()->randomElement([140, 280, 500]),
            'additional_rules' => [fake()->sentence()],
        ];
    }
}
