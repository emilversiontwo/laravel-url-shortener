<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use App\Filament\Resources\ShortUrlResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (!preg_match('/https?:\/\/?/', $data['url'])) {
            $data['url'] = 'https://' . $data['url'];
        }

        return $data;
    }
}
