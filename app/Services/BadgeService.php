<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Badge;
use App\Services\EventService;
use App\Abstracts\AbstractBadge;

class BadgeService extends AbstractBadge{
    protected EventService $event;

    /**
     * Undocumented function
     *
     * @param EventService $event
     */
    public function __construct(EventService $event)
    {
        $this->event = $event;
    }

    /**
     * Get user current badge
     *
     * @param User $user
     * @return string
     */
    public function getCurrentBadge(User $user): string
    {
        $currentBadge = $user->badges()->get('name');
        if(count($currentBadge) > 0){
            $currentBadgeModel = $currentBadge->last();
            $currentBadgeName = $currentBadgeModel['name'];
            
        }else{
            $currentBadgeName = 'Beginner';
        }

        return $currentBadgeName;
    }

    /**
     * Get user next badge
     *
     * @param User $user
     * @return string
     */
    public function getNextBadge(User $user): string
    {
        $currentBadgeName = $this->getCurrentBadge($user);
        if($currentBadgeName == 'Beginner'){
            $nextBadgeName = 'Intermediate';
        }else{
            $currentBadgeId = Badge::where('name', $currentBadgeName)->first();
            $nextBadge = Badge::where('id', '>', $currentBadgeId['id'])->first();
            $nextBadgeName = $nextBadge['name'] ?? '';
        }
        return $nextBadgeName;
    }
    
    /**
     * Unlock a new badge
     *
     * @param integer $totalAchievements
     * @param User $user
     * @return string
     */
    public function unlockBadge(int $totalAchievements, User $user): string
    {
        try{
            $badgeExist = $this->checkIfBadgeExists($totalAchievements);
            if($badgeExist){
                $badge = $this->checkIfBadgeAlreadyUnlockedByUser($badgeExist['name'], $user);
                if($badge){
                    return "No new badge unlocked";
                }else{
                    $badge = $this->unlockNewBadge($badgeExist['name'], $user);
                    $this->event->triggerBadgeEvent($badge['name'], $user);
                    info(['Badge Unlocked', $totalAchievements, $badge['name']]);
                }
            }else{
                return "No new badge unlocked";
            }
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}