<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

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

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Backlog => Heroicon::InboxStack,
            self::Todo => Heroicon::QueueList,
            self::InProgress => Heroicon::ArrowPath,
            self::InReview => Heroicon::Eye,
            self::Completed => Heroicon::CheckCircle,
            self::Cancelled => Heroicon::XCircle,
        };
    }
}
