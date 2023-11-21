<?php

namespace App\Abstracts;

use App\Models\User;
use App\Models\Badge;

abstract class AbstractBadge{
    public function checkIfBadgeExists($totalAchievements){
        $badgeExist = Badge::where('achievement_score', $totalAchievements)->first();
        
        return $badgeExist;
    }

    public function checkIfBadgeAlreadyUnlockedByUser($badgeName, User $user){
        $exist = $user->badges()->where('name', $badgeName)->first();
        return $exist;
    }

    public function unlockNewBadge($badgeName, User $user){
        $badge = $user->badges()->create(['name' => $badgeName]);
        return $badge;
    }
}