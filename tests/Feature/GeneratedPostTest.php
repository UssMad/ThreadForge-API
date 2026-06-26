<?php

use App\Models\Blueprint;
use App\Models\GeneratedPost;
use App\Models\RawContent;
use App\Models\User;

test('user can list their generated posts', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $user->id]);
    GeneratedPost::factory()->count(3)->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/posts');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('user can view their own generated post', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $user->id]);
    $post = GeneratedPost::factory()->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/posts/{$post->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $post->id,
            'status' => 'draft',
        ]);
});

test('user cannot view another users post', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $owner->id]);
    $post = GeneratedPost::factory()->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/posts/{$post->id}");

    $response->assertStatus(403);
});

test('user can update post status', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $user->id]);
    $post = GeneratedPost::factory()->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
        'status' => 'draft',
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->patchJson("/posts/{$post->id}/status", [
            'status' => 'posted',
        ]);

    $response->assertStatus(200)
        ->assertJson(['status' => 'posted']);
});

test('user cannot update another users post status', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $owner->id]);
    $post = GeneratedPost::factory()->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->patchJson("/posts/{$post->id}/status", [
            'status' => 'posted',
        ]);

    $response->assertStatus(403);
});
