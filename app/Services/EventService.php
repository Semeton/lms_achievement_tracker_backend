<?php

namespace App\Services;

use App\Events\BadgeUnlocked;
use App\Events\AchievementUnlocked;

class EventService{
    public function triggerAchievementEvent($achievementName, $user){
        $event = event(new AchievementUnlocked($achievementName, $user));
        return $event;
    }

    public function triggerBadgeEvent($badgeName, $user){
        $event = event(new BadgeUnlocked($badgeName, $user));
        return $event;
    }
}