<?php

namespace App\Abstracts;

use App\Models\User;
use App\Models\Badge;

abstract class AbstractBadge{
    public function checkIfBadgeExists(int $totalAchievements): ?Badge {
        $badgeExist = Badge::where('achievement_score', $totalAchievements)->first();
        
        return $badgeExist;
    }

    public function checkIfBadgeAlreadyUnlockedByUser(string $badgeName, User $user): ?Badge {
        $exist = $user->badges()->where('name', $badgeName)->first();
        return $exist;
    }

    public function unlockNewBadge(string $badgeName, User $user): Badge {
        $badge = $user->badges()->create(['name' => $badgeName]);
        return $badge;
    }
}