<?php

namespace App\AI\Agents;

use App\AI\Tools\GetCampaignRulesTool;
use App\AI\Tools\GetPostHistoryTool;
use App\Models\Conversation;
use App\Models\Message as EloquentMessage;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class GhostwriterAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(
        public ?string $conversationId = null,
        public ?int $postId = null,
    ) {}

    public function instructions(): Stringable|string
    {
        return 'You are ThreadForge Ghostwriter.

Rules:
- Never invent blueprint rules.
- Always use GetCampaignRulesTool when blueprint information is requested.
- Never invent post history.
- Always use GetPostHistoryTool when post information is requested.
- Use concise professional responses.
- Respect technical writing style.';
    }

    public function messages(): iterable
    {
        if ($this->conversationId) {
            return EloquentMessage::where('conversation_id', $this->conversationId)
                ->get()
                ->map(fn (EloquentMessage $msg) => new Message($msg->role, $msg->content))
                ->all();
        }

        return [];
    }

    public function tools(): iterable
    {
        return [
            new GetCampaignRulesTool,
            new GetPostHistoryTool,
        ];
    }

    public function generate(Conversation $conversation, string $message): string
    {
        $this->conversationId = (string) $conversation->id;

        return (string) $this->prompt($message);
    }
}
