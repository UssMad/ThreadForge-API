<?php

use App\Models\Conversation;
use App\Models\GeneratedPost;
use App\Models\RawContent;
use App\Models\User;

test('user can start a conversation', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/chat/conversations');

    $response->assertStatus(201)
        ->assertJsonStructure([
            'conversation' => ['id', 'generated_post_id', 'created_at'],
        ]);

    $this->assertDatabaseHas('conversations', [
        'user_id' => $user->id,
    ]);
});

test('user can start a conversation with a post reference', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $rawContent = RawContent::factory()->create(['user_id' => $user->id]);
    $post = GeneratedPost::factory()->create([
        'raw_content_id' => $rawContent->id,
        'blueprint_id' => $rawContent->blueprint_id,
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/chat/conversations', [
            'generated_post_id' => $post->id,
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'conversation' => [
                'generated_post_id' => $post->id,
            ],
        ]);
});

test('user can view conversation history', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $conversation = Conversation::factory()->create(['user_id' => $user->id]);
    $conversation->messages()->createMany([
        ['role' => 'user', 'content' => 'Hello'],
        ['role' => 'assistant', 'content' => 'Hi there'],
    ]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/chat/conversations/{$conversation->id}");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJson([
            ['role' => 'user', 'content' => 'Hello'],
            ['role' => 'assistant', 'content' => 'Hi there'],
        ]);
});

test('user cannot view another users conversation', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $token = $other->createToken('test')->plainTextToken;

    $conversation = Conversation::factory()->create(['user_id' => $owner->id]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/chat/conversations/{$conversation->id}");

    $response->assertStatus(403);
});
