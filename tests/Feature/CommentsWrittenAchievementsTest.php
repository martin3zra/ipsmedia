<?php

namespace Tests\Feature;

use App\Enums\EventType;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CommentsWrittenAchievementsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_listen_comment_written_and_unlock_first_achievement(): void
    {
        // Arrange && Act
        $comment = Comment::factory()->create();

        //Assert
        $this->assertDatabaseHas(Achievement::class, [
            'user_id' => $comment->user->id,
            'type' => EventType::commentWritten->value,
            'value' => 1,
        ]);
    }

    public function test_listen_comment_written_and_unlock_remaining_achievements(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act && Assert
        // Left +2 comments, should still have 1 achievements
        $this->writeComments(user: $user, times: 2);
        $this->assertDatabaseCount(Achievement::class, 1);

        // Left +1 comments, should gave us 2 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertDatabaseCount(Achievement::class, 2);

        // Left +1 comments, should gave us 2 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertDatabaseCount(Achievement::class, 2);

        // Left +4 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 4);
        $this->assertDatabaseCount(Achievement::class, 3);

        // Left +7 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 7);
        $this->assertDatabaseCount(Achievement::class, 4);

        // Left +1 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 1);
        $this->assertDatabaseCount(Achievement::class, 4);

        // Left +7 comments, should gave us 3 achievements
        $this->writeComments(user: $user, times: 7);
        $this->assertDatabaseCount(Achievement::class, 5);
    }

    private function writeComments(User $user, int $times = 1): void
    {
        Comment::factory()
            ->count($times)
            ->for($user)
            ->create();
    }
}
