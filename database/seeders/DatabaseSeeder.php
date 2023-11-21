<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Badge;
use App\Models\Lesson;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        $user = User::factory()
            ->count(3)
            ->create();

        $comments = Comment::factory()
            ->count(20)
            ->create();

        $badges = [
            ['name' => 'Beginner', 'achievement_score' => 0],
            ['name' => 'Intermediate', 'achievement_score' => 4],
            ['name' => 'Advanced', 'achievement_score' => 8],
            ['name' => 'Master', 'achievement_score' => 10],
        ];

        foreach ($badges as $item) {
            Badge::create($item);
        }

        $commentAchievements = [
            ['name' => 'First Comment Written', 'comments' => 1],
            ['name' => '3 Comments Written', 'comments' => 3],
            ['name' => '5 Comments Written', 'comments' => 5],
            ['name' => '10 Comments Written', 'comments' => 10],
            ['name' => '20 Comments Written', 'comments' => 20],
        ];

        foreach ($commentAchievements as $item) {
            CommentAchievement::create($item);
        }

        $lessonAchievements = [
            ['name' => 'First Lesson Watched', 'lessons' => 1],
            ['name' => '5 Lesson Watched', 'lessons' => 5],
            ['name' => '10 Lesson Watched', 'lessons' => 10],
            ['name' => '25 Lesson Watched', 'lessons' => 25],
            ['name' => '50 Lesson Watched', 'lessons' => 50],
        ];

        foreach ($lessonAchievements as $item) {
            LessonAchievement::create($item);
        }
    }
}