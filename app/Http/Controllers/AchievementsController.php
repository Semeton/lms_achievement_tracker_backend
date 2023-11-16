<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Badge;
use App\Models\LessonAchievement;
use App\Models\CommentAchievement;
use App\Services\AchievementService;
use App\Services\BadgeService;

class AchievementsController extends Controller
{
    public $badge;
    public $achievement;
    
    public function __construct(AchievementService $achievement, BadgeService $badge)
    {
        $this->achievement = $achievement;
        $this->badge = $badge;
    }
    public function index($userId)
    {
        try{
            if(!User::find($userId)){
                return response()->json(['message' => 'User not found'], 404);
            }
            $user = User::find($userId);
            $unlockedAchievements = $this->achievement->getUserAchievements($user);
            
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
            
            $nextLessonAchievement = $this->achievement->getNextLessonAchievements($user);
            $nextCommentAchievement = $this->achievement->getNextCommentAchievement($user);
            $currentBadgeName = $this->badge->getCurrentBadge($user);
            $nextBadgeName = $this->badge->getNextBadge($user);
            
            return response()->json([
                'unlocked_achievements' => $unlockedAchievements,
                'next_available_achievements' => [$nextLessonAchievement, $nextCommentAchievement],
                'current_badge' => $currentBadgeName,
                'next_badge' => $nextBadgeName,
                'remaing_to_unlock_next_badge' => $remainingAchievement
            ]);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}