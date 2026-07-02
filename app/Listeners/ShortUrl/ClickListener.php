<?php

namespace App\Listeners\ShortUrl;

use App\Events\ShortUrl\ShortUrlClicked;
use App\Jobs\ShortUrl\ProcessShortUrlClickJob;
use Illuminate\Support\Facades\Log;

class ClickListener
{
    /**
     * Handle the event.
     */
    public function handle(ShortUrlClicked $event): void
    {
        ProcessShortUrlClickJob::dispatch(dto: $event->dto);
    }
}
