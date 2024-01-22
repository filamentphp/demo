<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property-read Forms\Form $form
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

    /** @return Forms\Components\Component[] */
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
