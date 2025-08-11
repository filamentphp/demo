<?php

namespace App\Livewire;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property-read Schema $form
 */
class Form extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed> */
    public $data = [];

    public function mount(): void
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        $this->form->fill();
    }

    /** @return \Filament\Schemas\Components\Component[] */
    protected function getFormSchema(): array
    {
        return [
            Builder::make('test')
                ->blocks([
                    Block::make('one')
                        ->schema([
                            TextInput::make('one'),
                        ]),
                    Block::make('two')
                        ->schema([
                            TextInput::make('two'),
                        ]),
                ]),
        ];
    }

    public function submit(): never
    {
        dd($this->form->getState());
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function render(): View
    {
        return view('livewire.form');
    }
}
