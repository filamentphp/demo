<?php

namespace App\Filament\Resources\HR\LeaveRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Leave Details')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('employee.name'),
                        TextEntry::make('type')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('start_date')
                            ->date(),
                        TextEntry::make('end_date')
                            ->date(),
                        TextEntry::make('days_requested')
                            ->suffix(' days'),
                        TextEntry::make('start_time')
                            ->label('Start time (half days)')
                            ->time('H:i')
                            ->placeholder('N/A'),
                        TextEntry::make('end_time')
                            ->label('End time (half days)')
                            ->time('H:i')
                            ->placeholder('N/A'),
                        TextEntry::make('reason')
                            ->columnSpanFull(),
                    ]),

                Section::make('Review')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('approver.name')
                            ->placeholder('Not yet assigned'),
                        TextEntry::make('reviewed_at')
                            ->label('Reviewed at')
                            ->dateTime()
                            ->placeholder('Not yet reviewed'),
                        TextEntry::make('reviewer_notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
