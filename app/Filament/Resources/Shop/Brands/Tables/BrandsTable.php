<?php

namespace App\Filament\Resources\Shop\Brands\Tables;

use App\Models\Shop\Brand;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('website')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last modified at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('visit_website')
                    ->icon(Heroicon::ArrowTopRightOnSquare)
                    ->color('gray')
                    ->tooltip('Open brand website')
                    ->url(fn (Brand $record): ?string => $record->website)
                    ->openUrlInNewTab()
                    ->hidden(fn (Brand $record): bool => blank($record->website)),
                Action::make('toggle_visibility')
                    ->icon(fn (Brand $record): Heroicon => $record->is_visible ? Heroicon::EyeSlash : Heroicon::Eye)
                    ->color('gray')
                    ->tooltip(fn (Brand $record): string => $record->is_visible ? 'Hide brand' : 'Show brand')
                    ->action(fn (Brand $record) => $record->update(['is_visible' => ! $record->is_visible])),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->action(function (): void {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('sort')
            ->reorderable('sort');
    }
}
