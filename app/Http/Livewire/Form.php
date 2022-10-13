<?php

namespace App\Http\Livewire;

use App\Models\Blog\Post;
use App\Models\Comment;
use App\Models\Shop\Product;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public function mount()
    {
        $this->form->fill(Arr::only(Comment::first()->attributesToArray(), ['commentable_type', 'commentable_id']));
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\MorphToSelect::make('commentable')
                ->types([
                    Forms\Components\MorphToSelect\Type::make(Product::class)
                        ->titleColumnName('name'),
                    Forms\Components\MorphToSelect\Type::make(Post::class)
                        ->titleColumnName('title'),
                ])
                ->searchable(),
        ];
    }

    protected function getFormModel(): Model | string | null
    {
        return Comment::first();
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
