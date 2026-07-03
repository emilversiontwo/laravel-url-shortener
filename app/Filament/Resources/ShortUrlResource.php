<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShortUrlResource\Pages;
use App\Filament\Resources\ShortUrlResource\RelationManagers;
use App\Models\ShortUrl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShortUrlResource extends Resource
{
    protected static ?string $model = ShortUrl::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required(),
                Forms\Components\TextInput::make('alias')
                    ->helperText("Оставьте пустым для автоматической генерации")
                    ->placeholder('Только буквы и цифры, 3-20 символов')
                    ->regex('/^[a-zA-Z0-9]{3,20}$/')
                    ->unique(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('url'),
                Tables\Columns\TextColumn::make('alias'),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('short_url')
                    ->label('Короткая ссылка')
                    ->getStateUsing(function ($record) {
                        return route('shortUrl.click', $record->alias);
                    })
                    ->url(fn ($record) => route('shortUrl.click', $record->alias))
                    ->copyable()
                    ->copyMessage('Ссылка скопирована!')
                    ->limit(40)
                    ->tooltip(fn ($record) => route('shortUrl.click', $record->alias))
                    ->icon('heroicon-o-link')
                    ->color('primary'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('stats')
                    ->label('Статистика')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn (ShortUrl $record): string => Pages\ShortUrlStats::getUrl(['record' => $record])),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShortUrlClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShortUrls::route('/'),
            'create' => Pages\CreateShortUrl::route('/create'),
            'edit' => Pages\EditShortUrl::route('/{record}/edit'),
            'stats' => Pages\ShortUrlStats::route('/{record}/stats'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
