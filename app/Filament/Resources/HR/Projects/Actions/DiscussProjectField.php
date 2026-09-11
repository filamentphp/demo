<?php

namespace App\Filament\Resources\HR\Projects\Actions;

use App\Models\HR\Project;
use Filament\Actions\Action;

class DiscussProjectField
{
    public static function make(string $field): Action
    {
        return Action::make('discuss_' . $field)->label('Discuss')->icon('heroicon-m-chat-bubble-left')->color('gray')
            ->extraAttributes(['data-project-field-link' => $field])
            ->visible(fn (?Project $record): bool => $record !== null)
            ->dispatch('project-discuss-field', fn (Project $record): array => ['projectId' => $record->id, 'field' => $field]);
    }
}
