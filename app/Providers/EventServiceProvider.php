<?php

namespace App\Providers;

use App\Events\ShortUrl\ShortUrlClicked;
use App\Listeners\ShortUrl\ClickListener;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        ShortUrlClicked::class => [
            ClickListener::class,
        ]
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
