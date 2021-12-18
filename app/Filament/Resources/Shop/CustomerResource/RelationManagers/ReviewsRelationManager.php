<?php

namespace App\Filament\Resources\Shop\CustomerResource\RelationManagers;

use App\Filament\Resources\Shop\ReviewResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;

class ReviewsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ReviewResource::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ReviewResource::getTableColumns())
            ->filters([
                //
            ]);
    }
}
