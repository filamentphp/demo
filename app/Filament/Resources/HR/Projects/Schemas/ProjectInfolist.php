<?php

namespace App\Filament\Resources\HR\Projects\Schemas;

use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Project')
                    ->schema([
                        Tab::make('Overview')
                            ->icon(Heroicon::InformationCircle)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('slug'),
                                TextEntry::make('department.name')
                                    ->placeholder('No department'),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('priority')
                                    ->badge(),
                                ColorEntry::make('color')
                                    ->placeholder('No color'),
                                TextEntry::make('start_date')
                                    ->date(),
                                TextEntry::make('end_date')
                                    ->date()
                                    ->placeholder('No end date'),
                                TextEntry::make('description')
                                    ->prose()
                                    ->markdown()
                                    ->columnSpanFull()
                                    ->placeholder('No description'),
                            ]),

                        Tab::make('Budget')
                            ->icon(Heroicon::CurrencyDollar)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('budget')
                                    ->money('usd')
                                    ->placeholder('$0.00'),
                                TextEntry::make('spent')
                                    ->money('usd')
                                    ->placeholder('$0.00'),
                                TextEntry::make('estimated_hours')
                                    ->suffix(' hours')
                                    ->placeholder('0'),
                                TextEntry::make('actual_hours')
                                    ->suffix(' hours')
                                    ->placeholder('0'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
