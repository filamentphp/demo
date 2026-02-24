<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

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

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Planning => Heroicon::PencilSquare,
            self::Active => Heroicon::Play,
            self::OnHold => Heroicon::Pause,
            self::Completed => Heroicon::CheckCircle,
            self::Cancelled => Heroicon::XMark,
        };
    }
}
