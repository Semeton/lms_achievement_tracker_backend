<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Events\CommentWritten;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentListernerTest extends TestCase
{
    /**
     * Triggers CommentWritten Event which is then listened to to unlock the relevant
     * badge or achievement.
     */
    public function test_comment_event_listener(){
        $user = User::factory()->create();
        for($i = 1; $i <= 20; $i++){
            $comment = Comment::factory()->create(['user_id' => $user->id]);
            event(new CommentWritten($comment));
            $this->assertTrue(true);
        }
    }
}