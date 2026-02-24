<?php

namespace App\Filament\Resources\HR\Tasks;

use App\Enums\TaskStatus;
use App\Filament\Resources\HR\Tasks\Pages\CreateTask;
use App\Filament\Resources\HR\Tasks\Pages\EditTask;
use App\Filament\Resources\HR\Tasks\Pages\ListTasks;
use App\Filament\Resources\HR\Tasks\Schemas\TaskForm;
use App\Filament\Resources\HR\Tasks\Tables\TasksTable;
use App\Models\HR\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string | UnitEnum | null $navigationGroup = 'Projects';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'tasks';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', TaskStatus::InProgress)->count();
    }
}
