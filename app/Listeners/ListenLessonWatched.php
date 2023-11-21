<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Services\BadgeService;
use App\Services\AchievementService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListenLessonWatched
{
    protected $achievementService;
    protected $badgeService;
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
    public function handle(LessonWatched $event)
    {
        $lesson = $event->lesson;
        $user = $event->user;
        $lessonCount = $user->watched()->count();

        $this->achievementService->unlockLessonAchievement($lessonCount, $user);
        
        $achievements = $user->achievements()->count();
        $this->badgeService->unlockBadge($achievements, $user);
    }
}