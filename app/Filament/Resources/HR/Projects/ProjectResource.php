<?php

namespace App\Filament\Resources\HR\Projects;

use App\Enums\ProjectStatus;
use App\Filament\Resources\HR\Projects\Pages\CreateProject;
use App\Filament\Resources\HR\Projects\Pages\EditProject;
use App\Filament\Resources\HR\Projects\Pages\ListProjects;
use App\Filament\Resources\HR\Projects\Pages\ViewProject;
use App\Filament\Resources\HR\Projects\RelationManagers\TasksRelationManager;
use App\Filament\Resources\HR\Projects\RelationManagers\TimesheetsRelationManager;
use App\Filament\Resources\HR\Projects\Schemas\ProjectForm;
use App\Filament\Resources\HR\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\HR\Projects\Tables\ProjectsTable;
use App\Filament\Resources\HR\Projects\Widgets\ProjectStats;
use App\Models\HR\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string | UnitEnum | null $navigationGroup = 'Projects';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'projects';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
            TimesheetsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ProjectStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
            'view' => ViewProject::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', ProjectStatus::Active)->count();
    }

    /** @return Builder<Project> */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
