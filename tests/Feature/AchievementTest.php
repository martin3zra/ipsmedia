<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_listen_watched_lesson_and_unlock_first_achievement(): void
    {
        // Arrange
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        // Act
        LessonWatched::dispatch($lesson, $user);

        //Assert
        $this->assertDatabaseHas(Achievement::class, [
            'user_id' => $user->id,
            'type' => 'lessonWatched',
            'value' => 1,
        ]);
    }

    public function test_listen_watched_lesson_and_unlock_remaining_achievements(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act && Assert
        // Create 5 lesson and mark it as watched, that should gave us 2 achievements
        $this->markLessonsAsWatched(user: $user, times: 5);
        $this->assertDatabaseCount(Achievement::class, 2);

        //Act && Assert
        // Create 22 lesson and mark it as watched, that should gave us 4 achievements
        $this->markLessonsAsWatched(user: $user, times: 22);
        $this->assertDatabaseCount(Achievement::class, 4);

        $this->assertDatabaseHas(Achievement::class, [
            'user_id' => $user->id,
            'type' => 'lessonWatched',
            'value' => 5,
        ]);
    }

    private function markLessonsAsWatched(User $user, int $times = 1): void
    {
        $lessons = Lesson::factory()->times($times)->create();
        $lessons->each(function ($lesson) use ($user) {
            LessonWatched::dispatch($lesson, $user);
        });
    }
}
