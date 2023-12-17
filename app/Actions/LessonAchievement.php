<?php

namespace App\Actions;

use App\Models\Lesson;
use App\Models\User;
use App\Enums\EventType;
use App\Models\Achievement;

class LessonAchievement
{
    public function __construct(
        public Lesson $lesson,
        public User $user)
    {
    }

    public function execute(): void
    {

        $lessonWatched = $this->user->watched()->count();

        $achievement = Achievement::query()
            ->where('type', EventType::lessonWatched->value)
            ->where('qty', $lessonWatched)
            ->first();

        if ($achievement === null) {
            return;
        }

        $recorder = new AchievementRecorder(user: $this->user);
        $recorder->record(achiviement: $achievement);
    }
}
