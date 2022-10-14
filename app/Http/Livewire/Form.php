<?php

namespace App\Http\Livewire;

use App\Forms\Components\MarkdownEditor;
use App\Models\Address;
use App\Models\Blog\Post;
use App\Models\Comment;
use App\Models\Shop\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;

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
