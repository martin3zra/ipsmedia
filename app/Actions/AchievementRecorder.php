<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;

class AchievementRecorder
{
    public function __construct(public User $user)
    {
    }

    public function record(Achievement $achiviement): void
    {
        $this->user->achievements()->attach([$achiviement->id => [
            'type' => $achiviement->type,
        ]]);

        AchievementUnlocked::dispatch($achiviement->name, $this->user);
    }
}
