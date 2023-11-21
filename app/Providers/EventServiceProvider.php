<?php

namespace App\Providers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\ListenCommentWritten;
use Illuminate\Support\Facades\Event;
use App\Listeners\ListenLessonWatched;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LessonWatched::class => [
            ListenLessonWatched::class,
        ],
        CommentWritten::class => [
            ListenCommentWritten::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}