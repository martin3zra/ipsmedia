<?php

namespace App\Actions;

use App\Enums\EventType;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserAchievement
{
    public array $unlockedAchievements = [];
    public array $nextAvailableAchievements = [];
    public string $currentBadge;
    public string $nextBadge;
    public int $remaingToUnlockNextBadge = 0;
    public Collection $achievements;

    public function __construct(public User $user)
    {
    }

    public function resolveAchievements()
    {
        $this->achievements = $this->user->achievements;
        $this->computeUnlockedAchiviements();
        $this->resolveCurrentBadge();
        $this->resolveNextBadge();
        $this->resolveNextCommentAchievement();
        $this->resolveNextLessonWatchedAchievement();
    }

    private function resolveCurrentBadge(): void
    {
        $this->currentBadge = $this->user->latestBadge()->name;
    }

    private function resolveNextBadge(): void
    {
        $badge = $this->user->latestBadge()->resolveNext();
        $this->nextBadge = $badge->name;

        $this->resolveRemainingToUnlockNextBadge(badge: $badge);
    }

    private function resolveRemainingToUnlockNextBadge(Badge $badge)
    {
        $this->remaingToUnlockNextBadge = ($badge->achievements - $this->achievements->count());
    }

    private function computeUnlockedAchiviements()
    {
        $this->unlockedAchievements = $this->achievements->map(function ($achievement) {
            return [
                'name' => $achievement->name,
            ];
        })->toArray();
    }

    private function resolveNextCommentAchievement(): void
    {
        $this->resolveNextAvailableAchiviement(
            achievement: $this->user->commentAchievements->last(),
            eventType: EventType::commentWritten,
        );
    }

    private function resolveNextLessonWatchedAchievement(): void
    {
        $this->resolveNextAvailableAchiviement(
            achievement: $this->user->watchedLessonAchievements->last(),
            eventType: EventType::lessonWatched,
        );
    }

    private function resolveNextAvailableAchiviement(?Achievement $achievement, EventType $eventType): void
    {
        if ($achievement === null) {
            $achievement = Achievement::where('type', $eventType::lessonWatched->value)->first();
        } else {
            $achievement = Achievement::where('id', '>', $achievement->id)->where('type',$eventType->value)->first();
        }

        $this->nextAvailableAchievements[] = $achievement->name;
    }
}
