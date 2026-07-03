<?php

namespace App\Filament\Resources\ShortUrlResource\RelationManagers;

use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShortUrlClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'shortUrlClicks';

    protected static ?string $title = 'Статистика переходов';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip_address')
                    ->required(),
                Forms\Components\TextInput::make('user_agent'),
                Forms\Components\TextInput::make('referer'),
                Forms\Components\TextInput::make('country'),
                Forms\Components\TimePicker::make('clicked_at'),
                Forms\Components\TimePicker::make('created_at'),


            ]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP-адрес')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(50)
                    ->tooltip(fn ($state) => $state)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('referer')
                    ->label('Источник')
                    ->url(fn ($state) => $state)
                    ->openUrlInNewTab()
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('clicked_at')
                    ->label('Дата перехода')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->label('Только сегодня')
                    ->query(fn (Builder $query): Builder => $query->whereDate('clicked_at', today())),

                Tables\Filters\Filter::make('week')
                    ->label('За неделю')
                    ->query(fn (Builder $query): Builder => $query->where('clicked_at', '>=', now()->subWeek())),
            ])
            ->defaultSort('clicked_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}
