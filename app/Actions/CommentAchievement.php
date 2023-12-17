<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Models\Achievement;
use App\Models\Comment;

class CommentAchievement
{
    public function __construct(public Comment $comment)
    {
    }

    public function execute(): void
    {
        $user = $this->comment->user;
        $commentWritten = $user->comments()->count();
        $achievement = Achievement::query()
            ->where('type', EventType::commentWritten->value)
            ->where('qty', $commentWritten)
            ->first();

        if ($achievement === null) {
            return;
        }

        $recorder = new AchievementRecorder(user: $user);
        $recorder->record(achiviement: $achievement);
    }
}
