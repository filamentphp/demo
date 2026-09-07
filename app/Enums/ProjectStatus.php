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

enum ProjectStatus: string implements HasColor, HasIcon, HasLabel
{
    case Planning = 'planning';

    case Active = 'active';

    case OnHold = 'on_hold';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Planning => 'Planning',
            self::Active => 'Active',
            self::OnHold => 'On Hold',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Planning => 'gray',
            self::Active => 'success',
            self::OnHold => 'warning',
            self::Completed => 'info',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Planning => FilamentIcon::resolve(DemoIconAlias::ENUMS_PROJECT_STATUS_PLANNING) ?? Heroicon::PencilSquare,
            self::Active => FilamentIcon::resolve(DemoIconAlias::ENUMS_PROJECT_STATUS_ACTIVE) ?? Heroicon::Play,
            self::OnHold => FilamentIcon::resolve(DemoIconAlias::ENUMS_PROJECT_STATUS_ON_HOLD) ?? Heroicon::Pause,
            self::Completed => FilamentIcon::resolve(DemoIconAlias::ENUMS_PROJECT_STATUS_COMPLETED) ?? Heroicon::CheckCircle,
            self::Cancelled => FilamentIcon::resolve(DemoIconAlias::ENUMS_PROJECT_STATUS_CANCELLED) ?? Heroicon::XMark,
        };
    }
}
