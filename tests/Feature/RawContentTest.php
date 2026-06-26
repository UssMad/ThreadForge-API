<?php

use App\Models\Blueprint;
use App\Models\User;

test('user can submit raw content', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/content/repurpose', [
            'blueprint_id' => $blueprint->id,
            'title' => 'Test Title',
            'content' => 'Test content for repurposing.',
        ]);

    $response->assertStatus(202)
        ->assertJsonStructure([
            'message',
            'raw_content' => ['id', 'blueprint_id', 'title', 'content', 'created_at'],
        ])
        ->assertJson([
            'message' => 'Content queued successfully',
            'raw_content' => [
                'blueprint_id' => $blueprint->id,
                'title' => 'Test Title',
            ],
        ]);
});

test('user cannot submit content to another users blueprint', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $owner->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/content/repurpose', [
            'blueprint_id' => $blueprint->id,
            'title' => 'Test Title',
            'content' => 'Test content.',
        ]);

    $response->assertStatus(403);
});
