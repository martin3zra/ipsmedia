<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\User;

class ProcessLessonWatchedAchievement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $event->lesson->markLessonWasWatchedBy(user: $event->user);

        $value = $this->resolveAchievement(user: $event->user);
        if ($value === null) {
            return;
        }

        $event->user->achievements()->create([
            'value' => $value,
            'type' => 'lessonWatched',
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
