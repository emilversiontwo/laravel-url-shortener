<?php

namespace App\Jobs\ShortUrl;

use App\Services\ShortUrl\Dto\ShortUrlClickDto;
use App\Services\ShortUrl\ShortUrlService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessShortUrlClickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 60, 300];

    public function __construct(
        private readonly ShortUrlClickDto $dto,
    )
    {}

    public function handle(ShortUrlService $shortUrlService): void
    {
        $shortUrlService->recordClick($this->dto);
    }

    public function fail(Throwable $exception): void
    {
        Log::error("Failed to record shortUrl click request.", [
            'short_url_alias' => $this->dto->alias,
            'error' => $exception->getMessage(),
        ]);
    }
}
