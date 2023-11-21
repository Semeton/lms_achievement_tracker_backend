<?php

namespace App\Abstracts;

use App\Models\User;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;

abstract class AbstractAchievement {

    public function unlockAchievement(User $user, String $achievementName, String $achievementType){
        $achievement = $user->achievements()->firstOrCreate([
            'name' => $achievementName, 
            'achievement_type' => $achievementType
        ]);
        
        return $achievement;
    }

    public function checkLessonAchievementExist($lessonCount){
        $exist = LessonAchievement::where('lessons', $lessonCount)->first();
        return $exist;
    }

    public function checkCommentAchievementExist($commentCount){
        $exist = CommentAchievement::where('comments', $commentCount)->first();
        return $exist;
    }
}