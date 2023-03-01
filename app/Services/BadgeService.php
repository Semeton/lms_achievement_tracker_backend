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
        $currentBadge = $user->badges()->latest('id')->value('name');
        return $currentBadge ?? 'Beginner';
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
            return 'Intermediate';
        }
        
        $currentBadgeId = Badge::where('name', $currentBadgeName)->value('id');
        $nextBadge = Badge::where('id', '>', $currentBadgeId)->value('name');
        return $nextBadge ?? '';
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
                }
                
                $badge = $this->unlockNewBadge($badgeExist['name'], $user);
                $this->event->triggerBadgeEvent($badge['name'], $user);
                info(['Badge Unlocked', $totalAchievements, $badge['name']]);
                return $badge['name'];
            }else{
                return "No new badge unlocked";
            }
        }catch(Exception $e){
            info($e->getMessage());
            return "An error occurred while unlocking the badge";
        }
    }
}