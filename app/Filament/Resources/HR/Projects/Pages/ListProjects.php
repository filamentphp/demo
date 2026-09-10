<?php

namespace App\Filament\Resources\HR\Projects\Pages;

use App\Filament\Resources\HR\Projects\ProjectResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListProjects extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ProjectResource::class;

    #[On('echo-private:projects,ProjectChanged')]
    public function refreshProject(): void
    {
        if (filled($this->mountedActions)) {
            $this->skipRender();

            return;
        }

        $this->flushCachedTableRecords();
        $this->dispatch('refresh-sidebar');
    }

    protected function getActions(): array
    {
        return [
            Action::make('client_portal')
                ->label('Client portal')
                ->color('gray')
                ->url(route('client.projects'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ProjectResource::getWidgets();
    }
}
