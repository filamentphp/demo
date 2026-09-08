<?php

namespace App\Filament\Resources\Shop\Brands\Tables;

use App\Filament\DemoIconAlias;
use App\Models\Shop\Brand;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Facades\FilamentIcon;
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
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last modified at')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('visit_website')
                    ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_VISIT_WEBSITE) ?? Heroicon::ArrowTopRightOnSquare)
                    ->color('gray')
                    ->tooltip('Open brand website')
                    ->url(fn (Brand $record): ?string => $record->website)
                    ->openUrlInNewTab()
                    ->hidden(fn (Brand $record): bool => blank($record->website)),
                Action::make('toggle_visibility')
                    ->icon(fn (Brand $record): string | BackedEnum => $record->is_visible
                        ? (FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_HIDE) ?? Heroicon::EyeSlash)
                        : (FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_SHOW) ?? Heroicon::Eye))
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
