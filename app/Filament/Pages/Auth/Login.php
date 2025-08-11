<?php

namespace App\Filament\Pages\Auth;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'admin@filamentphp.com',
            'password' => 'demo.Filament@2021!',
            'remember' => true,
        ]);
    }
}
