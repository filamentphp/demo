<?php

namespace App\Http\Livewire;

use App\Forms\Components\MarkdownEditor;
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
            Forms\Components\RichEditor::make('content'),
            Forms\Components\RichEditor::make('content2'),
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
