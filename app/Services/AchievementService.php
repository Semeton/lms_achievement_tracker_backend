<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;
use App\Abstracts\AbstractAchievement;

class AchievementService extends AbstractAchievement {
    
    protected EventService $event;

    public function __construct(EventService $event)
    {
        $this->event = $event;
    }

    public function getUserAchievements(User $user, string $type = ''): array
    {
        if($type == 'comment'){
            $unlockedAchievements = $user->achievements()->where('achievement_type', $type)->pluck('name')->toArray();
        }else if($type == 'lesson'){
            $unlockedAchievements = $user->achievements()->where('achievement_type', $type)->pluck('name')->toArray();
        }else{
            $unlockedAchievements = $user->achievements()->pluck('name')->toArray();
        }
        return $unlockedAchievements;
    }

    public function getNextLessonAchievements(User $user): string
    {
        $lessonAchievement = $this->getUserAchievements($user, 'lesson');
        if(count($lessonAchievement) > 0){
            $lastLessonAchievement = $lessonAchievement[array_key_last($lessonAchievement)];
            $lastLessonAchievementId = LessonAchievement::where('name', $lastLessonAchievement)->first();
            $nextLessonAchievement = LessonAchievement::where('id', $lastLessonAchievementId['id'] + 1)->first()['name'] ?? '';
        }else{
            $nextLessonAchievement = "First Lesson Watched";
        }
        
        return $nextLessonAchievement;
    }

    public function getNextCommentAchievement(User $user): string
    {
        $commentAchievement = $this->getUserAchievements($user, 'comment');
        if(count($commentAchievement) > 0){
            $lastCommentAchievement = $commentAchievement[array_key_last($commentAchievement)];
            $lastLessonAchievementId = CommentAchievement::where('name', $lastCommentAchievement)->first();
            $nextCommentAchievement = CommentAchievement::where('id', $lastLessonAchievementId['id'] + 1)->first()['name'] ?? '';
        }else{
            $nextCommentAchievement = "First Comment Written";
        }

        return $nextCommentAchievement;
    }

    public function achievementsRemaingToUnlockNextBadge(User $user): int
    {
        $unlockedAchievements = $this->getUserAchievements($user);
        $achievements = count($unlockedAchievements);
        
        if($achievements < 4){
            $remainingAchievement = 4 - $achievements;
        }else if($achievements >= 4 && $achievements < 8){
            $remainingAchievement = 8 - $achievements;
        }else if($achievements >= 8 && $achievements < 10){
            $remainingAchievement = 10 - $achievements;
        }else{
            $remainingAchievement = 0;
        }
        return $remainingAchievement;
    }
    
    public function unlockLessonAchievement(int $lessonCount, User $user): void
    {
        try{
            $achievementExist = $this->checkLessonAchievementExist($lessonCount);
            if($achievementExist){
                $achievement  = $this->unlockAchievement($user, $achievementExist['name'], 'lesson');
                $this->event->triggerAchievementEvent($achievement['name'], $user);
                info(['Achievement Unlocked', $lessonCount, $achievement['name']]);
            }else{
                return;
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function unlockCommentAchievement(int $commentCount, User $user): void
    {
        try{
            $achievementExist = $this->checkCommentAchievementExist($commentCount);
            if($achievementExist){
                $achievement  = $this->unlockAchievement($user, $achievementExist['name'], 'lesson');
                $this->event->triggerAchievementEvent($achievement['name'], $user);
                info(['Achievement Unlocked', $commentCount, $achievement['name']]);
            }else{
                return;
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}