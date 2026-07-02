<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets;

use App\Models\ShortUrl;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShortUrlStatsWidget extends BaseWidget
{
    public ?ShortUrl $shortUrl = null;

    protected function getStats(): array
    {
        if (!$this->shortUrl) {
            return [];
        }

        return [
            Stat::make('Всего переходов', $this->shortUrl->shortUrlClicks()->count())
                ->description('За всё время')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Переходов сегодня',
                $this->shortUrl->shortUrlClicks()->whereDate('clicked_at', today())->count()
            )
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Уникальных IP',
                $this->shortUrl->shortUrlClicks()->distinct('ip_address')->count('ip_address')
            )
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }
}
