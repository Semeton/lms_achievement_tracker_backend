<?php

namespace App\Abstracts;

use App\Models\User;
use App\Models\Badge;

abstract class AbstractBadge{
    /**
     * Check if a badge exists for a total number of achievements
     *
     * @param integer $totalAchievements
     * @return Badge|null
     */
    public function checkIfBadgeExists(int $totalAchievements): ?Badge {
        $badgeExist = Badge::where('achievement_score', $totalAchievements)->first();
        
        return $badgeExist;
    }

    /**
     * Check if a badge is already unlocked by a user
     *
     * @param string $badgeName
     * @param User $user
     * @return Badge|null
     */
    public function checkIfBadgeAlreadyUnlockedByUser(string $badgeName, User $user): ?Badge {
        $exist = $user->badges()->where('name', $badgeName)->first();
        return $exist;
    }

    /**
     * Unlock a new badge for a user
     *
     * @param string $badgeName
     * @param User $user
     * @return Badge
     */
    public function unlockNewBadge(string $badgeName, User $user): Badge {
        $badge = $user->badges()->create(['name' => $badgeName]);
        return $badge;
    }
}