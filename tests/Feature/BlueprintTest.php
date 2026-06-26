<?php

use App\Models\Blueprint;
use App\Models\User;

test('user can create a blueprint', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/auth/blueprints', [
            'name' => 'Technical Twitter Thread',
            'audience_target' => 'Developers',
            'tone' => 'Professional',
            'max_hashtags' => 3,
            'max_characters' => 280,
            'additional_rules' => ['Use code snippets'],
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'name' => 'Technical Twitter Thread',
            'audience_target' => 'Developers',
            'tone' => 'Professional',
            'max_hashtags' => 3,
            'max_characters' => 280,
            'additional_rules' => ['Use code snippets'],
        ]);
});

test('user can list their blueprints', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    Blueprint::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/auth/blueprints');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('user can view their own blueprint', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/auth/blueprints/{$blueprint->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $blueprint->id,
            'name' => $blueprint->name,
        ]);
});

test('user cannot view another users blueprint', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $owner->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/auth/blueprints/{$blueprint->id}");

    $response->assertStatus(403);
});

test('user can update their own blueprint', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson("/auth/blueprints/{$blueprint->id}", [
            'name' => 'Updated Name',
        ]);

    $response->assertStatus(200)
        ->assertJson(['name' => 'Updated Name']);
});

test('user cannot update another users blueprint', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $owner->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->putJson("/auth/blueprints/{$blueprint->id}", [
            'name' => 'Hacked Name',
        ]);

    $response->assertStatus(403);
});

test('user can delete their own blueprint', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $user->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/auth/blueprints/{$blueprint->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('blueprints', ['id' => $blueprint->id]);
});

test('user cannot delete another users blueprint', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $blueprint = Blueprint::factory()->create(['user_id' => $owner->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->deleteJson("/auth/blueprints/{$blueprint->id}");

    $response->assertStatus(403);
});
