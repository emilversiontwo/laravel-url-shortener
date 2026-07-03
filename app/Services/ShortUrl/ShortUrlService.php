<?php
declare(strict_types=1);

namespace App\Services\ShortUrl;

use App\Events\ShortUrl\ShortUrlClicked;
use App\Models\ShortUrl;
use App\Models\ShortUrlClick;
use App\Services\ShortUrl\Dto\ShortUrlClickDto;
use Illuminate\Support\Facades\Cache;
use Str;

class ShortUrlService
{
    public function getShortUrlFromAlias(string $alias): ShortUrl
    {
        return $this->getCachedShortUrl($alias);
    }

    public function click(ShortUrlClickDto $dto): ShortUrl
    {
        $shortUrl = $this->getCachedShortUrl($dto->alias);

        event(new ShortUrlClicked($dto));

        return $shortUrl;
    }

    public function recordClick(ShortUrlClickDto $dto): void
    {
        $shortUrl = $this->getCachedShortUrl($dto->alias);

        $shortUrlClick = new ShortUrlClick();

        $shortUrlClick->ip_address = $dto->ipAddress;
        $shortUrlClick->user_agent = $dto->userAgent;
        $shortUrlClick->referer = $dto->referer;
        $shortUrlClick->clicked_at = $dto->clickedAt;

        $shortUrlClick->shortUrl()->associate($shortUrl);

        $shortUrlClick->save();
    }

    public function generateAlias(): string
    {
        do {
            $alias = Str::random(6);
        } while (ShortUrl::where('alias', $alias)->exists());

        return $alias;
    }

    protected function getCachedShortUrl(string $alias): ShortUrl
    {
        return Cache::remember(
            "short-url:{$alias}",
            3600,
            function () use ($alias) {
                return ShortUrl::query()->where('alias', '=', $alias)->firstOrFail();
            }
        );
    }
}
