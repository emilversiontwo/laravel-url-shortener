<?php

namespace App\Observers\ShortUrl;

use App\Models\ShortUrl;
use Illuminate\Support\Facades\Cache;

class ShortUrlObserver
{
    public function created(ShortUrl $shortUrl): void
    {
        Cache::set("short-url:" . $shortUrl->alias, $shortUrl, 3600);
    }

    public function updated(ShortUrl $shortUrl): void
    {
        Cache::delete("short-url:" . $shortUrl->getOriginal('alias'));
        Cache::set("short-url:" . $shortUrl->alias, $shortUrl, 3600);
    }

    public function deleted(ShortUrl $shortUrl): void
    {
        Cache::delete("short-url:" . $shortUrl->alias);
    }

    public function forceDeleted(ShortUrl $shortUrl): void
    {
        Cache::delete("short-url:" . $shortUrl->alias);
    }
}
