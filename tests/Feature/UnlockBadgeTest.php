<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UnlockBadgeTest extends TestCase
{

    public function test_listen_achievements_and_unlock_badges(): void
    {
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
        // Arrange
        $user = User::factory()->create();

        // Act && Assert
        $this->assertEquals(0, $user->badges()->count());
        $this->assertEquals('Beginner', $user->latestBadge()->name);

        // Left +2 comments, should still have 1 achievements
        $this->writeComments(user: $user, times: 2);
        $this->assertEquals(1, $user->commentAchievements()->count());

        // Beginner Badged: Because it doesn't have 4 achiviements
        $this->assertEquals(0, $user->badges()->count());

        // Create 5 lesson and mark it as watched, that should gave us 2 achievements
        $this->markLessonsAsWatched(user: $user, times: 5);
        $this->assertEquals(2, $user->watchedLessonAchievements()->count());

        // // Left +1 comments, should gave us 2 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertEquals(2, $user->commentAchievements()->count());

        // Intermediate badge: when the user have 4 achiviements it unlock the intermediate badge
        $this->assertEquals(4, $user->achievements()->count());
        $this->assertEquals(1, $user->badges()->count());
        $this->assertEquals('Intermediate', $user->latestBadge()->name);
    }

    public function test_listen_achievements_and_unlock_advance_badge(): void
    {

        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
        // Arrange
        $user = User::factory()->create();

        $this->assertEquals(0, $user->watchedLessonAchievements()->count());
        $this->assertEquals(0, $user->commentAchievements()->count());
        $this->assertEquals(0, $user->badges()->count());
        $this->assertEquals('Beginner', $user->latestBadge()->name);

        // Create 26 lesson and mark it as watched, that should gave us 4 achievements
        $this->markLessonsAsWatched(user: $user, times: 26);
        $this->assertEquals(4, $user->fresh()->watchedLessonAchievements()->count());

        // Left 20 comments, should still have 5 achievements
        $this->writeComments(user: $user, times: 20);
        $this->assertEquals(5, $user->fresh()->commentAchievements()->count());

        // Advanced: 8 Achievements
        $this->assertEquals(2, $user->fresh()->badges()->count());
        $this->assertEquals('Advanced', $user->latestBadge()->name);

        // Create 26 lesson and mark it as watched, that should gave us 4 achievements
        $this->markLessonsAsWatched(user: $user, times: 26);
        $this->assertEquals(5, $user->fresh()->watchedLessonAchievements()->count());

        // Left 20 comments, should still have 5 achievements
        $this->writeComments(user: $user, times: 20);
        $this->assertEquals(5, $user->fresh()->commentAchievements()->count());

        // Master: 10 Achievements
        $this->assertEquals(10, $user->fresh()->achievements()->count());
        $this->assertEquals(3, $user->fresh()->badges()->count());
        $this->assertEquals('Master', $user->latestBadge()->name);
    }

    private function writeComments(User $user, int $times = 1): void
    {
        Comment::factory()
            ->count($times)
            ->for($user)
            ->create();
    }

    private function markLessonsAsWatched(User $user, int $times = 1): void
    {
        $lessons = Lesson::factory()->times($times)->create();
        $lessons->each(function ($lesson) use ($user) {
            $lesson->markLessonWasWatchedBy(user: $user);
        });
    }
}
