<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use App\Filament\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use Filament\Resources\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShortUrlStats extends Page
{
    protected static string $resource = ShortUrlResource::class;

    protected static string $view = 'filament.resources.short-url-resource.pages.short-url-stats';

    public ShortUrl $record;

    protected function getHeaderWidgets(): array
    {
        return [
            ShortUrlResource\Widgets\ShortUrlStatsWidget::make(['shortUrl' => $this->record]),
        ];
    }

    public function getViewData(): array
    {
        return [
            'shortUrl' => $this->record,
        ];
    }
}
