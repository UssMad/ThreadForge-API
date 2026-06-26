<?php

namespace App\Policies;

use App\Models\GeneratedPost;
use App\Models\User;

class GeneratedPostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GeneratedPost $generatedPost): bool
    {
        return $user->id === $generatedPost->rawContent->user_id;
    }

    public function update(User $user, GeneratedPost $generatedPost): bool
    {
        return $user->id === $generatedPost->rawContent->user_id;
    }
}
