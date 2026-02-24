<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum TaskPriority: string implements HasColor, HasIcon, HasLabel
{
    case Low = 'low';

    case Medium = 'medium';

    case High = 'high';

    case Critical = 'critical';

    public function getLabel(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Critical => 'Critical',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Low => 'gray',
            self::Medium => 'info',
            self::High => 'warning',
            self::Critical => 'danger',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Low => Heroicon::ChevronDown,
            self::Medium => Heroicon::Minus,
            self::High => Heroicon::ChevronUp,
            self::Critical => Heroicon::Fire,
        };
    }
}
