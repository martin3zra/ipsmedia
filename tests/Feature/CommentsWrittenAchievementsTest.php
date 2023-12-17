<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CommentsWrittenAchievementsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_listen_comment_written_and_unlock_first_achievement(): void
    {
        // Arrange && Act
        $this->seed(AchievementSeeder::class);
        $comment = Comment::factory()->create();

        //Assert
        $this->assertEquals(1, $comment->user->commentAchievements()->count());
    }

    public function test_listen_comment_written_and_unlock_remaining_achievements(): void
    {
        // Arrange
        $this->seed(AchievementSeeder::class);
        $user = User::factory()->create();

        // Act && Assert
        // Left +2 comments, should still have 1 achievements
        $this->writeComments(user: $user, times: 2);
        $this->assertEquals(1, $user->commentAchievements()->count());

        // Left +1 comments, should gave us 2 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertEquals(2, $user->commentAchievements()->count());

        // Left +1 comments, should gave us 2 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertEquals(2, $user->commentAchievements()->count());

        // Left +4 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 4);
        $this->assertEquals(3, $user->commentAchievements()->count());

        // Left +7 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 7);
        $this->assertEquals(4, $user->commentAchievements()->count());

        // Left +1 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertEquals(4, $user->commentAchievements()->count());

        // Left +7 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 7);
        $this->assertEquals(5, $user->commentAchievements()->count());
    }

    private function writeComments(User $user, int $times = 1): void
    {
        Comment::factory()
            ->count($times)
            ->for($user)
            ->create();
    }
}
