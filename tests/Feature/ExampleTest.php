<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * The response if the request is being made for a user that doen not exist.
     */
    public function test_the_application_returns_a_user_does_not_exist(): void
    {
        $userIdNotExisting = 100;
        
        $response = $this->get("/users/{$userIdNotExisting}/achievements");

        $response->assertStatus(404);
        $response->assertJsonFragment(["message" =>"User not found"]);
    }

    /**
     * .
     */
    public function test_the_application_returns_a_successful_response_with_the_expected_attributes(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['unlocked_achievements', 'next_available_achievements','current_badge', 'next_badge', 'remaing_to_unlock_next_badge'])
            ->whereAllType([
                'unlocked_achievements' => 'array',
                'next_available_achievements' => 'array',
                'current_badge' => 'string',
                'next_badge' => 'string',
                'remaing_to_unlock_next_badge' => 'integer',
            ])
        );
    }

    /**
     * .
     */
    public function test_the_application_returns_a_successful_response_for_users_with_no_achievements(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'unlocked_achievements' => [],
            'next_available_achievements' => ["First Lesson Watched", "First Comment Written"],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 4,
        ]);
    }
    
}