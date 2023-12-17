<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Tests\TestCase;

class AchievementsEndpointTest extends TestCase
{
    public function test_can_display_unlock_and_remaining_achievements_and_badges(): void
    {
        // Arrange
        $this->seed(BadgeSeeder::class);
        $this->seed(AchievementSeeder::class);
        $user = User::factory()->create();

        // mark lesson as watched and unlock first achievement
        $lesson = Lesson::factory()->create();
        $lesson->markLessonWasWatchedBy(user: $user);

        // left a comment and unlock second achievement
        Comment::factory()->for($user)->create();

        // Act
        $response = $this->get("/users/{$user->id}/achievements");

        //Assert
        $response->assertOk();
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                ['name' => 'First Lesson Watched'],
                ['name' => 'First Comment Written'],
            ],
            'next_available_achievements' => [
                '3 Comments Written',
                '5 Lessons Watched',
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 2,
        ]);
    }
}
