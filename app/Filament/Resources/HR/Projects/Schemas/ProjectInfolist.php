<?php

namespace App\Filament\Resources\HR\Projects\Schemas;

use App\Filament\DemoIconAlias;
use App\Filament\Resources\HR\Projects\Actions\DiscussProjectField;
use App\Livewire\ProjectPresence;
use App\Livewire\ProjectRevisions;
use App\Models\HR\Project;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;

class ProjectInfolist
{
    public static function configure(Schema $schema, bool $preview = false): Schema
    {
        return $schema
            ->components([
                Livewire::make(ProjectPresence::class)
                    ->visible(fn (Project $record): bool => ! $preview && ! $record->trashed())
                    ->columnSpanFull(),
                Tabs::make('Project')
                    ->persistTabInQueryString($preview ? null : 'project-tab')
                    ->schema([
                        Tab::make('Overview')
                            ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_HR_PROJECTS_INFOLIST_TABS_OVERVIEW) ?? Heroicon::InformationCircle)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')->visible($preview),
                                TextEntry::make('department.name')
                                    ->label('Department')
                                    ->placeholder('No department'),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('priority')
                                    ->badge(),
                                TextEntry::make('start_date')
                                    ->date(),
                                TextEntry::make('end_date')
                                    ->date()
                                    ->placeholder('No end date'),
                                TextEntry::make('description')
                                    ->hintActions($preview ? [] : [DiscussProjectField::make('description')])
                                    ->prose()
                                    ->markdown()
                                    ->columnSpanFull()
                                    ->placeholder('No description'),
                            ]),

                        Tab::make('Budget')
                            ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_HR_PROJECTS_INFOLIST_TABS_BUDGET) ?? Heroicon::CurrencyDollar)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('budget')
                                    ->money('usd')
                                    ->placeholder('$0.00'),
                                TextEntry::make('spent')
                                    ->money('usd')
                                    ->placeholder('$0.00'),
                                TextEntry::make('remaining_budget')
                                    ->state(fn (Project $record): float => (float) $record->budget - (float) $record->spent)
                                    ->money('usd'),
                                TextEntry::make('estimated_hours')
                                    ->numeric()
                                    ->suffix(' hours')
                                    ->placeholder('0'),
                                TextEntry::make('actual_hours')
                                    ->numeric()
                                    ->suffix(' hours')
                                    ->placeholder('0'),
                            ]),

                        Tab::make('Revisions')
                            ->id('project-revisions')
                            ->visible(! $preview)
                            ->icon(Heroicon::DocumentDuplicate)
                            ->schema([
                                Livewire::make(ProjectRevisions::class),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
