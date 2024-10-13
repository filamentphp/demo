<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

use Filament\Forms;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;

class ContactForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.contact-form';


    public function mount()
    {
        $this->form->fill([]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('nom')
                ->label('Nom')
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Textarea::make('message')
                ->label('Message')
                ->required(),

            Checkbox::make('est_entreprise')
                ->label('Êtes-vous une entreprise?')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('show_enterprise_fields', $state);
                }),

            TextInput::make('telephone')
                ->label('Téléphone')
                ->required()
                ->visible(fn ($get) => $get('show_enterprise_fields')),

            TextInput::make('ville')
                ->label('Ville')
                ->required()
                ->visible(fn ($get) => $get('show_enterprise_fields')),

            TextInput::make('nom_entreprise')
                ->label('Nom de l\'entreprise')
                ->required()
                ->visible(fn ($get) => $get('show_enterprise_fields')),

            TextInput::make('num_patente')
                ->label('Numéro de patente')
                ->visible(fn ($get) => $get('show_enterprise_fields')),
        ];
    }

    public function submit()
    {
        $validatedData = $this->form->getState();

        $this->form->fill([]);

        $this->notify('success', 'Votre message a été envoyé avec succès.');

        // Send the email
        Mail::to('soufianjill@gmail.ma')->send(new ContactFormMail([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]));


        $this->reset();

        $this->notify('success', 'Message submitted successfully!');
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
