<?php

namespace App\Enums;

use App\Filament\DemoIconAlias;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum TaskStatus: string implements HasColor, HasIcon, HasLabel
{
    case Backlog = 'backlog';

    case Todo = 'todo';

    case InProgress = 'in_progress';

    case InReview = 'in_review';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Backlog => 'Backlog',
            self::Todo => 'To Do',
            self::InProgress => 'In Progress',
            self::InReview => 'In Review',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Backlog => 'gray',
            self::Todo => 'info',
            self::InProgress => 'warning',
            self::InReview => 'primary',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Backlog => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_BACKLOG) ?? Heroicon::InboxStack,
            self::Todo => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_TODO) ?? Heroicon::QueueList,
            self::InProgress => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_IN_PROGRESS) ?? Heroicon::ArrowPath,
            self::InReview => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_IN_REVIEW) ?? Heroicon::Eye,
            self::Completed => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_COMPLETED) ?? Heroicon::CheckCircle,
            self::Cancelled => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_STATUS_CANCELLED) ?? Heroicon::XCircle,
        };
    }
}
