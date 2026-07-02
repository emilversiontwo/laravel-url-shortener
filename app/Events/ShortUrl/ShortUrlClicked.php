<?php

namespace App\Events\ShortUrl;

use App\Services\ShortUrl\Dto\ShortUrlClickDto;
use Illuminate\Foundation\Events\Dispatchable;

class ShortUrlClicked
{
    use Dispatchable;

    public function __construct(
        public ShortUrlClickDto $dto,
    )
    {}
}
