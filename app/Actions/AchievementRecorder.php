<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Events\AchievementUnlocked;
use App\Models\User;

class AchievementRecorder
{
    public function __construct(public User $user)
    {
    }

    public function record(EventType $eventType, int $achiviement): void
    {
        $this->user->achievements()->create([
            'value' => $achiviement,
            'type' => $eventType->value,
        ]);

        $achievementName = $eventType->resolveAchievementName($achiviement);
        AchievementUnlocked::dispatch($achievementName, $this->user);
    }
}
