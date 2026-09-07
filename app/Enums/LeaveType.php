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

enum LeaveType: string implements HasColor, HasIcon, HasLabel
{
    case Annual = 'annual';

    case Sick = 'sick';

    case Personal = 'personal';

    case Unpaid = 'unpaid';

    case Parental = 'parental';

    public function getLabel(): string
    {
        return match ($this) {
            self::Annual => 'Annual Leave',
            self::Sick => 'Sick Leave',
            self::Personal => 'Personal Leave',
            self::Unpaid => 'Unpaid Leave',
            self::Parental => 'Parental Leave',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Annual => 'success',
            self::Sick => 'danger',
            self::Personal => 'info',
            self::Unpaid => 'warning',
            self::Parental => 'primary',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Annual => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_TYPE_ANNUAL) ?? Heroicon::Sun,
            self::Sick => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_TYPE_SICK) ?? Heroicon::Heart,
            self::Personal => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_TYPE_PERSONAL) ?? Heroicon::User,
            self::Unpaid => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_TYPE_UNPAID) ?? Heroicon::Banknotes,
            self::Parental => FilamentIcon::resolve(DemoIconAlias::ENUMS_LEAVE_TYPE_PARENTAL) ?? Heroicon::Gift,
        };
    }
}
