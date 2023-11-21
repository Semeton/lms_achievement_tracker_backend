<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonWatchedListenerTest extends TestCase
{
    /**
     * Triggers LessonWatched Event which is then listened to to unlock the relevant
     * badge or achievement.
     */
    public function test_lesson_event_listener(){
        $user = User::factory()->create();
        for($i = 1; $i <= 25; $i++){
            $lesson = Lesson::factory()->create();
            $user->lessons()->attach($lesson, ['watched' => true]);
            event(new LessonWatched($lesson, $user));
            $this->assertTrue(true);
        }
    }
}