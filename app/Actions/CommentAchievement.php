<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Models\Comment;
use App\Models\User;

class CommentAchievement
{
    public function __construct(public Comment $comment)
    {
    }

    public function execute(): void
    {
        $user = $this->comment->user;

        $value = $this->resolveAchievement(user: $user);
        if ($value === null) {
            return;
        }

        $user->achievements()->create([
            'value' => $value,
            'type' => EventType::commentWritten->value,
        ]);
    }

    private function resolveAchievement(User $user): ?int
    {
        $commentWritten = $user->comments()->count();
        $achievements = [1, 3, 5, 10, 20];

        if (in_array($commentWritten, $achievements)) {

            return $commentWritten;
        }

        return null;
    }
}
