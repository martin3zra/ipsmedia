<?php

namespace App\Enums;

enum EventType: string
{
    case lessonWatched = 'lessonWatched';
    case commentWritten = 'commentWritten';

    public function resolveAchievementName(int $achievement): string
    {
        if ($achievement === 1) {
            return ($this === EventType::commentWritten)
                ?"First Comment Written"
                :"First Lesson Watched";
        }

        return ($this === EventType::commentWritten)
            ? "{$achievement} Comments Written"
            : "{$achievement} Lessons Watched";
    }
}
