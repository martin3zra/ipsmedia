<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessAchievementUnlocked
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
    public function handle(AchievementUnlocked $event): void
    {
        $achievementsCount = $event->user->achievements()->count();
        $badge = Badge::where('achievements', $achievementsCount)->first();
        if ($badge === null) {
            return;
        }

        $event->user->badges()->attach($badge);
    }
}
