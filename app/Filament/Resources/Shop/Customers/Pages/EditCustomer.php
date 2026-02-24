<?php

namespace App\Filament\Resources\Shop\Customers\Pages;

use App\Filament\Resources\Shop\Customers\CustomerResource;
use App\Models\Shop\Customer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('send_email')
                ->icon(Heroicon::Envelope)
                ->color('info')
                ->modalWidth(Width::Large)
                ->modalSubmitActionLabel('Send')
                ->fillForm(fn (Customer $record): array => [
                    'to' => $record->email,
                ])
                ->schema([
                    TextInput::make('to')
                        ->email()
                        ->disabled()
                        ->dehydrated(),
                    TextInput::make('subject')
                        ->required(),
                    RichEditor::make('body')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function (Customer $record): void {
                    Notification::make()
                        ->title("Email sent to {$record->name}")
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
