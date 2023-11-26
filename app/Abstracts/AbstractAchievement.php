<?php

declare(strict_types=1);

namespace App\Abstracts;

use App\Models\User;
use App\Models\UserAchievement;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;

abstract class AbstractAchievement {

    /**
     * Unlock an achievement for a user and create a record of it in the database
     *
     * @param User $user
     * @param string $achievementName
     * @param string $achievementType
     * @return UserAchievement
     */
    public function unlockAchievement(User $user, string $achievementName, string $achievementType): UserAchievement {
        $achievement = $user->achievements()->firstOrCreate([
            'name' => $achievementName, 
            'achievement_type' => $achievementType
        ]);
        
        return $achievement;
    }

    /**
     * Check if a lesson achievement exist for a number of comment made by a usert
     *
     * @param integer $lessonCount
     * @return LessonAchievement|null
     */
    public function checkLessonAchievementExist(int $lessonCount): ?LessonAchievement {
        $exist = LessonAchievement::where('lessons', $lessonCount)->first();
        return $exist;
    }

    /**
     * Check if a comment achievement exist for a number of comment made by a usert
     *
     * @param integer $commentCount
     * @return CommentAchievement|null
     */
    public function checkCommentAchievementExist(int $commentCount): ?CommentAchievement {
        $exist = CommentAchievement::where('comments', $commentCount)->first();
        return $exist;
    }
}