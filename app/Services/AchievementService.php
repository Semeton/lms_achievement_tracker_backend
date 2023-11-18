<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;
use App\Abstracts\AbstractAchievement;

class AchievementService extends AbstractAchievement {
    protected $event;

    public function __construct(EventService $event)
    {
        $this->event = $event;
    }

    public function getUserAchievements(User $user, $type = '')
    {
        if($type == 'comment'){
            $unlockedAchievements = $user->achievements()->where('achievement_type', $type)->pluck('name');
        }else if($type == 'lesson'){
            $unlockedAchievements = $user->achievements()->where('achievement_type', $type)->pluck('name');
        }else{
            $unlockedAchievements = $user->achievements()->pluck('name');
        }
        return $unlockedAchievements;
    }

    public function getNextLessonAchievements(User $user)
    {
        $lessonAchievement = $this->getUserAchievements($user, 'lesson');
        if(count($lessonAchievement) > 0){
            $lastLessonAchievement = $lessonAchievement->last();
            $lastLessonAchievementId = LessonAchievement::where('name', $lastLessonAchievement['name'])->first();
            $nextLessonAchievement = LessonAchievement::where('id', $lastLessonAchievementId['id'] + 1)->get()->first()['name'] ?? '';
        }else{
            $nextLessonAchievement = "First Lesson Watched";
        }
        
        return $nextLessonAchievement;
    }

    public function getNextCommentAchievement(User $user)
    {
        $commentAchievement = $this->getUserAchievements($user, 'comment');
        if(count($commentAchievement) > 0){
            $lastCommentAchievement = $commentAchievement->last();
            $lastLessonAchievementId = CommentAchievement::where('name', $lastCommentAchievement['name'])->first();
            $nextCommentAchievement = CommentAchievement::where('id', $lastLessonAchievementId['id'] + 1)->get()->first()['name'] ?? '';
        }else{
            $nextCommentAchievement = "First Comment Written";
        }

        return $nextCommentAchievement;
    }
    
    public function unlockLessonAchievement($lessonCount, User $user)
    {
        try{
            $achievementExist = $this->checkLessonAchievementExist($lessonCount);
            if($achievementExist){
                $achievement  = $this->unlockAchievement($user, $achievementExist['name'], 'lesson');
                $this->event->triggerAchievementEvent($achievement['name'], $user);
                info(['Achievement Unlocked', $lessonCount, $achievement['name']]);
            }else{
                return "No new achievement unlocked";
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function unlockCommentAchievement($commentCount, User $user)
    {
        try{
            $achievementExist = $this->checkCommentAchievementExist($commentCount);
            if($achievementExist){
                $achievement  = $this->unlockAchievement($user, $achievementExist['name'], 'lesson');
                $this->event->triggerAchievementEvent($achievement['name'], $user);
                info(['Achievement Unlocked', $commentCount, $achievement['name']]);
            }else{
                return "No new achievement unlocked";
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}