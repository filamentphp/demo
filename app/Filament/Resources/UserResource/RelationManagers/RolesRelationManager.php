<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\RoleResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class RolesRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(RoleResource::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(RoleResource::getTableColumns())
            ->filters([
                //
            ]);
    }
}
