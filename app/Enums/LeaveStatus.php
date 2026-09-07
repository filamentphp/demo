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

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Pending => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_STATUS_PENDING) ?? Heroicon::Clock,
            self::Approved => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_STATUS_APPROVED) ?? Heroicon::Check,
            self::Rejected => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_STATUS_REJECTED) ?? Heroicon::XMark,
            self::Taken => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_STATUS_TAKEN) ?? Heroicon::CheckBadge,
            self::Cancelled => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_STATUS_CANCELLED) ?? Heroicon::XCircle,
        };
    }
}
