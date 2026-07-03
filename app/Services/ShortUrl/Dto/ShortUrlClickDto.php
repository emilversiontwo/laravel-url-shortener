<?php

namespace App\Services\ShortUrl\Dto;

use App\Support\Dto\Dto;
use Carbon\Carbon;

class ShortUrlClickDto extends Dto
{
    public string $alias;

    public ?string $ipAddress;

    public ?string $userAgent;

    public ?string $referer;

    public Carbon $clickedAt;
}
