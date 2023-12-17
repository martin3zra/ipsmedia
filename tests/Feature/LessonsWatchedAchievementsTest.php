<?php

namespace Tests\Feature;

use App\Enums\EventType;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LessonsWatchedAchievementsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_listen_watched_lesson_and_unlock_first_achievement(): void
    {
        // Arrange
        $this->seed(AchievementSeeder::class);
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        // Act
        $lesson->markLessonWasWatchedBy(user: $user);

        //Assert
        $this->assertEquals(1, $user->watchedLessonAchievements()->count());
    }

    public function test_listen_watched_lesson_and_unlock_remaining_achievements(): void
    {
        // Arrange
        $this->seed(AchievementSeeder::class);
        $user = User::factory()->create();

        // Act && Assert
        // Create 5 lesson and mark it as watched, that should gave us 2 achievements
        $this->markLessonsAsWatched(user: $user, times: 5);
        $this->assertEquals(2, $user->watchedLessonAchievements()->count());

        //Act && Assert
        // Create 22 lesson and mark it as watched, that should gave us 4 achievements
        $this->markLessonsAsWatched(user: $user, times: 22);
        $this->assertEquals(4, $user->watchedLessonAchievements()->count());
    }

    private function markLessonsAsWatched(User $user, int $times = 1): void
    {
        $lessons = Lesson::factory()->times($times)->create();
        $lessons->each(function ($lesson) use ($user) {
            $lesson->markLessonWasWatchedBy(user: $user);
        });
    }
}
