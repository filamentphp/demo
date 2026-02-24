<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

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

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Annual => Heroicon::Sun,
            self::Sick => Heroicon::Heart,
            self::Personal => Heroicon::User,
            self::Unpaid => Heroicon::Banknotes,
            self::Parental => Heroicon::Gift,
        };
    }
}
