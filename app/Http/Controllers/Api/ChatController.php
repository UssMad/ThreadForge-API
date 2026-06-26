<?php

namespace App\Http\Controllers\Api;

use App\AI\Agents\GhostwriterAgent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Http\Requests\StartConversationRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Start a new conversation.
     *
     * @authenticated
     *
     * @bodyParam generated_post_id integer Optional generated post ID to associate. Example: 1
     *
     * @response 201 {
     *   "conversation": {
     *     "id": 1,
     *     "generated_post_id": 1,
     *     "created_at": "2026-06-25T00:00:00.000000Z"
     *   }
     * }
     */
    public function startConversation(StartConversationRequest $request)
    {
        $conversation = $request->user()->conversations()->create(
            $request->validated()
        );

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'generated_post_id' => $conversation->generated_post_id,
                'created_at' => $conversation->created_at,
            ],
        ], 201);
    }

    /**
     * Send a message in a conversation.
     *
     * @authenticated
     *
     * @bodyParam conversation_id integer required The conversation ID. Example: 1
     * @bodyParam message string required The message text. Example: What are the campaign rules?
     *
     * @response {
     *   "conversation": {
     *     "id": 1,
     *     "generated_post_id": 1,
     *     "created_at": "2026-06-25T00:00:00.000000Z"
     *   },
     *   "assistant_message": {
     *     "id": 2,
     *     "role": "assistant",
     *     "content": "Here are the campaign rules...",
     *     "created_at": "2026-06-25T00:00:00.000000Z"
     *   }
     * }
     */
    public function sendMessage(ChatRequest $request)
    {
        $conversation = Conversation::findOrFail($request->conversation_id);

        $this->authorize('view', $conversation);

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        $agent = new GhostwriterAgent(
            conversationId: (string) $conversation->id,
        );

        $response = $agent->generate($conversation, $request->message);

        $assistantMessage = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $response,
        ]);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'generated_post_id' => $conversation->generated_post_id,
                'created_at' => $conversation->created_at,
            ],
            'assistant_message' => new MessageResource($assistantMessage),
        ]);
    }

    /**
     * Get conversation history.
     *
     * @authenticated
     *
     * @urlParam conversation integer required The conversation ID. Example: 1
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "role": "user",
     *       "content": "What are the campaign rules?",
     *       "created_at": "2026-06-25T00:00:00.000000Z"
     *     },
     *     {
     *       "id": 2,
     *       "role": "assistant",
     *       "content": "Here are the campaign rules...",
     *       "created_at": "2026-06-25T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function history(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $conversation->load('messages');

        return MessageResource::collection($conversation->messages);
    }
}
