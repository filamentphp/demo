<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User management';

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return $record->id !== 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->dehydrated(fn ($record) => $record->id !== 1),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->dehydrated(fn ($record) => $record->id !== 1)
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->maxLength(255)
                    ->dehydrated(fn ($state, $record) => filled($state) && $record->id !== 1)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->disableAutocomplete(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->dehydrated(fn ($record) => $record->id !== 1),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->dehydrated(fn ($record) => $record->id !== 1)
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                ->maxLength(255)
                ->dehydrated(fn ($state, $record) => filled($state) && $record->id !== 1)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),

            Tables\Columns\TextColumn::make('email'),
        ];
    }
}
