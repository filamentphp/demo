<?php

namespace App\Filament\Resources\Shop\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            ReplicateAction::make()
                ->requiresConfirmation()
                ->excludeAttributes(['id', 'number', 'status', 'created_at', 'updated_at', 'deleted_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['number'] = 'OR-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
                    $data['status'] = OrderStatus::New;

                    return $data;
                }),
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
