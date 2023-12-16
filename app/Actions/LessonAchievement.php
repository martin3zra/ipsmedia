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

        $value = $this->resolveAchievement(user: $this->user);
        if ($value === null) {
            return;
        }

        $this->user->achievements()->create([
            'value' => $value,
            'type' => EventType::lessonWatched->value,
        ]);
    }

    private function resolveAchievement(User $user): ?int
    {
        $lessonWatched = $user->watched()->count();
        $achievements = [1, 5, 10, 25, 50];

        if (in_array($lessonWatched, $achievements)) {

            return $lessonWatched;
        }

        return null;
    }

}
