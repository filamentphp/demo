<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum LeaveStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';

    case Approved = 'approved';

    case Rejected = 'rejected';

    case Taken = 'taken';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Taken => 'Taken',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Taken => 'info',
            self::Cancelled => 'gray',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Pending => Heroicon::Clock,
            self::Approved => Heroicon::Check,
            self::Rejected => Heroicon::XMark,
            self::Taken => Heroicon::CheckBadge,
            self::Cancelled => Heroicon::XCircle,
        };
    }
}
