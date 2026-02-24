<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class FlaggedOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where(function (Builder $query): void {
                        $query->where(function (Builder $q): void {
                            $q->where('status', OrderStatus::New)
                                ->where('created_at', '<=', now()->subDays(3));
                        })->orWhere(function (Builder $q): void {
                            $q->where('status', OrderStatus::Processing)
                                ->where('created_at', '<=', now()->subDays(7));
                        });
                    })
                    ->with('customer')
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'asc')
            ->columns([
                TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('days_old')
                    ->label('Days Old')
                    ->state(fn (Order $record): int => (int) $record->created_at?->diffInDays(now()))
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('created_at', $direction === 'asc' ? 'desc' : 'asc'))
                    ->weight(FontWeight::Bold),
                TextColumn::make('issue')
                    ->label('Issue')
                    ->state(fn (Order $record): string => $record->status === OrderStatus::New ? 'Awaiting processing' : 'Stuck in processing')
                    ->badge()
                    ->color(fn (Order $record): string => $record->status === OrderStatus::New ? 'warning' : 'danger'),
            ]);
    }
}
