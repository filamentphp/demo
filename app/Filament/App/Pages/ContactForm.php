<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

use Filament\Forms;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.contact-form';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('comment')
                ->label('Comment*')
                ->required(),
            Forms\Components\TextInput::make('name')
                ->label('Name*')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('Email*')
                ->email()
                ->required(),
            Forms\Components\Button::make('submit')
                ->label('Post Comment')
                ->action('submit')
                ->color('dark')
                ->icon('d-icon-arrow-right')
        ];
    }

    public function submit()
    {
        $this->validate();

     dd("ess");
        $this->reset();

        // Show success message
        $this->notify('success', 'Comment submitted successfully!');
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:500',
        ];
    }
}
