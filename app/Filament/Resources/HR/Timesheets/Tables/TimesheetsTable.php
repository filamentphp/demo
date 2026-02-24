<?php

namespace App\Filament\Resources\HR\Timesheets\Tables;

use App\Models\HR\Timesheet;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TimesheetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->summarize(Count::make()),

                TextColumn::make('project.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('task.title')
                    ->searchable()
                    ->limit(30)
                    ->toggleable()
                    ->placeholder('No task'),

                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->summarize(Range::make()->minimalDateTimeDifference()),

                TextColumn::make('hours')
                    ->numeric(1)
                    ->sortable()
                    ->summarize([
                        Sum::make()->label('Total'),
                        Average::make()->label('Avg'),
                    ]),

                IconColumn::make('is_billable')
                    ->label('Billable')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('hourly_rate')
                    ->money('usd')
                    ->sortable()
                    ->toggleable()
                    ->copyable(),

                TextColumn::make('total_cost')
                    ->money('usd')
                    ->sortable()
                    ->summarize(Sum::make()->money('usd')),
            ])
            ->defaultSort('date', 'desc')
            ->groups([
                Group::make('employee.name')
                    ->label('Employee')
                    ->collapsible(),
                Group::make('project.name')
                    ->label('Project')
                    ->collapsible(),
                Group::make('date')
                    ->label('Date')
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                SelectFilter::make('employee')
                    ->relationship('employee', 'name'),

                SelectFilter::make('project')
                    ->relationship('project', 'name'),

                TernaryFilter::make('is_billable'),

                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'From ' . Carbon::parse($data['from'])->toFormattedDateString();
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Until ' . Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                Action::make('toggle_billable')
                    ->iconButton()
                    ->icon(fn (Timesheet $record): Heroicon => $record->is_billable ? Heroicon::CurrencyDollar : Heroicon::NoSymbol)
                    ->color(fn (Timesheet $record): string => $record->is_billable ? 'success' : 'gray')
                    ->disabled(fn (Timesheet $record): bool => $record->date->isBefore(now()->subDays(7)))
                    ->action(fn (Timesheet $record) => $record->update(['is_billable' => ! $record->is_billable])),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                BulkAction::make('mark_billable')
                    ->icon(Heroicon::CurrencyDollar)
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each(fn (Timesheet $record) => $record->update(['is_billable' => true]));
                    })
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('mark_non_billable')
                    ->icon(Heroicon::NoSymbol)
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each(fn (Timesheet $record) => $record->update(['is_billable' => false]));
                    })
                    ->deselectRecordsAfterCompletion(),
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
