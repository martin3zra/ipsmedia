<?php

namespace Database\Seeders;

use App\Enums\EventType;
use App\Models\Achievement;
use App\Models\AchievementUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AchievementUser::query()->delete();
        Achievement::query()->delete();
        Achievement::create(['name' => 'First Comment Written', 'qty' => 1, 'type' => EventType::commentWritten->value]);
        Achievement::create(['name' => '3 Comments Written', 'qty' => 3, 'type' => EventType::commentWritten->value]);
        Achievement::create(['name' => '5 Comments Written', 'qty' => 5, 'type' => EventType::commentWritten->value]);
        Achievement::create(['name' => '10 Comments Written', 'qty' => 10, 'type' => EventType::commentWritten->value]);
        Achievement::create(['name' => '20 Comments Written', 'qty' => 20, 'type' => EventType::commentWritten->value]);

        Achievement::create(['name' => 'First Lesson Watched', 'qty' => 1, 'type' => EventType::lessonWatched->value]);
        Achievement::create(['name' => '5 Lessons Watched', 'qty' => 5, 'type' => EventType::lessonWatched->value]);
        Achievement::create(['name' => '10 Lessons Watched', 'qty' => 10, 'type' => EventType::lessonWatched->value]);
        Achievement::create(['name' => '25 Lessons Watched', 'qty' => 25, 'type' => EventType::lessonWatched->value]);
        Achievement::create(['name' => '50 Lessons Watched', 'qty' => 50, 'type' => EventType::lessonWatched->value]);
    }
}
