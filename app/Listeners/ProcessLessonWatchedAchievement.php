<?php

namespace App\Listeners;

use App\Actions\LessonAchievement;
use App\Events\LessonWatched;

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
        $lessonAchievement = new LessonAchievement(
            lesson: $event->lesson,
            user: $event->user,
        );

        $lessonAchievement->execute();
    }
}
