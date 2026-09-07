<?php

namespace App\Filament\Resources\Shop\Brands\Pages;

use App\Filament\DemoIconAlias;
use App\Filament\Resources\Shop\Brands\BrandResource;
use App\Models\Shop\Brand;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('visit_website')
                ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_SHOP_BRANDS_ACTIONS_VISIT_WEBSITE) ?? Heroicon::ArrowTopRightOnSquare)
                ->color('gray')
                ->tooltip('Open brand website')
                ->url(fn (Brand $record): ?string => $record->website)
                ->openUrlInNewTab()
                ->hidden(fn (Brand $record): bool => blank($record->website)),
            DeleteAction::make(),
        ];
    }
}
