<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlueprintController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\GeneratedPostController;
use App\Http\Controllers\Api\RawContentController;


Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::apiResource('blueprints', BlueprintController::class);
    });

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/content/repurpose', [RawContentController::class, 'store']);

    Route::get('/posts', [GeneratedPostController::class, 'index']);
    Route::get('/posts/{generatedPost}', [GeneratedPostController::class, 'show']);
    Route::patch('/posts/{generatedPost}/status', [GeneratedPostController::class, 'updateStatus']);

    Route::post('/chat/conversations', [ChatController::class, 'startConversation']);
    Route::post('/chat/messages', [ChatController::class, 'sendMessage']);
    Route::get('/chat/conversations/{conversation}', [ChatController::class, 'history']);
});