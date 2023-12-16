<?php

namespace App\Actions;

use App\Models\Lesson;
use App\Models\User;
use App\Enums\EventType;

class LessonAchievement
{
    public function __construct(
        public Lesson $lesson,
        public User $user)
    {
    }

    public function execute(): void
    {

        $this->lesson->markLessonWasWatchedBy(user: $this->user);

        $lessonWatched = $this->user->watched()->count();
        $achievements = [1, 5, 10, 25, 50];

        if (! in_array($lessonWatched, $achievements)) {
            return;
        }

        $recorder = new AchievementRecorder(user: $this->user);
        $recorder->record(
            eventType: EventType::lessonWatched,
            achiviement: $lessonWatched,
        );
    }
}
