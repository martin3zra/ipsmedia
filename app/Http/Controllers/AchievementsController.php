<?php

namespace App\Http\Controllers;

use App\Actions\UserAchievement;
use App\Enums\EventType;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {

        $userAchievement = new UserAchievement(user: $user);
        $userAchievement->resolveAchievements();

        return response()->json([
            'unlocked_achievements' => $userAchievement->unlockedAchievements,
            'next_available_achievements' => $userAchievement->nextAvailableAchievements,
            'current_badge' => $userAchievement->currentBadge,
            'next_badge' => $userAchievement->nextBadge,
            'remaing_to_unlock_next_badge' => $userAchievement->remaingToUnlockNextBadge,
        ]);
    }
}
