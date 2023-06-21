<?php

namespace App\Http\Livewire;

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
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DateTimePicker::make('date')->native(false),
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
