<?php

namespace App\Http\Controllers\web\ShortUrl;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use App\Services\ShortUrl\Dto\ShortUrlClickDto;
use App\Services\ShortUrl\ShortUrlService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlController extends Controller
{
    public function __construct(
        private readonly ShortUrlService $shortUrlService
    )
    {}

    public function __invoke(Request $request, string $alias): RedirectResponse
    {
        $dto = new ShortUrlClickDto([
            'ipAddress' => $request->ip(),
            'userAgent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'alias' => $alias,
            'clickedAt' => Carbon::now(),
        ]);

        $shortUrl = $this->shortUrlService->click($dto);

        return redirect($shortUrl->url, Response::HTTP_MOVED_PERMANENTLY);
    }
}
