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

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Low => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_PRIORITY_LOW) ?? Heroicon::ChevronDown,
            self::Medium => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_PRIORITY_MEDIUM) ?? Heroicon::Minus,
            self::High => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_PRIORITY_HIGH) ?? Heroicon::ChevronUp,
            self::Critical => FilamentIcon::resolve(DemoIconAlias::ENUMS_TASK_PRIORITY_CRITICAL) ?? Heroicon::Fire,
        };
    }
}
