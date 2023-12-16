<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Events\AchievementUnlocked;
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
        $commentWritten = $user->comments()->count();
        $achievements = [1, 3, 5, 10, 20];

        if (! in_array($commentWritten, $achievements)) {
            return;
        }

        $recorder = new AchievementRecorder(user: $user);
        $recorder->record(
            eventType: EventType::commentWritten,
            achiviement: $commentWritten,
        );
    }
}
