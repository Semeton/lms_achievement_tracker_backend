<?php

declare(strict_types=1);

namespace App\Abstracts;

use App\Models\User;
use App\Models\UserAchievement;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;

abstract class AbstractAchievement {

    public function unlockAchievement(User $user, string $achievementName, string $achievementType): UserAchievement {
        $achievement = $user->achievements()->firstOrCreate([
            'name' => $achievementName, 
            'achievement_type' => $achievementType
        ]);
        
        return $achievement;
    }

    public function checkLessonAchievementExist(int $lessonCount): ?LessonAchievement {
        $exist = LessonAchievement::where('lessons', $lessonCount)->first();
        return $exist;
    }

    public function checkCommentAchievementExist(int $commentCount): ?CommentAchievement {
        $exist = CommentAchievement::where('comments', $commentCount)->first();
        return $exist;
    }
}