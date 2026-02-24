<?php

namespace App\Filament\Resources\Shop\Orders\Tables;

use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('currency')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_price')
                    ->searchable()
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->money(),
                    ]),
                TextColumn::make('shipping_price')
                    ->label('Shipping cost')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Sum::make()
                            ->money(),
                    ]),
                TextColumn::make('created_at')
                    ->label('Order date')
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),

                Filter::make('created_at')
                    ->label('Order date')
                    ->schema([
                        DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('process')
                        ->icon(Heroicon::ArrowPath)
                        ->color('warning')
                        ->visible(fn (Order $record): bool => $record->status === OrderStatus::New)
                        ->action(function (Order $record): void {
                            $record->update(['status' => OrderStatus::Processing]);

                            Notification::make()
                                ->title('Order is now processing')
                                ->success()
                                ->send();
                        }),
                    Action::make('ship')
                        ->icon(Heroicon::Truck)
                        ->color('success')
                        ->visible(fn (Order $record): bool => $record->status === OrderStatus::Processing)
                        ->slideOver()
                        ->modalSubmitActionLabel('Ship')
                        ->schema([
                            Textarea::make('notes')
                                ->label('Shipping notes')
                                ->rows(3),
                        ])
                        ->extraModalFooterActions([
                            Action::make('ship_and_notify')
                                ->label('Ship & notify customer')
                                ->color('info')
                                ->action(function (Order $record, array $data): void {
                                    $record->update([
                                        'status' => OrderStatus::Shipped,
                                        'notes' => $data['notes'] ?? null,
                                    ]);

                                    Notification::make()
                                        ->title('Order shipped & customer notified')
                                        ->success()
                                        ->send();
                                }),
                        ])
                        ->action(function (Order $record, array $data): void {
                            $record->update([
                                'status' => OrderStatus::Shipped,
                                'notes' => $data['notes'] ?? null,
                            ]);

                            Notification::make()
                                ->title('Order shipped')
                                ->success()
                                ->send();
                        }),
                    Action::make('deliver')
                        ->icon(Heroicon::CheckBadge)
                        ->color('success')
                        ->visible(fn (Order $record): bool => $record->status === OrderStatus::Shipped)
                        ->requiresConfirmation()
                        ->action(function (Order $record): void {
                            $record->update(['status' => OrderStatus::Delivered]);

                            Notification::make()
                                ->title('Order marked as delivered')
                                ->success()
                                ->send();
                        }),
                    EditAction::make(),
                    Action::make('cancel')
                        ->icon(Heroicon::XCircle)
                        ->color('danger')
                        ->visible(fn (Order $record): bool => ! in_array($record->status, [OrderStatus::Delivered, OrderStatus::Cancelled]))
                        ->disabled(fn (Order $record): bool => $record->status === OrderStatus::Shipped)
                        ->requiresConfirmation()
                        ->action(function (Order $record): void {
                            $record->update(['status' => OrderStatus::Cancelled]);

                            Notification::make()
                                ->title('Order cancelled')
                                ->danger()
                                ->send();
                        }),
                    DeleteAction::make()
                        ->action(function (): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();
                        }),
                ]),
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
            ->groups([
                Group::make('created_at')
                    ->label('Order date')
                    ->date()
                    ->collapsible(),
            ]);
    }
}
