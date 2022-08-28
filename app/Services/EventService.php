<?php

namespace App\Services;

use App\Events\BadgeUnlocked;
use App\Events\AchievementUnlocked;

class EventService{
    
    /**
     * Trigger event
     */
    public function triggerAchievementEvent(string $achievementName, $user): ?AchievementUnlocked{
        $event = event(new AchievementUnlocked($achievementName, $user));
        return $event;
    }

    /**
     * Trigger event
     */
    public function triggerBadgeEvent(string $badgeName, $user): ?BadgeUnlocked{
        $event = event(new BadgeUnlocked($badgeName, $user));
        return $event;
    }
}