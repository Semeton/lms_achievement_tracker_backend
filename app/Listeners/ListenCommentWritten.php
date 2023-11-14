<?php

namespace App\Listeners;

use App\Services\BadgeService;
use App\Events\CommentWritten;
use App\Services\AchievementService;

class ListenCommentWritten
{
    protected AchievementService $achievementService;
    protected BadgeService $badgeService;
    /**
     * Create the event listener.
     */
    public function __construct(AchievementService $achievementService, BadgeService $badgeService)
    {
        $this->achievementService = $achievementService;
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event)
    {
        $user = $event->comment->user;
        $commentCount = $user->comments()->count();

        $this->achievementService->unlockCommentAchievement($commentCount, $user);

        $achievements = $user->achievements()->count();
        $this->badgeService->unlockBadge($achievements, $user);
        
    }
}