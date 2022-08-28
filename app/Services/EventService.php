<?php

namespace App\Services;

use App\Events\BadgeUnlocked;
use App\Events\AchievementUnlocked;

class EventService{
    public function triggerAchievementEvent(string $achievementName, $user): ?AchievementUnlocked{
        $event = event(new AchievementUnlocked($achievementName, $user));
        return $event;
    }

    public function triggerBadgeEvent(string $badgeName, $user): ?BadgeUnlocked{
        $event = event(new BadgeUnlocked($badgeName, $user));
        return $event;
    }
}