<?php

namespace App\Providers;

use App\Models\Blueprint;
use App\Models\Conversation;
use App\Models\GeneratedPost;
use App\Policies\BlueprintPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\GeneratedPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Blueprint::class, BlueprintPolicy::class);
        Gate::policy(GeneratedPost::class, GeneratedPostPolicy::class);
        Gate::policy(Conversation::class, ConversationPolicy::class);
    }
}
