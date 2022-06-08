<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class UsersRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    protected function canDeleteAny(): bool
    {
        return UserResource::canDeleteAny();
    }

    protected function canDelete(Model $record): bool
    {
        return UserResource::canDelete($record);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(UserResource::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UserResource::getTableColumns())
            ->filters([
                //
            ]);
    }
}
