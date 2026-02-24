<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum ExpenseCategory: string implements HasColor, HasIcon, HasLabel
{
    case Travel = 'travel';

    case Meals = 'meals';

    case Supplies = 'supplies';

    case Equipment = 'equipment';

    case Software = 'software';

    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Travel => 'Travel',
            self::Meals => 'Meals',
            self::Supplies => 'Supplies',
            self::Equipment => 'Equipment',
            self::Software => 'Software',
            self::Other => 'Other',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Travel => 'info',
            self::Meals => 'success',
            self::Supplies => 'warning',
            self::Equipment => 'primary',
            self::Software, self::Other => 'gray',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::Travel => Heroicon::GlobeAlt,
            self::Meals => Heroicon::Cake,
            self::Supplies => Heroicon::ShoppingCart,
            self::Equipment => Heroicon::WrenchScrewdriver,
            self::Software => Heroicon::ComputerDesktop,
            self::Other => Heroicon::EllipsisHorizontal,
        };
    }
}
