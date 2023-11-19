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
            
            $lessonAchievement = $this->achievement->getUserAchievements($user, 'lesson');
            if(count($lessonAchievement) > 0){
                $lastLessonAchievement = $lessonAchievement->last();
                $lastLessonAchievementId = LessonAchievement::where('name', $lastLessonAchievement['name'])->first();
                $nextLessonAchievement = LessonAchievement::where('id', $lastLessonAchievementId['id'] + 1)->get()->first()['name'] ?? '';
            }else{
                $nextLessonAchievement = "First Lesson Watched";
            }
            
            $commentAchievement = $user->achievements()->where('achievement_type', 'comment')->pluck('name');
            if(count($commentAchievement) > 0){
                $lastCommentAchievement = $commentAchievement->last();
                $lastLessonAchievementId = CommentAchievement::where('name', $lastCommentAchievement['name'])->first();
                $nextCommentAchievement = CommentAchievement::where('id', $lastLessonAchievementId['id'] + 1)->get()->first()['name'] ?? '';
            }else{
                $nextCommentAchievement = "First Comment Written";
            }
            
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
            
            // $currentBadge = $user->badges()->get('name');
            // if(count($currentBadge) > 0){
            //     $currentBadgeModel = $currentBadge->last();
            //     $currentBadgeName = $currentBadgeModel['name'];
            //     $currentBadgeId = Badge::where('name', $currentBadgeName)->first();
            //     $nextBadge = Badge::where('id', $currentBadgeId['id'] + 1)->first();
            //     $nextBadgeName = $nextBadge['name'] ?? '';
            // }else{
            //     $currentBadgeName = 'Beginner';
            //     $nextBadgeName = 'Intermediate';
            // }
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