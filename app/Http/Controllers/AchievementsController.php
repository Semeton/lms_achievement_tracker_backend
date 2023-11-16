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
    
    public function index(User $user)
    {
        try{
            $unlockedAchievements = $this->achievement->getUserAchievements($user);
            $nextLessonAchievement = $this->achievement->getNextLessonAchievements($user);
            $nextCommentAchievement = $this->achievement->getNextCommentAchievement($user);
            $remainingAchievement = $this->achievement->achievementsRemaingToUnlockNextBadge($user);
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