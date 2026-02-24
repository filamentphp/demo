<?php

namespace App\Filament\Resources\Shop\Customers\Tables;

use App\Models\Shop\Customer;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->sortable(),
                TextColumn::make('country')
                    ->getStateUsing(fn ($record): ?string => $record->addresses->first()?->country?->getLabel()),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
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
            ]);
    }
}
