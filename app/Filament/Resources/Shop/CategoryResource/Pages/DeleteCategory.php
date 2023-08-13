<?php

namespace App\Filament\Resources\Shop\CategoryResource\Pages;

use App\Filament\Resources\Shop\CategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class DeleteCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->successNotificationTitle('La catégorie a été supprimée avec Succès.'),
        ];
    }

    protected function getRemoveedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->body('La catégorie a été supprimée avec Succès.');
    }
}
