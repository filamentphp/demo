<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public function mount()
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Builder::make('test')
                ->blocks([
                    Forms\Components\Builder\Block::make('one')
                        ->schema([
                            Forms\Components\TextInput::make('one'),
                        ]),
                    Forms\Components\Builder\Block::make('two')
                        ->schema([
                            Forms\Components\TextInput::make('two'),
                        ]),
                ]),
        ];
    }

    public function submit()
    {
        dd($this->form->getState());
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function render()
    {
        return view('livewire.form');
    }
}
