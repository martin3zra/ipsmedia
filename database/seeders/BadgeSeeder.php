<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\BadgeUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BadgeUser::query()->delete();
        Badge::query()->delete();
        Badge::create(['name' => 'Beginner', 'achievements' => 0]);
        Badge::create(['name' => 'Intermediate', 'achievements' => 4]);
        Badge::create(['name' => 'Advanced', 'achievements' => 8]);
        Badge::create(['name' => 'Master', 'achievements' => 10]);
    }
}
