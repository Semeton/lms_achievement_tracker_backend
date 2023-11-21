<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Services\EventService;
use App\Abstracts\AbstractBadge;

class BadgeService extends AbstractBadge{
    protected $event;

    public function __construct(EventService $event)
    {
        $this->event = $event;
    }
    
    public function unlockBadge($totalAchievements, User $user)
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