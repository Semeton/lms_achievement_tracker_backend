<?php

namespace App\Services;

use App\Abstracts\AbstractAchievement;
use Exception;
use App\Models\User;

class AchievementService extends AbstractAchievement {
    protected $event;

    public function __construct(EventService $event)
    {
        $this->event = $event;
    }

    public function getUserAchievements(User $user, $type = 'all')
    {
        if($type == 'comment'){

        }else if($type == 'comment'){
            $unlockedAchievements = $user->achievements()->where('achievement_type', 'lesson')->pluck('name');
        }else{
            $unlockedAchievements = $user->achievements()->pluck('name');
        }
        return $unlockedAchievements;
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