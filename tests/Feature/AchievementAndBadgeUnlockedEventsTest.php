<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Events\BadgeUnlocked;
use App\Events\AchievementUnlocked;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\AchievementsController;
use App\Services\AchievementService;
use App\Services\BadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AchievementAndBadgeUnlockedEventsTest extends TestCase
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
    /** @assert that the AchievementUnlocked event is triggered when the
     * right number of comments is made by user
     */
    public function it_fires_comment_achievement_unlocked_event()
    {
        Event::fake();

        $user = User::factory()->create();
        $commentScores = [1, 3, 5, 10, 20];
        foreach($commentScores as $item){
            $this->achievementService->unlockCommentAchievement($item, $user);
        }
        Event::assertDispatched(AchievementUnlocked::class);
    }

    /** @assert that the AchievementUnlocked event is triggered when the
     * right number of lessons is watched by user
     */
    public function it_fires_lesson_achievement_unlocked_event()
    {
        Event::fake();

        $user = User::factory()->create();
        $lessonScores = [1, 5, 10, 25, 50];
        foreach($lessonScores as $item){
            $this->achievementService->unlockLessonAchievement($item, $user);
        }
        
        Event::assertDispatched(AchievementUnlocked::class);
    }

    /** @assert that the BadgeUnlocked event is triggered when the
     * right number of achievements is met by user
     */
    public function it_fires_badge_unlocked_event()
    {
        Event::fake();

        $user = User::factory()->create();
        $achievementScores = [0, 4, 8, 10];
        foreach($achievementScores as $item){
            $this->badgeService->unlockBadge($item, $user);
        }
        
        Event::assertDispatched(BadgeUnlocked::class);
    }
}